<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\ConvertHelper;
use AppUtils\FileHelper\FileInfo;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\ExtractedData\DataFolder;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Goes through the ware XML files from all data folders and extracts
 * them into a single collection.
 *
 * ## Two passes
 *
 * **Pass 1** reads the `libraries/wares.xml` file of every data folder
 * and collects the `<ware id>` definitions, including the price,
 * productions, licence, illegal factions, buy restriction, description,
 * volume and transport type. The DLC files are `<diff>` documents, so
 * this also picks up the wares that DLCs add through `<add sel="/wares">`.
 *
 * **Pass 2** applies the DLC `<add sel="/wares/ware[@id='X']">` patches
 * (read by {@see WaresPatchReader}) to the wares of pass 1: owners and
 * illegal factions are merged (sorted, de-duplicated) and productions
 * are appended, in data folder order. Patches for wares that pass 1 did
 * not keep are skipped with a notice.
 *
 * Owner, illegal faction and production parsing is shared by both passes.
 *
 * @package X4 Database
 * @subpackage Wares
 */
class WaresExtractor
{
    public const TAG_SHIP = 'ship';
    public const TAG_MODULE = 'module';
    public const TAG_RESEARCH = 'research';
    public const TAG_PAINTMOD = 'paintmod';
    public const TAG_EQUIPMENT_MOD = 'equipmentmod';
    public const TAG_EQUIPMENT_MOD_PART = 'equipmentmodpart';
    public const TAG_MISSION_ONLY = 'missiononly';
    public const TAG_SEMINAR = 'seminar';
    public const TAG_CRAFTING = 'crafting';
    public const TAG_EQUIPMENT = 'equipment';
    public const TAG_INVENTORY = 'inventory';
    public const TAG_WORKUNIT = 'workunit';
    public const TAG_DEPRECATED = 'deprecated';
    public const TAG_CONDENSATE = 'condensate';

    /**
     * Child elements of a ware patch that pass 2 handles.
     * @var string[]
     */
    private const PATCH_CHILD_TAGS = array('owner', 'illegal', 'production');

    private DataFolders $dataFolders;
    private Language $language;
    private array $wares = array();

    public function __construct(DataFolders $dataFolders)
    {
        $this->dataFolders = $dataFolders;
        $this->language = Languages::getInstance()->getEnglish();
    }

    public function extract() : void
    {
        $this->extractWares();
        $this->applyWarePatches();
        $this->analyzeGroups();
        $this->collectGroups();

        WareDefs::getInstance()
            ->getDataFile()
            ->putData($this->wares);
    }

    /**
     * @var array<string,string>
     */
    private array $autoTagGroups = array(
        self::TAG_SHIP => WareGroups::GROUP_SHIPS,
        self::TAG_RESEARCH => WareGroups::GROUP_RESEARCH,
        self::TAG_PAINTMOD => WareGroups::GROUP_PAINT_MODS,
        self::TAG_EQUIPMENT_MOD => WareGroups::GROUP_EQUIPMENT_MODS,
        self::TAG_MODULE => WareGroups::GROUP_MODULES,
        self::TAG_EQUIPMENT_MOD_PART => WareGroups::GROUP_EQUIPMENT_MOD_PARTS,
        self::TAG_MISSION_ONLY => WareGroups::GROUP_MISSION_ITEMS,
        self::TAG_SEMINAR => WareGroups::GROUP_SEMINARS,
        self::TAG_CRAFTING => WareGroups::GROUP_CRAFTING,
        self::TAG_EQUIPMENT => WareGroups::GROUP_EQUIPMENT,
        self::TAG_INVENTORY => WareGroups::GROUP_INVENTORY_ITEMS,
        self::TAG_CONDENSATE => WareGroups::GROUP_CONDENSATE,
    );

