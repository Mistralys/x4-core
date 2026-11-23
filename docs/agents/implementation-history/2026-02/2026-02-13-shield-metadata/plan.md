# Shield Metadata System - Implementation Plan

> **Created:** February 9, 2026  
> **Status:** Not Started  
> **Estimated Total Time:** 5-7 hours  
> **Dependencies:** x4-core project, x4-data-extractor with extracted game data

---

## 🎯 Project Overview

### Objective
Add detailed shield performance data to x4-core by creating a new `shields.json` data file that stores shield characteristics (capacity, recharge rate, delay, hull durability, integration) linked to existing `wares.json` entries via wareID.

### Business Value
- Enable detailed shield comparison in UI
- Support advanced filtering by defensive capabilities
- Provide foundation for ship loadout optimization features
- Complete equipment metadata beyond basic ware information

### Architectural Approach
Follow the established Collection-Item pattern used throughout x4-core:
- **ShieldDef** (item) - Individual shield with all properties
- **ShieldDefs** (collection) - Singleton managing all shields  
- **ShieldFinder** (filter) - Query interface for filtering
- **ShieldsExtractor** (builder) - Extracts data from X4 XML files

### Key Design Decisions

| Decision | Rationale |
|----------|-----------|
| **Separate shields.json** | Keep performance data isolated from basic ware info, follows existing pattern (modules.json, ships.json, engines.json) |
| **Link via wareID** | Maintains existing ware system, adds stats on top without duplication |
| **All properties extracted** | Include capacity, recharge rate/delay, hull, integrated flag for maximum UI flexibility |
| **ShieldFinder included** | Consistent with WaresFinder/ShipsFinder/ModulesFinder/EngineFinder patterns |
| **Extract after wares** | Shields depend on WareDefs for filtering by tags |
| **All DLCs supported** | Complete dataset matching other collections (vanilla + 7 expansions) |

### System Context

**Current State:**
- Shields exist in `wares.json` as basic ware entries (ID, label, size, tags)
- Performance data exists in X4 macro XML files but is **NOT extracted**
- Location: `x4-data-extractor/output/{datasource}/assets/props/SurfaceElements/macros/shield_*.xml`

**After Implementation:**
- `data/shields.json` contains performance data for all shields across all DLCs
- `ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1')` returns full stats
- `ShieldDefs::getInstance()->findShields()->selectSize('l')->selectMakerRace('argon')->getAll()` filters shields
- Build process: `composer build` or `composer extract-shields`

### Shield Properties Available in XML

From macro XML files (example: `shield_arg_l_standard_01_mk1_macro.xml`):

```xml
<macro name="shield_arg_l_standard_01_mk1_macro" class="shieldgenerator">
  <component ref="shield_arg_l_standard_01_mk1" />
  <properties>
    <identification name="{20106,3004}" basename="{20106,3001}" 
                    shortname="{20106,3005}" makerrace="argon" 
                    description="{20106,3002}" mk="1" />
    <recharge max="38844" rate="173" delay="0" />
    <hull max="2000" threshold="0.2" />
  </properties>
</macro>
```

**Extractable Fields:**
- **Recharge:** max (capacity), rate (recharge per second), delay (recharge delay) (3 fields)
- **Hull:** max (durability), threshold (damage threshold), integrated (flag) (3 fields, some optional)
- **Metadata:** wareID, macroID, label, size, makerRace, mk, shieldType, dataSourceID

**Shield Type Extraction:**
Shield types are derived from the ware ID pattern:
- `shield_*_standard_*` → "standard"
- `shield_*_racer_*` → "racer"
- `shield_*_corvette_*` → "corvette"
- `shield_*_mothership_*` → "mothership"
- `shield_*_yacht_*` → "yacht"
- `shield_*_experimental_*` → "experimental"
- `shield_*_virtual_*` → "virtual"

### Required Manifest Updates

