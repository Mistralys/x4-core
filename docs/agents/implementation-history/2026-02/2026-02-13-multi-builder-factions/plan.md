# Plan: Multi-Builder Faction Support

## Summary

Ships and equipment items (shields, potentially engines/weapons/modules in the future) can now have multiple builder factions in the game data, stored as space-separated strings in the XML `makerrace` attribute (e.g., `"argon teladi"` for the Envoy corvette). The current codebase treats this field as a single faction ID, causing `FactionDefs::getByID()` to throw a `RecordNotExistsException` when it encounters a compound ID. This plan introduces multi-builder-faction support while preserving full backward compatibility of the existing public API.

## Approach / Architecture

The strategy uses the **"primary faction + full list"** approach:

1. **Parse** space-separated faction strings at the point of entry (`fromArray()` deserialization in all affected `*Def` classes).
2. **Store** both the original compound string and the parsed array of faction IDs internally.
3. **Preserve** existing single-value API methods (`getBuilderFactionID()`, `getBuilderFaction()`, `getMakerRace()`) to return the **first** faction ID from the list — ensuring zero breaking changes for downstream consumers.
4. **Add** new methods to expose the full list and a convenience boolean check.
5. **Fix** filtering logic (`ShipFinder`, `ShieldFinder`, `EngineFinder`, `WeaponFinder`) to use intersection-based matching so that filtering by any one of a multi-faction item's factions will correctly match it.
6. **Fix** aggregation logic (`ShipDefs::getFactions()`) to unpack multi-faction entries.

### Affected Entity Types & Current State

| Entity | Field Name | Key Constant | Currently Multi-Faction? | Cross-refs FactionDefs? |
|--------|-----------|--------------|--------------------------|-------------------------|
| **ShipDef** | `builderFactionID` | `KEY_BUILDER_FACTION_ID` | YES (`"argon teladi"`) | YES |
| **ModuleDef** | `builderFactionID` | `KEY_BUILDER_FACTION_ID` | No (but possible in future) | YES |
| **ShieldDef** | `makerRace` | `KEY_MAKER_RACE` | YES (`"argon teladi"`) | No |
| **EngineDef** | `makerRace` | `KEY_MAKER_RACE` | No (but possible in future) | No |
| **WeaponDef** | `makerRace` | `KEY_MAKER_RACE` | No (but possible in future) | No |

### Data Format

The JSON data files (`ships.json`, `shields.json`, `engines.json`, `weapons.json`, `modules.json`) will change the storage format for the faction/race field from a single string to an array of strings:

**Before:**
```json
{ "builderFactionID": "argon teladi" }
```

**After:**
```json
{ "builderFactionIDs": ["argon", "teladi"] }
```

This applies similarly to `makerRace` → `makerRaces` in shields, engines, and weapons.

> **Note:** The JSON key name change (`builderFactionID` → `builderFactionIDs`, `makerRace` → `makerRaces`) is a **data format migration**. The `fromArray()` methods must handle both old and new formats for a smooth transition. The existing constants should remain for backward compatibility reading, and new constants should be added for the array format.

## Rationale

- **Backward compatibility:** The existing `getBuilderFactionID()` / `getMakerRace()` single-string methods are used throughout the codebase and potentially by external consumers. Changing their return type would be a breaking change. By returning the first faction from the list, all existing code continues to work without modification.
- **Correctness:** The finder filter logic must change to intersection-based matching so that searching for "argon" faction ships correctly returns the Envoy. This is a bug fix, not a behavioral change from the user's perspective.
- **Future-proofing:** Applying the pattern consistently across all five entity types (ships, modules, shields, engines, weapons) means future game updates adding multi-faction entries to other types will "just work" without additional code changes.
- **Convention:** Using a `string[]` internal representation with a primary-first convention mirrors how `WareDef` already handles multiple owner factions (`getFactionIDs(): string[]`).

## Detailed Steps

### Phase 1: ShipDef Multi-Faction Support (Critical — Fixes the Bug)

1. **Add new constants** to `ShipDef`:
   - `KEY_BUILDER_FACTION_IDS = 'builderFactionIDs'` (new JSON array key)
   - Keep `KEY_BUILDER_FACTION_ID` for backward-compatible reading.