    private function analyzeGroups() : void
    {
        Console::header('Analyzing groups...');

        foreach ($this->wares as $idx => $ware)
        {
            if(!empty($ware[WareDef::KEY_GROUP])) {
                continue;
            }

            $group = WareGroups::GROUP_OTHER;
            $wareTags = $ware[WareDef::KEY_TAGS] ?? [];

            foreach($this->autoTagGroups as $tag => $autoGroup) {
                if(in_array($tag, $wareTags, true)) {
                    $group = $autoGroup;
                    break;
                }
            }

            if($group === WareGroups::GROUP_OTHER && str_starts_with($ware[WareDef::KEY_WARE_ID], 'ship_')) {
                $group = WareGroups::GROUP_SHIPS;
            }

            if($group === WareGroups::GROUP_OTHER && str_starts_with($ware[WareDef::KEY_WARE_ID], 'module_')) {
                $group = WareGroups::GROUP_MODULES;
            }

            if($group === WareGroups::GROUP_OTHER) {
                Console::line1('Ware [%s] | NOTICE | No group detected.', $ware[WareDef::KEY_WARE_ID], $group);
            }

            // Some ships are grouped as "ships" but do not have the "ship" tag.
            if($group === 'ships' && !in_array(self::TAG_SHIP, $wareTags, true)) {
                if (!isset($this->wares[$idx][WareDef::KEY_TAGS])) {
                    $this->wares[$idx][WareDef::KEY_TAGS] = [];
                }
                $this->wares[$idx][WareDef::KEY_TAGS][] = self::TAG_SHIP;
            }

            $this->wares[$idx][WareDef::KEY_GROUP] = $group;
        }

        Console::nl();
    }

    private function collectGroups() : void
    {
        Console::header('Collecting groups...');

        foreach ($this->wares as $ware) {
            if (empty($ware[WareDef::KEY_GROUP])) {
                continue;
            }

            if (!in_array($ware[WareDef::KEY_GROUP], $this->groups, true)) {
                $this->groups[] = $ware[WareDef::KEY_GROUP];
            }
        }

        sort($this->groups);

        $knownGroups = WareGroups::getInstance();

        foreach($this->groups as $group) {
            if(!$knownGroups->idExists($group)) {
                Console::line1('Group [%s] | WARNING | Unknown group.', $group);
            }
        }

        Console::line1('Found [%d] groups in total.', count($this->groups));
        Console::nl();
    }

    private function extractWares() : void
    {
        foreach($this->dataFolders->getAll() as $dataFolder) {
            $this->extractDataFolder($dataFolder);
        }

        usort($this->wares, static function(array $a, array $b) : int {
            return strnatcasecmp($a[WareDef::KEY_WARE_ID], $b[WareDef::KEY_WARE_ID]);
        });

        Console::line1('Found [%d] wares in total.', count($this->wares));
        Console::nl();
    }

    private function extractDataFolder(DataFolder $dataFolder) : void
    {
        Console::header('Processing data folder [%s]', $dataFolder->getLabel());

        $waresFile = FileInfo::factory($dataFolder->getPath().'/libraries/wares.xml');

        if(!$waresFile->exists()) {
            Console::line1('SKIP | No wares file found.');
            return;
        }

        Console::line1('Processing wares file...');

        foreach(DOMExtended::createFromFile($waresFile)->byTagName('ware')->getAll() as $el) {
            $this->processWare($el, $dataFolder);
        }

        Console::line1('Found [%d] wares.', count($this->wares));
        Console::nl();
    }

    private array $groups = array();

    /**
     * IDs of the wares that pass 1 did not keep, with the reason.
     * @var array<string,string>
     */
    private array $skippedWareIDs = array();

    /**
     * @var string[]
     */
    private array $excludeTags = array(
        self::TAG_WORKUNIT,
        self::TAG_DEPRECATED
    );

