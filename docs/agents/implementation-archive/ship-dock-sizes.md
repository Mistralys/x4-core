# Ship Dock Sizes - Implementation Plan

**Status:** Not Started  
**Created:** February 8, 2026  
**Priority:** Medium  
**Estimated Effort:** 4-6 hours

---

## 🎯 Problem Statement

### Current State
Ship dock data in `ships.json` shows counts but not sizes:
```json
"docks": [
    {
        "size": "",
        "count": 13,
        "tags": ["dockingbay"]
    },
    {
        "size": "",
        "count": 1,
        "tags": ["dock_xs"]
    }
]
```

### Desired State
Simplified dock structure with size breakdown:
```json
"docks": {
    "m": 4,
    "s": 8
}
```

### Why This Matters
- In-game, the Colossus Vanguard shows "8 small + 4 medium" docks
- Current data doesn't differentiate between dock sizes
- Applications using this data can't determine ship capacity by ship size class
- The `xs` (spacesuit) docks should be filtered out as they're not relevant for ship docking

---

## 🔍 Technical Research Summary

### Where Dock Size Data Lives

The dock size information is **NOT** in the ship component XML but in the **dock macro XML files**:

#### Ship Macro XML Reference
**File:** `x4-data-extractor/output/vanilla/assets/units/size_xl/macros/ship_arg_xl_carrier_01_a_macro.xml`
```xml
<connection ref="con_launchtube_arg_s_01_a">
    <macro ref="launchtube_arg_s_01_macro" connection="Connection02" />
</connection>
<connection ref="con_launchtube_arg_m_01_a">
    <macro ref="launchtube_arg_m_01_macro" connection="Connection02" />
</connection>
```

#### Dock Macro XML (Where Size Is Defined)
**File:** `x4-data-extractor/output/vanilla/assets/structures/dock/macros/launchtube_arg_s_01_macro.xml`
```xml
<macro name="launchtube_arg_s_01_macro" class="dockingbay">
    <properties>
        <docksize tags="dock_s" />  <!-- SIZE INFO HERE -->
    </properties>
</macro>
```

**File:** `x4-data-extractor/output/vanilla/assets/structures/dock/macros/launchtube_arg_m_01_macro.xml`
```xml
<macro name="launchtube_arg_m_01_macro" class="dockingbay">
    <properties>
        <docksize tags="dock_m" />  <!-- MEDIUM DOCK -->
    </properties>
</macro>
```

### Available Dock Sizes
- `dock_xs` - Extra Small (spacesuit only, filter out)
- `dock_s` - Small (fighters)
- `dock_m` - Medium (corvettes, frigates)
- `dock_l` - Large (destroyers)
- `dock_xl` - Extra Large (carriers)

### Current Extraction Flow
1. `ShipsExtractor` reads ship **component** XML (connections only)
2. `ShipSlotAggregator` aggregates by connection tags
3. ❌ **Never reads dock macro XML to get size info**

---

## 📦 Work Packages

### Package 1: Macro Loader Infrastructure
**Effort:** 1-2 hours  
**Dependencies:** None  
**Project:** x4-data-extractor

#### Objective
Add capability to load dock macro XML files and extract dock size information.

#### Files to Modify
- `src/DataExtractor/Ships/ShipsExtractor.php`

#### Implementation Details

1. **Add new method to ShipsExtractor:**
```php
/**
 * Extracts dock size from a dock macro XML file.
 * 
 * @param string $macroName Name of the dock macro (e.g., "launchtube_arg_s_01_macro")
 * @return string|null Dock size (xs, s, m, l, xl) or null if not found
 */
private function extractDockSize(string $macroName): ?string
{
    try {
        // Get macro file from macro index
        $macroDef = \X4\Database\MacroIndex\MacroFileDefs::getInstance()
            ->getByMacroName($macroName);
        
        if ($macroDef === null) {
            return null;
        }
        
        $macroDOM = $macroDef->getDOM();
        
        // Find <docksize tags="dock_s"/> element
        $docksizeNodes = $macroDOM->getElementsByTagName('docksize');
        if ($docksizeNodes->length === 0) {
            return null;
        }
        
        $tags = $docksizeNodes->item(0)->getAttribute('tags');
        
        // Extract size from tags (e.g., "dock_s" -> "s")
        if (preg_match('/dock_(xs|s|m|l|xl)/', $tags, $matches)) {
            return $matches[1];
        }
        
        return null;
        
    } catch (\Exception $e) {
        // Log but don't fail extraction for missing macros
        error_log("Failed to extract dock size for macro: $macroName - " . $e->getMessage());
        return null;
    }
}
```

2. **Update extraction to load ship macro XML:**

