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
