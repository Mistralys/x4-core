# Work Package 3: Advanced Extraction Features

**Status:** � Complete  
**Completed Lines:** 526  
**Estimated Time:** 2-3 hours  
**Dependencies:** WP1 (Foundation)  
**Output File:** `docs/agents/project-manifest/extraction-reference.md` (append)

---

## 🎯 Objective

Document advanced extraction features that enable sophisticated data handling:
1. Variant ID System - How items with same display name are differentiated
2. Data Source Inheritance - How DLCs override base game data
3. Composite ID tracking - Multi-DLC macro resolution

These features enable proper handling of game updates, expansions, and item variations.

---

## 📋 Prerequisites

**Must Be Complete:**
- ✅ WP1 (Foundation & XML Sources)

**Optional (Can Run in Parallel):**
- WP2 (Core Extraction Patterns) - Helpful but not required

**Knowledge Required:**
- Understanding of DLC/expansion structure
- Ware ID naming conventions

**Files to Read Before Starting:**
1. [VariantID.php](../../../src/X4/Database/Core/VariantID.php) - Variant parsing
2. [MacroIndexExtractor.php](../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php) - Multi-source resolution
3. [DataSourcesExtractor.php](../../../src/X4/Database/DataSources/DataSourcesExtractor.php) - DLC detection
4. [KnownDataSources.php](../../../src/X4/Database/DataSources/KnownDataSources.php) - Source constants

---

## 📚 Context

### Why Variants Matter

In X4, you'll find:
- Multiple items with exact same display name
- Different stats despite same name
- Different manufacturers (Argon vs Terran)
- Different upgrade tiers (Mk1, Mk2, Mk3)

**Problem:** How do you differentiate them?

**Solution:** Variant ID system embedded in ware IDs.

### Why Data Source Inheritance Matters

Game updates and DLCs can:
- Replace/update vanilla items
- Add new items
- Rebalance existing items

**Problem:** Which version of an item do we use?

**Solution:** Data source priority system with composite IDs.

---

## 🔍 Source References

### Key Files for Variant ID

| File | Purpose |
|------|---------|
| [VariantID.php](../../../src/X4/Database/Core/VariantID.php) | Parse and represent variant IDs |
| [ShipDef.php](../../../src/X4/Database/Ships/ShipDef.php) | Example usage in ships |
| [EngineDef.php](../../../src/X4/Database/Engines/EngineDef.php) | Example usage in engines |

### Key Files for Data Source Inheritance

| File | Purpose |
|------|---------|
| [MacroIndexExtractor.php](../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php) | Handles multi-source macro resolution |
| [DataSourcesExtractor.php](../../../src/X4/Database/DataSources/DataSourcesExtractor.php) | Detects available DLCs |
| [KnownDataSources.php](../../../src/X4/Database/DataSources/KnownDataSources.php) | Defines load order |

---

## 🛠️ Implementation Steps

### Step 1: Document Variant ID System

Add this section to `extraction-reference.md`:

```markdown
## 🔢 Variant ID System

### Overview

Variant IDs distinguish items that have the same display name but different stats, manufacturers, or upgrade tiers.

### Variant ID Format

**Pattern:** `{number}:{letter}:{mk}`

**Components:**
- **Number (required):** Base model number (`01`, `02`, `03`, ...)
- **Letter (optional):** Variant letter (`a`, `b`, `c`, ...)
- **Mk (optional):** Upgrade tier (`mk1`, `mk2`, `mk3`)

### Examples

| Ware ID | Variant ID | Meaning |
|---------|------------|---------|
| `ship_arg_s_fighter_01_a` | `01:a:` | Model 01, variant A, no Mk |
| `ship_arg_s_fighter_01_b` | `01:b:` | Model 01, variant B, no Mk |
| `engine_arg_l_allround_01_mk1` | `01::mk1` | Model 01, no letter, Mk1 |
| `engine_arg_l_allround_01_mk2` | `01::mk2` | Model 01, no letter, Mk2 |
| `shield_arg_l_standard_02_mk3` | `02::mk3` | Model 02, no letter, Mk3 |
| `weapon_gen_s_laser_01_a_mk1` | `01:a:mk1` | Model 01, variant A, Mk1 |

### Why Variants Exist

#### 1. Different Stats, Same Name
   ```
   "Argon Fighter Mk1" → ship_arg_s_fighter_01_a (fast, fragile)
   "Argon Fighter Mk1" → ship_arg_s_fighter_01_b (slow, armored)
   ```
   Same display name, different combat roles.

#### 2. Manufacturer Variations
   ```
   "Engine XL" → engine_arg_l_allround_01_mk1 (Argon make)
   "Engine XL" → engine_par_l_allround_01_mk1 (Paranid make)
   ```
   Different manufacturers, different base stats.

#### 3. Upgrade Tiers
   ```
   "Shield Mk1" → shield_arg_l_standard_01_mk1
   "Shield Mk2" → shield_arg_l_standard_01_mk2
   "Shield Mk3" → shield_arg_l_standard_01_mk3
   ```
   Progressive upgrades, higher tier = better stats.

#### 4. DLC Additions
   ```
   Vanilla:     ship_arg_s_fighter_01_a
   DLC Split:   ship_spl_s_fighter_01_a
   ```
   Different data sources provide new variants.

### Variant Extraction Logic

**Implementation:** [VariantID](../../../src/X4/Database/Core/VariantID.php)

```php
namespace X4\Database\Core;

