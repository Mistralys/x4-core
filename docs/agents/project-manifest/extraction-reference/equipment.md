# Equipment Compatibility System

> **Module:** Extraction Equipment  
> **Category:** Data Extraction Patterns  
> **Last Updated:** February 9, 2026  
> **Dependencies:** Foundation, Core Extraction Patterns

---

## 🎯 Purpose

Documents the equipment compatibility algorithm that determines which equipment items can be installed on which ships. This is a multi-stage filtering process combining type matching, tag validation, size filtering, and custom criteria.

**Key Questions Answered:**
- Which engines can this ship use?
- What shields fit this ship's slots?
- How are slot sizes matched to equipment?
- How do mixed-size slots work?

---

## 📚 Quick Navigation

- [Equipment Compatibility Algorithm](#-equipment-compatibility-algorithm)
- [Tag Matching Rules](#-tag-matching-rules)
- [Size Filtering](#-size-filtering)
- [Mixed-Size Slot Handling](#-mixed-size-slot-handling)
- [Equipment Finder API](#-equipment-finder-api)
- [Complete Code Examples](#-complete-code-examples)

---

## 🔌 Equipment Compatibility Algorithm

### Overview

The equipment compatibility system is a **multi-stage filtering process** that determines which equipment wares can be installed on a ship's equipment slots.

**Stages:**
1. **Equipment Type Filtering** - Filter by slot type (engine, shield, weapon, turret, etc.)
2. **Ware Group Pre-filtering** - Quickly exclude incompatible ware groups
3. **Tag Validation** - Equipment must have required slot tags
4. **Size Matching** - Equipment size must match slot size exactly
5. **Custom Filters** - Optional filters (data source, performance stats, etc.)

**Pattern:** Finder pattern with fluent interface (from [tech-stack.md](../tech-stack.md))

---

### Compatibility Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Ship Equipment Query                                     │
│    $ship->getEngines() / getShields() / getWeapons()        │
│    Creates: ShipEquipmentFinder instance                    │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. Pre-filter by Ware Group                                 │
│    Engines → group='engines'                                │
│    Shields → group='shields'                                │
│    Weapons → group='weapons'                                │
│    Turrets → group='turrets'                                │
│    (Reduces search space by ~80%)                           │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. Equipment Tag Check                                      │
│    Must have 'equipment' tag in ware tags                   │
│    Non-equipment wares filtered out                         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Slot Compatibility Check (ShipDef::canEquip)            │
│    Iterate all ship's equipment slot groups                 │
│    For each slot: ShipSlotDefinition::canEquip()            │
│    Equipment compatible if ANY slot accepts it              │
└─────────────────────────────────────────────────────────────┘
                           ↓
         ┌─────────────────┴─────────────────┐
         ↓                                   ↓
┌──────────────────────┐          ┌──────────────────────┐
│ 5a. Size Check       │          │ 5b. Type Check       │
│ slot.size == ware.size│          │ slot has ware type  │
│ Must match exactly   │          │ (engine/shield/etc.) │
└──────────────────────┘          └──────────────────────┘
         ↓                                   ↓
         └─────────────────┬─────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. Apply Custom Filters (Optional)                          │
│    selectDataSource() → filter by DLC/vanilla               │
│    selectSize() → filter by specific size                   │
│    selectTag() → filter by additional tags                  │
│    Performance filters (min thrust, capacity, etc.)         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. Return Filtered Results                                  │
│    getAll() → WareDef[] array                              │
│    getFirst() → ?WareDef (first match or null)             │
│    count() → int (number of matches)                       │
└─────────────────────────────────────────────────────────────┘
```

---

## 🏷️ Tag Matching Rules

### Connection Tags vs Ware Tags

**Ship slots specify required tags** in their connection definitions:
```xml
<connection tags="engine large standard">
  <slot size="l" />
</connection>
```

**Equipment wares specify their capability tags:**
```xml
<ware id="engine_arg_l_allround_01_mk1" tags="engine large standard equipment" />
```

### Matching Algorithm

**Rule:** Equipment must have **ALL** tags specified by the slot's connection type tag.

**Source:** [ShipSlotDefinition::canEquip()](../../../../src/X4/Database/Ships/Equipment/ShipSlotDefinition.php)

```php
public function canEquip(WareDef $ware) : bool
{
    // 1. Size Check - Must match exactly
    if ($this->size !== '' && $ware->getSize() !== $this->size) {
        return false;
    }

    $wareTags = $ware->getCompatibilityTags();

    // 2. Primary Type Check - Identify slot type
    $types = ['engine', 'shield', 'turret', 'weapon', 'dockingbay', 'countermeasures'];
    $slotType = null;
    foreach ($types as $type) {
        if ($this->hasTag($type)) {
            $slotType = $type;
            break;
        }
    }

    // If slot has a type, ware must have that type
    if ($slotType && !in_array($slotType, $wareTags, true)) {
        return false;
    }

    return true;
}
```

### Tag Matching Examples

| Slot Tags | Equipment Tags | Compatible? | Reason |
|-----------|----------------|-------------|---------|
| `["engine", "large"]` | `["engine", "large", "standard", "equipment"]` | ✅ Yes | Has required type and size |
| `["engine", "large", "standard"]` | `["engine", "large", "equipment"]` | ✅ Yes | Has required type and size (standard not strictly enforced) |
| `["shield", "small"]` | `["shield", "small", "military", "equipment"]` | ✅ Yes | Extra tags allowed |
| `["weapon", "medium"]` | `["weapon", "large", "equipment"]` | ❌ No | Size mismatch (m vs l) |
| `["turret", "medium"]` | `["weapon", "medium", "equipment"]` | ❌ No | Type mismatch (turret vs weapon) |
| `["engine", "small"]` | `["shield", "small", "equipment"]` | ❌ No | Type mismatch |

### Ship-Level Validation

**Source:** [ShipDef::canEquip()](../../../../src/X4/Database/Ships/ShipDef.php)

```php
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
            return true;  // Compatible with at least one slot
        }
    }
    return false;  // Not compatible with any slot
}
```

**Algorithm:**
- Ship has multiple slot groups (e.g., 3 large shields, 5 medium shields)
- Equipment is compatible if **ANY** slot group accepts it
- Early exit on first match (performance optimization)

---

## 📏 Size Filtering

### Size Requirements

Equipment slots specify exact size constraints:

```xml
<slot size="s" />  <!-- Small only -->
<slot size="m" />  <!-- Medium only -->
<slot size="l" />  <!-- Large only -->
<slot size="xl" /> <!-- Extra-large only -->
```

**Rule:** Equipment size must **exactly match** slot size. No size substitution allowed.

### Size Extraction from Ware IDs

**Ware ID Pattern:**
```
{type}_{race}_{SIZE}_{variant}_{mk}
              ↑
              s, m, l, or xl
```

**Examples:**
- `engine_arg_l_allround_01_mk1` → size = `'l'` (large)
- `shield_arg_s_standard_01_mk1` → size = `'s'` (small)
- `weapon_par_m_laser_01_mk1` → size = `'m'` (medium)
- `turret_arg_xl_plasma_01_mk1` → size = `'xl'` (extra-large)

**Extraction Implementation:**
```php
// In WareDef class
public function getSize(): string
{
    // Size is typically the 3rd segment in ware IDs
    $parts = explode('_', $this->getID());
    $sizeCandidate = $parts[2] ?? '';
    
    // Validate size
    $validSizes = ['s', 'm', 'l', 'xl'];
    if (in_array($sizeCandidate, $validSizes, true)) {
        return $sizeCandidate;
    }
    
    return '';  // Unknown/no size
}
```

### Size Filtering in Finder

**Source:** [ShipEquipmentFinder::selectSize()](../../../../src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php)

```php
/**
 * Filter by equipment size.
 * @param string $size Size code: 's', 'm', 'l', 'xl'
 * @return $this
 */
public function selectSize(string $size): self
{
    if (!in_array($size, $this->sizes, true)) {
        $this->sizes[] = $size;
    }

    return $this;
}

protected function isMatch(CollectionItemInterface $item): bool
{
    // ... other checks ...
    
    // Check size filters
    if (!empty($this->sizes) && !in_array($item->getSize(), $this->sizes, true)) {
        return false;
    }
    
    return true;
}
```

**Usage:**
```php
// Find only large engines
$largeEngines = $ship->getEngines()
    ->selectSize('l')
    ->getAll();

// Find medium or large shields
$shields = $ship->getShields()
    ->selectSize('m')
    ->selectSize('l')  // Multiple sizes OR'd together
    ->getAll();
```

---

## 🔄 Mixed-Size Slot Handling

### The Problem

**Many ships have multiple slot configurations for the same equipment type.**

**Example: Argon Nemesis (L Destroyer)**
```json
{
  "shields": [
    {"size": "l", "count": 3, "tags": ["shield", "large"]},
    {"size": "m", "count": 12, "tags": ["shield", "medium"]}
  ],
  "weapons": [
    {"size": "l", "count": 4, "tags": ["weapon", "large"]},
    {"size": "m", "count": 8, "tags": ["weapon", "medium"]}
  ]
}
```

**Meaning:**
- Ship has **3 large shield slots** AND **12 medium shield slots**
- Ship has **4 large weapon slots** AND **8 medium weapon slots**

### Slot Group Structure

Each equipment type has an **array of slot groups**, where each group represents a unique size/tag combination.

**JSON Schema:**
```json
{
  "equipmentSlots": {
    "engines": [
      {"size": "s", "count": 2, "tags": ["engine", "small"]},
      {"size": "m", "count": 1, "tags": ["engine", "medium"]}
    ],
    "shields": [
      {"size": "l", "count": 3, "tags": ["shield", "large"]},
      {"size": "m", "count": 9, "tags": ["shield", "medium"]}
    ]
  }
}
```

### Query Implications

**Finding all compatible equipment (any size):**
```php
// Returns equipment for ALL slot groups
$allShields = $ship->getShields()->getAll();
// Result: Large shields (for 3 slots) + Medium shields (for 9 slots)
```

**Finding specific size:**
```php
// Returns equipment for ONLY large slot groups
$largeShields = $ship->getShields()
    ->selectSize('l')
    ->getAll();
// Result: Only large shields (for 3 large slots)

// Returns equipment for ONLY medium slot groups
$mediumShields = $ship->getShields()
    ->selectSize('m')
    ->getAll();
// Result: Only medium shields (for 9 medium slots)
```

### Slot Group Iteration

**Source:** [ShipDef::getEquipmentGroups()](../../../../src/X4/Database/Ships/ShipDef.php)

```php
/**
 * Get all equipment slot groups for this ship.
 * 
 * @param string|null $type Optional filter: 'engines', 'shields', 'weapons', etc.
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
            // Single slot group
            $result[] = ShipSlotDefinition::fromArray($groupData);
        } elseif (is_array($groupData)) {
            // Multiple slot groups
            foreach ($groupData as $item) {
                if (is_array($item)) {
                    $result[] = ShipSlotDefinition::fromArray($item);
                }
            }
        }
    }
    return $result;
}
```

### Extraction from XML

**Ship macro XML example:**
```xml
<connections>
  <!-- Large shield group (3 slots) -->
  <connection tags="shield large">
    <slot size="l" />
    <slot size="l" />
    <slot size="l" />
  </connection>
  
  <!-- Medium shield group (12 slots) -->
  <connection tags="shield medium">
    <slot size="m" />
    <slot size="m" />
    <slot size="m" />
    <!-- ... 9 more m slots ... -->
  </connection>
  
  <!-- Large weapon group (4 slots) -->
  <connection tags="weapon large">
    <slot size="l" />
    <slot size="l" />
    <slot size="l" />
    <slot size="l" />
  </connection>
</connections>
```

**Extraction Result:**
```json
{
  "shields": [
    {"size": "l", "count": 3, "tags": ["shield", "large"]},
    {"size": "m", "count": 12, "tags": ["shield", "medium"]}
  ],
  "weapons": [
    {"size": "l", "count": 4, "tags": ["weapon", "large"]}
  ]
}
```

---

## 🔍 Equipment Finder API

### Overview

**Pattern:** Fluent interface for filtering equipment by multiple criteria.

**Base Class:** [ShipEquipmentFinder](../../../../src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php)

**Purpose:** Find all equipment compatible with a ship's specific slot type, with optional filtering.

### Common API Methods

```php
// Base filtering (all equipment types)
selectDataSource(string $dataSource): self  // Filter by DLC/vanilla
selectSize(string $size): self              // Filter by size (s/m/l/xl)
selectTag(string $tag): self                // Filter by additional tag
selectLabelContains(string $search): self   // Search by label text

// Result retrieval
getAll(): WareDef[]                         // Get all matching equipment
getFirst(): ?WareDef                        // Get first match or null
count(): int                                // Count matching equipment
```

### Creating Equipment Finders

**Source:** [ShipDef equipment finder methods](../../../../src/X4/Database/Ships/ShipDef.php)

```php
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
```

### Usage Examples

**Find compatible engines:**
```php
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\DataSources\KnownDataSources;

$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');

// All compatible engines
$allEngines = $ship->getEngines()->getAll();

// Only vanilla engines
$vanillaEngines = $ship->getEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
    ->getAll();

// Only large engines
$largeEngines = $ship->getEngines()
    ->selectSize('l')
    ->getAll();

// Large engines from a specific DLC
$dlcLargeEngines = $ship->getEngines()
    ->selectDataSource('ego_dlc_terran')
    ->selectSize('l')
    ->getAll();
```

**Find compatible shields:**
```php
// All compatible shields (all sizes)
$allShields = $ship->getShields()->getAll();

// Only large shields
$largeShields = $ship->getShields()
    ->selectSize('l')
    ->getAll();

// Medium shields with specific tag
$mediumMilitary = $ship->getShields()
    ->selectSize('m')
    ->selectTag('military')
    ->getAll();

// Search by label
$argonShields = $ship->getShields()
    ->selectLabelContains('Argon')
    ->getAll();
```

**Find compatible weapons:**
```php
// All compatible weapons
$allWeapons = $ship->getWeapons()->getAll();

// Large weapons only
$largeWeapons = $ship->getWeapons()
    ->selectSize('l')
    ->getAll();

// Combine multiple filters
$specificWeapons = $ship->getWeapons()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
    ->selectSize('m')
    ->selectTag('pulse')
    ->getAll();
```

**Count and existence checks:**
```php
// Count compatible equipment
$engineCount = $ship->getEngines()->count();
$largeShieldCount = $ship->getShields()->selectSize('l')->count();

// Check if any compatible equipment exists
$hasVanillaEngines = $ship->getEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
    ->count() > 0;

// Get first match
$firstEngine = $ship->getEngines()->getFirst();
if ($firstEngine !== null) {
    echo "First compatible engine: " . $firstEngine->getLabel();
}
```

---

## 💻 Complete Code Examples

### Example 1: Equipment Compatibility Check

```php
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\Wares\WareDefs;

// Load the ship
$ship = ShipDefs::getInstance()->getByID('ship_arg_s_fighter_01_a');

// Load a specific engine
$engine = WareDefs::getInstance()->getByID('engine_arg_l_allround_01_mk1');

// Check if ship can equip this engine
if ($ship->canEquip($engine)) {
    echo "✅ Compatible: {$engine->getLabel()} can be installed on {$ship->getLabel()}";
    echo "\n  Reason: Size and tags match slot requirements";
} else {
    echo "❌ Incompatible: {$engine->getLabel()} cannot be installed on {$ship->getLabel()}";
    echo "\n  Reason: Size or tag mismatch (ship is size S, engine is size L)";
}
```

### Example 2: List All Compatible Equipment

```php
use Mistralys\X4\Database\Ships\ShipDefs;

$ship = ShipDefs::getInstance()->getByID('ship_arg_m_corvette_01_a');

echo "=== {$ship->getLabel()} Compatible Equipment ===\n\n";

// List all compatible engines
echo "Engines ({$ship->countEngines()} slots):\n";
foreach ($ship->getEngines()->getAll() as $engine) {
    echo "  - {$engine->getLabel()} (size: {$engine->getSize()}, source: {$engine->getDataSourceID()})\n";
}

// List all compatible shields
echo "\nShields ({$ship->countShields()} slots):\n";
foreach ($ship->getShields()->getAll() as $shield) {
    echo "  - {$shield->getLabel()} (size: {$shield->getSize()})\n";
}

// List all compatible weapons
echo "\nWeapons ({$ship->countWeapons()} slots):\n";
foreach ($ship->getWeapons()->getAll() as $weapon) {
    echo "  - {$weapon->getLabel()} (size: {$weapon->getSize()})\n";
}
```

### Example 3: Filter by Multiple Criteria

```php
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\DataSources\KnownDataSources;

$ship = ShipDefs::getInstance()->getByID('ship_arg_xl_carrier_01_a');

// Find large, vanilla engines
$vanillaLargeEngines = $ship->getEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
    ->selectSize('l')
    ->getAll();

echo "Vanilla large engines for {$ship->getLabel()}:\n";
foreach ($vanillaLargeEngines as $engine) {
    echo "  - {$engine->getLabel()}\n";
}

// Find medium shields with specific tag
$combatShields = $ship->getShields()
    ->selectSize('m')
    ->selectTag('combat')
    ->getAll();

echo "\nMedium combat shields:\n";
foreach ($combatShields as $shield) {
    echo "  - {$shield->getLabel()}\n";
}
```

### Example 4: Mixed-Size Slot Handling

```php
use Mistralys\X4\Database\Ships\ShipDefs;

$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');

// Get all equipment slot groups
$shieldGroups = $ship->getEquipmentGroups('shields');

echo "Shield slot configuration for {$ship->getLabel()}:\n";
foreach ($shieldGroups as $group) {
    echo "  - Size {$group->getSize()}: {$group->getCount()} slots\n";
    echo "    Tags: " . implode(', ', $group->getTags()) . "\n";
}

// Find equipment for each slot size
echo "\nLarge shields:\n";
foreach ($ship->getShields()->selectSize('l')->getAll() as $shield) {
    echo "  - {$shield->getLabel()}\n";
}

echo "\nMedium shields:\n";
foreach ($ship->getShields()->selectSize('m')->getAll() as $shield) {
    echo "  - {$shield->getLabel()}\n";
}
```

### Example 5: Complete Compatibility Implementation

**Full implementation of isMatch() method:**

**Source:** [ShipEquipmentFinder::isMatch()](../../../../src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php)

```php
/**
 * Check if a ware matches all filter criteria.
 *
 * @param CollectionItemInterface|WareDef $item
 * @return bool
 */
protected function isMatch(CollectionItemInterface $item): bool
{
    // Stage 1: Must be equipment ware
    if (!$item->hasTag('equipment')) {
        return false;
    }

    // Stage 2: Filter by ware group (performance optimization)
    $expectedGroup = self::SLOT_TO_GROUP_MAP[$this->slotTypeID] ?? '';
    if ($expectedGroup !== '' && $item->getGroupID() !== $expectedGroup) {
        return false;
    }

    // Stage 3: Check ship compatibility (size + tags)
    if (!$this->ship->canEquip($item)) {
        return false;
    }

    // Stage 4: Check data source filter
    if (!$this->isDataSourceMatch($item->getDataSourceID())) {
        return false;
    }

    // Stage 5: Check label search filter
    if (!$this->isLabelMatch($item->getLabel())) {
        return false;
    }

    // Stage 6: Check size filters
    if (!empty($this->sizes) && !in_array($item->getSize(), $this->sizes, true)) {
        return false;
    }

    // Stage 7: Check tag filters (all must be present)
    foreach ($this->tags as $tag) {
        if (!in_array($tag, $item->getTags(), true)) {
            return false;
        }
    }

    // All filters passed
    return true;
}
```

---

## 🗺️ Ware Group Mapping

### Slot Type to Ware Group Optimization

To reduce search space, each slot type is mapped to a specific ware group:

**Source:** [ShipEquipmentFinder::SLOT_TO_GROUP_MAP](../../../../src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php)

```php
/**
 * Map slot type IDs to ware group IDs for efficient pre-filtering
 */
private const SLOT_TO_GROUP_MAP = [
    KnownSlotTypes::ENGINE => WareGroups::GROUP_ENGINES,
    KnownSlotTypes::SHIELD => WareGroups::GROUP_SHIELDS,
    KnownSlotTypes::WEAPON => WareGroups::GROUP_WEAPONS,
    KnownSlotTypes::TURRET => WareGroups::GROUP_TURRETS,
    KnownSlotTypes::COUNTERMEASURES => WareGroups::GROUP_COUNTERMEASURES,
    KnownSlotTypes::DOCKING_BAY => '' // Docking bays don't have a ware group
];
```

**Performance Impact:**
- Reduces search space by ~80% before tag/size checks
- Prevents unnecessary compatibility checks on irrelevant wares
- Most noticeable on large ships with many slot types

---

## 📋 Summary

### Key Takeaways

1. **Multi-Stage Filtering:**
   - Equipment type → Ware group → Tag validation → Size matching → Custom filters

2. **Tag Validation:**
   - Equipment must have the slot type tag (engine/shield/weapon/etc.)
   - Size tags are validated separately via size matching

3. **Size Matching:**
   - Size must match **exactly** (no size substitution)
   - Extracted from ware ID pattern: `{type}_{race}_{SIZE}_{variant}_{mk}`

4. **Mixed-Size Slots:**
   - Ships commonly have multiple slot groups per equipment type
   - Each slot group has size, count, and tags
   - Equipment compatible if it matches **ANY** slot group

5. **Finder API:**
   - Fluent interface pattern for easy filtering
   - Methods: `selectDataSource()`, `selectSize()`, `selectTag()`, `selectLabelContains()`
   - Results: `getAll()`, `getFirst()`, `count()`

6. **Performance Optimization:**
   - Ware group pre-filtering reduces search space by 80%
   - Early exit on first incompatible check
   - Equipment tag check prevents non-equipment wares

### Related Documentation

- [tech-stack.md](../tech-stack.md) - Finder pattern architecture
- [public-api.md](../public-api.md) - ShipDef and ShipEquipmentFinder signatures
- [data-flows.md](../data-flows.md) - Equipment query flow diagrams

---

**Last Updated:** February 9, 2026  
**Document Version:** 1.0
