# Plan: Multi-Value Pattern Refinements

## Summary

Implement four refinements to the Multi-Value Faction/Race Pattern based on insights from the previous multi-builder-faction implementation. This includes: (1) extracting the pattern into a reusable trait for code reuse across all 5 entity types, (2) adjusting logging severity from WARNING to INFO for gracefully-handled missing factions, (3) eliminating the magic string "unknown" by introducing a `KnownFactions::FACTION_UNKNOWN` constant, and (4) adding comprehensive test coverage for edge cases including empty string handling and invalid faction IDs in compound strings.

## Approach / Architecture

### 1. Trait Abstraction (Extractable Architectural Pattern)

**Objective:** Extract the Multi-Value Pattern into a reusable trait that can be shared across all entity types (`ShipDef`, `ModuleDef`, `ShieldDef`, `EngineDef`, `WeaponDef`).

**Location:** `src/X4/Database/Core/MultiValueFieldTrait.php`

**Design:**
- Create a generic trait that encapsulates the multi-value field pattern
- Use PHP's type system to allow trait consumers to specify the field name, collection class, and entity type
- Pattern will support:
  - Internal array storage (`string[]`)
  - Backward-compatible single-value getter (first element)
  - Multi-value getter (full array)
  - Predicate method (`hasMultiple*()`)
  - Format migration logic (old/new/mixed formats)
  
**Trait Structure:**
```php
trait MultiValueFieldTrait
{
    /**
     * Generic method to retrieve a single value (backward compatible).
     * Returns the first element from the field array.
     */
    protected function getSingleValue(array $values, string $default): string;
    
    /**
     * Generic method to retrieve all values.
     */
    protected function getMultipleValues(array $values): array;
    
    /**
     * Generic method to check if multiple values exist.
     */
    protected function hasMultipleValues(array $values): bool;
    
    /**
     * Generic method to resolve array of IDs to entity objects.
     * @template T
     * @param string[] $ids
     * @param callable $resolver Function to resolve ID to entity (e.g., FactionDefs::getInstance()->getByID())
     * @return T[]
     */
    protected function resolveEntities(array $ids, callable $resolver): array;
    
    /**
     * Generic method to parse space-separated string or array into array.
     * Handles three formats:
     * 1. New array format: ["argon", "teladi"]
     * 2. Old string format: "argon teladi"
     * 3. Mixed format: old key with array value
     */
    protected function parseMultiValueField(
        array $data, 
        string $newKey, 
        string $oldKey, 
        string $default
    ): array;
}
```

**Refactoring Strategy:**
- Implement trait in `src/X4/Database/Core/MultiValueFieldTrait.php`
- Update 5 entity classes to use trait:
  - `ShipDef` (builder factions)
  - `ModuleDef` (builder factions)
  - `ShieldDef` (maker races)
  - `EngineDef` (maker races)
  - `WeaponDef` (maker races)
- Keep existing public API methods unchanged (call trait methods internally)
- Maintain backward compatibility with zero breaking changes

**Benefits:**
- **Eliminates 120+ lines of duplicate code** across 5 classes (24 lines × 5)
- **Single source of truth** for multi-value logic
- **Easier maintenance** - bugs fixed once, apply to all
- **Future-ready** - new entity types can adopt pattern in 10 minutes

---

### 2. Logging Severity Adjustment

**Objective:** Change logging level from WARNING to INFO for missing builder faction scenarios that are gracefully handled with fallback to `generic`.

**Location:** `src/X4/Database/Ships/ShipsExtractor.php:269`

**Current Behavior:**
```php
Console::line1('WARNING | The ship [%s] has no builder faction. Defaulting to [%s].', $shipID, $factionID);
```

**Rationale for Change:**
- The scenario is **not an error** - it's handled gracefully with a fallback to `generic`
- The `generic` faction is a valid and expected fallback for ships without explicit builder data
- WARNING severity suggests a problem that needs attention, but this is normal operation
- INFO severity correctly indicates routine processing information

**New Behavior:**
```php
Console::line1('INFO | The ship [%s] has no builder faction. Defaulting to [%s].', $shipID, $factionID);
```

**Impact:**
- Reduces noise in build logs
- Maintains audit trail (still logged)
- Aligns with semantic logging best practices
- No functional changes to code behavior

**Similar Locations to Check:**
- `ModuleMacroExtractor.php` (if similar pattern exists)
- `ShieldMacroExtractor.php` (if similar pattern exists)
- `EngineMacroExtractor.php` (if similar pattern exists)
- `WeaponMacroExtractor.php` (if similar pattern exists)

---

### 3. Magic String Elimination

**Objective:** Replace hardcoded `"unknown"` strings with a formal constant `KnownFactions::FACTION_UNKNOWN` for consistency and maintainability.

**Locations (based on synthesis document):**
- 3 locations across extractor classes (search required to confirm exact locations)

