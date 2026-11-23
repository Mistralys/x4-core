# X4 Core Changelog

## v1.5.0 - **WIP; UNRELEASED**

**Missing translations now fall back to English, or an empty string, instead of a raw code.**
Wares now carry prices, production recipes, licences, cargo volume and transport type, and the
regenerated ware data now includes them. Production methods can now be browsed as a
collection, with their label, tags, player-buildable status and default races. Translation codes can
also be checked for existence. The ware extractor moved to a new namespace, and the old class name
keeps working.

- Translations: Missing translations now fall back to English, or an empty string, not `{page,id}`.
- Wares: Added price, production recipes, licence, description, illegal factions and buy restriction.
- Wares: Added production resource lookups with total cost, cost per unit and production method.
- Wares: Added cargo volume and transport type; ten transport types, four of them cargo types.
- Wares: Records without the new data still load; empty values are omitted on export.
- Wares: Price and production data are now built on first access, not at load.
- Translations: Added checks for whether a translation code exists. Existing lookups are unchanged.
- ProductionMethods: Added collection with label, tags, player-buildable flag and default races.
- Ships: Storage and dock lookups now share one connection resolver; the storage macro name is kept. Extracted values are unchanged.
- Wares: Added a reader for DLC ware diff files; unsupported operations are logged as warnings.
- Wares: Moved extractor to the Mistralys namespace; old class name kept as deprecated shim.
- Ships: Added immutable types for steering curve, explosion damage, hangar capacity and modifiers.
- Ships: Added the eight ship purposes (fight, trade, mine, build, auxiliary, racing, salvage, dismantling).
- Ships: Added types for default and optional software and for the built-in default loadout.
- Ships: Added a macro properties object with links, purpose, thruster size, capacities, software, modifiers, steering curve and default loadout.
- Ships: Added base, variation and short variation names, falling back to English, or an empty string.
- Ships: Added a per-ship macro reader that extracts the macro properties from the ship's own macro, falling back to its alias target.
- Ships: The steering curve is a plain point list; `ShipDef::getMacroProperties()` exposes the new types.
- Wares: Fixed DLC ware patches being ignored; patched owners and illegal factions are merged, and patched productions appended. 196 wares gained 547 owners, which also changes the ship `usedBy` data.
- Wares: The extractor now reads price, productions, licence, description, illegal factions, buy restriction, volume and transport type. Unknown transport types and non-numeric volumes are logged as warnings.
- ProductionMethods: Added extractor reading vanilla methods and DLC `/wares/production` patches; a method defined twice keeps the later data folder's definition and logs a notice.
- ProductionMethods: Added the `extract-production-methods` Composer script; the build now runs it between the factions and wares steps.
- Wares: The extractor now logs a notice for a production with an unknown method and keeps the production.
- Data: Added production-methods.json with 12 methods, sorted by ID.
- Ships: `ShipDef` gained `getMacroProperties()` and delegators for the macro links (`getMacroID()`, `getMacro()`, `getCargoMacroID()`, `hasCargoMacro()`, `getCargoMacro()`, `getAliasMacroID()`, `isAliasMacro()`, `hasOwnAccFactors()`).
- Ships: `ShipDef` gained ware delegators `getPrice()`, `getProductions()`, `getLicenceID()`, `getDescription()` and `getIllegalFactionIDs()`.
- Wares: Added cargo volume and transport type; ships gain `getCargoTypeIDs()`, `canTransport()` and `getCargoUnits()`. The transport rule is derived from the matching vocabularies.
- Ships: Added `ShipException`; `getMacro()` throws it when the macro ID is empty instead of guessing the ID.
- Ships: Added macro information to ships.json: macro and cargo macro IDs, alias macro, own acceleration factors flag, purpose, thruster size, capacities, hangar, software, modifiers, names, steering curve, explosion damage and default loadouts. Existing values are unchanged.
- Data: Regenerated wares.json and ships.json; all 256 ships keep their IDs and order, and `usedBy` gained owners on 13 ships from DLC ware patches. Other data files are unchanged.
- Finders: Added `WareFinder::selectProducedBy()`, `selectUsingResource()`, `selectLicence()`, `selectIllegalFor()` and `selectTransportType()`. Single-valued attributes combine as any, capability filters (resources) as all; different filters combine as AND.
- Ships: Added `ShipFinder::selectPurpose()`, `selectThrusterSize()`, `selectCapturable()`, `selectWithHangar()` and `selectWithSoftware()`. Purposes and thruster sizes combine as any, hangar sizes and software as all, capturable is a single value (last call wins); an unknown purpose throws `ShipException`.
- Wares: Added `WareException`; an unknown transport type throws it, while unknown production method, ware or faction IDs throw the collection's `RecordNotExistsException`.
- API: All additions are backward compatible; the `ShipDef` constructor only gains a trailing optional parameter.
- Data: Regenerated wares.json with 1695 productions on 1305 wares (4820 resource lines over 98 resource wares), licences on 1083 wares and illegal factions on 18 wares. Existing keys are unchanged except owners, which only gain entries.