    private function processWare(ElementExtended $wareElement, DataFolder $dataFolder) : void
    {
        $id = $wareElement->getAttribute('id');

        // Skip the `<ware ware="energycells">` elements that do not have an ID and are found
        // in the `<production>` elements.
        if(empty($id)) {
            return;
        }

        $tags = ConvertHelper::explodeTrim(' ', $wareElement->getAttribute('tags'));

        sort($tags);

        foreach($this->excludeTags as $excludeTag) {
            if(in_array($excludeTag, $tags, true)) {
                Console::line2('Ware [%s] | SKIP | Tag [%s] is excluded.', $id, $excludeTag);
                $this->skippedWareIDs[$id] = sprintf('tag [%s] is excluded', $excludeTag);
                return;
            }
        }

        $unknownFactionIDs = array();
        $factionIDs = $this->parseOwners($wareElement, $id, $unknownFactionIDs);

        // A ware with an unknown owner is dropped entirely.
        if(!empty($unknownFactionIDs)) {
            $this->skippedWareIDs[$id] = 'has an unknown owner faction';
            return;
        }

        $componentID = '';
        $componentEl = $wareElement->findChildren()->byTagName('component')->getFirst();
        if($componentEl !== null) {
            $componentID = $componentEl->getAttribute('ref');
        }
        
        $xmlGroup = $wareElement->getAttribute('group');
        $component = ['size' => '', 'tags' => []];
        $size = '';

        if(!empty($componentID) && ($xmlGroup === 'ships' || $xmlGroup === 'modules' || in_array(self::TAG_EQUIPMENT, $tags, true) || in_array(self::TAG_SHIP, $tags, true))) {
             $componentDetails = $this->extractComponentDetails($componentID, $dataFolder);
             $size = $componentDetails['size'];
             $component = ['tags' => $componentDetails['tags']];
        }

        $variantID = (string)VariantID::resolveWareVariantID($id);

        $ware = array(
            WareDef::KEY_WARE_ID => $id,
            WareDef::KEY_LABEL => $this->language->ts($wareElement->getAttribute('name')),
            WareDef::KEY_GROUP => $xmlGroup,
            WareDef::KEY_DATA_SOURCE_ID => $dataFolder->getID(),
        );
        
        // Only include size if not empty
        if (!empty($size)) {
            $ware[WareDef::KEY_SIZE] = $size;
        }
        
        // Only include tags if not empty
        if (!empty($tags)) {
            $ware[WareDef::KEY_TAGS] = $tags;
        }
        
        // Only include macroID if not empty
        if (!empty($componentID)) {
            $ware[WareDef::KEY_MACRO_ID] = $componentID;
        }
        
        // Only include variantID if not default "::"
        if ($variantID !== '::') {
            $ware[WareDef::KEY_VARIANT_ID] = $variantID;
        }
        
        // Only include factions if not empty
        if (!empty($factionIDs)) {
            $ware[WareDef::KEY_FACTIONS] = $factionIDs;
        }
        
        // Only include component if tags is not empty
        if (!empty($component['tags'])) {
            $ware[WareDef::KEY_COMPONENT] = $component;
        }

        $this->addPriceData($ware, $wareElement, $id);

        $productions = $this->parseProductions($wareElement, $id);
        if(!empty($productions)) {
            $ware[WareDef::KEY_PRODUCTIONS] = $productions;
        }

        $restrictionEl = $this->getChildren($wareElement, 'restriction')[0] ?? null;
        if($restrictionEl !== null) {
            $licenceID = (string)$restrictionEl->getAttribute('licence');
            if($licenceID !== '') {
                $ware[WareDef::KEY_LICENCE_ID] = $licenceID;
            }
        }

        $descriptionID = (string)$wareElement->getAttribute('description');
        if($descriptionID !== '') {
            $ware[WareDef::KEY_DESCRIPTION_ID] = $descriptionID;
        }

        $illegalFactionIDs = $this->parseIllegalFactions($wareElement, $id);
        if(!empty($illegalFactionIDs)) {
            $ware[WareDef::KEY_ILLEGAL_FACTION_IDS] = $illegalFactionIDs;
        }

        if($restrictionEl !== null && $restrictionEl->getAttribute('buy') === '1') {
            $ware[WareDef::KEY_BUY_RESTRICTED] = true;
        }

        $this->addVolume($ware, $wareElement, $id);
        $this->addTransportType($ware, $wareElement, $id);

        $this->wares[] = $ware;
    }