class VariantID
{
    private string $number;
    private string $letter;
    private string $mk;
    
    public static function fromWareID(string $wareID): self
    {
        // Parse pattern: {prefix}_{race}_{size}_{type}_{NUMBER}_{LETTER}_{MK}
        // Example: engine_arg_l_allround_01_mk1
        
        $parts = explode('_', $wareID);
        $variant = new self();
        
        // Extract number (always present)
        foreach ($parts as $part) {
            if (preg_match('/^(\d+)$/', $part, $matches)) {
                $variant->number = $matches[1];
                break;
            }
        }
        
        // Extract letter (optional, single lowercase letter)
        foreach ($parts as $part) {
            if (preg_match('/^([a-z])$/', $part)) {
                $variant->letter = $part;
                break;
            }
        }
        
        // Extract mk (optional, mk1/mk2/mk3)
        foreach ($parts as $part) {
            if (preg_match('/^(mk\d+)$/', $part)) {
                $variant->mk = $part;
                break;
            }
        }
        
        return $variant;
    }
    
    public function toString(): string
    {
        return sprintf(
            '%s:%s:%s',
            $this->number,
            $this->letter,
            $this->mk
        );
    }
    
    public function getNumber(): string { return $this->number; }
    public function getLetter(): string { return $this->letter; }
    public function getMk(): string { return $this->mk; }
}
```

### Usage in Collections

**Example: ShipDef**

```php
namespace X4\Database\Ships;

class ShipDef
{
    private string $id;
    private VariantID $variantID;
    
    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->variantID = VariantID::fromWareID($this->id);
    }
    
    public function getVariantID(): VariantID
    {
        return $this->variantID;
    }
    
    public function getVariantString(): string
    {
        return $this->variantID->toString();  // "01:a:" or "02::mk3"
    }
}
```

### Filtering by Variant

**Find all Mk1 engines:**
```php
$mk1Engines = EngineDefs::getInstance()
    ->getAll()
    ->filter(fn(EngineDef $e) => $e->getVariantID()->getMk() === 'mk1');
```

**Find all 'a' variant ships:**
```php
$variantA = ShipDefs::getInstance()
    ->getAll()
    ->filter(fn(ShipDef $s) => $s->getVariantID()->getLetter() === 'a');
```

### Variant ID in JSON Output

**Example: engines.json**
```json
{
  "id": "engine_arg_l_allround_01_mk1",
  "name": "Engine XL",
  "variantID": "01::mk1",
  "size": "l",
  "forward": 2000.0
}
```

**Purpose:** Allows UI to distinguish items with same display name.
```

### Step 2: Document Data Source Inheritance

Add this section:

```markdown
## 🌍 Data Source Inheritance

### Overview

X4 supports multiple expansions (DLCs) that can add or override game data. The data source inheritance system manages which version of data to use when macros exist in multiple DLCs.

### DLC Load Order

**Priority:** Later sources override earlier sources.

```
1. vanilla          (Lowest priority - base game)
2. ego_dlc_split    (Split Vendetta)
3. ego_dlc_terran   (Cradle of Humanity)
4. ego_dlc_pirate   (Tides of Avarice)
5. ego_dlc_boron    (Kingdom End)
6. ego_dlc_timelines (Highest priority - most recent)
```

**Defined in:** [KnownDataSources](../../../src/X4/Database/DataSources/KnownDataSources.php)

```php
namespace X4\Database\DataSources;

