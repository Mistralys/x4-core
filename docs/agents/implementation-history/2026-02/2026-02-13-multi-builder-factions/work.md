# Work Packages: Multi-Builder Faction Support

**Plan:** [plan.md](plan.md)  
**Created:** 2026-02-13  
**Status:** READY_FOR_ENGINEERING

---

## Work Package Overview

| WP ID  | Title                              | Depends On          | Plan Steps | Priority | Assigned To                      |
|--------|------------------------------------|---------------------|------------|----------|----------------------------------|
| WP-001 | ShipDef Multi-Faction Support      | —                   | 1–10       | Critical | Lead Implementation Engineer     |
| WP-002 | ModuleDef Multi-Faction Support    | WP-001              | 11–14      | Normal   | Lead Implementation Engineer     |
| WP-003 | ShieldDef Multi-Faction Support    | WP-001              | 15–18      | Normal   | Lead Implementation Engineer     |
| WP-004 | EngineDef Multi-Faction Support    | WP-001              | 19–22      | Normal   | Lead Implementation Engineer     |
| WP-005 | WeaponDef Multi-Faction Support    | WP-001              | 23–26      | Normal   | Lead Implementation Engineer     |
| WP-006 | Tests                              | WP-001 … WP-005    | 27–32      | High     | Lead Implementation Engineer     |
| WP-007 | Database Rebuild & Verification    | WP-006              | 33–35      | High     | Lead Implementation Engineer     |
| WP-008 | Manifest Updates                   | WP-007              | 36–41      | Normal   | Documentation Agent              |

### Dependency Graph

```
WP-001 (ShipDef — critical fix)
  ├──→ WP-002 (ModuleDef)  ──┐
  ├──→ WP-003 (ShieldDef)  ──┤
  ├──→ WP-004 (EngineDef)  ──┼──→ WP-006 (Tests) ──→ WP-007 (Rebuild) ──→ WP-008 (Manifest)
  └──→ WP-005 (WeaponDef)  ──┘
```

> **Note:** WP-002 through WP-005 are independent of each other and may be implemented in any order or in parallel. All four must complete before WP-006 can start.

---

## WP-001: ShipDef Multi-Faction Support

**Priority:** Critical (fixes the production bug)  
**Plan Steps:** 1–10  
**Dependencies:** None  
**Assigned To:** Lead Implementation Engineer

### Objective

Implement multi-builder-faction support in the Ship domain. This is the critical fix — `ShipDef` currently stores `builderFactionID` as a single string, which causes `FactionDefs::getByID()` to throw `RecordNotExistsException` when it encounters compound values like `"argon teladi"`.

### Files to Modify

| File | Change Summary |
|------|---------------|
| `src/X4/Database/Ships/ShipDef.php` | Internal storage `string` → `string[]`, new constants, updated `fromArray()`/`toArray()`, new public API methods |
| `src/X4/Database/Ships/ShipDefs.php` | `getFactions()` must unpack multi-faction entries |
| `src/X4/Database/Ships/ShipFinder.php` | `isMatch()` faction filter uses intersection-based matching |
| `src/X4/Database/Ships/ShipsExtractor.php` | `resolveFaction()` returns `string[]`, splits space-separated attribute |

### Detailed Steps

1. **Add new constant** to `ShipDef`:
   - `KEY_BUILDER_FACTION_IDS = 'builderFactionIDs'` (new JSON array key).
   - Keep `KEY_BUILDER_FACTION_ID` for backward-compatible reading of old-format data.

2. **Change internal storage** in `ShipDef`:
   - Replace `private string $builderFactionID` with `private array $builderFactionIDs` (typed `string[]`).
   - Constructor parameter changes from `string $builderFactionID` to `array $builderFactionIDs`.

3. **Update `fromArray()`** in `ShipDef`:
   - Read new `KEY_BUILDER_FACTION_IDS` (array format) if present.
   - Fallback: read old `KEY_BUILDER_FACTION_ID` (string) and `explode(' ', ...)`.
   - If resulting array is empty, default to `[KnownFactions::FACTION_GENERIC]`.

