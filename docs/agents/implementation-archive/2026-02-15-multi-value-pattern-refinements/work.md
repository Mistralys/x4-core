# Work Packages: Multi-Value Pattern Refinements

**Plan Reference:** [plan.md](plan.md)  
**Created:** February 15, 2026  
**Total Work Packages:** 8  
**Estimated Total Time:** 7-10 hours

---

## Work Package Overview

| WP ID | Title | Dependencies | Estimated Time | Priority |
|-------|-------|--------------|----------------|----------|
| WP-001 | Magic String Elimination | None | 15 min | HIGH |
| WP-002 | Logging Severity Adjustment | None | 10 min | MEDIUM |
| WP-003 | Enhanced Test Coverage | WP-001 | 2-3 hours | HIGH |
| WP-004 | Trait Abstraction - Trait Creation | WP-001 | 1.5 hours | HIGH |
| WP-005 | Trait Abstraction - Entity Refactoring | WP-004 | 2.5 hours | HIGH |
| WP-006 | Trait Abstraction - Testing & Verification | WP-005 | 1 hour | CRITICAL |
| WP-007 | Documentation Updates | WP-006 | 1 hour | MEDIUM |
| WP-008 | Final Validation & QA | WP-007 | 30 min | CRITICAL |

---

## WP-001: Magic String Elimination

### Objective
Replace all hardcoded `"unknown"` strings with a formal constant `KnownFactions::FACTION_UNKNOWN` to improve code maintainability and consistency.

### Context
The synthesis document from the previous multi-builder-faction implementation identified 3 locations where the magic string `"unknown"` is hardcoded in extractor classes. This violates the DRY principle and makes the code harder to refactor.

### Dependencies
- None (can be done in parallel with WP-002)

### Assigned To
Lead Implementation Engineer

### Deliverables

1. **Add constant to `KnownFactions.php`:**
   - Location: `src/X4/Database/Factions/KnownFactions.php`
   - Add: `public const FACTION_UNKNOWN = 'unknown';`
   - Update: `FACTIONS` array to include `self::FACTION_UNKNOWN`

2. **Search and replace magic strings:**
   - Pattern: `"unknown"` or `'unknown'` in extractor context
   - Search directories: `src/X4/Database/*/`
   - Filter to: Extractor classes only (not UI, not tests)
   - Replace with: `KnownFactions::FACTION_UNKNOWN`
   - Expected locations (verify by search):
     - `ShipsExtractor.php`
     - `ModuleMacroExtractor.php`  
     - `ShieldMacroExtractor.php`
     - `EngineMacroExtractor.php`
     - `WeaponMacroExtractor.php`

3. **Verification:**
   - Run: `composer test` (773 tests must pass)
   - Run: `composer analyze` (PHPStan must pass with no new warnings)
   - Verify: All occurrences replaced (no remaining magic strings)

4. **Documentation:**
   - Update: `docs/agents/project-manifest/api/database-game-data-api.md`
     - Add `KnownFactions::FACTION_UNKNOWN` constant to API reference
   - Update: `docs/agents/project-manifest/constraints.md`
     - Add note about using `FACTION_UNKNOWN` constant instead of magic strings

### Acceptance Criteria

- [ ] `KnownFactions::FACTION_UNKNOWN` constant defined with value `'unknown'`
- [ ] `FACTIONS` array includes new constant
- [ ] All hardcoded `"unknown"` strings replaced in extractor classes (minimum 3 occurrences)
- [ ] Zero compile errors
- [ ] PHPStan passes with no new warnings
- [ ] 773 PHPUnit tests pass (no regressions)
- [ ] `api/database-game-data-api.md` includes new constant documentation
- [ ] `constraints.md` includes constant usage convention

### Implementation Notes

**Search Pattern:**
```regex
"unknown"|'unknown'
```

**Files to Check:**
- Focus on extractor classes in `src/X4/Database/*/` directories
- Manually review search results to avoid false positives (e.g., unrelated strings)
- Do NOT replace in tests or UI code (only production extractors)

**Zero Risk:**
- This is a pure refactoring (same string value)
- No functional changes
- No runtime behavior changes
- Improves code quality and IDE support

---

## WP-002: Logging Severity Adjustment

### Objective
Change logging level from WARNING to INFO for missing builder faction scenarios that are gracefully handled with fallback to `generic`.

### Context
The current implementation logs a WARNING when a ship has no builder faction and defaults to `generic`. This is not an error condition—it's normal operation with a valid fallback. The WARNING severity creates unnecessary noise in build logs.

### Dependencies
- None (can be done in parallel with WP-001)

### Assigned To
Lead Implementation Engineer

