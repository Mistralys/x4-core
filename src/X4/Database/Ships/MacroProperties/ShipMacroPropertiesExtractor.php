<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

use DOMElement;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipPurposes;
use Mistralys\X4\Database\Ships\ShipSizes;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;

/**
 * Reads the ship properties that are not part of the core ship stats
 * from the macro file of a single ship: steering curve, hangar, software,
 * modifiers, names, default loadout and more.
 *
 * Follows the per-item macro reader pattern of `ModuleMacroExtractor`:
 * {@see \Mistralys\X4\Database\Ships\ShipsExtractor} stays the orchestrator
 * and merges the result of {@see self::extract()} into the ship record.
 *
 * ## Own macro first, alias second
 *
 * Values are read from the ship's own macro first, and from the macro
 * the `alias=` attribute points to second (attribute by attribute, like
 * the core ship stats). Two exceptions inspect only the own file:
 * {@see ShipDef::KEY_HAS_OWN_ACC_FACTORS} (the mod's `<replace>` versus
 * `<add>` decision depends on the element existing there), and
 * {@see ShipDef::KEY_ALIAS_MACRO_ID}, which is the alias target itself.
 *
 * All paths are read below `/macros/macro/properties`, so that elements
 * of the same name elsewhere in the file (for example the `<thruster macro>`
 * entries of a loadout) are never mistaken for ship properties.
 *
 * @package X4 Database
 * @subpackage Ships
 */
class ShipMacroPropertiesExtractor
{
    private const PROPERTIES_PATH = '/macros/macro/properties/';

    /**
     * Thruster `tags` value => ship size ID.
     * @var array<string,string>
     */
    private const THRUSTER_SIZES = array(
        'small' => ShipSizes::SIZE_S,
        'medium' => ShipSizes::SIZE_M,
        'large' => ShipSizes::SIZE_L,
        'extralarge' => ShipSizes::SIZE_XL,
    );

    /**
     * Loadout containers and their entries: container => element name => loadout item type.
     * The `<software>` container is the only one that identifies by `ware` instead of `macro`.
     *
     * @var array<string,array<string,string>>
     */
    private const LOADOUT_ENTRIES = array(
        'macros' => array(
            'engine' => ShipLoadoutItem::TYPE_ENGINE,
            'shield' => ShipLoadoutItem::TYPE_SHIELD,
            'turret' => ShipLoadoutItem::TYPE_TURRET,
            'weapon' => ShipLoadoutItem::TYPE_WEAPON,
        ),
        'groups' => array(
            'shields' => ShipLoadoutItem::TYPE_SHIELD,
            'turrets' => ShipLoadoutItem::TYPE_TURRET,
        ),
        'virtualmacros' => array(
            'thruster' => ShipLoadoutItem::TYPE_THRUSTER,
        ),
        'software' => array(
            'software' => ShipLoadoutItem::TYPE_SOFTWARE,
        ),
        'ammunition' => array(
            'ammunition' => ShipLoadoutItem::TYPE_AMMUNITION,
        ),
    );

    private DOMExtended $dom;
    private ?DOMExtended $aliasDom;
    private ?string $aliasMacroID;
    private string $dataSourceID;
    private string $shipID;
    private ShipMacroConnectionResolver $connectionResolver;

    /**
     * @param DOMExtended $dom The ship's own macro.
     * @param DOMExtended|null $aliasDom The macro that the own macro's `alias=` points to, if any.
     * @param string|null $aliasMacroID Name of the alias macro. Written to the record when not empty.
     * @param string $dataSourceID Data source ID for macro lookups.
     * @param string $shipID The ship ware ID, for log messages.
     * @param ShipMacroConnectionResolver|null $connectionResolver Defaults to a resolver on the macro index.
     */
    public function __construct(
        DOMExtended $dom,
        ?DOMExtended $aliasDom,
        ?string $aliasMacroID,
        string $dataSourceID,
        string $shipID,
        ?ShipMacroConnectionResolver $connectionResolver = null
    )
    {
        $this->dom = $dom;
        $this->aliasDom = $aliasDom;
        $this->aliasMacroID = !empty($aliasMacroID) ? $aliasMacroID : null;
        $this->dataSourceID = $dataSourceID;
        $this->shipID = $shipID;
        $this->connectionResolver = $connectionResolver ?? new ShipMacroConnectionResolver();
    }

