<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\ArrayDataCollection;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Builder\KnownItemsClassGenerator;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\MacroIndex\MacroFileDef;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\SlotTypes\SlotTypes;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\UI\Console;
use Mistralys\X4\X4Application;
use Mistralys\X4\XML\DOMExtended;
use function AppUtils\array_remove_values;

class ShipsExtractor
{
    /**
     * @var array<string,array<string,mixed>>
     */
    private array $ships = array();

    public function extract() : void
    {
        $this->extractShips();
        $this->validateShipClasses();
        $this->generateKnownShipsClass();
    }

    private function extractShips() : void
    {
        Console::header('Extracting ships');

        foreach (WareDefs::getInstance()->getAll() as $ware) {
            $this->processWare($ware);
        }

        ksort($this->ships);

        $this->validateVariants();

        Console::line1('Found [%d] ships.', count($this->ships));
        Console::line1('Writing ships to file.');
        Console::nl();

        ShipDefs::getInstance()
            ->getDataFile()
            ->putData(array_values($this->ships));
    }

    private function validateVariants() : void
    {
        $labels = array();
        foreach($this->ships as $ship) {
            if(!isset($labels[$ship[ShipDef::KEY_LABEL]])) {
                $labels[$ship[ShipDef::KEY_LABEL]] = array();
            }

            $labels[$ship[ShipDef::KEY_LABEL]][] = $ship[ShipDef::KEY_WARE_ID];
        }

        foreach($labels as $label => $ids) {
            if(count($ids) === 1) {
                continue;
            }

            Console::line1('NOTICE | The ship [%s] has multiple variants.', $label);

            foreach($ids as $id) {
                $this->ships[$id][ShipDef::KEY_VARIANTS] = array_values(array_remove_values($ids, array($id)));
            }
        }
    }

    private function validateShipClasses() : void
    {
        Console::header('Validating ship classes');

        $collection = ShipClasses::getInstance();

        $usedClasses = array();
        foreach($this->ships as $ship) {
            $usedClasses[] = $ship[ShipDef::KEY_CLASS_ID];
        }

        foreach($collection->getIDs() as $id) {
            if(!in_array($id, $usedClasses)) {
                Console::line1('ERROR | The ship class [%s] is not used by any ship.', $id);
                exit;
            }
        }

        Console::line1('Done.');
        Console::nl();
    }

    private function processWare(WareDef $def) : void
    {
        if($def->getGroupID() !== WareGroups::GROUP_SHIPS) {
            return;
        }

        $macroDef = MacroFileDefs::getInstance()->getByMacroName(
            $def->getMacroID(),
            $def->getDataSourceID()
        );
        $dom = $macroDef->getDOM();
        $shipID = $def->getID();

        $alias = $this->resolveParentMacro($dom);

        $domAlias = null;
        if(!empty($alias)) {
            $domAlias = MacroFileDefs::getInstance()->getByMacroName(
                $alias,
                $def->getDataSourceID()
            )->getDOM();
        }

        $stats = $this->extractStats($dom, $domAlias);

        $slots = [];
        $equipment = [];

        $ref = $this->getAttributeFromDOM($dom, 'component', 'ref');
        if(!$ref && $domAlias) {
            $ref = $this->getAttributeFromDOM($domAlias, 'component', 'ref');
        }

        if(!empty($ref)) {
            $componentDom = $this->resolveComponentDOM($ref, $macroDef);
            if($componentDom) {
                $slots = $this->countSlots($componentDom);
                $equipment = $this->extractEquipment($componentDom, $dom, $def->getDataSourceID());
            }
        }

        $this->ships[$shipID] = array(
            ShipDef::KEY_WARE_ID => $shipID,
            ShipDef::KEY_LABEL => $def->getLabel(),
            ShipDef::KEY_VARIANT_ID => (string)VariantID::resolveWareVariantID($shipID),
            ShipDef::KEY_DATA_SOURCE_ID => $def->getDataSourceID(),
            ShipDef::KEY_SIZE => $this->resolveShipSize($dom),
            ShipDef::KEY_CLASS_ID => $this->resolveShipClass($domAlias ?? $dom, $shipID),
            ShipDef::KEY_BUILDER_FACTION_ID => $this->resolveFaction($domAlias ?? $dom, $shipID),
            ShipDef::KEY_USED_BY => $def->getFactionIDs(),
            ShipDef::KEY_HULL => $stats[ShipDef::KEY_HULL],
            ShipDef::KEY_MASS => $stats[ShipDef::KEY_MASS],
            ShipDef::KEY_DRAG_FORWARD => $stats[ShipDef::KEY_DRAG_FORWARD],
            ShipDef::KEY_INERTIA_PITCH => $stats[ShipDef::KEY_INERTIA_PITCH],
            ShipDef::KEY_PEOPLE => $stats[ShipDef::KEY_PEOPLE],
            ShipDef::KEY_STORAGE_MISSILE => $stats[ShipDef::KEY_STORAGE_MISSILE],
            ShipDef::KEY_SLOTS => $slots,
            ShipDef::KEY_EQUIPMENT => $equipment
        );
    }