4. **Update `toArray()`** in `ShipDef`:
   - Write `KEY_BUILDER_FACTION_IDS => $this->builderFactionIDs`.
   - Remove the old `KEY_BUILDER_FACTION_ID` key from output.

5. **Preserve backward-compatible API**:
   - `getBuilderFactionID(): string` — returns `$this->builderFactionIDs[0]`.
   - `getBuilderFaction(): FactionDef` — unchanged logic, calls `getBuilderFactionID()`.

6. **Add new API methods** to `ShipDef`:
   - `getBuilderFactionIDs(): string[]` — returns the full array.
   - `getBuilderFactions(): FactionDef[]` — returns `FactionDef` instances for all builder factions.
   - `hasMultipleBuilderFactions(): bool` — returns `count($this->builderFactionIDs) > 1`.

7. **Update `ShipDefs::getFactions()`** to iterate with `$ship->getBuilderFactionIDs()` and collect unique `FactionDef` instances.

8. **Update `ShipFinder::isMatch()`** — when `builderFactions` filter is set, use `array_intersect()` between `$ship->getBuilderFactionIDs()` and the filter set. If intersection is empty → no match.

9. **Update `ShipsExtractor::resolveFaction()`**:
   - Change return type from `string` to `string[]`.
   - `explode(' ', $makerrace)` and apply existing fallback logic.
   - Update callers to pass array into `ShipDef` constructor.

10. **Verify** the changes compile and existing code paths are preserved.

### Acceptance Criteria

- `ShipDef::getBuilderFactionID()` returns `string` (first faction) — no breaking change.
- `ShipDef::getBuilderFactionIDs()` returns `string[]` with all factions.
- `ShipDef::getBuilderFactions()` returns `FactionDef[]` for all factions.
- `ShipDef::hasMultipleBuilderFactions()` returns `true` for compound entries.
- `ShipDef::fromArray()` handles both old (`builderFactionID: "argon teladi"`) and new (`builderFactionIDs: ["argon", "teladi"]`) formats.
- `ShipDef::toArray()` writes new array format under `builderFactionIDs`.
- `ShipDefs::getFactions()` includes all factions from multi-builder ships.
- `ShipFinder` matches multi-faction ships when filtering by any one of their factions.
- `ShipsExtractor::resolveFaction()` returns `string[]`.

---

## WP-002: ModuleDef Multi-Faction Support

**Priority:** Normal (preventive)  
**Plan Steps:** 11–14  
**Dependencies:** WP-001  
**Assigned To:** Lead Implementation Engineer

### Objective

Apply the same multi-faction pattern established in WP-001 to `ModuleDef`. Currently module data does not contain multi-faction entries, but the pattern is applied preventively so future game updates introducing them will "just work."

### Files to Modify

| File | Change Summary |
|------|---------------|
| `src/X4/Database/Modules/ModuleDef.php` | Internal storage `string` → `string[]`, new constants, updated `fromArray()`/`toArray()`, new public API methods |
| `src/X4/Database/Modules/ModuleMacroExtractor.php` | `resolveFactionID()` returns `string[]`, splits space-separated attribute |

### Detailed Steps

11. **Apply same pattern as ShipDef to `ModuleDef`**:
    - Add `KEY_BUILDER_FACTION_IDS` constant.
    - Change internal storage from `string $builderFactionID` to `array $builderFactionIDs`.
    - Update `fromArray()` / `toArray()` with format migration (read new array format, fallback to old string, split by space).
    - Preserve `getBuilderFactionID(): string` (returns first element).
    - Add `getBuilderFactionIDs(): string[]`, `getBuilderFactions(): FactionDef[]`, `hasMultipleBuilderFactions(): bool`.

