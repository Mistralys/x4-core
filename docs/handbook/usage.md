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

Further filters are `selectProducedBy()`, `selectUsingResource()`, `selectLicence()`, `selectIllegalFor()` and `selectTransportType()`. Filters on single-valued attributes combine as any, capability filters (tags, resources) as all, and different filters combine as AND. Unknown production method, ware or faction IDs throw the collection's `RecordNotExistsException`; an unknown transport type throws a `WareException`.

**Licences and production costs:** A licence ID is an opaque key. It is defined per faction in the game's faction data, and there is no licence collection to resolve it against. `WareProduction::getResourceCost()` and `getResourceCostPerUnit()` sum the input resources at their reference average price only. They ignore production effects and production time, and return `null` when a resource is unknown or has no price.

```php
// Wares produced with the terran method that are illegal for the Pioneers
$wares = WareDefs::getInstance()
    ->findWares()
    ->selectProducedBy('terran')
    ->selectIllegalFor('pioneers')
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
