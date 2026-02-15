# Database Equipment API Reference

> **Domain**: Weapons, Shields, Modules, WeaponSystems  
> **Last Updated**: February 15, 2026

[← Back to API Index](README.md)

---

## Overview

The Equipment namespace contains all ship-mountable items:

- **Weapons**: All weapon systems with damage, range, DPS, and heat characteristics
- **Shields**: Shield generators with capacity, recharge rates, and types
- **Modules**: Station modules (production, storage, habitation, defense)
- **WeaponSystems**: Weapon type categorization (turrets, missiles, standard)

---

## Table of Contents

- [Weapons](#weapons)
- [Weapon Systems](#weapon-systems)
- [Shields](#shields)
- [Modules](#modules)

---

## Weapons

### Mistralys\X4\Database\Weapons\WeaponDef

Definition of a weapon with complete performance characteristics.

#### Key Constants (selected)
```php
KEY_WARE_ID: string = 'wareID'
KEY_SIZE: string = 'size'
KEY_MAKER_RACE: string = 'makerRace'
KEY_MK: string = 'mk'
KEY_WEAPON_SYSTEM: string = 'weaponSystem'
KEY_BULLET_SPEED: string = 'bulletSpeed'
KEY_BULLET_RANGE: string = 'bulletRange'
KEY_RELOAD_RATE: string = 'reloadRate'
KEY_DAMAGE_VALUE: string = 'damageValue'
KEY_HEAT_OVERHEAT: string = 'heatOverheat'
KEY_ROTATION_SPEED: string = 'rotationSpeed'
```

#### Methods
```php
static fromArray(mixed $weaponDef): WeaponDef
getID(): string
getLabel(): string
getVariantID(): VariantID
getWareID(): string
getMacroID(): string
getBulletClass(): string
getSize(): string
getDataSourceID(): string
getMakerRace(): string
getMakerRaces(): array // Returns string[]
hasMultipleMakerRaces(): bool
getMk(): int

// Weapon Classification
getWeaponSystem(): string
getWeaponCategory(): string

// Heat Management
getHeatOverheat(): float
getHeatCooldelay(): float
getHeatCoolrate(): float
getHeatReenable(): float
getHeatPerShot(): float
getShotsUntilOverheat(): float
getTimeUntilOverheat(): float
getCooldownTime(): float

// Targeting & Rotation
getRotationSpeed(): float
getRotationAcceleration(): float

// Durability
getHullMax(): float
getHullHittable(): int
isHullHittable(): bool

// Ammunition
getAmmoValue(): float
getAmmoReload(): float

// Projectile Characteristics
getBulletSpeed(): float
getBulletLifetime(): float
getBulletRange(): float
getBulletAmount(): int
getBulletBarrelamount(): int
getBulletTimediff(): float
getBulletAngle(): float
getBulletMaxhits(): int
getBulletRicochet(): int
canRicochet(): bool
getBulletAttach(): int
canAttach(): bool

// Performance
getReloadRate(): float
getDamageValue(): float
getRepairValue(): float
isRepairWeapon(): bool
getDPS(): float

// Type Checking
isTurret(): bool
isBeamWeapon(): bool
isMissileWeapon(): bool
isMiningWeapon(): bool
```

**Usage:**
```php
$weapon = WeaponDefs::getInstance()->getByID('weapon_arg_m_plasmagun_01_mk1');
echo $weapon->getLabel();       // "Argon M Plasma Gun Mk1"
echo $weapon->getDamageValue(); // 1500.0
echo $weapon->getDPS();         // 750.0 (damage per second)
echo $weapon->getBulletRange(); // 6000.0 (meters)
echo $weapon->getBulletSpeed(); // 800.0 (m/s)
echo $weapon->isTurret();       // false
```

---

### Mistralys\X4\Database\Weapons\WeaponDefs

Collection of all weapon definitions.

#### Constants
```php
DATA_FILE: string = 'weapons.json'
ERROR_WEAPON_NOT_FOUND: int = 143001
```

#### Methods
```php
static getInstance(): WeaponDefs
getDataFile(): JSONFile
find(string $idOrMacro): ?WeaponDef
findByMacro(string $macro): ?WeaponDef
findByBulletClass(string $bulletClass): ?WeaponDef
getByWeaponSystem(string $weaponSystem): array // Returns WeaponDef[]
getByCategory(string $category): array // Returns WeaponDef[]
getDefaultID(): string
findWeapons(): WeaponFinder
getByID(string $id): WeaponDef
getAll(): array // Returns WeaponDef[]
```

---

### Mistralys\X4\Database\Weapons\WeaponFinder

Finder for filtering weapon collections.

#### Methods
```php
getCollection(): ItemCollectionInterface
selectSize(string $size): self
selectSizes(array $sizes): self
selectMakerRace(string $race): self
selectWeaponSystem(string $system): self
selectWeaponCategory(string $category): self
selectMk(int $mk): self
selectMinMk(int $minMk): self
selectMinDamage(float $minDamage): self
selectMaxDamage(float $maxDamage): self
selectMinDPS(float $minDPS): self
selectMaxDPS(float $maxDPS): self
selectMinRange(float $minRange): self
selectMaxRange(float $maxRange): self
selectMinReloadRate(float $minReloadRate): self
selectMaxReloadRate(float $maxReloadRate): self
selectMinBulletSpeed(float $minBulletSpeed): self
selectMinRotationSpeed(float $minRotationSpeed): self
selectTurret(bool $isTurret = true): self
selectBeamWeapons(bool $isBeam = true): self
selectMissileWeapons(bool $isMissile = true): self
selectMiningWeapons(bool $isMining = true): self
selectRepairWeapons(bool $isRepair = true): self
selectDataSource(string|DataSourceDef $dataSource): self
sortByDPS(): self
sortByDamage(): self
sortByRange(): self
sortByReloadRate(): self
sortByLabel(): self
getAll(): array // Returns WeaponDef[]
```

**Usage:**
```php
// Find high-DPS medium weapons
$weapons = WeaponDefs::getInstance()->findWeapons()
    ->selectSize('m')
    ->selectMinDPS(1000.0)
    ->selectMinRange(5000.0)
    ->sortByDPS()
    ->getAll();

// Find turrets with good rotation
$turrets = WeaponDefs::getInstance()->findWeapons()
    ->selectTurret(true)
    ->selectMinRotationSpeed(50.0)
    ->selectMinDamage(1000.0)
    ->getAll();

// Find mining lasers
$miningLasers = WeaponDefs::getInstance()->findWeapons()
    ->selectMiningWeapons(true)
    ->getAll();
```

---

## Weapon Systems

### Mistralys\X4\Database\WeaponSystems\WeaponSystems

Singleton collection of all weapon system types. Provides centralized metadata for weapon system classification.

#### Methods
```php
static getInstance(): WeaponSystems
getCollectionName(): string // Returns 'Weapon Systems'
getCollectionDescription(): string
getDefaultID(): string // Returns 'weapon_standard'
getAll(): array // Returns WeaponSystem[]
getByID(string $id): ?WeaponSystem
idExists(string $id): bool
getIDs(): array // Returns string[]
isKnownSystem(string $systemID): bool
requireKnownSystem(string $systemID): void // Throws WeaponException if unknown
getTurretSystems(): array // Returns WeaponSystem[]
getMissileSystems(): array // Returns WeaponSystem[]
getStandardWeaponSystems(): array // Returns WeaponSystem[]
```

**Usage:**
```php
$systems = WeaponSystems::getInstance();
$shortRange = $systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
echo $shortRange->getLabel(); // "Short-Range Turret"

// Validate weapon system during extraction
$systems->requireKnownSystem('turret_shortrange'); // OK
$systems->requireKnownSystem('fake_system'); // Throws WeaponException
```

---

### Mistralys\X4\Database\WeaponSystems\WeaponSystem

Represents a single weapon system type with metadata.

#### Constants
```php
KEY_LABEL: string = 'label'
KEY_DESCRIPTION: string = 'description'
```

#### Methods
```php
getID(): string
getVariantID(): VariantID
getLabel(): string // Human-readable label (e.g., 'Short-Range Turret')
getDescription(): string // Detailed description
isTurret(): bool
isMissile(): bool
isStandardWeapon(): bool
```

---

### Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems

Type-safe constants for weapon system IDs.

#### Constants
```php
TURRET_SHORTRANGE: string = 'turret_shortrange'
TURRET_MIDRANGE: string = 'turret_midrange'
TURRET_LONGRANGE: string = 'turret_longrange'
WEAPON_STANDARD: string = 'weapon_standard'
WEAPON_MINING: string = 'weapon_mining'
MISSILE_DUMBFIRE: string = 'missile_dumbfire'
MISSILE_GUIDED: string = 'missile_guided'
TORPEDO: string = 'torpedo'
```

**Usage:**
```php
use Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems;

$weapons = WeaponDefs::getInstance()->findWeapons()
    ->selectWeaponSystem(KnownWeaponSystems::TURRET_SHORTRANGE)
    ->selectMinDPS(1000.0)
    ->getAll();
```

---

## Shields

### Mistralys\X4\Database\Shields\ShieldException

Exception for shield-related errors.

#### Constants
```php
ERROR_SHIELD_NOT_FOUND: int = 143001
ERROR_INVALID_SHIELD_SIZE: int = 143002
ERROR_INVALID_SHIELD_DATA: int = 143003
ERROR_INVALID_SHIELD_TYPE: int = 143004
```

---

### Mistralys\X4\Database\Shields\ShieldDef

Definition of a shield with performance characteristics.

#### Key Constants (selected)
```php
KEY_WARE_ID: string = 'wareID'
KEY_SIZE: string = 'size'
KEY_MAKER_RACE: string = 'makerRace'
KEY_MK: string = 'mk'
KEY_SHIELD_TYPE: string = 'shieldType'
KEY_RECHARGE_MAX: string = 'rechargeMax'
KEY_RECHARGE_RATE: string = 'rechargeRate'
KEY_RECHARGE_DELAY: string = 'rechargeDelay'
KEY_HULL_INTEGRATED: string = 'hullIntegrated'
```

#### Methods
```php
static fromArray(mixed $shieldDef): ShieldDef
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

// Shield Classification
getShieldType(): string
isStandard(): bool
isRacer(): bool
isCorvette(): bool
isMothership(): bool
isYacht(): bool
isExperimental(): bool
isVirtual(): bool

// Shield Performance
getRechargeMax(): float      // Maximum shield capacity
getRechargeRate(): float     // Recharge per second
getRechargeDelay(): float    // Delay before recharge starts
getCapacity(): float         // Alias for getRechargeMax()
getFullRechargeTime(): float // Time to fully recharge from 0

// Hull Integration
getHullMax(): float
getHullThreshold(): float
isHullIntegrated(): bool
hasHull(): bool

getWareID(): string
getWare(): WareDef
```

**Usage:**
```php
$shield = ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1');
echo $shield->getLabel();          // "Argon L Shield Mk1"
echo $shield->getCapacity();       // 50000.0
echo $shield->getRechargeRate();   // 150.0 per second
echo $shield->getRechargeDelay();  // 4.0 seconds
echo $shield->getFullRechargeTime(); // 333.33 seconds
echo $shield->isStandard();        // true
```

---

### Mistralys\X4\Database\Shields\ShieldDefs

Collection of all shield definitions.

#### Constants
```php
DATA_FILE: string = 'shields.json'
ERROR_SHIELD_NOT_FOUND: int = 143001
```

#### Methods
```php
static getInstance(): ShieldDefs
getDataFile(): JSONFile
find(string $idOrMacro): ?ShieldDef
findByMacro(string $macro): ?ShieldDef
getByType(string $type): array // Returns ShieldDef[]
getDefaultID(): string
findShields(): ShieldFinder
getByID(string $id): ShieldDef
getAll(): array // Returns ShieldDef[]
```

---

### Mistralys\X4\Database\Shields\ShieldFinder

Finder for filtering shield collections.

#### Methods
```php
getCollection(): ItemCollectionInterface
selectSize(string $size): self
selectSizes(array $sizes): self
selectMakerRace(string $race): self
selectMk(int $mk): self
selectMinMk(int $minMk): self
selectType(string $type): self
selectTypes(array $types): self
selectMinCapacity(float $minCapacity): self
selectMaxCapacity(float $maxCapacity): self
selectMinRechargeRate(float $minRate): self
selectMaxRechargeDelay(float $maxDelay): self
selectMinHull(float $minHull): self
selectWithHull(): self
selectIntegrated(): self
selectNonIntegrated(): self
selectDataSource(string|DataSourceDef $dataSource): self
selectLabelSearch(string $searchTerm): self
getAll(): array // Returns ShieldDef[]
```

**Usage:**
```php
// Find high-capacity shields with fast recharge
$shields = ShieldDefs::getInstance()->findShields()
    ->selectSize('l')
    ->selectMinCapacity(50000.0)
    ->selectMinRechargeRate(150.0)
    ->selectMaxRechargeDelay(5.0)
    ->getAll();

// Find integrated shields
$integratedShields = ShieldDefs::getInstance()->findShields()
    ->selectIntegrated()
    ->selectMinCapacity(20000.0)
    ->getAll();
```

---

## Modules

### Mistralys\X4\Database\Modules\ModuleDef

Module definition (station production, storage, defense, and habitation modules).

#### Key Constants (selected)
```php
KEY_CATEGORY: string = 'category'
KEY_BUILDER_FACTION_ID: string = 'builderFactionID'
KEY_WARES_PRODUCED: string = 'waresProduced'
KEY_HULL: string = 'hull'
KEY_CARGO_CAPACITY: string = 'cargoCapacity'
KEY_CARGO_TYPE: string = 'cargoType'
KEY_DRONE_CAPACITY: string = 'droneCapacity'
KEY_HOUSING_CAPACITY: string = 'housingCapacity'
```

#### Methods
```php
__construct(string $wareID, string $label, string $categoryID, string $macroID, string $builderFactionID, string $size, int $hull, int $droneCapacity, int $cargoCapacity, string $cargoType, int $housingCapacity, string $housingFactionID, VariantID $variantID, array $waresProduced): void
static fromArray(mixed $moduleDef): ModuleDef
getID(): string
getLabel(): string
getVariantID(): VariantID
getMacroID(): string
getSize(): string
getHullHitpoints(): int
getDroneCapacity(): int
getCargoCapacity(): int
getCargoType(): string
getHousingCapacity(): int
getHousingFactionID(): string
getMacros(): array // Returns string[]
getCategoryID(): string
getCategory(): ModuleCategory
getBuilderFaction(): FactionDef
getBuilderFactionID(): string
getBuilderFactionIDs(): array // Returns string[]
getBuilderFactions(): array // Returns FactionDef[]
hasMultipleBuilderFactions(): bool
isProduction(): bool
getProducedWares(): array // Returns string[]
getWareID(): string
getWare(): WareDef
```

**Usage:**
```php
$module = ModuleDefs::getInstance()->getByID('module_arg_prod_energycells_01');
echo $module->getLabel();           // "Argon Energy Cell Production"
echo $module->getCategoryID();      // "production"
echo $module->getHullHitpoints();   // 15000
echo $module->isProduction();       // true
print_r($module->getProducedWares()); // ['energycells']
```

---

## Usage Patterns

### Comparing Weapon Performance

```php
// Find best DPS weapons in size category
$weapons = WeaponDefs::getInstance()->findWeapons()
    ->selectSize('m')
    ->selectMinRange(5000.0)
    ->sortByDPS()
    ->getAll();

foreach ($weapons as $weapon) {
    echo "{$weapon->getLabel()}: ";
    echo "DPS={$weapon->getDPS()}, ";
    echo "Range={$weapon->getBulletRange()}, ";
    echo "Speed={$weapon->getBulletSpeed()}";
}
```

### Shield Selection for Ship

```php
$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');

// Find optimal shield
$shields = $ship->getShields()
    ->selectMinCapacity($ship->getHull() * 0.5) // 50% of hull
    ->selectMinRechargeRate(100.0)
    ->selectMaxRechargeDelay(5.0)
    ->getAll();

$bestShield = $shields[0] ?? null;
if ($bestShield) {
    $rechargeEfficiency = $bestShield->getCapacity() / $bestShield->getFullRechargeTime();
    echo "Best shield: {$bestShield->getLabel()}";
    echo " (Efficiency: {$rechargeEfficiency} HP/s)";
}
```

### Weapon System Analysis

```php
$systems = WeaponSystems::getInstance();

// Analyze turret systems
foreach ($systems->getTurretSystems() as $system) {
    $weapons = WeaponDefs::getInstance()->findWeapons()
        ->selectWeaponSystem($system->getID())
        ->getAll();
    
    echo "{$system->getLabel()}: " . count($weapons) . " weapons";
}
```

### Module Filtering

```php
// Find production modules
$productionModules = ModuleDefs::getInstance()->findModules()
    ->selectCategory('production')
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->getAll();

foreach ($productionModules as $module) {
    echo $module->getLabel() . " produces: ";
    echo implode(', ', $module->getProducedWares());
}
```

---

## Notes

- **DPS Calculation**: Damage Per Second accounts for reload rate and heat management
- **Shield Recharge**: Delay before recharge begins after taking damage
- **Weapon Heat**: Sustained fire causes overheating - monitor shots/time until overheat
- **Turret Rotation**: Higher rotation speed = better tracking of fast targets
- **Module Production**: Only production modules have `waresProduced` data
- **Size Compatibility**: Equipment must match ship slot size (s, m, l, xl)
- **Multi-Faction Equipment**: Some items can be manufactured by multiple factions
