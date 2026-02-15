<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\Ships\Equipment\ShipSlotDefinition;
use Mistralys\X4\Database\SlotTypes\KnownSlotTypes;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Weapons\WeaponDef;
use Mistralys\X4\Database\Weapons\WeaponDefs;

class ShipDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_WARE_ID = 'wareID';
    public const KEY_LABEL = 'label';
    public const KEY_SIZE = 'size';
    public const KEY_BUILDER_FACTION_ID = 'builderFactionID';
    public const KEY_BUILDER_FACTION_IDS = 'builderFactionIDs';
    public const KEY_CLASS_ID = 'classID';
    public const KEY_USED_BY = 'usedBy';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';
    public const KEY_VARIANT_ID = 'variantID';
    public const KEY_VARIANTS = 'variants';
    public const KEY_HULL = 'hull';
    public const KEY_MASS = 'mass';
    public const KEY_DRAG_FORWARD = 'dragForward';
    public const KEY_DRAG_REVERSE = 'dragReverse';
    public const KEY_DRAG_HORIZONTAL = 'dragHorizontal';
    public const KEY_DRAG_VERTICAL = 'dragVertical';
    public const KEY_DRAG_PITCH = 'dragPitch';
    public const KEY_DRAG_YAW = 'dragYaw';
    public const KEY_DRAG_ROLL = 'dragRoll';
    public const KEY_INERTIA_PITCH = 'inertiaPitch';
    public const KEY_INERTIA_YAW = 'inertiaYaw';
    public const KEY_INERTIA_ROLL = 'inertiaRoll';
    public const KEY_JERK_STRAFE = 'jerkStrafe';
    public const KEY_JERK_ANGULAR = 'jerkAngular';
    public const KEY_JERK_FORWARD_ACCEL = 'jerkForwardAccel';
    public const KEY_JERK_FORWARD_DECEL = 'jerkForwardDecel';
    public const KEY_JERK_FORWARD_RATIO = 'jerkForwardRatio';
    public const KEY_JERK_BOOST_ACCEL = 'jerkBoostAccel';
    public const KEY_JERK_BOOST_RATIO = 'jerkBoostRatio';
    public const KEY_JERK_TRAVEL_ACCEL = 'jerkTravelAccel';
    public const KEY_JERK_TRAVEL_DECEL = 'jerkTravelDecel';
    public const KEY_JERK_TRAVEL_RATIO = 'jerkTravelRatio';
    public const KEY_ACCFACTOR_FORWARD = 'accFactorForward';
    public const KEY_ACCFACTOR_REVERSE = 'accFactorReverse';
    public const KEY_ACCFACTOR_HORIZONTAL = 'accFactorHorizontal';
    public const KEY_ACCFACTOR_VERTICAL = 'accFactorVertical';
    public const KEY_PEOPLE = 'people';
    public const KEY_STORAGE_MISSILE = 'storageMissile';
    public const KEY_CARGO_CAPACITY = 'cargoCapacity';
    public const KEY_CARGO_TYPE = 'cargoType';
    public const KEY_SLOTS = 'slots';
    public const KEY_EQUIPMENT = 'equipment';

    private string $id;
    private string $classID;
    private string $size;
    /**
     * @var string[]
     */
    private array $builderFactionIDs;

    /**
     * @var string[]
     */
    private array $usedBy;
    private string $dataSourceID;
    private string $label;
    private VariantID $variantID;
    private int $hull;
    private float $mass;
    private float $dragForward;
    private float $dragReverse;
    private float $dragHorizontal;
    private float $dragVertical;
    private float $dragPitch;
    private float $dragYaw;
    private float $dragRoll;
    private float $inertiaPitch;
    private float $inertiaYaw;
    private float $inertiaRoll;
    private float $jerkStrafe;
    private float $jerkAngular;
    private float $jerkForwardAccel;
    private float $jerkForwardDecel;
    private float $jerkForwardRatio;
    private float $jerkBoostAccel;
    private float $jerkBoostRatio;
    private float $jerkTravelAccel;
    private float $jerkTravelDecel;
    private float $jerkTravelRatio;
    private float $accFactorForward;
    private float $accFactorReverse;
    private float $accFactorHorizontal;
    private float $accFactorVertical;
    private int $people;
    private int $storageMissile;
    private int $cargoCapacity;
    private string $cargoType;
    /**
     * @var array<string,int>
     */
    private array $slots;
    
    /**
     * @var array<string,mixed>
     */
    private array $equipment;

    /**
     * @var string[]
     */
    private array $variants;

    /**
     * @param string $id
     * @param string $label
     * @param VariantID $variantID
     * @param string $size
     * @param string[] $builderFactionIDs
     * @param string $classID
     * @param array $usedBy
     * @param string $dataSourceID
     * @param string[] $variants IDs of this ship's variants, if any.
     * @param int $hull Hull strength.
     * @param float $mass Physics mass.
     * @param float $dragForward Forward drag coefficient involved in acceleration.
     * @param float $dragReverse Reverse drag coefficient.
     * @param float $dragHorizontal Horizontal (strafing) drag coefficient.
     * @param float $dragVertical Vertical drag coefficient.
     * @param float $dragPitch Pitch rotational drag coefficient.
     * @param float $dragYaw Yaw rotational drag coefficient.
     * @param float $dragRoll Roll rotational drag coefficient.
     * @param float $inertiaPitch Pitch inertia coefficient.
     * @param float $inertiaYaw Yaw inertia coefficient.
     * @param float $inertiaRoll Roll inertia coefficient.
     * @param float $jerkStrafe Strafe jerk (rate of acceleration change).
     * @param float $jerkAngular Angular jerk (rotation acceleration change).
     * @param float $jerkForwardAccel Forward jerk acceleration.
     * @param float $jerkForwardDecel Forward jerk deceleration.
     * @param float $jerkForwardRatio Forward jerk ratio.
     * @param float $jerkBoostAccel Boost jerk acceleration.
     * @param float $jerkBoostRatio Boost jerk ratio.
     * @param float $jerkTravelAccel Travel mode jerk acceleration.
     * @param float $jerkTravelDecel Travel mode jerk deceleration.
     * @param float $jerkTravelRatio Travel mode jerk ratio.
     * @param float $accFactorForward Forward acceleration factor.
     * @param float $accFactorReverse Reverse acceleration factor.
     * @param float $accFactorHorizontal Horizontal acceleration factor.
     * @param float $accFactorVertical Vertical acceleration factor.
     * @param int $people Crew capacity.
     * @param int $storageMissile Missile storage capacity.
     * @param int $cargoCapacity Cargo storage capacity in m³.
     * @param string $cargoType Cargo type (container, liquid, solid, or none).
     * @param array<string,int> $slots Map of slot type ID to count.
     * @param array<string,mixed> $equipment Detailed equipment slots.
     */
    public function __construct(
        string $id,
        string $label,
        VariantID $variantID,
        string $size,
        array $builderFactionIDs,
        string $classID,
        array $usedBy,
        string $dataSourceID,
        array $variants,
        int $hull,
        float $mass,
        float $dragForward,
        float $dragReverse,
        float $dragHorizontal,
        float $dragVertical,
        float $dragPitch,
        float $dragYaw,
        float $dragRoll,
        float $inertiaPitch,
        float $inertiaYaw,
        float $inertiaRoll,
        float $jerkStrafe,
        float $jerkAngular,
        float $jerkForwardAccel,
        float $jerkForwardDecel,
        float $jerkForwardRatio,
        float $jerkBoostAccel,
        float $jerkBoostRatio,
        float $jerkTravelAccel,
        float $jerkTravelDecel,
        float $jerkTravelRatio,
        float $accFactorForward,
        float $accFactorReverse,
        float $accFactorHorizontal,
        float $accFactorVertical,
        int $people,
        int $storageMissile,
        int $cargoCapacity,
        string $cargoType,
        array $slots,
        array $equipment
    )
    {
        $this->id = $id;
        $this->label = $label;
        $this->variantID = $variantID;
        $this->size = $size;
        $this->builderFactionIDs = $builderFactionIDs;
        $this->classID = $classID;
        $this->usedBy = $usedBy;
        $this->dataSourceID = $dataSourceID;
        $this->variants = $variants;
        $this->hull = $hull;
        $this->mass = $mass;
        $this->dragForward = $dragForward;
        $this->dragReverse = $dragReverse;
        $this->dragHorizontal = $dragHorizontal;
        $this->dragVertical = $dragVertical;
        $this->dragPitch = $dragPitch;
        $this->dragYaw = $dragYaw;
        $this->dragRoll = $dragRoll;
        $this->inertiaPitch = $inertiaPitch;
        $this->inertiaYaw = $inertiaYaw;
        $this->inertiaRoll = $inertiaRoll;
        $this->jerkStrafe = $jerkStrafe;
        $this->jerkAngular = $jerkAngular;
        $this->jerkForwardAccel = $jerkForwardAccel;
        $this->jerkForwardDecel = $jerkForwardDecel;
        $this->jerkForwardRatio = $jerkForwardRatio;
        $this->jerkBoostAccel = $jerkBoostAccel;
        $this->jerkBoostRatio = $jerkBoostRatio;
        $this->jerkTravelAccel = $jerkTravelAccel;
        $this->jerkTravelDecel = $jerkTravelDecel;
        $this->jerkTravelRatio = $jerkTravelRatio;
        $this->accFactorForward = $accFactorForward;
        $this->accFactorReverse = $accFactorReverse;
        $this->accFactorHorizontal = $accFactorHorizontal;
        $this->accFactorVertical = $accFactorVertical;
        $this->people = $people;
        $this->storageMissile = $storageMissile;
        $this->cargoCapacity = $cargoCapacity;
        $this->cargoType = $cargoType;
        $this->slots = $slots;
        $this->equipment = $equipment;
    }

    public static function fromArray(array $def) : ShipDef
    {
        // Handle both new array format and old string format for backward compatibility
        $builderFactionIDs = [];
        if (isset($def[self::KEY_BUILDER_FACTION_IDS])) {
            $builderFactionIDs = (array)$def[self::KEY_BUILDER_FACTION_IDS];
        } elseif (isset($def[self::KEY_BUILDER_FACTION_ID])) {
            // Old key might contain either a string or an array (from intermediate rebuild)
            $oldValue = $def[self::KEY_BUILDER_FACTION_ID];
            if (is_array($oldValue)) {
                $builderFactionIDs = $oldValue;
            } else {
                $factionString = (string)$oldValue;
                $builderFactionIDs = array_values(array_filter(explode(' ', $factionString)));
            }
        }
        
        // Default to generic if empty
        if (empty($builderFactionIDs)) {
            $builderFactionIDs = [KnownFactions::FACTION_GENERIC];
        }

        $data = ArrayDataCollection::create($def);

        return new self(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_LABEL),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID)),
            $data->getString(self::KEY_SIZE),
            $builderFactionIDs,
            $data->getString(self::KEY_CLASS_ID),
            $data->getArray(self::KEY_USED_BY),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $data->getArrayFlavored(self::KEY_VARIANTS)->filterIndexedStrings(),
            $data->getInt(self::KEY_HULL, 0),
            $data->getFloat(self::KEY_MASS, 0.0),
            $data->getFloat(self::KEY_DRAG_FORWARD, 0.0),
            $data->getFloat(self::KEY_DRAG_REVERSE, 0.0),
            $data->getFloat(self::KEY_DRAG_HORIZONTAL, 0.0),
            $data->getFloat(self::KEY_DRAG_VERTICAL, 0.0),
            $data->getFloat(self::KEY_DRAG_PITCH, 0.0),
            $data->getFloat(self::KEY_DRAG_YAW, 0.0),
            $data->getFloat(self::KEY_DRAG_ROLL, 0.0),
            $data->getFloat(self::KEY_INERTIA_PITCH, 0.0),
            $data->getFloat(self::KEY_INERTIA_YAW, 0.0),
            $data->getFloat(self::KEY_INERTIA_ROLL, 0.0),
            $data->getFloat(self::KEY_JERK_STRAFE, 0.0),
            $data->getFloat(self::KEY_JERK_ANGULAR, 0.0),
            $data->getFloat(self::KEY_JERK_FORWARD_ACCEL, 0.0),
            $data->getFloat(self::KEY_JERK_FORWARD_DECEL, 0.0),
            $data->getFloat(self::KEY_JERK_FORWARD_RATIO, 0.0),
            $data->getFloat(self::KEY_JERK_BOOST_ACCEL, 0.0),
            $data->getFloat(self::KEY_JERK_BOOST_RATIO, 0.0),
            $data->getFloat(self::KEY_JERK_TRAVEL_ACCEL, 0.0),
            $data->getFloat(self::KEY_JERK_TRAVEL_DECEL, 0.0),
            $data->getFloat(self::KEY_JERK_TRAVEL_RATIO, 0.0),
            $data->getFloat(self::KEY_ACCFACTOR_FORWARD, 1.0),
            $data->getFloat(self::KEY_ACCFACTOR_REVERSE, 1.0),
            $data->getFloat(self::KEY_ACCFACTOR_HORIZONTAL, 1.0),
            $data->getFloat(self::KEY_ACCFACTOR_VERTICAL, 1.0),
            $data->getInt(self::KEY_PEOPLE, 0),
            $data->getInt(self::KEY_STORAGE_MISSILE, 0),
            $data->getInt(self::KEY_CARGO_CAPACITY, 0),
            $data->getString(self::KEY_CARGO_TYPE, 'none'),
            $data->getArray(self::KEY_SLOTS),
            $data->getArray(self::KEY_EQUIPMENT)
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function getEquipment() : array
    {
        return $this->equipment;
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
     * @return VariantID
     */
    public function getVariantID(): VariantID
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
        if(empty($this->builderFactionIDs)) {
            return KnownFactions::FACTION_GENERIC;
        }

        return $this->builderFactionIDs[0];
    }

    /**
     * Returns all builder faction IDs for this ship.
     * @return string[]
     */
    public function getBuilderFactionIDs(): array
    {
        return $this->builderFactionIDs;
    }

    public function getBuilderFaction() : FactionDef
    {
        return FactionDefs::getInstance()->getByID($this->getBuilderFactionID());
    }

    /**
     * Returns all builder factions for this ship.
     * @return FactionDef[]
     */
    public function getBuilderFactions() : array
    {
        $result = [];
        foreach ($this->builderFactionIDs as $factionID) {
            $result[] = FactionDefs::getInstance()->getByID($factionID);
        }
        return $result;
    }

    /**
     * Checks if this ship has multiple builder factions.
     * @return bool
     */
    public function hasMultipleBuilderFactions(): bool
    {
        return count($this->builderFactionIDs) > 1;
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

    public function getHull() : int
    {
        return $this->hull;
    }

    public function getMass() : float
    {
        return $this->mass;
    }

    public function getDragForward() : float
    {
        return $this->dragForward;
    }

    public function getDragReverse() : float
    {
        return $this->dragReverse;
    }

    public function getDragHorizontal() : float
    {
        return $this->dragHorizontal;
    }

    public function getDragVertical() : float
    {
        return $this->dragVertical;
    }

    public function getDragPitch() : float
    {
        return $this->dragPitch;
    }

    public function getDragYaw() : float
    {
        return $this->dragYaw;
    }

    public function getDragRoll() : float
    {
        return $this->dragRoll;
    }

    public function getInertiaPitch() : float
    {
        return $this->inertiaPitch;
    }

    public function getInertiaYaw() : float
    {
        return $this->inertiaYaw;
    }

    public function getInertiaRoll() : float
    {
        return $this->inertiaRoll;
    }

    public function getJerkStrafe() : float
    {
        return $this->jerkStrafe;
    }

    public function getJerkAngular() : float
    {
        return $this->jerkAngular;
    }

    public function getJerkForwardAccel() : float
    {
        return $this->jerkForwardAccel;
    }

    public function getJerkForwardDecel() : float
    {
        return $this->jerkForwardDecel;
    }

    public function getJerkForwardRatio() : float
    {
        return $this->jerkForwardRatio;
    }

    public function getJerkBoostAccel() : float
    {
        return $this->jerkBoostAccel;
    }

    public function getJerkBoostRatio() : float
    {
        return $this->jerkBoostRatio;
    }

    public function getJerkTravelAccel() : float
    {
        return $this->jerkTravelAccel;
    }

    public function getJerkTravelDecel() : float
    {
        return $this->jerkTravelDecel;
    }

    public function getJerkTravelRatio() : float
    {
        return $this->jerkTravelRatio;
    }

    public function getAccFactorForward() : float
    {
        return $this->accFactorForward;
    }

    public function getAccFactorReverse() : float
    {
        return $this->accFactorReverse;
    }

    public function getAccFactorHorizontal() : float
    {
        return $this->accFactorHorizontal;
    }

    public function getAccFactorVertical() : float
    {
        return $this->accFactorVertical;
    }

    public function getPeopleCapacity() : int
    {
        return $this->people;
    }

    public function getMissileStorage() : int
    {
        return $this->storageMissile;
    }

    /**
     * Returns the cargo storage capacity in m³.
     * Returns 0 if the ship has no storage connection.
     */
    public function getCargoCapacity() : int
    {
        return $this->cargoCapacity;
    }

    /**
     * Returns the cargo type (container, liquid, solid).
     * Returns "none" if the ship has no storage connection.
     */
    public function getCargoType() : string
    {
        return $this->cargoType;
    }

    public function getSlotCount(string $typeID) : int
    {
        return $this->slots[$typeID] ?? 0;
    }

    public function countWeapons() : int
    {
        return $this->getSlotCount(KnownSlotTypes::WEAPON);
    }

    public function countShields() : int
    {
        return $this->getSlotCount(KnownSlotTypes::SHIELD);
    }

    public function countTurrets() : int
    {
        return $this->getSlotCount(KnownSlotTypes::TURRET);
    }

    public function countDockingBays() : int
    {
        return $this->getSlotCount(KnownSlotTypes::DOCKING_BAY);
    }

    /**
     * Get the number of countermeasure launchers.
     * Note: Countermeasures are stored in equipment, not slots.
     * 
     * @return int
     */
    public function countCountermeasures() : int
    {
        return $this->equipment['countermeasures'] ?? 0;
    }
    
    public function countEngines() : int
    {
        return $this->getSlotCount(KnownSlotTypes::ENGINE);
    }

    // region: Equipment Compatibility Finders

    /**
     * Get a finder for all equipment compatible with this ship for a specific slot type.
     * Use the returned finder to filter by data source, size, tags, etc.
     *
     * @param string $slotTypeID The slot type ID (use KnownSlotTypes constants)
     * @return Equipment\ShipEquipmentFinder
     */
    public function findEquipmentForSlot(string $slotTypeID): Equipment\ShipEquipmentFinder
    {
        return new Equipment\ShipEquipmentFinder($this, $slotTypeID);
    }

    /**
     * Get all engines compatible with this ship.
     * @return Equipment\ShipEquipmentFinder
     */
    public function getEngines(): Equipment\ShipEquipmentFinder
    {
        return $this->findEquipmentForSlot(KnownSlotTypes::ENGINE);
    }

    /**
     * Get all shields compatible with this ship.
     * @return Equipment\ShipEquipmentFinder
     */
    public function getShields(): Equipment\ShipEquipmentFinder
    {
        return $this->findEquipmentForSlot(KnownSlotTypes::SHIELD);
    }

    /**
     * Get all weapons compatible with this ship.
     * @return Equipment\ShipEquipmentFinder
     */
    public function getWeapons(): Equipment\ShipEquipmentFinder
    {
        return $this->findEquipmentForSlot(KnownSlotTypes::WEAPON);
    }

    /**
     * Get all turrets compatible with this ship.
     * @return Equipment\ShipEquipmentFinder
     */
    public function getTurrets(): Equipment\ShipEquipmentFinder
    {
        return $this->findEquipmentForSlot(KnownSlotTypes::TURRET);
    }

    /**
     * Get all countermeasures compatible with this ship.
     * @return Equipment\ShipEquipmentFinder
     */
    public function getCountermeasures(): Equipment\ShipEquipmentFinder
    {
        return $this->findEquipmentForSlot(KnownSlotTypes::COUNTERMEASURES);
    }

    /**
     * Get all docking bays/modules compatible with this ship.
     * @return Equipment\ShipEquipmentFinder
     */
    public function getDockingBays(): Equipment\ShipEquipmentFinder
    {
        return $this->findEquipmentForSlot(KnownSlotTypes::DOCKING_BAY);
    }

    /**
     * Get all weapon performance data for weapons compatible with this ship's weapon slots.
     * Returns WeaponDef instances (performance stats) for weapons that match this ship's
     * weapon slot compatibility.
     *
     * @return WeaponDef[]
     */
    public function getCompatibleWeapons(): array
    {
        // Get compatible weapon ware IDs from equipment finder
        $compatibleWares = $this->getWeapons()->getAll();
        $compatibleWareIDs = array_map(fn(WareDef $ware) => $ware->getID(), $compatibleWares);

        // Filter WeaponDefs to match compatible ware IDs
        return $this->filterWeaponsByWareIDs($compatibleWareIDs);
    }

    /**
     * Get all weapon performance data for turrets compatible with this ship's turret slots.
     * Returns WeaponDef instances (performance stats) for turrets that match this ship's
     * turret slot compatibility.
     *
     * @return WeaponDef[]
     */
    public function getCompatibleTurrets(): array
    {
        // Get compatible turret ware IDs from equipment finder
        $compatibleWares = $this->getTurrets()->getAll();
        $compatibleWareIDs = array_map(fn(WareDef $ware) => $ware->getID(), $compatibleWares);

        // Filter WeaponDefs to match compatible ware IDs
        return $this->filterWeaponsByWareIDs($compatibleWareIDs);
    }

    /**
     * Filter WeaponDefs by an array of ware IDs.
     *
     * @param string[] $wareIDs
     * @return WeaponDef[]
     */
    private function filterWeaponsByWareIDs(array $wareIDs): array
    {
        if (empty($wareIDs)) {
            return [];
        }

        $weaponDefs = WeaponDefs::getInstance();
        $result = [];

        foreach ($wareIDs as $wareID) {
            $weapon = $weaponDefs->find($wareID);
            if ($weapon !== null) {
                $result[] = $weapon;
            }
        }

        return $result;
    }

    // endregion

    /**
     * @param string|null $type Optional filter (engines, shields, turrets, weapons, docks, countermeasures)
     * @return ShipSlotDefinition[]
     */
    public function getEquipmentGroups(?string $type = null) : array
    {
        $result = [];
        $sources = $this->equipment;
        
        if ($type !== null) {
            if (!isset($sources[$type])) {
                return [];
            }
            $sources = [$type => $sources[$type]];
        }

        foreach ($sources as $groupData) {
            // Handle single object (like Engines) vs Array of objects (like Shields)
            if (isset($groupData['size']) || isset($groupData['count'])) {
                // Single object
                $result[] = ShipSlotDefinition::fromArray($groupData);
            } elseif (is_array($groupData)) {
                // Array of objects
                foreach ($groupData as $item) {
                    if (is_array($item)) {
                        $result[] = ShipSlotDefinition::fromArray($item);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Checks if the ship has at least one slot compatible with the given ware.
     * 
     * @param WareDef $ware
     * @return bool
     */
    public function canEquip(WareDef $ware) : bool
    {
        $slots = $this->getEquipmentGroups();
        foreach ($slots as $slot) {
            if ($slot->canEquip($ware)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Get dock size breakdown as an associative array.
     * 
     * @return array<string,int> Map of dock sizes to counts (e.g., ['s' => 8, 'm' => 4])
     */
    public function getDocks(): array
    {
        $docks = $this->equipment['docks'] ?? [];
        
        return is_array($docks) ? $docks : [];
    }
    
    /**
     * Get count of docks for a specific size.
     * 
     * @param string $size Dock size: s, m, l, xl
     * @return int Number of docks of this size
     */
    public function getDockCount(string $size): int
    {
        $docks = $this->getDocks();
        return $docks[$size] ?? 0;
    }
    
    /**
     * Get total number of docks (all sizes).
     * 
     * @return int Total dock count
     */
    public function getTotalDockCount(): int
    {
        return array_sum($this->getDocks());
    }
    
    /**
     * Check if ship has any docks.
     * 
     * @return bool True if ship has docks
     */
    public function hasDocks(): bool
    {
        return !empty($this->getDocks());
    }
    
    /**
     * Get all dock sizes available on this ship.
     * 
     * @return string[] Array of size keys (s, m, l, xl)
     */
    public function getDockSizes(): array
    {
        return array_keys($this->getDocks());
    }

    public function toArray(): array
    {
        return array(
            self::KEY_WARE_ID => $this->id,
            self::KEY_LABEL => $this->label,
            self::KEY_VARIANT_ID => $this->variantID,
            self::KEY_SIZE => $this->size,
            self::KEY_BUILDER_FACTION_IDS => $this->builderFactionIDs,
            self::KEY_CLASS_ID => $this->classID,
            self::KEY_USED_BY => $this->usedBy,
            self::KEY_DATA_SOURCE_ID => $this->dataSourceID,
            self::KEY_VARIANTS => $this->variants,
            self::KEY_HULL => $this->hull,
            self::KEY_MASS => $this->mass,
            self::KEY_DRAG_FORWARD => $this->dragForward,
            self::KEY_DRAG_REVERSE => $this->dragReverse,
            self::KEY_DRAG_HORIZONTAL => $this->dragHorizontal,
            self::KEY_DRAG_VERTICAL => $this->dragVertical,
            self::KEY_DRAG_PITCH => $this->dragPitch,
            self::KEY_DRAG_YAW => $this->dragYaw,
            self::KEY_DRAG_ROLL => $this->dragRoll,
            self::KEY_INERTIA_PITCH => $this->inertiaPitch,
            self::KEY_INERTIA_YAW => $this->inertiaYaw,
            self::KEY_INERTIA_ROLL => $this->inertiaRoll,
            self::KEY_JERK_STRAFE => $this->jerkStrafe,
            self::KEY_JERK_ANGULAR => $this->jerkAngular,
            self::KEY_JERK_FORWARD_ACCEL => $this->jerkForwardAccel,
            self::KEY_JERK_FORWARD_DECEL => $this->jerkForwardDecel,
            self::KEY_JERK_FORWARD_RATIO => $this->jerkForwardRatio,
            self::KEY_JERK_BOOST_ACCEL => $this->jerkBoostAccel,
            self::KEY_JERK_BOOST_RATIO => $this->jerkBoostRatio,
            self::KEY_JERK_TRAVEL_ACCEL => $this->jerkTravelAccel,
            self::KEY_JERK_TRAVEL_DECEL => $this->jerkTravelDecel,
            self::KEY_JERK_TRAVEL_RATIO => $this->jerkTravelRatio,
            self::KEY_ACCFACTOR_FORWARD => $this->accFactorForward,
            self::KEY_ACCFACTOR_REVERSE => $this->accFactorReverse,
            self::KEY_ACCFACTOR_HORIZONTAL => $this->accFactorHorizontal,
            self::KEY_ACCFACTOR_VERTICAL => $this->accFactorVertical,
            self::KEY_PEOPLE => $this->people,
            self::KEY_STORAGE_MISSILE => $this->storageMissile,
            self::KEY_CARGO_CAPACITY => $this->cargoCapacity,
            self::KEY_CARGO_TYPE => $this->cargoType,
            self::KEY_SLOTS => $this->slots,
            self::KEY_EQUIPMENT => $this->equipment
        );
    }
}
