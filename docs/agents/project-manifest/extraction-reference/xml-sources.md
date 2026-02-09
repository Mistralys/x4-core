# XML Data Sources and Schema Reference

**Version:** 1.0  
**Created:** February 9, 2026  
**Last Updated:** February 9, 2026  
**Status:** Active  
**Audience:** AI agents and developers working with extraction logic

---

## 🎯 Purpose

This document provides comprehensive technical reference for X4 game data extraction sources and schemas:
- XML file locations across data sources (vanilla and DLCs)
- Complete inventory of all 11 extractors with dependencies
- XML schema patterns and data structures
- File path mappings for all data types

**Use this document when:**
- Locating XML files for specific game data types
- Understanding extraction dependencies and build order
- Creating new extractor classes
- Debugging extraction failures
- Understanding XML schema structure

---

## 📖 Table of Contents

1. [Overview](#overview)
2. [XML Data Sources](#xml-data-sources)
3. [Extractor Inventory](#extractor-inventory)
4. [XML Schema Quick Reference](#xml-schema-quick-reference)
5. [Related Documents](#related-documents)

---

## 📊 Overview

### Data Extraction Pipeline

```
┌─────────────────────────────────────────────────────────────┐
│ 1. Game Files (Compressed CAT/DAT)                          │
│    Location: X4 Foundations install directory               │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 2. X4 Data Extractor (x4-data-extractor project)            │
│    - Unpacks CAT/DAT using XCatTool                         │
│    - Extracts XML files by DLC/expansion                    │
│    - Outputs to: output/{vanilla,ego_dlc_*}/                │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 3. X4 Core Extractors (this project)                        │
│    - Read XML files from x4-data-extractor output           │
│    - Parse and transform data                               │
│    - Generate JSON files: data/*.json                       │
└─────────────────────────────────────────────────────────────┘
                           ↓
┌─────────────────────────────────────────────────────────────┐
│ 4. Runtime Collections                                       │
│    - Load JSON files into memory                            │
│    - Provide finder/filter APIs                             │
│    - Used by UI layer                                       │
└─────────────────────────────────────────────────────────────┘
```

### Scope of This Document

**IN SCOPE:**
- Step 3: X4 Core extractors reading XML → generating JSON
- XML file locations and structure
- Extractor dependencies and build order
- DOM querying and property extraction

**OUT OF SCOPE:**
- Step 2: XCatTool unpacking (see x4-data-extractor docs)
- Step 4: Runtime collection usage (see [data-flows.md](../data-flows.md))
- Game file structure (CAT/DAT internals)

---

## 📂 XML Data Sources

### Base Configuration

**X4 Data Extractor Output Path:**  
Configured in: `dev-config.php`  
Property: `X4Application::setDataExtractionPath()`  
Typical Value: `F:\Webserver\www\htdocs\tools\x4-data-extractor\output\`

### Data Source Folder Structure

Each data source is a separate folder containing complete XML extraction:

```
output/
├── vanilla/                    # Base game (always present)
├── ego_dlc_split/              # Split Vendetta expansion
├── ego_dlc_terran/             # Cradle of Humanity expansion
├── ego_dlc_pirate/             # Tides of Avarice expansion
├── ego_dlc_boron/              # Kingdom End expansion
├── ego_dlc_timelines/          # Timelines expansion
├── ego_dlc_mini_01/            # Mini DLC 1
├── ego_dlc_mini_02/            # Mini DLC 2
└── game-info.json              # Version and DLC detection
```

### Directory Structure (Per Data Source)

```
{dataSource}/
├── info.json                   # DLC metadata
├── index/
│   └── macros.xml              # Macro name → file path index
├── libraries/
│   ├── wares.xml               # All game items (catalog)
│   ├── characters.xml          # Factions and races
│   └── ...
├── assets/
│   ├── props/
│   │   ├── Engines/
│   │   │   └── macros/
│   │   │       └── *.xml       # Engine macro definitions
│   │   ├── ShipUpgrades/
│   │   │   └── macros/
│   │   │       └── *.xml       # Shield macro definitions
│   │   ├── WeaponSystems/
│   │   │   └── macros/
│   │   │       └── *.xml       # Weapon macro definitions
│   │   └── ...
│   ├── units/
│   │   └── */
│   │       └── macros/
│   │           └── *.xml       # Ship macro definitions
│   └── structures/
│       └── */
│           └── macros/
│               └── *.xml       # Module macro definitions
└── t/
    ├── 0001-L007.xml           # Russian (ru_RU)
    ├── 0001-L033.xml           # French (fr_FR)
    ├── 0001-L034.xml           # Spanish (es_ES)
    ├── 0001-L039.xml           # Italian (it_IT)
    ├── 0001-L044.xml           # English (en_EN)
    ├── 0001-L049.xml           # German (de_DE)
    └── 0001-L082.xml           # Korean (ko_KR)
```

### Key XML Files by Purpose

| Purpose | File Path | Extractor Using It |
|---------|-----------|-------------------|
| **Macro Index** | `index/macros.xml` | MacroIndexExtractor |
| **All Items Catalog** | `libraries/wares.xml` | WaresExtractor, all equipment extractors |
| **Factions/Races** | `libraries/characters.xml` | FactionsExtractor |
| **Translations** | `t/0001-L044.xml` (English) | TranslationsExtractor |
| **Engine Stats** | `assets/props/Engines/macros/*.xml` | EnginesExtractor |
| **Shield Stats** | `assets/props/ShipUpgrades/macros/*.xml` | ShieldsExtractor |
| **Weapon Stats** | `assets/props/WeaponSystems/macros/*.xml` | WeaponsExtractor |
| **Ship Stats** | `assets/units/*/macros/*.xml` | ShipsExtractor |
| **Module Stats** | `assets/structures/*/macros/*.xml` | ModulesExtractor |

### Path Resolution Example

```php
// To load an engine macro:
$dataPath = '/path/to/output/vanilla';
$macroPath = 'assets/props/Engines/macros/engine_arg_l_allround_01_mk1_macro.xml';
$fullPath = $dataPath . '/' . $macroPath;

// MacroIndex provides this mapping from macro name:
$macroName = 'engine_arg_l_allround_01_mk1_macro';
$mapping = $macroIndex->getMacro($macroName); // Returns MacroFileDef
$filePath = $mapping->getFilePath(); // Returns full path
```

### Multi-DLC Resolution

When a macro exists in multiple DLCs:
- MacroIndex tracks all occurrences
- Later DLCs override earlier ones (load order priority)
- Original data source tracked via composite ID: `{dataSource}::{macroName}`

**Example:**
```
vanilla::shield_arg_l_standard_01_mk1_macro
ego_dlc_terran::shield_ter_l_standard_01_mk1_macro
```

---

## 📋 Extractor Inventory

### Build Order and Dependencies

Extractors must run in dependency order. From [DatabaseBuilder.php](../../../../src/X4/Database/DatabaseBuilder.php):

```php
// Step 1: Foundation (no dependencies)
$this->buildMacroIndex();      // Required by all macro extractors
$this->buildTranslations();    // Required for labels
$this->buildDataSources();     // Required for DLC tracking

// Step 2: Core Data
$this->buildFactions();        // Required for maker race filtering

// Step 3: Wares (base for all equipment)
$this->buildWares();           // Required by all equipment extractors

// Step 4: Equipment (no inter-dependencies)
$this->buildEngines();
$this->buildShields();
$this->buildWeapons();
$this->buildModules();
$this->buildBlueprints();

// Step 5: Ships (uses equipment collections for compatibility)
$this->buildShips();
```

### Complete Extractor List

| # | Extractor | Output File | Dependencies | Pattern | Item Count (approx) |
|---|-----------|-------------|--------------|---------|---------------------|
| **1** | MacroIndexExtractor | macro-index.json | None | Single-Phase | ~15,000 macros |
| **2** | TranslationsExtractor | lang-*.json (7 files) | None | Single-Phase | ~50,000 strings/lang |
| **3** | DataSourcesExtractor | data-sources.json | None | Single-Phase | 8 data sources |
| **4** | FactionsExtractor | factions.json | Translations | Single-Phase | ~20 factions |
| **5** | WaresExtractor | wares.json | Translations, Factions | Single-Phase | ~500 wares |
| **6** | EnginesExtractor | engines.json | Wares, MacroIndex | Two-Phase | ~150 engines |
| **7** | ShieldsExtractor | shields.json | Wares, MacroIndex | Two-Phase | ~100 shields |
| **8** | WeaponsExtractor | weapons.json | Wares, MacroIndex | Two-Phase | ~200 weapons |
| **9** | ModulesExtractor | modules.json | Wares, MacroIndex | Two-Phase | ~300 modules |
| **10** | BlueprintsExtractor | blueprints.json | Wares | Single-Phase | ~400 blueprints |
| **11** | ShipsExtractor | ships.json | Wares, MacroIndex, Engines, Shields, Weapons | Two-Phase | ~60 ships |

### Extractor Details

#### 1. MacroIndexExtractor
**Location:** [src/X4/Database/MacroIndex/MacroIndexExtractor.php](../../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php)  
**Source XML:** `{dataSource}/index/macros.xml`  
**Output:** [data/macro-index.json](../../../data/macro-index.json)  
**Purpose:** Creates mapping of macro names to physical file paths across all data sources.

**Key Properties Extracted:**
- `name` - Macro identifier (e.g., `engine_arg_l_allround_01_mk1_macro`)
- `class` - Macro type (e.g., `engine`, `shield`, `ship`)
- `path` - Relative file path to macro XML

**Source Link:** [MacroIndexExtractor.php](../../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php)

---

#### 2. TranslationsExtractor
**Location:** [src/X4/Database/Translations/TranslationsExtractor.php](../../../../src/X4/Database/Translations/TranslationsExtractor.php)  
**Source XML:** `{dataSource}/t/0001-L*.xml` (7 language files)  
**Output:** [data/lang-*.json](../../../data/) (7 files: 007, 033, 034, 039, 044, 049, 082)  
**Purpose:** Extract UI text strings for all supported languages.

**Languages:**
- L007 (007) - Russian (ru_RU)
- L033 (033) - French (fr_FR)
- L034 (034) - Spanish (es_ES)
- L039 (039) - Italian (it_IT)
- L044 (044) - English (en_EN)
- L049 (049) - German (de_DE)
- L082 (082) - Korean (ko_KR)

**Source Link:** [TranslationsExtractor.php](../../../../src/X4/Database/Translations/TranslationsExtractor.php)

---

#### 3. DataSourcesExtractor
**Location:** [src/X4/Database/DataSources/DataSourcesExtractor.php](../../../../src/X4/Database/DataSources/DataSourcesExtractor.php)  
**Source:** Filesystem scan of output folders  
**Output:** [data/data-sources.json](../../../data/data-sources.json)  
**Purpose:** Detect installed DLCs and assign IDs/labels.

**Detection Mechanism:**
- Scans extraction path for folders matching pattern
- Reads `info.json` from each folder
- Assigns IDs: `vanilla`, `ego_dlc_split`, `ego_dlc_terran`, etc.

**Source Link:** [DataSourcesExtractor.php](../../../../src/X4/Database/DataSources/DataSourcesExtractor.php)

---

#### 4. FactionsExtractor
**Location:** [src/X4/Database/Factions/FactionsExtractor.php](../../../../src/X4/Database/Factions/FactionsExtractor.php)  
**Source XML:** `{dataSource}/libraries/characters.xml`  
**Output:** [data/factions.json](../../../data/factions.json)  
**Purpose:** Extract faction/race data for maker identification.

**Key Properties:**
- `id` - Faction identifier (e.g., `argon`, `paranid`)
- `name` - Localized faction name
- `race` - Race type

**Source Link:** [FactionsExtractor.php](../../../../src/X4/Database/Factions/FactionsExtractor.php)

---

#### 5. WaresExtractor
**Location:** [src/X4/Database/Wares/WaresExtractor.php](../../../../src/X4/Database/Wares/WaresExtractor.php)  
**Source XML:** `{dataSource}/libraries/wares.xml`  
**Output:** [data/wares.json](../../../data/wares.json)  
**Purpose:** Extract all game items (master catalog for equipment extractors).

**Key Properties:**
- `id` - Ware identifier (e.g., `engine_arg_l_allround_01_mk1`)
- `name` - Localized ware name
- `group` - Ware type (`engine`, `shield`, `weapon`, `module`, etc.)
- `transport` - Cargo class
- `tags` - String array of tags for filtering

**Source Link:** [WaresExtractor.php](../../../../src/X4/Database/Wares/WaresExtractor.php)

---

#### 6. EnginesExtractor
**Location:** [src/X4/Database/Engines/EnginesExtractor.php](../../../../src/X4/Database/Engines/EnginesExtractor.php)  
**Source XML:** Wares + `{dataSource}/assets/props/Engines/macros/*.xml`  
**Output:** [data/engines.json](../../../data/engines.json)  
**Purpose:** Extract engine equipment with performance statistics.

**Key Properties:**
- Wares data (id, name, maker)
- Forward/reverse thrust
- Boost duration and thrust
- Travel mode statistics

**Source Link:** [EnginesExtractor.php](../../../../src/X4/Database/Engines/EnginesExtractor.php)

---

#### 7. ShieldsExtractor
**Location:** [src/X4/Database/Shields/ShieldsExtractor.php](../../../../src/X4/Database/Shields/ShieldsExtractor.php)  
**Source XML:** Wares + `{dataSource}/assets/props/ShipUpgrades/macros/*.xml`  
**Output:** [data/shields.json](../../../data/shields.json)  
**Purpose:** Extract shield equipment with capacity and recharge rates.

**Key Properties:**
- Wares data (id, name, maker)
- Maximum capacity
- Recharge rate and delay
- Hull regeneration threshold

**Source Link:** [ShieldsExtractor.php](../../../../src/X4/Database/Shields/ShieldsExtractor.php)

---

#### 8. WeaponsExtractor
**Location:** [src/X4/Database/Weapons/WeaponsExtractor.php](../../../../src/X4/Database/Weapons/WeaponsExtractor.php)  
**Source XML:** Wares + `{dataSource}/assets/props/WeaponSystems/macros/*.xml`  
**Output:** [data/weapons.json](../../../data/weapons.json)  
**Purpose:** Extract weapon equipment with damage and firing characteristics.

**Key Properties:**
- Wares data (id, name, maker)
- Damage per shot
- Fire rate and range
- Heat generation

**Source Link:** [WeaponsExtractor.php](../../../../src/X4/Database/Weapons/WeaponsExtractor.php)

---

#### 9. ModulesExtractor
**Location:** [src/X4/Database/Modules/ModulesExtractor.php](../../../../src/X4/Database/Modules/ModulesExtractor.php)  
**Source XML:** Wares + `{dataSource}/assets/structures/*/macros/*.xml`  
**Output:** [data/modules.json](../../../data/modules.json)  
**Purpose:** Extract station module data.

**Key Properties:**
- Wares data (id, name, maker)
- Module type
- Production capabilities
- Construction requirements

**Source Link:** [ModulesExtractor.php](../../../../src/X4/Database/Modules/ModulesExtractor.php)

---

#### 10. BlueprintsExtractor
**Location:** [src/X4/Database/Blueprints/BlueprintsExtractor.php](../../../../src/X4/Database/Blueprints/BlueprintsExtractor.php)  
**Source XML:** `{dataSource}/libraries/wares.xml` (filtered by group='blueprints')  
**Output:** [data/blueprints.json](../../../data/blueprints.json)  
**Purpose:** Extract crafting blueprint data.

**Key Properties:**
- Blueprint identifier
- Localized name
- Crafting requirements
- Output items

**Source Link:** [BlueprintsExtractor.php](../../../../src/X4/Database/Blueprints/BlueprintsExtractor.php)

---

#### 11. ShipsExtractor
**Location:** [src/X4/Database/Ships/ShipsExtractor.php](../../../../src/X4/Database/Ships/ShipsExtractor.php)  
**Source XML:** Wares + `{dataSource}/assets/units/*/macros/*.xml`  
**Output:** [data/ships.json](../../../data/ships.json)  
**Purpose:** Extract ship data with equipment slots and compatibility.

**Key Properties:**
- Wares data (id, name, maker)
- Hull points
- Cargo capacity
- Crew capacity
- Equipment slots (engines, shields, weapons) with size/tag constraints
- Compatible equipment lists

**Source Link:** [ShipsExtractor.php](../../../../src/X4/Database/Ships/ShipsExtractor.php)

---

## 🗺️ XML Schema Quick Reference

### Complete Extractor → XML Mapping

| Extractor | Source XML Path | Root Node | Key Child Nodes | Common Attributes |
|-----------|-----------------|-----------|-----------------|-------------------|
| **MacroIndexExtractor** | `index/macros.xml` | `<index>` | `<entry>` | `name`, `class`, `path` |
| **TranslationsExtractor** | `t/0001-L*.xml` | `<language>` | `<page>`→`<t>` | `id` (text key) |
| **DataSourcesExtractor** | `info.json` (filesystem) | N/A | N/A | `id`, `name`, `description` |
| **FactionsExtractor** | `libraries/characters.xml` | `<characters>` | `<character>` | `id`, `name`, `race` |
| **WaresExtractor** | `libraries/wares.xml` | `<wares>` | `<ware>` | `id`, `name`, `group`, `transport`, `tags` |
| **EnginesExtractor** | Wares + `assets/props/Engines/macros/*.xml` | `<macro>`→`<component>` | `<engine>`, `<boost>`, `<travel>` | `forward`, `reverse`, `boost`, `duration` |
| **ShieldsExtractor** | Wares + `assets/props/ShipUpgrades/macros/*.xml` | `<macro>`→`<component>` | `<recharge>`, `<hull>` | `max`, `rate`, `delay`, `threshold` |
| **WeaponsExtractor** | Wares + `assets/props/WeaponSystems/macros/*.xml` | `<macro>`→`<component>` | `<bullet>`, `<weapon>`, `<heat>` | `damage`, `range`, `rate`, `heat` |
| **ModulesExtractor** | Wares + `assets/structures/*/macros/*.xml` | `<macro>`→`<component>` | `<properties>`, `<identification>` | `type`, `makerrace` |
| **BlueprintsExtractor** | `libraries/wares.xml` (filtered) | `<wares>` | `<ware>` (group=blueprints) | `id`, `name`, `group` |
| **ShipsExtractor** | Wares + `assets/units/*/macros/*.xml` | `<macro>`→`<component>` | `<properties>`, `<hull>`, `<storage>`, `<connections>` | `people`, `mass`, `drag`, `hull`, `tags` |

### XPath Query Patterns

Common DOM queries used across extractors:

```php
// Single required node - throws if missing
$dom->byTagName('engine')->requireFirst()->getAttribute('forward')

// Single optional node - returns null if missing
$dom->byTagName('boost')->getFirst()->getAttribute('duration') ?? 0

// All nodes of type - returns array
$connections = $dom->byTagName('connection')->getAll()

// Nested query - chain method calls
$dom->byTagName('component')
    ->requireFirst()
    ->byTagName('engine')
    ->requireFirst()

// Attribute extraction with type conversion
(float)$node->getAttribute('max')
(int)$node->getAttribute('people')
```

### Common XML Structures

#### Engine Macro Example
```xml
<macro name="engine_arg_l_allround_01_mk1_macro" class="engine">
  <component>
    <engine>
      <thruster forward="2000" reverse="800" />
      <boost duration="5000" thrust="12000" />
      <travel charge="80000" thrust="50000" />
    </engine>
  </component>
</macro>
```

**Extracted Properties:**
- Forward thrust: `<thruster forward="2000" />`
- Reverse thrust: `<thruster reverse="800" />`
- Boost duration: `<boost duration="5000" />`
- Boost thrust: `<boost thrust="12000" />`

---

#### Shield Macro Example
```xml
<macro name="shield_arg_l_standard_01_mk1_macro" class="shield">
  <component>
    <recharge max="10000" rate="100" delay="2000" />
    <hull threshold="0.25" />
  </component>
</macro>
```

**Extracted Properties:**
- Max capacity: `<recharge max="10000" />`
- Recharge rate: `<recharge rate="100" />`
- Recharge delay: `<recharge delay="2000" />`
- Regeneration threshold: `<hull threshold="0.25" />`

---

#### Ship Macro Example
```xml
<macro name="ship_arg_s_fighter_01_a_macro" class="ship">
  <component>
    <properties>
      <people capacity="1" />
      <hull max="2500" />
      <storage capacity="350" />
    </properties>
    <connections>
      <connection tags="engine small standard">
        <slot size="s" />
      </connection>
      <connection tags="shield small standard">
        <slot size="s" />
      </connection>
    </connections>
  </component>
</macro>
```

**Extracted Properties:**
- Hull: `<hull max="2500" />`
- Cargo capacity: `<storage capacity="350" />`
- Crew: `<people capacity="1" />`
- Equipment slots: `<connections>` with tags and sizes

---

### Attribute Data Types

| Type | Example Attribute | Conversion | Default if Missing |
|------|-------------------|------------|-------------------|
| **Integer** | `people`, `hull` | `(int)$value` | `0` |
| **Float** | `forward`, `max`, `rate` | `(float)$value` | `0.0` |
| **String** | `name`, `id`, `tags` | `(string)$value` | `''` |
| **Boolean** | `visible`, `enabled` | `$value === 'true'` | `false` |
| **String Array** | `tags="engine large"` | `explode(' ', $value)` | `[]` |

### Required vs Optional Attributes

**Required (use `requireFirst()`):**
- Properties that must exist for item to be valid
- Throw exception if missing
- Examples: `hull.max`, `engine.forward`, `shield.max`

**Optional (use `getFirst()`):**
- Properties with default values
- Return `null` if missing, provide fallback
- Examples: `boost.duration`, `travel.charge`

**Pattern:**
```php
// Required - will throw if missing
$hull = (int)$dom->byTagName('hull')
    ->requireFirst()
    ->getAttribute('max');

// Optional - null fallback
$boostNode = $dom->byTagName('boost')->getFirst();
$boostDuration = $boostNode ? (int)$boostNode->getAttribute('duration') : 0;
```

---

## 📚 Related Documents

- [AGENTS.md](../../AGENTS.md) - AI agent operating system and workflow
- [tech-stack.md](../tech-stack.md) - Architectural patterns (Extraction-Builder pattern)
- [data-flows.md](../data-flows.md) - Database Build Flow diagrams
- [public-api.md](../public-api.md) - Extractor class signatures
- [constraints.md](../constraints.md) - Code conventions and rules
- [file-tree.md](../file-tree.md) - Complete source file locations

---

**Document Status:** ✅ Complete  
**Created:** February 9, 2026  
**Last Updated:** February 9, 2026
