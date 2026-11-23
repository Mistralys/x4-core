# Work Package 2: Core Extraction Patterns

**Status:** � Complete  
**Completed Lines:** 506  
**Estimated Time:** 3-4 hours  
**Dependencies:** WP1 (Foundation)  
**Output File:** `docs/agents/project-manifest/extraction-reference.md` (append)

---

## 🎯 Objective

Document the core technical patterns used in data extraction:
1. Macro Resolution System - How macro names map to files and properties are resolved
2. Two-Phase Extractor Pattern - Main extractor + macro extractor architecture
3. Single-Phase Extractor Pattern - Simple extractors without macro resolution
4. DOM Query Patterns - Common XPath and DOMExtended usage
5. Error Handling Patterns - How extractors handle missing data

This work package captures the fundamental extraction architecture that all extractors use.

---

## 📋 Prerequisites

**Must Be Complete:**
- ✅ WP1 (Foundation & XML Sources)

**Knowledge Required:**
- PHP DOM manipulation
- XPath query syntax
- Collection-Item pattern from tech-stack.md

**Files to Read Before Starting:**
1. [tech-stack.md](../../project-manifest/tech-stack.md) - Understand patterns
2. [ShipsExtractor.php](../../../src/X4/Database/Ships/ShipsExtractor.php) - Example Two-Phase
3. [FactionsExtractor.php](../../../src/X4/Database/Factions/FactionsExtractor.php) - Example Single-Phase
4. [MacroFileDef.php](../../../src/X4/Database/MacroIndex/MacroFileDef.php) - Macro resolution
5. [DOMExtended.php](../../../vendor/mistralys/application-utils/src/XMLHelper/DOMExtended.php) - DOM wrapper

---

## 📚 Context

### Why Patterns Matter

Without documented patterns:
- New extractors reinvent solutions
- Inconsistent error handling
- Harder to maintain
- No clear mental model

With patterns:
- Consistent architecture
- Predictable behavior
- Faster development
- Easier debugging

### Core Concepts

**Macro Resolution:**
- Ships reference equipment by macro name
- Macro name must be resolved to physical file path
- Properties extracted from macro XML
- Parent macros provide fallback values

**Two-Phase Extraction:**
```
WaresExtractor filters wares.xml for group
  → For each ware, create MacroExtractor
  → MacroExtractor loads macro XML, extracts properties
  → Returns structured data array
```

**Single-Phase Extraction:**
```
Read XML file (libraries/wares.xml, libraries/characters.xml)
  → Parse DOM
  → Extract properties directly
  → Return structured data array
```

---

## 🔍 Source References

### Key Files for Macro Resolution