## v1.4.1 - Cross-Folder Macro Fixes
> Game version v9.0.0.0

**The Split carrier and the Split, Pirate and Terran builders no longer report a cargo capacity of 0.**
Macros that are defined in a different data folder than the file referencing them are now found
correctly. Dock information is also extracted for ships that depend on such macros.

- Ships: Fixed Split carrier and Split, Pirate and Terran builders reporting a cargo capacity of 0.
- Ships: Dock information is now extracted for ships whose dock macros live in another data folder.
- Ships: Missing storage macros are now logged as a warning instead of silently giving 0 cargo.
- Core: Fixed macro lookups failing for macros defined in another data folder.
- Build: Data file line endings are now normalized to LF, so git no longer flags unchanged files.
- Data: Regenerated ship data.

## v1.4.0 - Game Update v9 (Breaking-M)
> Game version v9.0.0.0

- Data: Updated all game data files.

### Breaking Changes

No breaking changes in the X4 Core APIs, but in the data. The v9 update is a massive data update across the board, including removed and added items. Notably, a range of constants from the `KnownBlueprints` class no longer exist. Double-check if you are using any.

## v1.3.0 - Ship Physics & Multi-Builder Factions
- Core: Added support for multiple builder factions across Ships, Modules, Shields, Engines, and Weapons.
- Core: Fixed crash when loading entities with multiple builder factions (e.g., Envoy ship).
- Core: Reduced log noise by downgrading missing builder faction warnings to info level.
- API: Added `get*IDs()`, `get*s()`, and `hasMultiple*()` methods to Ship, Module, Shield, Engine, and Weapon definitions for full multi-faction access.
- API: Existing single-value methods (e.g., `getBuilderFactionID()`) remain fully backward compatible, returning the primary faction.
- Ships: Added 24 new physics fields (drag coefficients, inertia, jerk values, acceleration factors)
- Ships: Added cargo capacity extraction (cargoCapacity, cargoType)
- Ships: Regenerated ships.json with complete physics data for all 256 ships
- Documentation: Updated public-api.md and handbook with complete physics API reference

## v1.2.1 - Cache folder fix
- Core: Fixed cache folder not being created automatically due to FolderInfo behavior change.
- Core: Added `.gitkeep` file to ensure cache directory is always tracked in git.

## v1.2.0 - Diplomacy and Envoy DLC release update
- Wares: Added new diplomacy ware groups, reorganized some ware group assignments.
- Wares: Added the Condensate.
- Modules: Added the new Argon connection modules.
- Modules: Some module names were updated.
- Ships: Added the Envoy and the Cypher.
- DataSources: Added the new Envoy DLC.
- Blueprints: Added new blueprints for the ships and their modules.
- Factions: Fixed the faction defs not being updated correctly.

## v1.1.1 - Small fixes and improvements
- User Interface: Now filtering redirect URLs that get specified with HTML-encoded ampersands.
- Ajax: Added the utility method `sendJSONViaPost()` to send POST requests to external services.