**Implementation:**
1. Add constant to `KnownFactions.php`:
```php
public const FACTION_UNKNOWN = 'unknown';
```

2. Update FACTIONS array to include the new constant:
```php
public const FACTIONS = array(
    // ... existing factions ...
    self::FACTION_UNKNOWN,
);
```

3. Replace all occurrences of hardcoded `"unknown"` string with `KnownFactions::FACTION_UNKNOWN`

**Search Pattern:**
```regex
"unknown"|'unknown'
```
Filter to extractor classes in `src/X4/Database/*/` directories.

**Rationale:**
- **Eliminates magic strings** - improves code maintainability
- **Type-safe references** - IDE autocomplete and refactoring support
- **Consistent pattern** - all faction IDs use constants
- **Documentation** - constant provides single source of truth

**Impact:**
- Zero runtime changes (same string value)
- Improved code quality and maintainability
- Better IDE support and refactoring safety

---

### 4. Enhanced Test Coverage

**Objective:** Add explicit test cases for edge cases in multi-value faction/race handling that are not currently covered.

**Test Scenarios to Add:**

#### 4.1 Empty String Handling
**Location:** `tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php`

**Test Case:**
```php
public function test_emptyBuilderFactionString(): void
{
    // Test that an empty string in faction field defaults to 'generic'
    // Simulate fromArray() with empty string in old format
}
```

**Purpose:** Verify that `fromArray()` handles empty strings gracefully and defaults to `generic`.

#### 4.2 Invalid Faction ID in Compound String
**Location:** `tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php`

**Test Case:**
```php
public function test_invalidFactionInCompoundString(): void
{
    // Test handling of "argon unknown teladi" space-separated string
    // Verify that invalid faction IDs are either filtered or cause appropriate error
}
```

**Purpose:** Verify behavior when space-separated string contains invalid faction IDs.

#### 4.3 Whitespace Handling
**Location:** `tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php`

**Test Case:**
```php
public function test_whitespaceInFactionString(): void
{
    // Test handling of "  argon    teladi  " with extra whitespace
    // Verify whitespace is properly trimmed
}
```

**Purpose:** Ensure robust parsing of space-separated strings with irregular whitespace.

#### 4.4 Single Value in Array Format
**Location:** `tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php`

**Test Case:**
```php
public function test_singleValueInArrayFormat(): void
{
    // Test that ["argon"] (single-element array) is handled correctly
    // Verify hasMultipleBuilderFactions() returns false
    // Verify backward-compatible getter returns the single value
}
```

**Purpose:** Ensure single-value arrays work correctly and don't erroneously report multiple values.

**Testing Strategy:**
- Add tests to existing `ShipCollectionTests.php` (primary focus for ships)
- Consider adding similar tests to other entity test suites (`ShieldDefTests.php`, `ModuleDefTests.php`, etc.) if critical
- Use PHPUnit data providers for multiple format variations
- Mock `fromArray()` data to simulate various input scenarios
- Verify both API methods (backward-compatible + new) work correctly

**Coverage Goal:**
- 100% of edge cases documented in synthesis recommendations
- Minimum 90% code coverage on multi-value field parsing logic

---

## Rationale

### Why These Four Refinements?

1. **Trait Abstraction (Gold Nugget):**
   - The synthesis document identified this as the **highest-value improvement**
   - Eliminates significant code duplication (120+ lines across 5 classes)
   - Establishes reusable pattern for future entity types
   - Demonstrates mature library evolution and architectural thinking

2. **Logging Severity:**
   - Low-effort, high-impact improvement (single-line change)
   - Reduces log noise and improves developer experience
   - Aligns with semantic logging best practices
   - No risk of breaking changes

3. **Magic String Elimination:**
   - Low-effort improvement with clear maintainability benefits
   - Completes the faction constant standardization
   - Improves IDE support and refactoring safety
   - Consistent with existing `KnownFactions` pattern

4. **Test Coverage:**
   - Critical for production confidence in edge case handling
   - Addresses gaps identified in previous implementation
   - Ensures format migration logic is robust
   - Prevents regression bugs in future changes

### Order of Implementation

These refinements should be implemented in the following sequence:

1. **Magic String Elimination** (15 minutes)
   - Lowest risk, no logic changes
   - Establishes constant for use in other steps

2. **Logging Severity Adjustment** (10 minutes)
   - Simple change, improves DX immediately
   - Independent of other changes

3. **Enhanced Test Coverage** (2-3 hours)
   - Establishes test baseline before refactoring
   - Ensures current implementation passes all edge cases
   - Provides safety net for trait refactoring

4. **Trait Abstraction** (4-6 hours)
   - Most complex refactoring
   - Benefits from having comprehensive tests in place
   - Demonstrates zero regression with existing tests

**Total Estimated Effort:** 7-10 hours

