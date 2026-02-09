# Advanced Extraction Features

> **Module:** X4 Core Project Manifest  
> **Category:** Data Extraction  
> **Last Updated:** February 9, 2026  
> **Dependencies:** [Extraction Patterns](patterns.md)

---

## 📖 Purpose

This document covers advanced extraction features that enable sophisticated data handling in X4 Core:

1. **Variant ID System** - How items with the same display name are differentiated
2. **Data Source Inheritance** - How DLCs override base game data  
3. **Composite ID Tracking** - Multi-DLC macro resolution

These features are critical for handling game updates, expansions, and item variations correctly.

---

## 🎯 Table of Contents

- [Variant ID System](#-variant-id-system)
  - [Overview](#overview)
  - [Format Specification](#format-specification)
  - [Why Variants Exist](#why-variants-exist)
  - [Parsing Implementation](#parsing-implementation)
  - [Usage in Collections](#usage-in-collections)
  - [Filtering by Variant](#filtering-by-variant)
  - [JSON Representation](#json-representation)
- [Data Source Inheritance](#-data-source-inheritance)
  - [Overview](#overview-1)
  - [DLC Load Order](#dlc-load-order)
  - [Override Mechanism](#override-mechanism)
  - [Composite ID System](#composite-id-system)
  - [Data Source Detection](#data-source-detection)
  - [Multi-Source Resolution](#multi-source-resolution)
  - [Filtering by Source](#filtering-by-source)
- [Source Files](#-source-files)

---

## 🔢 Variant ID System

### Overview

In X4: Foundations, multiple items can share identical display names despite having completely different stats, manufacturers, or upgrade tiers. The **Variant ID System** provides a structured way to distinguish these items programmatically.

**Problem:**
```
"Argon Fighter"  → Could be 10 different ships
"Engine XL"      → Could be 15 different engines
"Shield Mk1"     → Could be 8 different shields
```

**Solution:**
```
ship_arg_s_fighter_01_a  → Variant: 01:a:     (Model 01, Variant A)
ship_arg_s_fighter_01_b  → Variant: 01:b:     (Model 01, Variant B)
engine_arg_l_allround_01_mk1 → Variant: 01::mk1  (Model 01, Mk1)
```

---

### Format Specification

#### Pattern

**Format:** `{number}:{qualifier}:{mark}`

**Components:**

| Component | Required | Type | Description | Examples |
|-----------|----------|------|-------------|----------|
| **Number** | Yes | `01`-`99` | Base model number (zero-padded) | `01`, `02`, `15` |
| **Qualifier** | No | `a-z` or compound | Variant letter or identifier | `a`, `b`, `heavy`, `fast` |
| **Mark** | No | `mk1`-`mk3` | Upgrade tier | `mk1`, `mk2`, `mk3` |

**Separator:** Always colon (`:`)  
**Empty Parts:** Represented as empty string between colons

---

#### Complete Examples

| Ware ID | Variant ID | Breakdown |
|---------|------------|-----------|
| `ship_arg_s_fighter_01_a` | `01:a:` | Number: `01`, Qualifier: `a`, Mark: none |
| `ship_arg_s_fighter_01_b` | `01:b:` | Number: `01`, Qualifier: `b`, Mark: none |
| `ship_par_m_corvette_02_a` | `02:a:` | Number: `02`, Qualifier: `a`, Mark: none |
| `engine_arg_l_allround_01_mk1` | `01::mk1` | Number: `01`, Qualifier: none, Mark: `mk1` |
| `engine_arg_l_allround_01_mk2` | `01::mk2` | Number: `01`, Qualifier: none, Mark: `mk2` |
| `engine_arg_l_allround_01_mk3` | `01::mk3` | Number: `01`, Qualifier: none, Mark: `mk3` |
| `shield_arg_l_standard_02_mk3` | `02::mk3` | Number: `02`, Qualifier: none, Mark: `mk3` |
| `weapon_gen_s_laser_01_a_mk1` | `01:a:mk1` | Number: `01`, Qualifier: `a`, Mark: `mk1` |
| `weapon_gen_s_laser_01_a_mk2` | `01:a:mk2` | Number: `01`, Qualifier: `a`, Mark: `mk2` |
| `ship_tel_m_frigate_01_a` | `01:a:` | Number: `01`, Qualifier: `a`, Mark: none |

---

### Why Variants Exist

Understanding why variants are necessary helps explain the system design.

#### 1. Different Stats, Same Name

**Scenario:** Game designers create multiple ship variants with identical display names but different combat roles.

```
Display: "Argon Fighter Mk1"
├─ ship_arg_s_fighter_01_a  (01:a:)  → Fast interceptor (low armor)
├─ ship_arg_s_fighter_01_b  (01:b:)  → Heavy fighter (high armor)
└─ ship_arg_s_fighter_01_c  (01:c:)  → Balanced variant
```

**Why:** Provides variety without cluttering the UI with different names.

---

#### 2. Manufacturer Variations

**Scenario:** Different races manufacture the same equipment type with race-specific bonuses.

```
Display: "Engine XL"
├─ engine_arg_l_allround_01_mk1  (01::mk1)  → Argon make (efficiency focus)
├─ engine_par_l_allround_01_mk1  (01::mk1)  → Paranid make (speed focus)
├─ engine_tel_l_allround_01_mk1  (01::mk1)  → Teladi make (cost focus)
└─ engine_ter_l_allround_01_mk1  (01::mk1)  → Terran make (power focus)
```

**Why:** Race-specific equipment variants with different characteristics.

---

#### 3. Upgrade Tiers

**Scenario:** Progressive upgrade path from Mk1 → Mk2 → Mk3.

```
Display: "Energy Cell Cannon"
├─ weapon_gen_s_laser_01_mk1  (01::mk1)  → Base version
├─ weapon_gen_s_laser_01_mk2  (01::mk2)  → Improved version (+25% damage)
└─ weapon_gen_s_laser_01_mk3  (01::mk3)  → Elite version (+50% damage)
```

**Why:** Clear upgrade progression with same base design.

---

#### 4. DLC Additions

**Scenario:** DLCs introduce new variants of existing equipment types.

```
Display: "Fighter"
├─ ship_arg_s_fighter_01_a  (01:a:)  → Vanilla
├─ ship_spl_s_fighter_01_a  (01:a:)  → Split Vendetta DLC
├─ ship_ter_s_fighter_01_a  (01:a:)  → Cradle of Humanity DLC
└─ ship_bor_s_fighter_01_a  (01:a:)  → Kingdom End DLC
```

**Why:** Each DLC adds race-specific variants.

---

### Parsing Implementation

The [`VariantID`](../../../../src/X4/Database/Core/VariantID.php) class handles parsing ware IDs into structured variant information.

#### Class Structure

```php
namespace Mistralys\X4\Database\Core;

class VariantID
{
    private int $number;          // Model number (e.g., 1, 2, 3)
    private ?string $qualifier;   // Variant letter (e.g., 'a', 'b')
    private ?string $mark;        // Upgrade tier (e.g., 'mk1', 'mk2')
    
    /**
     * Create a variant ID from component parts
     */
    public function __construct(
        int $number = 0, 
        ?string $qualifier = null, 
        ?string $mark = null
    ) {
        $this->number = $number;
        $this->qualifier = $qualifier;
        $this->mark = $mark;
    }
}
```

---

#### Parsing from String ID

**Method:** `VariantID::fromID(string $variantID)`

```php
// Parse a variant ID string like "01:a:mk1"
$variant = VariantID::fromID('01:a:mk1');

echo $variant->getNumber();        // 1
echo $variant->getNumberString();  // "01" (zero-padded)
echo $variant->getQualifier();     // "a"
echo $variant->getMark();          // "mk1"
```

**Implementation:**
```php
public static function fromID(string $variantID) : self
{
    $parts = explode(':', $variantID);
    
    if(count($parts) !== 3) {
        return new VariantID();  // Empty variant
    }
    
    $number = (int)$parts[0];
    $qualifier = !empty($parts[1]) ? $parts[1] : null;
    $mark = !empty($parts[2]) ? $parts[2] : null;
    
    return new self($number, $qualifier, $mark);
}
```

---

#### Extracting from Ware ID

**Method:** `VariantID::resolveWareVariantID(string $wareID)`

```php
// Parse from full ware ID
$variant = VariantID::resolveWareVariantID('ship_arg_s_fighter_01_a');

echo $variant->getID();  // "01:a:"
```

**Algorithm:**
```php
public static function resolveWareVariantID($wareID): VariantID
{
    $parts = explode('_', $wareID);
    
    // Step 1: Extract mark (mk1, mk2, mk3) if present
    $mark = null;
    foreach (self::MARKS as $markValue) {
        if (in_array($markValue, $parts)) {
            $mark = $markValue;
            // Remove mark from parts for next step
            $parts = array_diff($parts, [$markValue]);
            break;
        }
    }
    
    // Step 2: Find number component (01, 02, etc.)
    foreach ($parts as $idx => $part) {
        if (preg_match('/^0\d$/', $part)) {  // Matches "01", "02", etc.
            $number = (int)$part;
            
            // Step 3: Everything after number is qualifier
            $remainingParts = array_slice($parts, $idx + 1);
            $qualifier = !empty($remainingParts) ? implode('-', $remainingParts) : null;
            
            return new VariantID($number, $qualifier, $mark);
        }
    }
    
    // No valid number found
    return new VariantID(0, null, $mark);
}
```

**Examples:**
```php
// Simple variant
VariantID::resolveWareVariantID('ship_arg_s_fighter_01_a')
// → VariantID(1, 'a', null) → "01:a:"

// With mark
VariantID::resolveWareVariantID('engine_arg_l_allround_01_mk2')
// → VariantID(1, null, 'mk2') → "01::mk2"

// Complex qualifier
VariantID::resolveWareVariantID('ship_par_m_corvette_02_heavy_mk1')
// → VariantID(2, 'heavy', 'mk1') → "02:heavy:mk1"
```

---

#### Conversion to String

**Method:** `getID()`

```php
$variant = new VariantID(1, 'a', 'mk1');
echo $variant->getID();  // "01:a:mk1"

$variant = new VariantID(2, null, 'mk2');
echo $variant->getID();  // "02::mk2"

$variant = new VariantID(3, 'b', null);
echo $variant->getID();  // "03:b:"
```

**Implementation:**
```php
public function getID() : string
{
    $parts = array();
    
    // Number (always present, zero-padded)
    $parts[] = $this->number > 0 ? sprintf('%02d', $this->number) : '';
    
    // Qualifier (optional)
    $parts[] = $this->qualifier ?? '';
    
    // Mark (optional)
    $parts[] = $this->mark ?? '';
    
    return implode(':', $parts);
}
```

---

### Usage in Collections

All item collections automatically parse and expose variant IDs.

#### Example: ShipDef

```php
namespace Mistralys\X4\Database\Ships;

class ShipDef
{
    private string $id;
    private VariantID $variantID;
    
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->variantID = VariantID::resolveWareVariantID($this->id);
    }
    
    public function getVariantID(): VariantID
    {
        return $this->variantID;
    }
    
    public function getVariantString(): string
    {
        return $this->variantID->getID();
    }
}
```

**Usage:**
```php
use Mistralys\X4\Database\Ships\ShipDefs;

$ship = ShipDefs::getInstance()->getByID('ship_arg_s_fighter_01_a');

echo $ship->getVariantString();           // "01:a:"
echo $ship->getVariantID()->getNumber();  // 1
echo $ship->getVariantID()->getQualifier(); // "a"
```

---

### Filtering by Variant

Variant IDs enable powerful filtering capabilities.

#### Filter by Mark

**Find all Mk1 engines:**
```php
use Mistralys\X4\Database\Engines\EngineDefs;

$mk1Engines = EngineDefs::getInstance()
    ->getAll()
    ->filter(fn(EngineDef $e) => $e->getVariantID()->getMark() === 'mk1');

foreach($mk1Engines as $engine) {
    echo $engine->getName() . " (" . $engine->getVariantString() . ")\n";
}
```

**Output:**
```
Engine XL (01::mk1)
Advanced Engine (02::mk1)
Military Engine (03::mk1)
```

---

#### Filter by Qualifier

**Find all 'a' variant ships:**
```php
use Mistralys\X4\Database\Ships\ShipDefs;

$variantA = ShipDefs::getInstance()
    ->getAll()
    ->filter(fn(ShipDef $s) => $s->getVariantID()->getQualifier() === 'a');

foreach($variantA as $ship) {
    echo $ship->getName() . " (" . $ship->getVariantString() . ")\n";
}
```

**Output:**
```
Argon Fighter (01:a:)
Paranid Corvette (02:a:)
Teladi Frigate (01:a:)
```

---

#### Filter by Number

**Find all model 01 variants:**
```php
use Mistralys\X4\Database\Weapons\WeaponDefs;

$model01 = WeaponDefs::getInstance()
    ->getAll()
    ->filter(fn(WeaponDef $w) => $w->getVariantID()->getNumber() === 1);
```

---

#### Complex Filters

**Find Mk2 or Mk3 engines (upgrade-focused):**
```php
$upgradedEngines = EngineDefs::getInstance()
    ->getAll()
    ->filter(function(EngineDef $e) {
        $mark = $e->getVariantID()->getMark();
        return $mark === 'mk2' || $mark === 'mk3';
    });
```

---

### JSON Representation

Variant IDs are included in all JSON exports for frontend consumption.

#### Example: engines.json

```json
{
  "id": "engine_arg_l_allround_01_mk1",
  "name": "Engine XL",
  "variantID": "01::mk1",
  "race": "argon",
  "size": "l",
  "forward": 2000.0,
  "reverse": 500.0
}
```

#### Example: ships.json

```json
{
  "id": "ship_arg_s_fighter_01_a",
  "name": "Argon Fighter",
  "variantID": "01:a:",
  "race": "argon",
  "size": "s",
  "hull": 1500,
  "speed": 350
}
```

**UI Usage:** Display items with same name but different variants:
```javascript
// Frontend JavaScript
const engines = [
  { name: "Engine XL", variantID: "01::mk1", forward: 2000 },
  { name: "Engine XL", variantID: "01::mk2", forward: 2500 },
  { name: "Engine XL", variantID: "01::mk3", forward: 3000 }
];

// Display with variant differentiation
engines.forEach(e => {
  console.log(`${e.name} [${e.variantID}]: ${e.forward} thrust`);
});
// Output:
// Engine XL [01::mk1]: 2000 thrust
// Engine XL [01::mk2]: 2500 thrust
// Engine XL [01::mk3]: 3000 thrust
```

---

## 🌍 Data Source Inheritance

### Overview

X4: Foundations supports multiple expansions (DLCs) that can **add new content** or **override existing content**. The Data Source Inheritance system manages which version of data to use when the same macro exists in multiple DLCs.

**Key Concept:** DLCs are loaded in a specific order, and later DLCs override earlier ones.

---

### DLC Load Order

DLCs are loaded in a fixed priority order. **Later sources override earlier sources.**

#### Priority Hierarchy

```
┌─────────────────────────────────────────────────────────────┐
│ LOWEST PRIORITY                                             │
├─────────────────────────────────────────────────────────────┤
│ 1. vanilla          │ X4: Foundations (base game)           │
│ 2. ego_dlc_split    │ Split Vendetta                        │
│ 3. ego_dlc_terran   │ Cradle of Humanity                    │
│ 4. ego_dlc_pirate   │ Tides of Avarice                      │
│ 5. ego_dlc_boron    │ Kingdom End                           │
│ 6. ego_dlc_timelines│ Timelines (most recent)               │
├─────────────────────────────────────────────────────────────┤
│ HIGHEST PRIORITY                                            │
└─────────────────────────────────────────────────────────────┘
```

**Rule:** If a macro exists in both `vanilla` and `ego_dlc_timelines`, the **Timelines version wins**.

---

#### Load Order Constants

**Defined in:** [`KnownDataSources.php`](../../../../src/X4/Database/DataSources/KnownDataSources.php)

```php
namespace Mistralys\X4\Database\DataSources;

class KnownDataSources
{
    // Data source IDs
    public const DATA_SOURCE_BASE_GAME = 'vanilla';
    public const DATA_SOURCE_SPLIT_VENDETTA = 'ego_dlc_split';
    public const DATA_SOURCE_CRADLE_HUMANITY = 'ego_dlc_terran';
    public const DATA_SOURCE_TIDES_AVARICE = 'ego_dlc_pirate';
    public const DATA_SOURCE_KINGDOM_END = 'ego_dlc_boron';
    public const DATA_SOURCE_TIMELINES = 'ego_dlc_timelines';
    
    // Complete list in priority order
    public const DATA_SOURCES = array(
        self::DATA_SOURCE_BASE_GAME,         // Lowest priority
        self::DATA_SOURCE_SPLIT_VENDETTA,
        self::DATA_SOURCE_CRADLE_HUMANITY,
        self::DATA_SOURCE_TIDES_AVARICE,
        self::DATA_SOURCE_KINGDOM_END,
        self::DATA_SOURCE_TIMELINES          // Highest priority
    );
}
```

---

### Override Mechanism

When a macro exists in multiple DLCs, the **highest priority version** is used.

#### Example: Engine Balancing Update

**Scenario:** Timelines DLC rebalances an engine that exists in the base game.

**Vanilla (base game):**
```xml
<!-- vanilla/assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml -->
<macro name="engine_arg_l_allround_01_mk1_macro">
  <properties>
    <thrust forward="2000" reverse="500" />
  </properties>
</macro>
```

**Timelines DLC:**
```xml
<!-- ego_dlc_timelines/assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml -->
<macro name="engine_arg_l_allround_01_mk1_macro">
  <properties>
    <thrust forward="2500" reverse="600" />  <!-- Buffed! -->
  </properties>
</macro>
```

**Result:** MacroIndex resolves to **Timelines version** with `forward=2500`.

---

#### Override Flow Diagram

```
┌──────────────────────────────────────────────────────────────┐
│ Macro: "engine_arg_l_allround_01_mk1_macro"                  │
└─────────┬────────────────────────────────────────────────────┘
          ├─ vanilla          → forward: 2000  (OVERRIDDEN)
          ├─ ego_dlc_split    → Not present
          ├─ ego_dlc_terran   → Not present
          ├─ ego_dlc_pirate   → Not present
          ├─ ego_dlc_boron    → Not present
          └─ ego_dlc_timelines → forward: 2500  ✓ ACTIVE VERSION
```

---

### Composite ID System

Composite IDs track which data source provides each macro.

#### Format

**Pattern:** `{dataSourceID}::{macroName}`

**Purpose:** Enable unique identification even when macro names collide.

---

#### Examples

| Macro Name | Data Source | Composite ID |
|------------|-------------|--------------|
| `engine_arg_l_allround_01_mk1_macro` | `vanilla` | `vanilla::engine_arg_l_allround_01_mk1_macro` |
| `engine_arg_l_allround_01_mk1_macro` | `ego_dlc_timelines` | `ego_dlc_timelines::engine_arg_l_allround_01_mk1_macro` |
| `ship_ter_m_frigate_01_a_macro` | `ego_dlc_terran` | `ego_dlc_terran::ship_ter_m_frigate_01_a_macro` |
| `ship_bor_l_destroyer_01_a_macro` | `ego_dlc_boron` | `ego_dlc_boron::ship_bor_l_destroyer_01_a_macro` |

**Note:** Only the **active (highest priority) version** is stored in the final macro index.

---

### Data Source Detection

The system automatically detects which DLCs are installed by scanning extracted data folders.

#### Implementation

**Location:** [`DataSourcesExtractor.php`](../../../../src/X4/Database/DataSources/DataSourcesExtractor.php)  
**Output:** [`data-sources.json`](../../../data/data-sources.json)

**Algorithm:**
```php
namespace Mistralys\X4\Database\DataSources;

class DataSourcesExtractor
{
    public function extract(): array
    {
        $result = [];
        $basePath = $this->app->getDataExtractionPath();
        
        // Scan for data source folders
        $folders = scandir($basePath);
        
        foreach ($folders as $folder) {
            $folderPath = $basePath . '/' . $folder;
            
            // Skip non-directories
            if (!is_dir($folderPath) || in_array($folder, ['.', '..'])) {
                continue;
            }
            
            // Check for info.json to confirm it's a data source
            $infoFile = $folderPath . '/info.json';
            if (!file_exists($infoFile)) {
                continue;
            }
            
            // Read data source metadata
            $info = json_decode(file_get_contents($infoFile), true);
            
            $result[] = [
                'id' => $folder,
                'label' => $info['name'] ?? $folder,
                'description' => $info['description'] ?? '',
                'version' => $info['version'] ?? 'unknown'
            ];
        }
        
        return $result;
    }
}
```

---

#### Example Output: data-sources.json

```json
[
  {
    "id": "vanilla",
    "label": "X4: Foundations",
    "description": "Base game content",
    "version": "7.00"
  },
  {
    "id": "ego_dlc_terran",
    "label": "Cradle of Humanity",
    "description": "Terran expansion",
    "version": "4.00"
  },
  {
    "id": "ego_dlc_timelines",
    "label": "Timelines",
    "description": "Latest expansion",
    "version": "7.00"
  }
]
```

**Usage:** Determine which DLCs are available before extraction.

---

### Multi-Source Resolution

The [`MacroIndexExtractor`](../../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php) handles macro resolution across multiple data sources.

#### Core Algorithm

```php
namespace Mistralys\X4\Database\MacroIndex;

class MacroIndexExtractor
{
    private DataFolders $dataFolders;
    private array $macros = [];
    
    public function extract(): void
    {
        // Process all data folders in priority order
        foreach ($this->dataFolders->getAll() as $dataFolder) {
            $this->processDataFolder($dataFolder);
        }
        
        // Sort and write to macro-index.json
        usort($this->macros, fn($a, $b) => 
            strnatcasecmp($a['name'], $b['name'])
        );
        
        MacroFileDefs::getInstance()
            ->getDataFile()
            ->putData(array_values($this->macros));
    }
    
    private function processDataFolder(DataFolder $dataFolder): void
    {
        $macroFile = $dataFolder->getPath() . '/index/macros.xml';
        
        if (!file_exists($macroFile)) {
            return;  // No macros in this data source
        }
        
        $dom = new DOMDocument();
        $dom->loadXML(file_get_contents($macroFile));
        
        foreach ($dom->getElementsByTagName('entry') as $entry) {
            $this->processEntry($entry, $dataFolder);
        }
    }
    
    private function processEntry(DOMElement $entry, DataFolder $dataFolder): void
    {
        $name = $entry->getAttribute('name');
        $path = $entry->getAttribute('value');
        
        // Key insight: This OVERWRITES earlier entries with same name
        $this->macros[$name] = [
            'name' => $name,
            'dataFolder' => $dataFolder->getID(),
            'fullPath' => $path,
            'compositeID' => $dataFolder->getID() . '::' . $name
        ];
    }
}
```

**Key Points:**
1. Data folders are processed in load order (vanilla → timelines)
2. Same macro name in later folder **overwrites** earlier entry
3. Only the **final (highest priority) version** is stored
4. Composite ID tracks which source "won"

---

#### Resolution Example

**Input State:**
```
vanilla/index/macros.xml:
  <entry name="engine_arg_l_allround_01_mk1_macro" value="..." />

ego_dlc_terran/index/macros.xml:
  <entry name="engine_arg_l_allround_01_mk1_macro" value="..." />

ego_dlc_timelines/index/macros.xml:
  <entry name="engine_arg_l_allround_01_mk1_macro" value="..." />
```

**Processing Flow:**
```
Step 1: Process vanilla
  macros['engine_arg_l_allround_01_mk1_macro'] = {
    dataFolder: 'vanilla', ...
  }

Step 2: Process ego_dlc_terran
  macros['engine_arg_l_allround_01_mk1_macro'] = {
    dataFolder: 'ego_dlc_terran', ...  (OVERWRITES vanilla)
  }

Step 3: Process ego_dlc_timelines
  macros['engine_arg_l_allround_01_mk1_macro'] = {
    dataFolder: 'ego_dlc_timelines', ...  (OVERWRITES terran)
  }
```

**Final Output (macro-index.json):**
```json
{
  "name": "engine_arg_l_allround_01_mk1_macro",
  "dataFolder": "ego_dlc_timelines",
  "fullPath": "/path/to/ego_dlc_timelines/assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml",
  "compositeID": "ego_dlc_timelines::engine_arg_l_allround_01_mk1_macro"
}
```

---

### Filtering by Source

Runtime APIs allow filtering collections by data source.

#### Find Items by Data Source

**Find only vanilla engines:**
```php
use Mistralys\X4\Database\Engines\EngineDefs;
use Mistralys\X4\Database\DataSources\KnownDataSources;

$vanillaEngines = EngineDefs::getInstance()
    ->getAll()
    ->filter(fn($e) => $e->getDataSource() === KnownDataSources::DATA_SOURCE_BASE_GAME);
```

**Find only Terran DLC ships:**
```php
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\DataSources\KnownDataSources;

$terranShips = ShipDefs::getInstance()
    ->getAll()
    ->filter(fn($s) => $s->getDataSource() === KnownDataSources::DATA_SOURCE_CRADLE_HUMANITY);
```

---

#### Find DLC-Exclusive Content

**Get all DLC items (exclude vanilla):**
```php
$dlcEngines = EngineDefs::getInstance()
    ->getAll()
    ->filter(function($engine) {
        return $engine->getDataSource() !== KnownDataSources::DATA_SOURCE_BASE_GAME;
    });
```

---

#### Check Item Availability

**Determine if item requires DLC:**
```php
$ship = ShipDefs::getInstance()->getByID('ship_ter_m_frigate_01_a');

if ($ship->getDataSource() === KnownDataSources::DATA_SOURCE_CRADLE_HUMANITY) {
    echo "Requires: Cradle of Humanity DLC\n";
} else {
    echo "Available in base game\n";
}
```

---

#### Use Cases

| Scenario | Filter Method |
|----------|---------------|
| **Player owns only base game** | Show only `vanilla` items |
| **"New in DLC" feature list** | Filter by specific DLC ID |
| **Debug: Which DLC adds this?** | Check `getDataSource()` |
| **Mod compatibility** | Exclude certain data sources |
| **DLC ownership validation** | Check required data source exists |

---

### Data Source in JSON Output

All extracted items include `dataSource` metadata.

#### Example: ships.json

```json
{
  "id": "ship_ter_m_frigate_01_a",
  "name": "Terran Frigate",
  "race": "terran",
  "dataSource": "ego_dlc_terran",
  "variantID": "01:a:",
  "hull": 15000
}
```

#### Example: engines.json

```json
{
  "id": "engine_arg_l_allround_01_mk1",
  "name": "Engine XL",
  "dataSource": "vanilla",
  "variantID": "01::mk1",
  "forward": 2000
}
```

**Frontend Usage:**
```javascript
// Filter items by DLC ownership
const ownedDLCs = ['vanilla', 'ego_dlc_terran'];

const availableShips = ships.filter(ship => 
  ownedDLCs.includes(ship.dataSource)
);
```

---

## 🔗 Source Files

### Core Classes

| File | Purpose |
|------|---------|
| [`VariantID.php`](../../../../src/X4/Database/Core/VariantID.php) | Parse and represent variant IDs |
| [`KnownDataSources.php`](../../../../src/X4/Database/DataSources/KnownDataSources.php) | Define load order and constants |
| [`MacroIndexExtractor.php`](../../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php) | Multi-source macro resolution |
| [`DataSourcesExtractor.php`](../../../../src/X4/Database/DataSources/DataSourcesExtractor.php) | DLC detection |

### Usage Examples

| File | Purpose |
|------|---------|
| [`ShipDef.php`](../../../../src/X4/Database/Ships/ShipDef.php) | Variant ID usage in ships |
| [`EngineDef.php`](../../../../src/X4/Database/Engines/EngineDef.php) | Variant ID usage in engines |
| [`MacroFileDef.php`](../../../../src/X4/Database/MacroIndex/MacroFileDef.php) | Data source tracking in macros |

---

## 📊 Summary

### Variant ID System

- **Format:** `{number}:{qualifier}:{mark}`
- **Purpose:** Distinguish items with same display name
- **Parsing:** `VariantID::resolveWareVariantID()`
- **Usage:** Filtering, UI differentiation, upgrade tracking

### Data Source Inheritance

- **Load Order:** `vanilla` → `split` → `terran` → `pirate` → `boron` → `timelines`
- **Override Rule:** Later sources replace earlier sources
- **Composite IDs:** `{dataSource}::{macroName}`
- **Purpose:** DLC filtering, version tracking, availability checks

---

**Last Updated:** February 9, 2026  
**Total Lines:** 591