| File | Purpose | Key Methods |
|------|---------|-------------|
| [MacroIndexExtractor.php](../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php) | Builds macro name → path index | `extract()` |
| [MacroFileDef.php](../../../src/X4/Database/MacroIndex/MacroFileDef.php) | Represents single macro entry | `getMacroName()`, `getFilePath()`, `getCompositeID()` |
| [ShipsExtractor.php](../../../src/X4/Database/Ships/ShipsExtractor.php#L280-L320) | Example: loads child + parent macros | `extractStats()` |
| [ShipMacroExtractor.php](../../../src/X4/Database/Ships/ShipMacroExtractor.php) | Example: extracts ship properties | `extract()` |

### Key Files for Two-Phase Pattern

| Extractor Pair | Description |
|----------------|-------------|
| [EnginesExtractor](../../../src/X4/Database/Engines/EnginesExtractor.php) + [EngineMacroExtractor](../../../src/X4/Database/Engines/EngineMacroExtractor.php) | Filters engines from wares, extracts thrust stats |
| [ShieldsExtractor](../../../src/X4/Database/Shields/ShieldsExtractor.php) + [ShieldMacroExtractor](../../../src/X4/Database/Shields/ShieldMacroExtractor.php) | Filters shields from wares, extracts capacity/rate stats |
| [WeaponsExtractor](../../../src/X4/Database/Weapons/WeaponsExtractor.php) + [WeaponMacroExtractor](../../../src/X4/Database/Weapons/WeaponMacroExtractor.php) | Filters weapons from wares, extracts damage/range stats |
| [ShipsExtractor](../../../src/X4/Database/Ships/ShipsExtractor.php) + [ShipMacroExtractor](../../../src/X4/Database/Ships/ShipMacroExtractor.php) | Filters ships from wares, extracts hull/equipment slots |

### Key Files for Single-Phase Pattern

| Extractor | Source XML | Complexity |
|-----------|------------|------------|
| [FactionsExtractor](../../../src/X4/Database/Factions/FactionsExtractor.php) | `libraries/characters.xml` | Simple: direct property mapping |
| [DataSourcesExtractor](../../../src/X4/Database/DataSources/DataSourcesExtractor.php) | Filesystem scan | Simple: folder detection |
| [TranslationsExtractor](../../../src/X4/Database/Translations/TranslationsExtractor.php) | `t/0001-L*.xml` | Medium: recursive page traversal |

### DOM Helper Classes

| Class | Purpose | Key Methods |
|-------|---------|-------------|
| [DOMExtended](../../../vendor/mistralys/application-utils/src/XMLHelper/DOMExtended.php) | Fluent DOM wrapper | `byTagName()`, `requireFirst()`, `getFirst()`, `getAll()`, `getAttribute()` |
| [XMLHelper](../../../vendor/mistralys/application-utils/src/XMLHelper.php) | DOM factory | `createDOMExtended()` |

---

## 🛠️ Implementation Steps

### Step 1: Document Macro Resolution System

Add this section to `extraction-reference.md` after the "## 📊 Overview" placeholder:

```markdown
## 🔍 Macro Resolution System

### Overview

The macro resolution system is the foundation of all equipment and ship extraction. It solves the problem: **"Given a ware ID, how do I find and load its detailed properties from XML?"**

### The Problem

```
Wares.xml contains: <ware id="engine_arg_l_allround_01_mk1" name="Engine XL" />
But where are the thrust stats? Forward speed? Boost duration?
```

**Answer:** In a separate macro XML file referenced by the ware's macro name.

### The Solution: Three-Step Resolution

```
1. Ware ID → Macro Name
   "engine_arg_l_allround_01_mk1" → "engine_arg_l_allround_01_mk1_macro"
   
2. Macro Name → File Path (via MacroIndex)
   "engine_arg_l_allround_01_mk1_macro" → "vanilla::assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml"
   
3. File Path → DOM → Properties
   Load XML, query with XPath, extract attributes
```

### MacroIndex Role

**Source:** [MacroIndexExtractor](../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php)  
**Output:** [macro-index.json](../../../data/macro-index.json)  
**Runtime:** [MacroIndex](../../../src/X4/Database/MacroIndex/MacroIndex.php) collection

**Purpose:** Provides fast O(1) lookup from macro name to file path across all data sources.

**Structure:**
```json
{
  "engine_arg_l_allround_01_mk1_macro": {
    "macroName": "engine_arg_l_allround_01_mk1_macro",
    "class": "engine",
    "filePath": "/full/path/to/vanilla/assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml",
    "dataSource": "vanilla",
    "compositeID": "vanilla::engine_arg_l_allround_01_mk1_macro"
  }
}
```

**API:**
```php
use X4\Database\MacroIndex\MacroIndex;

$macroIndex = MacroIndex::getInstance();
$macro = $macroIndex->getByID('engine_arg_l_allround_01_mk1_macro');

$filePath = $macro->getFilePath();      // Full absolute path
$dataSource = $macro->getDataSource();  // 'vanilla', 'ego_dlc_terran', etc.
$class = $macro->getClass();            // 'engine', 'shield', 'ship', etc.
```

### Macro Inheritance Chains

**Concept:** Ships (and some equipment) use macro inheritance to avoid duplication.

**Example:**
```
ship_arg_s_fighter_01_a_macro     ← Child macro (variant A)
    ↓ inherits from
ship_arg_s_fighter_01_macro       ← Parent macro (base model)
```

**Why:** Base model defines common properties, variants override specific ones.

**Implementation:** [ShipsExtractor::extractStats()](../../../src/X4/Database/Ships/ShipsExtractor.php#L280-L320)

```php
// Load child macro XML
$childMacro = $macroIndex->getByID('ship_arg_s_fighter_01_a_macro');
$childDOM = XMLHelper::createDOMExtended($childMacro->getFilePath());

// Check for parent reference
$parentAlias = $childDOM->byTagName('macro')
    ->requireFirst()
    ->getAttribute('alias');

if ($parentAlias !== null) {
    // Load parent macro XML
    $parentMacro = $macroIndex->getByID($parentAlias);
    $parentDOM = XMLHelper::createDOMExtended($parentMacro->getFilePath());
    
    // Property resolution: child first, parent as fallback
    $hull = $this->extractHull($childDOM) 
         ?? $this->extractHull($parentDOM);
}
```

**Property Resolution Order:**
1. Try to extract from child macro
2. If property missing, try parent macro
3. If still missing and required, throw exception
4. If optional, use default value

**Real Example:**

Child macro (`ship_arg_s_fighter_01_a_macro.xml`):
```xml
<macro name="ship_arg_s_fighter_01_a_macro" alias="ship_arg_s_fighter_01_macro">
  <component>
    <properties>
      <identification unique="a" />  <!-- Only variant ID differs -->
    </properties>
  </component>
</macro>
```

Parent macro (`ship_arg_s_fighter_01_macro.xml`):
```xml
<macro name="ship_arg_s_fighter_01_macro">
  <component>
    <properties>
      <hull max="2500" />
      <people capacity="1" />
      <storage capacity="350" />
      <!-- All base stats defined here -->
    </properties>
    <connections>
      <!-- Equipment slots defined here -->
    </connections>
  </component>
</macro>
```

**Result:** Child + parent combined, child properties override parent.

### DOM Loading and Property Fallback

**DOM Creation:**
```php
use Mistralys\X4\XMLHelper;

$filePath = '/path/to/macro.xml';
$dom = XMLHelper::createDOMExtended($filePath);
```

**DOMExtended Benefits:**
- Fluent API: `$dom->byTagName('engine')->requireFirst()->getAttribute('forward')`
- Null safety: `getFirst()` returns null instead of throwing
- Type coercion: `getAttribute()` returns string, cast as needed

**Property Extraction Pattern:**

```php
// Required property (throws if missing)
private function extractHull(DOMExtended $dom): int
{
    return (int)$dom->byTagName('hull')
        ->requireFirst()
        ->getAttribute('max');
}

// Optional property (returns null if missing)
private function extractBoost(DOMExtended $dom): ?array
{
    $boostNode = $dom->byTagName('boost')->getFirst();
    
    if ($boostNode === null) {
        return null;  // No boost capability
    }
    
    return [
        'duration' => (int)$boostNode->getAttribute('duration'),
        'thrust' => (float)$boostNode->getAttribute('thrust')
    ];
}

// Property with fallback value
private function extractCrew(DOMExtended $dom): int
{
    $peopleNode = $dom->byTagName('people')->getFirst();
    return $peopleNode ? (int)$peopleNode->getAttribute('capacity') : 0;
}
```

### Error Handling

**Missing Macro:**
```php
try {
    $macro = MacroIndex::getInstance()->getByID('nonexistent_macro');
} catch (X4Exception $e) {
    // Handle: log warning, skip item, or throw
}
```

**Invalid XML:**
```php
try {
    $dom = XMLHelper::createDOMExtended($filePath);
} catch (\Exception $e) {
    throw new X4Exception("Failed to parse XML: {$filePath}", $e);
}
```

**Missing Required Property:**
```php
try {
    $hull = $dom->byTagName('hull')->requireFirst();
} catch (\Exception $e) {
    throw new X4Exception("Required property 'hull' not found in macro", $e);
}
```

### Composite ID Format

**Purpose:** Track which data source provides each macro.

**Format:** `{dataSource}::{macroName}`

**Examples:**
- `vanilla::shield_arg_l_standard_01_mk1_macro`
- `ego_dlc_terran::ship_ter_m_frigate_01_a_macro`
- `ego_dlc_boron::engine_bor_l_allround_01_mk1_macro`

**Usage:**
```php
$macro = MacroIndex::getInstance()->getByID('shield_arg_l_standard_01_mk1_macro');
$compositeID = $macro->getCompositeID();  // "vanilla::shield_arg_l_standard_01_mk1_macro"
$dataSource = $macro->getDataSource();    // "vanilla"
```

**Why:** Allows filtering and tracking item origins, especially when same macro exists in multiple DLCs.
```

### Step 2: Document Extractor Patterns

Add this section after the Macro Resolution section:

```markdown
## ⚙️ Extractor Patterns

### Pattern Decision Matrix

| Criteria | Two-Phase | Single-Phase |
|----------|-----------|--------------|
| **Data requires macro XML** | ✅ Yes | ❌ No |
| **Complex property extraction** | ✅ Yes | ❌ No |
| **Multiple DOM queries needed** | ✅ Yes | ❌ No |
| **Properties in library XML** | ❌ No | ✅ Yes |
| **Simple structure** | ❌ No | ✅ Yes |
| **Performance critical** | ❌ Slower | ✅ Faster |

**Examples:**
- **Two-Phase:** Engines, Shields, Weapons, Ships, Modules
- **Single-Phase:** Factions, DataSources, Translations, Blueprints

---

### Two-Phase Extractor Pattern

**Architecture:**
```
Main Extractor (filters collection)
    ↓
Macro Extractor (per-item detail extraction)
    ↓
Structured data array
```

**When to Use:**
- Equipment that derives from wares but needs macro XML
- Complex items requiring multiple DOM queries
- Items with macro inheritance chains

#### Structure

**Main Extractor Responsibilities:**
1. Filter wares collection by group
2. For each ware, instantiate Macro Extractor
3. Collect and return results
4. Handle errors and logging

**Macro Extractor Responsibilities:**
1. Resolve macro name from ware ID
2. Load macro XML from MacroIndex
3. Extract properties via DOM queries
4. Return structured array for single item

#### Example: Engines (Complete Implementation)

**Main Extractor:** [EnginesExtractor.php](../../../src/X4/Database/Engines/EnginesExtractor.php)

```php
namespace X4\Database\Engines;

use X4\Database\BaseExtractor;
use X4\Database\Wares\WareDefs;

class EnginesExtractor extends BaseExtractor
{
    public const OUTPUT_FILE = 'engines.json';
    
    public function extract(): array
    {
        $result = [];
        
        // Step 1: Filter wares for engines
        $engines = WareDefs::getInstance()
            ->findWares()
            ->selectGroup('engine')
            ->getAll();
        
        // Step 2: Extract each engine's properties
        foreach ($engines as $ware) {
            $macroExtractor = new EngineMacroExtractor($ware);
            $result[] = $macroExtractor->extract();
        }
        
        return $result;
    }
}
```

**Macro Extractor:** [EngineMacroExtractor.php](../../../src/X4/Database/Engines/EngineMacroExtractor.php)

```php
namespace X4\Database\Engines;

use X4\Database\Wares\WareDef;
use X4\Database\MacroIndex\MacroIndex;
use Mistralys\X4\XMLHelper;

class EngineMacroExtractor
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
        $engine = $dom->byTagName('engine')->requireFirst();
        $thruster = $engine->byTagName('thruster')->requireFirst();
        
        return [
            'id' => $this->ware->getID(),
            'name' => $this->ware->getName(),
            'size' => $this->extractSize(),
            'forward' => (float)$thruster->getAttribute('forward'),
            'reverse' => (float)$thruster->getAttribute('reverse'),
            'boost' => $this->extractBoost($engine),
            'travel' => $this->extractTravel($engine),
            'dataSource' => $macro->getDataSource()
        ];
    }
    
    private function extractSize(): string
    {
        // Extract from ware ID pattern: engine_{race}_{size}_...
        $parts = explode('_', $this->ware->getID());
        return $parts[2] ?? 'unknown';  // s, m, l, xl
    }
    
    private function extractBoost(DOMExtended $engine): ?array
    {
        $boostNode = $engine->byTagName('boost')->getFirst();
        if ($boostNode === null) {
            return null;
        }
        
        return [
            'duration' => (int)$boostNode->getAttribute('duration'),
            'thrust' => (float)$boostNode->getAttribute('thrust')
        ];
    }
    
    private function extractTravel(DOMExtended $engine): ?array
    {
        $travelNode = $engine->byTagName('travel')->getFirst();
        if ($travelNode === null) {
            return null;
        }
        
        return [
            'charge' => (int)$travelNode->getAttribute('charge'),
            'thrust' => (float)$travelNode->getAttribute('thrust')
        ];
    }
}
```

**Benefits:**
- Clear separation of concerns
- Macro extractor reusable (if needed elsewhere)
- Easy to test individual extractors
- Main extractor handles collection logic, macro extractor handles detail

**Variations:**
- **Shields:** Similar pattern, extracts `<recharge>`, `<hull>` properties
- **Weapons:** Extracts `<bullet>`, `<weapon>`, `<heat>` properties
- **Ships:** More complex, includes equipment slot extraction and parent macro handling

---

### Single-Phase Extractor Pattern

**Architecture:**
```
Read XML file
    ↓