    /**
     * Gets the direct child elements with the given tag name.
     * (`byTagName()` searches all descendants.)
     *
     * @return ElementExtended[]
     */
    private function getChildren(ElementExtended $parent, string $tagName) : array
    {
        return array_values(array_filter(
            $parent->getChildren(),
            static fn(ElementExtended $el) : bool => $el->getDOMElement()->tagName === $tagName
        ));
    }

    /**
     * Adds the three flat `price*` keys from `<price min average max>`.
     *
     * @param array<string,mixed> $ware
     */
    private function addPriceData(array &$ware, ElementExtended $wareElement, string $wareID) : void
    {
        $priceEl = $this->getChildren($wareElement, 'price')[0] ?? null;
        if($priceEl === null) {
            return;
        }

        $map = array(
            WareDef::KEY_PRICE_MIN => 'min',
            WareDef::KEY_PRICE_AVERAGE => 'average',
            WareDef::KEY_PRICE_MAX => 'max',
        );

        $values = array();
        foreach($map as $key => $attribute) {
            $value = (string)$priceEl->getAttribute($attribute);
            if(!preg_match('/\A\d+\z/', $value)) {
                Console::line2('Ware [%s] | WARNING | Invalid price attribute [%s]: [%s].', $wareID, $attribute, $value);
                return;
            }

            $values[$key] = (int)$value;
        }

        foreach($values as $key => $value) {
            $ware[$key] = $value;
        }
    }

    /**
     * @param array<string,mixed> $ware
     */
    private function addVolume(array &$ware, ElementExtended $wareElement, string $wareID) : void
    {
        if(!$wareElement->hasAttribute('volume')) {
            return;
        }

        $volume = (string)$wareElement->getAttribute('volume');

        if(!preg_match('/\A\d+\z/', $volume)) {
            Console::line2('Ware [%s] | WARNING | Non-numeric volume [%s].', $wareID, $volume);
            return;
        }

        $ware[WareDef::KEY_VOLUME] = (int)$volume;
    }

    /**
     * @param array<string,mixed> $ware
     */
    private function addTransportType(array &$ware, ElementExtended $wareElement, string $wareID) : void
    {
        $transport = (string)$wareElement->getAttribute('transport');

        if($transport === '') {
            return;
        }

        if(!WareTransportTypes::idExists($transport)) {
            Console::line2('Ware [%s] | WARNING | Unknown transport type [%s].', $wareID, $transport);
        }

        $ware[WareDef::KEY_TRANSPORT_TYPE_ID] = $transport;
    }

    /**
     * Reads the `<owner faction>` children of a ware or patch element.
     * Unknown factions are logged and collected in `$unknownFactionIDs`;
     * it is up to the caller what to do with them.
     *
     * @param string[] $unknownFactionIDs
     * @return string[] Sorted, unique IDs of the known factions.
     */
    private function parseOwners(ElementExtended $parent, string $wareID, array &$unknownFactionIDs) : array
    {
        $factionDefs = FactionDefs::getInstance();
        $result = array();

        foreach($this->getChildren($parent, 'owner') as $ownerElement) {
            $factionID = (string)$ownerElement->getAttribute('faction');
            if($factionID === '') {
                continue;
            }

            if($factionDefs->idExists($factionID)) {
                $result[] = $factionID;
            } else {
                Console::line2('Ware [%s] | WARNING | Faction [%s] does not exist.', $wareID, $factionID);
                $unknownFactionIDs[] = $factionID;
            }
        }

        $result = array_values(array_unique($result));
        sort($result);

        return $result;
    }

