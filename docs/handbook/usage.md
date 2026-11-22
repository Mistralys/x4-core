# Usage Guide

> **Audience:** Library users

A short tour of the main collections. All of them are singletons accessed through `getInstance()`. For ships in depth, see the [Ship handbook](ships.md).

## Factions

```php
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Factions\KnownFactions;

// All factions
$factions = FactionDefs::getInstance()->getAll();

// A specific faction through the typed accessor
$argon = KnownFactions::getInstance()->getArgonFederation();
echo $argon->getLabel();
```

## Wares

```php
use Mistralys\X4\Database\Wares\WareDefs;

$ware = WareDefs::getInstance()->getByID('energycells');
echo $ware->getLabel();
```

### Finding Wares

`findWares()` returns a chainable finder.

```php
use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Wares\WareGroups;

$engines = WareDefs::getInstance()
    ->findWares()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_KINGDOM_END)
    ->selectGroup(WareGroups::GROUP_ENGINES)
    ->getAll();
```

## Ships

```php
use Mistralys\X4\Database\Ships\ShipDefs;

$ships = ShipDefs::getInstance()->getAll();
```

See the [Ship handbook](ships.md) for finding ships and reading their properties.

## Translations

Game texts are referenced by page and text ID, written as `{20101,20604}`. Fetch a language and translate either a code string or the two IDs:

```php
use Mistralys\X4\Database\Translations\Languages;

$translator = Languages::getInstance()->getEnglish()->getTranslator();

echo $translator->ts('{20101,20604}');
echo $translator->t(20101, 20604);
```

If a text ID is unknown, the code is returned unchanged in the `{page,id}` form.