2. **Change internal storage** in `ShipDef`:
   - Replace `private string $builderFactionID` with `private array $builderFactionIDs` (typed `string[]`).
   - Constructor parameter changes from `string $builderFactionID` to `array $builderFactionIDs`.

3. **Update `fromArray()`** in `ShipDef`:
   - Read new `KEY_BUILDER_FACTION_IDS` (array format) if present.
   - Fallback: read old `KEY_BUILDER_FACTION_ID` (string format) and split by space.
   - If the resulting array is empty, default to `[KnownFactions::FACTION_GENERIC]`.

4. **Update `toArray()`** in `ShipDef`:
   - Write `KEY_BUILDER_FACTION_IDS => $this->builderFactionIDs`.
   - Remove the old `KEY_BUILDER_FACTION_ID` key.

5. **Preserve existing API** — ensure backward compatibility:
   - `getBuilderFactionID(): string` — returns `$this->builderFactionIDs[0]` (the primary/first faction).
   - `getBuilderFaction(): FactionDef` — unchanged logic, calls `getBuilderFactionID()`.

6. **Add new API methods** to `ShipDef`:
   - `getBuilderFactionIDs(): string[]` — returns the full array of builder faction IDs.
   - `getBuilderFactions(): FactionDef[]` — returns `FactionDef` instances for all builder factions.
   - `hasMultipleBuilderFactions(): bool` — returns `count($this->builderFactionIDs) > 1`.

7. **Update `ShipDefs::getFactions()`** to unpack multi-faction entries:
   ```
   foreach $ship->getBuilderFactionIDs() as $factionID
       if not already in result: add FactionDefs::getByID($factionID)
   ```

8. **Update `ShipFinder::isMatch()`** to use intersection matching:
   ```
   if builderFactions filter is set:
       if intersection of ship->getBuilderFactionIDs() and filter is empty: return false
   ```

9. **Update `ShipsExtractor::resolveFaction()`**:
   - Change return type from `string` to `string[]` (array).
   - Split the raw `makerrace` attribute string by spaces.
   - Apply existing fallback logic (ship-settings exceptions, generic default) to produce an array.
   - Update the data structure passed to `ShipDef` constructor.

10. **Rebuild `ships.json`** to store the new array format.

### Phase 2: ModuleDef Multi-Faction Support (Preventive)

11. **Apply same pattern as ShipDef to `ModuleDef`**:
    - Add `KEY_BUILDER_FACTION_IDS` constant.
    - Change internal storage to `string[]`.
    - Update `fromArray()`/`toArray()` with format migration.
    - Preserve `getBuilderFactionID(): string` (returns first).
    - Add `getBuilderFactionIDs(): string[]`, `getBuilderFactions(): FactionDef[]`, `hasMultipleBuilderFactions(): bool`.

12. **Update `ModuleMacroExtractor::resolveFactionID()`**:
    - Change return type from `?string` to `string[]`.
    - Split raw `makerrace` by spaces.

13. **Update `ModuleFinder`** (if it has faction filtering) to use intersection matching.

14. **Rebuild `modules.json`** to store the new array format.

### Phase 3: ShieldDef Multi-Faction Support (Fixes Existing Data Issue)

15. **Apply pattern to `ShieldDef`**:
    - Add `KEY_MAKER_RACES = 'makerRaces'` constant.
    - Change `private string $makerRace` to `private array $makerRaces`.
    - Update constructor, `fromArray()`, `toArray()`.
    - Preserve `getMakerRace(): string` (returns first element).
    - Add `getMakerRaces(): string[]`, `hasMultipleMakerRaces(): bool`.

16. **Update `ShieldMacroExtractor::resolveMakerRace()`**:
    - Return `string[]` (split by space).

17. **Update `ShieldFinder::selectMakerRace()`** to use intersection matching.

18. **Rebuild `shields.json`** to store the new array format.

### Phase 4: EngineDef Multi-Faction Support (Preventive)

19. **Apply same pattern to `EngineDef`**:
    - Same changes as ShieldDef: `KEY_MAKER_RACES`, array storage, preserved `getMakerRace()`, new `getMakerRaces()` / `hasMultipleMakerRaces()`.

20. **Update `EngineMacroExtractor::resolveMakerRace()`**.

