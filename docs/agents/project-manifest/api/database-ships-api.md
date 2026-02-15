# Database Ships & Engines API Reference

> **Domain**: Ships, Engines, ShipClasses, ShipSizes, ShipSettings  
> **Last Updated**: February 15, 2026

[← Back to API Index](README.md)

---

## Overview

The Ships & Engines namespace contains all ship and propulsion data:

**Ships**: All player-flyable and NPC ships with complete physics, equipment slots, and storage
- **Engines**: All propulsion systems with thrust, boost, and travel characteristics
- **Ship Classes**: Categorization (Scout, Frigate, Carrier, etc.)
- **Ship Sizes**: Size classifications (XS, S, M, L, XL)
- **Equipment Compatibility**: Slot-based equipment filtering

---

## Table of Contents

- [Ships](#ships)
- [Engines](#engines)
- [Ship Classes & Sizes](#ship-classes--sizes)
- [Equipment Compatibility](#equipment-compatibility)

---

## Ships

### Mistralys\X4\Database\Ships\ShipDef

Ship definition with complete physics, equipment, and storage data.

#### Key Constants (selected)
```php
// Identity
KEY_WARE_ID: string = 'wareID'
KEY_LABEL: string = 'label'
KEY_SIZE: string = 'size'
KEY_BUILDER_FACTION_ID: string = 'builderFactionID'
KEY_CLASS_ID: string = 'classID'

// Physics - Hull & Mass
KEY_HULL: string = 'hull'
KEY_MASS: string = 'mass'

// Physics - Drag
KEY_DRAG_FORWARD: string = 'dragForward'
KEY_DRAG_PITCH: string = 'dragPitch'
KEY_DRAG_YAW: string = 'dragYaw'

// Physics - Jerk (rate of acceleration change)
KEY_JERK_STRAFE: string = 'jerkStrafe'
KEY_JERK_ANGULAR: string = 'jerkAngular'

// Storage
KEY_CARGO_CAPACITY: string = 'cargoCapacity'
KEY_PEOPLE: string = 'people'
KEY_STORAGE_MISSILE: string = 'storageMissile'
KEY_SLOTS: string = 'slots'
```

#### Methods

**Identity & Classification:**
```php
getID(): string
getLabel(): string
getSizeID(): string
getVariantID(): VariantID
getSize(): ShipSize
getBuilderFaction(): FactionDef
getBuilderFactionIDs(): array // Returns string[]
hasMultipleBuilderFactions(): bool
getClassID(): string
getClass(): ShipClass
getDataSourceID(): string
getWareID(): string
getWare(): WareDef
```

**Physics - Hull & Mass:**
```php
getHull(): int        // Hull hitpoints
getMass(): float      // Ship mass in tons
```

**Physics - Drag Coefficients:**
```php
getDragForward(): float
getDragReverse(): float
getDragHorizontal(): float
getDragVertical(): float
getDragPitch(): float
getDragYaw(): float
getDragRoll(): float
```

**Physics - Inertia:**
```php
getInertiaPitch(): float
getInertiaYaw(): float
getInertiaRoll(): float
```

**Physics - Jerk (rate of acceleration change):**
```php
getJerkStrafe(): float
getJerkAngular(): float
getJerkForwardAccel(): float
getJerkForwardDecel(): float
getJerkBoostAccel(): float
getJerkTravelAccel(): float
getJerkTravelDecel(): float
```

**Physics - Acceleration Factors:**
```php
getAccFactorForward(): float
getAccFactorReverse(): float
getAccFactorHorizontal(): float
getAccFactorVertical(): float
```

**Storage & Capacity:**
```php
getPeopleCapacity(): int      // Crew capacity
getMissileStorage(): int      // Missile storage count
getCargoCapacity(): int       // Cargo m³
getCargoType(): string        // container, liquid, solid, none
```

**Equipment Slots:**
```php
getSlotCount(string $typeID): int
countWeapons(): int
countShields(): int
countTurrets(): int
countDockingBays(): int
countCountermeasures(): int
countEngines(): int
```

**Equipment Compatibility (returns Finders):**
```php
findEquipmentForSlot(string $slotTypeID): Equipment\ShipEquipmentFinder
getEngines(): Equipment\ShipEquipmentFinder
getShields(): Equipment\ShipEquipmentFinder
getWeapons(): Equipment\ShipEquipmentFinder
getTurrets(): Equipment\ShipEquipmentFinder
getCountermeasures(): Equipment\ShipEquipmentFinder
getDockingBays(): Equipment\ShipEquipmentFinder
```

**Weapon Performance:**
```php
getCompatibleWeapons(): array // Returns WeaponDef[] with stats
getCompatibleTurrets(): array // Returns WeaponDef[] with stats
```

**Docking:**
```php
getDocks(): array             // Map of dock sizes to counts
getDockCount(string $size): int
getTotalDockCount(): int
hasDocks(): bool
getDockSizes(): array
```

**Usage:**
```php
$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');
echo $ship->getLabel();           // "Argon Destroyer Mk1"
echo $ship->getHull();            // 45000
echo $ship->getMass();            // 2500.0
echo $ship->getCargoCapacity();   // 2000
echo $ship->countWeapons();       // 4
echo $ship->countTurrets();       // 6

// Find compatible engines
$engines = $ship->getEngines()
    ->selectMinThrust(5000.0)
    ->selectDataSource('vanilla')
    ->getAll();
```

---

### Mistralys\X4\Database\Ships\ShipDefs

Collection of all ships.

#### Methods
```php
static getInstance(): ShipDefs
getDefaultID(): string
getDataFile(): JSONFile
find(string $id): ?ShipDef
getFactions(): array // Returns FactionDef[]
findShips(): ShipFinder
getDataSources(): array // Returns DataSourceDef[]
getByID(string $id): ShipDef
getAll(): array // Returns ShipDef[]
```

**Usage:**
```php
$ships = ShipDefs::getInstance();
$allShips = $ships->getAll();

// Find ships
$argonShips = $ships->findShips()
    ->selectBuilderFaction('argon')
    ->selectSize('l')
    ->getAll();
```

---

### Mistralys\X4\Database\Ships\ShipFinder

Specialized filtering utility to find ships.

#### Methods
```php
getCollection(): ItemCollectionInterface
selectBuilderFaction(string|FactionDef $faction): self
selectClass(string|ShipClass $class): self
selectSize(string|ShipSize $size): self
selectDataSource(string|DataSourceDef $dataSource): self
selectLabel(string $label): self
getAll(): array // Returns ShipDef[]
```

**Usage:**
```php
// Find large Argon capital ships
$capitalShips = ShipDefs::getInstance()->findShips()
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->selectSize('l')
    ->selectClass('capitalvessel')
    ->getAll();

// Find all carriers
$carriers = ShipDefs::getInstance()->findShips()
    ->selectClass(ShipClasses::CLASS_CARRIER)
    ->getAll();
```

---

## Engines

### Mistralys\X4\Database\Engines\EngineDef

Engine definition with thrust, boost, and travel characteristics.

#### Key Constants (selected)
```php
KEY_WARE_ID: string = 'wareID'
KEY_SIZE: string = 'size'
KEY_MAKER_RACE: string = 'makerRace'
KEY_MK: string = 'mk'
KEY_BOOST_DURATION: string = 'boostDuration'
KEY_BOOST_THRUST: string = 'boostThrust'
KEY_TRAVEL_THRUST: string = 'travelThrust'
KEY_THRUST_FORWARD: string = 'thrustForward'
KEY_HULL_MAX: string = 'hullMax'
```

#### Methods
```php
getID(): string
getLabel(): string
getVariantID(): VariantID
getMacroID(): string
getSize(): string
getDataSourceID(): string
getMakerRace(): string
getMakerRaces(): array // Returns string[]
hasMultipleMakerRaces(): bool
getMk(): int

// Boost Performance
getBoostDuration(): float      // Seconds
getBoostRecharge(): float      // Seconds
getBoostThrust(): float        // Thrust multiplier
getBoostAcceleration(): float
getBoostAttack(): float        // Activation time
getBoostRelease(): float       // Deactivation time
getBoostCoast(): float

// Travel Performance
getTravelCharge(): float       // Charge time to enter travel
getTravelThrust(): float       // Travel speed multiplier
getTravelAttack(): float
getTravelRelease(): float

// Standard Thrust
getThrustForward(): float
getThrustReverse(): float

// Durability
getHullMax(): float
getHullThreshold(): float
hasDecelerationCurve(): bool
getDecelerationCurve(): array

getWareID(): string
getWare(): WareDef
```

**Usage:**
```php
$engine = EngineDefs::getInstance()->getByID('engine_arg_l_allround_01_mk1');
echo $engine->getLabel();          // "Argon L AllRound Engine Mk1"
echo $engine->getThrustForward();  // 15000.0
echo $engine->getBoostThrust();    // 3.5 (multiplier)
echo $engine->getBoostDuration();  // 8.0 seconds
echo $engine->getTravelThrust();   // 12.0 (multiplier)
```

---

### Mistralys\X4\Database\Engines\EngineDefs

Collection of all engine definitions.

#### Constants
```php
DATA_FILE: string = 'engines.json'
ERROR_ENGINE_NOT_FOUND: int = 142001
```

#### Methods
```php
static getInstance(): EngineDefs
getDataFile(): JSONFile
find(string $idOrMacro): ?EngineDef
findByMacro(string $macro): ?EngineDef
getDefaultID(): string
findEngines(): EngineFinder
getByID(string $id): EngineDef
getAll(): array // Returns EngineDef[]
```

---

### Mistralys\X4\Database\Engines\EngineFinder

Finder for filtering engine collections.

#### Methods
```php
getCollection(): ItemCollectionInterface
selectSize(string $size): self
selectSizes(array $sizes): self
selectMakerRace(string $race): self
selectMk(int $mk): self
selectMinMk(int $minMk): self
selectMinThrust(float $minThrust): self
selectMaxThrust(float $maxThrust): self
selectMinBoostDuration(float $minDuration): self
selectMaxBoostRecharge(float $maxRecharge): self
selectMinBoostThrust(float $minMultiplier): self
selectMinTravelThrust(float $minTravel): self
selectMaxTravelCharge(float $maxCharge): self
selectMinHull(float $minHull): self
selectWithDecelerationCurve(): self
selectDataSource(string|DataSourceDef $dataSource): self
selectLabelSearch(string $searchTerm): self
getAll(): array // Returns EngineDef[]
```

**Usage:**
```php
// Find high-thrust large engines with good boost
$engines = EngineDefs::getInstance()->findEngines()
    ->selectSize('l')
    ->selectMinThrust(15000.0)
    ->selectMinBoostThrust(3.0)
    ->selectMinBoostDuration(8.0)
    ->getAll();

// Find travel-optimized engines
$travelEngines = EngineDefs::getInstance()->findEngines()
    ->selectMinTravelThrust(10.0)
    ->selectMaxTravelCharge(5.0)
    ->getAll();
```

---

## Ship Classes & Sizes

### Mistralys\X4\Database\Ships\ShipClass

Ship class definition (Scout, Fighter, Frigate, etc.).

#### Methods
```php
__construct(string $id, string $label): void
getID(): string
getLabel(): string
```

---

### Mistralys\X4\Database\Ships\ShipClasses

Collection of ship classes.

#### Constants (selected)
```php
CLASS_SCOUT: string = 'scout'
CLASS_FIGHTER: string = 'fighter'
CLASS_INTERCEPTOR: string = 'interceptor'
CLASS_BOMBER: string = 'bomber'
CLASS_MINER: string = 'miner'
CLASS_TRANSPORTER: string = 'transporter'
CLASS_CORVETTE: string = 'corvette'
CLASS_FRIGATE: string = 'frigate'
CLASS_DESTROYER: string = 'destroyer'
CLASS_CARRIER: string = 'carrier'
CLASS_BATTLESHIP: string = 'battleship'
CLASS_RESUPPLIER: string = 'resupplier'
CLASS_AUXILIARY: string = 'auxiliary'
CLASS_GUNBOAT: string = 'gunboat'
// ... (20+ class constants)
```

#### Methods
```php
static getInstance(): ShipClasses
getDefaultID(): string
getByID(string $id): ShipClass
getAll(): array // Returns ShipClass[]
```

---

### Mistralys\X4\Database\Ships\ShipSize

Ship size definition.

#### Methods
```php
__construct(string $id, string $label): void
getID(): string
getLabel(): string
```

---

### Mistralys\X4\Database\Ships\ShipSizes

Collection of ship sizes.

#### Constants
```php
SIZE_XS: string = 'xs'
SIZE_S: string = 's'
SIZE_M: string = 'm'
SIZE_L: string = 'l'
SIZE_XL: string = 'xl'
```

#### Methods
```php
static getInstance(): ShipSizes
getDefaultID(): string
idExists(string $id): bool
getByID(string $id): ShipSize
getAll(): array // Returns ShipSize[]
```

---

## Equipment Compatibility

### Mistralys\X4\Database\Ships\Equipment\ShipEquipmentFinder

Finder for filtering equipment compatible with a specific ship and slot type.

**Extends**: `BaseFinder`  
**Implements**: `DataSourceSelectionInterface`

#### Methods
```php
__construct(ShipDef $ship, string $slotTypeID): void
getCollection(): ItemCollectionInterface // Returns WareDefs instance
selectSize(string $size): self
selectTag(string $tag): self
selectDataSource(string|DataSourceDef $source): self
selectLabelSearch(string $searchTerm): self
getAll(): array // Returns WareDef[] matching all filters
```

**Usage:**
```php
// Find compatible engines for a ship
$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');
$engines = $ship->getEngines()
    ->selectDataSource('vanilla')
    ->selectSize('l')
    ->selectMinThrust(10000.0)
    ->getAll();

// Find compatible shields
$shields = $ship->getShields()
    ->selectMinCapacity(50000.0)
    ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
    ->getAll();

// Find compatible weapons
$weapons = $ship->getWeapons()
    ->selectTag('weapon')
    ->selectLabelSearch('Plasma')
    ->getAll();
```

---

## Usage Patterns

### Finding Ships by Criteria

```php
// Find all Argon large ships
$argonLargeShips = ShipDefs::getInstance()->findShips()
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->selectSize(ShipSizes::SIZE_L)
    ->getAll();

// Find all carriers regardless of faction
$carriers = ShipDefs::getInstance()->findShips()
    ->selectClass(ShipClasses::CLASS_CARRIER)
    ->getAll();

// Find fighters with specific label
$vanguards = ShipDefs::getInstance()->findShips()
    ->selectLabel('Vanguard')
    ->selectSize('s')
    ->getAll();
```

### Analyzing Ship Physics

```php
$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');

// Physics analysis
echo "Hull: " . $ship->getHull();
echo "Mass: " . $ship->getMass();
echo "Forward Drag: " . $ship->getDragForward();
echo "Pitch Inertia: " . $ship->getInertiaPitch();

// Physics tuning
$dragRatio = $ship->getDragForward() / $ship->getDragReverse();
$massToThrust = $ship->getMass() / $ship->getThrustForward();
```

### Equipment Configuration

```php
$ship = ShipDefs::getInstance()->getByID('ship_arg_m_frigate_01_a');

// Count equipment slots
echo "Weapons: " . $ship->countWeapons();
echo "Turrets: " . $ship->countTurrets();
echo "Shields: " . $ship->countShields();
echo "Engines: " . $ship->countEngines();

// Find optimal equipment
$bestEngine = $ship->getEngines()
    ->selectMinThrust(8000.0)
    ->selectMinBoostDuration(10.0)
    ->getAll()[0] ?? null;

$bestShield = $ship->getShields()
    ->selectMinCapacity(20000.0)
    ->selectMinRechargeRate(100.0)
    ->getAll()[0] ?? null;
```

### Engine Performance Comparison

```php
$engines = EngineDefs::getInstance()->findEngines()
    ->selectSize('l')
    ->selectMinThrust(15000.0)
    ->getAll();

foreach ($engines as $engine) {
    $thrustToMassRatio = $engine->getThrustForward() / $engine->getHullMax();
    $boostEfficiency = $engine->getBoostDuration() / $engine->getBoostRecharge();
    
    echo "{$engine->getLabel()}: ";
    echo "Thrust={$engine->getThrustForward()}, ";
    echo "Boost={$engine->getBoostThrust()}x, ";
    echo "Travel={$engine->getTravelThrust()}x";
}
```

---

## Notes

- **Physics Values**: All physics coefficients are extracted from game XML
- **Equipment Compatibility**: Based on slot types and ship equipment groups
- **Thrust Multipliers**: Boost and travel thrust are multipliers (e.g., 3.5x means 350% of base thrust)
- **Cargo Capacity**: Measured in cubic meters (m³)
- **Engine Selection**: Ships have specific engine sizes - ensure compatibility
- **Docking Bays**: Only capital ships have docking bays for smaller craft
- **Multi-Faction Ships**: Some ships can be built by multiple factions