Find the method that extracts ship data (likely `extractShipData()` or similar). After loading the component XML, add:

```php
// Load ship macro XML to get dock macro references
$shipMacroDef = \X4\Database\MacroIndex\MacroFileDefs::getInstance()
    ->getByMacroName($shipMacroName);

if ($shipMacroDef !== null) {
    $shipMacroDOM = $shipMacroDef->getDOM();
    
    // Get all <connection> elements with <macro> children
    $connections = $shipMacroDOM->getElementsByTagName('connection');
    $dockSizes = [];
    
    foreach ($connections as $connection) {
        $macroNodes = $connection->getElementsByTagName('macro');
        if ($macroNodes->length > 0) {
            $dockMacroName = $macroNodes->item(0)->getAttribute('ref');
            $connectionRef = $connection->getAttribute('ref');
            
            // Extract dock size from dock macro
            $size = $this->extractDockSize($dockMacroName);
            if ($size !== null) {
                $dockSizes[$connectionRef] = $size;
            }
        }
    }
    
    // Pass $dockSizes to aggregator
}
```

#### Testing
Create a test script that:
```php
$extractor = new \DataExtractor\Ships\ShipsExtractor();
$size = $extractor->extractDockSize('launchtube_arg_s_01_macro');
assert($size === 's', "Expected 's', got: $size");

$size = $extractor->extractDockSize('launchtube_arg_m_01_macro');
assert($size === 'm', "Expected 'm', got: $size");
```

#### Success Criteria
- ✅ Can load dock macro XML files via MacroFileDefs
- ✅ Can extract size from `<docksize tags="...">` element
- ✅ Returns correct size (s, m, l, xl) for known dock macros
- ✅ Gracefully handles missing/invalid macros

---

### Package 2: Aggregator Size Support
**Effort:** 1-2 hours  
**Dependencies:** Package 1  
**Project:** x4-data-extractor

#### Objective
Modify `ShipSlotAggregator` to accept and aggregate dock size information.

#### Files to Modify
- `src/DataExtractor/Ships/ShipSlotAggregator.php`

#### Implementation Details

1. **Update addConnection method signature:**

Find the `addConnection()` method and add a `$dockSize` parameter:

```php
public function addConnection(array $tags, ?string $dockSize = null): self
{
    $type = $this->resolveType($tags);
    
    if ($type === 'dockingbay') {
        // Store dock size with the connection
        $this->addDock($tags, $dockSize);
    } else {
        // Existing logic for other connection types
        $this->addSlot($type);
    }
    
    return $this;
}
```

2. **Add new addDock method:**

```php
private array $docks = [];

private function addDock(array $tags, ?string $size): void
{
    // Filter out xs docks (spacesuit docks)
    if ($size === 'xs') {
        return;
    }
    
    // Initialize size counter if not exists
    if (!isset($this->docks[$size])) {
        $this->docks[$size] = 0;
    }
    
    $this->docks[$size]++;
}
```

3. **Update getDocks method:**

Replace current `getDocks()` implementation to return the new format:

```php
public function getDocks(): array
{
    // Return empty object if no docks
    if (empty($this->docks)) {
        return [];
    }
    
    // Sort by size key (l, m, s, xl)
    ksort($this->docks);
    
    return $this->docks;
}
```

4. **Update ShipsExtractor to pass dock sizes:**

In the code from Package 1, when calling the aggregator:

```php
foreach ($connections as $connection) {
    $tags = explode(' ', $connection->getAttribute('tags'));
    $connectionRef = $connection->getAttribute('ref');
    
    // Get dock size if available
    $dockSize = $dockSizes[$connectionRef] ?? null;
    
    $aggregator->addConnection($tags, $dockSize);
}
```

#### Testing
Create test:
```php
$aggregator = new ShipSlotAggregator();
$aggregator->addConnection(['dockingbay'], 's');
$aggregator->addConnection(['dockingbay'], 's');
$aggregator->addConnection(['dockingbay'], 'm');
$aggregator->addConnection(['dockingbay'], 'xs'); // Should be filtered out

$docks = $aggregator->getDocks();
assert($docks === ['m' => 1, 's' => 2], "Dock aggregation failed");
```

#### Success Criteria
- ✅ Aggregator accepts dock size parameter
- ✅ Docks grouped by size (s, m, l, xl)
- ✅ xs docks filtered out completely
- ✅ Output format is `{"m": 4, "s": 8}` (object, not array)
- ✅ Keys sorted alphabetically

---

### Package 3: Builder Output Format
**Effort:** 30 minutes  
**Dependencies:** Package 2  
**Project:** x4-data-extractor

#### Objective
Update `ShipsBuilder` to use the new docks format in JSON output.