12. **Update `ModuleMacroExtractor::resolveFactionID()`**:
    - Change return type from `?string` to `string[]`.
    - Split raw `makerrace` by spaces.
    - Apply existing fallback logic (generic default) to produce an array.

13. **Update `ModuleFinder`** (if it has faction filtering) to use intersection matching. If no faction filter exists, no action needed.

14. **Verify** the changes compile and existing module code paths are preserved.

### Acceptance Criteria

- `ModuleDef::getBuilderFactionID()` returns `string` (first faction) — no breaking change.
- `ModuleDef::getBuilderFactionIDs()` returns `string[]`.
- `ModuleDef::getBuilderFactions()` returns `FactionDef[]`.
- `ModuleDef::hasMultipleBuilderFactions()` returns `bool`.
- `fromArray()` handles both old and new JSON formats.
- `toArray()` writes new array format.
- `ModuleMacroExtractor::resolveFactionID()` returns `string[]`.

---

## WP-003: ShieldDef Multi-Faction Support

**Priority:** Normal (fixes existing data issue in shields)  
**Plan Steps:** 15–18  
**Dependencies:** WP-001  
**Assigned To:** Lead Implementation Engineer

### Objective

Apply the multi-faction pattern to `ShieldDef` using the `makerRace` / `makerRaces` field naming convention. Shield data already contains compound entries like `"argon teladi"`.

### Files to Modify

| File | Change Summary |
|------|---------------|
| `src/X4/Database/Shields/ShieldDef.php` | Internal storage `string` → `string[]`, new constants (`KEY_MAKER_RACES`), updated `fromArray()`/`toArray()`, new public API methods |
| `src/X4/Database/Shields/ShieldMacroExtractor.php` | `resolveMakerRace()` returns `string[]` |
| `src/X4/Database/Shields/ShieldFinder.php` | `selectMakerRace()` filter uses intersection matching |

### Detailed Steps

15. **Apply pattern to `ShieldDef`**:
    - Add `KEY_MAKER_RACES = 'makerRaces'` constant.
    - Change `private string $makerRace` to `private array $makerRaces` (`string[]`).
    - Update constructor, `fromArray()`, `toArray()`.
    - Preserve `getMakerRace(): string` (returns first element).
    - Add `getMakerRaces(): string[]`, `hasMultipleMakerRaces(): bool`.

16. **Update `ShieldMacroExtractor::resolveMakerRace()`**:
    - Return `string[]` (split by space).

17. **Update `ShieldFinder::selectMakerRace()`** to use intersection matching in `isMatch()`.

18. **Verify** the changes compile and existing shield code paths are preserved.

### Acceptance Criteria

- `ShieldDef::getMakerRace()` returns `string` (first race) — no breaking change.
- `ShieldDef::getMakerRaces()` returns `string[]`.
- `ShieldDef::hasMultipleMakerRaces()` returns `bool`.
- `fromArray()` handles both old (`makerRace: "argon teladi"`) and new (`makerRaces: ["argon", "teladi"]`) formats.
- `toArray()` writes new array format under `makerRaces`.
- `ShieldFinder` matches multi-race shields when filtering by any one of their races.
- `ShieldMacroExtractor::resolveMakerRace()` returns `string[]`.

---

## WP-004: EngineDef Multi-Faction Support

**Priority:** Normal (preventive)  
**Plan Steps:** 19–22  
**Dependencies:** WP-001  
**Assigned To:** Lead Implementation Engineer

### Objective

Apply the multi-faction pattern to `EngineDef` using the `makerRace` / `makerRaces` field naming convention. Preventive — engine data does not currently contain compound entries.

### Files to Modify

| File | Change Summary |
|------|---------------|
| `src/X4/Database/Engines/EngineDef.php` | Internal storage `string` → `string[]`, new constants, updated `fromArray()`/`toArray()`, new public API methods |
| `src/X4/Database/Engines/EngineMacroExtractor.php` | `resolveMakerRace()` returns `string[]` |
| `src/X4/Database/Engines/EngineFinder.php` | `selectMakerRace()` filter uses intersection matching |

