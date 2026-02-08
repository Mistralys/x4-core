# Comprehensive Testing Plan - X4 Core Database Layer

> **Version:** 1.0  
> **Created:** February 8, 2026  
> **Status:** Not Started  
> **Goal:** Increase Database layer test coverage from ~15% to ~90%+

---

## 📋 Executive Summary

### Current State
- **Overall Coverage:** ~11%
- **Database Layer:** ~15% (12/80+ classes tested)
- **Critical Gaps:** 2 data types untested (DataSources, MacroIndex), 6 partially tested
- **Test Files:** 12 total

### Target State
- **Overall Coverage:** ~60%+ (Database + Core focused)
- **Database Layer:** ~90%+ (all 8 data types comprehensively tested)
- **Test Files:** ~60+ total (50 new files)
- **Patterns Covered:** Collection-Item, Finder, Extraction-Builder

### Scope
- ✅ **Database Layer:** All 8 data types (Blueprints, DataSources, Factions, MacroIndex, Modules, Ships, Translations, Wares)
- ✅ **Core Abstractions:** VariantID, DatabaseBuilder, traits/interfaces
- ❌ **UI Layer:** Out of scope (separate initiative)
- ❌ **XML Layer:** Out of scope (separate initiative)
- ❌ **Page System:** Out of scope (separate initiative)

### Test Philosophy
- **Comprehensive:** Every public method tested
- **Edge Cases:** Null inputs, missing data, invalid IDs
- **Pattern Validation:** Ensure architectural patterns work correctly
- **Data Integrity:** Cross-references between collections validated
- **No Regressions:** All existing tests must continue passing

---

## 📦 Work Packages Overview

| # | Package | Files | Status | Dependencies | Estimated Effort |
|---|---------|-------|--------|--------------|------------------|
| **WP-01** | Core Abstractions | 2 new | ⬜ Not Started | None | 4-6 hours |
| **WP-02** | DataSources Testing | 3 new | ⬜ Not Started | None | 3-4 hours |
| **WP-03** | MacroIndex Testing | 2 new | ⬜ Not Started | None | 2-3 hours |
| **WP-04** | Factions Expansion | 2 new, 1 enhance | ⬜ Not Started | None | 4-5 hours |
| **WP-05** | Translations Expansion | 2 new, 1 enhance | ⬜ Not Started | None | 4-5 hours |
| **WP-06** | Wares Expansion | 3 new, 2 keep | ⬜ Not Started | WP-03, WP-04 | 5-6 hours |
| **WP-07** | Ships Expansion | 3 new, 2 keep | ⬜ Not Started | WP-04 | 5-6 hours |
| **WP-08** | Modules Expansion | 3 new, 1 enhance | ⬜ Not Started | WP-04 | 5-6 hours |
| **WP-09** | Blueprints Expansion | 18 new, 1 enhance | ⬜ Not Started | WP-06, WP-07, WP-08 | 12-16 hours |
| **WP-10** | Extractors & Builders | 9 new | ⬜ Not Started | WP-02 through WP-09 | 8-10 hours |

**Total Estimated Effort:** 52-67 hours

---

## 🎯 Work Package Definitions

### **WP-01: Core Abstractions Testing** ⬜

**Objective:** Test foundational infrastructure used by all collections

**Status:** Not Started  
**Priority:** High (foundation for other packages)  
**Dependencies:** None  
**Effort:** 4-6 hours

#### Context
- **Pattern:** Collection-Item pattern relies on VariantID for game object identification
- **Location:** Core abstractions are in `src/X4/` (VariantID) and `src/X4/Database/Builder/` (DatabaseBuilder)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Collection-Item Pattern, [public-api.md](../project-manifest/public-api.md) - Database namespace

#### Files to Create

**1. `tests/X4Tests/Suites/Core/VariantIDTests.php`**
```php
Test cases:
- test_fromID_standardFormat(): ship_arg_l_fighter_01_a_mk2
- test_fromID_noQualifier(): ship_arg_l_fighter_01_mk2
- test_fromID_noMark(): ship_arg_l_fighter_01_a
- test_fromID_minimalFormat(): ship_arg_l_fighter_01
- test_getNumber(): Returns "01" from ship_arg_l_fighter_01_a_mk2
- test_getQualifier(): Returns "a" or null
- test_getMark(): Returns 2 from mk2, null if no mark
- test_hasMark(): Boolean test
- test_hasQualifier(): Boolean test
- test_allMarksConstants(): Validate MARK_1 through MARK_4
- test_appendConstantSuffix(): Converts getWhatever() to WHATEVER
- test_resolveWareVariantID(): Ware-specific variant handling
- test_fromID_invalidFormat(): Exception handling
- test_fromID_nullInput(): Edge case
```

**2. `tests/X4Tests/Suites/Core/DatabaseBuilderTests.php`**
```php
Test cases:
- test_buildOrder(): Verify macros built before wares
- test_allExtractorsExecute(): All 8 extractors run
- test_outputFilesCreated(): All JSON files in data/ created
- test_buildWithMissingSource(): Error handling
- test_buildWithCorruptJSON(): Error handling
- test_rebuildDoesNotFail(): Can rebuild over existing data
```

#### Reference Classes
- `src/X4/Database/VariantID.php`
- `src/X4/Database/Builder/DatabaseBuilder.php`
- `src/X4/Database/Builder/KnownItemsClassGenerator.php`

#### Acceptance Criteria
- [ ] VariantID has 100% method coverage
- [ ] All MARKS constants validated
- [ ] Edge cases (null, invalid formats) handled
- [ ] DatabaseBuilder orchestration tested
- [ ] All tests pass with no regressions
- [ ] PHPUnit reports 0 failures

#### How to Implement
1. Create `tests/X4Tests/Suites/Core/` directory
2. Study `src/X4/Database/VariantID.php` - read all public methods
3. Create VariantIDTests.php extending `X4TestCase`
4. Use existing ship/module IDs from `data/*.json` as test data
5. For DatabaseBuilder, check `composer.json` scripts for build command
6. Run tests: `vendor/bin/phpunit tests/X4Tests/Suites/Core/`

---

### **WP-02: DataSources Testing** ⬜

