# Work Package 5: Developer Support

**Status:** 🔵 Not Started  
**Estimated Lines:** 900-1200  
**Estimated Time:** 4-5 hours  
**Dependencies:** WP1, WP2, WP3, WP4  
**Output File:** `docs/agents/project-manifest/extraction-reference.md` (append)

---

## 🎯 Objective

Create comprehensive developer support documentation:
1. Troubleshooting Guide - Common errors and solutions
2. Extractor Development Guide - Step-by-step for creating new extractors
3. Testing extraction results
4. Performance optimization tips

This work package enables developers to create new extractors and debug issues independently.

---

## 📋 Prerequisites

**Must Be Complete:**
- ✅ WP1 (Foundation & XML Sources)
- ✅ WP2 (Core Extraction Patterns)
- ✅ WP3 (Advanced Extraction Features)
- ✅ WP4 (Equipment Compatibility System)

**Knowledge Required:**
- PHP 8.4+ features
- PHPUnit testing
- Understanding of all previous WPs

**Files to Read Before Starting:**
1. [DatabaseBuilder.php](../../../src/X4/Database/DatabaseBuilder.php) - Build process
2. [BaseExtractor.php](../../../src/X4/Database/BaseExtractor.php) - Extractor base class
3. [ShipDefTests.php](../../../tests/X4Tests/Suites/Database/Ships/ShipDefTests.php) - Example tests
4. All extractor classes for error pattern analysis

---

## 📚 Context

### Why Developer Support Matters

**Current State:**
- Extractors created ad-hoc
- Error handling inconsistent
- No testing guidelines
- Tribal knowledge required

**After This WP:**
- Clear step-by-step process
- Common errors documented with solutions
- Testing patterns established
- New developers productive quickly

---

## 🔍 Source References

### Key Files for Troubleshooting

Analyze these for common error patterns:
- All extractor classes in [src/X4/Database/](../../../src/X4/Database/)
- [DatabaseBuilder.php](../../../src/X4/Database/DatabaseBuilder.php) - Build errors
- Test files in [tests/X4Tests/Suites/Database/](../../../tests/X4Tests/Suites/Database/) - Test patterns

### Key Files for Development Guide

- [BaseExtractor.php](../../../src/X4/Database/BaseExtractor.php) - Base class
- [EnginesExtractor.php](../../../src/X4/Database/Engines/EnginesExtractor.php) - Simple Two-Phase example
- [FactionsExtractor.php](../../../src/X4/Database/Factions/FactionsExtractor.php) - Simple Single-Phase example
- [ShipsExtractor.php](../../../src/X4/Database/Ships/ShipsExtractor.php) - Complex Two-Phase example

---

## 🛠️ Implementation Steps

### Step 1: Document Troubleshooting Guide

Add this section to `extraction-reference.md`:

