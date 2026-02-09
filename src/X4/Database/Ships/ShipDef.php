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
    public const KEY_HULL = 'hull';
    public const KEY_MASS = 'mass';
    public const KEY_DRAG_FORWARD = 'dragForward';
    public const KEY_INERTIA_PITCH = 'inertiaPitch';
    public const KEY_PEOPLE = 'people';
    public const KEY_STORAGE_MISSILE = 'storageMissile';
    public const KEY_SLOTS = 'slots';
    public const KEY_EQUIPMENT = 'equipment';

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
    private VariantID $variantID;
    private int $hull;
    private float $mass;
    private float $dragForward;
    private float $inertiaPitch;
    private int $people;
    private int $storageMissile;
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
     * @param string $builderFactionID
     * @param string $classID
     * @param array $usedBy
     * @param string $dataSourceID
     * @param string[] $variants IDs of this ship's variants, if any.
     * @param int $hull Hull strength.
     * @param float $mass Physics mass.
     * @param float $dragForward Forward drag coefficient involved in acceleration.
     * @param float $inertiaPitch Pitch inertia coefficient.
     * @param int $people Crew capacity.
     * @param int $storageMissile Missile storage capacity.
     * @param array<string,int> $slots Map of slot type ID to count.
     * @param array<string,mixed> $equipment Detailed equipment slots.
     */
    public function __construct(
        string $id,
        string $label,
        VariantID $variantID,
        string $size,
        string $builderFactionID,
        string $classID,
        array $usedBy,
        string $dataSourceID,
        array $variants,
        int $hull,
        float $mass,
        float $dragForward,
        float $inertiaPitch,
        int $people,
        int $storageMissile,
        array $slots,
        array $equipment
    )
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
        $this->hull = $hull;
        $this->mass = $mass;
        $this->dragForward = $dragForward;
        $this->inertiaPitch = $inertiaPitch;
        $this->people = $people;
        $this->storageMissile = $storageMissile;
        $this->slots = $slots;
        $this->equipment = $equipment;
    }

    public static function fromArray(array $def) : ShipDef
    {
        $data = ArrayDataCollection::create($def);

        return new self(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_LABEL),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID)),
            $data->getString(self::KEY_SIZE),
            $data->getString(self::KEY_BUILDER_FACTION_ID),
            $data->getString(self::KEY_CLASS_ID),
            $data->getArray(self::KEY_USED_BY),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $data->getArrayFlavored(self::KEY_VARIANTS)->filterIndexedStrings(),
            $data->getInt(self::KEY_HULL, 0),
            $data->getFloat(self::KEY_MASS, 0.0),
            $data->getFloat(self::KEY_DRAG_FORWARD, 0.0),
            $data->getFloat(self::KEY_INERTIA_PITCH, 0.0),
            $data->getInt(self::KEY_PEOPLE, 0),
            $data->getInt(self::KEY_STORAGE_MISSILE, 0),
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
        if(empty($this->builderFactionID)) {
            return KnownFactions::FACTION_GENERIC;
        }

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

    public function getInertiaPitch() : float
    {
        return $this->inertiaPitch;
    }

    public function getPeopleCapacity() : int
    {
        return $this->people;
    }

    public function getMissileStorage() : int
    {
        return $this->storageMissile;
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
            self::KEY_BUILDER_FACTION_ID => $this->builderFactionID,
            self::KEY_CLASS_ID => $this->classID,
            self::KEY_USED_BY => $this->usedBy,
            self::KEY_DATA_SOURCE_ID => $this->dataSourceID,
            self::KEY_VARIANTS => $this->variants,
            self::KEY_HULL => $this->hull,
            self::KEY_MASS => $this->mass,
            self::KEY_DRAG_FORWARD => $this->dragForward,
            self::KEY_INERTIA_PITCH => $this->inertiaPitch,
            self::KEY_PEOPLE => $this->people,
            self::KEY_STORAGE_MISSILE => $this->storageMissile,
            self::KEY_SLOTS => $this->slots,
            self::KEY_EQUIPMENT => $this->equipment
        );
    }
}