**Objective:** Achieve 100% coverage for DataSources (currently 0%)

**Status:** Not Started  
**Priority:** High (fundamental reference data)  
**Dependencies:** None  
**Effort:** 3-4 hours

#### Context
- **Data File:** `data/data-sources.json` (8 entries: vanilla + 7 DLCs)
- **Pattern:** Collection-Item pattern (no Finder)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Collection-Item Pattern
- **Current Coverage:** 0% (completely untested)

#### Files to Create

**1. `tests/X4Tests/Suites/Database/DataSources/DataSourceCollectionTests.php`**
```php
Test cases:
- test_getInstance(): Singleton pattern
- test_getAll(): Returns 8 data sources
- test_getByID_vanilla(): Get vanilla data source
- test_getByID_dlc(): Get ego_dlc_boron
- test_getByID_invalid(): Exception for nonexistent ID
- test_idExists(): Validation method
- test_getDefault(): Returns vanilla
- test_collectionNotEmpty(): Basic smoke test
- test_allHaveValidIDs(): No empty/null IDs
```

**2. `tests/X4Tests/Suites/Database/DataSources/DataSourceDefTests.php`**
```php
Test cases:
- test_getID(): Returns string ID
- test_getLabel(): Returns human-readable name
- test_getFolder(): Returns folder name
- test_isExtension_vanilla(): Returns false
- test_isExtension_dlc(): Returns true
- test_toArray(): Serialization
- test_fromArray(): Deserialization
- test_vanillaIsNotExtension(): Validate vanilla flag
- test_allDLCsAreExtensions(): Validate 7 DLCs
```

**3. `tests/X4Tests/Suites/Database/DataSources/KnownDataSourcesTests.php`**
```php
Test cases:
- test_getVanilla(): Returns vanilla data source
- test_getDLCBoron(): Returns ego_dlc_boron
- test_getDLCSplit(): Returns ego_dlc_split
- test_getDLCTerran(): Returns ego_dlc_terran
- test_getDLCPirate(): Returns ego_dlc_pirate
- test_getDLCTimelines(): Returns ego_dlc_timelines
- test_getDLCMini01(): Returns ego_dlc_mini_01
- test_getDLCMini02(): Returns ego_dlc_mini_02
- test_allConstantsExist(): Validate constants match data file
- test_allDataSourcesHaveGetter(): Reverse validation
- test_DLCs_getAll(): Returns all 7 DLCs
- test_DLCs_getByID(): Get specific DLC
```

#### Reference Classes
- `src/X4/Database/DataSources/DataSourceDefs.php` (Collection)
- `src/X4/Database/DataSources/DataSourceDef.php` (Item)
- `src/X4/Database/DataSources/KnownDataSources.php` (Constants + Getters)
- `src/X4/Database/DataSources/DLCs.php` (Utility)

#### Test Data
Check `data/data-sources.json` for actual IDs and structure:
```json
{
  "vanilla": {...},
  "ego_dlc_boron": {...},
  "ego_dlc_split": {...},
  etc.
}
```

#### Acceptance Criteria
- [ ] 3 test files created in correct directory
- [ ] DataSourceDefs: 100% method coverage
- [ ] DataSourceDef: 100% method coverage
- [ ] KnownDataSources: All 8 getters tested
- [ ] DLCs utility: All methods tested
- [ ] Edge cases: Invalid IDs throw exceptions
- [ ] All tests pass
- [ ] No existing tests broken