### Deliverables

1. **Update `ShipsExtractor.php`:**
   - Location: `src/X4/Database/Ships/ShipsExtractor.php` (around line 269)
   - Current: `Console::line1('WARNING | The ship [%s] has no builder faction. Defaulting to [%s].', ...)`
   - Change to: `Console::line1('INFO | The ship [%s] has no builder faction. Defaulting to [%s].', ...)`
   - Add comment explaining rationale

2. **Check similar patterns in other extractors:**
   - `ModuleMacroExtractor.php` - Check for similar WARNING patterns
   - `ShieldMacroExtractor.php` - Check for similar WARNING patterns
   - `EngineMacroExtractor.php` - Check for similar WARNING patterns
   - `WeaponMacroExtractor.php` - Check for similar WARNING patterns
   - If similar patterns exist, apply the same change

3. **Verification:**
   - Run: `composer test` (773 tests must pass)
   - Run: Database rebuild to observe logs
   - Verify: Logs show INFO instead of WARNING
   - Confirm: No functional behavior changes

### Acceptance Criteria

- [ ] `ShipsExtractor.php` line ~269 changed from WARNING to INFO
- [ ] Similar changes applied to other extractors (if applicable)
- [ ] Code comments explain rationale for INFO severity
- [ ] Database rebuild log shows INFO instead of WARNING
- [ ] No functional behavior changes
- [ ] 773 PHPUnit tests pass (no regressions)

### Implementation Notes

**Rationale Comment (add to code):**
```php
// INFO severity: This is not an error condition. Ships without explicit
// builder data correctly fall back to 'generic' faction. This is normal
// operation and does not require developer attention.
```

**Testing:**
- Run `DatabaseBuilder` to regenerate database
- Review console output for severity levels
- Confirm reduced noise compared to WARNING

**Impact:**
- Semantic logging improvement
- Better developer experience
- Maintains audit trail (still logged)
- No functional changes

---

## WP-003: Enhanced Test Coverage

### Objective
Add comprehensive test coverage for edge cases in multi-value faction/race handling that are not currently covered, ensuring robust format migration and parsing logic.

### Context
The previous implementation handles 3 data format scenarios (new array, old string, mixed), but lacks explicit test coverage for edge cases like empty strings, invalid faction IDs, and whitespace handling. These tests provide confidence before the trait refactoring in WP-004/005.

### Dependencies
- **WP-001** (requires `FACTION_UNKNOWN` constant for test data)

### Assigned To
Lead Implementation Engineer

### Deliverables

1. **Add 4 new test methods to `ShipCollectionTests.php`:**
   - Location: `tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php`

2. **Test Case 1: `test_emptyBuilderFactionString()`**
   - Purpose: Verify empty string defaults to 'generic'
   - Simulate: `fromArray()` with empty string in old format
   - Assert: Result equals `KnownFactions::FACTION_GENERIC`
   - Assert: `hasMultipleBuilderFactions()` returns false

3. **Test Case 2: `test_invalidFactionInCompoundString()`**
   - Purpose: Verify handling of invalid faction IDs in space-separated string
   - Simulate: `"argon unknown teladi"` space-separated string
   - Assert: Either filtered out or handled gracefully (behavior depends on implementation)
   - Document: Expected behavior in test docblock

4. **Test Case 3: `test_whitespaceInFactionString()`**
   - Purpose: Ensure robust parsing with irregular whitespace
   - Simulate: `"  argon    teladi  "` with extra whitespace
   - Assert: Whitespace properly trimmed
   - Assert: Result array equals `["argon", "teladi"]`

5. **Test Case 4: `test_singleValueInArrayFormat()`**
   - Purpose: Ensure single-element arrays work correctly
   - Simulate: `["argon"]` (single-element array)
   - Assert: `hasMultipleBuilderFactions()` returns false
   - Assert: Backward-compatible getter returns `"argon"`
   - Assert: Multi-value getter returns `["argon"]`

6. **Verification:**
   - Run: `composer test` (777 tests = 773 existing + 4 new)
   - All tests must pass
   - Use PHPUnit coverage report to verify ≥90% on `fromArray()` parsing logic
   - Descriptive assertions with failure messages

### Acceptance Criteria

- [ ] 4 new test methods added to `ShipCollectionTests.php`
- [ ] `test_emptyBuilderFactionString()` implemented and passing
- [ ] `test_invalidFactionInCompoundString()` implemented and passing
- [ ] `test_whitespaceInFactionString()` implemented and passing
- [ ] `test_singleValueInArrayFormat()` implemented and passing
- [ ] All new tests use realistic faction IDs from `KnownFactions`
- [ ] Test assertions include descriptive failure messages
- [ ] Code coverage on `fromArray()` parsing logic ≥ 90%
- [ ] Total test count increased by 4 (773 → 777)
- [ ] Zero test failures (all 777 tests pass)