Parse DOM
    ↓
Extract properties directly
    ↓
Return structured data array
```

**When to Use:**
- Data entirely in library XML
- No macro resolution needed
- Simple property mapping
- Performance matters

#### Example: Factions (Complete Implementation)

**Extractor:** [FactionsExtractor.php](../../../src/X4/Database/Factions/FactionsExtractor.php)

```php
namespace X4\Database\Factions;

use X4\Database\BaseExtractor;
use X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\XMLHelper;

class FactionsExtractor extends BaseExtractor
{
    public const OUTPUT_FILE = 'factions.json';
    
    public function extract(): array
    {
        $result = [];
        
        // Iterate all data sources
        foreach (DataSourceDefs::getInstance()->getAll() as $dataSource) {
            $filePath = $dataSource->getPath() . '/libraries/characters.xml';
            
            if (!file_exists($filePath)) {
                continue;
            }
            
            $dom = XMLHelper::createDOMExtended($filePath);
            $characters = $dom->byTagName('character')->getAll();
            
            foreach ($characters as $character) {
                $id = $character->getAttribute('id');
                $race = $character->getAttribute('race');
                
                // Only include races (factions)
                if ($race !== null) {
                    $result[] = [
                        'id' => $id,
                        'race' => $race,
                        'name' => $this->getTranslation($id),
                        'dataSource' => $dataSource->getID()
                    ];
                }
            }
        }
        
        return $result;
    }
    