#### How to Implement
1. Create directory: `tests/X4Tests/Suites/Database/DataSources/`
2. Read `data/data-sources.json` to understand structure
3. Study `src/X4/Database/DataSources/*.php` for public API
4. Copy patterns from `tests/X4Tests/Suites/Database/Factions/KnownFactionTests.php`
5. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Database/DataSources/`

---

### **WP-03: MacroIndex Testing** ⬜

**Objective:** Achieve 100% coverage for MacroIndex (currently 0%)

**Status:** Not Started  
**Priority:** Medium (supporting infrastructure)  
**Dependencies:** None  
**Effort:** 2-3 hours

#### Context
- **Data File:** `data/macro-index.json` (maps macros to file paths)
- **Pattern:** Collection-Item pattern (no Finder)
- **Usage:** Used by Wares to resolve macro → file path
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Collection-Item Pattern

#### Files to Create

**1. `tests/X4Tests/Suites/Database/MacroIndex/MacroCollectionTests.php`**
```php
Test cases:
- test_getInstance(): Singleton pattern
- test_getAll(): Returns all macro entries
- test_getByMacro(): Get specific macro file
- test_macroExists(): Validation method
- test_findMacroFile(): Resolve macro to file path
- test_getByMacro_invalid(): Exception for nonexistent macro
- test_collectionNotEmpty(): Smoke test
- test_allMacrosHaveFilePaths(): Data integrity
```

**2. `tests/X4Tests/Suites/Database/MacroIndex/MacroDefTests.php`**
```php
Test cases:
- test_getMacro(): Returns macro name
- test_getFilePath(): Returns file path
- test_getComponent(): Returns component name
- test_toArray(): Serialization
- test_fromArray(): Deserialization
- test_integrationWithWares(): Ware → Macro → File path
```

#### Reference Classes
- `src/X4/Database/MacroIndex/MacroFileDefs.php` (Collection)
- `src/X4/Database/MacroIndex/MacroFileDef.php` (Item)
- `src/X4/Database/MacroIndex/MacroIndexExtractor.php` (Builder)

#### Test Data
Check `data/macro-index.json` for structure and sample macros.

#### Acceptance Criteria
- [ ] 2 test files created
- [ ] MacroFileDefs: 100% method coverage
- [ ] MacroFileDef: 100% method coverage
- [ ] Integration with Wares validated (indirect test)
- [ ] Edge cases: Nonexistent macros handled
- [ ] All tests pass

#### How to Implement
1. Create directory: `tests/X4Tests/Suites/Database/MacroIndex/`
2. Read `data/macro-index.json`
3. Study `src/X4/Database/MacroIndex/*.php`
4. Check `tests/X4Tests/Suites/Database/Wares/WareCollectionTests.php` for `test_getByWareOrMacro()` pattern
5. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Database/MacroIndex/`

---

### **WP-04: Factions Expansion** ⬜

**Objective:** Expand Factions coverage from 20% to 100%

**Status:** Not Started  
**Priority:** High (referenced by Ships, Wares, Modules)  
**Dependencies:** None  
**Effort:** 4-5 hours

#### Context
- **Data File:** `data/factions.json` (35+ factions)
- **Pattern:** Collection-Item pattern with KnownFactions constants
- **Current Coverage:** 20% (only enum validation tested)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Collection-Item Pattern

#### Files to Modify/Create

**1. ENHANCE: `tests/X4Tests/Suites/Database/Factions/KnownFactionTests.php`**
```php
Add test cases:
- test_getArgon(): Returns argon faction
- test_getBoron(): Returns boron faction
- test_getParanid(): Returns paranid faction
- test_getTeladi(): Returns teladi faction
- test_getSplit(): Returns split faction
- test_getTerran(): Returns terran faction
- test_getXenon(): Returns xenon faction
- test_getKhaak(): Returns khaak faction
- ... (test all 35+ individual getters)
- test_getGeneric(): Returns generic faction
```

**2. CREATE: `tests/X4Tests/Suites/Database/Factions/FactionCollectionTests.php`**
```php
Test cases:
- test_getInstance(): Singleton pattern
- test_getAll(): Returns all factions
- test_getByID(): Get specific faction
- test_getByID_invalid(): Exception handling
- test_getDefault(): Returns default faction
- test_detectFactionByID_longID(): argon → argon
- test_detectFactionByID_shortID(): ARG → argon
- test_detectFactionByID_invalid(): Exception
- test_SHORT_ID_MAPPINGS(): Validate all mappings
- test_allFactionsHaveShortIDs(): Data integrity
- test_idExists(): Validation method
```

**3. CREATE: `tests/X4Tests/Suites/Database/Factions/FactionDefTests.php`**
```php
Test cases:
- test_getID(): Returns faction ID
- test_getName(): Returns faction name
- test_getShortIDs(): Returns array of short IDs
- test_getDataSourceID(): Returns data source
- test_isGeneric(): Generic faction detection
- test_toArray(): Serialization
- test_fromArray(): Deserialization
- test_crossReference_ships(): Faction → Ships
- test_crossReference_wares(): Faction → Wares
- test_crossReference_modules(): Faction → Modules
```

#### Reference Classes
- `src/X4/Database/Factions/FactionDefs.php` (Collection)
- `src/X4/Database/Factions/FactionDef.php` (Item)
- `src/X4/Database/Factions/KnownFactions.php` (Constants + 35+ getters)

#### Test Data
Check `data/factions.json` for all faction IDs and short ID mappings.

#### Acceptance Criteria
- [ ] KnownFactionTests enhanced with all getter tests
- [ ] 2 new test files created
- [ ] FactionDefs: 100% method coverage
- [ ] FactionDef: 100% method coverage
- [ ] All 35+ faction getters tested
- [ ] Short ID detection tested
- [ ] Cross-references validated
- [ ] All tests pass

#### How to Implement
1. Study `src/X4/Database/Factions/KnownFactions.php` - list all getter methods
2. Add tests for each getter in KnownFactionTests.php
3. Create FactionCollectionTests.php - follow pattern from DataSourceCollectionTests
4. Create FactionDefTests.php
5. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Database/Factions/`

---

### **WP-05: Translations Expansion** ⬜

**Objective:** Expand Translations coverage from 15% to 100%

**Status:** Not Started  
**Priority:** Medium (supporting feature, not core data)  
**Dependencies:** None  
**Effort:** 4-5 hours

#### Context
- **Data Files:** `data/lang-*.json` (7 languages: EN, DE, FR, ES, IT, RU, KO)
- **Pattern:** Collection-Item pattern with Languages collection
- **Current Coverage:** 15% (only ts() helper tested, only English)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Collection-Item Pattern

#### Files to Modify/Create

**1. ENHANCE: `tests/X4Tests/Suites/Translations/TranslationTests.php`**
```php
Add test cases:
- test_translate_allLanguages(): Test ts() with all 7 languages
- test_translate_missingTranslation(): Fallback behavior
- test_translate_invalidCode(): Edge case
- test_translationCode_parsing(): {123456} format
- test_translationCode_withoutBraces(): 123456 format
- test_Language_t(): pageID/textID translation method
- test_crossLanguageConsistency(): Same code in all languages
```

**2. CREATE: `tests/X4Tests/Suites/Translations/LanguagesTests.php`**
```php
Test cases:
- test_getAll(): Returns all 7 languages
- test_getByID(): Get specific language by ID
- test_getByLocale(): Get by locale string (en_EN)
- test_getEnglish(): Static factory method
- test_getGerman(): Static factory method
- test_getFrench(): Static factory method
- test_getSpanish(): Static factory method
- test_getItalian(): Static factory method
- test_getRussian(): Static factory method
- test_getKorean(): Static factory method
- test_allLanguageConstants(): Validate IDs 44, 49, 33, 34, 39, 7, 82
- test_Language_getID(): Language item method
- test_Language_getLocale(): Language item method
- test_Language_getFilePath(): Language item method
```

**3. CREATE: `tests/X4Tests/Suites/Translations/TranslationCollectionTests.php`**
```php
Test cases:
- test_getInstance(): Singleton pattern
- test_getTranslation(): Get translation by code
- test_hasTranslation(): Validation method
- test_translationCount(): Number of translations loaded
- test_allLanguagesLoaded(): 7 language files loaded
- test_missingTranslationHandling(): Graceful degradation
```

#### Reference Classes
- `src/X4/Database/Translations/TranslationDefs.php` (Collection)
- `src/X4/Database/Translations/Languages.php` (Collection)
- `src/X4/Database/Translations/Language.php` (Item)

#### Test Data
Check all 7 `data/lang-*.json` files for structure and sample translations.

#### Language IDs
- 044: English (en_EN)
- 049: German (de_DE)
- 033: French (fr_FR)
- 034: Spanish (es_ES)
- 039: Italian (it_IT)
- 007: Russian (ru_RU)
- 082: Korean (ko_KR)

#### Acceptance Criteria
- [ ] TranslationTests enhanced with all 7 languages
- [ ] 2 new test files created
- [ ] All 7 language getters tested
- [ ] TranslationDefs: 100% method coverage
- [ ] Language: 100% method coverage
- [ ] Missing translation fallback tested
- [ ] All tests pass

#### How to Implement
1. Study `src/X4/Database/Translations/*.php`
2. Check `data/lang-*.json` file structure
3. Enhance TranslationTests.php with multi-language tests
4. Create LanguagesTests.php and TranslationCollectionTests.php
5. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Translations/`

---

### **WP-06: Wares Expansion** ⬜

**Objective:** Expand Wares coverage from 35% to 100%

**Status:** Not Started  
**Priority:** High (core game data)  
**Dependencies:** WP-03 (MacroIndex), WP-04 (Factions)  
**Effort:** 5-6 hours

#### Context
- **Data File:** `data/wares.json` (hundreds of wares)
- **Pattern:** Collection-Item-Finder pattern with WareGroups
- **Current Coverage:** 35% (good collection tests, missing Finder, Groups, item details)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Finder Pattern

#### Files to Keep/Create

**KEEP:** `tests/X4Tests/Suites/Database/Wares/WareCollectionTests.php` (5 tests)  
**KEEP:** `tests/X4Tests/Suites/Database/Wares/WareGroupTests.php` (2 tests)

**1. CREATE: `tests/X4Tests/Suites/Database/Wares/WareDefTests.php`**
```php
Test cases:
- test_getID(): Returns ware ID
- test_getName(): Returns ware name
- test_getGroup(): Returns WareGroup object
- test_getTags(): Returns array of tags
- test_hasTag(): Tag validation
- test_getMacro(): Returns macro name
- test_getFactionIDs(): Returns array of faction IDs
- test_getFactions(): Returns FactionDef objects
- test_getDataSourceIDs(): Returns data sources
- test_toArray(): Serialization
- test_fromArray(): Deserialization
- test_getPrice(): Ware-specific property
- test_getVolume(): Ware-specific property
- test_isIllegal(): Ware-specific property
- test_wareWithNoTags(): Edge case
- test_wareWithNoFactions(): Edge case
- test_wareWithNullMacro(): Edge case
```

**2. CREATE: `tests/X4Tests/Suites/Database/Wares/WareFinderTests.php`**
```php
Test cases:
- test_selectLabel(): Filter by label/name
- test_selectLabel_partialMatch(): Partial string matching
- test_selectGroup(): Filter by WareGroup
- test_selectTag(): Filter by tag
- test_selectDataSource(): Filter by DLC
- test_finderChaining(): Multiple filters combined
- test_getAll_afterFilters(): Result collection
- test_emptyResult(): No matches found
- test_complexQuery(): Label + Group + Tag + DataSource
```

**3. CREATE: `tests/X4Tests/Suites/Database/Wares/WareGroupsTests.php`**
```php
Test cases:
- test_getAll(): Returns all 35+ groups
- test_getByID(): Get specific group
- test_getDefault(): Returns default group
- test_idExists(): Validation method
- test_allGroupConstants(): Validate all 35+ constants
- test_WareGroup_getID(): WareGroup item method
- test_WareGroup_getLabel(): WareGroup item method
- test_WareGroup_getWares(): Get wares in group
- test_groupToWaresRelationship(): Data integrity
- test_everWareHasValidGroup(): Reverse validation
```

#### Reference Classes
- `src/X4/Database/Wares/WareDefs.php` (Collection)
- `src/X4/Database/Wares/WareDef.php` (Item)
- `src/X4/Database/Wares/WareFinder.php` (Finder)
- `src/X4/Database/Wares/WareGroups.php` (Collection - 35+ constants)
- `src/X4/Database/Wares/WareGroup.php` (Item)

#### Test Data
Check `data/wares.json` for structure, note groups and tags.

#### Acceptance Criteria
- [ ] 3 new test files created
- [ ] WareDef: 100% method coverage
- [ ] WareFinder: Complete coverage
- [ ] WareGroups: All 35+ groups tested
- [ ] Edge cases: No tags, no factions, null macros
- [ ] Integration: Wares → Factions → MacroIndex
- [ ] All tests pass

#### How to Implement
1. Study `src/X4/Database/Wares/*.php`
2. Reference existing `tests/X4Tests/Suites/Database/Ships/ShipFinderTests.php` for Finder pattern
3. Create WareDefTests.php
4. Create WareFinderTests.php following ShipFinderTests pattern
5. Create WareGroupsTests.php
6. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Database/Wares/`

---

### **WP-07: Ships Expansion** ⬜

**Objective:** Expand Ships coverage from 40% to 100%

**Status:** Not Started  
**Priority:** High (core game data)  
**Dependencies:** WP-04 (Factions)  
**Effort:** 5-6 hours

#### Context
- **Data Files:** `data/ships.json`, `data/ship-settings.json`
- **Pattern:** Collection-Item-Finder pattern with ShipClasses (20+) and ShipSizes (5)
- **Current Coverage:** 40% (good collection and finder tests, missing item details and class/size collections)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Finder Pattern

#### Files to Keep/Create

**KEEP:** `tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php` (3 tests)  
**KEEP:** `tests/X4Tests/Suites/Database/Ships/ShipFinderTests.php` (5 tests)

**1. CREATE: `tests/X4Tests/Suites/Database/Ships/ShipDefTests.php`**
```php
Test cases:
- test_getID(): Returns ship ID
- test_getName(): Returns ship name
- test_getClass(): Returns ShipClass object
- test_getSize(): Returns ShipSize object
- test_getRace(): Returns race/faction
- test_hasVariants(): Variant detection
- test_getVariants(): Returns variant ShipDef objects
- test_getVariantIDs(): Returns variant ID strings
- test_isVariant(): Check if ship is a variant
- test_toArray(): Serialization
- test_fromArray(): Deserialization
- test_getSpeed(): Ship-specific property
- test_getStorage(): Ship-specific property
- test_getCrew(): Ship-specific property
- test_getPurpose(): Ship-specific property (military/industry)
- test_variantRelationships(): Variant → Parent → Variants
```

**2. CREATE: `tests/X4Tests/Suites/Database/Ships/ShipClassesTests.php`**
```php
Test cases:
- test_getAll(): Returns all 20+ ship classes
- test_getByID(): Get specific class
- test_getDefault(): Returns default class
- test_idExists(): Validation method
- test_allClassConstants(): Fighter, Frigate, Destroyer, Carrier, etc.
- test_ShipClass_getID(): ShipClass item method
- test_ShipClass_getLabel(): ShipClass item method
- test_ShipClass_getShips(): Ships in this class
- test_classToShipsRelationship(): Data integrity
- test_everyShipHasValidClass(): Reverse validation
- test_militaryClasses(): Fighter, Corvette, Frigate, Destroyer, Carrier
- test_industrialClasses(): Miner, Trader, Builder
```

**3. CREATE: `tests/X4Tests/Suites/Database/Ships/ShipSizesTests.php`**
```php
Test cases:
- test_getAll(): Returns 5 sizes (XS, S, M, L, XL)
- test_getByID(): Get specific size
- test_idExists(): Validation method
- test_allSizeConstants(): XS, S, M, L, XL
- test_ShipSize_getID(): ShipSize item method
- test_ShipSize_getLabel(): ShipSize item method
- test_ShipSize_getShips(): Ships of this size
- test_sizeToShipsRelationship(): Data integrity
- test_everyShipHasValidSize(): Reverse validation
- test_sizeHierarchy(): XS < S < M < L < XL
```

#### Reference Classes
- `src/X4/Database/Ships/ShipDefs.php` (Collection)
- `src/X4/Database/Ships/ShipDef.php` (Item)
- `src/X4/Database/Ships/ShipFinder.php` (Finder - already well tested)
- `src/X4/Database/Ships/ShipClasses.php` (Collection - 20+ constants)
- `src/X4/Database/Ships/ShipClass.php` (Item)
- `src/X4/Database/Ships/ShipSizes.php` (Collection - 5 constants)
- `src/X4/Database/Ships/ShipSize.php` (Item)
- `src/X4/Database/Ships/KnownShips.php` (Constants)

#### Test Data
Check `data/ships.json` for structure, classes, sizes, variants.

#### Acceptance Criteria
- [ ] 3 new test files created
- [ ] ShipDef: 100% method coverage including variants
- [ ] ShipClasses: All 20+ classes tested
- [ ] ShipSizes: All 5 sizes tested
- [ ] Variant relationships validated
- [ ] Cross-references: Ships → Factions
- [ ] All tests pass

#### How to Implement
1. Study `src/X4/Database/Ships/*.php`
2. Check `data/ships.json` for variant structure
3. Create ShipDefTests.php
4. Create ShipClassesTests.php (similar to WareGroupsTests)
5. Create ShipSizesTests.php (similar to ShipClassesTests)
6. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Database/Ships/`

---

### **WP-08: Modules Expansion** ⬜

**Objective:** Expand Modules coverage from 25% to 100%

**Status:** Not Started  
**Priority:** Medium (important game data)  
**Dependencies:** WP-04 (Factions)  
**Effort:** 5-6 hours

#### Context
- **Data File:** `data/modules.json`
- **Pattern:** Collection-Item-Finder pattern with ModuleCategories
- **Current Coverage:** 25% (basic item tests, no Finder or Categories tests)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Finder Pattern

#### Files to Enhance/Create

**1. ENHANCE: `tests/X4Tests/Suites/Database/Modules/ModuleTests.php`**
```php
Add test cases:
- test_getHull(): Module hull strength
- test_getDroneCapacity(): Drone storage
- test_getCargoCapacity(): Cargo storage
- test_getHousing(): Crew housing
- test_getSize(): Module size
- test_getProduction(): Production capabilities
- test_getCargoType(): Cargo type restriction
- test_isProduction(): Production module detection
- test_isCargo(): Cargo module detection
- test_toArray(): Serialization
- test_fromArray(): Deserialization
```

**2. CREATE: `tests/X4Tests/Suites/Database/Modules/ModuleFinderTests.php`**
```php
Test cases:
- test_selectRace(): Filter by race/faction
- test_selectCategory(): Filter by ModuleCategory
- test_selectLabel(): Filter by label/name
- test_selectSize(): Filter by module size
- test_selectDroneCapacity(): Filter by drone capacity
- test_selectProduction(): Only production modules
- test_selectCargo(): Only cargo modules
- test_finderChaining(): Multiple filters combined
- test_getAll_afterFilters(): Result collection
- test_emptyResult(): No matches found
- test_complexQuery(): Race + Category + Size + Label
```

**3. CREATE: `tests/X4Tests/Suites/Database/Modules/ModuleCollectionTests.php`**
```php
Test cases:
- test_getInstance(): Singleton pattern
- test_getAll(): Returns all modules
- test_getByID(): Get specific module
- test_getDefault(): Returns default module
- test_idExists(): Validation method
- test_find(): Returns ModuleFinder instance
- test_collectionNotEmpty(): Smoke test
- test_allModulesHaveCategories(): Data integrity
```

**4. CREATE: `tests/X4Tests/Suites/Database/Modules/ModuleCategoriesTests.php`**
```php
Test cases:
- test_getAll(): Returns all categories
- test_getByID(): Get specific category
- test_idExists(): Validation method
- test_allCategoryConstants(): Validate all constants
- test_ModuleCategory_getID(): Category item method
- test_ModuleCategory_getLabel(): Category item method
- test_ModuleCategory_getModules(): Modules in category
- test_categoryToModulesRelationship(): Data integrity
- test_everyModuleHasValidCategory(): Reverse validation
```

#### Reference Classes
- `src/X4/Database/Modules/ModuleDefs.php` (Collection)
- `src/X4/Database/Modules/ModuleDef.php` (Item)
- `src/X4/Database/Modules/ModuleFinder.php` (Finder - untested)
- `src/X4/Database/Modules/ModuleCategories.php` (Collection)
- `src/X4/Database/Modules/ModuleCategory.php` (Item)

#### Test Data
Check `data/modules.json` for structure, categories, sizes.

#### Acceptance Criteria
- [ ] ModuleTests.php enhanced with all item methods
- [ ] 3 new test files created
- [ ] ModuleFinder: Complete coverage
- [ ] ModuleCategories: All categories tested
- [ ] ModuleDef: 100% method coverage
- [ ] Cross-references: Modules → Factions
- [ ] All tests pass

#### How to Implement
1. Study `src/X4/Database/Modules/*.php`
2. Enhance ModuleTests.php with missing item methods
3. Create ModuleFinderTests.php following Ship/Ware Finder pattern
4. Create ModuleCollectionTests.php
5. Create ModuleCategoriesTests.php following WareGroups pattern
6. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Database/Modules/`

---

### **WP-09: Blueprints Expansion** ⬜

**Objective:** Expand Blueprints coverage from 15% to 100%

**Status:** Not Started  
**Priority:** High (complex game data)  
**Dependencies:** WP-06 (Wares), WP-07 (Ships), WP-08 (Modules)  
**Effort:** 12-16 hours

#### Context
- **Data File:** `data/blueprints.json`
- **Pattern:** Collection-Item pattern with 16 categories and BlueprintSelection
- **Current Coverage:** 15% (only Ship blueprints and Unknown category tested)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Collection-Item Pattern
- **Challenge:** 16 distinct blueprint types with category-specific properties

#### Files to Enhance/Create

**1. ENHANCE: `tests/X4Tests/Suites/Database/Blueprints/ShipBlueprintTests.php`**
```php
Add comprehensive tests:
- test_getWare(): Returns associated ware
- test_getShip(): Returns ship definition
- test_getPrimaryResource(): Ship blueprint resources
- test_getCategory(): Returns ShipCategory
- test_toArray(): Serialization
- test_fromArray(): Deserialization
- test_variantBlueprints(): Variant ship blueprints
```

**2. CREATE: `tests/X4Tests/Suites/Database/Blueprints/BlueprintCollectionTests.php`**
```php
Test cases:
- test_getInstance(): Singleton pattern
- test_getAll(): Returns all blueprints
- test_getByID(): Get specific blueprint
- test_getCategories(): Returns BlueprintCategories
- test_getBlueprintTypes(): Returns all type classes
- test_filterByCategory(): Filter blueprints
- test_filterByType(): Filter blueprints
- test_BlueprintSelection(): Selection utility
- test_collectionNotEmpty(): Smoke test
- test_allBlueprintsHaveCategories(): Data integrity
```

**3. CREATE: 15 Category-Specific Test Files**

Create test file for each untested category following this template:

**`tests/X4Tests/Suites/Database/Blueprints/{Category}BlueprintTests.php`**

Categories to create (15 total):
- `CountermeasureBlueprintTests.php`
- `DeployableBlueprintTests.php`
- `DroneBlueprintTests.php`
- `EngineBlueprintTests.php`
- `MineBlueprintTests.php`
- `MissileBlueprintTests.php`
- `ModificationBlueprintTests.php`
- `ModuleBlueprintTests.php`
- `ShieldBlueprintTests.php`
- `SkinBlueprintTests.php`
- `ThrusterBlueprintTests.php`
- `TurretBlueprintTests.php`
- `WeaponBlueprintTests.php`
- `WelfareBlueprintTests.php`

Each test file follows this structure:
```php
Test cases:
- test_categoryDetection(): Correct category assigned
- test_getWare(): Returns associated ware
- test_getCategory(): Returns correct category object
- test_categorySpecificProperties(): Test category-unique getters
  * Weapon: getDamage(), getRange(), getFireRate()
  * Engine: getThrust(), getBoost()
  * Shield: getCapacity(), getRechargeRate()
  * Module: getHull(), getProduction()
  * Thruster: getForce(), getDirectionalThrust()
  * Turret: getRotation(), getElevation()
  * etc.
- test_toArray(): Serialization
- test_allBlueprintsInCategory(): All blueprints of this type
```

**4. CREATE: `tests/X4Tests/Suites/Database/Blueprints/BlueprintCategoriesTests.php`**
```php
Test cases:
- test_getAll(): Returns all 16 categories
- test_getByID(): Get specific category
- test_idExists(): Validation method
- test_allCategoryConstants(): All 16 validated
- test_BlueprintCategory_getID(): Category item method
- test_BlueprintCategory_getLabel(): Category item method
- test_BlueprintCategory_getBlueprints(): Blueprints in category
- test_CategorySelection(): Category selection utility
- test_categoryToWareRelationship(): Blueprint → Ware → Ship/Module
```

#### Blueprint Category IDs
1. `ship` - ShipBlueprint (already tested)
2. `countermeasure` - CountermeasureBlueprint
3. `deployable` - DeployableBlueprint
4. `drone` - DroneBlueprint
5. `engine` - EngineBlueprint
6. `mine` - MineBlueprint
7. `missile` - MissileBlueprint
8. `modification` - ModificationBlueprint
9. `module` - ModuleBlueprint
10. `shield` - ShieldBlueprint
11. `skin` - SkinBlueprint
12. `thruster` - ThrusterBlueprint
13. `turret` - TurretBlueprint
14. `weapon` - WeaponBlueprint
15. `welfare` - WelfareBlueprint
16. `unknown` - UnknownBlueprint (already tested)

#### Reference Classes
- `src/X4/Database/Blueprints/BlueprintDefs.php` (Collection)
- `src/X4/Database/Blueprints/BlueprintDef.php` (Abstract Item)
- `src/X4/Database/Blueprints/BlueprintSelection.php`
- `src/X4/Database/Blueprints/BlueprintCategories.php` (Collection)
- `src/X4/Database/Blueprints/BaseBlueprintCategory.php`
- `src/X4/Database/Blueprints/*/{Type}Blueprint.php` (16 types)
- `src/X4/Database/Blueprints/*/{Type}Category.php` (16 categories)

#### Test Data
Check `data/blueprints.json` for structure. Note: Each blueprint has a `category` field determining its type.

#### Acceptance Criteria
- [ ] ShipBlueprintTests enhanced
- [ ] BlueprintCollectionTests created
- [ ] 15 new category test files created
- [ ] BlueprintCategoriesTests created
- [ ] All 16 blueprint types covered
- [ ] BlueprintSelection tested
- [ ] Category-specific properties validated
- [ ] Cross-references: Blueprints → Wares → Ships/Modules
- [ ] All tests pass

#### How to Implement
1. Study `src/X4/Database/Blueprints/` directory structure
2. Check `data/blueprints.json` - note category values
3. Enhance ShipBlueprintTests.php
4. Create BlueprintCollectionTests.php
5. For each category:
   - Study `src/X4/Database/Blueprints/*/{Type}Blueprint.php`
   - Note category-specific getters (damage, thrust, capacity, etc.)
   - Create test file with category-specific tests
   - Run tests: `vendor/bin/phpunit tests/X4Tests/Suites/Database/Blueprints/{Type}BlueprintTests.php`
6. Create BlueprintCategoriesTests.php
7. Run full suite: `vendor/bin/phpunit tests/X4Tests/Suites/Database/Blueprints/`

**Note:** This is the largest work package (18 files). Consider breaking into sub-packages if needed:
- WP-09a: Collection + ShipBlueprint enhancement (2 files)
- WP-09b: Combat categories (Weapon, Missile, Turret, Countermeasure, Mine) (5 files)
- WP-09c: Ship components (Engine, Shield, Thruster) (3 files)
- WP-09d: Station components (Module, Welfare) (2 files)
- WP-09e: Misc categories (Deployable, Drone, Modification, Skin) (4 files)
- WP-09f: Categories collection (1 file)

---

### **WP-10: Extractors & Builders** ⬜

**Objective:** Achieve coverage for data extraction infrastructure

**Status:** Not Started  
**Priority:** Medium (infrastructure validation)  
**Dependencies:** WP-02 through WP-09 (all data types)  
**Effort:** 8-10 hours

#### Context
- **Purpose:** Validate data extraction from X4 game files to JSON
- **Pattern:** Builder and Extractor classes
- **Current Coverage:** 0% (completely untested)
- **Reference:** [tech-stack.md](../project-manifest/tech-stack.md) - Extraction-Builder Pattern
- **Note:** Requires x4-data-extractor workspace for full testing

#### Files to Create

**1. `tests/X4Tests/Suites/Database/Extractors/DataSourcesExtractorTests.php`**
```php
Test cases:
- test_extract(): Runs extraction
- test_outputFileCreated(): data/data-sources.json exists
- test_extractedDataValid(): Valid JSON structure
- test_allExpectedFieldsPresent(): id, label, folder, isExtension
- test_vanillaIncluded(): Vanilla entry exists
- test_allDLCsIncluded(): 7 DLCs extracted
```

**2-8. Repeat for all extractors:**
- `MacroIndexExtractorTests.php`
- `BlueprintExtractorTests.php`
- `FactionsExtractorTests.php`
- `ModuleExtractorTests.php`
- `ShipsExtractorTests.php`
- `TranslationExtractorTests.php`
- `WaresExtractorTests.php`

Each follows similar pattern:
```php
Test cases:
- test_extract(): Runs extraction
- test_outputFileCreated(): data/{type}.json exists
- test_extractedDataValid(): Valid JSON
- test_dataIntegrity(): All expected records present
- test_extractionFromGameFiles(): Read from x4-data-extractor output
- test_transformationLogic(): Data properly transformed
- test_errorHandling(): Missing source files handled
```

**9. `tests/X4Tests/Suites/Core/DatabaseBuilderTests.php`** (Enhanced from WP-01)
```php
Add integration tests:
- test_buildAll(): Builds all 8 data types
- test_buildOrder(): Correct dependency order (macros → wares, etc.)
- test_allExtractorsExecuted(): All 9 extractors run
- test_allOutputFilesCreated(): All data/*.json files created
- test_buildTime(): Performance validation (< X seconds)
- test_rebuildIdempotent(): Rebuild produces same results
- test_partialBuild(): Build specific data types only
```

#### Reference Classes
- `src/X4/Database/Builder/DatabaseBuilder.php`
- `src/X4/Database/*/Extractor/*Extractor.php` (9 extractors)
- `src/X4/Database/Builder/KnownItemsClassGenerator.php`

#### Integration Context
Extractors read from `x4-data-extractor` workspace output:
- `x4-data-extractor/output/vanilla/`
- `x4-data-extractor/output/ego_dlc_*/`

#### Acceptance Criteria
- [ ] 9 extractor test files created (1 per data type)
- [ ] DatabaseBuilderTests enhanced with integration tests
- [ ] All extractors validated
- [ ] Build orchestration tested
- [ ] Output file validation tested
- [ ] Error handling tested
- [ ] All tests pass

#### How to Implement
1. Study `src/X4/Database/Builder/DatabaseBuilder.php`
2. Check `composer.json` for build scripts
3. Study each `*Extractor.php` file
4. Create test files following pattern above
5. For integration tests, ensure `x4-data-extractor` workspace available
6. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Database/Extractors/`
7. Run: `vendor/bin/phpunit tests/X4Tests/Suites/Core/DatabaseBuilderTests.php`

