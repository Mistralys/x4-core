# X4 Database Core

Database and utility classes used to access X4: Foundations game data in 
an OOP way. It is designed to be used as a dependency in other projects
(see [X4 Tools](#x4-tools-and-libraries), for example).

## Database features

- Faction database
- Ware database
- Ship database
- Station module database
- Blueprint database
- Translation tool
- DLC metadata
- Macro file index
- Automated database file generation from the game data

## Requirements

- PHP 8.2 or higher.
- [Composer](https://getcomposer.org/).

## Installation

Require the package in your Composer project:

```bash
composer require mistralys/x4-core
```

## Usage

### Raw JSON data files

All extracted game data is stored in JSON files in the `data` folder.

- [factions.json](./data/factions.json) - Faction definitions
- [wares.json](./data/wares.json) - Ware definitions
- [ships.json](./data/ships.json) - Ship definitions
- [modules.json](./data/modules.json) - Station module definitions
- [blueprints.json](./data/blueprints.json) - Blueprints
- [macro-index.json](./data/macro-index.json) - Macro file index
- [data-sources.json](./data/data-sources.json) - Data sources (DLC metadata)

### The faction classes

All factions available in the game can be accessed through the faction collection.

#### The faction collection

All factions are available through the `FactionDefs` collection class.

```php
use Mistralys\X4\Database\Factions\FactionDefs;

echo "Available factions:" . PHP_EOL;

foreach(FactionDefs::getInstance()->getAll() as $faction) {
    echo $faction->getLabel() . PHP_EOL;
}
```

#### Faction getter methods

When working with the faction classes, dedicated getter methods exist to access
factions instead of using the faction ID constants.

```php
use Mistralys\X4\Database\Factions\KnownFactions;

$argon = KnownFactions::getInstance()->getArgon();

echo $argon->getLabel(); // Outputs "Argon"
```

### The ware classes

All items available in the game, from trade goods to ships, are available in the
main ware collection.

#### The ware collection

All wares are available through the `Wares` collection class.

```php
use Mistralys\X4\Database\Wares\WareDefs;

echo "Available wares:" . PHP_EOL;

foreach(WareDefs::getInstance()->getAll() as $ware) {
    echo $ware->getLabel() . PHP_EOL;
}
```

This fetches wares by ID:

```php
use Mistralys\X4\Database\Wares\WareDefs;

$advancedElectronics = WareDefs::getInstance()
    ->getByID('module_gen_prod_advancedelectronics_01');
```

#### The ware finder

The ware finder utility class allows selecting search criteria to filter the
wares and retrieve specific wares. The following example uses the ware finder to 
retrieve all ship engines provided by the Boron DLC.

```php
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;

$boronEngines = WareDefs::getInstance()
    ->findWares()
    ->selectDLC()
    ->boron()
    ->selectGroup(WareGroups::GROUP_ENGINES)
    ->getAll();
```

### The ship classes

All ships available in the game can be accessed through the ship collection.

```php
use Mistralys\X4\Database\Ships\ShipDefs;

echo "Available ships:" . PHP_EOL;

foreach(ShipDefs::getInstance()->getAll() as $ship) {
    echo $ship->getLabel() . PHP_EOL;
}
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