---

## Detailed Steps

### Step 1: Magic String Elimination
1. Add `FACTION_UNKNOWN` constant to `KnownFactions.php`
2. Update `FACTIONS` array to include new constant
3. Search codebase for hardcoded `"unknown"` or `'unknown'` strings in extractor context
4. Replace with `KnownFactions::FACTION_UNKNOWN`
5. Run PHPUnit to verify no regressions
6. Run PHPStan to verify static analysis passes

### Step 2: Logging Severity Adjustment
1. Locate all instances of `WARNING` for missing faction scenarios in extractors
2. Change `WARNING` to `INFO` with justification comments
3. Document rationale in code comments
4. Run database rebuild (`DatabaseBuilder`) to verify logs
5. Review build output to confirm reduced noise

### Step 3: Enhanced Test Coverage
1. Add `test_emptyBuilderFactionString()` to `ShipCollectionTests.php`
2. Add `test_invalidFactionInCompoundString()` to `ShipCollectionTests.php`
3. Add `test_whitespaceInFactionString()` to `ShipCollectionTests.php`
4. Add `test_singleValueInArrayFormat()` to `ShipCollectionTests.php`
5. Run all tests to establish baseline
6. If any tests fail, analyze and fix underlying implementation issues
7. Verify 90%+ coverage on `fromArray()` parsing logic

### Step 4: Trait Abstraction
1. **Create trait file:** `src/X4/Database/Core/MultiValueFieldTrait.php`
2. **Implement generic methods:**
   - `getSingleValue()`
   - `getMultipleValues()`
   - `hasMultipleValues()`
   - `resolveEntities()`
   - `parseMultiValueField()`
3. **Update `ShipDef`:**
   - Add `use MultiValueFieldTrait;`
   - Refactor `getBuilderFactionID()` to call `getSingleValue($this->builderFactionIDs, KnownFactions::FACTION_GENERIC)`
   - Refactor `getBuilderFactionIDs()` to call `getMultipleValues($this->builderFactionIDs)`
   - Refactor `hasMultipleBuilderFactions()` to call `hasMultipleValues($this->builderFactionIDs)`
   - Refactor `getBuilderFactions()` to call `resolveEntities($this->builderFactionIDs, fn($id) => FactionDefs::getInstance()->getByID($id))`
   - Update `fromArray()` to call `parseMultiValueField()`
4. **Repeat step 3 for:**
   - `ModuleDef`
   - `ShieldDef`
   - `EngineDef`
   - `WeaponDef`
5. **Run all tests** - verify zero regressions (773 tests should still pass)
6. **Run PHPStan** - verify static analysis passes
7. **Compare code metrics:**
   - Lines reduced (expect ~120+ lines eliminated)
   - Cyclomatic complexity unchanged or improved

---

## Dependencies

### Internal Dependencies
- **X4 Core codebase** (obviously)
- **Composer autoloader** must be run after creating new trait file:
  ```bash
  composer dump-autoload
  ```

### External Dependencies
None. All changes are internal refactoring and improvements.

### Version Dependencies
- PHP 8.4+ (already required)
- PHPUnit 9.5+ (already installed)
- PHPStan 1.6+ (already installed)

### Dependency Sequencing
- **Step 1 & 2** are independent (can be done in parallel)
- **Step 3** must complete before Step 4 (trait refactoring)
- **Step 4** depends on Step 1 (uses `FACTION_UNKNOWN` constant)

---

## Required Components

### New Files to Create
1. **`src/X4/Database/Core/MultiValueFieldTrait.php`**
   - Namespace: `Mistralys\X4\Database\Core`
   - Purpose: Reusable trait for multi-value field pattern
   - Approximate lines: 150-200

### Files to Modify

#### Constants & Configuration
1. **`src/X4/Database/Factions/KnownFactions.php`**
   - Add: `FACTION_UNKNOWN` constant
   - Update: `FACTIONS` array

#### Extractors (Logging + Magic Strings)
2. **`src/X4/Database/Ships/ShipsExtractor.php`**
   - Change: WARNING → INFO in missing faction handler
   - Replace: `"unknown"` with `KnownFactions::FACTION_UNKNOWN` (if applicable)

3. **`src/X4/Database/Modules/ModuleMacroExtractor.php`**
   - Replace: `"unknown"` with constant (if applicable)

4. **`src/X4/Database/Shields/ShieldMacroExtractor.php`**
   - Replace: `"unknown"` with constant (if applicable)

5. **`src/X4/Database/Engines/EngineMacroExtractor.php`**
   - Replace: `"unknown"` with constant (if applicable)

6. **`src/X4/Database/Weapons/WeaponMacroExtractor.php`**
   - Replace: `"unknown"` with constant (if applicable)