**Note:** Some tests may require actual X4 game data from x4-data-extractor. Consider mocking if game data unavailable.

---

## 📊 Progress Tracking

### Overall Status
```
Total Work Packages: 10
Completed: 0
In Progress: 0
Not Started: 10
Overall Progress: 0%
```

### Coverage Metrics

| Data Type | Before | Target | Current | Status |
|-----------|--------|--------|---------|--------|
| **Blueprints** | 15% | 95% | 15% | ⬜ Not Started |
| **DataSources** | 0% | 100% | 0% | ⬜ Not Started |
| **Factions** | 20% | 100% | 20% | ⬜ Not Started |
| **MacroIndex** | 0% | 100% | 0% | ⬜ Not Started |
| **Modules** | 25% | 95% | 25% | ⬜ Not Started |
| **Ships** | 40% | 95% | 40% | ⬜ Not Started |
| **Translations** | 15% | 90% | 15% | ⬜ Not Started |
| **Wares** | 35% | 95% | 35% | ⬜ Not Started |
| **Core** | 0% | 85% | 0% | ⬜ Not Started |
| **Extractors** | 0% | 75% | 0% | ⬜ Not Started |

### Test File Count
```
Current:  12 test files
Target:   ~62 test files
Progress: 12/62 (19%)
```