Per [AGENTS.md](../../AGENTS.md#manifest-maintenance-rules) change-to-document mapping:

| Manifest Document | Required Updates | Sections |
|-------------------|------------------|----------|
| [file-tree.md](../project-manifest/file-tree.md) | Add Shields/ folder under Database/ | `src/X4/Database/` |
| [public-api.md](../project-manifest/public-api.md) | Add Database\Shields namespace | New section with all public methods |
| [data-flows.md](../project-manifest/data-flows.md) | Add Shield Extraction Flow | New diagram showing XML → ShieldDef → JSON |
| [tech-stack.md](../project-manifest/tech-stack.md) | Add shields.json as data file | Data Files section, Collection-Item examples |
| [constraints.md](../project-manifest/constraints.md) | No changes needed | Follows existing patterns |

---

## 📦 Work Package Breakdown

This plan is divided into **5 independent work packages** that can be implemented incrementally. Each package includes complete context for pickup without prior session knowledge.

### Package Dependencies

```
WP1 (ShieldDef) ─┐
                 ├─> WP2 (ShieldDefs) ─┐
                 │                      ├─> WP4 (Integration)
                 └─> WP3 (ShieldFinder)─┘
                                        │
                                        └─> WP5 (Docs & Tests)
```

**Implementation Order:**
1. WP1 → WP2 → WP3 can be done in any order (no dependencies)
2. WP4 requires WP1, WP2, WP3 complete
3. WP5 requires WP4 complete

---

## 🔨 Work Package 1: ShieldDef Item Class

**Status:** Not Started  
**Estimated Time:** 1 hour  
**Dependencies:** None  
**Assigned To:** Unassigned

### Goal
Create the item class representing a single shield with all performance properties.

### Context
- **Pattern:** Collection-Item (see [tech-stack.md](../project-manifest/tech-stack.md#collection-item-pattern))
- **Example:** Study `src/X4/Database/Engines/EngineDef.php` for similar structure
- **Constraints:** [constraints.md](../project-manifest/constraints.md) - Immutable items, type declarations required

### Files to Create

#### `src/X4/Database/Shields/ShieldDef.php`

**Class Structure:**
```php
<?php
namespace Mistralys\X4\Database\Shields;

use X4\Database\CollectionItemInterface;
use X4\Database\CollectionItemTrait;

/**
 * Represents a single shield with performance characteristics.
 * 
 * Links to WareDef via wareID for basic ware info (label, tags, price).
 * Stores shield-specific performance data extracted from X4 macro XML.
 */
class ShieldDef implements CollectionItemInterface
{
    use CollectionItemTrait;
    
    // Property key constants for JSON serialization
    public const KEY_WARE_ID = 'wareID';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_LABEL = 'label';
    public const KEY_SIZE = 'size';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';
    public const KEY_MAKER_RACE = 'makerRace';
    public const KEY_MK = 'mk';
    public const KEY_VARIANT_ID = 'variantID';
    public const KEY_SHIELD_TYPE = 'shieldType';
    
    public const KEY_RECHARGE_MAX = 'rechargeMax';
    public const KEY_RECHARGE_RATE = 'rechargeRate';
    public const KEY_RECHARGE_DELAY = 'rechargeDelay';
    
    public const KEY_HULL_MAX = 'hullMax';
    public const KEY_HULL_THRESHOLD = 'hullThreshold';
    public const KEY_HULL_INTEGRATED = 'hullIntegrated';
    
    // Core identification
    private string $wareID;      // Primary key, links to wares.json
    private string $macroID;     // Shield macro reference
    private string $label;       // Display name (from ware)
    private string $size;        // s/m/l/xl
    private string $dataSourceID; // vanilla/ego_dlc_split/etc
    private string $makerRace;   // argon/paranid/teladi/etc
    private int $mk;             // Mark level (1/2/3)
    private string $variantID;   // Variant identifier (e.g., "01::mk1")
    private string $shieldType;  // standard/racer/corvette/mothership/yacht/experimental/virtual
    
    // Recharge properties (3 fields)
    private float $rechargeMax;     // Maximum shield capacity
    private float $rechargeRate;    // Recharge rate per second
    private float $rechargeDelay;   // Delay before recharge starts (seconds)
    
    // Hull durability (3 fields, some optional)
    private float $hullMax;         // Maximum hull points (0 if not specified)
    private float $hullThreshold;   // Damage threshold (0 if not specified)
    private bool $hullIntegrated;   // Whether shield is integrated
    
    // Constructor (private - use fromArray)
    private function __construct() {}
    
    // Static factory (required by pattern)
    public static function fromArray(array $data): self
    {
        $def = new self();
        
        // Core properties (required)
        $def->wareID = $data[self::KEY_WARE_ID];
        $def->macroID = $data[self::KEY_MACRO_ID];
        $def->label = $data[self::KEY_LABEL];
        $def->size = $data[self::KEY_SIZE];
        $def->dataSourceID = $data[self::KEY_DATA_SOURCE_ID];
        $def->makerRace = $data[self::KEY_MAKER_RACE] ?? 'unknown';
        $def->mk = $data[self::KEY_MK] ?? 1;
        $def->variantID = $data[self::KEY_VARIANT_ID] ?? '';
        $def->shieldType = $data[self::KEY_SHIELD_TYPE] ?? 'standard';
        
        // Recharge properties (with defaults for missing data)
        $def->rechargeMax = (float)($data[self::KEY_RECHARGE_MAX] ?? 0.0);
        $def->rechargeRate = (float)($data[self::KEY_RECHARGE_RATE] ?? 0.0);
        $def->rechargeDelay = (float)($data[self::KEY_RECHARGE_DELAY] ?? 0.0);
        
        // Hull properties (with defaults)
        $def->hullMax = (float)($data[self::KEY_HULL_MAX] ?? 0.0);
        $def->hullThreshold = (float)($data[self::KEY_HULL_THRESHOLD] ?? 0.0);
        $def->hullIntegrated = (bool)($data[self::KEY_HULL_INTEGRATED] ?? false);
        
        return $def;
    }
    
    // Serialization (required by pattern)
    public function toArray(): array
    {
        return [
            self::KEY_WARE_ID => $this->wareID,
            self::KEY_MACRO_ID => $this->macroID,
            self::KEY_LABEL => $this->label,
            self::KEY_SIZE => $this->size,
            self::KEY_DATA_SOURCE_ID => $this->dataSourceID,
            self::KEY_MAKER_RACE => $this->makerRace,
            self::KEY_MK => $this->mk,
            self::KEY_VARIANT_ID => $this->variantID,
            self::KEY_SHIELD_TYPE => $this->shieldType,
            
            self::KEY_RECHARGE_MAX => $this->rechargeMax,
            self::KEY_RECHARGE_RATE => $this->rechargeRate,
            self::KEY_RECHARGE_DELAY => $this->rechargeDelay,
            
            self::KEY_HULL_MAX => $this->hullMax,
            self::KEY_HULL_THRESHOLD => $this->hullThreshold,
            self::KEY_HULL_INTEGRATED => $this->hullIntegrated,
        ];
    }
    
    // Getters for CollectionItemInterface
    public function getID(): string { return $this->wareID; }
    public function getLabel(): string { return $this->label; }
    
    // Core property getters
    public function getWareID(): string { return $this->wareID; }
    public function getMacroID(): string { return $this->macroID; }
    public function getSize(): string { return $this->size; }
    public function getDataSourceID(): string { return $this->dataSourceID; }
    public function getMakerRace(): string { return $this->makerRace; }
    public function getMk(): int { return $this->mk; }
    public function getVariantID(): string { return $this->variantID; }
    public function getShieldType(): string { return $this->shieldType; }
    
    // Recharge getters
    public function getRechargeMax(): float { return $this->rechargeMax; }
    public function getRechargeRate(): float { return $this->rechargeRate; }
    public function getRechargeDelay(): float { return $this->rechargeDelay; }
    
    /**
     * Get shield capacity (alias for rechargeMax for clarity).
     */
    public function getCapacity(): float { return $this->rechargeMax; }
    
    /**
     * Calculate time to fully recharge from 0 (in seconds).
     * Formula: (capacity / rate) + delay
     */
    public function getFullRechargeTime(): float 
    { 
        if ($this->rechargeRate <= 0.0) {
            return 0.0;
        }
        return ($this->rechargeMax / $this->rechargeRate) + $this->rechargeDelay;
    }
    
    // Hull getters
    public function getHullMax(): float { return $this->hullMax; }
    public function getHullThreshold(): float { return $this->hullThreshold; }
    public function isHullIntegrated(): bool { return $this->hullIntegrated; }
    public function hasHull(): bool { return $this->hullMax > 0.0; }
    
    // Type checks
    public function isStandard(): bool { return $this->shieldType === 'standard'; }
    public function isRacer(): bool { return $this->shieldType === 'racer'; }
    public function isCorvette(): bool { return $this->shieldType === 'corvette'; }
    public function isMothership(): bool { return $this->shieldType === 'mothership'; }
    public function isYacht(): bool { return $this->shieldType === 'yacht'; }
    public function isExperimental(): bool { return $this->shieldType === 'experimental'; }
    public function isVirtual(): bool { return $this->shieldType === 'virtual'; }
}
```

#### `src/X4/Database/Shields/ShieldException.php`

**Class Structure:**
```php
<?php
namespace Mistralys\X4\Database\Shields;

use X4\Database\X4_Database_Exception;

class ShieldException extends X4_Database_Exception
{
    public const ERROR_SHIELD_NOT_FOUND = 143001;
    public const ERROR_INVALID_SHIELD_SIZE = 143002;
    public const ERROR_INVALID_SHIELD_DATA = 143003;
    public const ERROR_INVALID_SHIELD_TYPE = 143004;
}
```

### Implementation Steps

1. Create directory: `src/X4/Database/Shields/`
2. Create `ShieldDef.php` with full class implementation above
3. Create `ShieldException.php` with error constants
4. Verify PHP syntax: `php -l src/X4/Database/Shields/ShieldDef.php`
5. Run PHPStan: `composer phpstan` (expect no errors)

### Verification Checklist

- [ ] File created at correct location following file-tree.md structure
- [ ] Implements `CollectionItemInterface` with required methods
- [ ] Uses `CollectionItemTrait` for common functionality
- [ ] All properties have type declarations
- [ ] `fromArray()` handles missing fields with defaults
- [ ] `toArray()` returns all properties for JSON serialization
- [ ] Getter methods follow naming convention (get* prefix)
- [ ] Helper methods: `getCapacity()`, `getFullRechargeTime()`, type checks
- [ ] No setter methods (immutable after construction)
- [ ] PHPStan passes with no errors
- [ ] Exception class extends correct parent

### Reference Files
- Pattern: `src/X4/Database/Engines/EngineDef.php`
- Interface: `src/X4/Database/CollectionItemInterface.php`
- Trait: `src/X4/Database/CollectionItemTrait.php`
- Parent exception: `src/X4/Database/X4_Database_Exception.php`

---

## 🔨 Work Package 2: ShieldDefs Collection Class

**Status:** Not Started  
**Estimated Time:** 1 hour  
**Dependencies:** WP1 (ShieldDef must exist)  
**Assigned To:** Unassigned

### Goal
Create the singleton collection class managing all shield instances, loading from `shields.json`.

### Context
- **Pattern:** Collection-Item (see [tech-stack.md](../project-manifest/tech-stack.md#collection-item-pattern))
- **Example:** Study `src/X4/Database/Engines/EngineDefs.php` for structure
- **Data Source:** Will load from `data/shields.json` (created by extractor in WP4)

### Files to Create

#### `src/X4/Database/Shields/ShieldDefs.php`

**Class Structure:**
```php
<?php
namespace Mistralys\X4\Database\Shields;

use X4\Database\BaseStringPrimaryCollection;
use X4\Database\ItemCollectionInterface;

/**
 * Collection of all shield definitions.
 * 
 * Singleton accessor for shield performance data loaded from shields.json.
 * Shields link to WareDefs via wareID.
 * 
 * Usage:
 *   $shield = ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1');
 *   $shields = ShieldDefs::getInstance()->getAll();
 *   $finder = ShieldDefs::getInstance()->findShields();
 */
class ShieldDefs extends BaseStringPrimaryCollection implements ItemCollectionInterface
{
    public const DATA_FILE = 'shields.json';
    public const ERROR_SHIELD_NOT_FOUND = ShieldException::ERROR_SHIELD_NOT_FOUND;
    
    private static ?self $instance = null;
    
    /**
     * Get singleton instance.
     * Loads shields.json on first access.
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Private constructor enforces singleton pattern.
     */
    private function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get shield by ware ID.
     * 
     * @param string $wareID Shield ware ID (e.g., 'shield_arg_l_standard_01_mk1')
     * @return ShieldDef
     * @throws ShieldException If shield not found
     */
    public function getByID(string $wareID): ShieldDef
    {
        $shield = $this->getItemByID($wareID);
        if ($shield === null) {
            throw new ShieldException(
                sprintf('Shield not found: %s', $wareID),
                self::ERROR_SHIELD_NOT_FOUND
            );
        }
        return $shield;
    }
    
    /**
     * Check if shield exists.
     */
    public function idExists(string $wareID): bool
    {
        return $this->getItemByID($wareID) !== null;
    }
    
    /**
     * Get all shields.
     * 
     * @return ShieldDef[]
     */
    public function getAll(): array
    {
        return $this->getItems();
    }
    
    /**
     * Get shields by type.
     * 
     * @param string $type Shield type (standard/racer/corvette/mothership/yacht/experimental/virtual)
     * @return ShieldDef[]
     */
    public function getByType(string $type): array
    {
        return array_filter(
            $this->getAll(),
            fn(ShieldDef $shield) => $shield->getShieldType() === $type
        );
    }
    
    /**
     * Get default shield ID (first in collection).
     */
    public function getDefaultID(): string
    {
        $shields = $this->getAll();
        if (empty($shields)) {
            throw new ShieldException(
                'No shields available',
                self::ERROR_SHIELD_NOT_FOUND
            );
        }
        return $shields[0]->getID();
    }
    
    /**
     * Get default shield instance.
     */
    public function getDefault(): ShieldDef
    {
        return $this->getByID($this->getDefaultID());
    }
    
    /**
     * Create finder for filtering shields.
     * 
     * @return ShieldFinder
     */
    public function findShields(): ShieldFinder
    {
        return new ShieldFinder($this->getAll());
    }
    
    /**
     * Load shields from JSON file.
     * Called automatically by parent constructor.
     */
    protected function registerItems(): void
    {
        $data = $this->getDataFile()->getData();
        
        foreach ($data as $shieldData) {
            $this->registerItem(ShieldDef::fromArray($shieldData));
        }
    }
    
    /**
     * Get data file name.
     */
    public function getDataFileName(): string
    {
        return self::DATA_FILE;
    }
}
```

### Implementation Steps

1. Create `src/X4/Database/Shields/ShieldDefs.php` with implementation above
2. Verify PHP syntax: `php -l src/X4/Database/Shields/ShieldDefs.php`
3. Run PHPStan: `composer phpstan`
4. **Note:** Cannot fully test until `shields.json` exists (created in WP4)

### Verification Checklist

- [ ] Extends `BaseStringPrimaryCollection` correctly
- [ ] Implements `ItemCollectionInterface`
- [ ] Singleton pattern with private constructor
- [ ] `getInstance()` returns same instance on multiple calls
- [ ] `getByID()` throws `ShieldException` for invalid IDs
- [ ] `getAll()` returns `ShieldDef[]` array
- [ ] `getByType()` filters shields by type
- [ ] `findShields()` returns `ShieldFinder` instance
- [ ] `registerItems()` calls `ShieldDef::fromArray()`
- [ ] PHPStan passes with no errors

### Reference Files
- Pattern: `src/X4/Database/Engines/EngineDefs.php`
- Base class: `src/X4/Database/BaseStringPrimaryCollection.php`
- Interface: `src/X4/Database/ItemCollectionInterface.php`

### Testing Note
Full testing requires `data/shields.json` to exist. After WP4 completion:
```php
// Test basic access
$shields = ShieldDefs::getInstance()->getAll();
echo count($shields);  // Should be ~107

// Test lookup
$shield = ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1');
echo $shield->getCapacity();
echo $shield->getFullRechargeTime();

// Test type filtering
$racers = ShieldDefs::getInstance()->getByType('racer');
```

---

## 🔨 Work Package 3: ShieldFinder Filter Class

**Status:** Not Started  
**Estimated Time:** 1.5 hours  
**Dependencies:** WP1 (ShieldDef must exist)  
**Assigned To:** Unassigned

### Goal
Create the finder class for filtering shields by various criteria (size, race, type, performance).

### Context
- **Pattern:** Finder Pattern (see [tech-stack.md](../project-manifest/tech-stack.md#finder-pattern))
- **Example:** Study `src/X4/Database/Engines/EngineFinder.php` for filter methods
- **Usage:** Fluent interface for chaining filters

### Files to Create

#### `src/X4/Database/Shields/ShieldFinder.php`

**Class Structure:**
```php
<?php
namespace Mistralys\X4\Database\Shields;

use X4\Database\BaseFinder;

/**
 * Finder for filtering shield collections.
 * 
 * Provides fluent interface for filtering shields by:
 * - Physical properties (size, maker race, type)
 * - Data source (vanilla, DLCs)
 * - Performance characteristics (capacity, recharge rate, delay)
 * - Quality (mark level)
 * - Hull properties (durability, integration)
 * 
 * Usage:
 *   $shields = ShieldDefs::getInstance()->findShields()
 *       ->selectSize('l')
 *       ->selectMakerRace('argon')
 *       ->selectType('standard')
 *       ->selectMinCapacity(30000)
 *       ->getAll();
 */
class ShieldFinder extends BaseFinder
{
    /**
     * @var ShieldDef[]
     */
    private array $shields;
    
    /**
     * @param ShieldDef[] $shields Initial shield collection
     */
    public function __construct(array $shields)
    {
        $this->shields = $shields;
    }
    
    /**
     * Filter by shield size.
     * 
     * @param string $size Shield size: 's', 'm', 'l', 'xl'
     * @return self
     */
    public function selectSize(string $size): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getSize() === $size
        );
        return $this;
    }
    
    /**
     * Filter by multiple sizes.
     * 
     * @param string[] $sizes Array of sizes
     * @return self
     */
    public function selectSizes(array $sizes): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => in_array($shield->getSize(), $sizes, true)
        );
        return $this;
    }
    
    /**
     * Filter by maker race.
     * 
     * @param string $race Race: 'argon', 'paranid', 'teladi', 'split', 'boron', 'terran', etc.
     * @return self
     */
    public function selectMakerRace(string $race): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getMakerRace() === $race
        );
        return $this;
    }
    
    /**
     * Filter by shield type.
     * 
     * @param string $type Type: 'standard', 'racer', 'corvette', 'mothership', 'yacht', 'experimental', 'virtual'
     * @return self
     */
    public function selectType(string $type): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getShieldType() === $type
        );
        return $this;
    }
    
    /**
     * Filter by data source.
     * 
     * @param string $dataSourceID Data source: 'vanilla', 'ego_dlc_split', etc.
     * @return self
     */
    public function selectDataSource(string $dataSourceID): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getDataSourceID() === $dataSourceID
        );
        return $this;
    }
    
    /**
     * Filter by mark level.
     * 
     * @param int $mk Mark level (1, 2, 3)
     * @return self
     */
    public function selectMk(int $mk): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getMk() === $mk
        );
        return $this;
    }
    
    /**
     * Filter by minimum mark level.
     * 
     * @param int $minMk Minimum mark level
     * @return self
     */
    public function selectMinMk(int $minMk): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getMk() >= $minMk
        );
        return $this;
    }
    
    // ===== Performance Filters =====
    
    /**
     * Filter by minimum shield capacity.
     * 
     * @param float $minCapacity Minimum capacity (rechargeMax)
     * @return self
     */
    public function selectMinCapacity(float $minCapacity): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getCapacity() >= $minCapacity
        );
        return $this;
    }
    
    /**
     * Filter by maximum shield capacity.
     * 
     * @param float $maxCapacity Maximum capacity
     * @return self
     */
    public function selectMaxCapacity(float $maxCapacity): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getCapacity() <= $maxCapacity
        );
        return $this;
    }
    
    /**
     * Filter by capacity range.
     * 
     * @param float $minCapacity Minimum capacity
     * @param float $maxCapacity Maximum capacity
     * @return self
     */
    public function selectCapacityRange(float $minCapacity, float $maxCapacity): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getCapacity() >= $minCapacity 
                                  && $shield->getCapacity() <= $maxCapacity
        );
        return $this;
    }
    
    /**
     * Filter by minimum recharge rate.
     * 
     * @param float $minRate Minimum recharge rate per second
     * @return self
     */
    public function selectMinRechargeRate(float $minRate): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getRechargeRate() >= $minRate
        );
        return $this;
    }
    
    /**
     * Filter by maximum recharge delay.
     * 
     * @param float $maxDelay Maximum recharge delay in seconds
     * @return self
     */
    public function selectMaxRechargeDelay(float $maxDelay): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getRechargeDelay() <= $maxDelay
        );
        return $this;
    }
    
    /**
     * Filter by maximum full recharge time.
     * 
     * @param float $maxTime Maximum time to fully recharge (seconds)
     * @return self
     */
    public function selectMaxRechargeTime(float $maxTime): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getFullRechargeTime() <= $maxTime
        );
        return $this;
    }
    
    // ===== Hull Filters =====
    
    /**
     * Filter shields that have hull durability.
     * 
     * @return self
     */
    public function selectWithHull(): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->hasHull()
        );
        return $this;
    }
    
    /**
     * Filter shields without hull durability.
     * 
     * @return self
     */
    public function selectWithoutHull(): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => !$shield->hasHull()
        );
        return $this;
    }
    
    /**
     * Filter by minimum hull durability.
     * 
     * @param float $minHull Minimum hull max value
     * @return self
     */
    public function selectMinHull(float $minHull): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->getHullMax() >= $minHull
        );
        return $this;
    }
    
    /**
     * Filter integrated shields only.
     * 
     * @return self
     */
    public function selectIntegrated(): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => $shield->isHullIntegrated()
        );
        return $this;
    }
    
    /**
     * Filter non-integrated shields only.
     * 
     * @return self
     */
    public function selectNonIntegrated(): self
    {
        $this->shields = array_filter(
            $this->shields,
            fn(ShieldDef $shield) => !$shield->isHullIntegrated()
        );
        return $this;
    }
    
    // ===== Type-Specific Filters =====
    
    /**
     * Filter standard shields only.
     * 
     * @return self
     */
    public function selectStandard(): self
    {
        return $this->selectType('standard');
    }
    
    /**
     * Filter racer shields only.
     * 
     * @return self
     */
    public function selectRacer(): self
    {
        return $this->selectType('racer');
    }
    
    /**
     * Filter corvette shields only.
     * 
     * @return self
     */
    public function selectCorvette(): self
    {
        return $this->selectType('corvette');
    }
    
    /**
     * Filter mothership shields only.
     * 
     * @return self
     */
    public function selectMothership(): self
    {
        return $this->selectType('mothership');
    }
    
    // ===== Sorting =====
    
    /**
     * Sort by capacity (descending).
     * 
     * @return self
     */
    public function sortByCapacity(): self
    {
        usort($this->shields, function(ShieldDef $a, ShieldDef $b) {
            return $b->getCapacity() <=> $a->getCapacity();
        });
        return $this;
    }
    
    /**
     * Sort by recharge rate (descending).
     * 
     * @return self
     */
    public function sortByRechargeRate(): self
    {
        usort($this->shields, function(ShieldDef $a, ShieldDef $b) {
            return $b->getRechargeRate() <=> $a->getRechargeRate();
        });
        return $this;
    }
    
    /**
     * Sort by full recharge time (ascending - faster first).
     * 
     * @return self
     */
    public function sortByRechargeTime(): self
    {
        usort($this->shields, function(ShieldDef $a, ShieldDef $b) {
            return $a->getFullRechargeTime() <=> $b->getFullRechargeTime();
        });
        return $this;
    }
    
    /**
     * Sort by label alphabetically.
     * 
     * @return self
     */
    public function sortByLabel(): self
    {
        usort($this->shields, function(ShieldDef $a, ShieldDef $b) {
            return strcmp($a->getLabel(), $b->getLabel());
        });
        return $this;
    }
    
    // ===== Result Methods =====
    
    /**
     * Get all filtered shields.
     * 
     * @return ShieldDef[]
     */
    public function getAll(): array
    {
        return array_values($this->shields);
    }
    
    /**
     * Get first shield from filtered results.
     * 
     * @return ShieldDef|null
     */
    public function getFirst(): ?ShieldDef
    {
        $shields = array_values($this->shields);
        return $shields[0] ?? null;
    }
    
    /**
     * Get count of filtered shields.
     * 
     * @return int
     */
    public function count(): int
    {
        return count($this->shields);
    }
    
    /**
     * Check if any shields match the filters.
     * 
     * @return bool
     */
    public function hasResults(): bool
    {
        return !empty($this->shields);
    }
}
```

### Implementation Steps

1. Create `src/X4/Database/Shields/ShieldFinder.php` with implementation above
2. Verify PHP syntax: `php -l src/X4/Database/Shields/ShieldFinder.php`
3. Run PHPStan: `composer phpstan`

### Verification Checklist

- [ ] Extends `BaseFinder` correctly
- [ ] All filter methods return `self` for fluent interface
- [ ] Size filters: `selectSize()`, `selectSizes()`
- [ ] Race/source filters: `selectMakerRace()`, `selectDataSource()`
- [ ] Type filters: `selectType()`, type-specific helpers
- [ ] Performance filters: capacity, recharge rate, delay
- [ ] Hull filters: `selectWithHull()`, `selectMinHull()`, `selectIntegrated()`
- [ ] Sorting methods: by capacity, rate, time, label
- [ ] Result methods: `getAll()`, `getFirst()`, `count()`, `hasResults()`
- [ ] PHPStan passes with no errors

### Reference Files
- Pattern: `src/X4/Database/Engines/EngineFinder.php`
- Base class: `src/X4/Database/BaseFinder.php`

### Testing Note
Example usage after shields.json exists:
```php
// Find best large Argon shields
$shields = ShieldDefs::getInstance()->findShields()
    ->selectSize('l')
    ->selectMakerRace('argon')
    ->selectStandard()
    ->selectMinCapacity(30000)
    ->sortByCapacity()
    ->getAll();

// Find fast-recharge racer shields
$racers = ShieldDefs::getInstance()->findShields()
    ->selectRacer()
    ->selectMaxRechargeDelay(5.0)
    ->sortByRechargeTime()
    ->getAll();
```

---

## 🔨 Work Package 4: Extraction & Integration

**Status:** Not Started  
**Estimated Time:** 2-3 hours  
**Dependencies:** WP1, WP2, WP3 must be complete  
**Assigned To:** Unassigned

### Goal
Create the extractor classes and integrate shield extraction into the build system.

### Context
- **Pattern:** Extraction-Builder (see [tech-stack.md](../project-manifest/tech-stack.md#extraction-builder-pattern))
- **Example:** Study `src/X4/Database/Engines/EnginesExtractor.php` for structure
- **Build System:** Add to `DatabaseBuilder` and `composer.json`

### Files to Create/Modify

#### 1. `src/X4/Database/Shields/ShieldsExtractor.php`

**Class Structure:**
```php
<?php
namespace Mistralys\X4\Database\Shields;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;

/**
 * Extracts shield performance data from X4 macro XML files.
 * 
 * Process:
 * 1. Filter WareDefs to get only shields (tag = "shield")
 * 2. For each shield wareID:
 *    - Access macro XML via WareDef
 *    - Parse performance properties from XML
 *    - Build ShieldDef data array
 * 3. Write to data/shields.json
 */
class ShieldsExtractor
{
    /**
     * @var array<int,array<string,mixed>>
     */
    private array $shields = array();

    public function __construct(DataFolders $dataFolders)
    {
        // DataFolders passed for consistency with other extractors
    }

    /**
     * Main extraction method.
     * Generates data/shields.json.
     */
    public function extract(): void
    {
        $this->extractShields();
    }

    private function extractShields(): void
    {
        Console::header('Extracting shields...');

        // Filter wares to get shields only (tag contains "shield")
        foreach (WareDefs::getInstance()->getAll() as $ware) {
            if (in_array('shield', $ware->getTags(), true)) {
                $this->processWare($ware);
            }
        }

        Console::line1('Found [%d] shields.', count($this->shields));
        Console::line1('Saving to disk...');
        Console::nl();

        ksort($this->shields);

        ShieldDefs::getInstance()
            ->getDataFile()
            ->putData($this->shields);
    }

    private function processWare(WareDef $ware): void
    {
        $this->shields[] = (new ShieldMacroExtractor($ware))->extract();
    }
}
```

#### 2. `src/X4/Database/Shields/ShieldMacroExtractor.php`

**Class Structure:**
```php
<?php
namespace Mistralys\X4\Database\Shields;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Extracts shield performance data from a single shield macro XML file.
 */
class ShieldMacroExtractor
{
    private WareDef $ware;
    private DOMExtended $dom;

    public function __construct(WareDef $ware)
    {
        $this->ware = $ware;
        $this->dom = $ware->getMacro()->getDOM();
    }

    /**
     * Extracts the shield data from the macro XML.
     *
     * @return array<string,mixed>
     */
    public function extract(): array
    {
        return array(
            ShieldDef::KEY_WARE_ID => $this->ware->getID(),
            ShieldDef::KEY_MACRO_ID => $this->dom->byTagName('macro')->requireFirst()->getAttribute('name'),
            ShieldDef::KEY_LABEL => $this->ware->getLabel(),
            ShieldDef::KEY_SIZE => $this->ware->getSize(),
            ShieldDef::KEY_DATA_SOURCE_ID => $this->ware->getDataSourceID(),
            ShieldDef::KEY_MAKER_RACE => $this->resolveMakerRace(),
            ShieldDef::KEY_MK => $this->resolveMk(),
            ShieldDef::KEY_VARIANT_ID => (string)$this->ware->getVariantID(),
            ShieldDef::KEY_SHIELD_TYPE => $this->resolveShieldType(),
            
            // Recharge properties
            ShieldDef::KEY_RECHARGE_MAX => $this->resolveFloat('recharge', 'max'),
            ShieldDef::KEY_RECHARGE_RATE => $this->resolveFloat('recharge', 'rate'),
            ShieldDef::KEY_RECHARGE_DELAY => $this->resolveFloat('recharge', 'delay'),
            
            // Hull properties
            ShieldDef::KEY_HULL_MAX => $this->resolveFloat('hull', 'max'),
            ShieldDef::KEY_HULL_THRESHOLD => $this->resolveFloat('hull', 'threshold'),
            ShieldDef::KEY_HULL_INTEGRATED => $this->resolveIntegrated(),
        );
    }

    private function resolveMakerRace(): string
    {
        $el = $this->tagIdentification();
        if ($el) {
            $race = $el->getAttribute('makerrace');
            if (!empty($race)) {
                return $race;
            }
        }
        return 'unknown';
    }

    private function resolveMk(): int
    {
        $el = $this->tagIdentification();
        if ($el) {
            $mk = $el->getAttribute('mk');
            if (!empty($mk)) {
                return (int)$mk;
            }
        }
        return 1;
    }

    /**
     * Resolve shield type from ware ID pattern.
     * Examples:
     *   shield_arg_l_standard_01_mk1 -> standard
     *   shield_par_s_racer_01_mk1 -> racer
     *   shield_gen_xl_corvette_01_mk1 -> corvette
     */
    private function resolveShieldType(): string
    {
        $wareID = $this->ware->getID();
        
        $types = [
            'standard',
            'racer',
            'corvette',
            'mothership',
            'yacht',
            'experimental',
            'virtual'
        ];
        
        foreach ($types as $type) {
            if (strpos($wareID, '_' . $type . '_') !== false) {
                return $type;
            }
        }
        
        return 'standard'; // Default fallback
    }

    /**
     * Resolve integrated flag from hull element.
     * integrated="1" means shield is integrated into hull.
     */
    private function resolveIntegrated(): bool
    {
        $el = $this->dom->byTagName('hull')->getFirst();
        if ($el !== null) {
            $integrated = $el->getAttribute('integrated');
            return $integrated === '1';
        }
        return false;
    }

    /**
     * Resolve a float attribute from an XML element.
     *
     * @param string $tagName Tag name to search for
     * @param string $attribute Attribute name to extract
     * @param float $default Default value if element or attribute not found
     * @return float
     */
    private function resolveFloat(string $tagName, string $attribute, float $default = 0.0): float
    {
        $el = $this->dom->byTagName($tagName)->getFirst();
        if ($el !== null) {
            $value = $el->getAttribute($attribute);
            if (!empty($value)) {
                return (float)$value;
            }
        }
        return $default;
    }

    private function tagIdentification(): ?ElementExtended
    {
        return $this->dom->byTagName('identification')->getFirst();
    }
}
```

#### 3. Modify `src/X4/Database/DatabaseBuilder.php`

Add this method:
```php
/**
 * Extract shields data.
 */
public static function extractShields() : void
{
    (new \Mistralys\X4\Database\Shields\ShieldsExtractor(self::getDataFolders()))->extract();
}
```

Add call in the `build()` method (after extractEngines):
```php
self::extractShields();
```

#### 4. Modify `composer.json`

Add script entry:
```json
"extract-shields": "Mistralys\\X4\\Database\\DatabaseBuilder::extractShields"
```

### Implementation Steps

1. Create `ShieldsExtractor.php`
2. Create `ShieldMacroExtractor.php`
3. Modify `DatabaseBuilder.php` to add `extractShields()` and call in `build()`
4. Modify `composer.json` to add composer script
5. Run `composer build` or `composer extract-shields`
6. Verify `data/shields.json` is created with ~107 entries
7. Run PHPStan: `composer phpstan`

### Verification Checklist

- [ ] `ShieldsExtractor` follows pattern from `EnginesExtractor`
- [ ] `ShieldMacroExtractor` parses all XML properties correctly
- [ ] `resolveShieldType()` correctly identifies all shield types
- [ ] `resolveIntegrated()` correctly parses integrated flag
- [ ] `DatabaseBuilder` integration is complete
- [ ] `composer extract-shields` command works
- [ ] `data/shields.json` is created with proper structure
- [ ] Shield count is ~107
- [ ] All shields have valid maker race and mk values
- [ ] PHPStan passes with no errors

### Reference Files
- Pattern: `src/X4/Database/Engines/EnginesExtractor.php`
- Pattern: `src/X4/Database/Engines/EngineMacroExtractor.php`
- Builder: `src/X4/Database/DatabaseBuilder.php`
- Config: `composer.json`

### Testing After Extraction

```php
// Test ShieldDefs loads successfully
$shields = ShieldDefs::getInstance()->getAll();
echo "Total shields: " . count($shields) . "\n";

// Test specific shield
$shield = ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1');
echo "Label: " . $shield->getLabel() . "\n";
echo "Capacity: " . $shield->getCapacity() . "\n";
echo "Recharge Rate: " . $shield->getRechargeRate() . "\n";
echo "Recharge Delay: " . $shield->getRechargeDelay() . "\n";
echo "Full Recharge Time: " . $shield->getFullRechargeTime() . "s\n";
echo "Hull Max: " . $shield->getHullMax() . "\n";
echo "Integrated: " . ($shield->isHullIntegrated() ? 'Yes' : 'No') . "\n";

// Test finder
$argonLarge = ShieldDefs::getInstance()->findShields()
    ->selectSize('l')
    ->selectMakerRace('argon')
    ->selectStandard()
    ->sortByCapacity()
    ->getAll();
echo "Argon large shields: " . count($argonLarge) . "\n";

// Test type filtering
$racers = ShieldDefs::getInstance()->findShields()
    ->selectRacer()
    ->getAll();
echo "Racer shields: " . count($racers) . "\n";
```

---

## 🔨 Work Package 5: Documentation & Tests

**Status:** Not Started  
**Estimated Time:** 1 hour  
**Dependencies:** WP4 must be complete (shields.json must exist)  
**Assigned To:** Unassigned

### Goal
Update project manifest documentation and create unit tests for shield system.

### Context
- **Manifest Updates:** Required per [AGENTS.md](../../AGENTS.md#manifest-maintenance-rules)
- **Testing:** Follow PHPUnit conventions used in project
- **Examples:** Study existing tests in `tests/X4Tests/Suites/`

### Documentation Updates Required

#### 1. Update [file-tree.md](../project-manifest/file-tree.md)

Add under `src/X4/Database/`:
```markdown
Shields/
    ShieldDef.php                  # Shield item class
    ShieldDefs.php                 # Shield collection (singleton)
    ShieldFinder.php               # Shield filter queries
    ShieldsExtractor.php           # Shield extraction orchestrator
    ShieldMacroExtractor.php       # Shield XML parser
    ShieldException.php            # Shield-specific exceptions
```

Add under `data/`:
```markdown
shields.json                       # Shield performance data (107 entries)
```

#### 2. Update [public-api.md](../project-manifest/public-api.md)

Add new section:
```markdown
### Database\Shields

#### ShieldDef
Immutable shield definition with performance characteristics.

**Public Methods:**
- `static fromArray(array): ShieldDef` - Create from JSON data
- `toArray(): array` - Serialize to array
- `getID(): string` - Get ware ID (primary key)
- `getLabel(): string` - Get display name
- `getWareID(): string` - Get ware ID
- `getMacroID(): string` - Get macro reference
- `getSize(): string` - Get size (s/m/l/xl)
- `getDataSourceID(): string` - Get data source
- `getMakerRace(): string` - Get maker race
- `getMk(): int` - Get mark level
- `getVariantID(): string` - Get variant ID
- `getShieldType(): string` - Get shield type
- `getRechargeMax(): float` - Get max capacity
- `getRechargeRate(): float` - Get recharge rate
- `getRechargeDelay(): float` - Get recharge delay
- `getCapacity(): float` - Alias for getRechargeMax()
- `getFullRechargeTime(): float` - Calculate full recharge time
- `getHullMax(): float` - Get hull durability
- `getHullThreshold(): float` - Get hull threshold
- `isHullIntegrated(): bool` - Check if integrated
- `hasHull(): bool` - Check if has hull
- `isStandard(): bool` - Check if standard type
- `isRacer(): bool` - Check if racer type
- `isCorvette(): bool` - Check if corvette type
- `isMothership(): bool` - Check if mothership type
- `isYacht(): bool` - Check if yacht type
- `isExperimental(): bool` - Check if experimental type
- `isVirtual(): bool` - Check if virtual type

#### ShieldDefs
Singleton collection of all shields.

**Public Methods:**
- `static getInstance(): ShieldDefs` - Get singleton instance
- `getByID(string): ShieldDef` - Get shield by ware ID
- `idExists(string): bool` - Check if shield exists
- `getAll(): ShieldDef[]` - Get all shields
- `getByType(string): ShieldDef[]` - Get shields by type
- `getDefault(): ShieldDef` - Get default shield
- `getDefaultID(): string` - Get default shield ID
- `findShields(): ShieldFinder` - Create finder for filtering
- `getDataFileName(): string` - Get data file name

#### ShieldFinder
Fluent interface for filtering shields.

**Public Methods:**
- `selectSize(string): self` - Filter by size
- `selectSizes(array): self` - Filter by multiple sizes
- `selectMakerRace(string): self` - Filter by maker race
- `selectType(string): self` - Filter by shield type
- `selectDataSource(string): self` - Filter by data source
- `selectMk(int): self` - Filter by mark level
- `selectMinMk(int): self` - Filter by minimum mark
- `selectMinCapacity(float): self` - Filter by minimum capacity
- `selectMaxCapacity(float): self` - Filter by maximum capacity
- `selectCapacityRange(float, float): self` - Filter by capacity range
- `selectMinRechargeRate(float): self` - Filter by minimum recharge rate
- `selectMaxRechargeDelay(float): self` - Filter by maximum delay
- `selectMaxRechargeTime(float): self` - Filter by maximum recharge time
- `selectWithHull(): self` - Filter shields with hull
- `selectWithoutHull(): self` - Filter shields without hull
- `selectMinHull(float): self` - Filter by minimum hull
- `selectIntegrated(): self` - Filter integrated shields
- `selectNonIntegrated(): self` - Filter non-integrated shields
- `selectStandard(): self` - Filter standard shields
- `selectRacer(): self` - Filter racer shields
- `selectCorvette(): self` - Filter corvette shields
- `selectMothership(): self` - Filter mothership shields
- `sortByCapacity(): self` - Sort by capacity (desc)
- `sortByRechargeRate(): self` - Sort by recharge rate (desc)
- `sortByRechargeTime(): self` - Sort by recharge time (asc)
- `sortByLabel(): self` - Sort alphabetically
- `getAll(): ShieldDef[]` - Get all filtered shields
- `getFirst(): ?ShieldDef` - Get first shield
- `count(): int` - Get count of shields
- `hasResults(): bool` - Check if has results

#### ShieldException
Shield-specific exceptions.

**Constants:**
- `ERROR_SHIELD_NOT_FOUND = 143001`
- `ERROR_INVALID_SHIELD_SIZE = 143002`
- `ERROR_INVALID_SHIELD_DATA = 143003`
- `ERROR_INVALID_SHIELD_TYPE = 143004`
```

#### 3. Update [data-flows.md](../project-manifest/data-flows.md)

Add new flow diagram:
```markdown
### Shield Extraction Flow

```mermaid
flowchart TD
    Start[Composer: extract-shields] --> Builder[DatabaseBuilder::extractShields]
    Builder --> Extractor[ShieldsExtractor::extract]
    Extractor --> Filter[Filter WareDefs for tag='shield']
    Filter --> Loop{For each shield ware}
    Loop --> MacroExt[ShieldMacroExtractor::extract]
    MacroExt --> WareDOM[WareDef->getMacro()->getDOM]
    WareDOM --> XMLParse[Parse XML: recharge, hull, identification]
    XMLParse --> TypeResolve[Resolve shield type from wareID]
    TypeResolve --> Array[Build data array]
    Array --> Loop
    Loop -->|All processed| Sort[Sort by wareID]
    Sort --> Write[Write to data/shields.json]
    Write --> Done[107 shields saved]

    style Start fill:#e1f5ff
    style Done fill:#d4edda
    style XMLParse fill:#fff3cd
```

**Key Steps:**
1. **Filter Wares**: Select wares with tag "shield" (107 total)
2. **Parse XML**: Extract recharge (max/rate/delay) and hull (max/threshold/integrated)
3. **Type Resolution**: Derive type from wareID pattern (_standard_, _racer_, etc.)
4. **Write JSON**: Sort and save to shields.json

**XML Structure:**
```xml
<macro name="shield_arg_l_standard_01_mk1_macro">
  <properties>
    <identification makerrace="argon" mk="1" />
    <recharge max="38844" rate="173" delay="0" />
    <hull max="2000" threshold="0.2" />
  </properties>
</macro>
```

**Data Access:**
```php
// Load collection (singleton)
$shields = ShieldDefs::getInstance()->getAll();

// Query specific shield
$shield = ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1');

// Filter with finder
$racers = ShieldDefs::getInstance()->findShields()
    ->selectRacer()
    ->selectMinCapacity(1000)
    ->sortByRechargeRate()
    ->getAll();
```
```

#### 4. Update [tech-stack.md](../project-manifest/tech-stack.md)

Add under "Data Files":
```markdown
- `shields.json` - Shield performance data (107 entries): capacity, recharge rate/delay, hull, integration
```

Add example under "Collection-Item Pattern":
```markdown
**Example: Shields**
- Item: `ShieldDef` - Individual shield with recharge/hull properties
- Collection: `ShieldDefs` - Singleton accessing shields.json
- Finder: `ShieldFinder` - Filter by size, race, type, capacity
```

### Test Files to Create

#### `tests/X4Tests/Suites/ShieldDefsTest.php`

```php
<?php

declare(strict_types=1);

namespace X4Tests\Suites;

use Mistralys\X4\Database\Shields\ShieldDefs;
use Mistralys\X4\Database\Shields\ShieldException;
use PHPUnit\Framework\TestCase;

class ShieldDefsTest extends TestCase
{
    public function test_getInstance_returnsSingleton(): void
    {
        $instance1 = ShieldDefs::getInstance();
        $instance2 = ShieldDefs::getInstance();
        
        $this->assertSame($instance1, $instance2);
    }
    
    public function test_getAll_returnsShields(): void
    {
        $shields = ShieldDefs::getInstance()->getAll();
        
        $this->assertNotEmpty($shields);
        $this->assertGreaterThan(100, count($shields)); // ~107 shields
    }
    
    public function test_getByID_validID_returnsShield(): void
    {
        $shield = ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1');
        
        $this->assertSame('shield_arg_l_standard_01_mk1', $shield->getWareID());
        $this->assertSame('l', $shield->getSize());
        $this->assertSame('argon', $shield->getMakerRace());
        $this->assertSame(1, $shield->getMk());
    }
    
    public function test_getByID_invalidID_throwsException(): void
    {
        $this->expectException(ShieldException::class);
        $this->expectExceptionCode(ShieldException::ERROR_SHIELD_NOT_FOUND);
        
        ShieldDefs::getInstance()->getByID('nonexistent_shield');
    }
    
    public function test_idExists_validID_returnsTrue(): void
    {
        $this->assertTrue(ShieldDefs::getInstance()->idExists('shield_arg_l_standard_01_mk1'));
    }
    
    public function test_idExists_invalidID_returnsFalse(): void
    {
        $this->assertFalse(ShieldDefs::getInstance()->idExists('nonexistent_shield'));
    }
    
    public function test_getByType_standard_returnsStandardShields(): void
    {
        $standard = ShieldDefs::getInstance()->getByType('standard');
        
        $this->assertNotEmpty($standard);
        foreach ($standard as $shield) {
            $this->assertTrue($shield->isStandard());
        }
    }
    
    public function test_findShields_returnsShieldFinder(): void
    {
        $finder = ShieldDefs::getInstance()->findShields();
        
        $this->assertInstanceOf(\Mistralys\X4\Database\Shields\ShieldFinder::class, $finder);
    }
}
```

#### `tests/X4Tests/Suites/ShieldFinderTest.php`

```php
<?php

declare(strict_types=1);

namespace X4Tests\Suites;

use Mistralys\X4\Database\Shields\ShieldDefs;
use PHPUnit\Framework\TestCase;

class ShieldFinderTest extends TestCase
{
    public function test_selectSize_filtersCorrectly(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectSize('l')
            ->getAll();
        
        $this->assertNotEmpty($shields);
        foreach ($shields as $shield) {
            $this->assertSame('l', $shield->getSize());
        }
    }
    
    public function test_selectMakerRace_filtersCorrectly(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMakerRace('argon')
            ->getAll();
        
        $this->assertNotEmpty($shields);
        foreach ($shields as $shield) {
            $this->assertSame('argon', $shield->getMakerRace());
        }
    }
    
    public function test_selectType_filtersCorrectly(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectType('racer')
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertTrue($shield->isRacer());
        }
    }
    
    public function test_selectMinCapacity_filtersCorrectly(): void
    {
        $minCapacity = 10000.0;
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMinCapacity($minCapacity)
            ->getAll();
        
        $this->assertNotEmpty($shields);
        foreach ($shields as $shield) {
            $this->assertGreaterThanOrEqual($minCapacity, $shield->getCapacity());
        }
    }
    
    public function test_chainedFilters_workCorrectly(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectSize('l')
            ->selectMakerRace('argon')
            ->selectStandard()
            ->selectMinCapacity(30000)
            ->sortByCapacity()
            ->getAll();
        
        $this->assertNotEmpty($shields);
        
        $prevCapacity = PHP_FLOAT_MAX;
        foreach ($shields as $shield) {
            $this->assertSame('l', $shield->getSize());
            $this->assertSame('argon', $shield->getMakerRace());
            $this->assertTrue($shield->isStandard());
            $this->assertGreaterThanOrEqual(30000, $shield->getCapacity());
            
            // Verify descending sort
            $this->assertLessThanOrEqual($prevCapacity, $shield->getCapacity());
            $prevCapacity = $shield->getCapacity();
        }
    }
    
    public function test_selectIntegrated_filtersCorrectly(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectIntegrated()
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertTrue($shield->isHullIntegrated());
        }
    }
    
    public function test_count_returnsCorrectCount(): void
    {
        $finder = ShieldDefs::getInstance()->findShields()->selectSize('m');
        
        $this->assertSame(count($finder->getAll()), $finder->count());
    }
}
```

### Implementation Steps

1. Update [file-tree.md](../project-manifest/file-tree.md)
2. Update [public-api.md](../project-manifest/public-api.md)
3. Update [data-flows.md](../project-manifest/data-flows.md)
4. Update [tech-stack.md](../project-manifest/tech-stack.md)
5. Create `tests/X4Tests/Suites/ShieldDefsTest.php`
6. Create `tests/X4Tests/Suites/ShieldFinderTest.php`
7. Run tests: `composer test`
8. Verify all tests pass

### Verification Checklist

- [ ] file-tree.md updated with Shields/ folder
- [ ] public-api.md updated with Database\Shields namespace
- [ ] data-flows.md updated with Shield Extraction Flow
- [ ] tech-stack.md updated with shields.json reference
- [ ] Unit tests created for ShieldDefs
- [ ] Unit tests created for ShieldFinder
- [ ] All tests pass: `composer test`
- [ ] Documentation is consistent with implementation
- [ ] No PHPStan errors

### Reference Files
- Existing tests: `tests/X4Tests/Suites/EngineDefsTest.php` (if created)
- Existing tests: `tests/X4Tests/Suites/ModuleDefsTest.php`
- Documentation: All manifest files in `docs/agents/project-manifest/`

---

## 📋 Implementation Summary

### Total Effort Estimate
- WP1 (ShieldDef): 1 hour
- WP2 (ShieldDefs): 1 hour
- WP3 (ShieldFinder): 1.5 hours
- WP4 (Extraction): 2-3 hours
- WP5 (Docs & Tests): 1 hour
- **Total: 5.5-7.5 hours**

### Implementation Order
```
Phase 1: Core Classes (3.5 hrs)
  ├─ WP1: ShieldDef + ShieldException
  ├─ WP2: ShieldDefs
  └─ WP3: ShieldFinder

Phase 2: Extraction (2-3 hrs)
  └─ WP4: ShieldsExtractor + ShieldMacroExtractor + Integration

Phase 3: Documentation (1 hr)
  └─ WP5: Manifest updates + Tests
```

### Success Criteria
- [ ] All 5 work packages completed
- [ ] `data/shields.json` exists with ~107 entries
- [ ] `composer extract-shields` works
- [ ] All unit tests pass
- [ ] PHPStan passes with no errors
- [ ] All manifest documents updated
- [ ] Shield filtering works via ShieldFinder
- [ ] Can query shields by ID, type, size, race
- [ ] Performance metrics (capacity, recharge) accessible

### Deliverables
- 6 new PHP classes in `src/X4/Database/Shields/`
- 1 new data file: `data/shields.json`
- 1 new composer script: `extract-shields`
- 2 new test suites
- 4 manifest document updates

---

## 🔗 Related Documentation

- [AGENTS.md](../../AGENTS.md) - AI agent operating procedures
- [tech-stack.md](../project-manifest/tech-stack.md) - Architectural patterns
- [constraints.md](../project-manifest/constraints.md) - Development rules
- [public-api.md](../project-manifest/public-api.md) - API reference
- [file-tree.md](../project-manifest/file-tree.md) - Project structure
- [data-flows.md](../project-manifest/data-flows.md) - Data flow diagrams

---

## 💡 Notes for Implementers

### Shield Type Resolution Strategy
Shield types are inferred from wareID patterns rather than XML attributes:
- Pattern: `shield_{race}_{size}_{type}_{variant}_mk{mark}`
- Example: `shield_arg_l_racer_01_mk1` → type = "racer"
- Fallback: If no type detected, use "standard"

### Hull Integration
The `integrated` attribute indicates shields that are built into the ship's hull:
- `integrated="1"` → Cannot be removed/replaced
- `integrated="0"` or missing → Standard removable shield
- Typically found on small/medium ships and special variants

### Performance Calculations
Full recharge time calculation:
```
rechargeTime = (capacity / rechargeRate) + rechargeDelay
```
This is exposed via `getFullRechargeTime()` for convenience.

### Data Validation
Shield data should be validated during extraction:
- All shields must have wareID, macroID, size
- Capacity (rechargeMax) should be > 0
- Recharge rate should be > 0
- Shield type must be one of the 7 known types
- MakerRace should not be "unknown" (indicates missing XML data)

### Testing Coverage
Tests should verify:
- Singleton behavior
- Collection loading
- Item lookup (valid/invalid IDs)
- Type filtering
- Performance filtering
- Chained filter operations
- Sorting operations
- Helper methods (getCapacity, getFullRechargeTime, type checks)

---

**End of Shield Metadata Implementation Plan**