    /**
     * Extracts the ship properties.
     *
     * Returns the keys of every macro property of the ship record
     * (everything but `macroID` and `cargoMacroID`, which the ships
     * extractor writes itself), keyed by the `ShipDef::KEY_*` constants,
     * in the same format as {@see ShipMacroProperties::toArray()}:
     * optional values are omitted when absent.
     *
     * @return array<string,mixed>
     */
    public function extract() : array
    {
        $properties = new ShipMacroProperties(
            aliasMacroID: $this->aliasMacroID,
            hasOwnAccFactors: $this->dom->byTagName('accfactors')->getFirst() !== null,
            steeringCurve: $this->extractSteeringCurve(),
            explosionDamage: $this->extractExplosionDamage(),
            purposeID: $this->extractPurpose(),
            thrusterSizeID: $this->extractThrusterSize(),
            iconID: (string)$this->attr('identification', 'icon'),
            secrecyLevel: $this->intAttr('secrecy', 'level'),
            droneCapacity: $this->intAttr('storage', 'unit') ?? 0,
            deployableCapacityOverride: $this->intAttr('storage', 'deployable'),
            countermeasureCapacityOverride: $this->intAttr('storage', 'countermeasure'),
            hangar: $this->extractHangar(),
            software: $this->extractSoftware(),
            travelDriveStability: $this->floatAttr('traveldrivestability', 'maxvalue'),
            gasGatherRate: $this->floatAttr('gatherrate', 'gas'),
            radarRangeOverride: $this->floatAttr('radar', 'range'),
            modifiers: new ShipModifiers(
                $this->floatAttr('modifiers/weapon', 'heat'),
                $this->floatAttr('modifiers/shield', 'capacity'),
                $this->floatAttr('modifiers/shield', 'rechargerate'),
                $this->floatAttr('modifiers/shield', 'rechargedelay')
            ),
            baseNameID: $this->attr('identification', 'basename'),
            variationNameID: $this->attr('identification', 'variation'),
            shortVariationNameID: $this->attr('identification', 'shortvariation'),
            capturable: $this->attr('capture', 'allow') !== '0',
            prestige: $this->intAttr('ship', 'prestige'),
            boostRechargeDelay: $this->floatAttr('boost', 'rechargedelay'),
            boostMinDuration: $this->floatAttr('boost', 'minduration'),
            threatScore: $this->intAttr('threatscore', 'value'),
            playerCoverable: $this->attr('ownership', 'playercoverable') === '1',
            defaultLoadout: $this->extractDefaultLoadout()
        );

        $record = $properties->toArray();

        // Written by ShipsExtractor, from the ware and the storage connection.
        unset($record[ShipDef::KEY_MACRO_ID]);

        return $record;
    }

    /**
     * The DOMs in lookup order: the ship's own macro, then the alias target.
     *
     * @return DOMExtended[]
     */
    private function getDOMs() : array
    {
        return $this->aliasDom !== null ? array($this->dom, $this->aliasDom) : array($this->dom);
    }

    private function findElement(DOMExtended $dom, string $path) : ?DOMElement
    {
        return $this->findElements($dom, $path)[0] ?? null;
    }

    /**
     * @param DOMExtended $dom
     * @param string $path Path below `/macros/macro/properties/`, may contain predicates.
     * @return DOMElement[]
     */
    private function findElements(DOMExtended $dom, string $path) : array
    {
        $result = array();
        $nodes = $dom->getXPath()->query(self::PROPERTIES_PATH.$path);

        foreach($nodes ?: array() as $node) {
            if($node instanceof DOMElement) {
                $result[] = $node;
            }
        }

        return $result;
    }

    /**
     * Reads an attribute of an element below `properties`: the own
     * macro first, then the alias target. An empty value counts as absent.
     */
    private function attr(string $path, string $attribute) : ?string
    {
        foreach($this->getDOMs() as $dom)
        {
            $element = $this->findElement($dom, $path);

            if($element !== null && $element->hasAttribute($attribute) && $element->getAttribute($attribute) !== '') {
                return $element->getAttribute($attribute);
            }
        }

        return null;
    }

    private function intAttr(string $path, string $attribute) : ?int
    {
        $value = $this->attr($path, $attribute);

        return $value !== null ? (int)$value : null;
    }

    private function floatAttr(string $path, string $attribute) : ?float
    {
        $value = $this->attr($path, $attribute);

        return $value !== null ? (float)$value : null;
    }

