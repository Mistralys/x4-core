# Ship Data API - Quick Reference

> **Appendix to:** [Ship Data API Reference](ships.md)  
> **Last Updated:** February 9, 2026

## Table of Contents

- [Collection Metadata](#collection-metadata)
- [Ship Sizes](#ship-sizes)
- [Ship Classes](#ship-classes)
- [Equipment Slot Types](#equipment-slot-types)
- [Common Ship Constants](#common-ship-constants)
- [Common Patterns](#common-patterns)
- [Important Notes](#important-notes)

---

## Collection Metadata

```php
// Get factions that build ships
$factions = ShipDefs::getInstance()->getFactions(); // FactionDef[]

// Get data sources with ships
$dataSources = ShipDefs::getInstance()->getDataSources(); // DataSourceDef[]
```

---

## Ship Sizes

```php
use Mistralys\X4\Database\Ships\ShipSizes;

ShipSizes::SIZE_XS;  // 'xs' - Extra Small
ShipSizes::SIZE_S;   // 's'  - Small
ShipSizes::SIZE_M;   // 'm'  - Medium
ShipSizes::SIZE_L;   // 'l'  - Large
ShipSizes::SIZE_XL;  // 'xl' - Extra Large

// Access size objects
$sizes = ShipSizes::getInstance();
$medium = $sizes->getByID('m');
echo $medium->getLabel(); // "Medium"
```

---

## Ship Classes

**Combat Ships:**

| Constant | ID | Description |
|----------|----|-----------  |
| `ShipClasses::CLASS_FIGHTER` | `'fighter'` | Fighter |
| `ShipClasses::CLASS_HEAVY_FIGHTER` | `'heavy_fighter'` | Heavy Fighter |
| `ShipClasses::CLASS_SCOUT` | `'scout'` | Scout |
| `ShipClasses::CLASS_CORVETTE` | `'corvette'` | Corvette |
| `ShipClasses::CLASS_GUNBOAT` | `'gunboat'` | Gunboat |
| `ShipClasses::CLASS_FRIGATE` | `'frigate'` | Frigate |
| `ShipClasses::CLASS_DESTROYER` | `'destroyer'` | Destroyer |
| `ShipClasses::CLASS_BATTLESHIP` | `'battleship'` | Battleship |
| `ShipClasses::CLASS_CARRIER` | `'carrier'` | Carrier |

**Economic Ships:**

| Constant | ID | Description |
|----------|----|-----------  |
| `ShipClasses::CLASS_MINER` | `'miner'` | Miner |
| `ShipClasses::CLASS_LARGEMINER` | `'largeminer'` | Large Miner |
| `ShipClasses::CLASS_FREIGHTER` | `'freighter'` | Freighter |
| `ShipClasses::CLASS_TRANSPORTER` | `'transporter'` | Transporter |
| `ShipClasses::CLASS_COURIER` | `'courier'` | Courier |

**Support Ships:**

| Constant | ID | Description |
|----------|----|-----------  |
| `ShipClasses::CLASS_BUILDER` | `'builder'` | Builder |
| `ShipClasses::CLASS_RESUPPLIER` | `'resupplier'` | Resupplier |
| `ShipClasses::CLASS_TUG` | `'tug'` | Tug |
| `ShipClasses::CLASS_SCAVENGER` | `'scavenger'` | Scavenger |
| `ShipClasses::CLASS_SCRAPPER` | `'scrapper'` | Scrapper |

**Special Ships:**

| Constant | ID | Description |
|----------|----|-----------  |
| `ShipClasses::CLASS_EXPEDITIONARY` | `'expeditionary'` | Expeditionary |
| `ShipClasses::CLASS_COMPACTOR` | `'compactor'` | Compactor |
| `ShipClasses::CLASS_ENVOY` | `'envoy'` | Envoy |

```php
use Mistralys\X4\Database\Ships\ShipClasses;

// Access class objects
$classes = ShipClasses::getInstance();
$battleship = $classes->getByID('battleship');
echo $battleship->getLabel(); // "Battleship"
```

---

## Equipment Slot Types

```php
use Mistralys\X4\Database\SlotTypes\KnownSlotTypes;

KnownSlotTypes::ENGINE;
KnownSlotTypes::SHIELD;
KnownSlotTypes::WEAPON;
KnownSlotTypes::TURRET;
KnownSlotTypes::COUNTERMEASURES;
KnownSlotTypes::DOCKING_BAY;
```

---

## Common Ship Constants

```php
use Mistralys\X4\Database\Ships\KnownShips;

// Sample of 450+ available constants
KnownShips::SHIP_ASGARD;
KnownShips::SHIP_CERBERUS_SENTINEL;
KnownShips::SHIP_COLOSSUS_VANGUARD;
KnownShips::SHIP_ECLIPSE_VANGUARD_01_A;
KnownShips::SHIP_BEHEMOTH_VANGUARD;
// ... see KnownShips class for complete list
```

---

## Common Patterns

### Singleton Collections

All collection classes use singletons:

```php
ShipDefs::getInstance();
ShipSizes::getInstance();
ShipClasses::getInstance();
```

### Fluent Finders

All finders support method chaining:

```php
$result = ShipDefs::getInstance()
    ->findShips()
    ->selectSize('m')
    ->selectClass('frigate')
    ->getAll();
```

### ID vs Object Parameters

Most filter methods accept both string IDs and objects:

```php
// Both work
->selectSize('m')
->selectSize($sizeObject)

->selectClass('frigate')
->selectClass($classObject)
```

---

## Important Notes

### 1. Data is Read-Only

All ship data is loaded from JSON files. No setters or database modifications.

### 2. Equipment Returns WareDef

Equipment finders return `WareDef[]`, not specialized classes like `EngineDef[]`.

```php
$engines = $ship->getEngines()->getAll(); // Returns WareDef[]
```

Use `getCompatibleWeapons()` / `getCompatibleTurrets()` for weapon performance data:

```php
$weapons = $ship->getCompatibleWeapons(); // Returns WeaponDef[]
```

### 3. Generic Faction Fallback

Ships without a builder faction default to `KnownFactions::FACTION_GENERIC`.

### 4. Compatibility vs Availability

`canEquip()` checks slot compatibility but doesn't track if slots are already filled. Slot availability is application logic.

### 5. Lazy Loading

Collections load data on first access, not at instantiation.

```php
// Data loads here, not at getInstance()
$ships = ShipDefs::getInstance()->getAll();
```

### 6. Countermeasures Storage

Countermeasures are stored in the equipment array, not the slots array.

```php
// Gets from equipment array, not slots
$count = $ship->countCountermeasures();
```