class KnownDataSources
{
    public const DATA_SOURCE_VANILLA = 'vanilla';
    public const DATA_SOURCE_SPLIT = 'ego_dlc_split';
    public const DATA_SOURCE_TERRAN = 'ego_dlc_terran';
    public const DATA_SOURCE_PIRATE = 'ego_dlc_pirate';
    public const DATA_SOURCE_BORON = 'ego_dlc_boron';
    public const DATA_SOURCE_TIMELINES = 'ego_dlc_timelines';
    
    private const LOAD_ORDER = [
        self::DATA_SOURCE_VANILLA,
        self::DATA_SOURCE_SPLIT,
        self::DATA_SOURCE_TERRAN,
        self::DATA_SOURCE_PIRATE,
        self::DATA_SOURCE_BORON,
        self::DATA_SOURCE_TIMELINES,
    ];
}
```

### DLC Override Mechanism

When a macro exists in multiple DLCs, the **later (higher priority)** version is used.

**Example Scenario:**

Vanilla provides:
```xml
<!-- vanilla/assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml -->
<engine>
  <thruster forward="2000" />
</engine>
```

Timelines DLC updates it:
```xml
<!-- ego_dlc_timelines/assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml -->
<engine>
  <thruster forward="2500" />  <!-- Buffed! -->
</engine>
```

**Result:** MacroIndex resolves to Timelines version (forward thrust = 2500).

### Composite ID Tracking

**Format:** `{dataSource}::{macroName}`

**Purpose:** Track which DLC provides each macro, even when overridden.

**Examples:**
- `vanilla::engine_arg_l_allround_01_mk1_macro`
- `ego_dlc_terran::ship_ter_m_frigate_01_a_macro`
- `ego_dlc_timelines::engine_arg_l_allround_01_mk1_macro` (overrides vanilla)

**Storage in macro-index.json:**
```json
{
  "engine_arg_l_allround_01_mk1_macro": {
    "macroName": "engine_arg_l_allround_01_mk1_macro",
    "class": "engine",
    "filePath": "/path/to/ego_dlc_timelines/assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml",
    "dataSource": "ego_dlc_timelines",
    "compositeID": "ego_dlc_timelines::engine_arg_l_allround_01_mk1_macro"
  }
}
```

**Note:** Only the active (highest priority) version is stored.

### Data Source Detection

**Implementation:** [DataSourcesExtractor](../../../src/X4/Database/DataSources/DataSourcesExtractor.php)

```php
namespace X4\Database\DataSources;

class DataSourcesExtractor extends BaseExtractor
{
    public function extract(): array
    {
        $result = [];
        $basePath = $this->getApp()->getDataExtractionPath();
        
        // Scan for data source folders
        $folders = scandir($basePath);
        
        foreach ($folders as $folder) {
            $folderPath = $basePath . '/' . $folder;
            
            if (!is_dir($folderPath) || $folder === '.' || $folder === '..') {
                continue;
            }
            
            // Check for info.json to confirm it's a data source
            $infoFile = $folderPath . '/info.json';
            if (!file_exists($infoFile)) {
                continue;
            }
            
            $info = json_decode(file_get_contents($infoFile), true);
            
            $result[] = [
                'id' => $folder,  // 'vanilla', 'ego_dlc_terran', etc.
                'label' => $info['name'] ?? $folder,
                'description' => $info['description'] ?? '',
                'path' => $folderPath
            ];
        }
        
        return $result;
    }
}
```

**Output:** [data-sources.json](../../../data/data-sources.json)

```json
[
  {
    "id": "vanilla",
    "label": "X4 Foundations",
    "description": "Base game content"
  },
  {
    "id": "ego_dlc_terran",
    "label": "Cradle of Humanity",
    "description": "Terran expansion"
  }
]
```

### Multi-Source Macro Resolution

**Implementation:** [MacroIndexExtractor](../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php)

**Algorithm:**
```php
public function extract(): array
{
    $macroIndex = [];
    
    // Iterate data sources in load order
    foreach (KnownDataSources::LOAD_ORDER as $dataSourceID) {
        $dataSource = DataSourceDefs::getInstance()->getByID($dataSourceID);
        
        if (!$dataSource->exists()) {
            continue;  // DLC not installed
        }
        
        $indexFile = $dataSource->getPath() . '/index/macros.xml';
        $dom = XMLHelper::createDOMExtended($indexFile);
        
        foreach ($dom->byTagName('entry')->getAll() as $entry) {
            $macroName = $entry->getAttribute('name');
            
            // Later sources override earlier sources
            $macroIndex[$macroName] = [
                'macroName' => $macroName,
                'class' => $entry->getAttribute('class'),
                'filePath' => $dataSource->getPath() . '/' . $entry->getAttribute('path'),
                'dataSource' => $dataSourceID,
                'compositeID' => $dataSourceID . '::' . $macroName
            ];
        }
    }
    
    return array_values($macroIndex);
}
```

**Key Points:**
1. Iterate sources in load order (vanilla first)
2. Same macro name in later source **overwrites** earlier entry
3. Final index contains only one entry per macro (latest version)
4. Composite ID tracks which source provided it

### Filtering by Data Source

**Runtime API:**

```php
use X4\Database\Engines\EngineDefs;
use X4\Database\DataSources\KnownDataSources;