    private function getTranslation(string $id): string
    {
        // Look up localized name from Translations collection
        // Implementation details omitted for brevity
    }
}
```

**Benefits:**
- Simple and direct
- Fast execution (no macro resolution overhead)
- Easy to understand
- Minimal code

**Variations:**
- **DataSources:** Filesystem scan instead of XML
- **Translations:** Recursive page traversal
- **Blueprints:** Filters wares by group, no macro needed

---

### Common DOM Query Patterns

Based on analysis of all extractors:

#### Pattern 1: Required Single Node
```php
// Node must exist or throw exception
$hull = (int)$dom->byTagName('hull')
    ->requireFirst()
    ->getAttribute('max');
```

**Use When:** Property is mandatory for data integrity.

#### Pattern 2: Optional Single Node
```php
// Node may not exist, return null
$boostNode = $dom->byTagName('boost')->getFirst();
$boostDuration = $boostNode ? (int)$boostNode->getAttribute('duration') : 0;
```

**Use When:** Property has reasonable default or is truly optional.

#### Pattern 3: Multiple Nodes
```php
// Get all nodes of a type
$connections = $dom->byTagName('connection')->getAll();

foreach ($connections as $connection) {
    $tags = $connection->getAttribute('tags');
    // Process each connection
}
```

**Use When:** Collection of related items (equipment slots, components, etc.).

#### Pattern 4: Nested Query
```php
// Chain queries for nested elements
$rechargeMax = $dom->byTagName('component')
    ->requireFirst()
    ->byTagName('recharge')
    ->requireFirst()
    ->getAttribute('max');
