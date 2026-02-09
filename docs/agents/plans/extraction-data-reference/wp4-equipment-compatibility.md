# Work Package 4: Equipment Compatibility System

**Status:** � Complete  
**Completed Lines:** 577  
**Estimated Time:** 2-3 hours  
**Dependencies:** WP1 (Foundation), WP2 (Core Extraction Patterns)  
**Output File:** `docs/agents/project-manifest/extraction-reference.md` (append)

---

## 🎯 Objective

Document the equipment compatibility algorithm that determines which equipment can be installed on which ships:
1. Tag Matching System - How connection tags filter compatible equipment
2. Size Filtering - Ship slot sizes vs equipment sizes
3. Mixed-Size Slot Handling - Ships with multiple slot configurations
4. Equipment Finder API - How to query compatible equipment
5. Compatibility Flow - Complete algorithm walkthrough

This is critical knowledge from the recent ships/equipment implementation.

---

## 📋 Prerequisites

**Must Be Complete:**
- ✅ WP1 (Foundation & XML Sources)
- ✅ WP2 (Core Extraction Patterns)

**Optional:**
- WP3 (Advanced Features) - Helpful for understanding data sources

**Knowledge Required:**
- Understanding of Collection-Item pattern
- Finder pattern from tech-stack.md
- Ship equipment slot structure

**Files to Read Before Starting:**
1. [ShipEquipmentFinder.php](../../../src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php) - Complete finder implementation
2. [ShipDef.php](../../../src/X4/Database/Ships/ShipDef.php) - canEquip() method
3. [ShipsExtractor.php](../../../src/X4/Database/Ships/ShipsExtractor.php) - Equipment slot extraction
4. [ShipDefTests.php](../../../tests/X4Tests/Suites/Database/Ships/ShipDefTests.php) - Compatibility tests

---

## 📚 Context

### The Compatibility Problem

In X4, not all ships can use all equipment:
- Small ships can't use large shields
- Engine slots have different size requirements
- Different manufacturers may have tag restrictions
- Some equipment is DLC-specific

**Question:** Given a ship and equipment type, which specific items are compatible?

**Answer:** Multi-stage filtering using tags, sizes, and custom constraints.

### Why This Matters

Without a documented algorithm:
- Developers reinvent compatibility logic
- UI might show invalid equipment combinations
- Players confused by what they can install
- Equipment changes require code archaeology

With documented algorithm:
- Clear mental model
- Consistent across all equipment types
- Easy to extend for new equipment types
- Testable and verifiable

---

## 🔍 Source References

### Key Files

| File | Purpose | Lines to Study |
|------|---------|----------------|
| [ShipEquipmentFinder.php](../../../src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php) | Complete finder implementation | All methods |
| [ShipDef::canEquip()](../../../src/X4/Database/Ships/ShipDef.php) | Tag validation logic | canEquip() method |
| [ShipsExtractor](../../../src/X4/Database/Ships/ShipsExtractor.php) | Slot extraction | extractEquipmentSlots() |
| [ShipDefTests](../../../tests/X4Tests/Suites/Database/Ships/ShipDefTests.php) | Compatibility test cases | testEngineCompatibility(), testShieldCompatibility() |

### Equipment Slot JSON Structure

**Example from ships.json:**
```json
{
  "id": "ship_arg_s_fighter_01_a",
  "equipmentSlots": {
    "engines": [
      {
        "size": "s",
        "count": 1,
        "tags": ["engine", "small", "standard"]
      }
    ],
    "shields": [
      {
        "size": "s",
        "count": 2,
        "tags": ["shield", "small"]
      }
    ],
    "weapons": [
      {
        "size": "s",
        "count": 4,
        "tags": ["weapon", "small", "standard"]
      }
    ]
  }
}
```

---

## 🛠️ Implementation Steps

### Step 1: Document Tag Matching System

Add this section to `extraction-reference.md`:

```markdown
## 🔌 Equipment Compatibility Algorithm

### Overview

The equipment compatibility system determines which equipment items can be installed on which ships. It's a multi-stage filtering process combining:
1. Equipment type matching (engines, shields, weapons)
2. Tag validation (connection tags match equipment tags)
3. Size filtering (slot size matches equipment size)
4. Custom filters (data source, capacity, thrust, etc.)

### Tag Matching System

#### Ship Connection Tags

Ships specify required tags in connection XML:

```xml
<connection tags="engine large standard">
  <slot size="l" />