    private function extractEquipment(DOMExtended $componentDom, ?DOMExtended $shipMacroDom = null, ?string $dataSourceID = null): array
    {
        $aggregator = new ShipSlotAggregator();
        
        // Build dock size map from ship macro if available
        $dockSizes = [];
        if ($shipMacroDom && $dataSourceID) {
            $dockSizes = $this->extractDockSizesFromShipMacro($shipMacroDom, $dataSourceID);
        }
        
        $connections = $componentDom->byTagName('connection')->getAll();
        foreach($connections as $conn) {
             $tags = $conn->getAttribute('tags');
             $name = $conn->getAttribute('name');
             $dockSize = $dockSizes[$name] ?? null;
             
             $aggregator->addStructureConnection([
                 'name' => $name,
                 'group' => $conn->getAttribute('group'),
                 'tags' => !empty($tags) ? explode(' ', $tags) : [],
                 'dockSize' => $dockSize
             ]);
        }
        return $aggregator->getAggregatedData();
    }

    private function resolveShipClass(DOMExtended $dom, string $shipID) : string
    {
        $class = $dom
            ->byTagName('ship')
            ->requireFirst(sprintf('ERROR | No ship tag found for ship macro [%s].', $shipID))
            ->getAttribute('type');

        if(ShipClasses::getInstance()->idExists($class)) {
            return $class;
        }

        Console::line1('ERROR | The ship class [%s] is not known.', $class);
        exit;
    }

    private function resolveParentMacro(DOMExtended $dom) : ?string
    {
        $alias = $dom->byTagName('macro')->requireFirst()->getAttribute('alias');
        if(!empty($alias)) {
            return $alias;
        }

        return null;
    }

    private function resolveFaction(DOMExtended $dom, string $shipID) : string
    {
        $factionID = $dom
            ->byTagName('identification')
            ->requireFirst(sprintf('ERROR | No identification element found for ship macro [%s].', $shipID))
            ->getAttribute('makerrace');

        if(!empty($factionID)) {
            return $factionID;
        }

        $factionID = KnownFactions::FACTION_GENERIC;

        $exceptions = $this->getFactionExceptions();
        if(isset($exceptions[$shipID])) {
            $factionID = $exceptions[$shipID];
            Console::line1('INFO | The ship [%s] has a custom faction ID [%s] defined in the settings.', $shipID, $factionID);
        } else {
            Console::line1('WARNING | The ship [%s] has no builder faction. Defaulting to [%s].', $shipID, $factionID);
        }

        return $factionID;
    }

    /**
     * @return array<string|int,string>
     */
    private function getFactionExceptions() : array
    {
        return $this->getShipSettings()
            ->getArrayFlavored('faction-exceptions')
            ->toAssocString();
    }

    private ?ArrayDataCollection $shipSettings = null;

    private function getShipSettings() : ArrayDataCollection
    {
        if(isset($this->shipSettings)) {
           return $this->shipSettings;
        }

        $this->shipSettings = ArrayDataCollection::create(
            JSONFile::factory(X4Application::getDataFolder().'/ship-settings.json')
                ->getData()
        );

        return $this->shipSettings;
    }

    private function resolveShipSize(DOMExtended $dom) : string
    {
        $class = $dom->byTagName('macro')->requireFirst()->getAttribute('class');
        $parts = explode('_', $class);
        return strtolower(array_pop($parts));
    }

    private function generateKnownShipsClass() : void
    {
        $generator = new KnownItemsClassGenerator(
            ShipDefs::class,
            ShipDef::class,
            FolderInfo::factory(__DIR__)
        );

        foreach($this->ships as $item)
        {
            $generator->addItem(
                $item[ShipDef::KEY_WARE_ID],
                $item[ShipDef::KEY_LABEL],
                VariantID::fromID($item[ShipDef::KEY_VARIANT_ID])
            );
        }

        $generator->generate();
    }

    private function extractStats(DOMExtended $dom, ?DOMExtended $parentDom) : array
    {
        return [
            ShipDef::KEY_HULL => (int)$this->resolvePropertyAttribute($dom, $parentDom, 'hull', 'max', 0),
            ShipDef::KEY_MASS => (float)$this->resolvePropertyAttribute($dom, $parentDom, 'physics', 'mass', 0),
            ShipDef::KEY_DRAG_FORWARD => (float)$this->resolvePropertyAttribute($dom, $parentDom, 'drag', 'forward', 0),
            ShipDef::KEY_INERTIA_PITCH => (float)$this->resolvePropertyAttribute($dom, $parentDom, 'inertia', 'pitch', 0),
            ShipDef::KEY_PEOPLE => (int)$this->resolvePropertyAttribute($dom, $parentDom, 'people', 'capacity', 0),
            ShipDef::KEY_STORAGE_MISSILE => (int)$this->resolvePropertyAttribute($dom, $parentDom, 'storage', 'missile', 0)
        ];
    }