#### Entity Definitions (Trait Usage)
7. **`src/X4/Database/Ships/ShipDef.php`**
   - Add: `use MultiValueFieldTrait;`
   - Refactor: 5 methods to use trait

8. **`src/X4/Database/Modules/ModuleDef.php`**
   - Add: `use MultiValueFieldTrait;`
   - Refactor: 5 methods to use trait

9. **`src/X4/Database/Shields/ShieldDef.php`**
   - Add: `use MultiValueFieldTrait;`
   - Refactor: 5 methods to use trait

10. **`src/X4/Database/Engines/EngineDef.php`**
    - Add: `use MultiValueFieldTrait;`
    - Refactor: 5 methods to use trait

11. **`src/X4/Database/Weapons/WeaponDef.php`**
    - Add: `use MultiValueFieldTrait;`
    - Refactor: 5 methods to use trait

#### Test Files
12. **`tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php`**
    - Add: 4 new test methods for edge cases

### Manifest Documents to Update

#### After Step 1-3 (Magic Strings, Logging, Tests)
1. **`docs/agents/project-manifest/api/database-game-data-api.md`**
   - Add: `KnownFactions::FACTION_UNKNOWN` constant
   
2. **`docs/agents/project-manifest/constraints.md`**
   - Add: Note about using `FACTION_UNKNOWN` constant instead of magic strings

#### After Step 4 (Trait Abstraction)
3. **`docs/agents/project-manifest/tech-stack.md`**
   - Update: Pattern #9 to reference trait implementation
   - Add: Documentation of `MultiValueFieldTrait` in architectural patterns section

4. **`docs/agents/project-manifest/api/database-core-api.md`**
   - Add: `MultiValueFieldTrait` documentation with method signatures

5. **`docs/agents/project-manifest/file-tree.md`**
   - Add: New trait file location

6. **`docs/agents/project-manifest/constraints.md`**
   - Add: Convention for using `MultiValueFieldTrait` in new entity types

---

## Assumptions

1. **Backward Compatibility is Non-Negotiable**
   - All existing public API methods must continue to work identically
   - No breaking changes allowed in trait refactoring
   - Test suite must remain at 773 passing tests (no new failures)

2. **Generic Faction is Valid Fallback**
   - Using `generic` as default faction is correct business logic
   - No special handling needed for `generic` faction
   - INFO severity is appropriate for this scenario

3. **Unknown Faction is Distinct from Generic**
   - `"unknown"` is a valid faction ID in some contexts
   - Adding `FACTION_UNKNOWN` constant does not change semantics
   - Current extractor logic using `"unknown"` is correct

4. **Trait Approach is Feasible**
   - PHP traits can effectively encapsulate this pattern
   - No conflicts with existing trait usage
   - IDE support for traits is adequate

5. **Test Coverage is Representative**
   - The Envoy ship (multi-faction example) remains in game data
   - Edge cases in tests represent real-world data scenarios
   - Test data format matches production JSON structure

6. **No Database Rebuild Required**
   - Changes are implementation-only (no data format changes)
   - Existing JSON files remain valid
   - No extraction logic changes that affect output format

7. **Magic String Search is Exhaustive**
   - Using regex search `"unknown"|'unknown'` will find all occurrences
   - Manual review of search results will identify true positives vs. unrelated strings
   - Extractor classes are the primary target (not UI, not tests)

---

## Constraints

### Architectural Constraints

1. **Strict Type Declarations**
   - All new code must use `declare(strict_types=1);`
   - All methods must have type hints
   - Follow existing type declaration patterns

2. **Namespace Convention**
   - Trait must be in: `Mistralys\X4\Database\Core`
   - Follow existing namespace structure

3. **No Breaking Changes**
   - **CRITICAL:** Public API must not change
   - Existing methods must maintain identical signatures
   - Return types must remain the same
   - Exception types must remain the same

4. **Singleton Pattern Preservation**
   - Collection classes remain singletons
   - No changes to `getInstance()` pattern

5. **JSON Data Format**
   - No changes to JSON structure
   - Must support all three existing formats:
     - New: `"builderFactionIDs": ["argon", "teladi"]`
     - Old: `"builderFactionID": "argon teladi"`
     - Mixed: `"builderFactionID": ["argon", "teladi"]`

### Code Style Constraints

1. **PHPDoc Comments**
   - Required for all public methods in trait
   - Include `@package` and `@subpackage` tags
   - Document generic types with `@template` tags

2. **Naming Conventions**
   - Trait methods: `protected` visibility (used internally)
   - Public methods in entity classes: unchanged
   - Constants: `ALL_CAPS_SNAKE_CASE`

3. **Method Naming**
   - Trait methods use generic names (`getSingleValue`, not `getBuilderFactionID`)
   - Entity methods use domain-specific names (unchanged)

### Testing Constraints

1. **Zero Test Failures**
   - All 773 existing tests must continue to pass
   - Skipped tests (8) can remain skipped
   - Warnings (2) acceptable if pre-existing