    private function extractSteeringCurve() : ShipSteeringCurve
    {
        foreach($this->getDOMs() as $dom)
        {
            $points = array();
            foreach($this->findElements($dom, 'steeringcurve/point') as $node) {
                $points[] = new ShipSteeringCurvePoint(
                    (float)$node->getAttribute('position'),
                    (float)$node->getAttribute('value')
                );
            }

            if(!empty($points)) {
                return new ShipSteeringCurve($points);
            }
        }

        return new ShipSteeringCurve();
    }

    private function extractExplosionDamage() : ?ShipExplosionDamage
    {
        $damage = new ShipExplosionDamage(
            $this->floatAttr('explosiondamage', 'value'),
            $this->floatAttr('explosiondamage', 'shield'),
            $this->floatAttr('explosiondamage', 'shielddisruption')
        );

        return $damage->isEmpty() ? null : $damage;
    }

    private function extractPurpose() : string
    {
        $purpose = (string)$this->attr('purpose', 'primary');

        if($purpose !== '' && !ShipPurposes::idExists($purpose)) {
            // Purpose is informational, so unlike ship classes it does not abort the extraction.
            Console::line1('Ship [%s] | WARNING | Unknown purpose [%s].', $this->shipID, $purpose);
        }

        return $purpose;
    }

    private function extractThrusterSize() : string
    {
        $tags = (string)$this->attr('thruster', 'tags');

        if($tags === '') {
            return '';
        }

        foreach(preg_split('/\s+/', trim($tags)) ?: array() as $tag) {
            if(isset(self::THRUSTER_SIZES[$tag])) {
                return self::THRUSTER_SIZES[$tag];
            }
        }

        Console::line1('Ship [%s] | WARNING | Unknown thruster size tag [%s].', $this->shipID, $tags);

        return $tags;
    }

    /**
     * Sums the capacity of the internal hangar bays per dock size.
     *
     * A bay is a connected macro with a `<dock storage="1">` element. Bays
     * flagged `hidden="1"` are skipped: they are not part of the hangar a
     * player can see or use (one hidden bay exists, in the game's surface
     * elements, and no ship connects to it today). A bay that lists several
     * sizes is added to each of them, which over-counts it when the sizes
     * are summed, and logs a WARNING.
     *
     * Uses the own connections, falling back to the alias target's
     * connections when the own macro yields no bays.
     */
    private function extractHangar() : ShipHangar
    {
        foreach($this->getDOMs() as $dom)
        {
            $capacities = $this->collectHangarCapacities($dom);

            if(!empty($capacities)) {
                return new ShipHangar($capacities);
            }
        }

        return new ShipHangar();
    }

    /**
     * @return array<string,int> Capacity by size ID, in the order of the ship sizes.
     */
    private function collectHangarCapacities(DOMExtended $dom) : array
    {
        $capacities = array();

        foreach($this->connectionResolver->resolve($dom, $this->dataSourceID) as $connection)
        {
            $bayDom = $connection->macroDef->getDOM();
            $dock = $this->findElement($bayDom, 'dock');

            if($dock === null || $dock->getAttribute('storage') !== '1' || $dock->getAttribute('hidden') === '1') {
                continue;
            }

            $sizes = $this->parseDockSizes($bayDom);

            if(empty($sizes)) {
                Console::line1('Ship [%s] | WARNING | Hangar macro [%s] has no dock size.', $this->shipID, $connection->macroID);
                continue;
            }

            if(count($sizes) > 1) {
                Console::line1(
                    'Ship [%s] | WARNING | Hangar macro [%s] lists several sizes [%s]; its capacity is added to each.',
                    $this->shipID,
                    $connection->macroID,
                    implode(' ', $sizes)
                );
            }

            foreach($sizes as $size) {
                $capacities[$size] = ($capacities[$size] ?? 0) + (int)$dock->getAttribute('capacity');
            }
        }

        $sorted = array();
        foreach(array(ShipSizes::SIZE_XS, ShipSizes::SIZE_S, ShipSizes::SIZE_M, ShipSizes::SIZE_L, ShipSizes::SIZE_XL) as $size) {
            if(isset($capacities[$size])) {
                $sorted[$size] = $capacities[$size];
            }
        }

        return $sorted;
    }

    /**
     * @return string[] Size IDs from `<docksize tags="dock_s dock_m">`.
     */
    private function parseDockSizes(DOMExtended $bayDom) : array
    {
        $docksize = $this->findElement($bayDom, 'docksize');
        $sizes = array();

        if($docksize !== null && preg_match_all('/dock_(xs|s|m|l|xl)\b/', $docksize->getAttribute('tags'), $matches)) {
            $sizes = array_values(array_unique($matches[1]));
        }

        return $sizes;
    }