    private function resolveComponentDOM(string $ref, MacroFileDef $macroDef) : ?DOMExtended
    {
        // Assume relative path ../$ref.xml matches assets/units/size_X/$ref.xml
        // when macro is in assets/units/size_X/macros/$ref_macro.xml
        $macroFolder = dirname($macroDef->getFullPath());
        $parentFolder = dirname($macroFolder);

        $path = $macroDef->getDataFolder()->getPath() . '/' . $parentFolder . '/' . $ref . '.xml';

        if(file_exists($path)) {
            return DOMExtended::createFromFile($path);
        }

        return null;
    }

    private function countSlots(DOMExtended $componentDom) : array
    {
        $counts = [];
        $slotTypes = SlotTypes::getInstance()->getAll();

        foreach($slotTypes as $type) {
            // Exclude countermeasures from slots - they're equipment, not slots
            if ($type->getID() === 'countermeasures') {
                continue;
            }
            $counts[$type->getID()] = 0;
        }

        $connections = $componentDom->byTagName('connection')->getAll();
        foreach($connections as $conn) {
            $tags = $conn->getAttribute('tags');
            if(empty($tags)) {
                continue;
            }

            foreach($slotTypes as $type) {
                // Exclude countermeasures from slots
                if ($type->getID() === 'countermeasures') {
                    continue;
                }
                if(str_contains($tags, $type->getPrimaryTag())) {
                    $counts[$type->getID()]++;
                }
            }
        }

        return $counts;
    }

    private function resolvePropertyAttribute(DOMExtended $dom, ?DOMExtended $parentDom, string $tagName, string $attributeName, mixed $defaultValue) : mixed
    {
        $val = $this->getAttributeFromDOM($dom, $tagName, $attributeName);
        if($val !== null) {
            return $val;
        }

        if($parentDom) {
            $val = $this->getAttributeFromDOM($parentDom, $tagName, $attributeName);
            if($val !== null) {
                return $val;
            }
        }

        return $defaultValue;
    }

    private function getAttributeFromDOM(DOMExtended $dom, string $tagName, string $attributeName) : ?string
    {
        $el = $dom->byTagName($tagName)->getFirst();
        if($el) {
            $val = $el->getAttribute($attributeName);
            // 0 is a valid value, check strict empty string
            if($val !== '') {
                return $val;
            }
        }
        return null;
    }
    
    /**
     * Extracts dock sizes from ship macro XML by looking up dock macro references.
     * 
     * @param DOMExtended $shipMacroDom Ship macro DOM document
     * @param string $dataSourceID Data source ID for macro lookup
     * @return array<string,string> Map of connection names to dock sizes (s, m, l, xl)
     */
    private function extractDockSizesFromShipMacro(DOMExtended $shipMacroDom, string $dataSourceID): array
    {
        $dockSizes = [];
        
        $connections = $shipMacroDom->byTagName('connection')->getAll();
        foreach ($connections as $connection) {
            $connectionRef = $connection->getAttribute('ref');
            
            // Find macro child elements
            $children = $connection->getChildren();
            foreach ($children as $child) {
                // Check if this is a macro element
                $domElement = $child->getDOMElement();
                if ($domElement->nodeName !== 'macro') {
                    continue;
                }
                
                $dockMacroName = $child->getAttribute('ref');
                if (empty($dockMacroName)) {
                    continue;
                }
                
                // Extract dock size from the dock macro
                $size = $this->extractDockSizeFromMacro($dockMacroName, $dataSourceID);
                if ($size !== null) {
                    $dockSizes[$connectionRef] = $size;
                }
            }
        }
        
        return $dockSizes;
    }
    
    /**
     * Extracts dock size from a dock macro XML file.
     * 
     * @param string $macroName Name of the dock macro (e.g., "launchtube_arg_s_01_macro")
     * @param string $dataSourceID Data source ID for macro lookup
     * @return string|null Dock size (xs, s, m, l, xl) or null if not found
     */
    private function extractDockSizeFromMacro(string $macroName, string $dataSourceID): ?string
    {
        try {
            // Get macro file from macro index
            $macroDef = MacroFileDefs::getInstance()->getByMacroName($macroName, $dataSourceID);
            
            if ($macroDef === null) {
                return null;
            }
            
            $macroDOM = $macroDef->getDOM();
            
            // Find <docksize tags="dock_s"/> element
            $docksizeNodes = $macroDOM->byTagName('docksize')->getAll();
            if (empty($docksizeNodes)) {
                return null;
            }
            
            $tags = $docksizeNodes[0]->getAttribute('tags');
            
            // Extract size from tags (e.g., "dock_s" -> "s")
            if (preg_match('/dock_(xs|s|m|l|xl)/', $tags, $matches)) {
                return $matches[1];
            }
            
            return null;
            
        } catch (\Exception $e) {
            // Log but don't fail extraction for missing macros
            // Silently continue
            return null;
        }
    }
}
