## v1.0.0 - Data update and revamp
- Blueprints: Revamped the data extraction and collection handling.
- Modules: Revamped the data extraction and collection handling.
- Factions: Revamped the data extraction and collection handling.
- Ships: Added the ship collection.
- Wares: Added the wares collection.
- DataSources: Added the data sources collection to access DLC information.
- Macro File Index: Added the macro file index.
- Dependencies: Updated AppUtils Core to [v2.3.12](https://github.com/Mistralys/application-utils-core/releases/tag/2.3.12).

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
