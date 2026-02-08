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
    public const KEY_FACTIONS = 'factions';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_VARIANT_ID = 'variantID';
    public const KEY_SPECS = 'specs';

    private string $id;
    private string $label;
    private string $groupID;

    /**
     * The tags associated with this ware.
     * @var string[]
     */
    private array $tags;
    private string $dataSourceID;

    /**
     * The IDs of the factions that own this ware.
     * @var string[]
     */
    private array $factionIDs;
    private string $macroID;
    private VariantID $variantID;
    
    /**
     * @var array{size:string,tags:string[]}
     */
    private array $specs;

    /**
     * @param string $id
     * @param string $macroID
     * @param string $label
     * @param string $groupID
     * @param VariantID $variantID
     * @param string[] $tags
     * @param string $dataSourceID
     * @param string[] $factionIDs
     * @param array{size:string,tags:string[]} $specs
     */
    public function __construct(
        string $id, 
        string $macroID, 
        string $label, 
        string $groupID, 
        VariantID $variantID, 
        array $tags, 
        string $dataSourceID, 
        array $factionIDs,
        array $specs
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->groupID = $groupID;
        $this->macroID = $macroID;
        $this->variantID = $variantID;
        $this->tags = $tags;
        $this->dataSourceID = $dataSourceID;
        $this->factionIDs = $factionIDs;
        $this->specs = $specs;
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
            $data->getString(self::KEY_MACRO_ID),
            $data->getString(self::KEY_LABEL),
            $data->getString(self::KEY_GROUP),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID)),
            $data->getArray(self::KEY_TAGS),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $data->getArray(self::KEY_FACTIONS),
            $data->getArray(self::KEY_SPECS)
        );
    }

    public function hasTag(string $tag) : bool
    {
        return in_array($tag, $this->tags, true);
    }
    
    /**
     * Gets the physical specifications of the item component (Size, compatibility tags).
     * @return array{size:string,tags:string[]}
     */
    public function getSpecs() : array
    {
        return $this->specs;
    }
    
    public function getSize() : string
    {
        return $this->specs['size'] ?? '';
    }
    
    /**
     * @return string[]
     */
    public function getCompatibilityTags() : array
    {
        $specTags = $this->specs['tags'] ?? [];
        $wareTags = $this->getTags();

        return array_unique(array_merge($specTags, $wareTags));
    }

    public function toArray() : array
    {
        return array(
            self::KEY_WARE_ID => $this->getID(),
            self::KEY_LABEL => $this->getLabel(),
            self::KEY_GROUP => $this->getGroupID(),
            self::KEY_VARIANT_ID => $this->getVariantID()->getID(),
            self::KEY_TAGS => $this->getTags(),
            self::KEY_DATA_SOURCE_ID => $this->getDataSourceID(),
            self::KEY_FACTIONS => $this->getFactionIDs(),
            self::KEY_MACRO_ID => $this->getMacroID(),
            self::KEY_SPECS => $this->getSpecs()
        );
    }
}