### Implementation Notes

**Test Data Strategy:**
- Use `KnownFactions::FACTION_*` constants for test data
- Mock `fromArray()` data with various formats
- Test both old and new data format scenarios

**Assertion Examples:**
```php
$this->assertEquals(
    KnownFactions::FACTION_GENERIC, 
    $ship->getBuilderFactionID(),
    'Empty faction string should default to generic'
);

$this->assertFalse(
    $ship->hasMultipleBuilderFactions(),
    'Single-element array should not report multiple factions'
);

$this->assertCount(
    2, 
    $ship->getBuilderFactionIDs(),
    'Whitespace-padded string should parse to 2 factions'
);
```

**Coverage Tool:**
- Use PHPUnit coverage report: `composer test -- --coverage-text`
- Focus on `fromArray()` and `parseMultiValueField()` (after trait creation)

---

## WP-004: Trait Abstraction - Trait Creation

### Objective
Create the `MultiValueFieldTrait` that encapsulates the multi-value field pattern into reusable, generic methods that can be shared across all 5 entity types.

### Context
This implements the "Gold Nugget" recommendation from the synthesis document. By extracting the pattern into a trait, we eliminate ~120 lines of duplicate code across 5 entity classes and establish a reusable pattern for future entity types.

### Dependencies
- **WP-001** (trait uses `FACTION_UNKNOWN` constant)

### Assigned To
Lead Implementation Engineer

### Deliverables

1. **Create trait file:**
   - Location: `src/X4/Database/Core/MultiValueFieldTrait.php`
   - Namespace: `Mistralys\X4\Database\Core`
   - Visibility: All methods `protected` (internal use only)

2. **Implement 5 generic methods:**

   a. **`getSingleValue(array $values, string $default): string`**
   - Returns first element from array (backward-compatible)
   - Falls back to `$default` if array empty

   b. **`getMultipleValues(array $values): array`**
   - Returns full array as-is

   c. **`hasMultipleValues(array $values): bool`**
   - Returns true if array has more than 1 element

   d. **`resolveEntities(array $ids, callable $resolver): array`**
   - Generic method to resolve IDs to entity objects
   - Uses callable for flexibility (e.g., `FactionDefs::getInstance()->getByID()`)
   - Returns array of resolved entities
   - Uses `@template` for generic typing

   e. **`parseMultiValueField(array $data, string $newKey, string $oldKey, string $default): array`**
   - Handles 3 format scenarios:
     1. New array format: `$data[$newKey] = ["argon", "teladi"]`
     2. Old string format: `$data[$oldKey] = "argon teladi"`
     3. Mixed format: `$data[$oldKey] = ["argon", "teladi"]`
   - Splits space-separated strings into arrays
   - Trims whitespace from each element
   - Returns array of strings
   - Falls back to `[$default]` if both keys missing or empty

3. **Complete PHPDoc:**
   - Add package/subpackage tags
   - Document all parameters with types
   - Use `@template` for generic types
   - Include usage examples in class docblock
   - Document design rationale
   - Add "How to Use This Trait" section

4. **Verification:**
   - Run: `composer dump-autoload` (register new trait)
   - Verify: No compile errors
   - Verify: PHPStan passes

### Acceptance Criteria

- [ ] `MultiValueFieldTrait.php` created in `src/X4/Database/Core/`
- [ ] Namespace is `Mistralys\X4\Database\Core`
- [ ] All 5 methods implemented: `getSingleValue()`, `getMultipleValues()`, `hasMultipleValues()`, `resolveEntities()`, `parseMultiValueField()`
- [ ] All methods have `protected` visibility
- [ ] All methods have complete PHPDoc with parameter types
- [ ] Generic types documented with `@template` annotations
- [ ] Class docblock includes usage examples
- [ ] "How to Use This Trait" section in docblock
- [ ] Design rationale documented
- [ ] `composer dump-autoload` executed successfully
- [ ] Zero compile errors
- [ ] PHPStan passes with no new warnings

### Implementation Notes

**Method Visibility:**
- All trait methods are `protected` (not `public`)
- Entity classes expose public API that calls trait methods internally
- No direct access to trait methods from external code

**Generic Type Example:**
```php
/**
 * @template T
 * @param string[] $ids
 * @param callable(string): T $resolver
 * @return T[]
 */
protected function resolveEntities(array $ids, callable $resolver): array
{
    return array_map($resolver, $ids);
}
```