## v1.1.0 - UI Messaging, PHP8 (Breaking-S)
- User Interface: Added the session messaging queue and display.
- User Interface: Added `redirectWithXXXMessage()` methods in pages.
- User Interface: Added `getMessages()`.
- User Interface: Fixed missing webroot URL in generated URLs.
- User Interface: Added AJAX method handling in pages.
- User Interface: Added fixed footer and footer content manipulation.
- User Interface: Fixed some missing Font Awesome icon styles.
- User Interface: Fixed missing JS for popups in Bootstrap.
- User Interface: Added possibility to set the page title.
- Blueprints: Fixed missing module definitions.
- Blueprints: Fixed missing class for ship blueprints.
- Tests: The example UI now uses the main `dev-config.php` instead of its own variant.,
- Requirements: Now requiring PHP v8.4.
- Core: Session handling is now automatically enabled.

## v1.0.0 - Data update and revamp (Breaking-XL)
- Blueprints: Revamped the data extraction and collection handling.
- Modules: Revamped the data extraction and collection handling.
- Factions: Revamped the data extraction and collection handling.
- Ships: Added the ship collection.
- Wares: Added the wares collection.
- DataSources: Added the data sources collection to access DLC information.
- Macro File Index: Added the macro file index.
- Dependencies: Updated AppUtils Core to [v2.3.12](https://github.com/Mistralys/application-utils-core/releases/tag/2.3.12).

### Breaking changes

Virtually all classes have been renamed, namespaced or modified in 
some way. Going forward, the APIs will be more consistent and easier 
to use.

## v0.0.11 - Data folder update
- Modules: Now using the `DataFolders` class to access the extracted game files.
- Modules: Sorting by key in the JSON for version consistency.
- Translations: Translation files are now bundled with the package.
- Translations: Added the `Languages` class to access the available languages.
- Tests: Fixed translation tests.

## v0.0.10 - Translation update
- Translations: Updated to use the extracted data folders.
- Data: If available, extracted game files can be accessed with the `DataFolders` class.
- Dependencies: Added `x4-data-extractor` as a dependency.

## v0.0.9 - Translation fix
- Translations: Fixed escaped comments not being parsed correctly.

## v0.0.8 - Game class
- Game: Added the `X4Game` class to access game data, like the version.
- Config: Added the `X4_GAME_FOLDER` constant.
- Config: Added the `X4_CORE_INSTALL_URL` constant to simplify the test runner.

## v0.0.7 - Game v7.6 update
- Modules: Updated for game version 7.6.
- Modules: Improved macro cross-referencing, removed manual conversion table.
- Modules: More consistent extraction handling.
- Ships: Fixed the Astrid not having a class.
- Races: Added missing entry for the Khaa'k.
- Dependencies: Loosened version constraints to be usable with PHP8+.
- Translations: Handling replacing references recursively.
- Translations: Ignoring invalid references.
- DataGrids: Added collapsible rows.
- UI: Added `addJSHead()` and `addJSOnload()`.
- UI: Added `addInternalJS()` and `addExternalJS()`.
- Icons: Added `save()`.

## v0.0.6 - Translations
- Modules: `getLabel()` now returns the ID if no label exists.
- Modules: Now using translated labels in the data file.
- Translations: Added extraction of game translation labels.
- Translations: Added translator for specific language codes.

## v0.0.5 - Modules update
- Modules: Added a full list of station modules.
- Modules: Added the `ModuleExtractor` to find modules in the XML data files.

## v0.0.4 - Blueprint update
- Blueprints: Added the Erlking and the Astrid.

## v0.0.3 - Blueprint update
- Blueprints: Added the "Welfare" category.
- Blueprints: Added the Boron Art Academy welfare module.

## v0.0.2 - Blueprint tweaks
- UI: Fixed the subpage's `getURL()` not using `getURLParams()`.
- UI: Added new icon variants.
- Blueprints: Added `BlupeprintSelection::getCategoryIDs()`.
- Blueprints: Added some missing macros.
- Blueprints: Added `BlueprintDefs::registerUnknownBlueprint()`.
- Blueprints: Unknown or custom blueprints no longer throw an exception.

## v0.0.1 - Alpha release
- Blueprints database
- Races database
- User interface rendering
- Composer dependencies