```

**Use When:** XML has deep nesting, need to traverse hierarchy.

#### Pattern 5: Attribute with Type Conversion
```php
// Always cast attributes to expected type
$forward = (float)$thruster->getAttribute('forward');
$people = (int)$properties->getAttribute('capacity');
$visible = $item->getAttribute('visible') === 'true';
```

**Use When:** Always (XML attributes are strings by default).

#### Pattern 6: Attribute with Fallback
```php
// Use null coalescing for defaults
$tags = $connection->getAttribute('tags') ?? '';
$size = $slot->getAttribute('size') ?? 'm';
```

**Use When:** Attribute may be missing, provide sensible default.

#### Pattern 7: String Array from Space-Separated
```php
// Common for tags: "engine large standard"
$tagsString = $connection->getAttribute('tags') ?? '';
$tagsArray = array_filter(explode(' ', $tagsString));
```

**Use When:** Tags, multiple values in single attribute.

---

### Error Handling Patterns

#### Pattern 1: Missing Macro (Recoverable)
```php
try {
    $macro = MacroIndex::getInstance()->getByID($macroName);
} catch (X4Exception $e) {
    // Log warning and skip item
    $this->logWarning("Macro not found: {$macroName}");
    continue;  // Skip this item, continue with next
}
```

**Use When:** Missing data shouldn't stop entire extraction.

#### Pattern 2: Invalid XML (Fatal)
```php
try {
    $dom = XMLHelper::createDOMExtended($filePath);
} catch (\Exception $e) {
    throw new X4Exception(
        "Failed to parse XML at {$filePath}: {$e->getMessage()}",
        $e
    );
}
```

**Use When:** Corrupted file means extraction cannot continue.

#### Pattern 3: Missing Required Property (Fatal)
```php
try {
    $hull = $dom->byTagName('hull')->requireFirst();
} catch (\Exception $e) {
    throw new X4Exception(
        "Required property 'hull' not found in {$macroName}",
        $e
    );
}
```

**Use When:** Property is mandatory for data integrity.

#### Pattern 4: Missing Optional Property (Default)
```php
$boostNode = $dom->byTagName('boost')->getFirst();