**Format Migration Logic:**
```php
protected function parseMultiValueField(
    array $data, 
    string $newKey, 
    string $oldKey, 
    string $default
): array {
    // 1. Check new key (plural) with array
    if (isset($data[$newKey]) && is_array($data[$newKey])) {
        return $data[$newKey];
    }
    
    // 2. Check old key (singular)
    if (isset($data[$oldKey])) {
        // Handle array in old key (intermediate rebuild scenario)
        if (is_array($data[$oldKey])) {
            return $data[$oldKey];
        }
        // Handle string in old key (legacy format)
        if (is_string($data[$oldKey]) && trim($data[$oldKey]) !== '') {
            return array_map('trim', explode(' ', $data[$oldKey]));
        }
    }
    
    // 3. Fallback to default
    return [$default];
}
```

**Documentation Template:**
See plan.md for comprehensive PHPDoc structure.

---

## WP-005: Trait Abstraction - Entity Refactoring

### Objective
Refactor all 5 entity classes (`ShipDef`, `ModuleDef`, `ShieldDef`, `EngineDef`, `WeaponDef`) to use the `MultiValueFieldTrait`, eliminating code duplication while preserving backward compatibility.

### Context
This is the core refactoring that achieves the code duplication reduction goal. Each entity class will replace its multi-value field implementation with trait method calls, maintaining identical public API signatures.

### Dependencies
- **WP-004** (requires trait to exist)

### Assigned To
Lead Implementation Engineer

### Deliverables

1. **Refactor `ShipDef`:**
   - Location: `src/X4/Database/Ships/ShipDef.php`
   - Add: `use MultiValueFieldTrait;`
   - Refactor methods to call trait:
     - `getBuilderFactionID()` → calls `getSingleValue($this->builderFactionIDs, KnownFactions::FACTION_GENERIC)`
     - `getBuilderFactionIDs()` → calls `getMultipleValues($this->builderFactionIDs)`
     - `hasMultipleBuilderFactions()` → calls `hasMultipleValues($this->builderFactionIDs)`
     - `getBuilderFactions()` → calls `resolveEntities($this->builderFactionIDs, fn($id) => FactionDefs::getInstance()->getByID($id))`
   - Update `fromArray()` → calls `parseMultiValueField()`

2. **Refactor `ModuleDef`:**
   - Location: `src/X4/Database/Modules/ModuleDef.php`
   - Apply same pattern as `ShipDef`
   - Methods: `getBuilderFactionID()`, `getBuilderFactionIDs()`, `hasMultipleBuilderFactions()`, `getBuilderFactions()`

3. **Refactor `ShieldDef`:**
   - Location: `src/X4/Database/Shields/ShieldDef.php`
   - Apply pattern for maker races
   - Methods: `getMakerRace()`, `getMakerRaces()`, `hasMultipleMakerRaces()`, `getMakerFactions()`

4. **Refactor `EngineDef`:**
   - Location: `src/X4/Database/Engines/EngineDef.php`
   - Apply pattern for maker races
   - Methods: `getMakerRace()`, `getMakerRaces()`, `hasMultipleMakerRaces()`, `getMakerFactions()`

5. **Refactor `WeaponDef`:**
   - Location: `src/X4/Database/Weapons/WeaponDef.php`
   - Apply pattern for maker races
   - Methods: `getMakerRace()`, `getMakerRaces()`, `hasMultipleMakerRaces()`, `getMakerFactions()`

6. **Verification per entity:**
   - After each entity refactoring, run `composer test`
   - Verify tests still pass (incremental safety)
   - Fix any issues before moving to next entity

### Acceptance Criteria

- [ ] All 5 entity classes use `MultiValueFieldTrait`
- [ ] `ShipDef` refactored and tests pass
- [ ] `ModuleDef` refactored and tests pass
- [ ] `ShieldDef` refactored and tests pass
- [ ] `EngineDef` refactored and tests pass
- [ ] `WeaponDef` refactored and tests pass
- [ ] All public API signatures unchanged
- [ ] All trait method calls use correct parameters
- [ ] `fromArray()` methods use `parseMultiValueField()`
- [ ] Zero breaking changes to public API
- [ ] Backward-compatible getters return first element
- [ ] Multi-value getters return full array

### Implementation Notes

**Integration Pattern:**
```php
class ShipDef
{
    use MultiValueFieldTrait;
    
    private array $builderFactionIDs;
    
    public function getBuilderFactionID(): string 
    {
        return $this->getSingleValue(
            $this->builderFactionIDs, 
            KnownFactions::FACTION_GENERIC
        );
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
        $ship = new self();
        $ship->builderFactionIDs = self::parseMultiValueField(
            $data,
            self::KEY_BUILDER_FACTION_IDS,
            self::KEY_BUILDER_FACTION_ID,
            KnownFactions::FACTION_GENERIC
        );
        // ... rest of fromArray() logic
        return $ship;
    }
}
```

