# Synthesis: Ship Macro Metadata

### Outcome Summary

x4-core's ship and ware data now covers what consumers previously read from raw game XML: ship macro links and properties, ware prices, production recipes, licences, cargo volume and transport types, plus a new production-methods data file. The wares extractor now applies DLC patches (fixing missing owners), and the work added finder filters, a translation fallback, a production cost helper and typed value classes, all additive for v1.5.0. All 17 work packages passed every stage; the changelog entry stays WIP, nothing was tagged or published, and several plan figures were corrected against the raw XML.

### Metrics

- Work packages: 17 of 17 COMPLETE, all stages PASS (pipeline health: 17 with all stages passing, 0 missing).
- Final full suite (WP-017 QA): 946 tests, 142966 assertions, 0 failures, 2 warnings, 8 skipped. Needs `php -d memory_limit=2G`.
- Test progression: 847 (WP-002/003), 880 (WP-005/012), 901 (WP-007/013), 916 (WP-008/014), 941 (WP-015), 946 (WP-017).
- Exit gate (WP-017, base commit exported via git archive): baseline 790 tests passing and 531 phpstan findings; now 946 passing and 558 phpstan findings. The 27 extra findings are typing noise in new code and tests, with no new test failures.
- Load cost versus the WP-001 baseline (limit 2x): WareDefs 205 ms vs 129 ms (1.6x), peak 12.6 MB vs 8.4 MB; ShipDefs 81 ms vs 56 ms (1.5x), 10.5 MB vs 8.4 MB.
- Code review: 0 blocking findings across all WPs. Security issues: 0.
- Data verified by independent raw-XML scans: wares.json (licence on 1083 wares, 1695 productions on 1305 wares, 18 illegal wares); ships.json (256 ships, all pre-existing keys unchanged except `usedBy` supersets); production-methods.json (12 methods).

#### Failed or deviating metrics (none blocking)

Plan figures that did not match the data. Each was reconciled against raw XML and the real numbers went into docs and changelog:
- Owner additions from DLC patches: 196 wares / 547 additions (plan 195 / 546). WP-007.
- Production resource lines: 4820 over 98 distinct resource wares (plan 4909 / 111). WP-007.
- Ships with `usedBy` gains: 13 (plan 14). WP-015.
- Ships with purpose `mine`: 50 (plan 49). WP-015.
- Non-capturable ships: 6 `ship_kha_*` plus 8 `ship_xen_*` (plan said all kha). WP-017.

Other transient items: one non-reproducible error in the first full run of WP-008 QA (DatabaseBuilder build tests) and one in WP-015 (TranslationDefsTests JSON read). Both passed on rerun.

### Strategic Recommendations