    /**
     * Reads the `<illegal factions="a b">` children of a ware or patch
     * element. Unknown factions are logged and skipped.
     *
     * @return string[] Sorted, unique faction IDs.
     */
    private function parseIllegalFactions(ElementExtended $parent, string $wareID) : array
    {
        $factionDefs = FactionDefs::getInstance();
        $result = array();

        foreach($this->getChildren($parent, 'illegal') as $illegalElement) {
            foreach(ConvertHelper::explodeTrim(' ', (string)$illegalElement->getAttribute('factions')) as $factionID) {
                if($factionDefs->idExists($factionID)) {
                    $result[] = $factionID;
                } else {
                    Console::line2('Ware [%s] | WARNING | Illegal faction [%s] does not exist.', $wareID, $factionID);
                }
            }
        }

        $result = array_values(array_unique($result));
        sort($result);

        return $result;
    }

    /**
     * Reads the `<production>` children of a ware or patch element,
     * in document order.
     *
     * @return array<int,array<string,mixed>> Records in the format of {@see WareProduction::toArray()}.
     */
    private function parseProductions(ElementExtended $parent, string $wareID) : array
    {
        $result = array();

        foreach($this->getChildren($parent, 'production') as $productionEl)
        {
            $resources = array();
            foreach($this->getChildren($productionEl, 'primary') as $primaryEl) {
                foreach($this->getChildren($primaryEl, 'ware') as $resourceEl) {
                    $resourceID = (string)$resourceEl->getAttribute('ware');
                    if($resourceID === '') {
                        continue;
                    }

                    $resources[$resourceID] = ($resources[$resourceID] ?? 0) + (int)$resourceEl->getAttribute('amount');
                }
            }

            $effects = array();
            foreach($this->getChildren($productionEl, 'effects') as $effectsEl) {
                foreach($this->getChildren($effectsEl, 'effect') as $effectEl) {
                    $type = (string)$effectEl->getAttribute('type');
                    if($type !== '') {
                        $effects[$type] = (float)$effectEl->getAttribute('product');
                    }
                }
            }

            $methodID = (string)$productionEl->getAttribute('method');

            if(!ProductionMethodDefs::getInstance()->idExists($methodID)) {
                Console::line2('Ware [%s] | NOTICE | Unknown production method [%s].', $wareID, $methodID);
            }

            $result[] = array(
                WareProduction::KEY_METHOD_ID => $methodID,
                WareProduction::KEY_DURATION => (float)$productionEl->getAttribute('time'),
                WareProduction::KEY_QUANTITY => (int)$productionEl->getAttribute('amount'),
                WareProduction::KEY_RESOURCES => $resources,
                WareProduction::KEY_EFFECTS => $effects,
            );
        }

        return $result;
    }

    /**
     * Pass 2: applies the DLC `<add sel="/wares/ware[@id]">` patches to
     * the wares collected in pass 1.
     */
    private function applyWarePatches() : void
    {
        Console::header('Applying ware patches...');

        $index = array();
        foreach($this->wares as $idx => $ware) {
            $index[$ware[WareDef::KEY_WARE_ID]] ??= $idx;
        }

        $patched = 0;

        foreach((new WaresPatchReader($this->dataFolders))->getWarePatches() as $patch)
        {
            $wareID = (string)$patch->targetWareID;

            if(!isset($index[$wareID])) {
                Console::line2(
                    'Patch [%s] | NOTICE | Ware [%s] is %s, patch skipped.',
                    $patch->dataFolderID,
                    $wareID,
                    isset($this->skippedWareIDs[$wareID]) ? 'not kept ('.$this->skippedWareIDs[$wareID].')' : 'unknown'
                );
                continue;
            }

            foreach($patch->element->getChildren() as $child) {
                $name = $child->getDOMElement()->tagName;
                if(!in_array($name, self::PATCH_CHILD_TAGS, true)) {
                    Console::line2('Patch [%s] | Ware [%s] | NOTICE | Unknown child element [%s] skipped.', $patch->dataFolderID, $wareID, $name);
                }
            }

            $this->applyWarePatch($index[$wareID], $patch->element, $wareID);
            $patched++;
        }

        Console::line1('Applied [%d] ware patches.', $patched);
        Console::nl();
    }