---

## 🎯 Implementation Guidelines

### General Principles

1. **Follow Existing Patterns**
   - Study existing test files in `tests/X4Tests/Suites/`
   - Copy structure from similar tests (Collection → Collection, Finder → Finder)
   - Use `X4TestCase` as base class

2. **Naming Conventions**
   - Test files: `{ClassName}Tests.php`
   - Test methods: `test_{methodName}()` or `test_{featureName}()`
   - Be descriptive: `test_getByID_invalid()` not `test_error()`

3. **Test Structure**
   ```php
   public function test_feature(): void
   {
       // Arrange - Set up test data
       $collection = SomeDefs::getInstance();
       
       // Act - Execute the method
       $result = $collection->getByID('some_id');
       
       // Assert - Verify expectations
       $this->assertNotNull($result);
       $this->assertEquals('some_id', $result->getID());
   }
   ```

4. **Use Real Data**
   - Reference actual IDs from `data/*.json` files
   - Don't hardcode fake data - use real game data
   - Check JSON files to see what's available

5. **Edge Cases Matter**
   - Test null inputs
   - Test nonexistent IDs (expect exceptions)
   - Test empty collections
   - Test boundary conditions

6. **Cross-References**
   - Test relationships: Wares → MacroIndex, Ships → Factions
   - Validate data integrity across collections