- **Lazy hydration for shared collections.** `WareDef` keeps raw price and production records and builds value objects on first access. This avoids about 1700 production objects at every cold start and held load cost under 2x. Use this pattern for any other widely loaded collection.
- **Deprecated-shim pattern.** An empty subclass in a `Legacy/` folder with `@deprecated`, plus an instanceof test, makes a published rename non-breaking (WP-002). It is reusable for the `BlueprintExtractor` `Mistral\` typo.
- **Independent raw-XML scanners in tests.** `RawWareScanner` and `RawShipMacroScanner` share no code with the extractors, so tests cannot pass by sharing a bug. They also exposed the plan's wrong counts. Prefer them over hard-coded counts.
- **Per-ship reader that returns the value object's `toArray()`.** `ShipMacroPropertiesExtractor` builds `ShipMacroProperties` and returns its array, so the JSON format cannot drift from the class. This follows the `ModuleMacroExtractor` pattern.
- **Honest derived rules.** `ShipDef::canTransport()` is documented as derived from matching vocabularies, and `playerCoverable` is documented as an uninterpreted game flag. Keep this approach where game semantics are unconfirmed.
- **Resolver extraction.** The connection-to-macro walk now exists once, in `ShipMacroConnectionResolver`, and is shared by storage, dock and hangar resolution.
- **Plan numbers need verification.** The plan carried five count errors. Future plans should state counts as "verify against raw scan", or ACs should compare to a scan rather than a literal.

### Code Insights

#### Developer
- `TagNameFinder`/`findChildren()->byTagName()` searches all descendants, not direct children. This is a trap that WP-007 worked around with a private `getChildren()` (WP-007).
- `ElementExtended::getAttribute()` is declared `?string` but returns `''` for missing attributes, which causes many phpstan errors in `ShipsExtractor` (WP-009).
- All software wares share `software_dummy_macro`, so `WareDefs::findByMacro()` is unreliable for software; resolve by ware ID (WP-011).
- `ShipSizes::normalizeID()` calls `str_starts_with('ship_', $id)` with swapped arguments, so normalisation never triggers. This is pre-existing (WP-012).
- `ShipsExtractor` still uses `byTagName()->getFirst()` for stats, while the new extractor uses exact `properties` paths. A later move to path-based lookup would prevent shadowing (WP-013).
- The `ships.json` `variants` key lands after the macro keys on 6 ships. Merging macro data after `validateVariants()` would avoid it (WP-015).
- `WareProduction` has no `getMethodID()` accessor (WP-008).
- `ProductionMethodDefs::getDefault()` returns the alphabetical-first method, not a semantic default (WP-004).
- `memory_get_peak_usage(true)` is 2 MB granular; `false` is better for load-cost comparison (WP-001).
- `ShipMacroProperties::getThrusterSize()` throws on a default instance with an empty thruster size ID (WP-012).
- Public readonly properties on `ShipMacroProperties` duplicate its accessors; only the accessors are the supported API (WP-012).

#### QA
- `TranslationDefs::parseCode()` casts with `(int)`, so `{abc,def}` parses as page 0, id 0 instead of malformed (WP-003).
- The multi-size hangar bay WARNING, loadout min/max-only paths and the missing-macro path are untested (WP-013).
- Console output cannot be captured, so tests need a parallel collector such as `getWarnings()` (WP-006, WP-009).
- The ships.json-versus-baseline AC could not be verified independently at WP-009 QA because the baseline lives outside the repo (WP-009).
- Two PHPUnit warnings in the translation suites were never investigated (WP-003).

#### Reviewer
- Applied fixes: removed redundant same-namespace imports (WP-002); added a `getWare()` docblock note on software resolution (WP-011).
- Filter property declarations in `ShipFinder` are interleaved with methods, unlike the other finders (WP-017).
- `MacroFileDef::getFullPath()` returns a virtual extension-less path that is easily confused with `getFile()->getPath()` (WP-014).
- `selectProducedBy` and `selectUsingResource` scan all wares per call; add an index only if a repeated-lookup consumer appears (WP-016).

#### Release Engineer
- The changelog-curator sub-agent was unavailable in several WPs (WP-007, 008, 013, 014, 015, 016, 017), so the changelog was edited directly in project style.
- The WIP v1.5.0 heading may need a Deprecation tag and subsection for the `WaresExtractor` shim (WP-002).

#### Documentation
- Doc gaps: `TranslationDefs` has no section of its own; `api/README.md` has no domain exception list; `ships-reference.md` has no macro lookup tables; `tech-stack.md` has no note on per-item macro readers; `handbook/ships.md` has no `getMacroProperties()` section.
- Stale content: `xml-sources.md` says "~500 wares" (actual about 1870) and "~60 ships" (actual 256); the `troubleshooting.md` quick-reference table lacks Errors 8 and 9 and has an outdated Error 4 sample and a Feb 2026 date; `file-tree.md` `MacroProperties/` listing is incomplete.

### Deferred & Follow-Up Items

| # | Type | Source | Agent | Description | Priority |
|---|---|---|---|---|---|
| 1 | deferred | WP-017 / release | Release Engineer | The user must finalize the WIP v1.5.0 heading, tag v1.5.0 and publish. Nothing was tagged. | medium |
| 2 | deferred | WP-015/016/017 | QA, Developer, Reviewer | Extractor tests write to the live `data/` folder, rewrite data files with CRLF (extract scripts write LF) and cause transient read errors. Redirect them to a temp data folder. | medium |
| 3 | deferred | WP-003, WP-007, WP-008 | Developer | Full suite exhausts the 128M default `memory_limit` (Translations tests, `Languages` singleton). Raise it in `phpunit.xml` or add an unload hook. | medium |
| 4 | deferred | WP-007 | Developer | `TagNameFinder` descendant-search trap. Add a direct-children finder or rename. | medium |
| 5 | deferred | WP-011 | Developer, QA | `software_dummy_macro` makes `findByMacro()` unreliable for software; consider a guard in `ShipLoadoutItem::getWare()` or a pinning test, and document the caveat in the `WareDefs` section of `database-game-data-api.md`. | medium |
| 6 | deferred | WP-013 | Developer | Move `ShipsExtractor` stat lookups to path-based lookups like the new extractor. | low |
| 7 | deferred | WP-013, WP-016 | QA, Reviewer | Add tests for the multi-size hangar bay WARNING (stub resolver), loadout edge paths, and unknown-licence empty result. | low |
| 8 | deferred | WP-003 | QA | Tighten `parseCode()` with a numeric check and add a test. | low |
| 9 | deferred | WP-004, WP-008 | QA, Developer | Add a `getDataFile()` path assertion, an empty or missing `production-methods.json` test, and `WareProduction::getMethodID()`. | low |
| 10 | deferred | WP-007 | Developer | `parseProductions` casts `time` and `amount` unvalidated; add a WARNING on non-numeric values. `extractComponentDetails()` swallows all Throwables silently. | low |
| 11 | deferred | WP-009 | Developer | `ElementExtended::getAttribute()` return type mismatch (`?string` versus `''`). | low |
| 12 | deferred | WP-015 | Developer | Merge macro data after `validateVariants()` so key order is stable. | low |
| 13 | deferred | WP-017 | Reviewer | Group `ShipFinder` filter property declarations at the top of the class. | low |
| 14 | deferred | WP-017, WP-016 | Developer | Typing noise: 27 new phpstan findings; add generic `@return` annotations to finders (`BaseFinder::getAll()` typed as the generic interface). | low |
| 15 | deferred | WP-006 | Developer | Add a Console buffer or spy for capturing log output in tests. | low |
| 16 | deferred | WP-005, WP-011 | Developer | Docs and `WareDefs` cleanups: misplaced `getMacroIndex()` docblock; shared `toFloat()` helper for nullable-float value objects. | low |
| 17 | deferred | WP-004, WP-005, WP-007, WP-008, WP-009, WP-012, WP-013, WP-014, WP-015 | Documentation | Stale or missing docs listed under Code Insights (Documentation): `TranslationDefs` section, `api/README.md` domain index and exception list, `ships-reference.md` lookup tables, `tech-stack.md` macro readers note, `handbook/ships.md` `getMacroProperties()` section, `xml-sources.md` counts, `troubleshooting.md` Errors 4, 8, 9 and date, `file-tree.md` `MacroProperties/` listing. | low |
| 18 | out-of-scope | WP-002 | Developer, Reviewer | The `Mistral\` typo in `BlueprintExtractor` (`DatabaseBuilder.php` L13) is outside this plan; the shim pattern applies. | low |
| 19 | out-of-scope | WP-015 | Planner | Library-level loadouts from `libraries/loadouts.xml` (story and scenario setups) need their own collection. | low |
| 20 | out-of-scope | WP-010, WP-012 | Planner | Steering curve interpolation (`getValueAt()`) and the meaning of `playerCoverable` stay unconfirmed and were deliberately not implemented. | low |
| 21 | deferred | WP-001 | Developer, QA | The baseline folder and timing scripts live in `%TEMP%\x4-core-baseline` (outside the repo) and may be cleaned up; keep them if later comparisons are needed. | low |

### Next Steps

1. **Knowledge Archiver pending (manual run).** The Knowledge Archiver sub-agent could not be dispatched in this session because no Task tool was available. A manual run needs `cwd_path` = `F:\Webserver\www\htdocs\Workspaces\x4-foundations\STABLE` and `project_storage_path` = `f:\Webserver\www\htdocs\Workspaces\x4-foundations\STABLE\x4-core\docs\agents\plans\2026-11-22-ship-macro-metadata`.
2. **Release v1.5.0.** Normalise data line endings (run any `composer extract-*` command after a test run), finalize the WIP heading (consider a Deprecation tag for the `WaresExtractor` shim), tag and publish.
3. **Test harness hygiene.** Redirect extractor tests to a temp data folder and raise the PHPUnit memory limit (items 2 and 3 above). These two items caused most of the friction in this cycle.
4. **Adopt the new data downstream.** The Cargo Sizes mod (`CargoSizeExtractor`, `AccelerationOverrideDef`) can read macro IDs, steering curve and explosion damage from `ships.json` instead of raw XML.
5. **Docs refresh pass.** Close the stale and missing documentation items (item 17).
6. **Seed the next plan** from the low-priority code debt (items 4, 6 to 16) and the out-of-scope items (18 to 20).
