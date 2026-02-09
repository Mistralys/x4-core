# Weapon Metadata System - Implementation Plan

> **Created:** February 9, 2026  
> **Status:** Not Started  
> **Estimated Total Time:** 8-10 hours  
> **Dependencies:** x4-core project, x4-data-extractor with extracted game data

---

## 🎯 Project Overview

### Objective
Add detailed weapon performance data to x4-core by creating a new `weapons.json` data file that stores weapon characteristics (damage, heat, reload rate, range, bullet properties, rotation speed) linked to existing `wares.json` entries via wareID.

### Business Value
- Enable detailed weapon comparison in UI
- Support advanced filtering by weapon type and performance characteristics
- Provide foundation for ship loadout optimization features
- Complete equipment metadata beyond basic ware information
- Allow combat effectiveness analysis

### Architectural Approach
Follow the established Collection-Item pattern used throughout x4-core:
- **WeaponDef** (item) - Individual weapon with all properties
- **WeaponDefs** (collection) - Singleton managing all weapons  
- **WeaponFinder** (filter) - Query interface for filtering
- **WeaponsExtractor** (builder) - Extracts data from X4 XML files

### Key Design Decisions

| Decision | Rationale |
|----------|-----------|
| **Separate weapons.json** | Keep performance data isolated from basic ware info, follows existing pattern (modules.json, ships.json, engines.json) |
| **Link via wareID** | Maintains existing ware system, adds stats on top without duplication |
| **Two-file extraction** | Weapons reference bullet macros - need both weapon AND bullet XML data |
| **All properties extracted** | Include damage, heat, reload, rotation, range, bullet speed for maximum UI flexibility |
| **WeaponFinder included** | Consistent with WaresFinder/ShipsFinder/ModulesFinder/EngineFinder patterns |
| **Extract after wares** | Weapons depend on WareDefs for filtering by group |
| **All DLCs supported** | Complete dataset matching other collections (vanilla + 7 expansions) |
| **Weapon system categorization** | Group weapons by type (standard, beam, missile, etc.) for filtering |

### System Context

**Current State:**
- Weapons exist in `wares.json` as basic ware entries (ID, label, size, tags)
- Performance data exists in X4 macro XML files but is **NOT extracted**
- Weapon location: `x4-data-extractor/output/{datasource}/assets/props/WeaponSystems/*/macros/*_macro.xml`
- Bullet location: `x4-data-extractor/output/{datasource}/assets/fx/weaponFx/macros/bullet_*_macro.xml`

**After Implementation:**
- `data/weapons.json` contains performance data for all weapons across all DLCs
- `WeaponDefs::getInstance()->getByID('weapon_gen_s_laser_01_mk1')` returns full stats
- `WeaponDefs::getInstance()->findWeapons()->selectSize('s')->selectWeaponSystem('standard')->getAll()` filters weapons
- Build process: `composer build` or `composer extract-weapons`

### Weapon Properties Available in XML

From weapon macro XML files (example: `weapon_gen_s_laser_01_mk1_macro.xml`):

```xml
<properties>
  <identification name="{20105,1004}" basename="{20105,1001}" shortname="{20105,1005}" 
                  description="{20105,1002}" mk="1" />
  <bullet class="bullet_gen_s_laser_01_mk1_macro" />
  <heat overheat="10000" cooldelay="1.13" coolrate="2000" reenable="9500" />
  <rotationspeed max="165.4" />
  <rotationacceleration max="330.8" />
  <hull max="500" hittable="0" />
  <effects>
    <firing start="startshooting_gen_s_laser_01_mk1" stop="stopshooting_gen_s_laser_01_mk1" />
  </effects>
</properties>
```

From bullet macro XML files (example: `bullet_gen_s_laser_01_mk1_macro.xml`):

```xml
<properties>
  <ammunition value="2" reload="0.5" />
  <bullet speed="4147" lifetime="0.75" amount="1" barrelamount="1" icon="weapon_laser_mk1" 
          timediff="0.018" angle="0.3" maxhits="1" ricochet="0" scale="0" attach="0" />
  <heat value="53" />
  <reload rate="7" />
  <damage value="32" repair="0" />
  <effects>
    <impact ref="impact_gen_s_laser_01_mk1" inside="impact_gen_s_laser_01_mk1_inside" />
    <bigobjectimpact ref="impact_gen_s_laser_01_mk1_bigobject" 
                     inside="impact_gen_s_laser_01_mk1_bigobject_inside" />
    <launch ref="muzzle_gen_s_laser_01_mk1" />
  </effects>
  <weapon system="weapon_standard" />
</properties>
```

**Extractable Fields:**

**From Weapon Macro:**
- **Identification:** mk (mark level)
- **Heat:** overheat, cooldelay, coolrate, reenable (4 fields)
- **Rotation:** rotationspeed max, rotationacceleration max (2 fields)
- **Hull:** max hull, hittable flag (2 fields)
- **Bullet Reference:** bullet class name (links to bullet macro)

**From Bullet Macro:**
- **Ammunition:** value, reload time (2 fields)
- **Bullet:** speed, lifetime, amount, barrelamount, icon, timediff, angle, maxhits, ricochet, scale, attach (11 fields)
- **Bullet Range:** calculated as speed × lifetime (1 derived field)
- **Heat:** heat value per shot (1 field)
- **Reload:** reload rate (1 field)
- **Damage:** damage value, repair value (2 fields)
- **Weapon System:** weapon system type (standard/beam/missile/etc.)

**Total:** ~25 extractable properties + metadata (wareID, macroID, label, size, makerRace, mk, dataSourceID, weaponType)

### Weapon Categories in X4

Based on folder structure in `assets/props/WeaponSystems/`:
- **capital** - Capital ship weapons
- **dumbfire** - Unguided rockets
- **energy** - Beam and ion weapons
- **guided** - Guided missiles
- **heavy** - Heavy weapons (flak, plasma)
- **mines** - Mine launchers
- **mining** - Mining lasers
- **missile** - Missile launchers
- **spacesuit** - Handheld weapons
- **standard** - Standard lasers and shotguns
- **torpedo** - Torpedo launchers

### Required Manifest Updates

