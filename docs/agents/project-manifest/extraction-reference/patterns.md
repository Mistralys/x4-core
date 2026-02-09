# Extraction Patterns and Algorithms

> **Module Type:** Core Reference  
> **Domain:** Data Extraction Architecture  
> **Prerequisites:** [tech-stack.md](../tech-stack.md), [data-flows.md](../data-flows.md)  
> **Last Updated:** February 9, 2026  
> **Version:** 1.0

---

## 📋 Overview

This document captures the **core extraction patterns and algorithms** used throughout X4 Core's data extraction system. Understanding these patterns is essential for:

1. **Adding new extractors** - Follow established patterns
2. **Debugging extraction failures** - Understand error cases
3. **Optimizing extraction** - Know when to use which pattern
4. **Maintaining consistency** - All extractors work the same way

**Key Concepts:**
- **Macro Resolution:** How equipment names → file paths → properties
- **Two-Phase Extraction:** Main extractor + macro extractor architecture
- **Single-Phase Extraction:** Direct XML parsing without macro resolution
- **DOM Query Patterns:** Common XPath and DOMExtended usage
- **Error Handling:** Gracefully handling missing data

---

## 🔍 Macro Resolution System

### The Problem

X4's data is split across multiple XML files:

```
wares.xml:     <ware id="engine_arg_l_allround_01_mk1" name="Engine XL" />
               ↓ But where are the thrust stats? Speed? Boost?
               
macro XML:     assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml
               ↑ HERE! In a separate file!
```

**Challenge:** Given a ware ID, how do we find and load the detailed properties?

---

### Three-Step Resolution Algorithm

The macro resolution system uses a three-step process:

```
STEP 1: Ware ID → Macro Name
        "engine_arg_l_allround_01_mk1" + "_macro"
        = "engine_arg_l_allround_01_mk1_macro"

STEP 2: Macro Name → File Path (via MacroIndex)
        MacroIndex lookup returns:
        "vanilla::assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml"

STEP 3: File Path → DOM → Extract Properties
        Load XML, query with XPath, extract attributes
```

---

### MacroIndex: Fast Lookup Table

**Source:** [../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php](../../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php)  
**Output:** [../../../data/macro-index.json](../../../data/macro-index.json)  
**Runtime API:** [../../../src/X4/Database/MacroIndex/MacroIndex.php](../../../../src/X4/Database/MacroIndex/MacroIndex.php)

**Purpose:** Provides O(1) lookup from macro name to file path across all data sources.

**JSON Structure:**
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

**Runtime API Usage:**
```php
use Mistralys\X4\Database\MacroIndex\MacroIndex;

$macroIndex = MacroIndex::getInstance();
$macro = $macroIndex->getByID('engine_arg_l_allround_01_mk1_macro');

// Access properties
$filePath = $macro->getFilePath();      // Full absolute path
$dataSource = $macro->getDataSource();  // 'vanilla', 'ego_dlc_terran', etc.
$class = $macro->getClass();            // 'engine', 'shield', 'ship', etc.
```

---

### Macro Inheritance Chains

**Concept:** Ships and some equipment use macro inheritance to avoid duplication.

**Ship Example:**
```
ship_arg_s_fighter_01_a_macro     ← Child macro (Variant A)
    ↓ inherits from
ship_arg_s_fighter_01_macro       ← Parent macro (Base model)
```

**Why:** Base model defines common properties, variants override specific properties.

**Child Macro XML** (`ship_arg_s_fighter_01_a_macro.xml`):
```xml
<macro name="ship_arg_s_fighter_01_a_macro" alias="ship_arg_s_fighter_01_macro">
  <component>
    <properties>
      <identification unique="a" />  <!-- Only variant ID differs -->
    </properties>
  </component>
</macro>
```

**Parent Macro XML** (`ship_arg_s_fighter_01_macro.xml`):
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

**Result:** Child + parent properties combined, child properties override parent.

---

### Implementation: Parent/Child Property Resolution