```markdown
## 🔧 Troubleshooting Guide

### Common Errors and Solutions

#### Error 1: Missing Macro

**Error Message:**
```
X4Exception: Macro 'shield_xyz_macro' not found in MacroIndex
```

**Cause:**
- Ware references non-existent macro
- Typo in macro name
- Macro not in any installed DLC
- MacroIndex not built yet

**Solutions:**

1. **Verify macro exists in game files:**
   ```bash
   # Search all data sources
   Get-ChildItem -Path "F:\...\output\" -Recurse -Filter "shield_xyz_macro.xml"
   ```

2. **Check ware ID to macro name conversion:**
   ```php
   // Ware ID: shield_xyz
   // Expected macro: shield_xyz_macro  (append '_macro')
   ```

3. **Verify MacroIndex built:**
   ```php
   // In DatabaseBuilder::build():
   $this->buildMacroIndex();  // Must be first!
   ```

4. **Check extraction path configuration:**
   ```php
   // In dev-config.php:
   X4Application::setDataExtractionPath('/correct/path/to/output/');
   ```

---

#### Error 2: Invalid XML

**Error Message:**
```
Exception: Failed to parse XML at path/to/macro.xml
DOMException: XML parse error
```

**Cause:**
- Malformed XML file
- Encoding issues (non-UTF-8)
- File corruption
- Incomplete file (extraction interrupted)

**Solutions:**

1. **Validate XML with linter:**
   ```bash
   # Using xmllint (Linux/WSL)
   xmllint --noout path/to/macro.xml
   
   # Using PowerShell
   [xml]$xml = Get-Content path/to/macro.xml
   ```

2. **Check file encoding:**
   ```bash
   file -i path/to/macro.xml  # Should show: charset=utf-8
   ```

3. **Re-extract from game files:**
   ```bash
   # Run x4-data-extractor again
   cd x4-data-extractor
   php extract.php --source vanilla
   ```

4. **Inspect file manually:**
   - Open in text editor
   - Check for truncated content
   - Verify closing tags

---

#### Error 3: Property Not Found

**Error Message:**
```
Exception: Required property 'hull.max' not found
Called requireFirst() but no matching node found
```

**Cause:**
- DOM query failed (wrong tag name)
- Property missing in XML
- Wrong XML structure (nested differently)
- Using `requireFirst()` for optional property

**Solutions:**

1. **Verify XML structure:**
   ```xml
   <!-- Expected: -->
   <hull max="2500" />
   
   <!-- Might actually be: -->
   <properties>
     <hull max="2500" />
   </properties>
   ```

2. **Adjust DOM query:**
   ```php
   // If nested differently:
   $hull = $dom->byTagName('properties')
       ->requireFirst()
       ->byTagName('hull')
       ->requireFirst()
       ->getAttribute('max');
   ```

3. **Use getFirst() for optional properties:**
   ```php
   // Don't throw if missing:
   $boostNode = $dom->byTagName('boost')->getFirst();
   if ($boostNode !== null) {
       $duration = (int)$boostNode->getAttribute('duration');
   }
   ```

4. **Add error context:**
   ```php
   try {
       $hull = $dom->byTagName('hull')->requireFirst();
   } catch (\Exception $e) {
       throw new X4Exception(
           "Hull property not found in macro: {$macroName} at {$filePath}",
           $e
       );
   }
   ```

---

#### Error 4: Build Dependency Violation

**Error Message:**
```
Error: Collection used before built
Call to undefined method on null
```

**Cause:**
- Collection accessed before its extractor ran
- Wrong build order in DatabaseBuilder
- Circular dependency

**Solutions:**

1. **Check build order in DatabaseBuilder:**
   ```php
   // CORRECT order:
   $this->buildMacroIndex();    // 1. Foundation
   $this->buildWares();         // 2. Base data
   $this->buildEngines();       // 3. Uses wares, macro index
   $this->buildShips();         // 4. Uses engines (for compatibility)
   
   // WRONG order:
   $this->buildShips();         // ERROR: Engines not built yet!
   $this->buildEngines();
   ```

2. **Review dependency chain:**
   ```
   MacroIndex → (required by all macro extractors)
   Wares → (required by all equipment extractors)
   Equipment → (required by Ships for compatibility)
   ```

3. **Add explicit checks:**
   ```php
   public function extract(): array
   {
       if (!WareDefs::getInstance()->isBuilt()) {
           throw new X4Exception('Wares collection must be built first');
       }
       // ... extraction logic
   }
   ```

---

#### Error 5: Data Source Not Detected

**Error Message:**
```
Warning: DLC 'ego_dlc_terran' not found
0 macros extracted for ego_dlc_terran
```

**Cause:**
- DLC folder missing
- Folder structure incorrect
- Missing info.json file
- Extraction path wrong

**Solutions:**

1. **Verify folder exists:**
   ```bash
   Test-Path "F:\...\output\ego_dlc_terran"
   ```

2. **Check folder structure:**
   ```
   ego_dlc_terran/
   ├── info.json        ← Must exist!
   ├── index/
   │   └── macros.xml
   └── libraries/
       └── wares.xml
   ```

3. **Verify info.json content:**
   ```json
   {
     "id": "ego_dlc_terran",
     "name": "Cradle of Humanity",
     "description": "Terran expansion"
   }
   ```

4. **Re-run data source extraction:**
   ```bash
   composer build
   # This rebuilds data-sources.json
   ```

---

#### Error 6: Type Conversion Failure

**Error Message:**
```
TypeError: Cannot convert string to float
Invalid value for forward thrust: "N/A"
```

**Cause:**
- Attribute value not numeric
- Missing attribute returns null
- XML has placeholder/invalid value

**Solutions:**

1. **Validate before conversion:**
   ```php
   $forwardStr = $thruster->getAttribute('forward');
   
   if (!is_numeric($forwardStr)) {
       $this->logWarning("Invalid forward value: {$forwardStr} in {$macroName}");
       $forward = 0.0;  // Use default
   } else {
       $forward = (float)$forwardStr;
   }
   ```

2. **Use null coalescing:**
   ```php
   $forward = (float)($thruster->getAttribute('forward') ?? 0);
   ```

3. **Add validation to extraction:**
   ```php
   private function extractForward(DOMExtended $thruster): float
   {
       $value = $thruster->getAttribute('forward');
       
       if ($value === null || !is_numeric($value)) {
           throw new X4Exception("Invalid or missing 'forward' attribute");
       }
       
       return (float)$value;
   }
   ```

---

### Debugging Techniques

#### 1. Enable Verbose Logging

```php
// In DatabaseBuilder
$this->setVerbose(true);