Per [AGENTS.md](../../AGENTS.md#manifest-maintenance-rules) change-to-document mapping:

| Manifest Document | Required Updates | Sections |
|-------------------|------------------|----------|
| [file-tree.md](../project-manifest/file-tree.md) | Add Weapons/ folder under Database/ | `src/X4/Database/` |
| [public-api.md](../project-manifest/public-api.md) | Add Database\Weapons namespace | New section with all public methods |
| [data-flows.md](../project-manifest/data-flows.md) | Add Weapon Extraction Flow | New diagram showing XML → WeaponDef → JSON |
| [tech-stack.md](../project-manifest/tech-stack.md) | Add weapons.json as data file | Data Files section, Collection-Item examples |
| [constraints.md](../project-manifest/constraints.md) | No changes needed | Follows existing patterns |

---

## 📦 Work Package Breakdown

This plan is divided into **5 independent work packages** that can be implemented incrementally. Each package includes complete context for pickup without prior session knowledge.

### Package Dependencies

```
WP1 (WeaponDef) ─┐
                 ├─> WP2 (WeaponDefs) ─┐
                 │                      ├─> WP4 (Integration)
                 └─> WP3 (WeaponFinder)─┘
                                        │
                                        └─> WP5 (Docs & Tests)
```

**Implementation Order:**
1. WP1 → WP2 → WP3 can be done in any order (no dependencies)
2. WP4 requires WP1, WP2, WP3 complete
3. WP5 requires WP4 complete

---

## 🔨 Work Package 1: WeaponDef Item Class

**Status:** Not Started  
**Estimated Time:** 2 hours  
**Dependencies:** None  
**Assigned To:** Unassigned

### Goal
Create the item class representing a single weapon with all performance properties.

### Context
- **Pattern:** Collection-Item (see [tech-stack.md](../project-manifest/tech-stack.md#collection-item-pattern))
- **Example:** Study `src/X4/Database/Modules/ModuleDef.php` and `src/X4/Database/Engines/EngineDef.php` for similar structure
- **Constraints:** [constraints.md](../project-manifest/constraints.md) - Immutable items, type declarations required

### Files to Create

#### `src/X4/Database/Weapons/WeaponDef.php`

**Class Structure:**
```php
<?php
namespace X4\Database\Weapons;

use X4\Database\CollectionItemInterface;
use X4\Database\CollectionItemTrait;

/**
 * Represents a single weapon with performance characteristics.
 * 
 * Links to WareDef via wareID for basic ware info (label, tags, price).
 * Stores weapon-specific performance data extracted from X4 weapon and bullet macro XML.
 */
class WeaponDef implements CollectionItemInterface
{
    use CollectionItemTrait;
    
    // Core identification
    private string $wareID;        // Primary key, links to wares.json
    private string $macroID;       // Weapon macro reference
    private string $bulletClass;   // Bullet macro reference
    private string $label;         // Display name (from ware)
    private string $size;          // s/m/l/xl
    private string $dataSourceID;  // vanilla/ego_dlc_split/etc
    private string $makerRace;     // argon/paranid/teladi/etc
    private int $mk;               // Mark level (1/2/3)
    private string $weaponSystem;  // weapon_standard/weapon_beam/weapon_missile/etc
    private string $weaponCategory; // standard/energy/heavy/mining/etc (from folder)
    
    // Weapon heat properties (4 fields)
    private float $heatOverheat;   // Overheat threshold
    private float $heatCooldelay;  // Cooldown delay after stopping fire
    private float $heatCoolrate;   // Cooling rate per second
    private float $heatReenable;   // Heat level when weapon re-enables
    
    // Weapon rotation (2 fields)
    private float $rotationSpeed;        // Max rotation speed (deg/s)
    private float $rotationAcceleration; // Max rotation acceleration
    
    // Weapon hull (2 fields)
    private float $hullMax;      // Maximum hull points
    private int $hullHittable;   // Can be targeted (0/1)
    
    // Ammunition properties (2 fields)
    private float $ammoValue;    // Ammunition value
    private float $ammoReload;   // Reload time per ammo
    
    // Bullet properties (11 fields)
    private float $bulletSpeed;       // Bullet velocity (m/s)
    private float $bulletLifetime;    // Bullet lifetime (seconds)
    private float $bulletRange;       // Calculated: speed × lifetime
    private int $bulletAmount;        // Bullets per shot
    private int $bulletBarrelamount;  // Barrels firing
    private string $bulletIcon;       // Icon reference
    private float $bulletTimediff;    // Time between bullets in burst
    private float $bulletAngle;       // Spread angle
    private int $bulletMaxhits;       // Max hits per bullet
    private int $bulletRicochet;      // Can ricochet (0/1)
    private int $bulletAttach;        // Attaches to target (0/1)
    
    // Combat properties (3 fields)
    private float $heatPerShot;  // Heat generated per shot
    private float $reloadRate;   // Reload rate
    private float $damageValue;  // Damage per hit
    private float $repairValue;  // Repair value (for repair lasers)
    
    // Constructor (private - use fromArray)
    private function __construct() {}
    
    // Static factory (required by pattern)
    public static function fromArray(array $data): self
    {
        $def = new self();
        
        // Core properties (required)
        $def->wareID = $data['wareID'];
        $def->macroID = $data['macroID'];
        $def->bulletClass = $data['bulletClass'] ?? '';
        $def->label = $data['label'];
        $def->size = $data['size'];
        $def->dataSourceID = $data['dataSourceID'];
        $def->makerRace = $data['makerRace'] ?? 'unknown';
        $def->mk = $data['mk'] ?? 1;
        $def->weaponSystem = $data['weaponSystem'] ?? 'weapon_standard';
        $def->weaponCategory = $data['weaponCategory'] ?? 'standard';
        
        // Weapon heat properties (with defaults)
        $def->heatOverheat = $data['heatOverheat'] ?? 0.0;
        $def->heatCooldelay = $data['heatCooldelay'] ?? 0.0;
        $def->heatCoolrate = $data['heatCoolrate'] ?? 0.0;
        $def->heatReenable = $data['heatReenable'] ?? 0.0;
        
        // Weapon rotation
        $def->rotationSpeed = $data['rotationSpeed'] ?? 0.0;
        $def->rotationAcceleration = $data['rotationAcceleration'] ?? 0.0;
        
        // Weapon hull
        $def->hullMax = $data['hullMax'] ?? 0.0;
        $def->hullHittable = $data['hullHittable'] ?? 0;
        
        // Ammunition
        $def->ammoValue = $data['ammoValue'] ?? 0.0;
        $def->ammoReload = $data['ammoReload'] ?? 0.0;
        
        // Bullet properties
        $def->bulletSpeed = $data['bulletSpeed'] ?? 0.0;
        $def->bulletLifetime = $data['bulletLifetime'] ?? 0.0;
        $def->bulletRange = $data['bulletRange'] ?? ($def->bulletSpeed * $def->bulletLifetime);
        $def->bulletAmount = $data['bulletAmount'] ?? 1;
        $def->bulletBarrelamount = $data['bulletBarrelamount'] ?? 1;
        $def->bulletIcon = $data['bulletIcon'] ?? '';
        $def->bulletTimediff = $data['bulletTimediff'] ?? 0.0;
        $def->bulletAngle = $data['bulletAngle'] ?? 0.0;
        $def->bulletMaxhits = $data['bulletMaxhits'] ?? 1;
        $def->bulletRicochet = $data['bulletRicochet'] ?? 0;
        $def->bulletAttach = $data['bulletAttach'] ?? 0;
        
        // Combat properties
        $def->heatPerShot = $data['heatPerShot'] ?? 0.0;
        $def->reloadRate = $data['reloadRate'] ?? 0.0;
        $def->damageValue = $data['damageValue'] ?? 0.0;
        $def->repairValue = $data['repairValue'] ?? 0.0;
        
        return $def;
    }
    
    // Getters for all properties
    public function getWareID(): string { return $this->wareID; }
    public function getMacroID(): string { return $this->macroID; }
    public function getBulletClass(): string { return $this->bulletClass; }
    public function getLabel(): string { return $this->label; }
    public function getSize(): string { return $this->size; }
    public function getDataSourceID(): string { return $this->dataSourceID; }
    public function getMakerRace(): string { return $this->makerRace; }
    public function getMk(): int { return $this->mk; }
    public function getWeaponSystem(): string { return $this->weaponSystem; }
    public function getWeaponCategory(): string { return $this->weaponCategory; }
    
    // Heat getters
    public function getHeatOverheat(): float { return $this->heatOverheat; }
    public function getHeatCooldelay(): float { return $this->heatCooldelay; }
    public function getHeatCoolrate(): float { return $this->heatCoolrate; }
    public function getHeatReenable(): float { return $this->heatReenable; }
    
    // Rotation getters
    public function getRotationSpeed(): float { return $this->rotationSpeed; }
    public function getRotationAcceleration(): float { return $this->rotationAcceleration; }
    
    // Hull getters
    public function getHullMax(): float { return $this->hullMax; }
    public function getHullHittable(): int { return $this->hullHittable; }
    
    // Ammunition getters
    public function getAmmoValue(): float { return $this->ammoValue; }
    public function getAmmoReload(): float { return $this->ammoReload; }
    
    // Bullet getters
    public function getBulletSpeed(): float { return $this->bulletSpeed; }
    public function getBulletLifetime(): float { return $this->bulletLifetime; }
    public function getBulletRange(): float { return $this->bulletRange; }
    public function getBulletAmount(): int { return $this->bulletAmount; }
    public function getBulletBarrelamount(): int { return $this->bulletBarrelamount; }
    public function getBulletIcon(): string { return $this->bulletIcon; }
    public function getBulletTimediff(): float { return $this->bulletTimediff; }
    public function getBulletAngle(): float { return $this->bulletAngle; }
    public function getBulletMaxhits(): int { return $this->bulletMaxhits; }
    public function getBulletRicochet(): int { return $this->bulletRicochet; }
    public function getBulletAttach(): int { return $this->bulletAttach; }
    
    // Combat getters
    public function getHeatPerShot(): float { return $this->heatPerShot; }
    public function getReloadRate(): float { return $this->reloadRate; }
    public function getDamageValue(): float { return $this->damageValue; }
    public function getRepairValue(): float { return $this->repairValue; }
    
    // Calculated properties
    
    /**
     * Calculate effective DPS (damage per second).
     * Takes into account reload rate and damage.
     */
    public function getDPS(): float
    {
        if ($this->reloadRate <= 0) {
            return 0.0;
        }
        return $this->damageValue * $this->reloadRate * $this->bulletAmount;
    }
    
    /**
     * Calculate shots until overheat.
     */
    public function getShotsUntilOverheat(): float
    {
        if ($this->heatPerShot <= 0) {
            return PHP_FLOAT_MAX;
        }
        return $this->heatOverheat / $this->heatPerShot;
    }
    
    /**
     * Calculate time to fire until overheat (seconds).
     */
    public function getTimeUntilOverheat(): float
    {
        if ($this->reloadRate <= 0 || $this->heatPerShot <= 0) {
            return PHP_FLOAT_MAX;
        }
        return $this->getShotsUntilOverheat() / $this->reloadRate;
    }
    
    /**
     * Check if this is a turret weapon.
     */
    public function isTurret(): bool
    {
        return str_contains($this->macroID, 'turret_');
    }
    
    /**
     * Check if this is a beam weapon.
     */
    public function isBeamWeapon(): bool
    {
        return $this->weaponSystem === 'weapon_beam' || 
               str_contains($this->macroID, '_beam_');
    }
    
    /**
     * Check if this is a mining weapon.
     */
    public function isMiningWeapon(): bool
    {
        return $this->weaponCategory === 'mining' ||
               str_contains($this->macroID, '_mining_');
    }
    
    /**
     * Check if this is a repair weapon.
     */
    public function isRepairWeapon(): bool
    {
        return $this->repairValue > 0;
    }
    
    // Array conversion
    public function toArray(): array
    {
        return [
            'wareID' => $this->wareID,
            'macroID' => $this->macroID,
            'bulletClass' => $this->bulletClass,
            'label' => $this->label,
            'size' => $this->size,
            'dataSourceID' => $this->dataSourceID,
            'makerRace' => $this->makerRace,
            'mk' => $this->mk,
            'weaponSystem' => $this->weaponSystem,
            'weaponCategory' => $this->weaponCategory,
            
            'heatOverheat' => $this->heatOverheat,
            'heatCooldelay' => $this->heatCooldelay,
            'heatCoolrate' => $this->heatCoolrate,
            'heatReenable' => $this->heatReenable,
            
            'rotationSpeed' => $this->rotationSpeed,
            'rotationAcceleration' => $this->rotationAcceleration,
            
            'hullMax' => $this->hullMax,
            'hullHittable' => $this->hullHittable,
            
            'ammoValue' => $this->ammoValue,
            'ammoReload' => $this->ammoReload,
            
            'bulletSpeed' => $this->bulletSpeed,
            'bulletLifetime' => $this->bulletLifetime,
            'bulletRange' => $this->bulletRange,
            'bulletAmount' => $this->bulletAmount,
            'bulletBarrelamount' => $this->bulletBarrelamount,
            'bulletIcon' => $this->bulletIcon,
            'bulletTimediff' => $this->bulletTimediff,
            'bulletAngle' => $this->bulletAngle,
            'bulletMaxhits' => $this->bulletMaxhits,
            'bulletRicochet' => $this->bulletRicochet,
            'bulletAttach' => $this->bulletAttach,
            
            'heatPerShot' => $this->heatPerShot,
            'reloadRate' => $this->reloadRate,
            'damageValue' => $this->damageValue,
            'repairValue' => $this->repairValue,
        ];
    }
}
```

### Implementation Steps

1. **Create the file** `src/X4/Database/Weapons/WeaponDef.php`
2. **Copy the class structure above**
3. **Run PHPStan** to check for type errors: `composer phpstan`
4. **Verify pattern compliance:**
   - Implements `CollectionItemInterface` ✓
   - Uses `CollectionItemTrait` ✓
   - Private constructor ✓
   - Static `fromArray()` factory ✓
   - All properties have getters ✓
   - Immutable (no setters) ✓

### Testing (Manual)

```php
// Test in PHP console
require 'vendor/autoload.php';

$data = [
    'wareID' => 'weapon_gen_s_laser_01_mk1',
    'macroID' => 'weapon_gen_s_laser_01_mk1_macro',
    'bulletClass' => 'bullet_gen_s_laser_01_mk1_macro',
    'label' => 'Bolt Repeater Mk1',
    'size' => 's',
    'dataSourceID' => 'vanilla',
    'makerRace' => 'generic',
    'mk' => 1,
    'weaponSystem' => 'weapon_standard',
    'weaponCategory' => 'standard',
    'heatOverheat' => 10000,
    'damageValue' => 32,
    'bulletSpeed' => 4147,
    'bulletLifetime' => 0.75,
];

$weapon = \X4\Database\Weapons\WeaponDef::fromArray($data);
echo "Weapon: " . $weapon->getLabel() . "\n";
echo "Damage: " . $weapon->getDamageValue() . "\n";
echo "Range: " . $weapon->getBulletRange() . " m\n";
```

---

## 🔨 Work Package 2: WeaponDefs Collection Class

**Status:** Not Started  
**Estimated Time:** 1.5 hours  
**Dependencies:** WP1 (WeaponDef)  
**Assigned To:** Unassigned

### Goal
Create the singleton collection class that manages all weapon definitions.

### Context
- **Pattern:** Collection-Item (see [tech-stack.md](../project-manifest/tech-stack.md#collection-item-pattern))
- **Example:** Study `src/X4/Database/Modules/ModuleDefs.php` and `src/X4/Database/Engines/EngineDefs.php`
- **Data Source:** `data/weapons.json` (will be created in WP4)

### Files to Create

#### `src/X4/Database/Weapons/WeaponDefs.php`

**Class Structure:**
```php
<?php
namespace X4\Database\Weapons;

use X4\X4Application;

/**
 * Singleton collection of all weapon definitions.
 * 
 * Loads from data/weapons.json and provides access by wareID.
 * Use findWeapons() for filtered queries.
 */
class WeaponDefs
{
    private static ?self $instance = null;
    
    /** @var array<string, WeaponDef> */
    private array $weapons = [];
    
    private bool $loaded = false;
    
    // Private constructor (singleton)
    private function __construct()
    {
        $this->load();
    }
    
    /**
     * Get singleton instance.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Reset singleton (for testing).
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
    
    /**
     * Load weapons from JSON file.
     */
    private function load(): void
    {
        if ($this->loaded) {
            return;
        }
        
        $path = X4Application::getInstance()->getDataFolder() . '/weapons.json';
        
        if (!file_exists($path)) {
            // No weapons file yet (first build) - empty collection
            $this->loaded = true;
            return;
        }
        
        $json = file_get_contents($path);
        if ($json === false) {
            throw new \RuntimeException("Failed to read weapons.json");
        }
        
        $data = json_decode($json, true);
        if ($data === null) {
            throw new \RuntimeException("Failed to parse weapons.json");
        }
        
        foreach ($data as $weaponData) {
            $weapon = WeaponDef::fromArray($weaponData);
            $this->weapons[$weapon->getWareID()] = $weapon;
        }
        
        $this->loaded = true;
    }
    
    /**
     * Get weapon by wareID.
     */
    public function getByID(string $wareID): ?WeaponDef
    {
        return $this->weapons[$wareID] ?? null;
    }
    
    /**
     * Get all weapons.
     * 
     * @return WeaponDef[]
     */
    public function getAll(): array
    {
        return array_values($this->weapons);
    }
    
    /**
     * Get count of weapons.
     */
    public function getCount(): int
    {
        return count($this->weapons);
    }
    
    /**
     * Create a weapon finder for filtered queries.
     */
    public function findWeapons(): WeaponFinder
    {
        return new WeaponFinder($this->getAll());
    }
    
    /**
     * Get all unique weapon systems.
     * 
     * @return string[]
     */
    public function getAllWeaponSystems(): array
    {
        $systems = [];
        foreach ($this->weapons as $weapon) {
            $systems[$weapon->getWeaponSystem()] = true;
        }
        return array_keys($systems);
    }
    
    /**
     * Get all unique weapon categories.
     * 
     * @return string[]
     */
    public function getAllWeaponCategories(): array
    {
        $categories = [];
        foreach ($this->weapons as $weapon) {
            $categories[$weapon->getWeaponCategory()] = true;
        }
        return array_keys($categories);
    }
    
    /**
     * Get all weapons by weapon system.
     * 
     * @return WeaponDef[]
     */
    public function getByWeaponSystem(string $system): array
    {
        return array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getWeaponSystem() === $system
        );
    }
    
    /**
     * Get all weapons by category.
     * 
     * @return WeaponDef[]
     */
    public function getByCategory(string $category): array
    {
        return array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getWeaponCategory() === $category
        );
    }
}
```

### Implementation Steps

1. **Create the file** `src/X4/Database/Weapons/WeaponDefs.php`
2. **Copy the class structure above**
3. **Run PHPStan**: `composer phpstan`
4. **Run tests** (will fail initially until weapons.json exists)

### Testing (Manual)

```php
// Test in PHP console (after weapons.json exists)
require 'vendor/autoload.php';

$defs = \X4\Database\Weapons\WeaponDefs::getInstance();
echo "Total weapons: " . $defs->getCount() . "\n";
echo "Weapon systems: " . implode(', ', $defs->getAllWeaponSystems()) . "\n";
echo "Weapon categories: " . implode(', ', $defs->getAllWeaponCategories()) . "\n";

$weapon = $defs->getByID('weapon_gen_s_laser_01_mk1');
if ($weapon) {
    echo "Found: " . $weapon->getLabel() . "\n";
}
```

---

## 🔨 Work Package 3: WeaponFinder Filter Class

**Status:** Not Started  
**Estimated Time:** 2 hours  
**Dependencies:** WP1 (WeaponDef)  
**Assigned To:** Unassigned

### Goal
Create a fluent interface for filtering weapons by various criteria.

### Context
- **Pattern:** Finder (see [tech-stack.md](../project-manifest/tech-stack.md#finder-pattern))
- **Example:** Study `src/X4/Database/Wares/WaresFinder.php`, `src/X4/Database/Ships/ShipsFinder.php`, `src/X4/Database/Engines/EngineFinder.php`
- **Usage:** Chainable methods that filter the weapon list

### Files to Create

#### `src/X4/Database/Weapons/WeaponFinder.php`

**Class Structure:**
```php
<?php
namespace X4\Database\Weapons;

/**
 * Fluent interface for filtering weapons.
 * 
 * Usage:
 *   WeaponDefs::getInstance()->findWeapons()
 *     ->selectSize('s')
 *     ->selectWeaponSystem('weapon_standard')
 *     ->selectMakerRace('argon')
 *     ->sortByDamage()
 *     ->getAll();
 */
class WeaponFinder
{
    /** @var WeaponDef[] */
    private array $weapons;
    
    /**
     * @param WeaponDef[] $weapons
     */
    public function __construct(array $weapons)
    {
        $this->weapons = $weapons;
    }
    
    /**
     * Filter by size (s/m/l/xl).
     */
    public function selectSize(string $size): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getSize() === $size
        );
        return $this;
    }
    
    /**
     * Filter by multiple sizes.
     * 
     * @param string[] $sizes
     */
    public function selectSizes(array $sizes): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => in_array($w->getSize(), $sizes)
        );
        return $this;
    }
    
    /**
     * Filter by weapon system.
     */
    public function selectWeaponSystem(string $system): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getWeaponSystem() === $system
        );
        return $this;
    }
    
    /**
     * Filter by weapon category.
     */
    public function selectCategory(string $category): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getWeaponCategory() === $category
        );
        return $this;
    }
    
    /**
     * Filter by maker race.
     */
    public function selectMakerRace(string $race): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getMakerRace() === $race
        );
        return $this;
    }
    
    /**
     * Filter by data source.
     */
    public function selectDataSource(string $dataSourceID): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getDataSourceID() === $dataSourceID
        );
        return $this;
    }
    
    /**
     * Filter by mark level.
     */
    public function selectMk(int $mk): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getMk() === $mk
        );
        return $this;
    }
    
    /**
     * Filter turrets only.
     */
    public function selectTurrets(): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->isTurret()
        );
        return $this;
    }
    
    /**
     * Filter non-turrets only.
     */
    public function selectNonTurrets(): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => !$w->isTurret()
        );
        return $this;
    }
    
    /**
     * Filter beam weapons only.
     */
    public function selectBeamWeapons(): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->isBeamWeapon()
        );
        return $this;
    }
    
    /**
     * Filter mining weapons only.
     */
    public function selectMiningWeapons(): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->isMiningWeapon()
        );
        return $this;
    }
    
    /**
     * Filter repair weapons only.
     */
    public function selectRepairWeapons(): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->isRepairWeapon()
        );
        return $this;
    }
    
    /**
     * Filter by minimum damage.
     */
    public function selectMinDamage(float $minDamage): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getDamageValue() >= $minDamage
        );
        return $this;
    }
    
    /**
     * Filter by minimum range.
     */
    public function selectMinRange(float $minRange): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getBulletRange() >= $minRange
        );
        return $this;
    }
    
    /**
     * Filter by minimum DPS.
     */
    public function selectMinDPS(float $minDPS): self
    {
        $this->weapons = array_filter(
            $this->weapons,
            fn(WeaponDef $w) => $w->getDPS() >= $minDPS
        );
        return $this;
    }
    
    /**
     * Custom filter with callback.
     */
    public function selectCustom(callable $callback): self
    {
        $this->weapons = array_filter($this->weapons, $callback);
        return $this;
    }
    
    /**
     * Sort by damage (descending).
     */
    public function sortByDamage(bool $ascending = false): self
    {
        usort($this->weapons, function(WeaponDef $a, WeaponDef $b) use ($ascending) {
            $result = $a->getDamageValue() <=> $b->getDamageValue();
            return $ascending ? $result : -$result;
        });
        return $this;
    }
    
    /**
     * Sort by DPS (descending).
     */
    public function sortByDPS(bool $ascending = false): self
    {
        usort($this->weapons, function(WeaponDef $a, WeaponDef $b) use ($ascending) {
            $result = $a->getDPS() <=> $b->getDPS();
            return $ascending ? $result : -$result;
        });
        return $this;
    }
    
    /**
     * Sort by range (descending).
     */
    public function sortByRange(bool $ascending = false): self
    {
        usort($this->weapons, function(WeaponDef $a, WeaponDef $b) use ($ascending) {
            $result = $a->getBulletRange() <=> $b->getBulletRange();
            return $ascending ? $result : -$result;
        });
        return $this;
    }
    
    /**
     * Sort by label (A-Z).
     */
    public function sortByLabel(bool $descending = false): self
    {
        usort($this->weapons, function(WeaponDef $a, WeaponDef $b) use ($descending) {
            $result = strcmp($a->getLabel(), $b->getLabel());
            return $descending ? -$result : $result;
        });
        return $this;
    }
    
    /**
     * Get filtered results.
     * 
     * @return WeaponDef[]
     */
    public function getAll(): array
    {
        return array_values($this->weapons);
    }
    
    /**
     * Get first result or null.
     */
    public function getFirst(): ?WeaponDef
    {
        return $this->weapons[array_key_first($this->weapons)] ?? null;
    }
    
    /**
     * Get count of filtered results.
     */
    public function getCount(): int
    {
        return count($this->weapons);
    }
}
```

### Implementation Steps

1. **Create the file** `src/X4/Database/Weapons/WeaponFinder.php`
2. **Copy the class structure above**
3. **Run PHPStan**: `composer phpstan`
4. **Test fluent interface** works (after weapons.json exists)

### Testing (Manual)

```php
// Test in PHP console (after weapons.json exists)
require 'vendor/autoload.php';

$finder = \X4\Database\Weapons\WeaponDefs::getInstance()->findWeapons();

// Find all small generic standard weapons
$weapons = $finder
    ->selectSize('s')
    ->selectMakerRace('generic')
    ->selectWeaponSystem('weapon_standard')
    ->sortByDamage()
    ->getAll();

echo "Found " . count($weapons) . " weapons\n";
foreach ($weapons as $weapon) {
    echo "- " . $weapon->getLabel() . " (Damage: " . $weapon->getDamageValue() . ")\n";
}
```

---

## 🔨 Work Package 4: WeaponsExtractor Builder Class

**Status:** Not Started  
**Estimated Time:** 3-4 hours  
**Dependencies:** WP1, WP2, WP3, x4-data-extractor data  
**Assigned To:** Unassigned

### Goal
Create the extractor that parses X4 weapon and bullet XML files and generates `data/weapons.json`.

### Context
- **Pattern:** Extraction-Builder (see [tech-stack.md](../project-manifest/tech-stack.md#extraction-builder-pattern))
- **Example:** Study `src/X4/Database/Engines/EnginesExtractor.php` and `src/X4/Database/Modules/ModulesExtractor.php`
- **Data Sources:**
  - Weapon wares: Filter `WareDefs` by weapon groups
  - Weapon macros: `assets/props/WeaponSystems/*/macros/*_macro.xml`
  - Bullet macros: `assets/fx/weaponFx/macros/bullet_*_macro.xml`

### Files to Create

#### `src/X4/Database/Weapons/WeaponsExtractor.php`

**Class Structure:**
```php
<?php
namespace X4\Database\Weapons;

use X4\Database\Wares\WareDefs;
use X4\Database\DataSources\DataSourceDefs;
use X4\XML\ExtendedDOMDocument;
use X4\XML\ExtendedDOMXPath;
use X4\X4Application;

/**
 * Extracts weapon data from X4 game files.
 * 
 * Builds weapons.json from:
 * 1. Weapon wares (from WareDefs)
 * 2. Weapon macro XML files (heat, rotation, hull)
 * 3. Bullet macro XML files (damage, speed, reload)
 */
class WeaponsExtractor
{
    private const WEAPON_TAGS = [
        'weapon',
        'weapons',
        'turret',
        'turrets',
    ];
    
    /** @var array<string, array> */
    private array $weapons = [];
    
    /**
     * Extract all weapons from all data sources.
     */
    public function extract(): void
    {
        echo "Extracting weapons...\n";
        
        $wares = WareDefs::getInstance();
        $dataSources = DataSourceDefs::getInstance();
        
        // Find all weapon wares
        $weaponWares = [];
        foreach ($wares->getAll() as $ware) {
            if ($this->isWeaponWare($ware->getTags())) {
                $weaponWares[] = $ware;
            }
        }
        
        echo "Found " . count($weaponWares) . " weapon wares\n";
        
        // Process each weapon ware
        foreach ($weaponWares as $ware) {
            $dataSource = $dataSources->getByID($ware->getDataSourceID());
            if ($dataSource === null) {
                echo "  Warning: Data source not found for " . $ware->getID() . "\n";
                continue;
            }
            
            $this->extractWeapon($ware, $dataSource->getPath());
        }
        
        echo "Extracted " . count($this->weapons) . " weapons\n";
    }
    
    /**
     * Check if ware is a weapon based on tags.
     * 
     * @param string[] $tags
     */
    private function isWeaponWare(array $tags): bool
    {
        foreach ($tags as $tag) {
            if (in_array(strtolower($tag), self::WEAPON_TAGS)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Extract single weapon data.
     */
    private function extractWeapon(object $ware, string $dataSourcePath): void
    {
        $macroID = $ware->getID() . '_macro';
        
        // Find weapon macro file
        $weaponMacroPath = $this->findWeaponMacroFile($dataSourcePath, $ware->getID());
        if ($weaponMacroPath === null) {
            echo "  Warning: Weapon macro not found for " . $ware->getID() . "\n";
            return;
        }
        
        // Parse weapon macro
        $weaponData = $this->parseWeaponMacro($weaponMacroPath);
        if ($weaponData === null) {
            echo "  Warning: Failed to parse weapon macro for " . $ware->getID() . "\n";
            return;
        }
        
        // Find and parse bullet macro
        $bulletClass = $weaponData['bulletClass'] ?? '';
        if (!empty($bulletClass)) {
            $bulletMacroPath = $this->findBulletMacroFile($dataSourcePath, $bulletClass);
            if ($bulletMacroPath !== null) {
                $bulletData = $this->parseBulletMacro($bulletMacroPath);
                if ($bulletData !== null) {
                    $weaponData = array_merge($weaponData, $bulletData);
                }
            }
        }
        
        // Determine weapon category from file path
        $weaponData['weaponCategory'] = $this->determineWeaponCategory($weaponMacroPath);
        
        // Add ware metadata
        $weaponData['wareID'] = $ware->getID();
        $weaponData['macroID'] = $macroID;
        $weaponData['label'] = $ware->getLabel();
        $weaponData['size'] = $ware->getSize();
        $weaponData['dataSourceID'] = $ware->getDataSourceID();
        $weaponData['makerRace'] = $this->extractMakerRace($ware->getID());
        
        // Calculate bullet range
        if (isset($weaponData['bulletSpeed']) && isset($weaponData['bulletLifetime'])) {
            $weaponData['bulletRange'] = $weaponData['bulletSpeed'] * $weaponData['bulletLifetime'];
        }
        
        $this->weapons[$ware->getID()] = $weaponData;
    }
    
    /**
     * Find weapon macro file.
     */
    private function findWeaponMacroFile(string $dataSourcePath, string $weaponID): ?string
    {
        $basePath = $dataSourcePath . '/assets/props/WeaponSystems';
        
        // Search in all category folders
        $categories = ['capital', 'dumbfire', 'energy', 'guided', 'heavy', 'mines', 
                      'mining', 'missile', 'spacesuit', 'standard', 'torpedo'];
        
        foreach ($categories as $category) {
            $path = $basePath . '/' . $category . '/macros/' . $weaponID . '_macro.xml';
            if (file_exists($path)) {
                return $path;
            }
        }
        
        return null;
    }
    
    /**
     * Find bullet macro file.
     */
    private function findBulletMacroFile(string $dataSourcePath, string $bulletClass): ?string
    {
        // Remove _macro suffix if present
        $bulletClass = str_replace('_macro', '', $bulletClass);
        
        $path = $dataSourcePath . '/assets/fx/weaponFx/macros/' . $bulletClass . '_macro.xml';
        if (file_exists($path)) {
            return $path;
        }
        
        return null;
    }
    
    /**
     * Parse weapon macro XML file.
     */
    private function parseWeaponMacro(string $path): ?array
    {
        $doc = new ExtendedDOMDocument();
        if (!@$doc->load($path)) {
            return null;
        }
        
        $xpath = new ExtendedDOMXPath($doc);
        $data = [];
        
        // Extract mk level
        $identification = $xpath->query('//identification')->item(0);
        if ($identification !== null) {
            $data['mk'] = (int)($identification->getAttribute('mk') ?: 1);
        }
        
        // Extract bullet class
        $bullet = $xpath->query('//bullet[@class]')->item(0);
        if ($bullet !== null) {
            $data['bulletClass'] = $bullet->getAttribute('class');
        }
        
        // Extract heat properties
        $heat = $xpath->query('//heat')->item(0);
        if ($heat !== null) {
            $data['heatOverheat'] = (float)($heat->getAttribute('overheat') ?: 0);
            $data['heatCooldelay'] = (float)($heat->getAttribute('cooldelay') ?: 0);
            $data['heatCoolrate'] = (float)($heat->getAttribute('coolrate') ?: 0);
            $data['heatReenable'] = (float)($heat->getAttribute('reenable') ?: 0);
        }
        
        // Extract rotation speed
        $rotationSpeed = $xpath->query('//rotationspeed')->item(0);
        if ($rotationSpeed !== null) {
            $data['rotationSpeed'] = (float)($rotationSpeed->getAttribute('max') ?: 0);
        }
        
        // Extract rotation acceleration
        $rotationAccel = $xpath->query('//rotationacceleration')->item(0);
        if ($rotationAccel !== null) {
            $data['rotationAcceleration'] = (float)($rotationAccel->getAttribute('max') ?: 0);
        }
        
        // Extract hull
        $hull = $xpath->query('//hull')->item(0);
        if ($hull !== null) {
            $data['hullMax'] = (float)($hull->getAttribute('max') ?: 0);
            $data['hullHittable'] = (int)($hull->getAttribute('hittable') ?: 0);
        }
        
        return $data;
    }
    
    /**
     * Parse bullet macro XML file.
     */
    private function parseBulletMacro(string $path): ?array
    {
        $doc = new ExtendedDOMDocument();
        if (!@$doc->load($path)) {
            return null;
        }
        
        $xpath = new ExtendedDOMXPath($doc);
        $data = [];
        
        // Extract ammunition properties
        $ammunition = $xpath->query('//ammunition')->item(0);
        if ($ammunition !== null) {
            $data['ammoValue'] = (float)($ammunition->getAttribute('value') ?: 0);
            $data['ammoReload'] = (float)($ammunition->getAttribute('reload') ?: 0);
        }
        
        // Extract bullet properties
        $bullet = $xpath->query('//bullet')->item(0);
        if ($bullet !== null) {
            $data['bulletSpeed'] = (float)($bullet->getAttribute('speed') ?: 0);
            $data['bulletLifetime'] = (float)($bullet->getAttribute('lifetime') ?: 0);
            $data['bulletAmount'] = (int)($bullet->getAttribute('amount') ?: 1);
            $data['bulletBarrelamount'] = (int)($bullet->getAttribute('barrelamount') ?: 1);
            $data['bulletIcon'] = $bullet->getAttribute('icon') ?: '';
            $data['bulletTimediff'] = (float)($bullet->getAttribute('timediff') ?: 0);
            $data['bulletAngle'] = (float)($bullet->getAttribute('angle') ?: 0);
            $data['bulletMaxhits'] = (int)($bullet->getAttribute('maxhits') ?: 1);
            $data['bulletRicochet'] = (int)($bullet->getAttribute('ricochet') ?: 0);
            $data['bulletAttach'] = (int)($bullet->getAttribute('attach') ?: 0);
        }
        
        // Extract heat per shot
        $heat = $xpath->query('//heat')->item(0);
        if ($heat !== null) {
            $data['heatPerShot'] = (float)($heat->getAttribute('value') ?: 0);
        }
        
        // Extract reload rate
        $reload = $xpath->query('//reload')->item(0);
        if ($reload !== null) {
            $data['reloadRate'] = (float)($reload->getAttribute('rate') ?: 0);
        }
        
        // Extract damage
        $damage = $xpath->query('//damage')->item(0);
        if ($damage !== null) {
            $data['damageValue'] = (float)($damage->getAttribute('value') ?: 0);
            $data['repairValue'] = (float)($damage->getAttribute('repair') ?: 0);
        }
        
        // Extract weapon system
        $weaponSystem = $xpath->query('//weapon')->item(0);
        if ($weaponSystem !== null) {
            $data['weaponSystem'] = $weaponSystem->getAttribute('system') ?: 'weapon_standard';
        }
        
        return $data;
    }
    
    /**
     * Determine weapon category from file path.
     */
    private function determineWeaponCategory(string $path): string
    {
        $categories = ['capital', 'dumbfire', 'energy', 'guided', 'heavy', 'mines', 
                      'mining', 'missile', 'spacesuit', 'standard', 'torpedo'];
        
        foreach ($categories as $category) {
            if (str_contains($path, '/WeaponSystems/' . $category . '/')) {
                return $category;
            }
        }
        
        return 'standard';
    }
    
    /**
     * Extract maker race from weapon ID.
     */
    private function extractMakerRace(string $weaponID): string
    {
        // Pattern: weapon_<race>_<size>_<type>_<variant>_mk<level>
        // Examples: weapon_arg_s_laser_01_mk1, weapon_gen_m_beam_01_mk2
        
        $parts = explode('_', $weaponID);
        if (count($parts) < 2) {
            return 'unknown';
        }
        
        $race = $parts[1]; // Second part should be race code
        
        // Map race codes to full names
        $raceMap = [
            'arg' => 'argon',
            'par' => 'paranid',
            'tel' => 'teladi',
            'spl' => 'split',
            'ter' => 'terran',
            'xen' => 'xenon',
            'kha' => 'khaak',
            'gen' => 'generic',
            'bor' => 'boron',
            'pio' => 'pioneers',
            'sca' => 'scaleplate',
        ];
        
        return $raceMap[$race] ?? $race;
    }
    
    /**
     * Save extracted weapons to JSON file.
     */
    public function save(): void
    {
        $path = X4Application::getInstance()->getDataFolder() . '/weapons.json';
        
        // Convert to array with sorted keys
        ksort($this->weapons);
        $data = array_values($this->weapons);
        
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new \RuntimeException("Failed to encode weapons.json");
        }
        
        if (file_put_contents($path, $json) === false) {
            throw new \RuntimeException("Failed to write weapons.json");
        }
        
        echo "Saved " . count($this->weapons) . " weapons to weapons.json\n";
    }
}
```

### Implementation Steps

1. **Create the file** `src/X4/Database/Weapons/WeaponsExtractor.php`
2. **Copy the class structure above**
3. **Run PHPStan**: `composer phpstan`
4. **Test extraction:**
   ```php
   require 'vendor/autoload.php';
   $extractor = new \X4\Database\Weapons\WeaponsExtractor();
   $extractor->extract();
   $extractor->save();
   ```
5. **Verify weapons.json** was created in `data/` folder

### Integration into Build Process

Update `composer.json` to add weapon extraction:

```json
{
    "scripts": {
        "extract-weapons": [
            "@php -r \"require 'vendor/autoload.php'; $e = new \\X4\\Database\\Weapons\\WeaponsExtractor(); $e->extract(); $e->save();\""
        ],
        "build": [
            "@extract-wares",
            "@extract-modules",
            "@extract-ships",
            "@extract-engines",
            "@extract-weapons",
            "@build-macro-index"
        ]
    }
}
```

---

## 🔨 Work Package 5: Documentation & Tests

**Status:** Not Started  
**Estimated Time:** 1.5 hours  
**Dependencies:** WP1, WP2, WP3, WP4  
**Assigned To:** Unassigned

### Goal
Update manifest documentation and create test suite for weapon system.

### Context
- **Manifest Updates:** Per [AGENTS.md](../../AGENTS.md#manifest-maintenance-rules)
- **Test Pattern:** Study `tests/X4Tests/Suites/Database/` for examples
- **Documentation:** Keep public-api.md signatures in sync with code

### Files to Update/Create

#### 1. Update `docs/agents/project-manifest/file-tree.md`

Add under `src/X4/Database/`:

```
Database/
    ...
    Weapons/
        WeaponDef.php
        WeaponDefs.php
        WeaponFinder.php
        WeaponsExtractor.php
```

#### 2. Update `docs/agents/project-manifest/public-api.md`

Add new section after `Database\Engines`:

```markdown
### Database\Weapons

#### WeaponDef
- `static fromArray(array $data): self`
- `getWareID(): string`
- `getMacroID(): string`
- `getBulletClass(): string`
- `getLabel(): string`
- `getSize(): string`
- `getDataSourceID(): string`
- `getMakerRace(): string`
- `getMk(): int`
- `getWeaponSystem(): string`
- `getWeaponCategory(): string`
- `getHeatOverheat(): float`
- `getHeatCooldelay(): float`
- `getHeatCoolrate(): float`
- `getHeatReenable(): float`
- `getRotationSpeed(): float`
- `getRotationAcceleration(): float`
- `getHullMax(): float`
- `getHullHittable(): int`
- `getAmmoValue(): float`
- `getAmmoReload(): float`
- `getBulletSpeed(): float`
- `getBulletLifetime(): float`
- `getBulletRange(): float`
- `getBulletAmount(): int`
- `getBulletBarrelamount(): int`
- `getBulletIcon(): string`
- `getBulletTimediff(): float`
- `getBulletAngle(): float`
- `getBulletMaxhits(): int`
- `getBulletRicochet(): int`
- `getBulletAttach(): int`
- `getHeatPerShot(): float`
- `getReloadRate(): float`
- `getDamageValue(): float`
- `getRepairValue(): float`
- `getDPS(): float` - Calculated DPS
- `getShotsUntilOverheat(): float` - Calculated shots before overheat
- `getTimeUntilOverheat(): float` - Calculated time before overheat
- `isTurret(): bool`
- `isBeamWeapon(): bool`
- `isMiningWeapon(): bool`
- `isRepairWeapon(): bool`
- `toArray(): array`

#### WeaponDefs
- `static getInstance(): self`
- `static resetInstance(): void`
- `getByID(string $wareID): ?WeaponDef`
- `getAll(): WeaponDef[]`
- `getCount(): int`
- `findWeapons(): WeaponFinder`
- `getAllWeaponSystems(): string[]`
- `getAllWeaponCategories(): string[]`
- `getByWeaponSystem(string $system): WeaponDef[]`
- `getByCategory(string $category): WeaponDef[]`

#### WeaponFinder
- `__construct(WeaponDef[] $weapons)`
- `selectSize(string $size): self`
- `selectSizes(string[] $sizes): self`
- `selectWeaponSystem(string $system): self`
- `selectCategory(string $category): self`
- `selectMakerRace(string $race): self`
- `selectDataSource(string $dataSourceID): self`
- `selectMk(int $mk): self`
- `selectTurrets(): self`
- `selectNonTurrets(): self`
- `selectBeamWeapons(): self`
- `selectMiningWeapons(): self`
- `selectRepairWeapons(): self`
- `selectMinDamage(float $minDamage): self`
- `selectMinRange(float $minRange): self`
- `selectMinDPS(float $minDPS): self`
- `selectCustom(callable $callback): self`
- `sortByDamage(bool $ascending = false): self`
- `sortByDPS(bool $ascending = false): self`
- `sortByRange(bool $ascending = false): self`
- `sortByLabel(bool $descending = false): self`
- `getAll(): WeaponDef[]`
- `getFirst(): ?WeaponDef`
- `getCount(): int`

#### WeaponsExtractor
- `extract(): void`
- `save(): void`
```

#### 3. Update `docs/agents/project-manifest/data-flows.md`

Add new flow after Engine Extraction Flow:

```markdown
### Weapon Extraction Flow

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Filter Weapon Wares                                      │
│    WareDefs → filter by weapon tags                         │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. Find Weapon Macro Files                                  │
│    assets/props/WeaponSystems/*/macros/*_macro.xml          │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. Parse Weapon Macro XML                                   │
│    Extract: mk, bulletClass, heat, rotation, hull           │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Find Bullet Macro File                                   │
│    assets/fx/weaponFx/macros/bullet_*_macro.xml             │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. Parse Bullet Macro XML                                   │
│    Extract: ammo, bullet props, heat, reload, damage        │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. Merge Data & Calculate Range                             │
│    bulletRange = bulletSpeed × bulletLifetime               │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. Save to weapons.json                                     │
│    JSON array of weapon objects                             │
└─────────────────────────────────────────────────────────────┘
```
```

#### 4. Update `docs/agents/project-manifest/tech-stack.md`

Add to Data Files section:

```markdown
- `data/weapons.json` - Weapon performance data (damage, heat, reload, range, bullet properties)
```

Add to Collection-Item Pattern examples:

```markdown
- **WeaponDef/WeaponDefs** - Weapon performance characteristics
```

#### 5. Create Test Suite

Create `tests/X4Tests/Suites/Database/WeaponsTest.php`:

```php
<?php

namespace X4Tests\Suites\Database;

use PHPUnit\Framework\TestCase;
use X4\Database\Weapons\WeaponDef;
use X4\Database\Weapons\WeaponDefs;
use X4\Database\Weapons\WeaponFinder;

class WeaponsTest extends TestCase
{
    public function test_weaponDef_fromArray(): void
    {
        $data = [
            'wareID' => 'weapon_test',
            'macroID' => 'weapon_test_macro',
            'bulletClass' => 'bullet_test_macro',
            'label' => 'Test Weapon',
            'size' => 's',
            'dataSourceID' => 'vanilla',
            'makerRace' => 'argon',
            'mk' => 1,
            'weaponSystem' => 'weapon_standard',
            'weaponCategory' => 'standard',
            'heatOverheat' => 10000,
            'damageValue' => 50,
            'bulletSpeed' => 1000,
            'bulletLifetime' => 2.0,
            'reloadRate' => 5,
            'bulletAmount' => 1,
        ];
        
        $weapon = WeaponDef::fromArray($data);
        
        $this->assertSame('weapon_test', $weapon->getWareID());
        $this->assertSame('Test Weapon', $weapon->getLabel());
        $this->assertSame(50.0, $weapon->getDamageValue());
        $this->assertSame(2000.0, $weapon->getBulletRange()); // 1000 * 2.0
        $this->assertSame(250.0, $weapon->getDPS()); // 50 * 5 * 1
    }
    
    public function test_weaponDefs_singleton(): void
    {
        $instance1 = WeaponDefs::getInstance();
        $instance2 = WeaponDefs::getInstance();
        
        $this->assertSame($instance1, $instance2);
    }
    
    public function test_weaponFinder_filtering(): void
    {
        $weapons = [
            WeaponDef::fromArray([
                'wareID' => 'weapon_1',
                'macroID' => 'weapon_1_macro',
                'bulletClass' => 'bullet_1',
                'label' => 'Weapon 1',
                'size' => 's',
                'dataSourceID' => 'vanilla',
                'makerRace' => 'argon',
                'mk' => 1,
                'weaponSystem' => 'weapon_standard',
                'weaponCategory' => 'standard',
                'damageValue' => 30,
            ]),
            WeaponDef::fromArray([
                'wareID' => 'weapon_2',
                'macroID' => 'turret_2_macro',
                'bulletClass' => 'bullet_2',
                'label' => 'Weapon 2',
                'size' => 'm',
                'dataSourceID' => 'vanilla',
                'makerRace' => 'paranid',
                'mk' => 2,
                'weaponSystem' => 'weapon_beam',
                'weaponCategory' => 'energy',
                'damageValue' => 50,
            ]),
        ];
        
        $finder = new WeaponFinder($weapons);
        
        // Test size filter
        $result = $finder->selectSize('s')->getAll();
        $this->assertCount(1, $result);
        $this->assertSame('weapon_1', $result[0]->getWareID());
        
        // Test turret filter
        $finder2 = new WeaponFinder($weapons);
        $result = $finder2->selectTurrets()->getAll();
        $this->assertCount(1, $result);
        $this->assertSame('weapon_2', $result[0]->getWareID());
    }
}
```

### Implementation Steps

1. **Update all 5 manifest files** as specified above
2. **Create test file** `tests/X4Tests/Suites/Database/WeaponsTest.php`
3. **Run tests**: `composer test`
4. **Run PHPStan**: `composer phpstan`
5. **Verify manifest consistency** - all references match code

---

## 🔄 Verification Checklist

After completing all work packages:

- [ ] `data/weapons.json` exists with 200+ entries
- [ ] All weapon categories extracted (vanilla + 7 DLCs)
- [ ] `WeaponDef` implements `CollectionItemInterface`
- [ ] `WeaponDefs` singleton works correctly
- [ ] `WeaponFinder` fluent interface chains properly
- [ ] PHPStan passes with no errors
- [ ] Tests pass (100% for weapon classes)
- [ ] All 4 manifest documents updated
- [ ] `composer build` includes weapon extraction
- [ ] Calculated properties work (DPS, range, overheat time)

---

## 🎓 Knowledge Transfer

### For Future Agents

When picking up this work:

1. **Start with AGENTS.md** - Read the full agent operating system guide
2. **Review Project Manifest** - Especially [tech-stack.md](../project-manifest/tech-stack.md) for patterns
3. **Check weapons.json status** - Does it exist? Is it current?
4. **Verify prerequisites** - x4-data-extractor must have extracted all DLCs
5. **Follow WP order** - Don't skip dependencies (WP4 requires WP1-3)
6. **Test incrementally** - Run PHPStan and tests after each WP
7. **Update manifest** - Don't batch updates, do them immediately

### Key Patterns to Understand

1. **Collection-Item Pattern**
   - Study: `ModuleDef`/`ModuleDefs` and `EngineDef`/`EngineDefs` as reference
   - Item = immutable data object
   - Collection = singleton manager
   - Finder = fluent filter interface

2. **Two-File Extraction Pattern**
   - Weapons require TWO XML files (weapon macro + bullet macro)
   - Parse weapon macro for heat, rotation, hull
   - Parse bullet macro for damage, speed, reload
   - Merge data from both sources

3. **Data Flow**
   - Wares extracted first
   - Weapons filter wares by weapon tags
   - Weapon macro provides weapon properties
   - Bullet macro provides bullet/damage properties
   - Build process orchestrates everything

### Common Pitfalls

1. **Missing x4-data-extractor output**
   - Run `composer unpack-all` first
   - Verify both weapon AND bullet macro files exist

2. **Bullet macro not found**
   - Some weapons may not have bullet macros (expected for missile launchers)
   - Handle gracefully with null checks

3. **Type mismatches**
   - XML attributes are strings, cast to float/int appropriately
   - Missing XML elements must have defaults (0 for numeric, empty string for text)

4. **Manifest out of sync**
   - Update immediately after code changes
   - Future agents depend on accuracy

5. **Weapon category detection**
   - Determined from folder path (WeaponSystems/category/)
   - Fallback to 'standard' if unknown

### External Dependencies

- **x4-data-extractor** - Must be installed and data extracted
- **WareDefs** - Must be extracted before weapons
- **DataFolders** - Points to extractor output
- **MacroIndex** - Optional but helpful for macro lookups

---

## 📞 Support & Questions

### Decision Points Requiring User Input

None currently - all design decisions have been made. However, if issues arise:

1. **Macro files not found** - May need path adjustment in `findWeaponMacroFile()` or `findBulletMacroFile()`
2. **Performance concerns** - May need to optimize XML parsing for 200+ weapon files + 200+ bullet files
3. **Missing properties** - Some weapons may lack certain XML elements (expected, use defaults)
4. **Weapon categorization** - Current approach uses folder path, may need refinement

### Useful Debugging Commands

```php
// Check how many weapon wares exist
php -r "require 'vendor/autoload.php'; 
    \$wares = array_filter(
        \X4\Database\Wares\WareDefs::getInstance()->getAll(), 
        fn(\$w) => in_array('weapon', \$w->getTags())
    );
    echo count(\$wares);"

// Check if weapon macro exists
php -r "require 'vendor/autoload.php';
    \$path = 'F:/Webserver/www/htdocs/tools/x4-data-extractor/output/vanilla/assets/props/WeaponSystems/standard/macros/weapon_gen_s_laser_01_mk1_macro.xml';
    echo file_exists(\$path) ? 'EXISTS' : 'NOT FOUND';"

// Check if bullet macro exists
php -r "require 'vendor/autoload.php';
    \$path = 'F:/Webserver/www/htdocs/tools/x4-data-extractor/output/vanilla/assets/fx/weaponFx/macros/bullet_gen_s_laser_01_mk1_macro.xml';
    echo file_exists(\$path) ? 'EXISTS' : 'NOT FOUND';"

// Test weapon DPS calculation
php -r "require 'vendor/autoload.php';
    \$weapon = \X4\Database\Weapons\WeaponDefs::getInstance()->getByID('weapon_gen_s_laser_01_mk1');
    echo 'DPS: ' . \$weapon->getDPS();"
```

---

## 🏁 Final Notes

This implementation follows established x4-core patterns exactly. The key difference from engines is the **two-file extraction** (weapon macro + bullet macro) which requires careful orchestration.

**Estimated Lines of Code:**
- WeaponDef: ~350 lines (more properties than engines)
- WeaponDefs: ~120 lines
- WeaponFinder: ~250 lines (more filter methods)
- WeaponsExtractor: ~300 lines (two-file parsing)
- Tests: ~400 lines
- **Total: ~1,420 lines**

**Key Success Metrics:**
- weapons.json contains 200+ entries
- All DLCs included
- All properties from both macros extracted
- DPS and range calculations work
- Filtering works correctly
- Tests pass
- Manifest updated

**Complexity Notes:**
- Weapon extraction is MORE complex than engines due to two-file structure
- Bullet macro parsing is critical - contains damage, speed, reload data
- Range calculation is derived (speed × lifetime)
- DPS calculation requires reload rate and damage
- Some weapons (missiles) may not have bullet macros (handle gracefully)

**Remember:** This is a well-established pattern with a twist (two-file extraction). Follow the patterns, handle edge cases, update the manifest, and test thoroughly. The weapon system is core to gameplay analysis - accuracy is critical.

---

**End of Implementation Plan**