2. **Coverage Requirements**
   - New trait methods: 90% code coverage minimum
   - Edge case tests must cover all identified scenarios
   - Use assertions to verify behavior, not just execution

3. **Test Data**
   - Use realistic faction IDs from `KnownFactions`
   - Simulate actual data formats from game extraction
   - Do not use synthetic data that doesn't reflect production

### Performance Constraints

1. **No Performance Degradation**
   - Trait method calls must be inline (negligible overhead)
   - No additional loops or iterations
   - Same algorithmic complexity as current implementation

2. **Memory Usage**
   - No increase in memory footprint
   - Array storage remains the same

### Documentation Constraints

1. **Manifest Synchronization**
   - All manifest documents must be updated
   - No contradictions between code and documentation
   - Update "Last Updated" dates

2. **Code Comments**
   - Trait must have comprehensive PHPDoc
   - Rationale for generic design must be documented
   - Usage examples in trait PHPDoc

---

## Out of Scope

### Explicitly Excluded from This Plan

1. **Applying Pattern to Other Fields**
   - This plan focuses only on existing multi-faction/race fields
   - Future application to other multi-value fields (e.g., licenses, tags) is out of scope
   - Pattern documentation enables future expansion but does not implement it

2. **Code Generation Tools**
   - Synthesis document suggested code generation for applying pattern
   - This plan implements manual refactoring only
   - Future automation could be a separate enhancement

3. **Performance Optimization**
   - No performance tuning of multi-value parsing
   - Current implementation is adequate
   - Optimization would be premature without benchmarks

4. **Data Validation**
   - No validation that faction IDs exist in `FactionDefs`
   - Current trust-based approach remains unchanged
   - Validation could be added in future but adds complexity

5. **Additional Entity Types**
   - Only the 5 existing entities with multi-faction/race support are in scope
   - Other entity types (`BlueprintDef`, `WareDef`, etc.) are excluded
   - Future entities can adopt pattern but not included in this refactoring

6. **UI Changes**
   - No changes to how multi-faction data is displayed in UI components
   - UI already correctly handles multiple factions
   - This is purely backend refactoring

7. **Database Rebuild**
   - No changes to extraction logic that affect data format
   - No need to re-run `DatabaseBuilder`
   - JSON files remain valid and unchanged

8. **Translation Support**
   - No localization changes
   - `FACTION_UNKNOWN` constant does not need translation
   - UI labels for factions are already handled

9. **Documentation Beyond Manifest**
   - No updates to README.md or user-facing documentation
   - No new handbook sections
   - Manifest updates only (for AI agents)

10. **External Library Changes**
    - No changes to `mistralys/x4-data-extractor`
    - No changes to composer dependencies
    - All work confined to x4-core repository

---

## Acceptance Criteria

### Step 1: Magic String Elimination

- [ ] `KnownFactions::FACTION_UNKNOWN` constant defined
- [ ] `FACTIONS` array includes new constant
- [ ] All hardcoded `"unknown"` strings replaced in extractor context
- [ ] Zero compile errors
- [ ] PHPStan passes with no new warnings
- [ ] 773 PHPUnit tests pass (no regressions)
- [ ] Documentation updated:
  - [ ] `api/database-game-data-api.md` includes new constant
  - [ ] `constraints.md` references constant usage convention

### Step 2: Logging Severity Adjustment

- [ ] `ShipsExtractor.php` line ~269 changed to INFO
- [ ] Similar changes in other extractors (if applicable)
- [ ] Code comments explain rationale
- [ ] Database rebuild log shows INFO instead of WARNING
- [ ] No functional behavior changes
- [ ] 773 PHPUnit tests pass (no regressions)

### Step 3: Enhanced Test Coverage

- [ ] 4 new test methods added to `ShipCollectionTests.php`:
  - [ ] `test_emptyBuilderFactionString()`
  - [ ] `test_invalidFactionInCompoundString()`
  - [ ] `test_whitespaceInFactionString()`
  - [ ] `test_singleValueInArrayFormat()`
- [ ] All new tests pass
- [ ] Edge cases properly covered (assertions verify behavior)
- [ ] Test data uses realistic faction IDs
- [ ] Code coverage on `fromArray()` ≥ 90%
- [ ] Total test count increased by 4
- [ ] Zero test failures

### Step 4: Trait Abstraction

- [ ] `MultiValueFieldTrait.php` created in correct location
- [ ] Trait includes 5 protected methods:
  - [ ] `getSingleValue()`
  - [ ] `getMultipleValues()`
  - [ ] `hasMultipleValues()`
  - [ ] `resolveEntities()`
  - [ ] `parseMultiValueField()`