#### Files to Modify
- `src/DataExtractor/Ships/ShipsBuilder.php`

#### Implementation Details

1. **Update buildShipData method:**

Find where dock data is written to the ship array. Replace:

```php
// OLD
$shipData['docks'] = $aggregator->getDocks(); // Returns old array format

// NEW
$docks = $aggregator->getDocks();
if (!empty($docks)) {
    $shipData['docks'] = $docks; // Already in correct object format
} else {
    $shipData['docks'] = new \stdClass(); // Empty object in JSON
}
```

2. **Ensure JSON encoding uses objects:**

If there's JSON encoding configuration, ensure it preserves objects:

```php
// When writing to ships.json
$json = json_encode($ships, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
```

Note: PHP associative arrays with string keys automatically encode as JSON objects.

#### Testing
Run extraction and verify JSON output:
```json
{
    "docks": {
        "m": 4,
        "s": 8
    }
}
```

NOT:
```json
{
    "docks": [
        {"size": "s", "count": 8}
    ]
}
```

#### Success Criteria
- ✅ ships.json contains docks as objects, not arrays
- ✅ No empty "size" fields
- ✅ No xs docks in output
- ✅ JSON is valid and parseable

---

### Package 4: X4-Core Ship Class Updates
**Effort:** 1 hour  
**Dependencies:** Package 3 (ships.json must be regenerated)  
**Project:** x4-core

#### Objective
Update `Ship` class to work with new docks format.

#### Files to Modify
- `src/X4/Database/Ships/Ship.php`
- Any other classes that access dock data (search codebase)

#### Implementation Details

1. **Update getDocks method:**

Find and update:
```php
// OLD
public function getDocks(): array
{
    return $this->data['docks'] ?? [];
}

// NEW
public function getDocks(): array
{
    $docks = $this->data['docks'] ?? [];
    
    // Return as object/associative array
    return is_array($docks) ? $docks : [];
}
```

2. **Add convenience methods:**

```php
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
```

3. **Search for existing dock usage:**

```bash
cd x4-core
grep -r "getDocks()" --include="*.php" src/
grep -r "->data\['docks'\]" --include="*.php" src/
```

Update any code that expects the old array format.

#### Testing
Create PHPUnit test:
```php
public function testDockStructure(): void
{
    $ship = ShipDefs::getInstance()
        ->getShipByMacroName('ship_arg_xl_carrier_01_a');
    
    $docks = $ship->getDocks();
    
    // Should be associative array
    $this->assertIsArray($docks);
    $this->assertArrayHasKey('s', $docks);
    $this->assertArrayHasKey('m', $docks);
    
    // Should match game data
    $this->assertEquals(8, $ship->getDockCount('s'));
    $this->assertEquals(4, $ship->getDockCount('m'));
    $this->assertEquals(12, $ship->getTotalDockCount());
    
    // Should not have xs docks
    $this->assertArrayNotHasKey('xs', $docks);
}
```

#### Success Criteria
- ✅ Ship class returns docks in new format
- ✅ No breaking changes to existing code
- ✅ All tests pass
- ✅ Convenience methods work correctly

---

### Package 5: UI/Display Updates (Optional)
**Effort:** 1-2 hours  
**Dependencies:** Package 4  
**Project:** x4-core

#### Objective
Update any UI components that display dock information.

#### Files to Search
```bash
cd x4-core
grep -r "docks" --include="*.php" src/X4/UI/
grep -r "docks" --include="*.php" tests/
```

#### Implementation Details

1. **Update ship detail pages:**

If there are pages displaying ship information, update to show size breakdown:

```php
// OLD
echo "Docks: " . count($ship->getDocks());

// NEW
$docks = $ship->getDocks();
if (!empty($docks)) {
    echo "Docks: ";
    $parts = [];
    foreach ($docks as $size => $count) {
        $parts[] = "$count × $size";
    }
    echo implode(', ', $parts); // Output: "8 × s, 4 × m"
}
```

2. **Update DataGrid columns:**

If dock data appears in any DataGrid, update column rendering:

```php
$grid->addColumn('docks', 'Docks')
    ->setDataCallback(function(Ship $ship) {
        $docks = $ship->getDocks();
        if (empty($docks)) {
            return '-';
        }
        
        $parts = [];
        foreach ($docks as $size => $count) {
            $parts[] = "$count×" . strtoupper($size);
        }
        return implode(', ', $parts);
    });
```

#### Success Criteria
- ✅ UI shows dock size breakdown
- ✅ Format is human-readable
- ✅ No PHP notices/warnings
- ✅ Visual layout not broken

---

## 🔧 Extraction Command Reference

### Location
- **Project:** x4-data-extractor
- **Config:** `dev-config.php` (copy from `dev-config.dist.php` if missing)

