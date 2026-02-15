# Database Core API Reference

> **Domain**: Core Database Patterns - CollectionItem, ItemCollection, Finder, VariantID  
> **Last Updated**: February 15, 2026

[← Back to API Index](README.md)

---

## Overview

The Database Core namespace defines the fundamental patterns used throughout the X4 Core library for data collection management. These interfaces and utilities provide:

- **CollectionItem Interface**: Contract for items stored in collections
- **ItemCollection Interface**: Contract for collections holding items
- **Finder Pattern**: Fluent filtering interface for querying collections
- **VariantID**: Ship/ware variant identification system (mk1, mk2, mk3)
- **BaseExtractor**: Common XML processing utilities for data extraction

---

## Table of Contents

- [Core Interfaces](#core-interfaces)
- [VariantID System](#variantid-system)
- [BaseExtractor Utilities](#baseextractor-utilities)

---

## Core Interfaces

### Mistralys\X4\Database\Core\CollectionItemInterface

Interface for collection items.

```php
getWareID(): string
getLabel(): string
getWare(): WareDef
getVariantID(): VariantID
getID(): string
```

**Implemented by:**
- `ShipDef`
- `WareDef`
- `ModuleDef`
- `WeaponDef`
- `ShieldDef`
- `EngineDef`
- `BlueprintDef`

**Usage:**
```php
function displayItem(CollectionItemInterface $item): void {
    echo $item->getLabel();
    echo $item->getVariantID()->getID(); // e.g., "mk1", "mk2_vanguard"
}
```

---

### Mistralys\X4\Database\Core\ItemCollectionInterface

Interface for item collections.

```php
getAll(): array // Returns CollectionItemInterface[]
```

**Implemented by:**
- `ShipDefs`
- `WareDefs`
- `ModuleDefs`
- `WeaponDefs`
- `ShieldDefs`
- `EngineDefs`
- `BlueprintDefs`

**Usage:**
```php
function countItems(ItemCollectionInterface $collection): int {
    return count($collection->getAll());
}
```

---

### Finder Pattern

All Finder classes implement a fluent interface for filtering collections:

**General Pattern:**
```php
// Base functionality available in all finders
class SomeFinder {
    getCollection(): ItemCollectionInterface
    selectLabelSearch(string $searchTerm): self
    getAll(): array // Returns specific item type[]
}
```

**Common Finder Methods:**
- `selectSize(string $size)` - Filter by size (s, m, l, xl)
- `selectDataSource(string|DataSourceDef $source)` - Filter by data source
- `selectLabelSearch(string $term)` - Filter by label text
- `getAll()` - Execute query and return results

**Available Finders:**
- `WareFinder` - Filter wares
- `ShipFinder` - Filter ships
- `ModuleFinder` - Filter modules
- `WeaponFinder` - Filter weapons
- `ShieldFinder` - Filter shields
- `EngineFinder` - Filter engines
- `ShipEquipmentFinder` - Filter equipment compatible with a ship

---

## VariantID System

### Mistralys\X4\Database\Core\VariantID

Handles ship/ware variant identification.

#### Constants
```php
MARKS: array = ['mk1', 'mk2', 'mk3']
```

#### Methods
```php
__construct(int $number = 0, ?string $qualifier = null, ?string $mark = null): void
static fromID(string $variantID): self
getNumber(): int
getNumberString(): string
getQualifier(): ?string
getMark(): ?string
getID(): string
static resolveWareVariantID(mixed $wareID): VariantID
appendConstantSuffix(string $constant, ?string $exceptionSuffix = null): string
```

**Variant ID Format:**
```
[number][_qualifier][_mark]

Examples:
- "01"           -> number: 1, qualifier: null, mark: null
- "02_a"         -> number: 2, qualifier: "a", mark: null
- "03_vanguard"  -> number: 3, qualifier: "vanguard", mark: null
- "01_mk2"       -> number: 1, qualifier: null, mark: "mk2"
- "02_a_mk3"     -> number: 2, qualifier: "a", mark: "mk3"
```

**Usage:**
```php
// Parse variant ID
$variantID = VariantID::fromID('02_vanguard_mk2');
echo $variantID->getNumber();    // 2
echo $variantID->getQualifier(); // "vanguard"
echo $variantID->getMark();      // "mk2"
echo $variantID->getID();        // "02_vanguard_mk2"

// Create variant ID
$variant = new VariantID(1, 'elite', 'mk3');
echo $variant->getID(); // "01_elite_mk3"

// Resolve from ware ID
$variant = VariantID::resolveWareVariantID('ship_arg_s_scout_01_a');
// Extracts and parses variant from full ware ID
```

**Variant ID Usage in Constants:**
```php
// Append variant to constant name
$variant = new VariantID(2, 'vanguard');
$constantName = $variant->appendConstantSuffix('SHIP_ARG_SCOUT');
// Returns: "SHIP_ARG_SCOUT_02_VANGUARD"
```

---

## BaseExtractor Utilities

### Mistralys\X4\Database\BaseExtractor

Abstract base class for data extractors. Provides common XML processing utilities.

#### Methods

##### `resolveNestedPropertyAttribute()`
```php
protected function resolveNestedPropertyAttribute(
    AppUtils\XMLHelper\ElementExtended $element,
    string $childTagName,
    string $attributeName
): float
```

Resolves an attribute value from a nested property element within a parent element. Searches for a child `<property name="{$childTagName}">` element and extracts the specified attribute value. Returns `0.0` if the property or attribute is not found.

**Example XML Structure:**
```xml
<ship>
    <properties>
        <property name="angular" min="1.5" max="3.0"/>
        <property name="forward" min="2.0" max="4.0"/>
    </properties>
</ship>
```

**Usage:**
```php
// Extract the 'min' attribute from the 'angular' property
$minValue = $this->resolveNestedPropertyAttribute($shipElement, 'angular', 'min');
// Returns 1.5 if found, 0.0 if not found
```

**Parameters:**
- `$element` - Parent DOM element containing child property elements
- `$childTagName` - Name attribute value of the child `<property>` element to find
- `$attributeName` - Attribute name to extract from the found property element

**Returns:** The attribute value as float, or `0.0` if property or attribute not found

---

## Usage Patterns

### Implementing a Collection

```php
use Mistralys\X4\Database\Core\ItemCollectionInterface;

class MyItemDefs implements ItemCollectionInterface
{
    private array $items = [];
    
    public function getAll(): array {
        return $this->items;
    }
    
    public function getByID(string $id): MyItemDef {
        return $this->items[$id] ?? throw new MyException('Item not found');
    }
}
```

### Implementing a CollectionItem

```php
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\VariantID;

class MyItemDef implements CollectionItemInterface
{
    public function __construct(
        private string $wareID,
        private string $label,
        private VariantID $variantID
    ) {}
    
    public function getWareID(): string { return $this->wareID; }
    public function getLabel(): string { return $this->label; }
    public function getVariantID(): VariantID { return $this->variantID; }
    public function getID(): string { return $this->wareID; }
    
    public function getWare(): WareDef {
        return WareDefs::getInstance()->getByID($this->wareID);
    }
}
```

### Implementing a Finder

```php
use Mistralys\X4\Database\Core\ItemCollectionInterface;

class MyItemFinder
{
    private array $filters = [];
    
    public function __construct(private ItemCollectionInterface $collection) {}
    
    public function getCollection(): ItemCollectionInterface {
        return $this->collection;
    }
    
    public function selectSize(string $size): self {
        $this->filters['size'] = $size;
        return $this;
    }
    
    public function getAll(): array {
        $items = $this->collection->getAll();
        
        foreach ($this->filters as $key => $value) {
            $items = array_filter($items, fn($item) => 
                $item->getSize() === $value
            );
        }
        
        return array_values($items);
    }
}
```

### Using Finders

```php
// Find large Argon ships from base game
$ships = ShipDefs::getInstance()->findShips()
    ->selectSize('l')
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
    ->getAll();

// Find mk2 or mk3 weapons with high DPS
$weapons = WeaponDefs::getInstance()->findWeapons()
    ->selectMinMk(2)
    ->selectMinDPS(1000.0)
    ->selectSize('m')
    ->getAll();

// Find compatible engines for a ship
$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');
$engines = $ship->getEngines()
    ->selectDataSource('vanilla')
    ->selectMinThrust(5000.0)
    ->getAll();
```

---

## Design Philosophy

### Collection-Item Pattern

The Collection-Item pattern provides a consistent interface for all game data:

1. **Collections** (e.g., `ShipDefs`) hold all items of a type
2. **Items** (e.g., `ShipDef`) represent individual entities
3. **Finders** provide fluent filtering across collections
4. **Interfaces** ensure consistency across all implementations

### Benefits

- **Type Safety**: Strong typing with interfaces
- **Consistency**: All collections work the same way
- **Discoverability**: Predictable method names (`getAll()`, `getByID()`, `find*()`)
- **Testability**: Easy to mock interfaces
- **Extensibility**: New collections follow the same pattern

---

## Notes

- All items in collections implement `CollectionItemInterface`
- All collections implement `ItemCollectionInterface`
- Finders use **fluent interface** (methods return `self` for chaining)
- VariantID parsing handles complex variant formats automatically
- BaseExtractor utilities reduce boilerplate in data extraction classes