**Critical Rule:**
- **NO PUBLIC API CHANGES**
- Method signatures must remain identical
- Return types must match exactly
- Exception types must match exactly

**Refactoring Strategy:**
- Work on one entity class at a time
- Run tests after each entity
- Commit after each successful entity refactoring
- This provides incremental safety and rollback points

---

## WP-006: Trait Abstraction - Testing & Verification

### Objective
Run comprehensive testing and verification to ensure the trait refactoring maintains 100% backward compatibility and introduces no regressions.

### Context
This is the critical quality gate that verifies the refactoring succeeded without breaking changes. All 777 tests must pass, PHPStan must be clean, and code metrics should show significant duplication reduction.

### Dependencies
- **WP-005** (all entities refactored)

### Assigned To
QA & Validation Agent

### Deliverables

1. **Full test suite execution:**
   - Run: `composer test`
   - Expected: 777 tests pass (773 existing + 4 new from WP-003)
   - Expected: 0 failures, 0 errors
   - Expected: 8 skipped (pre-existing)
   - Expected: 2 warnings (pre-existing, acceptable)
   - Document: Total assertions count

2. **Static analysis:**
   - Run: `composer analyze` (PHPStan)
   - Expected: Zero new warnings or errors
   - Document: PHPStan level and results

3. **Code metrics comparison:**
   - Count lines in entity classes before/after refactoring
   - Expected: ≥100 lines reduced across 5 classes
   - Document: Exact reduction achieved
   - Metric: Cyclomatic complexity unchanged or improved

4. **Backward compatibility verification:**
   - Manual inspection of public API signatures
   - Verify: All method signatures unchanged
   - Verify: Return types identical
   - Verify: Exception types identical

5. **Multi-faction entity verification:**
   - Run database rebuild (if needed)
   - Verify: Envoy ship still shows `builderFactionIDs: ["argon", "teladi"]`
   - Verify: JSON files unchanged (if no rebuild)

6. **Performance check:**
   - Run: `composer test` and note execution time
   - Compare: Baseline vs. post-refactoring time
   - Expected: No significant performance degradation (trait methods are inline)

### Acceptance Criteria

- [ ] 777 tests pass (773 existing + 4 new)
- [ ] Zero test failures
- [ ] Zero test errors
- [ ] Assertions count documented
- [ ] PHPStan passes with no new warnings
- [ ] Code duplication reduced by ≥100 lines
- [ ] Public API signatures unchanged (manual verification)
- [ ] Return types identical to pre-refactoring
- [ ] Envoy ship data verified (multi-faction example)
- [ ] Test execution time within acceptable range (no performance regression)
- [ ] All 5 entity classes successfully use trait

### Implementation Notes

**Test Execution Command:**
```bash
composer test
```

**Expected Output:**
```
OK (777 tests, 84,XXX assertions)
```

**Code Metrics:**
- Use `wc -l` or similar to count lines
- Focus on entity class files (ShipDef.php, ModuleDef.php, etc.)
- Compare before/after line counts
- Document in QA report

**Manual Verification Checklist:**
- [ ] `ShipDef::getBuilderFactionID(): string` unchanged
- [ ] `ShipDef::getBuilderFactionIDs(): array` unchanged
- [ ] `ShipDef::hasMultipleBuilderFactions(): bool` unchanged
- [ ] `ShipDef::getBuilderFactions(): array` unchanged
- [ ] (Repeat for all 5 entity classes)

**Rollback Trigger:**
- If any test fails, DO NOT PROCEED
- Investigate failure and fix in WP-005
- Do not mark WP-006 complete until all tests pass

---

## WP-007: Documentation Updates

### Objective
Update all 5 manifest documents to reflect the new trait implementation and ensure documentation is synchronized with the codebase.

### Context
Manifest documentation is the authoritative source of truth for AI agents. All architectural changes must be documented, including the new trait pattern, API additions, file locations, and conventions.

### Dependencies
- **WP-006** (implementation verified and complete)

### Assigned To
Documentation Agent

### Deliverables

1. **Update `tech-stack.md`:**
   - Location: `docs/agents/project-manifest/tech-stack.md`
   - Update: Pattern #9 (Multi-Value Faction/Race Pattern) to reference trait implementation
   - Add: Section on `MultiValueFieldTrait` in architectural patterns
   - Document: How the trait implements the pattern
   - Include: Code examples