### Run Extraction
```bash
cd x4-data-extractor
composer extract-ships
```

Or extract everything:
```bash
composer extract-all
```

### Copy Output to x4-core
After extraction, copy the generated files:
```bash
# From x4-data-extractor root
cp output/ships.json ../x4-core/data/ships.json
```

Or if there's an automated script:
```bash
cd x4-core
composer update-data
```

---

## ✅ Verification Checklist

### After Package 1 & 2
- [ ] Extractor loads dock macro XML files
- [ ] Dock sizes extracted correctly (s, m, l, xl)
- [ ] xs docks filtered out
- [ ] No crashes on missing macros

### After Package 3
- [ ] Run extraction: `composer extract-ships`
- [ ] Open `x4-data-extractor/output/ships.json`
- [ ] Find Colossus Vanguard: `ship_arg_xl_carrier_01_a`
- [ ] Verify docks structure: `{"m": 4, "s": 8}` (approximately, exact numbers may vary)
- [ ] Verify NO xs docks present
- [ ] Verify NO empty "size" fields
- [ ] Check other carriers for correct dock counts

### After Package 4
- [ ] Copy ships.json to x4-core/data/
- [ ] Run: `cd x4-core && composer test`
- [ ] All tests pass
- [ ] No PHP warnings/notices

### After Package 5
- [ ] Browse to any ship detail pages
- [ ] Verify dock information displays correctly
- [ ] Visual layout intact

---

## 📚 Reference Information

### File Locations

#### x4-data-extractor
- **Extractor:** `src/DataExtractor/Ships/ShipsExtractor.php`
- **Aggregator:** `src/DataExtractor/Ships/ShipSlotAggregator.php`
- **Builder:** `src/DataExtractor/Ships/ShipsBuilder.php`
- **Output:** `output/ships.json`

#### x4-core
- **Ship Class:** `src/X4/Database/Ships/Ship.php`
- **Data File:** `data/ships.json`
- **Tests:** `tests/X4Tests/Suites/Database/ShipTest.php` (if exists)

### Example Ships to Test

| Ship | Macro | Expected Docks |
|------|-------|----------------|
| Colossus Vanguard | ship_arg_xl_carrier_01_a | s: 8, m: 4 |
| Asgard | ship_tel_xl_carrier_01_a | Varies |
| Raptor | ship_par_xl_carrier_01_a | Varies |
| Drake | ship_spl_xl_carrier_01_a | Varies |

### Related Manifest Documents
- [tech-stack.md](../project-manifest/tech-stack.md) - Extraction-Builder Pattern
- [data-flows.md](../project-manifest/data-flows.md) - Database Build Flow
- [constraints.md](../project-manifest/constraints.md) - JSON Data Files

---

## 🚨 Known Gotchas

### 1. Macro Not Found
Some ships may reference mod-specific or DLC dock macros. Handle gracefully:
```php
if ($macroDef === null) {
    error_log("Warning: Macro not found: $macroName");
    return null; // Don't crash, just skip
}
```

### 2. JSON Object vs Array
PHP encodes:
- `['s' => 8, 'm' => 4]` → `{"s": 8, "m": 4}` ✅
- `[['size' => 's']]` → `[{"size": "s"}]` ❌

Use associative arrays with string keys for object output.

### 3. Empty Docks
Ships with no docks should have:
```json
"docks": {}
```
NOT:
```json
"docks": []
```

Use `new \stdClass()` in PHP before JSON encoding.

### 4. Backward Compatibility
If other tools depend on old dock format, consider:
- Adding a migration period
- Supporting both formats temporarily
- Updating dependent tools first

---

## 📝 Update Manifest After Completion

When all packages are complete, update these manifest documents:

### [data-flows.md](../project-manifest/data-flows.md)
Add section: "Database Build Flow - Dock Size Extraction"

### [public-api.md](../project-manifest/public-api.md)
Update:
- `Ship::getDocks()` return type
- Add new convenience methods

### [tech-stack.md](../project-manifest/tech-stack.md)
Update: "Extraction-Builder Pattern" with dock size example

---

## 🎯 Success Metrics

### Functional
- ✅ Colossus Vanguard shows 8 small + 4 medium docks
- ✅ No xs docks in any ship
- ✅ All carriers have size-differentiated docks
- ✅ No crashes or errors during extraction

### Technical
- ✅ All PHPUnit tests pass
- ✅ No PHP warnings/notices
- ✅ PHPStan analysis passes
- ✅ ships.json is valid JSON

### Documentation
- ✅ Manifest documents updated
- ✅ Code comments added
- ✅ This plan marked as complete

---

**Next Steps:** Start with Package 1 - Macro Loader Infrastructure