// Get only vanilla engines
$vanillaEngines = EngineDefs::getInstance()
    ->findEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
    ->getAll();

// Get only Terran DLC engines
$terranEngines = EngineDefs::getInstance()
    ->findEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_TERRAN)
    ->getAll();

// Get engines from any DLC (exclude vanilla)
$dlcEngines = EngineDefs::getInstance()
    ->getAll()
    ->filter(fn($e) => $e->getDataSource() !== KnownDataSources::DATA_SOURCE_VANILLA);
```

**Use Cases:**
- Player only owns base game, exclude DLC items
- Show "new in DLC X" items
- Debug which DLC provides specific item

### Data Source in JSON Output

All extracted items include `dataSource` property:

```json
{
  "id": "ship_ter_m_frigate_01_a",
  "name": "Terran Frigate",
  "dataSource": "ego_dlc_terran"
}
```

**Purpose:** Allows filtering and categorization by DLC.
```

---

## ✅ Verification Steps

After completing this work package:

1. **Content Added:** Variant ID System section complete (200-300 lines)
2. **Content Added:** Data Source Inheritance section complete (200-300 lines)
3. **Code Examples Accurate:** All examples match actual implementations
4. **Real Data:** Examples use actual ware IDs from data files
5. **Cross-References Work:** Links to source files resolve
6. **No Contradictions:** Aligns with tech-stack.md and WP1/WP2

### Specific Checks

```bash
# Verify sections added
Select-String "## 🔢 Variant ID System" docs/agents/project-manifest/extraction-reference.md
Select-String "## 🌍 Data Source Inheritance" docs/agents/project-manifest/extraction-reference.md

# Check line count increase (should be +400-600 from previous)
(Get-Content docs/agents/project-manifest/extraction-reference.md).Count

# Verify variant examples
Select-String "01:a:mk1" docs/agents/project-manifest/extraction-reference.md
```

---

## 📤 Deliverables

1. **Sections Added to extraction-reference.md:**
   - Variant ID System (200-300 lines)
   - Data Source Inheritance (200-300 lines)

2. **Content Includes:**
   - Variant ID format and parsing
   - Real-world variant examples
   - VariantID class implementation
   - DLC load order
   - Override mechanism
   - Composite ID tracking
   - Data source detection algorithm
   - Multi-source macro resolution
   - Filtering APIs

---

## 🔄 Next Steps

After WP3 completion:

1. **Update README:** Mark WP3 as complete
2. **Choose Next:** 
   - If WP2 complete → Can start WP4 (Equipment Compatibility)
   - If WP2 not complete → Can work on WP2 (parallel with WP3)

**Dependencies Met:**
- WP4 still requires WP2 to be complete
- WP5 still requires WP1-4
- WP6 still requires WP1-5

---

## 📝 Notes

- Variant ID system is critical for UI differentiation
- Data source tracking enables DLC filtering
- Composite IDs prevent macro name collisions
- Load order determines which version of data wins

---

**Work Package Status:** � Complete  
**Created:** February 9, 2026  
**Completed:** February 9, 2026