21. **Update `EngineFinder::selectMakerRace()`** to use intersection matching.

22. **Rebuild `engines.json`** to store the new array format.

### Phase 5: WeaponDef Multi-Faction Support (Preventive)

23. **Apply same pattern to `WeaponDef`**:
    - Same changes as ShieldDef/EngineDef.

24. **Update `WeaponMacroExtractor::resolveMakerRace()`**.

25. **Update `WeaponFinder::selectMakerRace()`** to use intersection matching.

26. **Rebuild `weapons.json`** to store the new array format.

### Phase 6: Tests

27. **Update `ShipCollectionTests::test_factionsHaveShips()`** to pass with the new multi-faction data.

28. **Add new test: `test_multiBuilderFaction()`** in `ShipCollectionTests`:
    - Load the Envoy ship by ID `ship_gen_m_corvette_01`.
    - Assert `hasMultipleBuilderFactions()` returns `true`.
    - Assert `getBuilderFactionIDs()` returns `['argon', 'teladi']`.
    - Assert `getBuilderFactionID()` returns `'argon'` (the primary).
    - Assert `getBuilderFaction()->getID()` returns `'argon'`.
    - Assert `getBuilderFactions()` returns two `FactionDef` instances.

29. **Add test: `test_finderMatchesMultiFaction()`**:
    - Use `ShipFinder::selectBuilderFaction('argon')` — assert Envoy is in results.
    - Use `ShipFinder::selectBuilderFaction('teladi')` — assert Envoy is in results.

30. **Add shield test: `test_multiMakerRace()`**:
    - Load shield `shield_gen_m_corvette_01_mk1`.
    - Assert `getMakerRaces()` returns `['argon', 'teladi']`.
    - Assert `getMakerRace()` returns `'argon'`.
    - Assert `hasMultipleMakerRaces()` returns `true`.

31. **Run full test suite** (`composer test`) and ensure all tests pass.

32. **Run PHPStan** (`composer analyze`) and fix any static analysis issues.

### Phase 7: Database Rebuild

33. **Run `composer build`** to regenerate all JSON data files with the new array format.

34. **Verify** that `ships.json` now contains `"builderFactionIDs": ["argon", "teladi"]` for the Envoy.

35. **Verify** that `shields.json` now contains `"makerRaces": ["argon", "teladi"]` for the Envoy shield.

### Phase 8: Manifest Updates

36. **Update `public-api.md`**:
    - Add new methods to `ShipDef`: `getBuilderFactionIDs()`, `getBuilderFactions()`, `hasMultipleBuilderFactions()`.
    - Add new methods to `ModuleDef`: `getBuilderFactionIDs()`, `getBuilderFactions()`, `hasMultipleBuilderFactions()`.
    - Add new methods to `ShieldDef`: `getMakerRaces()`, `hasMultipleMakerRaces()`.
    - Add new methods to `EngineDef`: `getMakerRaces()`, `hasMultipleMakerRaces()`.
    - Add new methods to `WeaponDef`: `getMakerRaces()`, `hasMultipleMakerRaces()`.
    - Update key constants sections for all affected classes.

37. **Update `tech-stack.md`**:
    - Note in Collection-Item Pattern section that builder faction is now multi-valued.

38. **Update `data-flows.md`**:
    - Update Database Build Flow to note faction array parsing.

39. **Update `constraints.md`**:
    - Add a note about multi-builder-faction handling convention (primary = first in list).

40. **Update `file-tree.md`** if any new files are added (unlikely for this change).

41. **Update `extraction-reference.md`** or relevant sub-documents noting the `makerrace` attribute can be space-separated.

## Dependencies

- **`FactionDefs` / `KnownFactions`**: No changes needed — these are already correct. The faction IDs `"argon"` and `"teladi"` each exist individually.
- **JSON data rebuild**: Steps 33-35 depend on all code changes being complete.
- **Tests**: Steps 27-32 depend on all code changes being complete.
- **Manifest updates**: Steps 36-41 depend on all code and test changes being finalized.

### Sequencing

```
Phase 1 (ShipDef — critical fix)
    → Phase 2 (ModuleDef)
    → Phase 3 (ShieldDef)
    → Phase 4 (EngineDef)
    → Phase 5 (WeaponDef)
        → Phase 6 (Tests)
            → Phase 7 (Database Rebuild)
                → Phase 8 (Manifest Updates)
```