2. **Update `api/database-core-api.md`:**
   - Location: `docs/agents/project-manifest/api/database-core-api.md`
   - Add: `MultiValueFieldTrait` documentation
   - Document: All 5 method signatures
   - Include: PHPDoc content
   - Add: Usage examples
   - Note: `protected` visibility (internal use only)

3. **Update `file-tree.md`:**
   - Location: `docs/agents/project-manifest/file-tree.md`
   - Add: `src/X4/Database/Core/MultiValueFieldTrait.php` to file tree
   - Place: Under `Database/Core/` section
   - Maintain: Tree structure formatting

4. **Update `constraints.md`:**
   - Location: `docs/agents/project-manifest/constraints.md`
   - Add: New section "Multi-Value Fields Convention"
   - Document: How to use `MultiValueFieldTrait` in new entity types
   - Include: Step-by-step implementation guide
   - Reference: `ShipDef` as canonical example

5. **Update `api/database-game-data-api.md`:**
   - Verify: `KnownFactions::FACTION_UNKNOWN` documented (from WP-001)
   - Ensure: Constant appears in `KnownFactions` constants list

6. **Update timestamps:**
   - Update: "Last Updated" dates in all modified documents
   - Set to: Current date (February 15, 2026)

### Acceptance Criteria

- [ ] `tech-stack.md` updated with trait reference in Pattern #9
- [ ] `tech-stack.md` includes trait architecture section
- [ ] `api/database-core-api.md` documents all 5 trait methods
- [ ] `api/database-core-api.md` includes usage examples
- [ ] `file-tree.md` includes trait file location
- [ ] `constraints.md` includes Multi-Value Fields Convention section
- [ ] `constraints.md` provides implementation guide for new entity types
- [ ] `api/database-game-data-api.md` includes `FACTION_UNKNOWN` constant
- [ ] All "Last Updated" dates current (February 15, 2026)
- [ ] No contradictions between code and documentation
- [ ] Documentation is clear and actionable for future agents

### Implementation Notes

**Pattern #9 Update Example:**
```markdown
## Pattern #9: Multi-Value Faction/Race Pattern

**Implementation:** `MultiValueFieldTrait` (`Database/Core/`)

Entities that support multiple factions or races use this trait to:
- Store values as arrays internally (`string[]`)
- Provide backward-compatible single-value API (returns first element)
- Provide new multi-value API (returns full array)
- Include predicate method (`hasMultiple*(): bool`)
- Handle format migration (old string → array, new array → array)

**Usage:** Entity classes use this trait and call protected methods.

**Example:**
```php
class ShipDef
{
    use MultiValueFieldTrait;
    
    public function getBuilderFactionID(): string {
        return $this->getSingleValue($this->builderFactionIDs, KnownFactions::FACTION_GENERIC);
    }
}
```
```

**Multi-Value Fields Convention (constraints.md):**
```markdown
### Multi-Value Fields Convention

When adding multi-value support to an entity type:

1. Use `MultiValueFieldTrait` from `Database/Core/`
2. Store field as `private array $fieldName = []`
3. Provide backward-compatible getter returning first element
4. Provide multi-value getter returning full array
5. Add predicate method `hasMultiple*(): bool`
6. Use `parseMultiValueField()` in `fromArray()`
7. Follow `ShipDef` as reference implementation

See: `tech-stack.md` Pattern #9 for details.
```

**Quality Check:**
- Read each updated document front-to-back
- Verify no contradictions with code
- Ensure examples are correct and runnable
- Check cross-references between documents

---

## WP-008: Final Validation & QA

### Objective
Conduct final validation to ensure all acceptance criteria across all work packages are met and the implementation is production-ready.

### Context
This is the final quality gate before marking the project complete. It verifies that all 4 refinements have been successfully implemented, documented, and tested.

### Dependencies
- **WP-007** (all work complete and documented)

### Assigned To
QA & Validation Agent

### Deliverables

1. **Acceptance criteria verification:**
   - Review all work packages (WP-001 through WP-007)
   - Verify each acceptance criterion is met
   - Document: Any unmet criteria (should be zero)

2. **Overall project criteria check:**
   - [ ] All 4 plan steps completed successfully
   - [ ] Total test count: 777 (773 existing + 4 new)
   - [ ] Zero test failures
   - [ ] Zero new PHPStan warnings
   - [ ] 5 manifest documents updated and synchronized
   - [ ] "Last Updated" dates current
   - [ ] No breaking changes to any public API
   - [ ] Backward compatibility maintained 100%

3. **Final test execution:**
   - Run: `composer test`
   - Expected: 777 tests pass
   - Run: `composer analyze`
   - Expected: Clean (no new warnings)