    private function extractSoftware() : ShipSoftware
    {
        foreach($this->getDOMs() as $dom)
        {
            $nodes = $this->findElements($dom, 'software/software');

            if(empty($nodes)) {
                continue;
            }

            $default = array();
            $optional = array();
            $wares = WareDefs::getInstance();

            foreach($nodes as $node)
            {
                $wareID = $node->getAttribute('ware');

                if($wareID === '') {
                    continue;
                }

                if(!$wares->idExists($wareID)) {
                    Console::line1('Ship [%s] | NOTICE | Software ware [%s] does not exist in the ware data.', $this->shipID, $wareID);
                }

                if($node->getAttribute('default') === '1') {
                    $default[] = $wareID;
                }

                if($node->getAttribute('compatible') === '1') {
                    $optional[] = $wareID;
                }
            }

            return new ShipSoftware($default, $optional);
        }

        return new ShipSoftware();
    }

    /**
     * Reads `<loadouts><loadout id="default">` (own macro, else alias target)
     * and flattens it into one list of what gets installed.
     *
     * Counts: a single `<macros>` entry and a software entry count 1, a
     * group entry counts its `exact` (the `max` when it only has a
     * `min`/`max` range), an ammunition entry counts its `max`. Entries with
     * the same type, macro and `optional` flag are merged by summing their
     * counts, in first-seen order. Connection paths and group names are
     * dropped. No ware lookup happens here.
     */
    private function extractDefaultLoadout() : ?ShipLoadout
    {
        foreach($this->getDOMs() as $dom)
        {
            $loadout = $this->findElement($dom, 'loadouts/loadout[@id="default"]');

            if($loadout !== null) {
                return $this->flattenLoadout($loadout);
            }
        }

        return null;
    }

    private function flattenLoadout(DOMElement $loadout) : ShipLoadout
    {
        /** @var array<string,array{type:string,macroID:string,count:int,optional:bool}> $merged */
        $merged = array();

        foreach($loadout->childNodes as $container)
        {
            if(!$container instanceof DOMElement) {
                continue;
            }

            $entryTypes = self::LOADOUT_ENTRIES[$container->nodeName] ?? null;

            if($entryTypes === null) {
                Console::line1('Ship [%s] | WARNING | Unknown loadout element [%s] skipped.', $this->shipID, $container->nodeName);
                continue;
            }

            foreach($container->childNodes as $entry)
            {
                if(!$entry instanceof DOMElement) {
                    continue;
                }

                if(!isset($entryTypes[$entry->nodeName])) {
                    Console::line1('Ship [%s] | WARNING | Unknown loadout element [%s/%s] skipped.', $this->shipID, $container->nodeName, $entry->nodeName);
                    continue;
                }

                $type = $entryTypes[$entry->nodeName];
                $macroID = $entry->getAttribute($type === ShipLoadoutItem::TYPE_SOFTWARE ? 'ware' : 'macro');

                if($macroID === '') {
                    Console::line1('Ship [%s] | WARNING | Loadout element [%s/%s] has no macro, skipped.', $this->shipID, $container->nodeName, $entry->nodeName);
                    continue;
                }

                $optional = $entry->getAttribute('optional') === '1';
                $key = $type.'|'.$macroID.'|'.($optional ? '1' : '0');
                $count = $this->resolveLoadoutCount($container->nodeName, $entry);

                if(isset($merged[$key])) {
                    $merged[$key][ShipLoadoutItem::KEY_COUNT] += $count;
                    continue;
                }

                $merged[$key] = array(
                    ShipLoadoutItem::KEY_TYPE => $type,
                    ShipLoadoutItem::KEY_MACRO_ID => $macroID,
                    ShipLoadoutItem::KEY_COUNT => $count,
                    ShipLoadoutItem::KEY_OPTIONAL => $optional,
                );
            }
        }

        return ShipLoadout::fromArray(array_values($merged));
    }

    private function resolveLoadoutCount(string $container, DOMElement $entry) : int
    {
        if($container === 'groups') {
            foreach(array('exact', 'max') as $attribute) {
                if($entry->getAttribute($attribute) !== '') {
                    return (int)$entry->getAttribute($attribute);
                }
            }
        }

        if($container === 'ammunition' && $entry->getAttribute('max') !== '') {
            return (int)$entry->getAttribute('max');
        }

        return 1;
    }
}
