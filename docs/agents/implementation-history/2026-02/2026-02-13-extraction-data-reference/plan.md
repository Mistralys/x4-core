# Extraction Logic and Data Location Reference - Implementation Plan

**Version:** 1.1  
**Created:** February 9, 2026  
**Updated:** February 9, 2026  
**Status:** ✅ Split into Work Packages  
**Objective:** Create exhaustive technical reference for X4 data extraction patterns and XML data locations

---

## 📦 Work Package Status

**This plan has been split into 6 self-contained work packages for incremental implementation.**

**Location:** [/docs/agents/plans/extraction-data-reference/](extraction-data-reference/)

### Work Package Index

| WP# | Name | Status | Est. Time | Dependencies |
|-----|------|--------|-----------|--------------|
| **WP1** | [Foundation & XML Sources](extraction-data-reference/wp1-foundation-xml-sources.md) | � Complete | 2-3 hrs | None |
| **WP2** | [Core Extraction Patterns](extraction-data-reference/wp2-core-extraction-patterns.md) | 🟢 Complete | 3-4 hrs | WP1 |
| **WP3** | [Advanced Features](extraction-data-reference/wp3-advanced-features.md) | � Complete | 2-3 hrs | WP1 |
| **WP4** | [Equipment Compatibility](extraction-data-reference/wp4-equipment-compatibility.md) | � Complete | 2-3 hrs | WP1, WP2 |
| **WP5** | [Developer Support](extraction-data-reference/wp5-developer-support.md) | 🔵 Ready | 4-5 hrs | WP1-4 |
| **WP6** | [Integration & Finalization](extraction-data-reference/wp6-integration-finalization.md) | 🔵 Ready | 1-2 hrs | WP1-5 |

**Total Estimated Time:** 15-20 hours  
**Implementation Order:** WP1 → (WP2 + WP3 parallel) → WP4 → WP5 → WP6

### Quick Start

1. **Start Here:** Read [extraction-data-reference/README.md](extraction-data-reference/README.md)
2. **Begin Implementation:** Start with [WP1](extraction-data-reference/wp1-foundation-xml-sources.md)
3. **Track Progress:** Update work package status as you complete each one

---

## 🎯 Executive Summary

Create a comprehensive extraction reference documenting all extraction patterns, XML data locations, and equipment compatibility algorithms discovered while building the ships/equipment collections. This will serve as the authoritative guide for future extractor development and troubleshooting.

**Deliverable:** `docs/agents/project-manifest/extraction-reference.md` (~8000-10000 words, heavily referenced)

**Purpose:** Capture tribal knowledge from ships/equipment implementation and make future extractor work significantly faster.

**Audience:** AI agents and developers who need to create/maintain extractors or understand data sources.

---

## 📋 Implementation Steps

### **Step 1: Create extraction-reference.md**

**Location:** `docs/agents/project-manifest/extraction-reference.md`

**Content Sections:**
- Document XML file locations for each data type (wares, factions, macros)
- Map game XML structure to extractor classes
- Include file path patterns and examples from each DLC

**Key Information to Include:**
- `{dataFolder}/libraries/wares.xml` - All game items
- `{dataFolder}/libraries/characters.xml` - Factions/races
- `{dataFolder}/index/macros.xml` - Macro name → file path mapping
- `{dataFolder}/assets/props/*/macros/*.xml` - Individual macro definitions

**Dependencies:** None (new file)

**Estimated Lines:** 200-300 for this section

---

### **Step 2: Document Macro Resolution System**

**Content to Document:**
1. **MacroIndex Role**
   - Maps macro names to physical file paths
   - Tracks data source (vanilla, DLC) for each macro
   - Composite ID format: `{dataFolder}::{macroName}`
   - Example: `vanilla::shield_arg_l_standard_01_mk1_macro`