</connection>
```

**Meaning:** This slot requires equipment with tags `{"engine", "large", "standard"}`.

#### Equipment Tags

Equipment wares specify their tags in wares.xml:

```xml
<ware id="engine_arg_l_allround_01_mk1" tags="engine large standard" />
```

**Meaning:** This engine has tags `{"engine", "large", "standard"}`.

#### Matching Algorithm

**Rule:** Equipment must have **ALL** tags specified by the connection.

**Implementation:** [ShipDef::canEquip()](../../../src/X4/Database/Ships/ShipDef.php)

```php
public function canEquip(array $connectionTags, WareDef $ware): bool
{
    $equipmentTags = $ware->getTags();
    
    // Equipment must have all connection tags
    foreach ($connectionTags as $requiredTag) {
        if (!in_array($requiredTag, $equipmentTags, true)) {
            return false;  // Missing required tag
        }
    }
    
    return true;  // All tags present
}
```

**Examples:**

| Connection Tags | Equipment Tags | Compatible? | Reason |
|-----------------|----------------|-------------|--------|
| `["engine", "large"]` | `["engine", "large", "standard"]` | ✅ Yes | All required tags present |
| `["engine", "large", "standard"]` | `["engine", "large"]` | ❌ No | Missing "standard" tag |
| `["shield", "small"]` | `["shield", "small", "military"]` | ✅ Yes | Extra tags OK |
| `["weapon", "medium"]` | `["weapon", "large"]` | ❌ No | Wrong size tag |

### Size Filtering

#### Size Requirements

Equipment slots specify size constraints:

```xml
<slot size="s" />  <!-- Small only -->
<slot size="m" />  <!-- Medium only -->
<slot size="l" />  <!-- Large only -->
<slot size="xl" /> <!-- Extra-large only -->
```

**Rule:** Equipment size must **exactly match** slot size.

#### Size Extraction

**From Ware ID pattern:**
```
engine_{race}_{SIZE}_{type}_{variant}_{mk}
       ↑
       s, m, l, or xl
```

**Example:**
- `engine_arg_l_allround_01_mk1` → size = `'l'` (large)
- `shield_arg_s_standard_01_mk1` → size = `'s'` (small)
- `weapon_par_m_laser_01_mk1` → size = `'m'` (medium)

**Extraction Implementation:**
```php
private function extractSize(string $wareID): string
{
    $parts = explode('_', $wareID);
    // Size is typically the 3rd part: engine_arg_L_allround_...
    return $parts[2] ?? 'unknown';
}
```

#### Size Filtering in Finder

**Implementation:** [ShipEquipmentFinder::selectSize()](../../../src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php)

```php
public function selectSize(string $size): self
{
    $this->filters[] = function(WareDef $ware) use ($size) {
        return $ware->getSize() === $size;
    };
    
    return $this;
}
```

**Usage:**
```php
// Find only large engines
$largeEngines = $ship->getEngines()
    ->selectSize('l')
    ->getAll();
```

### Mixed-Size Slot Handling

#### The Problem

Some ships have multiple slot configurations for the same equipment type.

**Example: Medium freighter**
```json
{
  "shields": [
    {"size": "l", "count": 3, "tags": ["shield", "large"]},
    {"size": "m", "count": 9, "tags": ["shield", "medium"]}
  ]
}
```

**Meaning:** Ship has 3 large shield slots AND 9 medium shield slots.

#### Slot Group Structure

**JSON Format:**
```json
{
  "equipmentSlots": {
    "engines": [
      {"size": "s", "count": 1, "tags": ["engine", "small"]},
      {"size": "m", "count": 2, "tags": ["engine", "medium"]}
    ]
  }
}
```

Each array element is a **slot group** with:
- `size` - Required equipment size
- `count` - Number of slots in this group
- `tags` - Required equipment tags

#### Query Implications

**Finding compatible shields:**
```php
// Get all shields (any size) compatible with this ship
$allShields = $ship->getShields()->getAll();

// Result includes:
// - 3 large shields (for large slots)
// - 9 medium shields (for medium slots)
```

**Finding specific size:**
```php
// Get only large shields
$largeShields = $ship->getShields()
    ->selectSize('l')
    ->getAll();  // Returns shields for 3 large slots only
```

#### Extraction from XML

**ship macro XML:**
```xml
<connections>
  <!-- Large shield group -->
  <connection tags="shield large">
    <slot size="l" />
    <slot size="l" />
    <slot size="l" />
  </connection>
  
  <!-- Medium shield group -->
  <connection tags="shield medium">
    <slot size="m" />
    <slot size="m" />
    <!-- ... 7 more m slots ... -->
  </connection>