### Detailed Steps

19. **Apply same pattern to `EngineDef`**:
    - Same changes as ShieldDef: `KEY_MAKER_RACES`, array storage, preserved `getMakerRace()`, new `getMakerRaces()` / `hasMultipleMakerRaces()`.

20. **Update `EngineMacroExtractor::resolveMakerRace()`** to return `string[]`.

21. **Update `EngineFinder::selectMakerRace()`** to use intersection matching in `isMatch()`.

22. **Verify** the changes compile and existing engine code paths are preserved.

### Acceptance Criteria

- `EngineDef::getMakerRace()` returns `string` (first race) — no breaking change.
- `EngineDef::getMakerRaces()` returns `string[]`.
- `EngineDef::hasMultipleMakerRaces()` returns `bool`.
- `fromArray()` handles both old and new JSON formats.
- `toArray()` writes new array format.
- `EngineFinder` matches multi-race engines when filtering by any one of their races.
- `EngineMacroExtractor::resolveMakerRace()` returns `string[]`.

---

## WP-005: WeaponDef Multi-Faction Support

**Priority:** Normal (preventive)  
**Plan Steps:** 23–26  
**Dependencies:** WP-001  
**Assigned To:** Lead Implementation Engineer

### Objective

Apply the multi-faction pattern to `WeaponDef` using the `makerRace` / `makerRaces` field naming convention. Preventive — weapon data does not currently contain compound entries.

### Files to Modify

| File | Change Summary |
|------|---------------|
| `src/X4/Database/Weapons/WeaponDef.php` | Internal storage `string` → `string[]`, new constants, updated `fromArray()`/`toArray()`, new public API methods |
| `src/X4/Database/Weapons/WeaponMacroExtractor.php` | `resolveMakerRace()` returns `string[]` |
| `src/X4/Database/Weapons/WeaponFinder.php` | `selectMakerRace()` filter uses intersection matching |

### Detailed Steps

23. **Apply same pattern to `WeaponDef`**:
    - Same changes as ShieldDef/EngineDef: `KEY_MAKER_RACES`, array storage, preserved `getMakerRace()`, new `getMakerRaces()` / `hasMultipleMakerRaces()`.

24. **Update `WeaponMacroExtractor::resolveMakerRace()`** to return `string[]`.

25. **Update `WeaponFinder::selectMakerRace()`** to use intersection matching in `isMatch()`.

26. **Verify** the changes compile and existing weapon code paths are preserved.

### Acceptance Criteria

- `WeaponDef::getMakerRace()` returns `string` (first race) — no breaking change.
- `WeaponDef::getMakerRaces()` returns `string[]`.
- `WeaponDef::hasMultipleMakerRaces()` returns `bool`.
- `fromArray()` handles both old and new JSON formats.
- `toArray()` writes new array format.
- `WeaponFinder` matches multi-race weapons when filtering by any one of their races.
- `WeaponMacroExtractor::resolveMakerRace()` returns `string[]`.

---

## WP-006: Tests

**Priority:** High  
**Plan Steps:** 27–32  
**Dependencies:** WP-001, WP-002, WP-003, WP-004, WP-005  
**Assigned To:** Lead Implementation Engineer

### Objective

Update existing tests and add new tests to validate multi-faction behavior across all entity types. Run full test suite and PHPStan static analysis.

### Files to Modify

| File | Change Summary |
|------|---------------|
| `tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php` | Fix `test_factionsHaveShips()`, add `test_multiBuilderFaction()`, add `test_finderMatchesMultiFaction()` |

### Detailed Steps

27. **Update `ShipCollectionTests::test_factionsHaveShips()`** to pass with the new multi-faction data format.