// In extractors
$this->log("Processing ware: {$wareID}");
$this->logWarning("Skipping invalid item: {$reason}");
```

#### 2. Inspect Intermediate JSON

After extraction, check JSON files:
```bash
# View extracted data
Get-Content data/engines.json | ConvertFrom-Json | Format-List

# Count items
(Get-Content data/engines.json | ConvertFrom-Json).Count
```

#### 3. Use var_dump() for DOM Inspection

```php
// Dump DOM structure
$engine = $dom->byTagName('engine')->getFirst();
var_dump($engine->getAttributes());  // See all attributes

// Dump entire node
var_dump($dom->saveXML($engine->getDOMElement()));
```

#### 4. Test Extraction in Isolation

```php
// Test single item extraction
$extractor = new EngineMacroExtractor($ware);
$result = $extractor->extract();
var_dump($result);  // Inspect output structure
```

#### 5. Validate Against Game Version

```bash
# Check game version
cat output/game-version.txt

# Different game versions may have different XML structures
# Ensure x4-data-extractor matches your game version
```

---

### Performance Debugging

#### Slow Extraction

**Symptoms:**
- Build takes minutes instead of seconds
- Specific extractor very slow

**Diagnosis:**

```php
// Add timing
$start = microtime(true);
$result = $extractor->extract();
$duration = microtime(true) - $start;
echo "Extraction took: {$duration}s\n";
```

**Common Causes:**

1. **Too many DOM loads:**
   ```php
   // BAD: Loads DOM multiple times
   foreach ($items as $item) {
       $dom = XMLHelper::createDOMExtended($path);  // Slow!
       // ... extract
   }
   
   // GOOD: Load once, reuse
   $dom = XMLHelper::createDOMExtended($path);
   foreach ($items as $item) {
       // ... extract from cached DOM
   }
   ```

2. **Inefficient filtering:**
   ```php
   // BAD: Multiple passes
   $items = WareDefs::getInstance()->getAll();
   $engines = array_filter($items, fn($w) => $w->getGroup() === 'engine');
   $large = array_filter($engines, fn($w) => $w->getSize() === 'l');
   
   // GOOD: Single pass
   $items = WareDefs::getInstance()->getAll();
   $large = array_filter($items, fn($w) => 
       $w->getGroup() === 'engine' && $w->getSize() === 'l'
   );
   ```

3. **Unnecessary work:**
   ```php
   // BAD: Extract properties not used
   'boost' => $this->extractBoost($dom),  // Not needed for this use case
   
   // GOOD: Only extract what's needed
   // Omit unused properties
   ```
```

### Step 2: Create Extractor Development Guide

Add this section:

```markdown
## 📐 Extractor Development Guide

### Step-by-Step: Creating a New Extractor

This guide walks through creating a complete extractor from scratch using a hypothetical "Turrets" extractor as an example.

---

### Phase 1: Planning

#### Step 1.1: Determine Pattern

**Questions:**
1. Does the data require macro XML files?
2. Is it derived from wares collection?
3. Does it have complex properties?

**Decision Matrix:**

| Criteria | Answer | Pattern |
|----------|--------|---------|
| Requires macro XML | ✅ Yes | Two-Phase |
| Derived from wares | ✅ Yes | Two-Phase |
| Complex properties | ✅ Yes | Two-Phase |
| **OTHERWISE** | ❌ No | Single-Phase |

**For Turrets:** ✅ Two-Phase (requires macro XML for turret stats)

#### Step 1.2: Identify Dependencies

**Questions:**
1. What collections does this extractor need?
2. Where does it fit in build order?

**For Turrets:**
- Requires: `MacroIndex`, `Wares`
- Build after: Wares
- Build before: Ships (if ships reference turrets)

#### Step 1.3: Define Output Structure

**Create JSON schema:**
```json
{
  "id": "turret_arg_m_beam_01_mk1",
  "name": "Beam Turret",
  "size": "m",
  "rotationSpeed": 45.0,
  "damage": 500.0,
  "range": 3000.0,
  "dataSource": "vanilla"
}
```

---

### Phase 2: Implementation

#### Step 2.1: Create Main Extractor Class

**Location:** `src/X4/Database/Turrets/TurretsExtractor.php`

```php
<?php

namespace X4\Database\Turrets;

use X4\Database\BaseExtractor;
use X4\Database\Wares\WareDefs;

class TurretsExtractor extends BaseExtractor
{
    public const OUTPUT_FILE = 'turrets.json';
    
    public function extract(): array
    {
        $result = [];
        
        // Filter wares for turrets
        $turrets = WareDefs::getInstance()
            ->findWares()
            ->selectGroup('turret')
            ->getAll();
        
        // Extract each turret's properties
        foreach ($turrets as $ware) {
            try {
                $macroExtractor = new TurretMacroExtractor($ware);
                $result[] = $macroExtractor->extract();
            } catch (\Exception $e) {
                $this->logWarning("Failed to extract turret {$ware->getID()}: {$e->getMessage()}");
                continue;  // Skip invalid items
            }
        }
        
        return $result;
    }
}
```

**Key Points:**
- Extend `BaseExtractor`
- Define `OUTPUT_FILE` constant
- Implement `extract()` method
- Handle errors per-item (don't stop entire extraction)

#### Step 2.2: Create Macro Extractor Class (Two-Phase Only)

**Location:** `src/X4/Database/Turrets/TurretMacroExtractor.php`

```php
<?php

namespace X4\Database\Turrets;

use X4\Database\Wares\WareDef;
use X4\Database\MacroIndex\MacroIndex;
use Mistralys\X4\XMLHelper;
use X4\X4Exception;

class TurretMacroExtractor
{
    private WareDef $ware;
    
    public function __construct(WareDef $ware)
    {
        $this->ware = $ware;
    }
    
    public function extract(): array
    {
        // Step 1: Resolve macro
        $macroName = $this->ware->getID() . '_macro';
        $macro = MacroIndex::getInstance()->getByID($macroName);
        
        // Step 2: Load DOM
        $dom = XMLHelper::createDOMExtended($macro->getFilePath());
        
        // Step 3: Extract properties
        return [
            'id' => $this->ware->getID(),
            'name' => $this->ware->getName(),
            'size' => $this->extractSize(),
            'rotationSpeed' => $this->extractRotationSpeed($dom),
            'damage' => $this->extractDamage($dom),
            'range' => $this->extractRange($dom),
            'dataSource' => $macro->getDataSource()
        ];
    }
    
    private function extractSize(): string
    {
        // Parse from ware ID: turret_{race}_{SIZE}_...
        $parts = explode('_', $this->ware->getID());
        return $parts[2] ?? 'unknown';
    }
    
    private function extractRotationSpeed(DOMExtended $dom): float
    {
        $rotation = $dom->byTagName('rotation')->getFirst();
        
        if ($rotation === null) {
            return 0.0;  // Default for fixed turrets
        }
        
        return (float)$rotation->getAttribute('speed');
    }
    
    private function extractDamage(DOMExtended $dom): float
    {
        return (float)$dom->byTagName('bullet')
            ->requireFirst()
            ->getAttribute('damage');
    }
    
