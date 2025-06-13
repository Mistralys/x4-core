<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;

class ShipDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_WARE_ID = 'wareID';
    public const KEY_LABEL = 'label';
    public const KEY_SIZE = 'size';
    public const KEY_BUILDER_FACTION_ID = 'builderFactionID';
    public const KEY_CLASS_ID = 'classID';
    public const KEY_USED_BY = 'usedBy';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';
    public const KEY_VARIANT_ID = 'variantID';
    public const KEY_VARIANTS = 'variants';

    private string $id;
    private string $classID;
    private string $size;
    private string $builderFactionID;

    /**
     * @var string[]
     */
    private array $usedBy;
    private string $dataSourceID;
    private string $label;
    private string $variantID;

    /**
     * @var string[]
     */
    private array $variants;

    /**
     * @param string $id
     * @param string $label
     * @param string $variantID
     * @param string $size
     * @param string $builderFactionID
     * @param string $classID
     * @param array $usedBy
     * @param string $dataSourceID
     * @param string[] $variants IDs of this ship's variants, if any.
     */
    public function __construct(string $id, string $label, string $variantID, string $size, string $builderFactionID, string $classID, array $usedBy, string $dataSourceID, array $variants)
    {
        $this->id = $id;
        $this->label = $label;
        $this->variantID = $variantID;
        $this->size = $size;
        $this->builderFactionID = $builderFactionID;
        $this->classID = $classID;
        $this->usedBy = $usedBy;
        $this->dataSourceID = $dataSourceID;
        $this->variants = $variants;
    }

    public static function fromArray(array $def) : ShipDef
    {
        $data = ArrayDataCollection::create($def);

        return new self(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_LABEL),
            $data->getString(self::KEY_VARIANT_ID),
            $data->getString(self::KEY_SIZE),
            $data->getString(self::KEY_BUILDER_FACTION_ID),
            $data->getString(self::KEY_CLASS_ID),
            $data->getArray(self::KEY_USED_BY),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $data->getArrayFlavored(self::KEY_VARIANTS)->filterIndexedStrings()
        );
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSizeID(): string
    {
        return $this->size;
    }

    /**
     * Returns the variant of the ship, which is used to differentiate between
     * different models of the same ship type. An example of this is the
     * Eclipse Vanguard, of which two versions exist with the exact same name:
     *
     * - ship_arg_s_heavyfighter_01_a
     * - ship_arg_s_heavyfighter_02_a
     *
     * In this case, the variant IDs are "01-a" and "02-a" respectively.
     *
     * @return string
     */
    public function getVariantID(): string
    {
        return $this->variantID;
    }

    public function hasVariants() : bool
    {
        return !empty($this->variants);
    }

    public function getSize() : ShipSize
    {
        return ShipSizes::getInstance()->getByID($this->getSizeID());
    }

    public function getBuilderFactionID(): string
    {
        return $this->builderFactionID;
    }

    public function getBuilderFaction() : FactionDef
    {
        return FactionDefs::getInstance()->getByID($this->getBuilderFactionID());
    }

    public function getClassID(): string
    {
        return $this->classID;
    }

    public function getClass(): ShipClass
    {
        return ShipClasses::getInstance()->getByID($this->getClassID());
    }

    public function getDataSourceID(): string
    {
        return $this->dataSourceID;
    }

    public function getDataSource() : DataSourceDef
    {
        return DataSourceDefs::getInstance()->getByID($this->getDataSourceID());
    }

    /**
     * @return FactionDef[]
     */
    public function getUsedBy() : array
    {
        $result = array();

        foreach($this->usedBy as $factionID) {
            $result[] = FactionDefs::getInstance()->getByID($factionID);
        }

        return $result;
    }

    public function toArray(): array
    {
        return array(
            self::KEY_WARE_ID => $this->id,
            self::KEY_LABEL => $this->label,
            self::KEY_VARIANT_ID => $this->variantID,
            self::KEY_SIZE => $this->size,
            self::KEY_BUILDER_FACTION_ID => $this->builderFactionID,
            self::KEY_CLASS_ID => $this->classID,
            self::KEY_USED_BY => $this->usedBy,
            self::KEY_DATA_SOURCE_ID => $this->dataSourceID,
            self::KEY_VARIANTS => $this->variants
        );
    }
}
