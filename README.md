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

## X4 Tools and libraries

- [X4 Game Notes][] - _Docs_ - Howto, tips and general information about X4.
- [X4 Core][] - _Library_ - Access X4 game data in an OOP way.
- [X4 Data Extractor][] - _Tool & Library_ - Extract X4 game files.
- [X4 Savegame Parser][] - _Tool_ - Parse X4 savegames to extract information.
- [X4 Cargo Size Mod][] - _Mod_ - Mod to increase ship cargo sizes.

[X4 Data Extractor]: https://github.com/Mistralys/x4-data-extractor
[X4 Game Notes]: https://github.com/Mistralys/x4-game-notes
[X4 Core]: https://github.com/Mistralys/x4-core
[X4 Savegame Parser]: https://github.com/Mistralys/x4-savegame-parser
[X4 Cargo Size Mod]: https://github.com/Mistralys/x4-mod-cargo-sizes