28. **Add `test_multiBuilderFaction()`** in `ShipCollectionTests`:
    - Load the Envoy ship by ID `ship_gen_m_corvette_01`.
    - Assert `hasMultipleBuilderFactions()` returns `true`.
    - Assert `getBuilderFactionIDs()` returns `['argon', 'teladi']`.
    - Assert `getBuilderFactionID()` returns `'argon'` (the primary).
    - Assert `getBuilderFaction()->getID()` returns `'argon'`.
    - Assert `getBuilderFactions()` returns two `FactionDef` instances.

29. **Add `test_finderMatchesMultiFaction()`**:
    - Use `ShipFinder::selectBuilderFaction('argon')` — assert Envoy is in results.
    - Use `ShipFinder::selectBuilderFaction('teladi')` — assert Envoy is in results.

30. **Add shield test: `test_multiMakerRace()`** (in appropriate test class):
    - Load shield `shield_gen_m_corvette_01_mk1`.
    - Assert `getMakerRaces()` returns `['argon', 'teladi']`.
    - Assert `getMakerRace()` returns `'argon'`.
    - Assert `hasMultipleMakerRaces()` returns `true`.

31. **Run full test suite** (`composer test`) — all tests must pass.

32. **Run PHPStan** (`composer analyze`) — fix any static analysis issues.

### Acceptance Criteria

- `test_factionsHaveShips()` passes with new data format.
- `test_multiBuilderFaction()` passes — validates all new ShipDef API methods.
- `test_finderMatchesMultiFaction()` passes — validates ShipFinder intersection matching.
- `test_multiMakerRace()` passes — validates ShieldDef multi-race support.
- Full PHPUnit suite passes (`composer test`).
- PHPStan analysis passes (`composer analyze`).

---

## WP-007: Database Rebuild & Verification

**Priority:** High  
**Plan Steps:** 33–35  
**Dependencies:** WP-006  
**Assigned To:** Lead Implementation Engineer

### Objective

Rebuild all JSON data files with the new array format and verify correctness.

### Files Affected (Regenerated)

| File | Change |
|------|--------|
| `data/ships.json` | `builderFactionID` → `builderFactionIDs` (array format) |
| `data/modules.json` | `builderFactionID` → `builderFactionIDs` (array format) |
| `data/shields.json` | `makerRace` → `makerRaces` (array format) |
| `data/engines.json` | `makerRace` → `makerRaces` (array format) |
| `data/weapons.json` | `makerRace` → `makerRaces` (array format) |

### Detailed Steps

33. **Run `composer build`** to regenerate all JSON data files with the new array format.

34. **Verify** that `ships.json` now contains `"builderFactionIDs": ["argon", "teladi"]` for the Envoy ship (`ship_gen_m_corvette_01`).

35. **Verify** that `shields.json` now contains `"makerRaces": ["argon", "teladi"]` for multi-race shields.

### Acceptance Criteria

- `composer build` completes without errors.
- `ships.json` uses `builderFactionIDs` (array) instead of `builderFactionID` (string).
- `modules.json` uses `builderFactionIDs` (array).
- `shields.json` uses `makerRaces` (array) instead of `makerRace` (string).
- `engines.json` uses `makerRaces` (array).
- `weapons.json` uses `makerRaces` (array).
- Envoy ship has `"builderFactionIDs": ["argon", "teladi"]`.
- Multi-race shields have properly split arrays.

---

## WP-008: Manifest Updates

**Priority:** Normal  
**Plan Steps:** 36–41  
**Dependencies:** WP-007  
**Assigned To:** Documentation Agent

### Objective

Update all project manifest documents to reflect the multi-builder-faction changes. This ensures future agents have correct documentation.

### Files to Modify

| File | Change Summary |
|------|---------------|
| `docs/agents/project-manifest/public-api.md` | Add new methods for all 5 entity types, update constants |
| `docs/agents/project-manifest/tech-stack.md` | Note multi-valued builder faction in Collection-Item Pattern |
| `docs/agents/project-manifest/data-flows.md` | Update Database Build Flow noting faction array parsing |
| `docs/agents/project-manifest/constraints.md` | Add multi-builder-faction handling convention |
| `docs/agents/project-manifest/extraction-reference.md` | Note `makerrace` attribute can be space-separated |