Phases 2-5 are independent of each other and can be implemented in parallel, but all must complete before Phase 6.

## Required Components

### Files to Modify

- [src/X4/Database/Ships/ShipDef.php](../../src/X4/Database/Ships/ShipDef.php) — Core multi-faction support
- [src/X4/Database/Ships/ShipDefs.php](../../src/X4/Database/Ships/ShipDefs.php) — `getFactions()` unpacking
- [src/X4/Database/Ships/ShipFinder.php](../../src/X4/Database/Ships/ShipFinder.php) — Intersection matching
- [src/X4/Database/Ships/ShipsExtractor.php](../../src/X4/Database/Ships/ShipsExtractor.php) — Extract as array
- [src/X4/Database/Modules/ModuleDef.php](../../src/X4/Database/Modules/ModuleDef.php) — Multi-faction support
- [src/X4/Database/Modules/ModuleMacroExtractor.php](../../src/X4/Database/Modules/ModuleMacroExtractor.php) — Extract as array
- [src/X4/Database/Shields/ShieldDef.php](../../src/X4/Database/Shields/ShieldDef.php) — Multi-race support
- [src/X4/Database/Shields/ShieldMacroExtractor.php](../../src/X4/Database/Shields/ShieldMacroExtractor.php) — Extract as array
- [src/X4/Database/Shields/ShieldFinder.php](../../src/X4/Database/Shields/ShieldFinder.php) — Intersection matching
- [src/X4/Database/Engines/EngineDef.php](../../src/X4/Database/Engines/EngineDef.php) — Multi-race support
- [src/X4/Database/Engines/EngineMacroExtractor.php](../../src/X4/Database/Engines/EngineMacroExtractor.php) — Extract as array
- [src/X4/Database/Engines/EngineFinder.php](../../src/X4/Database/Engines/EngineFinder.php) — Intersection matching
- [src/X4/Database/Weapons/WeaponDef.php](../../src/X4/Database/Weapons/WeaponDef.php) — Multi-race support
- [src/X4/Database/Weapons/WeaponMacroExtractor.php](../../src/X4/Database/Weapons/WeaponMacroExtractor.php) — Extract as array
- [src/X4/Database/Weapons/WeaponFinder.php](../../src/X4/Database/Weapons/WeaponFinder.php) — Intersection matching
- [tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php](../../tests/X4Tests/Suites/Database/Ships/ShipCollectionTests.php) — Fix + new tests
- [data/ships.json](../../data/ships.json) — Regenerated
- [data/shields.json](../../data/shields.json) — Regenerated
- [data/engines.json](../../data/engines.json) — Regenerated
- [data/weapons.json](../../data/weapons.json) — Regenerated
- [data/modules.json](../../data/modules.json) — Regenerated

### Manifest Files to Update

- [docs/agents/project-manifest/public-api.md](public-api.md)
- [docs/agents/project-manifest/tech-stack.md](tech-stack.md)
- [docs/agents/project-manifest/data-flows.md](data-flows.md)
- [docs/agents/project-manifest/constraints.md](constraints.md)
- [docs/agents/project-manifest/extraction-reference.md](extraction-reference.md) (or sub-documents)

### No New Files Required

All changes are modifications to existing files. No new classes or files need to be created.

## Assumptions

1. The X4 game's `makerrace` XML attribute uses **space separation** (not comma or other delimiter) for multiple factions. This is confirmed by the Envoy ship data (`"argon teladi"`).
2. The **first** faction in the space-separated list is a reasonable choice as the "primary" faction for backward compatibility purposes.
3. Future game updates may introduce multi-faction entries for engines, weapons, and modules — hence applying the pattern preventively across all types.
4. The `WareDef.factions` field (owner factions, stored as an array in `wares.xml`) is a **separate concept** from builder/maker factions and is not affected by this change.
5. The `ModuleFinder` class may or may not have faction filtering — this needs to be verified during implementation. If it does, update it; if not, no action needed.
6. Downstream projects (`x4-mod-cargo-sizes`) have **zero** faction API usage and require no changes.

## Constraints

