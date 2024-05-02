# X4 Tools Core

X4 database and utility classes written in PHP.

This package is used by my X4 tools:

- [Savegame parser](https://github.com/Mistralys/x4-savegame-parser)
- [Blueprints optimizer](https://github.com/Mistralys/x4-blueprint-optimizer)

## Configuration

1. Unpack the game's data files ([howto](https://github.com/Mistralys/x4-game-notes/blob/main/unpacking-game-files.md)).
2. Clone the repository.
3. Copy `dev-config.php.dist` to `dev-config.php`.
4. Edit the file to set the correct paths. 

## Database update

To update the bundled database, the extraction scripts must be run:

1. Translations: `bin/extract-translations.php`
2. Modules: `bin/extract-modules.php`

This will update the JSON files in the `data` folder.

> NOTE: The language JSON files are not included in the repository, as they are too large.
> They are only used when extracting data.