    private function extractRange(DOMExtended $dom): float
    {
        return (float)$dom->byTagName('weapon')
            ->requireFirst()
            ->getAttribute('range');
    }
}
```

**Key Points:**
- Constructor takes `WareDef`
- Resolve macro via MacroIndex
- Load DOM from macro file path
- Extract properties into array
- Use private methods for clarity
- Handle optional vs required properties

#### Step 2.3: Create Collection Classes

**TurretDefs (Singleton Collection):**  
Location: `src/X4/Database/Turrets/TurretDefs.php`

```php
<?php

namespace X4\Database\Turrets;

use X4\Database\Core\BaseCollection;

class TurretDefs extends BaseCollection
{
    protected static ?self $instance = null;
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    protected function getJSONFile(): string
    {
        return 'turrets.json';
    }
    
    protected function createItem(array $data): TurretDef
    {
        return new TurretDef($data);
    }
    
    public function findTurrets(): TurretFinder
    {
        return new TurretFinder($this);
    }
}
```

**TurretDef (Item):**  
Location: `src/X4/Database/Turrets/TurretDef.php`

```php
<?php

namespace X4\Database\Turrets;

use X4\Database\Core\BaseItem;

class TurretDef extends BaseItem
{
    public function getRotationSpeed(): float
    {
        return (float)$this->data['rotationSpeed'];
    }
    
    public function getDamage(): float
    {
        return (float)$this->data['damage'];
    }
    
    public function getRange(): float
    {
        return (float)$this->data['range'];
    }
    
    public function getSize(): string
    {
        return (string)$this->data['size'];
    }
}
```

**TurretFinder (Optional):**  
Location: `src/X4/Database/Turrets/TurretFinder.php`

```php
<?php

namespace X4\Database\Turrets;

use X4\Database\Core\BaseFinder;

class TurretFinder extends BaseFinder
{
    public function selectSize(string $size): self
    {
        return $this->addFilter(fn(TurretDef $t) => $t->getSize() === $size);
    }
    
    public function selectMinDamage(float $minDamage): self
    {
        return $this->addFilter(fn(TurretDef $t) => $t->getDamage() >= $minDamage);
    }
    
    public function selectMinRange(float $minRange): self
    {
        return $this->addFilter(fn(TurretDef $t) => $t->getRange() >= $minRange);
    }
}
```

#### Step 2.4: Add to DatabaseBuilder

**Location:** `src/X4/Database/DatabaseBuilder.php`

```php
use X4\Database\Turrets\TurretsExtractor;

class DatabaseBuilder
{
    public function build(): void
    {
        // ... existing extractors
        
        $this->buildWares();
        $this->buildEngines();
        $this->buildShields();
        $this->buildWeapons();
        
        // Add turrets here (after wares, before ships if needed)
        $this->buildTurrets();
        
        $this->buildShips();
    }
    
    private function buildTurrets(): void
    {
        $this->log('Building turrets...');
        $extractor = new TurretsExtractor($this->app);
        $this->runExtractor($extractor);
    }
}
```

**Verify Build Order:**
```
MacroIndex → Wares → Turrets → Ships
```

#### Step 2.5: Create JSON Data File

**Location:** `data/turrets.json`

**Initial Content:**
```json
[]
```

**After First Build:**
```json
[
  {
    "id": "turret_arg_m_beam_01_mk1",
    "name": "Beam Turret",
    "size": "m",
    "rotationSpeed": 45.0,
    "damage": 500.0,
    "range": 3000.0,
    "dataSource": "vanilla"
  }
]
```

---

### Phase 3: Testing

#### Step 3.1: Create Test Class

**Location:** `tests/X4Tests/Suites/Database/Turrets/TurretDefTests.php`

```php
<?php

namespace X4Tests\Suites\Database\Turrets;

use X4\Database\Turrets\TurretDefs;
use X4Tests\BaseTestCase;

class TurretDefTests extends BaseTestCase
{
    public function testCollectionLoaded(): void
    {
        $turrets = TurretDefs::getInstance();
        
        $this->assertGreaterThan(0, $turrets->countItems(), 
            'Turrets collection should not be empty');
    }
    