- **Backward compatibility is mandatory**: `getBuilderFactionID()` and `getMakerRace()` must continue to return `string`. No existing method signatures may change their return type.
- **Synchronous file I/O only** (per `constraints.md`).
- **All files must use `declare(strict_types=1)`**.
- **Follow naming conventions** from `constraints.md`: new methods use `get*()` for getters, `has*()` for boolean checks, `select*()` for finder filters.
- **No database queries** — all filtering is in-memory.
- **Data files must be pretty-printed JSON** (`JSON_PRETTY_PRINT`).
- **Manifest must be updated** with every code change.

## Out of Scope

- Changing the `WareDef` faction handling (owner factions are already stored as arrays — they are a different concept).
- Adding faction-based grouping or display logic in UI components.
- Modifying `FactionDefs` or `KnownFactions` — all individual faction IDs already exist correctly.
- Modifying the `x4-data-extractor` project — the raw XML data is correct; the issue is in how x4-core parses it.
- Modifying the `x4-mod-cargo-sizes` project — it has zero faction API usage.
- Creating a migration script for old JSON format — `fromArray()` handles both formats automatically.

## Acceptance Criteria

1. **Unit test `test_factionsHaveShips` passes** without error.
2. **`ShipDef::getBuilderFactionID()`** returns `'argon'` for the Envoy ship (first/primary faction).
3. **`ShipDef::getBuilderFactionIDs()`** returns `['argon', 'teladi']` for the Envoy ship.
4. **`ShipDef::hasMultipleBuilderFactions()`** returns `true` for the Envoy, `false` for single-faction ships.
5. **`ShipFinder::selectBuilderFaction('argon')`** includes the Envoy in results.
6. **`ShipFinder::selectBuilderFaction('teladi')`** includes the Envoy in results.
7. **`ShipDefs::getFactions()`** returns both Argon and Teladi without error (no duplicate crash).
8. **`ShieldDef::getMakerRace()`** returns `'argon'` for the Envoy shield (primary).
9. **`ShieldDef::getMakerRaces()`** returns `['argon', 'teladi']` for the Envoy shield.
10. **All existing tests pass** (`composer test`).
11. **PHPStan passes** (`composer analyze`).
12. **`composer build`** completes successfully with new JSON format.
13. **All manifest documents are updated** to reflect the new API surface.

## Testing Strategy

### Unit Tests (PHPUnit)

**New test methods** in `ShipCollectionTests`:
- `test_multiBuilderFaction()` — Validates Envoy's multi-faction data, primary faction return, and full list.
- `test_finderMatchesMultiFaction()` — Validates finder intersection matching for multi-faction ships.

**New test methods** in shield tests (file to be identified or created):
- `test_multiMakerRace()` — Validates Envoy shield's multi-race data.

**Modified tests:**
- `test_factionsHaveShips()` — Should continue to pass after the fix (no assertion changes needed, just the underlying data fix).

### Integration Testing

- Run `composer build` end-to-end to verify data extraction produces correct array-format JSON.
- Verify all JSON files pass validation (well-formed, correct structure).

### Static Analysis

- Run `composer analyze` (PHPStan) to catch type mismatches from the `string` → `string[]` internal storage change.

## Risks & Mitigations

| Risk | Mitigation |
|------|------------|
| **Future game data uses a different delimiter** (not space) | The parsing logic should use `preg_split('/\s+/', ...)` to handle any whitespace. Add a comment documenting the assumption. |
| **External consumers rely on `getBuilderFactionID()` returning the compound string** | Extremely unlikely (the compound string is invalid as a faction ID), but document the behavioral change in the changelog. |
| **JSON format migration breaks cached data** | `fromArray()` handles both old (`string`) and new (`string[]`) formats seamlessly. |
| **Performance impact from array operations in finders** | Negligible — `array_intersect()` on tiny arrays (1-2 elements) has no measurable cost. |
| **Other extractors (blueprint, etc.) also read `makerrace`** | Blueprint extraction operates on ware-level data, not macro-level `makerrace`. Verified no `makerrace` usage in blueprint extractor. |
| **`ModuleFinder` may need updates** | Verify during implementation; `ModuleFinder` may already filter by `builderFactionID`. Apply intersection matching if so. |
