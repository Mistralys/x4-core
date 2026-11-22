# X4 Core

Access X4: Foundations game data from PHP, without parsing game files yourself.

**LAST VERIFIED: November 2026** | X4 Foundations 9.0, Empire Update.

## What is X4 Core?

X4 Core is a PHP library that gives you factions, wares, ships, station modules and blueprints from X4: Foundations as plain objects. The extracted game data ships with the package, so you can query it right after installing. It is built to be a dependency for your own X4 tools, mods and websites.

## Features

- Look up factions, wares, ships, station modules and blueprints through a consistent object API.
- Narrow down results with chainable finders, for example by ware group or by the DLC that provides an item.
- Translate game text codes such as `{20101,20604}` using the bundled official translations.
- Read the extracted data directly as JSON files if you prefer not to use the PHP classes.
- Regenerate the bundled data from your own game installation with a Composer command.
- Build web pages around the data with the included Bootstrap-based UI components.

## Requirements

- PHP 8.4 or higher
- PHP extensions: `simplexml`, `json`, `mbstring`, `dom`, `curl`
- [Composer](https://getcomposer.org/)

## Quick Start

Install the package in your Composer project:

```bash
composer require mistralys/x4-core
```

Then create a script that looks up a faction and finds the ship engines added by the Boron DLC:

```php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;

echo KnownFactions::getInstance()->getArgonFederation()->getLabel() . PHP_EOL;

$engines = WareDefs::getInstance()
    ->findWares()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_KINGDOM_END)
    ->selectGroup(WareGroups::GROUP_ENGINES)
    ->getAll();

foreach ($engines as $engine) {
    echo $engine->getLabel() . PHP_EOL;
}
```

The first line printed is the faction name:

```text
Argon Federation
```

The lines after it are the labels of the matching Boron engines.

## Learn More

| Resource | Description |
|----------|-------------|
| [Usage guide](docs/handbook/usage.md) | Factions, wares, the ware finder and translations |
| [Ship handbook](docs/handbook/ships.md) | Ship data API reference for library users |
| [Development setup](docs/handbook/development-setup.md) | Configure the project and rebuild the bundled data from your game files |
| [Public API reference](docs/agents/project-manifest/api/README.md) | Public class and method signatures, grouped by domain |
| [Project manifest](docs/agents/project-manifest/README.md) | Architecture, patterns and conventions |
| [Extraction reference](docs/agents/project-manifest/extraction-reference.md) | How game data is extracted, and how to rebuild the database |
| [Changelog](changelog.md) | Release history, including breaking data changes |
| 

### Related X4 projects

- [X4 Data Extractor](https://github.com/Mistralys/x4-data-extractor) – Unpacks the game data files.
- [X4 Savegame Parser](https://github.com/Mistralys/x4-savegame-parser) – Extracts information from savegames.
- [X4 Cargo Size Mod](https://github.com/Mistralys/x4-mod-cargo-sizes) – A mod that increases ship cargo sizes.
- [X4 Game Notes](https://github.com/Mistralys/x4-game-notes) – How-tos, tips and general information about X4.

## License

[MIT License](LICENSE)

## Like it? A tea is appreciated!

<a href="https://buymeacoffee.com/aeonoftime"><img src="docs/images/buy-me-a-tea.png" alt="Buy me a tea" width="200"></a>



