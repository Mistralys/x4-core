# X4 Tools Core

Utility classes used to access X4: Foundations game data in an OOP way.
Designed to be used as a library in other projects (see [X4 Tools](#x4-tools-and-libraries)).

## Requirements

- PHP 8.2 or higher.
- [Composer](https://getcomposer.org/).

## Usage

### Installation

Require the package in your Composer project:

```bash
composer require mistralys/x4-core
```

### Accessing Race information

All races available in the game can be accessed through dedicated classes.

```php
use Mistralys\X4\Database\Factions\KnownFactions;

$argon = KnownFactions::getInstance()->getArgon();

echo $argon->getLabel(); // Outputs "Argon"
```

### Accessing translations

The official translations are bundled with the package, and can be
accessed to translate text codes like `{20101,20604}` ("Manorina (Gas) Vanguard").

```php
use Mistralys\X4\Database\Translations\Languages;

$english = Languages::getInstance()->getEnglish();

$shipName = $english->t('{20101,20604}');
```

## Development setup

### Unpacking game data files

The mod requires the game's data files to be unpacked using the
[X4 Data Extractor][] tool. The tool acts as a library to access the
extracted information. This includes the DLC metadata necessary to
generate the correct mod file structure.

Please refer to the tool's instructions to unpack the game data files.

### Configuration

1. Unpack the data files (see above).
2. Clone this repository.
3. Copy `dev-config.php.dist` to `dev-config.php`.
4. Edit the file to set the correct paths. 

### Database update

To update the bundled database, use the `build` Composer command
to update the JSON files in the `data` folder.

```bash
composer build
```

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