### Running Tests

**Single test file:**
```bash
vendor/bin/phpunit tests/X4Tests/Suites/Database/DataSources/DataSourceCollectionTests.php
```

**Directory:**
```bash
vendor/bin/phpunit tests/X4Tests/Suites/Database/DataSources/
```

**All tests:**
```bash
vendor/bin/phpunit
```

**With coverage:**
```bash
vendor/bin/phpunit --coverage-html coverage/
```

### Verification Checklist

For each work package:
- [ ] All test files created in correct directory
- [ ] All tests pass individually
- [ ] All tests pass as suite
- [ ] No existing tests broken
- [ ] PHPStan passes (no type errors)
- [ ] Code follows project conventions (see `docs/agents/project-manifest/constraints.md`)
- [ ] Tests use real data from JSON files
- [ ] Edge cases covered
- [ ] Cross-references validated

---

## 📚 Key Reference Documents

Before implementing any work package, consult:

1. **[AGENTS.md](../../AGENTS.md)** - Operating procedures, manifest-first approach
2. **[README.md](../project-manifest/README.md)** - Project overview
3. **[tech-stack.md](../project-manifest/tech-stack.md)** - Architectural patterns
4. **[constraints.md](../project-manifest/constraints.md)** - Rules and conventions
5. **[public-api.md](../project-manifest/public-api.md)** - All public methods
6. **[file-tree.md](../project-manifest/file-tree.md)** - File locations

