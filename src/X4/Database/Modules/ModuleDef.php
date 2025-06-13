<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;

class ModuleDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_CATEGORY = 'category';
    public const KEY_BUILDER_FACTION_ID = 'builderFactionID';
    public const KEY_REQUIRED_WORKFORCE = 'requiredWorkforce';
    public const KEY_WARES_PRODUCED = 'waresProduced';
    public const KEY_SIZE = 'size';
    public const KEY_HOUSING_FACTION_ID = 'housingFactionID';
    public const KEY_DRONE_CAPACITY = 'droneCapacity';
    public const KEY_CARGO_CAPACITY = 'cargoCapacity';
    public const KEY_HOUSING_CAPACITY = 'housingCapacity';
    public const KEY_CARGO_TYPE = 'cargoType';
    public const KEY_HULL = 'hull';
    public const KEY_LABEL = 'label';
    public const KEY_WARE_ID = 'wareID';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_VARIANT_ID = 'variantID';

    private string $wareID;
    private string $label;
    private string $categoryID;
    private string $macroID;
    private string $builderFactionID;
    private string $size;
    private int $hull;
    private int $droneCapacity;
    private int $cargoCapacity;
    private string $cargoType;
    private int $housingCapacity;
    private string $housingFactionID;
    private VariantID $variantID;

    public function __construct(
        string $wareID,
        string $label,
        string $categoryID,
        string $macroID,
        string $builderFactionID,
        string $size,
        int $hull,
        int $droneCapacity,
        int $cargoCapacity,
        string $cargoType,
        int $housingCapacity,
        string $housingFactionID,
        VariantID $variantID
    )
    {
        $this->wareID = $wareID;
        $this->label = $label;
        $this->categoryID = $categoryID;
        $this->macroID = $macroID;
        $this->builderFactionID = $builderFactionID;
        $this->size = $size;
        $this->hull = $hull;
        $this->droneCapacity = $droneCapacity;
        $this->cargoCapacity = $cargoCapacity;
        $this->cargoType = $cargoType;
        $this->housingCapacity = $housingCapacity;
        $this->housingFactionID = $housingFactionID;
        $this->variantID = $variantID;
    }

    public static function fromArray(mixed $moduleDef) : ModuleDef
    {
        $data = ArrayDataCollection::create($moduleDef);

        return new ModuleDef(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_LABEL),
            $data->getString(self::KEY_CATEGORY),
            $data->getString(self::KEY_MACRO_ID),
            $data->getString(self::KEY_BUILDER_FACTION_ID),
            $data->getString(self::KEY_SIZE),
            $data->getInt(self::KEY_HULL),
            $data->getInt(self::KEY_DRONE_CAPACITY),
            $data->getInt(self::KEY_CARGO_CAPACITY),
            $data->getString(self::KEY_CARGO_TYPE),
            $data->getInt(self::KEY_HOUSING_CAPACITY),
            $data->getString(self::KEY_HOUSING_FACTION_ID),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID))
        );
    }

    public function getID() : string
    {
        return $this->wareID;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getVariantID(): VariantID
    {
        return $this->variantID;
    }

    public function getMacroID() : string
    {
        return $this->macroID;
    }

    public function getSize() : string
    {
        return $this->size;
    }

    public function getHullHitpoints() : int
    {
        return $this->hull;
    }

    public function getDroneCapacity() : int
    {
        return $this->droneCapacity;
    }

    public function getCargoCapacity() : int
    {
        return $this->cargoCapacity;
    }

    public function getCargoType() : string
    {
        return $this->cargoType;
    }

    public function getHousingCapacity() : int
    {
        return $this->housingCapacity;
    }

    public function getHousingFactionID() : string
    {
        return $this->housingFactionID;
    }

    /**
     * A module can have multiple macros which reference it.
     *
     * An example is the Medical production module, which
     * is used by several races. Each race has its own macro,
     * which refers to the same module.
     *
     * @return string[]
     */
    public function getMacros() : array
    {
        return array();
    }

    public function getCategoryID() : string
    {
        return $this->categoryID;
    }

    public function getCategory() : ModuleCategory
    {
        return ModuleCategories::getInstance()->getByID($this->getCategoryID());
    }

    /**
     * @return FactionDef
     */
    public function getBuilderFaction() : FactionDef
    {
        return FactionDefs::getInstance()->getByID($this->getBuilderFactionID());
    }

    public function getBuilderFactionID() : string
    {
        return $this->builderFactionID;
    }

    public function isProduction() : bool
    {
        return $this->getCategory()->isProduction();
    }
}
