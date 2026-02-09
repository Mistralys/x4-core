<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistral\X4\Database\Wares;

use AppUtils\ConvertHelper;
use AppUtils\FileHelper\FileInfo;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\ExtractedData\DataFolder;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Goes through the ware XML files from all data folders and extracts
 * them into a single collection.
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
                return;
            }
        }

        $factionDefs = FactionDefs::getInstance();
        $factionIDs = array();
        foreach($wareElement->findChildren()->byTagName('owner')->getAll() as $ownerElement) {
            $factionID = $ownerElement->getAttribute('faction');
            if(empty($factionID)) {
                continue;
            }

            if($factionDefs->idExists($factionID)) {
                $factionIDs[] = $factionID;
            } else {
                Console::line2('Ware [%s] | WARNING | Faction [%s] does not exist.', $id, $factionID);
                return;
            }
        }

        sort($factionIDs);

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

        $this->wares[] = $ware;
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