### Quick Links to Source Code

**Collections (one per data type):**
- `src/X4/Database/Blueprints/BlueprintDefs.php`
- `src/X4/Database/DataSources/DataSourceDefs.php`
- `src/X4/Database/Factions/FactionDefs.php`
- `src/X4/Database/MacroIndex/MacroFileDefs.php`
- `src/X4/Database/Modules/ModuleDefs.php`
- `src/X4/Database/Ships/ShipDefs.php`
- `src/X4/Database/Translations/TranslationDefs.php`
- `src/X4/Database/Wares/WareDefs.php`

**Data Files:**
- `data/blueprints.json`
- `data/data-sources.json`
- `data/factions.json`
- `data/macro-index.json`
- `data/modules.json`
- `data/ships.json`
- `data/lang-*.json` (7 languages)
- `data/wares.json`

**Existing Tests (for patterns):**
- `tests/X4Tests/Suites/Database/Ships/ShipFinderTests.php` - Finder pattern
- `tests/X4Tests/Suites/Database/Wares/WareCollectionTests.php` - Collection pattern
- `tests/X4Tests/Suites/Database/Factions/KnownFactionTests.php` - Constants pattern

---

## 🚀 Getting Started

### For First-Time Implementation

1. **Read the manifest** (15-20 minutes)
   - [AGENTS.md](../../AGENTS.md)
   - [tech-stack.md](../project-manifest/tech-stack.md)
   - [constraints.md](../project-manifest/constraints.md)