- [ ] All trait methods have complete PHPDoc
- [ ] 5 entity classes refactored to use trait:
  - [ ] `ShipDef`
  - [ ] `ModuleDef`
  - [ ] `ShieldDef`
  - [ ] `EngineDef`
  - [ ] `WeaponDef`
- [ ] Code duplication reduced by ≥100 lines
- [ ] Zero breaking changes to public API
- [ ] 773 PHPUnit tests pass (no regressions)
- [ ] PHPStan passes with no new warnings
- [ ] `composer dump-autoload` executed successfully
- [ ] Documentation updated:
  - [ ] `tech-stack.md` - Pattern #9 references trait
  - [ ] `api/database-core-api.md` - Trait documented
  - [ ] `file-tree.md` - Trait file listed
  - [ ] `constraints.md` - Trait usage convention documented

### Overall Project Criteria

- [ ] All 4 steps completed successfully
- [ ] Total test count: 777 (773 existing + 4 new)
- [ ] Zero test failures
- [ ] Zero new PHPStan warnings
- [ ] 5 manifest documents updated and synchronized
- [ ] "Last Updated" dates current
- [ ] No breaking changes to any public API
- [ ] Backward compatibility maintained 100%

---

## Testing Strategy

### Unit Testing Approach

1. **Preserve Existing Tests**
   - All 773 existing tests must pass without modification
   - No changes to existing test logic
   - Tests serve as regression protection during refactoring

2. **New Edge Case Tests**
   - Focus on `ShipCollectionTests.php` (primary test suite)
   - Use PHPUnit assertions extensively:
     - `assertEquals()` for value comparisons
     - `assertContains()` for array membership
     - `assertTrue() / assertFalse()` for boolean checks
     - `assertCount()` for array size verification
     - `assertInstanceOf()` for type verification
   - Use descriptive test method names
   - Include failure messages in assertions

3. **Test Data Strategy**
   - Mock `fromArray()` data with various formats
   - Use realistic faction IDs from `KnownFactions`
   - Simulate edge cases:
     - Empty strings
     - Whitespace variations
     - Invalid faction IDs
     - Single-element arrays
     - Multi-element arrays
     - Mixed old/new format

4. **Trait Testing**
   - Trait methods are tested indirectly through entity class tests
   - No separate trait test file needed (trait is not standalone)
   - Entity tests cover all trait code paths

### Integration Testing

1. **Collection-Finder Integration**
   - Existing `test_finderMatchesMultiFaction()` covers this
   - No new integration tests needed
   - Verify multi-faction ships appear in filtered results

2. **Extractor-Builder Integration**
   - Run full database rebuild after changes
   - Verify JSON output format unchanged
   - Verify logs show INFO severity

### Static Analysis Testing

1. **PHPStan**
   - Run with existing configuration (`phpstan.neon`)
   - Level: As currently configured in project
   - Zero new errors or warnings acceptable

2. **Type Coverage**
   - All trait methods fully typed
   - All entity methods maintain existing type declarations
   - Generics (`@template`) used appropriately

### Manual Testing

1. **Build Process**
   - Run `composer dump-autoload` after trait creation
   - Verify autoloader recognizes new trait
   - Run database rebuild to test extractors

2. **Log Review**
   - Review build logs for INFO severity
   - Confirm reduced noise compared to WARNING
   - Verify no unexpected errors

### Test Execution Sequence

```bash
# After each step, run:
composer test              # PHPUnit tests
composer analyze          # PHPStan analysis

# After Step 4, additionally run:
composer dump-autoload    # Refresh autoloader
composer test             # Verify trait integration
```

### Coverage Goals

| Component | Coverage Target | Measurement |
|-----------|----------------|-------------|
| `MultiValueFieldTrait` | 90% | PHPUnit coverage report |
| `fromArray()` methods | 90% | PHPUnit coverage report |
| Edge case scenarios | 100% | All 4 test cases pass |
| Existing functionality | 100% | 773 tests still pass |

---