    public function testSpecificTurret(): void
    {
        $turret = TurretDefs::getInstance()
            ->getByID('turret_arg_m_beam_01_mk1');
        
        $this->assertNotNull($turret, 'Turret should exist');
        $this->assertEquals('m', $turret->getSize());
        $this->assertGreaterThan(0, $turret->getDamage());
        $this->assertGreaterThan(0, $turret->getRange());
    }
    
    public function testFinderSize(): void
    {
        $mediumTurrets = TurretDefs::getInstance()
            ->findTurrets()
            ->selectSize('m')
            ->getAll();
        
        $this->assertNotEmpty($mediumTurrets);
        
        foreach ($mediumTurrets as $turret) {
            $this->assertEquals('m', $turret->getSize());
        }
    }
    
    public function testFinderDamage(): void
    {
        $highDamage = TurretDefs::getInstance()
            ->findTurrets()
            ->selectMinDamage(400.0)
            ->getAll();
        
        foreach ($highDamage as $turret) {
            $this->assertGreaterThanOrEqual(400.0, $turret->getDamage());
        }
    }
}
```

**Run Tests:**
```bash
vendor/bin/phpunit tests/X4Tests/Suites/Database/Turrets/TurretDefTests.php
```

#### Step 3.2: Validate JSON Structure

```php
public function testJSONStructure(): void
{
    $json = file_get_contents(__DIR__ . '/../../../../../data/turrets.json');
    $data = json_decode($json, true);
    
    $this->assertIsArray($data);
    $this->assertArrayHasKey('id', $data[0]);
    $this->assertArrayHasKey('name', $data[0]);
    $this->assertArrayHasKey('size', $data[0]);
    $this->assertArrayHasKey('rotationSpeed', $data[0]);
    $this->assertArrayHasKey('damage', $data[0]);
    $this->assertArrayHasKey('range', $data[0]);
    $this->assertArrayHasKey('dataSource', $data[0]);
}
```

---

### Phase 4: Documentation

#### Step 4.1: Update Manifest Documents

**Files to Update:**

1. **tech-stack.md** (if new pattern):
   - Add to Collection-Item Pattern examples
   - Document any new patterns discovered

2. **public-api.md**:
   ```markdown
   ## Database\Turrets Namespace
   
   ### TurretDefs
   
   Singleton collection of all turrets.
   
   **Methods:**
   - `getInstance(): TurretDefs` - Get singleton instance
   - `getByID(string $id): ?TurretDef` - Find turret by ID
   - `getAll(): TurretDef[]` - Get all turrets
   - `findTurrets(): TurretFinder` - Get finder for filtering
   
   ### TurretDef
   
   Represents a single turret.
   
   **Properties:**
   - `getID(): string` - Turret ID
   - `getName(): string` - Localized name
   - `getSize(): string` - Size (s, m, l)
   - `getRotationSpeed(): float` - Degrees per second
   - `getDamage(): float` - Damage per shot
   - `getRange(): float` - Maximum range
   - `getDataSource(): string` - DLC providing this turret
   
   ### TurretFinder
   
   Fluent finder for filtering turrets.
   
   **Methods:**
   - `selectSize(string $size): self` - Filter by size
   - `selectMinDamage(float $min): self` - Minimum damage
   - `selectMinRange(float $min): self` - Minimum range
   - `getAll(): TurretDef[]` - Get filtered results
   ```

3. **file-tree.md**:
   ```markdown
   src/X4/Database/Turrets/
   ├── TurretDef.php
   ├── TurretDefs.php
   ├── TurretFinder.php
   ├── TurretsExtractor.php
   └── TurretMacroExtractor.php
   ```

4. **data-flows.md** (if new flow):
   - Add turret extraction flow diagram if unique

5. **extraction-reference.md**:
   - Add to Extractor Inventory table
   - Add to XML Schema Quick Reference table
   - Add example to Extractor Patterns section

#### Step 4.2: Update This Document

Add turrets example to relevant sections of extraction-reference.md.

---

### Phase 5: Verification

#### Final Checklist

- [ ] Extractor classes created and follow patterns
- [ ] Collection classes created (Defs, Def, Finder)
- [ ] Added to DatabaseBuilder with correct dependency order
- [ ] JSON file created and populated
- [ ] Tests created and passing
- [ ] All manifest documents updated
- [ ] No PHPStan errors: `vendor/bin/phpstan analyze`
- [ ] Code style correct: `vendor/bin/phpcs`
- [ ] Build succeeds: `composer build`

#### Integration Test

```bash
# Full build
composer build