</connections>
```

**Extraction logic:** [ShipsExtractor::extractEquipmentSlots()](../../../src/X4/Database/Ships/ShipsExtractor.php)

```php
private function extractEquipmentSlots(DOMExtended $dom): array
{
    $slots = [
        'engines' => [],
        'shields' => [],
        'weapons' => []
    ];
    
    foreach ($dom->byTagName('connection')->getAll() as $connection) {
        $tags = array_filter(explode(' ', $connection->getAttribute('tags') ?? ''));
        $slotNodes = $connection->byTagName('slot')->getAll();
        
        // Determine equipment type from tags
        $type = $this->determineEquipmentType($tags);
        
        if ($type === null) {
            continue;  // Not an equipment connection
        }
        
        // Group slots by size
        $sizeGroups = [];
        foreach ($slotNodes as $slotNode) {
            $size = $slotNode->getAttribute('size');
            $sizeGroups[$size] = ($sizeGroups[$size] ?? 0) + 1;
        }
        
        // Create slot group for each size
        foreach ($sizeGroups as $size => $count) {
            $slots[$type][] = [
                'size' => $size,
                'count' => $count,
                'tags' => $tags
            ];
        }
    }
    
    return $slots;
}
```

### Equipment Finder API

#### Overview

**Pattern:** Fluent interface for filtering equipment by multiple criteria.

**Base Class:** [ShipEquipmentFinder](../../../src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php)

**Specialized Finders:**
- `ShipEngineFinder` - Extra filters: `selectMinForward()`, `selectMinBoost()`
- `ShipShieldFinder` - Extra filters: `selectMinCapacity()`, `selectMinRate()`
- `ShipWeaponFinder` - Extra filters: `selectMinDamage()`, `selectMinRange()`

#### Common API Methods

```php
// Base filtering (all equipment types)
selectDataSource(string $dataSource): self
selectSize(string $size): self
selectTag(string $tag): self
getAll(): WareDef[]
getFirst(): ?WareDef
count(): int

// Engine-specific
selectMinForward(float $minThrust): self
selectMinBoost(int $minDuration): self

// Shield-specific
selectMinCapacity(float $minCapacity): self
selectMinRate(float $minRate): self
selectMinThreshold(float $minThreshold): self

// Weapon-specific
selectMinDamage(float $minDamage): self
selectMinRange(float $minRange): self
selectMaxHeat(float $maxHeat): self
```

#### Usage Examples

**Find compatible engines:**
```php
use X4\Database\Ships\ShipDefs;

$ship = ShipDefs::getInstance()->getByID('ship_arg_s_fighter_01_a');

// All compatible engines
$allEngines = $ship->getEngines()->getAll();

// Only vanilla engines
$vanillaEngines = $ship->getEngines()
    ->selectDataSource('vanilla')
    ->getAll();

// Size l engines
$largeEngines = $ship->getEngines()
    ->selectSize('l')
    ->getAll();

// High-thrust engines
$fastEngines = $ship->getEngines()
    ->selectMinForward(3000.0)
    ->selectMinBoost(15000)
    ->getAll();
```

**Find compatible shields:**
```php
// All compatible shields
$allShields = $ship->getShields()->getAll();

// High-capacity shields
$strongShields = $ship->getShields()
    ->selectMinCapacity(15000.0)
    ->selectMinRate(200.0)
    ->getAll();

// Size m shields with specific tag
$mediumMilitary = $ship->getShields()
    ->selectSize('m')
    ->selectTag('military')
    ->getAll();
```

**Find compatible weapons:**
```php
// All compatible weapons
$allWeapons = $ship->getWeapons()->getAll();

// High-damage, long-range weapons
$sniperWeapons = $ship->getWeapons()
    ->selectMinDamage(500.0)
    ->selectMinRange(5000.0)
    ->getAll();

// Low-heat weapons (sustained fire)
$lowHeatWeapons = $ship->getWeapons()
    ->selectMaxHeat(1000.0)
    ->getAll();
```

### Compatibility Flow Diagram

**Complete algorithm visualized:**

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Get Equipment Type Collection                            │
│    $ship->getEngines() / getShields() / getWeapons()        │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. Filter by Ware Group                                     │
│    Engines: group='engine'                                  │
│    Shields: group='shield'                                  │
│    Weapons: group='weapon'                                  │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. Validate Tags (ShipDef::canEquip())                      │
│    Equipment must have ALL connection tags                  │
│    Example: connection tags ["engine", "large"]             │
│             → equipment must have both tags                 │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Apply Size Filter (if specified)                         │
│    Equipment size must match slot size exactly              │
│    selectSize('l') → only size 'l' equipment                │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 5. Apply Data Source Filter (if specified)                  │
│    selectDataSource('vanilla') → only vanilla items         │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 6. Apply Custom Filters (if specified)                      │
│    selectMinForward(2000.0) → forward >= 2000               │
│    selectMinCapacity(10000.0) → capacity >= 10000           │
│    etc.                                                     │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 7. Return Filtered WareDef[] Collection                     │
│    getAll() → array of compatible equipment                │
│    getFirst() → first match or null                         │
│    count() → number of compatible items                     │
└─────────────────────────────────────────────────────────────┘
```