## Risks & Mitigations

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| **Trait refactoring introduces subtle bugs** | MEDIUM | HIGH | Comprehensive test suite (777 tests) provides regression protection. Refactor one entity class at a time and run tests after each. |
| **Breaking changes to public API** | LOW | CRITICAL | Strict adherence to backward compatibility rule. Code review checklist includes API signature verification. All existing methods must call trait methods internally with identical behavior. |
| **Magic string search misses occurrences** | MEDIUM | LOW | Manual review of search results. Use multiple search patterns (`"unknown"`, `'unknown'`, `unknown`). Search in specific directories only (extractors). |
| **Trait approach fails due to PHP limitations** | LOW | HIGH | PHP 8.4 traits are mature and well-supported. Protected methods in traits are standard practice. Fallback: Use abstract base class instead of trait. |
| **Test coverage insufficient for edge cases** | MEDIUM | MEDIUM | Define test scenarios explicitly in plan (this document). Require 90% coverage on parsing logic. Manual review of test completeness. |
| **Performance regression from trait abstraction** | LOW | MEDIUM | Trait methods are inline (no overhead). No algorithmic changes. Run benchmarks if concerned. |
| **Manifest documentation out of sync** | MEDIUM | LOW | Mandatory manifest update checklist. Review manifest changes as part of code review. |
| **Logging severity change causes confusion** | LOW | LOW | Add code comments explaining rationale. Document in code review. Severity change is semantic (INFO is correct). |
| **Unknown faction constant conflicts with existing code** | LOW | MEDIUM | Search for existing `FACTION_UNKNOWN` before adding. Run full test suite to detect conflicts. |
| **Autoloader not updated after trait creation** | MEDIUM | HIGH | Mandatory `composer dump-autoload` step in plan. Include in acceptance criteria. Document in Step 4 instructions. |
| **Entity classes have subtle differences preventing trait use** | MEDIUM | HIGH | Review all 5 entity class implementations before coding trait. Use method parameters to handle differences (field names, collection classes). Fallback: Trait provides helpers, not full implementation. |
| **Tests fail due to missing game data (Envoy ship)** | LOW | LOW | Existing tests handle this with `markTestSkipped()`. New tests should follow same pattern. No blocker. |
| **PHPStan fails on generic types in trait** | LOW | MEDIUM | Use `@template` annotations correctly. Reference PHPStan documentation for generics. Worst case: Suppress specific warnings with @phpstan-ignore-next-line. |
| **Code duplication calculation is incorrect** | LOW | LOW | Metric is estimate (~120 lines). Exact number less important than reduction achieved. |
| **Trait PHPDoc is ambiguous for future implementers** | MEDIUM | MEDIUM | Include comprehensive usage examples in trait PHPDoc. Document parameter purposes. Add "How to Use This Trait" section. |

### Critical Risk: Breaking Changes

**Mitigation Plan:**
1. Before refactoring, document exact public API signatures for all 5 entity classes
2. After refactoring, verify signatures match exactly
3. Run all 773 tests after each entity class refactoring
4. Manual inspection of API methods in each class
5. Code review specifically focuses on API preservation

### High-Priority Testing

The following scenarios MUST be tested explicitly:
- ✅ Backward-compatible getter returns first element
- ✅ Multi-value getter returns full array
- ✅ Predicate method correctly identifies single vs. multiple
- ✅ Empty faction array defaults to generic
- ✅ Space-separated string parsing works
- ✅ Old format with array value works
- ✅ Finder intersection matching works

---

## Implementation Notes

### Trait Design Considerations

**Generic vs. Specific:**
- Trait methods are **generic** (work with any field)
- Entity methods are **specific** (domain names like `getBuilderFactionIDs()`)
- Entity methods call trait methods with field-specific parameters

**Example Integration:**
```php
class ShipDef
{
    use MultiValueFieldTrait;
    
    private array $builderFactionIDs;
    
    public function getBuilderFactionID(): string 
    {
        return $this->getSingleValue($this->builderFactionIDs, KnownFactions::FACTION_GENERIC);
    }
    
    public function getBuilderFactionIDs(): array 
    {
        return $this->getMultipleValues($this->builderFactionIDs);
    }
    
    public function hasMultipleBuilderFactions(): bool 
    {
        return $this->hasMultipleValues($this->builderFactionIDs);
    }
    
    public function getBuilderFactions(): array 
    {
        return $this->resolveEntities(
            $this->builderFactionIDs,
            fn($id) => FactionDefs::getInstance()->getByID($id)
        );
    }
    
    public static function fromArray(array $data): self 
    {
        $factionIDs = self::parseMultiValueField(
            $data,
            self::KEY_BUILDER_FACTION_IDS,
            self::KEY_BUILDER_FACTION_ID,
            KnownFactions::FACTION_GENERIC
        );
        // ... rest of fromArray() logic
    }
}
```

**Trait Method Visibility:**
- All trait methods are `protected` (internal use only)
- Entity classes keep their `public` methods unchanged
- No trait methods exposed directly to external code

### Code Review Checklist

For reviewers of this implementation:

- [ ] All 5 entity classes use trait consistently
- [ ] No public API signature changes
- [ ] All trait methods have PHPDoc
- [ ] Code duplication reduced significantly
- [ ] 777 tests pass (773 + 4 new)
- [ ] PHPStan clean
- [ ] Constants used instead of magic strings
- [ ] Logging severity changed to INFO
- [ ] Manifest documents updated
- [ ] `composer dump-autoload` executed

### Rollback Plan

If critical issues are discovered:

1. **Trait Refactoring Problems:**
   - Revert entity class changes (git revert)
   - Delete trait file
   - Run `composer dump-autoload`
   - Verify 773 tests pass

2. **Test Failures:**
   - Analyze failing test
   - Fix implementation or test as appropriate
   - Do not merge until all tests pass