# Verify turrets.json created
Test-Path data/turrets.json

# Check item count
(Get-Content data/turrets.json | ConvertFrom-Json).Count

# Run all tests
vendor/bin/phpunit
```

---

### Common Pitfalls

1. **Wrong Build Order**
   - Symptom: "Collection not built" errors
   - Fix: Review dependency chain, adjust DatabaseBuilder order

2. **Missing Composer Autoload**
   - Symptom: "Class not found" errors
   - Fix: Run `composer dump-autoload`

3. **Wrong Namespace**
   - Symptom: PSR-4 autoload errors
   - Fix: Ensure namespace matches folder structure

4. **Forgot to Add to DatabaseBuilder**
   - Symptom: JSON file not created
   - Fix: Add `build*()` method and call in `build()`

5. **JSON File Not Gitignored**
   - Check: `.gitignore` should include `data/*.json`
   - Reason: JSON files are build artifacts, not source

6. **Manifest Docs Not Updated**
   - Impact: Future developers confused
   - Fix: Update all 5 manifest files

---

### Performance Checklist

- [ ] Minimize DOM loads (cache if reused)
- [ ] Use single-pass filtering where possible
- [ ] Only extract properties actually needed
- [ ] Handle errors per-item (don't stop entire extraction)
- [ ] Log warnings, don't throw for recoverable errors

---

### Testing Checklist

- [ ] Collection loads and has items
- [ ] Specific items can be retrieved
- [ ] All properties accessible
- [ ] Finder filters work correctly
- [ ] JSON structure matches schema
- [ ] Data source tracking works
- [ ] Size extraction correct
- [ ] Variant ID correct (if applicable)

---

**Estimated Time:** 2-4 hours for simple extractor, 4-8 hours for complex extractor

**Success Criteria:** New extractor works identically to existing extractors, follows all patterns, fully documented.
```

---

## ✅ Verification Steps

After completing this work package:

1. **Content Added:** Troubleshooting Guide section complete (300-400 lines)
2. **Content Added:** Extractor Development Guide section complete (600-800 lines)
3. **All Common Errors Documented:** At least 6 error scenarios with solutions
4. **Complete Example:** Full turret extractor walkthrough
5. **Checklists Included:** Verification, performance, and testing checklists
6. **Actionable:** Developer can create new extractor by following guide

### Specific Checks

```bash
# Verify sections added
Select-String "## 🔧 Troubleshooting Guide" docs/agents/project-manifest/extraction-reference.md
Select-String "## 📐 Extractor Development Guide" docs/agents/project-manifest/extraction-reference.md

# Check error coverage
Select-String "Error [0-9]:" docs/agents/project-manifest/extraction-reference.md

# Verify complete example
Select-String "TurretsExtractor" docs/agents/project-manifest/extraction-reference.md
```

---

## 📤 Deliverables

1. **Sections Added to extraction-reference.md:**
   - Troubleshooting Guide (300-400 lines)
   - Extractor Development Guide (600-800 lines)

2. **Content Includes:**
   - 6+ common errors with solutions
   - Debugging techniques
   - Performance debugging
   - Complete step-by-step extractor creation
   - Testing guidelines
   - Documentation update checklist
   - Common pitfalls
   - Success criteria

---

## 🔄 Next Steps

After WP5 completion:

1. **Update README:** Mark WP5 as complete
2. **Proceed to WP6:** Integration & Finalization (final step)

**Dependencies Met:**
- WP6 can now start (requires WP1-5) ✅

---

## 📝 Notes

- Troubleshooting section critical for maintenance
- Development guide must be actionable (follow steps → working extractor)
- Real examples better than theory
- Checklists ensure nothing forgotten
- Link to actual error messages from codebase

---

**Work Package Status:** ✅ Complete  
**Created:** February 9, 2026  
**Last Updated:** February 9, 2026