**Source:** [../../../src/X4/Database/Ships/ShipsExtractor.php](../../../../src/X4/Database/Ships/ShipsExtractor.php#L280-L320)

```php
private function resolveParentMacro(DOMExtended $dom): ?string
{
    $alias = $dom->byTagName('macro')->requireFirst()->getAttribute('alias');
    if (!empty($alias)) {
        return $alias;  // Child references parent via alias
    }
    return null;
}

private function extractStats(DOMExtended $dom, ?DOMExtended $parentDom): array
{
    return [
        // Try child first, fall back to parent
        ShipDef::KEY_HULL => (int)$this->resolvePropertyAttribute(
            $dom, 
            $parentDom, 
            'hull', 
            'max', 
            0  // Default if not in child or parent
        ),
        ShipDef::KEY_PEOPLE => (int)$this->resolvePropertyAttribute(
            $dom, 
            $parentDom, 
            'people', 
            'capacity', 
            0
        ),
        // ... more properties
    ];
}

/**
 * Property Resolution Order:
 * 1. Try to extract from child DOM
 * 2. If null/missing, try parent DOM
 * 3. If still missing, use default value
 */
private function resolvePropertyAttribute(
    DOMExtended $dom, 
    ?DOMExtended $parentDom, 
    string $tagName, 
    string $attribute, 
    $default
) {
    // Try child first
    $node = $dom->byTagName($tagName)->getFirst();
    if ($node !== null) {
        $value = $node->getAttribute($attribute);
        if (!empty($value)) {
            return $value;
        }
    }
    
    // Try parent
    if ($parentDom !== null) {
        $node = $parentDom->byTagName($tagName)->getFirst();
        if ($node !== null) {
            $value = $node->getAttribute($attribute);
            if (!empty($value)) {
                return $value;
            }
        }
    }
    
    // Use default
    return $default;
}
```

---

### DOM Loading Pattern

**DOM Creation:**
```php
use Mistralys\X4\XML\DOMExtended;

$filePath = '/path/to/macro.xml';
$dom = DOMExtended::createFromFile($filePath);
```

**DOMExtended Benefits:**
- **Fluent API:** `$dom->byTagName('engine')->requireFirst()->getAttribute('forward')`
- **Null Safety:** `getFirst()` returns `null` instead of throwing
- **Type Safety:** Proper PHP types, cast as needed

---

### Property Extraction Patterns

**Pattern 1: Required Property (throws if missing)**
```php
private function extractHull(DOMExtended $dom): int
{
    return (int)$dom->byTagName('hull')
        ->requireFirst()  // Throws if not found
        ->getAttribute('max');
}
```

**Pattern 2: Optional Property (returns null if missing)**
```php
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
```

**Pattern 3: Property with Fallback Value**
```php
private function extractCrew(DOMExtended $dom): int
{
    $peopleNode = $dom->byTagName('people')->getFirst();
    return $peopleNode ? (int)$peopleNode->getAttribute('capacity') : 0;
}
```

---

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

**Why:** Allows filtering and tracking item origins, especially when same macro name exists in multiple DLCs.

---

## ⚙️ Two-Phase Extractor Pattern

### When to Use

Use the two-phase pattern when:
- ✅ Data requires macro XML resolution
- ✅ Complex property extraction from macro files
- ✅ Multiple DOM queries needed per item
- ✅ Equipment derived from wares but needs detailed stats

**Examples:** Engines, Shields, Weapons, Ships, Modules

---

### Architecture

```
┌─────────────────────────────────────────────┐
│ Main Extractor                              │
│ - Filters collection (e.g., wares by group) │
│ - Iterates filtered items                   │
│ - Instantiates Macro Extractor per item     │
│ - Collects results                          │
└─────────────────────┬───────────────────────┘
                      │
                      ↓
┌─────────────────────────────────────────────┐
│ Macro Extractor                             │
│ - Resolves macro name from ware ID          │
│ - Loads macro XML via MacroIndex            │
│ - Extracts properties via DOM queries       │
│ - Returns structured array for single item  │
└─────────────────────────────────────────────┘
```

### Division of Responsibilities

| Responsibility | Main Extractor | Macro Extractor |
|----------------|-----------------|-----------------|
| Filter collection | ✅ Yes | ❌ No |
| Iterate items | ✅ Yes | ❌ No |
| Resolve macros | ❌ No | ✅ Yes |
| Load XML | ❌ No | ✅ Yes |
| Extract properties | ❌ No | ✅ Yes |
| Handle errors | ✅ Yes | ✅ Yes |
| Write output file | ✅ Yes | ❌ No |

---

### Complete Example: Engines

#### Main Extractor

**File:** [../../../src/X4/Database/Engines/EnginesExtractor.php](../../../../src/X4/Database/Engines/EnginesExtractor.php)

```php
<?php
declare(strict_types=1);

namespace Mistralys\X4\Database\Engines;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;

/**
 * Main extractor for engines.
 * Filters wares collection and delegates detail extraction.
 */
class EnginesExtractor
{
    private array $engines = array();

    public function __construct(DataFolders $dataFolders)
    {
        // DataFolders injected for consistency
    }

    public function extract(): void
    {
        $this->extractEngines();
    }

    private function extractEngines(): void
    {
        Console::header('Extracting engines...');

        // STEP 1: Filter wares collection for engines only
        $engineWares = WareDefs::getInstance()
            ->findWares()
            ->selectGroup(WareGroups::GROUP_ENGINES)
            ->getAll();

        // STEP 2: Process each engine ware
        foreach ($engineWares as $ware) {
            $this->processWare($ware);
        }

        Console::line1('Found [%d] engines.', count($this->engines));
        Console::line1('Saving to disk...');
        Console::nl();

        // STEP 3: Sort and save
        ksort($this->engines);

        EngineDefs::getInstance()
            ->getDataFile()
            ->putData($this->engines);
    }

    private function processWare(WareDef $ware): void
    {
        // Instantiate Macro Extractor for detail work
        $macroExtractor = new EngineMacroExtractor($ware);
        $this->engines[] = $macroExtractor->extract();
    }
}
```

---

#### Macro Extractor

**File:** [../../../src/X4/Database/Engines/EngineMacroExtractor.php](../../../../src/X4/Database/Engines/EngineMacroExtractor.php)

```php
<?php
declare(strict_types=1);

namespace Mistralys\X4\Database\Engines;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Extracts detailed properties from a single engine macro XML.
 */
class EngineMacroExtractor
{
    private WareDef $ware;
    private DOMExtended $dom;

    public function __construct(WareDef $ware)
    {
        $this->ware = $ware;
        
        // STEP 1: Get macro from ware (uses MacroIndex internally)
        $macro = $ware->getMacro();
        
        // STEP 2: Load DOM from macro file path
        $this->dom = $macro->getDOM();
    }

    /**
     * STEP 3: Extract all properties from macro XML
     *
     * @return array<string,mixed>
     */
    public function extract(): array
    {
        return array(
            // Basic identifiers
            EngineDef::KEY_WARE_ID => $this->ware->getID(),
            EngineDef::KEY_MACRO_ID => $this->dom->byTagName('macro')
                ->requireFirst()
                ->getAttribute('name'),
            EngineDef::KEY_LABEL => $this->ware->getLabel(),
            EngineDef::KEY_SIZE => $this->ware->getSize(),
            EngineDef::KEY_DATA_SOURCE_ID => $this->ware->getDataSourceID(),
            EngineDef::KEY_MAKER_RACE => $this->resolveMakerRace(),
            EngineDef::KEY_MK => $this->resolveMk(),
            EngineDef::KEY_VARIANT_ID => (string)$this->ware->getVariantID(),
            
            // Boost properties (optional)
            EngineDef::KEY_BOOST_DURATION => $this->resolveFloat('boost', 'duration'),
            EngineDef::KEY_BOOST_RECHARGE => $this->resolveFloat('boost', 'recharge'),
            EngineDef::KEY_BOOST_THRUST => $this->resolveFloat('boost', 'thrust', 1.0),
            EngineDef::KEY_BOOST_ACCELERATION => $this->resolveFloat('boost', 'acceleration', 1.0),
            EngineDef::KEY_BOOST_ATTACK => $this->resolveFloat('boost', 'attack'),
            EngineDef::KEY_BOOST_RELEASE => $this->resolveFloat('boost', 'release'),
            EngineDef::KEY_BOOST_COAST => $this->resolveFloat('boost', 'coast', 1.0),
            
            // Travel properties (optional)
            EngineDef::KEY_TRAVEL_CHARGE => $this->resolveFloat('travel', 'charge'),
            EngineDef::KEY_TRAVEL_THRUST => $this->resolveFloat('travel', 'thrust'),
            EngineDef::KEY_TRAVEL_ATTACK => $this->resolveFloat('travel', 'attack'),
            EngineDef::KEY_TRAVEL_RELEASE => $this->resolveFloat('travel', 'release'),
            
            // Thrust properties (required)
            EngineDef::KEY_THRUST_FORWARD => $this->resolveFloat('thrust', 'forward'),
            EngineDef::KEY_THRUST_REVERSE => $this->resolveFloat('thrust', 'reverse'),
            
            // Hull properties
            EngineDef::KEY_HULL_MAX => $this->resolveFloat('hull', 'max'),
            EngineDef::KEY_HULL_THRESHOLD => $this->resolveFloat('hull', 'threshold'),
            
            // Complex nested data
            EngineDef::KEY_DECELERATION_CURVE => $this->resolveDecelerationCurve(),
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
     * Generic method to extract a float attribute from an optional element.
     */
    private function resolveFloat(
        string $tagName, 
        string $attribute, 
        float $default = 0.0
    ): float {
        $el = $this->dom->byTagName($tagName)->getFirst();
        if ($el !== null) {
            $value = $el->getAttribute($attribute);
            if (!empty($value)) {
                return (float)$value;
            }
        }
        return $default;
    }

    /**
     * Extract complex nested structure: deceleration curve points.
     *
     * @return array<int,array{position:float,value:float}>
     */
    private function resolveDecelerationCurve(): array
    {
        $curve = $this->dom->byTagName('decelerationcurve')->getFirst();
        if ($curve === null) {
            return array();
        }

        $points = array();
        foreach ($this->dom->byTagName('point')->getAll() as $pointNode) {
            // Verify this point belongs to the deceleration curve
            $parent = $pointNode->getDOMElement()->parentNode;
            if ($parent && $parent->nodeName === 'decelerationcurve') {
                $points[] = array(
                    'position' => (float)$pointNode->getAttribute('position'),
                    'value' => (float)$pointNode->getAttribute('value'),
                );
            }
        }

        return $points;
    }

    private function tagIdentification(): ?ElementExtended
    {
        return $this->dom->byTagName('identification')->getFirst();
    }
}
```

---

### Benefits of Two-Phase Pattern

✅ **Clear Separation:** Main extractor handles collection logic, macro extractor handles detail  
✅ **Reusability:** Macro extractor can be used independently if needed  
✅ **Testability:** Easy to unit test each extractor in isolation  
✅ **Maintainability:** Changes to property extraction don't affect collection filtering  
✅ **Consistency:** All equipment extractors follow same pattern

---

## 🔄 Single-Phase Extractor Pattern

### When to Use

Use the single-phase pattern when:
- ✅ All data is in library XML files (no macro resolution needed)
- ✅ Simple property mapping
- ✅ Performance matters (faster than two-phase)
- ✅ No complex nested queries

**Examples:** Factions, DataSources, Translations, SlotTypes

---

### Architecture

```
┌─────────────────────────────────────────────┐
│ Single Extractor                            │
│ - Reads XML file(s) directly               │
│ - Parses DOM                                │
│ - Extracts properties immediately           │
│ - Returns structured data array             │
│ - Writes output file                        │
└─────────────────────────────────────────────┘
```

**Key Difference:** No separation between filtering and detail extraction—everything in one class.

---

### Complete Example: Factions

**File:** [../../../src/X4/Database/Factions/FactionsExtractor.php](../../../../src/X4/Database/Factions/FactionsExtractor.php)

```php
<?php
declare(strict_types=1);

namespace Mistralys\X4\Database\Factions;

use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\DataExtractor\CatFileFinder;
use Mistralys\X4\ExtractedData\DataFolder;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Single-phase extractor for factions.
 * Reads factions.xml directly—no macro resolution needed.
 */
class FactionsExtractor
{
    private DataFolders $dataFolders;
    private Language $language;
    private array $factions = array();

    public function __construct(DataFolders $dataFolders)
    {
        $this->dataFolders = $dataFolders;
        $this->language = Languages::getInstance()->getEnglish();
    }

    public function extract(): void
    {
        $this->extractFactions();
        $this->validateFactions();
        $this->generateKnownFactions();
    }

    private function extractFactions(): void
    {
        // Iterate all data sources (vanilla + DLCs)
        foreach ($this->dataFolders->getAll() as $dataFolder) {
            $this->extractDataFolder($dataFolder);
        }

        // Add special "generic" faction not in XML
        $this->factions['generic'] = array(
            FactionDef::KEY_ID => 'generic',
            FactionDef::KEY_NAME => 'Generic',
            FactionDef::KEY_DATA_SOURCE_ID => CatFileFinder::SOURCE_VANILLA
        );

        ksort($this->factions);

        Console::line1('Found [%d] factions in total.', count($this->factions));
        Console::nl();

        // Write output
        FactionDefs::getInstance()
            ->getDataFile()
            ->putData(array_values($this->factions));
    }

    private function extractDataFolder(DataFolder $dataFolder): void
    {
        Console::header('Processing data folder [%s]', $dataFolder->getLabel());

        $factionsFile = $dataFolder->getPath() . '/libraries/factions.xml';

        if (!file_exists($factionsFile)) {
            Console::line1('SKIP | No factions file found.');
            Console::nl();
            return;
        }

        Console::line1('Processing factions file...');

        // STEP 1: Load DOM directly
        $dom = DOMExtended::createFromFile($factionsFile);
        
        // STEP 2: Get all faction elements
        $factionElements = $dom->byTagName('faction')->getAll();

        $found = 0;
        foreach ($factionElements as $factionElement) {
            $this->processFaction($factionElement, $dataFolder);
            $found++;
        }

        Console::line1('Found [%d] factions.', $found);
        Console::nl();
    }

    private function processFaction(
        ElementExtended $factionElement, 
        DataFolder $dataFolder
    ): void {
        $id = $factionElement->getAttribute('id');

        if (empty($id)) {
            Console::line1('ERROR | Faction element has no ID.');
            echo $factionElement->getXML();
            return;
        }

        // STEP 3: Extract properties directly from element
        $namePageID = $factionElement->getAttribute('name');
        $translated = $this->language->ts($namePageID);
        
        if (empty($translated)) {
            $translated = ucfirst($id);
        }

        // Special cases for naming conflicts
        if ($id === 'smuggler') {
            $translated = 'Smuggler';
        }
        if ($id === 'outlaw') {
            $translated = 'Outlaw';
        }

        // STEP 4: Build data array
        $this->factions[$id] = array(
            FactionDef::KEY_ID => $id,
            FactionDef::KEY_NAME => $translated,
            FactionDef::KEY_DATA_SOURCE_ID => $dataFolder->getID()
        );
    }

    private function validateFactions(): void
    {
        Console::header('Validating factions');

        $used = array();
        foreach ($this->factions as $faction) {
            $used[] = $faction[FactionDef::KEY_ID];
        }

        foreach (FactionDefs::getInstance()->getIDs() as $id) {
            if (!in_array($id, $used)) {
                Console::line1('ERROR | The faction [%s] is unused.', $id);
                exit;
            }
        }

        Console::line1('Done.');
        Console::nl();
    }

    private function generateKnownFactions(): void
    {
        // Generate KnownFactions class with constants
        // (Implementation omitted for brevity)
    }
}
```

---

### Benefits of Single-Phase Pattern

✅ **Simplicity:** All logic in one place, easier to understand  
✅ **Performance:** 5-10x faster than two-phase (no macro resolution)  
✅ **Direct:** No intermediate objects, straight from XML to data  
✅ **Less Code:** Fewer classes to maintain

---

## 📐 DOM Query Patterns

These patterns appear across ALL extractors—learn them once, use them everywhere.

---

### Pattern 1: Required Single Node

```php
// Element MUST exist or throw exception
$hull = (int)$dom->byTagName('hull')
    ->requireFirst()  // Throws if not found
    ->getAttribute('max');
```

**Use When:** Property is mandatory for data integrity (ship without hull = invalid).

---

### Pattern 2: Optional Single Node with Null Check

```php
// Element may not exist—return null or default
$boostNode = $dom->byTagName('boost')->getFirst();

if ($boostNode !== null) {
    $duration = (int)$boostNode->getAttribute('duration');
} else {
    $duration = 0;  // Default: no boost
}
```

**Use When:** Property is truly optional (not all engines have boost capability).

---

### Pattern 3: Multiple Nodes (Collection)

```php
// Get all nodes of same type
$connections = $dom->byTagName('connection')->getAll();

foreach ($connections as $connection) {
    $tags = $connection->getAttribute('tags');
    $size = $connection->getAttribute('size');
    // Process each connection
}
```

**Use When:** Working with collections (equipment slots, weapon bullets, etc.).

---

### Pattern 4: Nested Query Chain

```php
// Chain queries for deeply nested elements
$rechargeMax = $dom->byTagName('component')
    ->requireFirst()
    ->byTagName('properties')
    ->requireFirst()
    ->byTagName('recharge')
    ->requireFirst()
    ->getAttribute('max');
```

**Use When:** XML has deep nesting, need to traverse hierarchy.

---

### Pattern 5: Attribute with Type Conversion

```php
// ALWAYS cast attributes to expected PHP type
$forward = (float)$thruster->getAttribute('forward');
$people = (int)$properties->getAttribute('capacity');
$visible = $properties->getAttribute('visible') === 'true';  // Bool
```

**Use When:** Always! XML attributes are strings by default—cast to proper type.

---

### Pattern 6: Attribute with Null Coalescing

```php
// Provide default if attribute missing
$tags = $connection->getAttribute('tags') ?? '';
$size = $slot->getAttribute('size') ?? 'm';
$capacity = (int)($storage->getAttribute('capacity') ?? '0');
```

**Use When:** Attribute may be absent, provide sensible fallback.

---

### Pattern 7: Space-Separated String to Array

```php
// Common for tags: "engine large standard"
$tagsString = $connection->getAttribute('tags') ?? '';
$tagsArray = array_filter(explode(' ', $tagsString));

// Result: ['engine', 'large', 'standard']
```

**Use When:** Tags or multiple values in single attribute.

---

## 🚨 Error Handling Patterns

Extractors must handle failures gracefully. Here are the standard patterns:

---

### Pattern 1: Missing Macro (Recoverable)

```php
try {
    $macro = MacroIndex::getInstance()->getByID($macroName);
} catch (X4Exception $e) {
    // Log warning and skip item—don't stop entire extraction
    Console::line1('WARNING | Macro not found: %s', $macroName);
    continue;  // Skip this item, process next
}
```

**Use When:** Missing macro shouldn't stop entire extraction—skip item and continue.

---

### Pattern 2: Invalid XML (Fatal)

```php
try {
    $dom = DOMExtended::createFromFile($filePath);
} catch (\Exception $e) {
    // Corrupted XML = can't continue
    throw new X4Exception(
        "Failed to parse XML at {$filePath}: {$e->getMessage()}",
        $e
    );
}
```

**Use When:** Corrupted core file means extraction cannot continue—fail fast.

---

### Pattern 3: Missing Required Property (Fatal)

```php
try {
    $hull = $dom->byTagName('hull')->requireFirst();
} catch (\Exception $e) {
    // Required property missing = invalid data
    throw new X4Exception(
        "Required property 'hull' not found in {$macroName}",
        $e
    );
}
```

**Use When:** Property is mandatory for item validity—throw exception.

---

### Pattern 4: Missing Optional Property (Default)

```php
$boostNode = $dom->byTagName('boost')->getFirst();

if ($boostNode === null) {
    // No boost element—use sensible default
    return ['duration' => 0, 'thrust' => 0.0];
}

return [
    'duration' => (int)$boostNode->getAttribute('duration'),
    'thrust' => (float)$boostNode->getAttribute('thrust')
];
```

**Use When:** Property has reasonable default value (engines without boost = 0).

---

### Pattern 5: Type Conversion Failure (Defensive)

```php
$forwardStr = $thruster->getAttribute('forward');
$forward = is_numeric($forwardStr) ? (float)$forwardStr : 0.0;

if ($forward === 0.0 && $forwardStr !== '0') {
    Console::line1('WARNING | Invalid forward thrust: %s', $forwardStr);
}
```

**Use When:** Being defensive about data quality—log issues but don't crash.

---

## ⚡ Performance Considerations

### Two-Phase vs Single-Phase

| Metric | Two-Phase | Single-Phase |
|--------|-----------|--------------|
| **Execution Time** | ~100-500ms | ~10-50ms |
| **DOM Loads** | Many (1 per item) | Few (1 per file) |
| **Memory** | Higher (DOMs cached) | Lower |
| **Complexity** | Higher | Lower |

### Optimization Tips

1. **Minimize DOM Loads:** DOM parsing is expensive—reuse when possible
2. **Batch Processing:** Process all items in one pass, don't reload collections
3. **Lazy Evaluation:** Don't extract properties you won't use
4. **Filter Early:** Reduce item set before expensive operations

### Don't Prematurely Optimize

Remember the rule from [constraints.md](../constraints.md):

> Extraction runs **once at build time**. Sub-second performance is acceptable. Prioritize **clarity over speed** for long-term maintenance.

---

## 🔗 Related Resources

### Source Files Referenced

**Extractors:**
- [ShipsExtractor.php](../../../../src/X4/Database/Ships/ShipsExtractor.php) - Complex two-phase with inheritance
- [EnginesExtractor.php](../../../../src/X4/Database/Engines/EnginesExtractor.php) - Standard two-phase
- [EngineMacroExtractor.php](../../../../src/X4/Database/Engines/EngineMacroExtractor.php) - Macro detail extraction
- [FactionsExtractor.php](../../../../src/X4/Database/Factions/FactionsExtractor.php) - Single-phase example

**Index & Utilities:**
- [MacroIndexExtractor.php](../../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php) - Builds macro index
- [MacroIndex.php](../../../../src/X4/Database/MacroIndex/MacroIndex.php) - Runtime lookup API
- [MacroFileDef.php](../../../../src/X4/Database/MacroIndex/MacroFileDef.php) - Macro entry representation

**DOM Utilities:**
- [DOMExtended.php](../../../../src/X4/XML/DOMExtended.php) - Fluent DOM wrapper
- [ElementExtended.php](../../../../src/X4/XML/ElementExtended.php) - Element wrapper

### Related Manifest Documents

- [tech-stack.md](../tech-stack.md) - Extraction-Builder Pattern overview
- [data-flows.md](../data-flows.md) - Database Build Flow visualization
- [constraints.md](../constraints.md) - Performance and optimization rules

---

## ✅ Summary Checklist

When creating a new extractor, ask:

- [ ] Do I need macro resolution? → Two-Phase or Single-Phase?
- [ ] Where is my source XML? → Library file or macro files?
- [ ] What properties are required vs optional? → Use appropriate DOM patterns
- [ ] How do I handle missing data? → Choose right error handling pattern
- [ ] Is inheritance involved? → Implement parent/child resolution
- [ ] Have I followed naming conventions? → Check [constraints.md](../constraints.md)
- [ ] Did I update manifest documents? → See [AGENTS.md](../../AGENTS.md)

---

**Document Status:** ✅ Complete  
**Total Lines:** ~595  
**Created:** February 9, 2026  
**Last Updated:** February 9, 2026