3. **Performance Regression:**
   - Revert trait usage
   - Keep test improvements
   - Keep constant additions
   - Keep logging changes

---

## Success Metrics

### Quantitative Metrics

| Metric | Before | After | Target |
|--------|--------|-------|--------|
| **Code Duplication** | ~120 lines duplicated | ~0 lines duplicated | >100 lines reduced |
| **Test Count** | 773 tests | 777 tests | +4 tests |
| **Test Failures** | 0 | 0 | 0 |
| **PHPStan Warnings** | baseline | baseline | No new warnings |
| **Magic Strings** | 3 occurrences | 0 occurrences | 0 |
| **Trait Reuse** | 0 traits | 5 classes use trait | 5 |
| **Code Coverage (parsing)** | unknown | ≥90% | ≥90% |

### Qualitative Metrics

- ✅ **Maintainability:** Multi-value logic centralized in one place
- ✅ **Consistency:** All 5 entity types follow identical pattern
- ✅ **Developer Experience:** Reduced log noise from INFO severity
- ✅ **Code Quality:** Magic strings eliminated
- ✅ **Confidence:** Comprehensive edge case coverage
- ✅ **Documentation:** Manifest synchronized with implementation

### Success Definition

This implementation is considered **successful** if:

1. All 4 steps complete without critical issues
2. Zero breaking changes to public API
3. All 777 tests pass
4. Code duplication reduced by >100 lines
5. Manifest documentation complete and accurate
6. Implementation indistinguishable from original behavior externally
7. Pattern is reusable for future entity types

---

## Timeline Estimate

### Detailed Time Breakdown

| Step | Task | Estimated Time |
|------|------|----------------|
| **1** | Magic String Elimination | **15 minutes** |
| 1.1 | Add constant to `KnownFactions.php` | 5 minutes |
| 1.2 | Search and replace magic strings | 5 minutes |
| 1.3 | Run tests and analysis | 5 minutes |
| **2** | Logging Severity Adjustment | **10 minutes** |
| 2.1 | Locate and change WARNING to INFO | 5 minutes |
| 2.2 | Run database rebuild and verify logs | 5 minutes |
| **3** | Enhanced Test Coverage | **2-3 hours** |
| 3.1 | Write `test_emptyBuilderFactionString` | 30 minutes |
| 3.2 | Write `test_invalidFactionInCompoundString` | 30 minutes |
| 3.3 | Write `test_whitespaceInFactionString` | 30 minutes |
| 3.4 | Write `test_singleValueInArrayFormat` | 30 minutes |
| 3.5 | Run tests and debug | 30 minutes |
| **4** | Trait Abstraction | **4-6 hours** |
| 4.1 | Create and implement trait | 1.5 hours |
| 4.2 | Refactor `ShipDef` | 30 minutes |
| 4.3 | Refactor `ModuleDef` | 30 minutes |
| 4.4 | Refactor `ShieldDef` | 30 minutes |
| 4.5 | Refactor `EngineDef` | 30 minutes |
| 4.6 | Refactor `WeaponDef` | 30 minutes |
| 4.7 | Run full test suite and debug | 1 hour |
| 4.8 | Update manifest documentation | 1 hour |
| **Total** | | **7-10 hours** |

### Critical Path

```
Step 1 (Magic Strings) ──┐
                          ├──> Step 3 (Tests) ──> Step 4 (Trait)
Step 2 (Logging) ────────┘
```

**Parallel Execution:**
- Steps 1 and 2 can be done in parallel (independent)
- Step 3 requires Step 1 (uses `FACTION_UNKNOWN` constant)
- Step 4 requires Step 3 (tests provide safety net)

**Minimum Timeline:** 6 hours (with parallel execution)  
**Maximum Timeline:** 10 hours (serial execution + debugging)  
**Expected Timeline:** 7-8 hours (typical case)

---

## Post-Implementation Documentation

After implementation, the following documentation will exist:

### Developer Documentation

1. **Trait PHPDoc** (`MultiValueFieldTrait.php`)
   - How to use the trait in new entity types
   - Method parameters explained
   - Usage examples
   - Design rationale

2. **Manifest Updates**
   - `tech-stack.md` - Pattern #9 updated with trait reference
   - `api/database-core-api.md` - Trait API documented
   - `file-tree.md` - Trait location documented
   - `constraints.md` - Trait usage convention

### Knowledge Transfer

For future agents working on this pattern:

- **Reference Implementation:** `ShipDef` serves as canonical example
- **Pattern Location:** `MultiValueFieldTrait` in `Database/Core/`
- **Extension Guide:** See manifest `constraints.md` for adding to new entities
- **Test Examples:** `ShipCollectionTests.php` shows comprehensive test patterns

---

**AGENT:** Planning  
**STATUS:** READY_FOR_PM