### Implementation Details

**ShipEquipmentFinder Base:**
```php
namespace X4\Database\Ships\Equipment;

abstract class ShipEquipmentFinder
{
    private ShipDef $ship;
    private array $filters = [];
    
    public function __construct(ShipDef $ship)
    {
        $this->ship = $ship;
        $this->addBaseFilters();
    }
    
    abstract protected function getEquipmentType(): string;  // 'engines', 'shields', 'weapons'
    abstract protected function getWareGroup(): string;      // 'engine', 'shield', 'weapon'
    
    private function addBaseFilters(): void
    {
        // Filter 1: Ware group
        $this->filters[] = function(WareDef $ware) {
            return $ware->getGroup() === $this->getWareGroup();
        };
        
        // Filter 2: Tag validation
        $equipmentType = $this->getEquipmentType();
        $slots = $this->ship->getEquipmentSlots()[$equipmentType] ?? [];
        
        $this->filters[] = function(WareDef $ware) use ($slots) {
            foreach ($slots as $slotGroup) {
                if ($this->ship->canEquip($slotGroup['tags'], $ware)) {
                    return true;  // Compatible with at least one slot group
                }
            }
            return false;  // Not compatible with any slot group
        };
    }
    
    public function selectSize(string $size): self
    {
        $this->filters[] = fn(WareDef $w) => $w->getSize() === $size;
        return $this;
    }
    
    public function selectDataSource(string $dataSource): self
    {
        $this->filters[] = fn(WareDef $w) => $w->getDataSource() === $dataSource;
        return $this;
    }
    
    public function getAll(): array
    {
        $wares = WareDefs::getInstance()->getAll();
        
        foreach ($this->filters as $filter) {
            $wares = array_filter($wares, $filter);
        }
        
        return array_values($wares);
    }
}
```

**Specialized Finder Example:**
```php
namespace X4\Database\Ships\Equipment;

class ShipEngineFinder extends ShipEquipmentFinder
{
    protected function getEquipmentType(): string
    {
        return 'engines';
    }
    
    protected function getWareGroup(): string
    {
        return 'engine';
    }
    
    public function selectMinForward(float $minThrust): self
    {
        $this->filters[] = function(WareDef $ware) use ($minThrust) {
            $engine = EngineDefs::getInstance()->getByID($ware->getID());
            return $engine && $engine->getForward() >= $minThrust;
        };
        
        return $this;
    }
    
    public function selectMinBoost(int $minDuration): self
    {
        $this->filters[] = function(WareDef $ware) use ($minDuration) {
            $engine = EngineDefs::getInstance()->getByID($ware->getID());
            $boost = $engine?->getBoost();
            return $boost && $boost['duration'] >= $minDuration;
        };
        
        return $this;
    }
}
```
```

---

## ✅ Verification Steps

After completing this work package:

1. **Content Added:** Equipment Compatibility Algorithm section complete (400-500 lines)
2. **Diagram Included:** Compatibility flow diagram present
3. **Code Examples Accurate:** All examples match actual implementations
4. **API Documented:** All finder methods documented
5. **Real Examples:** Use actual ship/equipment IDs from data
6. **Cross-References Work:** Links to source files resolve

### Specific Checks

```bash
# Verify section added
Select-String "## 🔌 Equipment Compatibility Algorithm" docs/agents/project-manifest/extraction-reference.md

# Check for diagram
Select-String "Compatibility Flow Diagram" docs/agents/project-manifest/extraction-reference.md

# Verify API examples
Select-String "selectMinForward" docs/agents/project-manifest/extraction-reference.md
```

---

## 📤 Deliverables

1. **Section Added to extraction-reference.md:**
   - Equipment Compatibility Algorithm (400-500 lines)

2. **Content Includes:**
   - Tag matching system
   - Size filtering
   - Mixed-size slot handling
   - Equipment Finder API
   - Complete compatibility flow diagram
   - Implementation details
   - Usage examples for all equipment types

---

## 🔄 Next Steps

After WP4 completion:

1. **Update README:** Mark WP4 as complete
2. **Proceed to WP5:** Developer Support (requires WP1-4)

**Dependencies Met:**
- WP5 can now start (requires WP1-4) ✅
- WP6 still blocked (requires WP1-5)

---

## 📝 Notes

- Compatibility algorithm is most complex part of equipment system
- Tag matching is strict (ALL tags required)
- Size matching is exact (no size substitution)
- Mixed-size slots are common, document carefully
- Finder API is fluent interface pattern

---

**Work Package Status:** � Complete  
**Created:** February 9, 2026  
**Completed:** February 9, 2026
