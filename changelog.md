## v1.4.0 - Multi-Builder Faction Support
- Core: Added support for multiple builder factions across Ships, Modules, Shields, Engines, and Weapons.
- Core: Fixed crash when loading entities with multiple builder factions (e.g., Envoy ship).
- Core: Reduced log noise by downgrading missing builder faction warnings to info level.
- API: Added `get*IDs()`, `get*s()`, and `hasMultiple*()` methods to Ship, Module, Shield, Engine, and Weapon definitions for full multi-faction access.
- API: Existing single-value methods (e.g., `getBuilderFactionID()`) remain fully backward compatible, returning the primary faction.

## v1.3.0 - Complete Ship Physics Data
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
