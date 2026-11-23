# Work Package 1: Foundation & XML Sources

**Status:** � Complete  
**Completed Lines:** 440  
**Estimated Time:** 2-3 hours  
**Dependencies:** None  
**Output File:** `docs/agents/project-manifest/extraction-reference.md` (create)

---

## 🎯 Objective

Create the foundational extraction reference document with:
1. Document structure and introduction
2. XML file location mappings for all data types
3. Complete XML Schema Quick Reference Table
4. Index of all extractors

This work package establishes the base document that all other WPs will build upon.

---

## 📋 Prerequisites

**Knowledge Required:**
- Understanding of X4 game data structure (XML files)
- Familiarity with the x4-data-extractor project
- Knowledge of project manifest documentation style

**Files to Read Before Starting:**
1. [AGENTS.md](../../../AGENTS.md) - Overall project context
2. [constraints.md](../../project-manifest/constraints.md) - Documentation standards
3. [tech-stack.md](../../project-manifest/tech-stack.md) - Architectural patterns
4. [DatabaseBuilder.php](../../../src/X4/Database/DatabaseBuilder.php) - See build order

**External Project:**
- Review x4-data-extractor output folder structure at `f:\Webserver\www\htdocs\tools\x4-data-extractor\output\`

---

## 📚 Context

### What is Extraction?

The X4 game stores data in compressed CAT/DAT files. The x4-data-extractor project:
1. Unpacks these files to XML
2. Outputs to structured folders (vanilla, ego_dlc_*)
3. X4 Core reads these XMLs and builds JSON files
4. JSON files are loaded into runtime collections

### Data Flow
```
Game Files (CAT/DAT) 
  → x4-data-extractor unpacks to XML
  → X4 Core extractors read XML
  → Build JSON files (data/*.json)
  → Runtime collections load JSON
```

### Why This Document Matters

Current state:
- Extraction logic scattered across 11+ extractor classes
- XML paths hardcoded in various files
- No single source of truth for data locations
- Tribal knowledge from recent ships/equipment implementation needs capturing

This document consolidates all extraction knowledge.

---

## 🔍 Source References

### Extractor Classes to Review

**Location:** `src/X4/Database/*/`

| Extractor | File Path | Purpose |
|-----------|-----------|---------|
| MacroIndexExtractor | [src/X4/Database/MacroIndex/MacroIndexExtractor.php](../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php) | Maps macro names to file paths |
| FactionsExtractor | [src/X4/Database/Factions/FactionsExtractor.php](../../../src/X4/Database/Factions/FactionsExtractor.php) | Extracts race/faction data |
| DataSourcesExtractor | [src/X4/Database/DataSources/DataSourcesExtractor.php](../../../src/X4/Database/DataSources/DataSourcesExtractor.php) | Detects installed DLCs |
| TranslationsExtractor | [src/X4/Database/Translations/TranslationsExtractor.php](../../../src/X4/Database/Translations/TranslationsExtractor.php) | Language files |
| WaresExtractor | [src/X4/Database/Wares/WaresExtractor.php](../../../src/X4/Database/Wares/WaresExtractor.php) | All game items |
| EnginesExtractor | [src/X4/Database/Engines/EnginesExtractor.php](../../../src/X4/Database/Engines/EnginesExtractor.php) | Engine equipment |
| ShieldsExtractor | [src/X4/Database/Shields/ShieldsExtractor.php](../../../src/X4/Database/Shields/ShieldsExtractor.php) | Shield equipment |
| WeaponsExtractor | [src/X4/Database/Weapons/WeaponsExtractor.php](../../../src/X4/Database/Weapons/WeaponsExtractor.php) | Weapon equipment |
| ModulesExtractor | [src/X4/Database/Modules/ModulesExtractor.php](../../../src/X4/Database/Modules/ModulesExtractor.php) | Station modules |
| BlueprintsExtractor | [src/X4/Database/Blueprints/BlueprintsExtractor.php](../../../src/X4/Database/Blueprints/BlueprintsExtractor.php) | Crafting blueprints |
| ShipsExtractor | [src/X4/Database/Ships/ShipsExtractor.php](../../../src/X4/Database/Ships/ShipsExtractor.php) | Ships with equipment slots |

### XML Data Locations

**Base Path:** `f:\Webserver\www\htdocs\tools\x4-data-extractor\output\`

Each data source folder (vanilla, ego_dlc_*) contains:
- `libraries/wares.xml` - All game items
- `libraries/characters.xml` - Factions/races
- `index/macros.xml` - Macro name → file path index
- `assets/props/*/macros/*.xml` - Equipment macro definitions
- `assets/units/*/macros/*.xml` - Ship macro definitions
- `assets/structures/*/macros/*.xml` - Module macro definitions
- `t/0001-L044.xml` - English translations (044 = en_EN)

---

## 🛠️ Implementation Steps

### Step 1: Create Document Structure

**File:** `docs/agents/project-manifest/extraction-reference.md`

Create with this structure:

```markdown
# Extraction Logic and Data Location Reference

**Version:** 1.0  
**Created:** February 9, 2026  
**Last Updated:** February 9, 2026  
**Status:** Active  
**Audience:** AI agents and developers creating/maintaining extractors

---

## 🎯 Purpose

This document provides comprehensive technical reference for:
- X4 game data extraction patterns
- XML source file locations
- Extractor architecture and patterns
- Equipment compatibility algorithms
- Troubleshooting extraction issues

**Use this document when:**
- Creating new extractor classes
- Understanding XML data structure
- Debugging extraction failures
- Locating game data for specific ware types
- Understanding equipment compatibility logic

---

## 📖 Table of Contents

1. [Overview](#overview)
2. [XML Data Sources](#xml-data-sources)
3. [Extractor Inventory](#extractor-inventory)
4. [XML Schema Quick Reference](#xml-schema-quick-reference)
5. [Macro Resolution System](#macro-resolution-system) *(WP2)*
6. [Extractor Patterns](#extractor-patterns) *(WP2)*
7. [Variant ID System](#variant-id-system) *(WP3)*
8. [Data Source Inheritance](#data-source-inheritance) *(WP3)*
9. [Equipment Compatibility Algorithm](#equipment-compatibility-algorithm) *(WP4)*
10. [Troubleshooting Guide](#troubleshooting-guide) *(WP5)*
11. [Extractor Development Guide](#extractor-development-guide) *(WP5)*

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
- Extractor patterns and architecture
- DOM querying and property extraction

**OUT OF SCOPE:**
- Step 2: XCatTool unpacking (see x4-data-extractor docs)
- Step 4: Runtime collection usage (see data-flows.md)
- Game file structure (CAT/DAT internals)

---

## 📂 XML Data Sources

*(Content to be added in this step)*

---

## 📋 Extractor Inventory

*(Content to be added in this step)*

---

## 🗺️ XML Schema Quick Reference

*(Content to be added in this step)*

---

## 📚 Related Documents

- [AGENTS.md](../../AGENTS.md) - AI agent operating system
- [tech-stack.md](tech-stack.md) - Architectural patterns
- [data-flows.md](data-flows.md) - Runtime data flow diagrams
- [public-api.md](public-api.md) - Class signatures
- [constraints.md](constraints.md) - Code conventions

---

**Document Status:** 🟡 Work in Progress  
**Completion:** WP1/6
```

### Step 2: Document XML Data Sources

Add this section after "## 📂 XML Data Sources":

```markdown
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
| **Engine Stats** | `assets/props/Engines/macros/*.xml` | EngineMacroExtractor |
| **Shield Stats** | `assets/props/ShipUpgrades/macros/*.xml` | ShieldMacroExtractor |
| **Weapon Stats** | `assets/props/WeaponSystems/macros/*.xml` | WeaponMacroExtractor |
| **Ship Stats** | `assets/units/*/macros/*.xml` | ShipMacroExtractor |
| **Module Stats** | `assets/structures/*/macros/*.xml` | ModuleMacroExtractor |

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

See [Data Source Inheritance](#data-source-inheritance) section for details.
```

### Step 3: Create Extractor Inventory

Add this section after "## 📋 Extractor Inventory":

```markdown
## 📋 Extractor Inventory

### Build Order and Dependencies

Extractors must run in dependency order. From [DatabaseBuilder::build()](../../../src/X4/Database/DatabaseBuilder.php):

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

| # | Extractor | Output File | Dependencies | Pattern | Purpose |
|---|-----------|-------------|--------------|---------|---------|
| **1** | MacroIndexExtractor | macro-index.json | None | Single-Phase | Map macro names to file paths |
| **2** | TranslationsExtractor | lang-*.json (7 files) | None | Single-Phase | Extract UI text for all languages |
| **3** | DataSourcesExtractor | data-sources.json | None | Single-Phase | Detect installed DLCs |
| **4** | FactionsExtractor | factions.json | Translations | Single-Phase | Extract races and factions |
| **5** | WaresExtractor | wares.json | Translations, Factions | Single-Phase | Extract all game items catalog |
| **6** | EnginesExtractor | engines.json | Wares, MacroIndex | Two-Phase | Extract engine equipment |
| **7** | ShieldsExtractor | shields.json | Wares, MacroIndex | Two-Phase | Extract shield equipment |
| **8** | WeaponsExtractor | weapons.json | Wares, MacroIndex | Two-Phase | Extract weapon equipment |
| **9** | ModulesExtractor | modules.json | Wares, MacroIndex | Two-Phase | Extract station modules |
| **10** | BlueprintsExtractor | blueprints.json | Wares | Single-Phase | Extract crafting blueprints |
| **11** | ShipsExtractor | ships.json | Wares, MacroIndex, Engines, Shields, Weapons | Two-Phase | Extract ships with equipment slots |

### Extractor Details

#### 1. MacroIndexExtractor
**Location:** [src/X4/Database/MacroIndex/MacroIndexExtractor.php](../../../src/X4/Database/MacroIndex/MacroIndexExtractor.php)  
**Source XML:** `{dataSource}/index/macros.xml`  
**Output:** [data/macro-index.json](../../../data/macro-index.json)  
**Purpose:** Creates mapping of macro names to physical file paths across all data sources.

**Key Properties Extracted:**
- `name` - Macro identifier (e.g., `engine_arg_l_allround_01_mk1_macro`)
- `class` - Macro type (e.g., `engine`, `shield`, `ship`)
- `path` - Relative file path to macro XML

#### 2. TranslationsExtractor
**Location:** [src/X4/Database/Translations/TranslationsExtractor.php](../../../src/X4/Database/Translations/TranslationsExtractor.php)  
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

#### 3. DataSourcesExtractor
**Location:** [src/X4/Database/DataSources/DataSourcesExtractor.php](../../../src/X4/Database/DataSources/DataSourcesExtractor.php)  
**Source:** Filesystem scan of output folders  
**Output:** [data/data-sources.json](../../../data/data-sources.json)  
**Purpose:** Detect installed DLCs and assign IDs/labels.

**Detection Mechanism:**
- Scans extraction path for folders matching pattern
- Reads `info.json` from each folder
- Assigns IDs: `vanilla`, `ego_dlc_split`, `ego_dlc_terran`, etc.

#### 4. FactionsExtractor
**Location:** [src/X4/Database/Factions/FactionsExtractor.php](../../../src/X4/Database/Factions/FactionsExtractor.php)  
**Source XML:** `{dataSource}/libraries/characters.xml`  
**Output:** [data/factions.json](../../../data/factions.json)  
**Purpose:** Extract faction/race data for maker identification.

**Key Properties:**
- `id` - Faction identifier (e.g., `argon`, `paranid`)
- `name` - Localized faction name
- `race` - Race type

#### 5. WaresExtractor
**Location:** [src/X4/Database/Wares/WaresExtractor.php](../../../src/X4/Database/Wares/WaresExtractor.php)  
**Source XML:** `{dataSource}/libraries/wares.xml`  
**Output:** [data/wares.json](../../../data/wares.json)  
**Purpose:** Extract all game items (master catalog for equipment extractors).

**Key Properties:**
- `id` - Ware identifier (e.g., `engine_arg_l_allround_01_mk1`)
- `name` - Localized ware name
- `group` - Ware type (`engine`, `shield`, `weapon`, `module`, etc.)
- `transport` - Cargo class
- `tags` - String array of tags for filtering

#### 6-11. Equipment and Ship Extractors
See [Extractor Patterns](#extractor-patterns) section for detailed pattern documentation.
```

### Step 4: Create XML Schema Quick Reference Table

Add this section after "## 🗺️ XML Schema Quick Reference":

```markdown
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
// Single required node
$dom->byTagName('engine')->requireFirst()->getAttribute('forward')

// Single optional node
$dom->byTagName('boost')->getFirst()->getAttribute('duration') ?? 0

// All nodes of type
$connections = $dom->byTagName('connection')->getAll()

// Nested query
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
```

---

## ✅ Verification Steps

After completing this work package:

1. **File Created:** `docs/agents/project-manifest/extraction-reference.md` exists
2. **Structure Complete:** All section headers present (including placeholders for future WPs)
3. **XML Sources Documented:** All data source folders and key files mapped
4. **Extractor Inventory Complete:** All 11 extractors listed with details
5. **XML Schema Table Complete:** All extractors have XML mapping entries
6. **Examples Accurate:** All XPath examples match actual extractor code
7. **Cross-References Work:** All file path links resolve correctly
8. **No Contradictions:** Content aligns with tech-stack.md and data-flows.md

### Specific Checks

```bash
# Verify file exists
Test-Path docs/agents/project-manifest/extraction-reference.md

# Check file size (should be 500-700 lines)
(Get-Content docs/agents/project-manifest/extraction-reference.md).Count

# Verify all 11 extractors mentioned
Select-String "Extractor" docs/agents/project-manifest/extraction-reference.md | Measure

# Check for placeholder sections for future WPs
Select-String "\(WP[2-6]\)" docs/agents/project-manifest/extraction-reference.md
```

---

## 📤 Deliverables

1. **File:** `docs/agents/project-manifest/extraction-reference.md` (500-700 lines)
2. **Sections Completed:**
   - Overview
   - XML Data Sources
   - Extractor Inventory
   - XML Schema Quick Reference

3. **Placeholders Added:**
   - Macro Resolution System *(WP2)*
   - Extractor Patterns *(WP2)*
   - Variant ID System *(WP3)*
   - Data Source Inheritance *(WP3)*
   - Equipment Compatibility Algorithm *(WP4)*
   - Troubleshooting Guide *(WP5)*
   - Extractor Development Guide *(WP5)*

---

## 🔄 Next Steps

After WP1 completion:

1. **Update README:** Mark WP1 as complete in `extraction-data-reference/README.md`
2. **Choose Next:** Can proceed with WP2 or WP3 (both can run in parallel)
3. **Recommended:** Start WP2 (Core Extraction Patterns) next for logical flow

**Dependencies Met:**
- WP2 can start (requires WP1)
- WP3 can start (requires WP1)
- WP4 blocked (requires WP1 + WP2)
- WP5 blocked (requires WP1-4)
- WP6 blocked (requires WP1-5)

---

## 📝 Notes

- Keep extraction-reference.md open while working on future WPs
- All future WPs will append to this file
- Maintain consistent formatting with other manifest docs
- Use code blocks for examples, tables for structure
- Link to source files extensively

---

**Work Package Status:** � Complete  
**Created:** February 9, 2026  
**Completed:** February 9, 2026
