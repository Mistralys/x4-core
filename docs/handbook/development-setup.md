# Development Setup

> **Audience:** Contributors and developers who want to rebuild the bundled database

X4 Core ships with the extracted game data in the `data/` folder, so using the library does not require any of the steps below. You only need this setup if you want to regenerate the data from your own game installation, for example after a game update, or to run the test suite.

## Prerequisites

The database rebuild reads the game's data files, which are packed in the game's `.cat`/`.dat` archives. They must be unpacked first with the [X4 Data Extractor](https://github.com/Mistralys/x4-data-extractor) tool. It also supplies the DLC metadata that the build needs.

## Configuration

1. Unpack the game data files with the X4 Data Extractor.
2. Clone the X4 Core repository and run `composer install`.
3. Copy `dev-config.dist.php` to `dev-config.php`.
4. Edit `dev-config.php` and set the paths for your system.

`dev-config.php` is git-ignored and loaded automatically by Composer's autoloader, so your local paths are not committed.

The following constants are defined in the file (namespace `Mistralys\X4`):

| Constant | Purpose |
|----------|---------|
| `X4_GAME_FOLDER` | Absolute path to the X4 game folder. |
| `X4_EXTRACTED_CAT_FILES_FOLDER` | Absolute path to the folder holding the unpacked game files. Required to rebuild the database. |
| `X4_CORE_INSTALL_URL` | Used only to run the tests: the URL on your local webserver where the X4 Core clone is served. |

## Database Update

Regenerate all JSON files in `data/` with:

```bash
composer build
```

The build runs the individual extractors in dependency order: the macro index, translations, data sources, factions and wares first, then engines, shields, weapons, modules, blueprints and ships.

To refresh a single data set, run one of the `extract-*` scripts defined in `composer.json`, for example:

```bash
composer extract-wares
```

Run `composer run-script --list` to see all available scripts. For how the extractors work internally, see the [Extraction reference](../agents/project-manifest/extraction-reference.md).

After every build command, the line endings of the files in `data/` are normalized to LF, matching the repository (see `.gitattributes`). The JSON writer otherwise leaves a stray CRLF at the end of each file on Windows, which makes git report unchanged data files as modified. The generated `Known*.php` classes are normalized the same way.

## Tests and Static Analysis

```bash
composer test
composer analyze
```