    private function applyWarePatch(int $idx, ElementExtended $patchElement, string $wareID) : void
    {
        // Unknown factions are logged by the parser, and only that faction is skipped.
        $unknownFactionIDs = array();
        $owners = $this->parseOwners($patchElement, $wareID, $unknownFactionIDs);

        if(!empty($owners)) {
            $merged = array_values(array_unique(array_merge($this->wares[$idx][WareDef::KEY_FACTIONS] ?? array(), $owners)));
            sort($merged);
            $this->wares[$idx][WareDef::KEY_FACTIONS] = $merged;
        }

        $illegal = $this->parseIllegalFactions($patchElement, $wareID);

        if(!empty($illegal)) {
            $merged = array_values(array_unique(array_merge($this->wares[$idx][WareDef::KEY_ILLEGAL_FACTION_IDS] ?? array(), $illegal)));
            sort($merged);
            $this->wares[$idx][WareDef::KEY_ILLEGAL_FACTION_IDS] = $merged;
        }

        $productions = $this->parseProductions($patchElement, $wareID);

        if(!empty($productions)) {
            $this->wares[$idx][WareDef::KEY_PRODUCTIONS] = array_merge(
                $this->wares[$idx][WareDef::KEY_PRODUCTIONS] ?? array(),
                $productions
            );
        }
    }

    /**
     * @return array{size:string,tags:string[]}
     */
    private function extractComponentDetails(string $macroID, DataFolder $dataFolder) : array
    {
        $result = ['size' => '', 'tags' => []];

        try {
            $macro = MacroFileDefs::getInstance()->getByMacroName($macroID, $dataFolder->getID());
            if (!$macro) return $result; 

             $macroDOM = $macro->getDOM();
             // Some macros use 'component ref=".."'
             $compRefEl = $macroDOM->byTagName('component')->getFirst();
             if(!$compRefEl) return $result;
             
             $ref = $compRefEl->getAttribute('ref');
             if(!$ref) return $result;
             
             // Path logic: Assume standard structure ../[component].xml relative to macro folder
             $macroFolder = $macro->getFile()->getFolderPath();
             $parentFolder = dirname($macroFolder);
             // Note: file path construction depends on OS, but PHP handles / fine
             $compPath = $parentFolder . '/' . $ref . '.xml';
             
             if(file_exists($compPath)) {
                 $compDOM = DOMExtended::createFromFile($compPath);
                 
                 // Look for 'container' connection
                 $container = $compDOM->bySelector('connection[name="container"]')->getFirst();
                 $rawTags = [];
                 
                 if($container) {
                     $rawTags = ConvertHelper::explodeTrim(' ', $container->getAttribute('tags'));
                 } else {
                     // Fallback: connection01
                     $c01 = $compDOM->bySelector('connection[name="connection01"]')->getFirst();
                     if($c01) $rawTags = ConvertHelper::explodeTrim(' ', $c01->getAttribute('tags'));
                 }
                 
                 $result['tags'] = $rawTags;
                 foreach($rawTags as $tag) {
                     if($tag === 'small' || $tag === 'size_s') $result['size'] = 's';
                     if($tag === 'medium' || $tag === 'size_m') $result['size'] = 'm';
                     if($tag === 'large' || $tag === 'size_l') $result['size'] = 'l';
                     if($tag === 'extra_large' || $tag === 'extralarge' || $tag === 'size_xl') $result['size'] = 'xl';
                 }
             }

             // Heuristic Fallback: If size not found in tags, try to parse from macro name
             if (empty($result['size'])) {
                 $name = strtolower($macroID);
                 if (preg_match('/_(size_s|s)_/', $name)) $result['size'] = 's';
                 elseif (preg_match('/_(size_m|m)_/', $name)) $result['size'] = 'm';
                 elseif (preg_match('/_(size_l|l)_/', $name)) $result['size'] = 'l';
                 elseif (preg_match('/_(size_xl|xl)_/', $name)) $result['size'] = 'xl';
             }

        } catch (\Throwable $e) {
            // Ignore missing macros or files
        }

        return $result;
    }
}