4. **Documentation completeness:**
   - Verify: All manifest documents updated
   - Verify: No contradictions between docs and code
   - Verify: Cross-references accurate

5. **Code quality review:**
   - Verify: No magic strings remain
   - Verify: Logging severity changed to INFO
   - Verify: All 5 entity classes use trait
   - Verify: PHPDoc complete on trait

6. **Production readiness assessment:**
   - Risk level: LOW / MEDIUM / HIGH
   - Breaking changes: YES / NO
   - Technical debt: INTRODUCED / REDUCED / NONE
   - Recommendation: APPROVE / CONDITIONAL / REJECT

### Acceptance Criteria

- [ ] All work packages (WP-001 through WP-007) acceptance criteria met
- [ ] Overall project acceptance criteria verified
- [ ] 777 tests pass (final execution)
- [ ] PHPStan clean (final execution)
- [ ] All manifest documents complete and accurate
- [ ] No contradictions found
- [ ] Production readiness: APPROVED
- [ ] Risk level: LOW
- [ ] Breaking changes: NONE
- [ ] Technical debt: REDUCED

### Implementation Notes

**Verification Process:**
1. Systematically check each WP's acceptance criteria
2. Document any discrepancies
3. If discrepancies found, return to relevant WP for fixes
4. Only mark complete when 100% criteria met

**Production Readiness Criteria:**
- All tests pass
- No breaking changes
- Documentation complete
- Code quality high
- No known bugs
- No critical issues

**Final Report (template):**
```markdown
## Final Validation Report

**Date:** February 15, 2026
**Validator:** QA & Validation Agent
**Project:** Multi-Value Pattern Refinements

### Summary
All 8 work packages completed successfully.

### Test Results
- Tests Run: 777
- Tests Passed: 777
- Tests Failed: 0
- Assertions: XX,XXX

### Code Quality
- PHPStan: PASS (no new warnings)
- Magic Strings: 0 remaining
- Code Duplication: Reduced by XXX lines
- Trait Reuse: 5 classes

### Documentation
- Manifest Documents Updated: 5
- Contradictions Found: 0
- Cross-references: Accurate

### Production Readiness
- **Status:** APPROVED
- **Risk Level:** LOW
- **Breaking Changes:** NONE
- **Technical Debt:** REDUCED

### Recommendation
✅ Production-ready. Safe to deploy.
```

**Success Criteria:**
Project is successful if:
- All 8 work packages complete
- All acceptance criteria met
- Zero breaking changes
- Code quality improved (duplication reduced)
- Documentation synchronized

---

## Implementation Sequence

### Phase 1: Foundation (Parallel Execution)
**Duration:** ~25 minutes  
**Work Packages:** WP-001, WP-002

These are independent and can be executed in parallel:
- WP-001: Magic String Elimination (15 min)
- WP-002: Logging Severity Adjustment (10 min)

**Output:** Constants defined, logging severity improved

---

### Phase 2: Test Enhancement
**Duration:** 2-3 hours  
**Work Packages:** WP-003

**Dependencies:** WP-001 (uses `FACTION_UNKNOWN`)

This establishes comprehensive test coverage before refactoring:
- WP-003: Enhanced Test Coverage (2-3 hours)

**Output:** 4 new tests, 777 total tests, ≥90% coverage on parsing logic

---

### Phase 3: Trait Abstraction (Sequential Execution)
**Duration:** 4-5 hours  
**Work Packages:** WP-004, WP-005, WP-006

Must be executed sequentially:

1. **WP-004: Trait Creation** (1.5 hours)
   - Dependencies: WP-001
   - Creates the trait with 5 methods
   - Comprehensive PHPDoc

2. **WP-005: Entity Refactoring** (2.5 hours)
   - Dependencies: WP-004
   - Refactors 5 entity classes to use trait
   - Incremental testing after each entity

3. **WP-006: Testing & Verification** (1 hour)
   - Dependencies: WP-005
   - Full regression testing
   - Code metrics comparison
   - Quality gate

**Output:** Trait implemented, 5 entities refactored, all tests passing

---

### Phase 4: Finalization
**Duration:** 1.5 hours  
**Work Packages:** WP-007, WP-008

Sequential execution:

1. **WP-007: Documentation** (1 hour)
   - Dependencies: WP-006
   - Updates 5 manifest documents
   - Synchronizes with implementation

2. **WP-008: Final Validation** (30 min)
   - Dependencies: WP-007
   - Comprehensive QA
   - Production readiness assessment

**Output:** Documentation complete, project validated, production-ready

---

## Total Estimated Timeline

