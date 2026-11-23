# Database Ships & Engines API Reference

> **Domain**: Ships, Engines, ShipClasses, ShipSizes, ShipSettings  
> **Last Updated**: November 22, 2026

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
- [Ship Value Classes](#ship-value-classes)

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

#### Macro Property Key Constants
The flat record keys read by `ShipMacroProperties::fromArray()`. Each constant is the upper-snake form of its string value.
```php
KEY_MACRO_ID = 'macroID'; KEY_CARGO_MACRO_ID = 'cargoMacroID'; KEY_ALIAS_MACRO_ID = 'aliasMacroID'; KEY_HAS_OWN_ACC_FACTORS = 'hasOwnAccFactors'
KEY_STEERING_CURVE = 'steeringCurve'
KEY_EXPLOSION_DAMAGE_HULL = 'explosionDamageHull'; KEY_EXPLOSION_DAMAGE_SHIELD = 'explosionDamageShield'; KEY_EXPLOSION_SHIELD_DISRUPTION = 'explosionShieldDisruption'
KEY_PURPOSE_ID = 'purposeID'; KEY_THRUSTER_SIZE = 'thrusterSize'; KEY_ICON_ID = 'iconID'; KEY_SECRECY_LEVEL = 'secrecyLevel'
KEY_DRONE_CAPACITY = 'droneCapacity'; KEY_DEPLOYABLE_CAPACITY = 'deployableCapacity'; KEY_COUNTERMEASURE_CAPACITY = 'countermeasureCapacity'
KEY_HANGAR_CAPACITY = 'hangarCapacity'; KEY_DEFAULT_SOFTWARE_IDS = 'defaultSoftwareIDs'; KEY_OPTIONAL_SOFTWARE_IDS = 'optionalSoftwareIDs'
KEY_TRAVEL_DRIVE_STABILITY = 'travelDriveStability'; KEY_GAS_GATHER_RATE = 'gasGatherRate'; KEY_RADAR_RANGE = 'radarRange'
KEY_WEAPON_HEAT_MODIFIER = 'weaponHeatModifier'; KEY_SHIELD_CAPACITY_MODIFIER = 'shieldCapacityModifier'
KEY_SHIELD_RECHARGE_RATE_MODIFIER = 'shieldRechargeRateModifier'; KEY_SHIELD_RECHARGE_DELAY_MODIFIER = 'shieldRechargeDelayModifier'
KEY_BASE_NAME_ID = 'baseNameID'; KEY_VARIATION_NAME_ID = 'variationNameID'; KEY_SHORT_VARIATION_NAME_ID = 'shortVariationNameID'
KEY_CAPTURABLE = 'capturable'; KEY_PRESTIGE = 'prestige'; KEY_BOOST_RECHARGE_DELAY = 'boostRechargeDelay'; KEY_BOOST_MIN_DURATION = 'boostMinDuration'
KEY_THREAT_SCORE = 'threatScore'; KEY_PLAYER_COVERABLE = 'playerCoverable'; KEY_DEFAULT_LOADOUT = 'defaultLoadout'
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
getCargoType(): string        // Raw space-separated storage tags, e.g. "container solid liquid", or "none". See getCargoTypeIDs()
```

**Macro Properties & Part A Delegators:**
```php
getMacroProperties(): ShipMacroProperties // Immutable aggregate; all other macro values are read through it
getMacroID(): string          // Macro name; read from ships.json (set by ShipsExtractor for every ship)
getMacro(): MacroFileDef      // Throws ShipException (ERROR_NO_MACRO_ID) if macroID is empty; never guesses "{wareID}_macro"
getCargoMacroID(): ?string    // Storage macro name, null if none
hasCargoMacro(): bool
getCargoMacro(): MacroFileDef // Throws ShipException (ERROR_NO_CARGO_MACRO_ID) if there is no storage macro
getAliasMacroID(): ?string    // Target of an alias macro, null otherwise
isAliasMacro(): bool
hasOwnAccFactors(): bool
```

> **Note**: Only these Part A values are delegated on `ShipDef`. Read all other macro values via `getMacroProperties()`, e.g. `$ship->getMacroProperties()->getHangar()`.

**Ware Delegators (same result as the same call on `getWare()`):**
```php
getPrice(): ?WarePrice
getProductions(): array       // Returns WareProduction[]
getLicenceID(): ?string
getDescription(?Language $language = null): string
getIllegalFactionIDs(): array // Returns string[]
```

**Cargo Helpers:**
```php
getCargoTypeIDs(): array      // string[]; the getCargoType() tokens, empty exactly when the type is "none"
canTransport(WareDef $ware): bool   // True when the ware's transportTypeID is among getCargoTypeIDs()
getCargoUnits(WareDef $ware): int   // intdiv(cargoCapacity, ware volume); 0 if not transportable or the volume is not positive
```

> **Note**: The `canTransport()` rule is derived: the game data shows that ware transport types and ship cargo types use matching vocabularies, but does not state the rule. It knows nothing about trade rules.

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

### Mistralys\X4\Database\Ships\ShipException

Extends `X4Exception`. Thrown by the `ShipDef` macro accessors.

```php
ERROR_NO_MACRO_ID: int = 144001        // Ship has no macro name; getMacro() cannot resolve a file
ERROR_NO_CARGO_MACRO_ID: int = 144002  // Ship has no storage macro; getCargoMacro() cannot resolve a file
ERROR_UNKNOWN_PURPOSE: int = 144003    // ShipFinder::selectPurpose() received a purpose ID that does not exist
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
selectPurpose(string $purposeID): self           // any; throws ShipException (144003) for an unknown purpose ID
selectThrusterSize(string|ShipSize $size): self   // any; matches the thruster size from the macro properties
selectCapturable(bool $capturable = true): self   // single value, last call wins
selectWithHangar(string|ShipSize $size): self     // all; hangar capacity above 0 for every selected size
selectWithSoftware(string|WareDef $software): self // all; software installable (default or optional); ware ID need not exist in WareDefs
selectDataSource(string|DataSourceDef $dataSource): self
selectLabel(string $label): self
getAll(): array // Returns ShipDef[]
```

**Combination rules:** Builder factions, classes, sizes, purposes and thruster sizes match ANY of the selected values. Hangar sizes and software match ALL selected values. `selectCapturable()` takes a single value; the last call wins. Different filter kinds always combine as AND. The ships that `selectCapturable(false)` returns come from more than one faction, so do not assume a single faction.

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

// Find trade ships that can run a given software
$traders = ShipDefs::getInstance()->findShips()
    ->selectPurpose(ShipPurposes::PURPOSE_TRADE)
    ->selectWithSoftware('software_dockmk2')
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

## Ship Value Classes

Immutable value objects (all `final`, readonly properties) in `Mistralys\X4\Database\Ships\MacroProperties`, plus the `ShipPurposes` constants class. Each value object has `fromArray()` and `toArray()`. `ShipMacroProperties` groups them; the flat `ships.json` keys are mapped onto them by `ShipMacroProperties::fromArray()`.

### Mistralys\X4\Database\Ships\MacroProperties\ShipSteeringCurve

Ordered list of steering curve points. The in-game meaning of `position` and `value` is unconfirmed, so the class offers no interpolation.

```php
__construct(ShipSteeringCurvePoint[] $points = [])
static fromArray(array $points): ShipSteeringCurve // [{position, value}, ...]
toArray(): array // [{position: float, value: float}, ...], order preserved
getPoints(): array // ShipSteeringCurvePoint[], document order
isEmpty(): bool
```

`ShipSteeringCurvePoint` has readonly `float $position` and `float $value`, `fromArray()` and `toArray()`.

### Mistralys\X4\Database\Ships\MacroProperties\ShipExplosionDamage

```php
__construct(?float $hull = null, ?float $shield = null, ?float $shieldDisruption = null)
static fromArray(array $data): ShipExplosionDamage
toArray(): array // keys hull, shield, shieldDisruption; null values omitted
isEmpty(): bool
```

`hull` is the game's `value` attribute.

### Mistralys\X4\Database\Ships\MacroProperties\ShipHangar

Hangar capacity per ship size (see `ShipSizes`), as a flat `{size: capacity}` map.

```php
__construct(array $capacities = []) // array<string,int>
static fromArray(array $data): ShipHangar
toArray(): array // {size: int}
getCapacity(string $size): int // 0 if the size has no capacity
getTotalCapacity(): int // plain sum of the map
getSizes(): array // string[] sizes with an entry
isEmpty(): bool
```

> **Note**: a bay usable by several sizes is counted once per size, so `getTotalCapacity()` over-counts for such ships.

### Mistralys\X4\Database\Ships\MacroProperties\ShipModifiers

```php
__construct(?float $weaponHeat = null, ?float $shieldCapacity = null, ?float $shieldRechargeRate = null, ?float $shieldRechargeDelay = null)
static fromArray(array $data): ShipModifiers
toArray(): array // keys weaponHeat, shieldCapacity, shieldRechargeRate, shieldRechargeDelay; null values omitted
isEmpty(): bool // true when all values are null
```

### Mistralys\X4\Database\Ships\ShipPurposes

Constants class for the ship purpose IDs.

```php
PURPOSE_FIGHT = 'fight'; PURPOSE_TRADE = 'trade'; PURPOSE_MINE = 'mine'; PURPOSE_BUILD = 'build';
PURPOSE_AUXILIARY = 'auxiliary'; PURPOSE_RACING = 'racing'; PURPOSE_SALVAGE = 'salvage'; PURPOSE_DISMANTLING = 'dismantling';

static getIDs(): array // string[], all eight purposes
static idExists(string $id): bool
```

### Mistralys\X4\Database\Ships\MacroProperties\ShipSoftware

Software wares of a ship, kept as ware IDs.

```php
__construct(array $defaultWareIDs = [], array $optionalWareIDs = [])
static fromArray(array $data): ShipSoftware
toArray(): array // {defaultWareIDs: string[], optionalWareIDs: string[]}
getDefaultSoftware(): array // WareDef[]; unresolved ware IDs are skipped
getOptionalSoftware(): array // WareDef[]; unresolved ware IDs are skipped
isDefault(string $wareID): bool
isOptional(string $wareID): bool
isInstallable(string $wareID): bool
isEmpty(): bool
```

> **Notes**: "optional" means compatible software: the installable extras, which never overlap the defaults. Software is resolved by **ware ID**, never by macro: all software wares share the macro `software_dummy_macro`. Unresolved IDs stay in the ID lists (the `is*()` methods still answer true) and are only skipped when resolving to `WareDef`.

### Mistralys\X4\Database\Ships\MacroProperties\ShipLoadout

Flat list of the ship's built-in default loadout. Placement data (connections, groups) is deliberately not kept.

```php
__construct(ShipLoadoutItem[] $items = [])
static fromArray(array $items): ShipLoadout // list of ShipLoadoutItem arrays
toArray(): array
getItems(): array // ShipLoadoutItem[]
getItemsByType(string $type): array // ShipLoadoutItem[]
countByType(string $type): int
getWares(): array // WareDef[], only for resolvable items
isEmpty(): bool
```

### Mistralys\X4\Database\Ships\MacroProperties\ShipLoadoutItem

```php
TYPE_ENGINE, TYPE_SHIELD, TYPE_WEAPON, TYPE_TURRET, TYPE_THRUSTER, TYPE_SOFTWARE, TYPE_AMMUNITION

__construct(string $type, string $macroID, int $count = 1, bool $optional = false)
static getTypes(): array // string[], the TYPE_* values
static fromArray(array $data): ShipLoadoutItem // {type, macroID, count, optional}
toArray(): array // no wareID key
getWare(): ?WareDef // resolved at runtime via WareDefs::findByMacro(); null if unknown
```

> **Note**: for software items, `macroID` holds the software **ware ID**, and `getWare()` is only correct in that case. The ware is never stored in the JSON, so it cannot drift from `wares.json`.

### Mistralys\X4\Database\Ships\MacroProperties\ShipMacroProperties

Immutable aggregate of the values read from a ship's macro file that are not part of the core `ShipDef` stats. Future macro data is added here so the positional `ShipDef` constructor stops growing. All constructor properties are readonly and have defaults, so `fromArray([])` yields a valid default instance.

```php
static fromArray(array $shipRecord): ShipMacroProperties // flat ShipDef::KEY_* keys
toArray(): array

// Macro links
getMacroID(): string
getCargoMacroID(): ?string
getAliasMacroID(): ?string
hasOwnAccFactors(): bool

// Values
getSteeringCurve(): ShipSteeringCurve
getExplosionDamage(): ?ShipExplosionDamage // null when all three values are absent
getPurposeID(): string
getThrusterSizeID(): string
getThrusterSize(): ShipSize
getIconID(): string
getSecrecyLevel(): ?int
getDroneCapacity(): int
getDeployableCapacityOverride(): ?int // null = game default
getCountermeasureCapacityOverride(): ?int // null = game default
getHangar(): ShipHangar
getHangarCapacity(string $size): int
hasHangar(): bool
getSoftware(): ShipSoftware
getTravelDriveStability(): ?float
getGasGatherRate(): ?float
getRadarRangeOverride(): ?float // null = game default
getModifiers(): ShipModifiers
isCapturable(): bool
getPrestige(): ?int
getBoostRechargeDelay(): ?float
getBoostMinDuration(): ?float
getThreatScore(): ?int
isPlayerCoverable(): bool // raw game flag, meaning undocumented
getDefaultLoadout(): ?ShipLoadout
hasDefaultLoadout(): bool

// Names (language defaults to English; falls back to English, then empty string; never a raw {page,id} code)
getBaseName(?Language $language = null): string
getVariationName(?Language $language = null): string
getShortVariationName(?Language $language = null): string
getBaseNameID(): ?string
getVariationNameID(): ?string
getShortVariationNameID(): ?string
```

**Array form:** flat, self-describing keys. `toArray()` always writes `macroID`, `hasOwnAccFactors`, `steeringCurve`, `purposeID`, `thrusterSize`, `iconID`, `droneCapacity`, `hangarCapacity`, `defaultSoftwareIDs`, `optionalSoftwareIDs`, `capturable` and `playerCoverable`, and omits every nullable key when null. Nesting is used only for `steeringCurve`, `defaultLoadout` and the `hangarCapacity` size map; there are no `explosionDamage`, `modifiers`, `software` or `hangar` object keys.

> **Supported API**: use the accessors. The public readonly constructor properties are an implementation detail and may change.
>
> **Default instance**: `getThrusterSize()` throws on a default instance, because the thruster size ID defaults to an empty string and `ShipSizes` has no empty ID. Check `getThrusterSizeID()` first when the instance may be a default.
>
> **Steering curve**: the point names are kept from the game; their in-game meaning is unconfirmed.

### Mistralys\X4\Database\Ships\MacroProperties\ShipCargoInfo, ShipMacroConnection and ShipMacroConnectionResolver

Build-time helpers. `ShipCargoInfo` holds readonly `int $capacity` (0), `string $type` (`none`) and `?string $macroID` (null) for a ship's storage. `ShipMacroConnectionResolver::resolve(DOMExtended $dom, string $dataSourceID, string $connectionRefPrefix = ''): ShipMacroConnection[]` returns resolved child macros in document order; see [patterns.md](../extraction-reference/patterns.md).

### Mistralys\X4\Database\Ships\MacroProperties\ShipMacroPropertiesExtractor

Build-time per-ship reader (same role as `ModuleMacroExtractor` for modules). It reads the ship's macro properties and returns the record in `ShipMacroProperties::toArray()` format, keyed by `ShipDef::KEY_*`.

```php
__construct(DOMExtended $dom, ?DOMExtended $aliasDom, ?string $aliasMacroID, string $dataSourceID, string $shipID)
extract(): array // Part B keys plus aliasMacroID and hasOwnAccFactors
```

- **No macro links:** `extract()` omits `macroID` and `cargoMacroID`; `ShipsExtractor` must set them when it merges the record.
- **Lookup order:** the ship's own macro first, then the alias target, attribute by attribute. Steering curve, software and default loadout come whole from the first DOM that has them.
- **`hasOwnAccFactors`:** inspects only the own macro (a partial `<accfactors>` counts); the alias target is ignored.
- **Hangar:** capacity is summed per size from child macros whose `properties/dock` has `storage="1"`, resolved through `ShipMacroConnectionResolver`. Bays marked `hidden="1"` are skipped. Without own bays, the alias connections are used. A bay listing several sizes is added to each size and logs a `WARNING`.
- **Default loadout:** read from `<loadout id="default">`, flattened to normalized types with counts and merged by type, macro and `optional`. A single macro counts 1, a group counts its `exact` value or, when absent, its max, and ammunition counts its max. Entries without a macro or ware attribute and unknown element names log a `WARNING` and are skipped. No ware lookup happens at extraction time.
- **Warnings:** unknown thruster tags and unknown purposes log a `WARNING` and keep the raw value; software ware IDs missing from `WareDefs` log a `NOTICE` and are kept.

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