### Detailed Steps

36. **Update `public-api.md`**:
    - **ShipDef**: Add `getBuilderFactionIDs(): string[]`, `getBuilderFactions(): FactionDef[]`, `hasMultipleBuilderFactions(): bool`, constant `KEY_BUILDER_FACTION_IDS`.
    - **ModuleDef**: Add `getBuilderFactionIDs(): string[]`, `getBuilderFactions(): FactionDef[]`, `hasMultipleBuilderFactions(): bool`, constant `KEY_BUILDER_FACTION_IDS`.
    - **ShieldDef**: Add `getMakerRaces(): string[]`, `hasMultipleMakerRaces(): bool`, constant `KEY_MAKER_RACES`.
    - **EngineDef**: Add `getMakerRaces(): string[]`, `hasMultipleMakerRaces(): bool`, constant `KEY_MAKER_RACES`.
    - **WeaponDef**: Add `getMakerRaces(): string[]`, `hasMultipleMakerRaces(): bool`, constant `KEY_MAKER_RACES`.
    - Update existing constant sections for all affected classes.

37. **Update `tech-stack.md`**:
    - In Collection-Item Pattern section, note that builder faction / maker race fields are now multi-valued (array of strings), with the first entry being the "primary" for backward-compatible single-value access.

38. **Update `data-flows.md`**:
    - In Database Build Flow, note that faction/race extraction now splits space-separated `makerrace` XML attributes into arrays.

39. **Update `constraints.md`**:
    - Add convention: multi-builder-faction handling uses "primary = first in list" pattern. Single-value getters always return the first element.

40. **Update `file-tree.md`** only if new files were added (unlikely — skip if no new files).

41. **Update `extraction-reference.md`** (or sub-documents) noting the `makerrace` attribute can contain space-separated faction IDs.

### Acceptance Criteria

- `public-api.md` documents all new methods and constants for all 5 entity types.
- `tech-stack.md` notes multi-valued builder faction pattern.
- `data-flows.md` notes array parsing in build flow.
- `constraints.md` documents the "primary = first in list" convention.
- `extraction-reference.md` notes space-separated `makerrace` handling.
- No contradictions between manifest and implementation.

---

## Implementation Notes

### Pattern Reference

All entity types follow the same transformation pattern. The Ship implementation (WP-001) serves as the reference implementation. WP-002 through WP-005 replicate the pattern with field name adjustments:

| Concept | Ships / Modules | Shields / Engines / Weapons |
|---------|----------------|-----------------------------|
| Old field | `builderFactionID` | `makerRace` |
| New field | `builderFactionIDs` | `makerRaces` |
| Old constant | `KEY_BUILDER_FACTION_ID` | `KEY_MAKER_RACE` |
| New constant | `KEY_BUILDER_FACTION_IDS` | `KEY_MAKER_RACES` |
| Single getter | `getBuilderFactionID()` | `getMakerRace()` |
| Array getter | `getBuilderFactionIDs()` | `getMakerRaces()` |
| Boolean check | `hasMultipleBuilderFactions()` | `hasMultipleMakerRaces()` |
| FactionDef getter | `getBuilderFactions()` | — (not cross-referenced) |

### Backward Compatibility Contract

- **No return type changes** on existing public methods.
- **No removed methods or constants**.
- `fromArray()` must handle both old and new JSON formats.
- `toArray()` writes only the new format.

### Risks

- **ModuleFinder faction filtering**: Plan step 13 mentions updating ModuleFinder if it has faction filtering. The implementation engineer must verify whether this filter exists. If not, no action is needed.
- **Test data assumptions**: Shield test (step 30) assumes `shield_gen_m_corvette_01_mk1` has compound makerRace. Verify against actual data during implementation; adjust test target if needed.