| Phase | Duration | Critical Path |
|-------|----------|---------------|
| Phase 1 | 25 min | Yes (WP-001 required for WP-003/004) |
| Phase 2 | 2-3 hours | Yes (required for Phase 3) |
| Phase 3 | 4-5 hours | Yes (core refactoring) |
| Phase 4 | 1.5 hours | Yes (completion) |
| **Total** | **7.5-10 hours** | **End-to-end** |

**Minimum:** 7.5 hours (with parallel Phase 1 + efficient execution)  
**Expected:** 8-9 hours (typical case with some debugging)  
**Maximum:** 10 hours (with unexpected issues)

---

## Risk Mitigation

### High-Priority Risks

1. **Trait refactoring introduces regressions**
   - Mitigation: Comprehensive test suite (777 tests) provides safety net
   - Mitigation: Incremental entity refactoring (WP-005)
   - Mitigation: Test after each entity before proceeding

2. **Breaking changes to public API**
   - Mitigation: Strict acceptance criteria requiring signature verification
   - Mitigation: Manual API inspection in WP-006
   - Mitigation: Backward compatibility explicitly tested

3. **Test coverage insufficient**
   - Mitigation: WP-003 explicitly adds 4 edge case tests
   - Mitigation: Coverage requirement ≥90% on parsing logic
   - Mitigation: Tests added BEFORE refactoring (safety baseline)

4. **Documentation out of sync**
   - Mitigation: Mandatory WP-007 with specific document checklist
   - Mitigation: Cross-reference verification in WP-008
   - Mitigation: Cannot mark complete until docs synchronized

### Rollback Plan

If critical issues discovered in WP-006:
1. Identify problematic entity class
2. Revert that entity's refactoring (git revert)
3. Re-run tests
4. Fix issue in WP-005
5. Retry WP-006

If entire approach fails:
1. Revert all entity changes
2. Delete trait file
3. Run `composer dump-autoload`
4. Verify 773 tests pass (back to baseline)
5. Keep WP-001, WP-002, WP-003 improvements (low risk)

---

## Success Metrics

### Quantitative Metrics

- **Code Duplication:** Reduced by ≥100 lines (target: ~120 lines)
- **Test Count:** Increased by 4 tests (773 → 777)
- **Test Pass Rate:** 100% (0 failures)
- **Magic Strings:** Reduced from 3 to 0
- **Trait Reuse:** 5 entity classes using shared pattern
- **Code Coverage:** ≥90% on parsing logic

### Qualitative Metrics

- **Maintainability:** Improved (single source of truth for pattern)
- **Consistency:** Improved (all 5 entities identical pattern)
- **Developer Experience:** Improved (reduced log noise)
- **Code Quality:** Improved (no magic strings)
- **Confidence:** High (comprehensive edge case coverage)
- **Documentation:** Complete (manifest synchronized)

### Definition of Success

This project is **successful** if:
1. ✅ All 8 work packages completed
2. ✅ All acceptance criteria met (100%)
3. ✅ Zero breaking changes
4. ✅ Code duplication reduced >100 lines
5. ✅ All 777 tests pass
6. ✅ Manifest documentation complete
7. ✅ Production readiness: APPROVED

---

## Communication & Handoff

### For Next Agent (Lead Implementation Engineer)

**Starting Point:** WP-001  
**First Actions:**
1. Read this work.md document completely
2. Read plan.md for detailed context
3. Review ledger.json for project state
4. Start with WP-001 (Magic String Elimination)
5. Update ledger.json when starting and completing each WP

**Key Reminders:**
- WP-001 and WP-002 can be done in parallel
- WP-003 requires WP-001 complete
- WP-004 is critical foundation for WP-005/006
- Test after each entity refactoring in WP-005
- Update ledger.json with status and artifacts

### For QA Agent

**Entry Points:** WP-006, WP-008  
**Responsibilities:**
1. Comprehensive testing in WP-006
2. Code metrics comparison
3. Backward compatibility verification
4. Final validation in WP-008
5. Production readiness assessment

**Acceptance Gates:**
- WP-006: All 777 tests must pass (gate to WP-007)
- WP-008: All criteria met (gate to COMPLETE)

### For Documentation Agent

**Entry Point:** WP-007  
**Responsibilities:**
1. Update 5 manifest documents
2. Ensure synchronization with code
3. Update timestamps
4. Verify cross-references

**Quality Standards:**
- Zero contradictions with code
- All examples correct and runnable
- Clear and actionable guidance
- Cross-references accurate

---

**END OF WORK PACKAGES DOCUMENT**

**Document Version:** 1.0  
**Created:** February 15, 2026  
**Last Updated:** February 15, 2026  
**Status:** READY FOR ENGINEERING