2. **Choose a work package** (recommend WP-02: DataSources for simplicity)

3. **Study the pattern**
   - Read relevant source files in `src/X4/Database/{DataType}/`
   - Check existing tests for similar patterns
   - Check `data/{datatype}.json` for structure

4. **Implement incrementally**
   - Create one test file at a time
   - Run tests after each file
   - Fix failures before moving on

5. **Validate thoroughly**
   - Run full test suite
   - Check coverage report
   - Update this document's progress tracking

### For Resuming Work

If picking up this plan after time away:

1. **Check progress tracking tables** (above)
2. **Identify next work package** (first with "Not Started")
3. **Read that work package section** completely
4. **Check dependencies** (previous packages completed?)
5. **Follow implementation steps** in work package
6. **Update progress tracking** when complete

---

## 📝 Notes

### Assumptions
- PHPUnit 9.5+ available (`vendor/bin/phpunit`)
- X4 Core codebase in working state
- All data files in `data/` directory populated
- X4 game data available (for extractor tests)

### Out of Scope for This Plan
- UI Layer testing (separate initiative)
- XML Layer testing (separate initiative)
- Page System testing (separate initiative)
- Performance benchmarking
- Load testing
- Security testing
- Integration testing with external systems

### Future Enhancements
After completing this plan, consider:
- **WP-11:** UI Component Testing (Button, Icon, Text, Console, DataGrid)
- **WP-12:** Page System Testing (BasePage, BasePageWithNav, SubPages)
- **WP-13:** XML Layer Testing (DOMExtended, Finders)
- **WP-14:** Integration Testing (end-to-end workflows)
- **WP-15:** Performance Testing (large collection queries)

---

## 🔄 Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | Feb 8, 2026 | Initial comprehensive testing plan created | AI Agent |

---

**Last Updated:** February 8, 2026  
**Document Owner:** X4 Core Development Team  
**Status:** Ready for Implementation