2. **Macro Inheritance Chains**
   - Ships reference parent macros via `<macro alias="parent_macro_name">`
   - Example: `ship_arg_s_fighter_01_a_macro` → `ship_arg_s_fighter_01_macro`
   - Both DOMs loaded, properties resolve child-first with parent fallback

3. **DOM Loading and Property Fallback**
   - Show code from [ShipsExtractor::extractStats()](src/X4/Database/Ships/ShipsExtractor.php#L280-L320)
   - Detail how `DOMExtended` wrapper provides fluent API
   - Document error handling for missing properties

**Source References:**
- [MacroFileDef](src/X4/Database/MacroIndex/MacroFileDef.php)
- [ShipsExtractor](src/X4/Database/Ships/ShipsExtractor.php#L280-L320)
- [MacroIndexExtractor](src/X4/Database/MacroIndex/MacroIndexExtractor.php)

**Estimated Lines:** 300-400

---

### **Step 3: Document Extractor Patterns**

**Patterns to Document:**

1. **Two-Phase Extractor Pattern**
   ```
   Main Extractor (filters collection) → MacroExtractor (per item detail extraction)
   ```
   - Examples: ShieldsExtractor → ShieldMacroExtractor
   - When to use: Complex items requiring macro XML parsing
   - Benefits: Separation of collection filtering and detail extraction

2. **Single-Phase Extractor Pattern**
   - Examples: FactionsExtractor, DataSourcesExtractor
   - When to use: Simple data from library XML files
   - Benefits: Performance, simplicity

3. **Common XPath/DOM Queries**
   Extract from:
   - [ShieldMacroExtractor](src/X4/Database/Shields/ShieldMacroExtractor.php)
   - [EngineMacroExtractor](src/X4/Database/Engines/EngineMacroExtractor.php)
   - [WeaponMacroExtractor](src/X4/Database/Weapons/WeaponMacroExtractor.php)

   Common patterns:
   ```php
   $dom->byTagName('recharge')->getFirst()->getAttribute('max')
   $dom->byTagName('hull')->requireFirst()->getAttribute('threshold')
   $dom->byTagName('identification')->requireFirst()->getAttribute('makerrace')
   ```

4. **XML Attribute Extraction Techniques**
   - Optional vs required (`getFirst()` vs `requireFirst()`)
   - Type conversion (string → float/int)
   - Default values for missing attributes
   - Array extraction (multiple nodes)

5. **Error Handling Patterns**
   - Missing macro files
   - Invalid XML structure
   - Missing required attributes
   - Type conversion failures

**Source References:**
- All extractor classes in [src/X4/Database/](src/X4/Database/)
- [DOMExtended](vendor/mistralys/application-utils/src/XMLHelper/DOMExtended.php)

**Estimated Lines:** 500-600

---

### **Step 4: Document Equipment Compatibility Algorithm**

**Content to Document:**

1. **Tag Matching System**
   - Ships specify required tags in connection XML: `<connection tags="engine large standard" />`
   - Equipment must have ALL specified tags to be compatible
   - Tags extracted from ware groups and macro definitions
   - Implementation in `ShipDef::canEquip()`

2. **Size Filtering**
   - Ship slots specify size requirements (s, m, l, xl)
   - Equipment must match size exactly
   - Implementation in [ShipEquipmentFinder](src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php)

3. **Mixed-Size Slot Handling**
   ```json
   "shields": [
     {"size": "l", "count": 3, "tags": ["large", "shield"]},
     {"size": "m", "count": 9, "tags": ["medium", "shield"]}
   ]
   ```
   - Single ship can have multiple slot definitions for same equipment type
   - Allows mixed equipment loadouts
   - Each slot group has independent size and tag requirements

4. **Equipment Finder API**
   ```php
   // Find compatible engines
   $ship->getEngines()
       ->selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
       ->selectSize('l')
       ->getAll();  // Returns WareDef[]

   // Find compatible shields
   $ship->getShields()
       ->selectTag('standard')
       ->selectMinCapacity(10000)
       ->getAll();
   ```

5. **Compatibility Flow Diagram**
   ```
   1. Filter by ware group (engines/shields/weapons)
   2. Check ShipDef::canEquip() - validates tags
   3. Apply size filter
   4. Apply data source filter
   5. Apply custom filters (capacity, thrust, etc.)
   6. Return filtered WareDef[] instances
   ```

**Source References:**
- [ShipEquipmentFinder](src/X4/Database/Ships/Equipment/ShipEquipmentFinder.php)
- [ShipDef::canEquip()](src/X4/Database/Ships/ShipDef.php)
- [ShipsExtractor equipment slot extraction](src/X4/Database/Ships/ShipsExtractor.php)

**Estimated Lines:** 400-500

---

### **Step 5: Document Variant ID System**

**Content to Document:**

1. **Variant ID Pattern**
   - Format: `{number}:{letter}:{mk}`
   - Examples:
     - `01:a:mk1` - Standard first variant, Mk1
     - `01:b:mk2` - Second variant, Mk2
     - `02::mk3` - Different model, no letter variant, Mk3

2. **Why Variants Exist**
   - Same display name, different stats
   - Different data sources (vanilla vs DLC)
   - Manufacturer variations
   - Upgrade tiers (Mk1, Mk2, Mk3)

3. **Variant Extraction Logic**
   - Parsed from ware ID suffix
   - Implementation in [VariantID](src/X4/Database/Core/VariantID.php)
   - Used across Ships, Engines, Shields, Weapons

4. **Examples from Collections**
   - **Ships:** `ship_arg_s_fighter_01_a` → `01:a:`
   - **Engines:** `engine_arg_l_allround_01_mk1` → `01::mk1`
   - **Shields:** `shield_arg_l_standard_01_mk2` → `01::mk2`
   - **Weapons:** `weapon_gen_s_laser_01_mk1` → `01::mk1`

**Source References:**
- [VariantID](src/X4/Database/Core/VariantID.php)
- [ShipDef::getVariantID()](src/X4/Database/Ships/ShipDef.php)

**Estimated Lines:** 200-300

---

### **Step 6: Create XML Schema Quick Reference Table**

**Content to Create:**

Table mapping extractors to XML sources:

| Extractor | Source XML Path | Key Nodes | Common Attributes |
|-----------|----------------|-----------|-------------------|
| **MacroIndexExtractor** | `index/macros.xml` | `<macro>` | `name`, `class`, `path` |
| **FactionsExtractor** | `libraries/characters.xml` | `<character>` | `id`, `name`, `race` |
| **WaresExtractor** | `libraries/wares.xml` | `<ware>` | `id`, `name`, `group`, `transport` |
| **EnginesExtractor** | Wares + `assets/props/Engines/macros/*.xml` | `<engine>`, `<boost>`, `<travel>` | `forward`, `reverse`, `boost`, `duration` |
| **ShieldsExtractor** | Wares + `assets/props/ShipUpgrades/macros/*.xml` | `<recharge>`, `<hull>` | `max`, `rate`, `delay`, `threshold` |
| **WeaponsExtractor** | Wares + `assets/props/WeaponSystems/macros/*.xml` | `<bullet>`, `<weapon>`, `<heat>` | `damage`, `range`, `rate`, `heat` |
| **ShipsExtractor** | Wares + `assets/units/*/macros/*.xml` | `<properties>`, `<hull>`, `<storage>` | `people`, `mass`, `drag`, `hull` |
| **ModuleExtractor** | Wares + `assets/structures/*/macros/*.xml` | `<properties>`, `<identification>` | `type`, `makerrace` |
| **BlueprintExtractor** | Wares (ware groups) | `<ware>` | `group` (filtered for blueprints) |

**Additional Details:**
- XPath patterns for frequent queries
- Attribute data types (int, float, string, bool)
- Optional vs required attributes
- Default values when attributes missing

**Estimated Lines:** 300-400

---

### **Step 7: Document Data Source Inheritance**

**Content to Document:**

1. **DLC Override Mechanism**
   - Later DLCs can provide updated macro definitions
   - MacroIndex resolves to most recent version
   - Original data source tracked via data folder ID

2. **Data Source Priority**
   ```
   Load Order (lowest to highest priority):
   1. vanilla
   2. ego_dlc_split
   3. ego_dlc_terran
   4. ego_dlc_pirate
   5. ego_dlc_boron
   6. ego_dlc_timelines
   ```
   - Later sources override earlier sources
   - Used for balance patches and new content

3. **Composite ID Tracking**
   - Format: `{dataFolder}::{macroName}`
   - Example: `ego_dlc_terran::ship_ter_m_frigate_01_a_macro`
   - Allows tracking which DLC provides each macro

4. **Data Source Detection**
   - [DataSourcesExtractor](src/X4/Database/DataSources/DataSourcesExtractor.php) scans folders
   - Identifies installed DLCs
   - Creates [data-sources.json](data/data-sources.json) with IDs and labels

**Source References:**
- [MacroIndexExtractor](src/X4/Database/MacroIndex/MacroIndexExtractor.php)
- [DataSourcesExtractor](src/X4/Database/DataSources/DataSourcesExtractor.php)
- [KnownDataSources](src/X4/Database/DataSources/KnownDataSources.php)

**Estimated Lines:** 200-300

---

### **Step 8: Add Extraction Troubleshooting Section**

**Content to Document:**

1. **Common Errors**
   
   **Missing Macro Error:**
   ```
   Error: Macro 'shield_xyz_macro' not found in MacroIndex
   Cause: Ware references non-existent macro
   Solution: Check ware ID, verify macro exists in game files
   ```

   **Invalid XML Error:**
   ```
   Error: Failed to parse XML at path/to/macro.xml
   Cause: Malformed XML, encoding issues
   Solution: Validate XML with linter, check file encoding
   ```

   **Property Not Found Error:**
   ```
   Error: Required property 'hull.max' not found
   Cause: DOM query failed, missing XML attribute
   Solution: Use requireFirst() for required, getFirst() for optional
   ```

2. **Build Dependency Violations**
   - Error: Collection used before built
   - Cause: Wrong build order in [DatabaseBuilder::build()](src/X4/Database/DatabaseBuilder.php#L121-L139)
   - Solution: Review dependency chain, ensure prerequisites built first

3. **Data Source Detection Issues**
   - Error: DLC not detected
   - Cause: Folder structure mismatch, missing info.json
   - Solution: Verify x4-data-extractor output structure

4. **Debugging Techniques**
   - Enable verbose mode in DatabaseBuilder
   - Use `var_dump()` on DOM queries
   - Check extracted JSON intermediate results
   - Validate against game version

**Estimated Lines:** 300-400

---

### **Step 9: Create Extractor Development Guide**

**Content to Create:**

1. **Step-by-Step: Creating a New Extractor**
   
   **Example: Creating TurretsExtractor**
   
   ```
   Step 1: Create extractor class
   Location: src/X4/Database/Turrets/TurretsExtractor.php
   Extends: BaseExtractor
   
   Step 2: Implement extract() method
   - Load wares collection
   - Filter for ware group 'turret'
   - For each turret, create TurretMacroExtractor
   - Collect results as array
   
   Step 3: Create macro extractor (if needed)
   Location: src/X4/Database/Turrets/TurretMacroExtractor.php
   - Load macro DOM
   - Extract properties (damage, rotation speed, etc.)
   - Return structured array
   
   Step 4: Create collection classes
   - TurretDefs (singleton collection)
   - TurretDef (item with property accessors)
   - TurretFinder (optional, for filtering)
   
   Step 5: Add to DatabaseBuilder
   - Import extractor class
   - Add to build() method in dependency order
   - Test build process
   
   Step 6: Create JSON structure
   Location: data/turrets.json
   - Define schema
   - Test extraction output
   
   Step 7: Update manifest documentation
   - tech-stack.md (if new pattern)
   - public-api.md (add signatures)
   - file-tree.md (add new files)
   - data-flows.md (if new flow)
   - extraction-reference.md (document XML sources)
   ```

2. **When to Use Two-Phase vs Single-Phase**
   
   **Use Two-Phase When:**
   - Data requires macro XML parsing
   - Complex property extraction
   - Multiple DOM queries needed
   - Examples: Engines, Shields, Weapons, Ships
   
   **Use Single-Phase When:**
   - Data entirely in library XML
   - Simple structure, no macro resolution
   - Minimal transformation needed
   - Examples: Factions, DataSources, translations

3. **Adding to Build Dependency Chain**
   
   **Dependency Rules:**
   - MacroIndex must be first (required by all macro extractors)
   - Translations early (needed for labels)
   - Wares before equipment (engines/shields/weapons derive from wares)
   - Ships after equipment (analyzes equipment compatibility)
   
   **Example Dependency Chain:**
   ```php
   // In DatabaseBuilder::build()
   $this->buildMacroIndex();      // 1. Required by all
   $this->buildTranslations();    // 2. Labels
   $this->buildDataSources();     // 3. DLC detection
   $this->buildFactions();        // 4. Makers
   $this->buildWares();           // 5. Base for all items
   $this->buildEngines();         // 6. Ware-derived
   $this->buildShields();         // 7. Ware-derived
   $this->buildWeapons();         // 8. Ware-derived
   $this->buildShips();           // 9. Uses equipment collections
   ```

4. **Testing Extraction Results**
   - Create PHPUnit test in `tests/X4Tests/Suites/Database/`
   - Test collection count: `$this->assertGreaterThan(100, EngineDefs::getInstance()->countItems())`
   - Test specific items: `$this->assertNotNull(EngineDefs::getInstance()->getByID('engine_arg_l_allround_01_mk1'))`
   - Test property values: `$this->assertEquals('l', $engine->getSize())`
   - Validate JSON structure matches expected schema

**Estimated Lines:** 600-800

---

### **Step 10: Update project-manifest README**

**Changes to Make:**

1. **Add to Navigation Table**
   ```markdown
   | **7** | [extraction-reference.md](extraction-reference.md) | XML extraction patterns, data locations | Building extractors, understanding data flow |
   ```

2. **Update Document Priority**
   - Add extraction-reference.md after data-flows.md
   - Position as technical deep-dive for extractor development

3. **Add to "When to Use" Guidance**
   ```markdown
   - **extraction-reference.md**: When creating new extractors, debugging extraction issues, understanding XML data locations
   ```

4. **Update Quick Reference Commands**
   ```markdown
   # Find XML data location for a ware type
   → Open extraction-reference.md, search for ware type in XML Schema table
   
   # Understand macro resolution
   → Open extraction-reference.md, see Macro Resolution System section
   ```

**Files to Modify:**
- [docs/agents/project-manifest/README.md](docs/agents/project-manifest/README.md)

**Estimated Lines Changed:** 50-100

---

## ✅ Verification Checklist

After implementation, verify:

- [ ] All 11 extractors documented in extraction-reference.md
- [ ] All file path references are accurate and link to actual source code
- [ ] XPath examples match actual extraction code patterns
- [ ] Equipment compatibility examples match ShipEquipmentFinder logic
- [ ] Macro resolution examples align with ShipsExtractor implementation
- [ ] XML schema table complete with all extractors and source paths
- [ ] Troubleshooting section covers common errors with solutions
- [ ] Extractor development guide is actionable (can follow steps to create new extractor)
- [ ] Cross-references between extraction-reference.md and other manifest docs work
- [ ] Project manifest README navigation updated
- [ ] No contradictions with existing docs (tech-stack.md, data-flows.md, constraints.md)

---

## 📊 Success Criteria

Successful implementation achieved when:

1. **Completeness:**
   - All extractors documented (MacroIndex, Translations, DataSources, Factions, Wares, Engines, Shields, Weapons, Modules, Blueprints, Ships)
   - All XML source locations mapped
   - All major patterns covered (Two-Phase, Single-Phase, Macro Resolution, Equipment Compatibility)

2. **Usability:**
   - AI agent can create new extractor by following guide
   - Developer can locate XML data for any ware type in <5 minutes
   - Troubleshooting section resolves 80%+ of common errors

3. **Accuracy:**
   - All code examples compile and run
   - All file paths resolve correctly
   - All XPath patterns match actual extraction queries

4. **Integration:**
   - Project manifest README navigation includes extraction-reference.md
   - Cross-references work bidirectionally (extraction-reference ↔ tech-stack ↔ data-flows)
   - No duplication of content from other docs

5. **Maintainability:**
   - Document structure supports incremental updates
   - Clear sections for adding new extractors in future
   - Version tracking and last-updated dates

---

## 🎯 Decisions Made

| Decision | Rationale |
|----------|-----------|
| **Location:** `docs/agents/project-manifest/` | Keeps with other agent-focused technical documentation |
| **Scope:** Extraction layer only | Focuses on x4-data-extractor → JSON, not runtime collection usage |
| **Format:** Markdown with heavy code examples | Matches existing project manifest style, highly scannable |
| **Audience:** AI agents and developers | Primary users are those creating/maintaining extractors |
| **Length:** 8000-10000 words | Comprehensive without being overwhelming, similar to public-api.md |
| **Cross-linking:** Extensive references to source code | Makes document actionable, not just informational |
| **Pattern Focus:** Two-Phase extractor as primary example | Most complex and most common pattern for equipment/ships |
| **Equipment Coverage:** Deep dive on compatibility algorithm | Captures recent insights from ships/equipment implementation |

---

## 📝 Open Questions

- Should we include performance optimization techniques for extractors?
- Do we need a separate XML schema diagram in addition to the table?
- Should troubleshooting include xcatTool unpacking issues?
- Include section on incremental build optimization?

---

## 🔄 Maintenance Protocol

When adding new extractors in the future:

1. **Update extraction-reference.md:**
   - Add to extractor inventory
   - Document XML sources in schema table
   - Add any new patterns discovered
   - Update troubleshooting if new error types found

2. **Cross-reference updates:**
   - Add to tech-stack.md if new architectural pattern
   - Add to data-flows.md if new data flow
   - Add to public-api.md with class signatures
   - Add to file-tree.md with file locations

3. **Test updates:**
   - Verify all examples still work with new extractor
   - Update cross-references if file paths change
   - Validate no contradictions introduced

---

## 💡 Future Enhancements

Potential additions after initial version:

1. **Visual diagrams:** Mermaid flowcharts for extraction process
2. **Performance section:** Optimization techniques, caching strategies
3. **Schema validation:** Add JSON schema validation examples
4. **Integration tests:** Examples of end-to-end extraction testing
5. **Migration guide:** How to update extractors when game version changes
6. **Advanced patterns:** Composite extractors, lazy loading, streaming extraction

---

**Total Estimated Lines:** 3000-4000 in extraction-reference.md  
**Total Estimated Time:** 6-8 hours for comprehensive implementation  
**Priority:** High (captures tribal knowledge before it's forgotten)  
**Dependencies:** None (builds on existing code, doesn't modify)

---

## 📚 Related Documents

- [AGENTS.md](../../AGENTS.md) - AI agent operating system
- [tech-stack.md](../project-manifest/tech-stack.md) - Architectural patterns
- [data-flows.md](../project-manifest/data-flows.md) - Data flow diagrams
- [public-api.md](../project-manifest/public-api.md) - Class signatures
- [constraints.md](../project-manifest/constraints.md) - Code conventions

---

**Plan Status:** ✅ Ready for Implementation  
**Next Steps:** Begin Step 1 - Create extraction-reference.md structure