if ($boostNode === null) {
    return ['duration' => 0, 'thrust' => 0];  // Provide default
}
```

**Use When:** Property has reasonable default value.

#### Pattern 5: Type Conversion Failure (Log and Use Default)
```php
$forwardStr = $thruster->getAttribute('forward');
$forward = is_numeric($forwardStr) ? (float)$forwardStr : 0.0;

if ($forward === 0.0) {
    $this->logWarning("Invalid forward thrust value: {$forwardStr}");
}
```

**Use When:** Be defensive about data quality.

---

### Performance Considerations

**Two-Phase Extractors:**
- ~100-500ms per extractor depending on item count
- DOM loading is expensive (cache if possible)
- MacroIndex lookup is O(1) and fast

**Single-Phase Extractors:**
- ~10-50ms typically
- Much faster due to no macro resolution
- Prefer when possible

**Optimization Tips:**
1. **Minimize DOM loads:** Cache DOMs if accessing multiple properties
2. **Batch processing:** Process all items, don't reload collections
3. **Lazy evaluation:** Don't extract properties not used
4. **Filter early:** Reduce ware set before macro extraction

**Don't Prematurely Optimize:**
- Extraction runs once at build time
- Sub-second performance is acceptable
- Clarity > speed for maintenance
```

---

## ✅ Verification Steps

After completing this work package:

1. **Content Added:** Macro Resolution System section complete (300-400 lines)
2. **Content Added:** Extractor Patterns section complete (500-600 lines)
3. **Code Examples Accurate:** All PHP examples compile and follow project style
4. **Real References:** All code examples match actual extractor implementations
5. **Cross-References Work:** All file path links resolve
6. **No Contradictions:** Aligns with tech-stack.md patterns
7. **Comprehensive:** Covers all DOM query patterns found in codebase

### Specific Checks

```bash
# Verify sections added
Select-String "## 🔍 Macro Resolution System" docs/agents/project-manifest/extraction-reference.md
Select-String "## ⚙️ Extractor Patterns" docs/agents/project-manifest/extraction-reference.md

# Check line count increase (should be +700-1000 from WP1)
(Get-Content docs/agents/project-manifest/extraction-reference.md).Count

# Verify code examples present
Select-String "```php" docs/agents/project-manifest/extraction-reference.md | Measure

# Check cross-references
Select-String "\[.*\]\(.*\.php" docs/agents/project-manifest/extraction-reference.md
```

---

## 📤 Deliverables

1. **Sections Added to extraction-reference.md:**
   - Macro Resolution System (300-400 lines)
   - Extractor Patterns (500-600 lines)

2. **Content Includes:**
   - Macro resolution algorithm
   - Macro inheritance explanation
   - DOM loading and property fallback
   - Two-Phase pattern with complete engine example
   - Single-Phase pattern with complete faction example
   - 7 common DOM query patterns
   - 5 error handling patterns
   - Performance considerations

---

## 🔄 Next Steps

After WP2 completion:

1. **Update README:** Mark WP2 as complete
2. **Choose Next:** Can proceed with WP3 or WP4
   - WP3 (Advanced Features) - Independent, can run parallel
   - WP4 (Equipment Compatibility) - Requires WP2, can start now

**Dependencies Met:**
- WP4 can now start (requires WP1 + WP2) ✅
- WP5 still blocked (requires WP1-4)
- WP6 still blocked (requires WP1-5)

---

## 📝 Notes

- All code examples should be runnable (not pseudocode)
- Reference actual source files extensively
- Use consistent terminology with tech-stack.md
- Include both simple and complex pattern examples
- Error handling is critical for extractor reliability

---

**Work Package Status:** � Complete  
**Created:** February 9, 2026  
**Completed:** February 9, 2026
