<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\ArrayDataCollection;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\MacroIndex\MacroFileDef;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;

/**
 * Definition of a ware.
 *
 * @package X4 Database
 * @subpackage Wares
 */
class WareDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_WARE_ID = 'wareID';
    public const KEY_LABEL = 'label';
    public const KEY_GROUP = 'group';
    public const KEY_TAGS = 'tags';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';
    public const KEY_SIZE = 'size';
    public const KEY_FACTIONS = 'factions';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_VARIANT_ID = 'variantID';
    public const KEY_COMPONENT = 'component';

    private string $id;
    private string $label;
    private string $groupID;

    /**
     * The tags associated with this ware.
     * @var string[]
     */
    private array $tags;
    private string $dataSourceID;
    private string $size;

    /**
     * The IDs of the factions that own this ware.
     * @var string[]
     */
    private array $factionIDs;
    private string $macroID;
    private VariantID $variantID;
    
    /**
     * @var array{tags:string[]}
     */
    private array $component;

    /**
     * @param string $id
     * @param string $macroID
     * @param string $label
     * @param string $groupID
     * @param VariantID $variantID
     * @param string[] $tags
     * @param string $dataSourceID
     * @param string $size
     * @param string[] $factionIDs
     * @param array{tags:string[]} $component
     */
    public function __construct(
        string $id, 
        string $macroID, 
        string $label, 
        string $groupID, 
        VariantID $variantID, 
        array $tags, 
        string $dataSourceID, 
        string $size,
        array $factionIDs,
        array $component
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->groupID = $groupID;
        $this->macroID = $macroID;
        $this->variantID = $variantID;
        $this->tags = $tags;
        $this->dataSourceID = $dataSourceID;
        $this->size = $size;
        $this->factionIDs = $factionIDs;
        $this->component = $component;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getVariantID(): VariantID
    {
        return $this->variantID;
    }

    public function getWare(): WareDef
    {
        return $this;
    }

    public function getGroupID() : string
    {
        return $this->groupID;
    }

    public function getGroup() : WareGroup
    {
        return WareGroups::getInstance()->getByID($this->getGroupID());
    }

    /**
     * Get the tags associated with this ware.
     * @return string[]
     */
    public function getTags() : array
    {
        return $this->tags;
    }

    public function getDataSourceID() : string
    {
        return $this->dataSourceID;
    }

    /**
     * Gets information on the original game data source that this ware was extracted from.
     * @return DataSourceDef
     */
    public function getDataSource() : DataSourceDef
    {
        return DataSourceDefs::getInstance()->getByID($this->getDataSourceID());
    }

    public function getMacroID() : string
    {
        return $this->macroID;
    }

    public function getMacro() : MacroFileDef
    {
        return MacroFileDefs::getInstance()->getByMacroName(
            $this->getMacroID(),
            $this->getDataSourceID()
        );
    }

    /**
     * Get the IDs of the factions that typically use this ware.
     * @return string[]
     */
    public function getFactionIDs() : array
    {
        return $this->factionIDs;
    }

    /**
     * Gets the factions that typically use this ware.
     * @return FactionDef[]
     */
    public function getFactions() : array
    {
        $defs = FactionDefs::getInstance();
        $result = array();
        foreach($this->factionIDs as $factionID) {
            $result[] = $defs->getByID($factionID);
        }

        usort($result, static function(FactionDef $a, FactionDef $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });

        return $result;
    }

    public static function fromArray(array $wareDef) : WareDef
    {
        $data = ArrayDataCollection::create($wareDef);

        return new WareDef(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_MACRO_ID, ''),
            $data->getString(self::KEY_LABEL),
            $data->getString(self::KEY_GROUP),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID, '::')),
            $data->getArray(self::KEY_TAGS, []),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $data->getString(self::KEY_SIZE, ''),
            $data->getArray(self::KEY_FACTIONS, []),
            $data->getArray(self::KEY_COMPONENT, ['tags' => []])
        );
    }

    public function hasTag(string $tag) : bool
    {
        return in_array($tag, $this->tags, true);
    }
    
    /**
     * Gets the component data (compatibility tags from the physical component).
     * @return array{tags:string[]}
     */
    public function getComponent() : array
    {
        return $this->component;
    }
    
    /**
     * @deprecated Use getComponent() instead
     * @return array{tags:string[]}
     */
    public function getSpecs() : array
    {
        return $this->getComponent();
    }
    
    public function getSize() : string
    {
        return $this->size;
    }
    
    /**
     * @return string[]
     */
    public function getCompatibilityTags() : array
    {
        $componentTags = $this->component['tags'] ?? [];
        $wareTags = $this->getTags();

        return array_unique(array_merge($componentTags, $wareTags));
    }

    public function toArray() : array
    {
        $result = array(
            self::KEY_WARE_ID => $this->getID(),
            self::KEY_LABEL => $this->getLabel(),
            self::KEY_GROUP => $this->getGroupID(),
            self::KEY_DATA_SOURCE_ID => $this->getDataSourceID(),
        );
        
        // Only include size if not empty
        if (!empty($this->getSize())) {
            $result[self::KEY_SIZE] = $this->getSize();
        }
        
        // Only include tags if not empty
        if (!empty($this->getTags())) {
            $result[self::KEY_TAGS] = $this->getTags();
        }
        
        // Only include macroID if not empty
        if (!empty($this->getMacroID())) {
            $result[self::KEY_MACRO_ID] = $this->getMacroID();
        }
        
        // Only include variantID if not default "::"
        if ($this->getVariantID()->getID() !== '::') {
            $result[self::KEY_VARIANT_ID] = $this->getVariantID()->getID();
        }
        
        // Only include factions if not empty
        if (!empty($this->getFactionIDs())) {
            $result[self::KEY_FACTIONS] = $this->getFactionIDs();
        }
        
        // Only include component if tags is not empty
        $component = $this->getComponent();
        if (!empty($component['tags'])) {
            $result[self::KEY_COMPONENT] = $component;
        }
        
        return $result;
    }
}
