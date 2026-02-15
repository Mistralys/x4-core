# XML API Reference

> **Domain**: XML Processing Utilities - DOMExtended, ElementExtended, Finders  
> **Last Updated**: February 15, 2026

[← Back to API Index](README.md)

---

## Overview

The XML namespace provides extended XML processing capabilities built on top of PHP's DOM:

- **DOMExtended**: Enhanced DOM document with XPath and CSS selector support
- **ElementExtended**: Enhanced DOM element with fluent query interface
- **TagFinders**: Fluent interface for finding elements by tag name or CSS selector

---

## Table of Contents

- [Core Classes](#core-classes)
- [Element Querying](#element-querying)
- [Usage Patterns](#usage-patterns)

---

## Core Classes

### Mistralys\X4\XML\XMLException

Exception for XML-related errors.

**Extends:** `X4Exception`

---

### Mistralys\X4\XML\DOMExtended

Extended DOM document handler with enhanced query capabilities.

#### Methods
```php
__construct(DOMDocument $document): void
getDOM(): DOMDocument
static createFromFile(string|FileInfo|SplFileInfo $file): self
static createFromString(string $xml): self
byTagName(string $tagName): TagNameFinder
bySelector(string $selector): TagSelectorFinder
getXPath(): DOMXPath
getSelectorConverter(): CssSelectorConverter
getXML(): string
```

**Usage:**
```php
// Load from file
$dom = DOMExtended::createFromFile('path/to/file.xml');

// Load from string
$xml = '<root><item id="1">Test</item></root>';
$dom = DOMExtended::createFromString($xml);

// Query by tag name
$items = $dom->byTagName('item')->getAll();

// Query by CSS selector
$elements = $dom->bySelector('item[id="1"]')->getAll();

// Get XPath instance
$xpath = $dom->getXPath();
$results = $xpath->query('//item[@id="1"]');

// Export as XML string
$xmlString = $dom->getXML();
```

---

### Mistralys\X4\XML\ElementExtended

Extended DOM element wrapper with enhanced functionality.

#### Methods
```php
__construct(DOMExtended $dom, DOMElement $element): void
hasAttribute(string $name): bool
getAttribute(string $name): ?string
hasChildren(): bool
getChildren(): array // Returns ElementExtended[]
getXML(): string
findChildren(): TagSelection
getDOMElement(): DOMElement
```

**Usage:**
```php
$dom = DOMExtended::createFromFile('ship.xml');
$shipElements = $dom->byTagName('ship')->getAll();

foreach ($shipElements as $shipElement) {
    // Check for attributes
    if ($shipElement->hasAttribute('id')) {
        $id = $shipElement->getAttribute('id');
        echo "Ship ID: {$id}";
    }
    
    // Get child elements
    if ($shipElement->hasChildren()) {
        $children = $shipElement->getChildren();
        foreach ($children as $child) {
            echo $child->getDOMElement()->nodeName;
        }
    }
    
    // Get XML representation
    $xml = $shipElement->getXML();
    
    // Query children
    $properties = $shipElement->findChildren()->byTagName('property')->getAll();
}
```

---

## Element Querying

### TagNameFinder

Fluent interface for finding elements by tag name.

#### Methods
```php
// (Methods inherited from base finder)
getAll(): array // Returns ElementExtended[]
getFirst(): ?ElementExtended
```

**Usage:**
```php
$dom = DOMExtended::createFromFile('components.xml');

// Find all <component> elements
$components = $dom->byTagName('component')->getAll();

// Find first <component> element
$firstComponent = $dom->byTagName('component')->getFirst();
```

---

### TagSelectorFinder

Fluent interface for finding elements by CSS selector.

#### Methods
```php
// (Methods inherited from base finder)
getAll(): array // Returns ElementExtended[]
getFirst(): ?ElementExtended
```

**Usage:**
```php
$dom = DOMExtended::createFromFile('wares.xml');

// Find by CSS selector
$weapons = $dom->bySelector('ware[group="weapons"]')->getAll();

// Complex selector
$mkWeapons = $dom->bySelector('ware[id*="mk1"][size="m"]')->getAll();

// Get first match
$firstWeapon = $dom->bySelector('ware[group="weapons"]')->getFirst();
```

---

### TagSelection

Result collection from element queries.

#### Methods
```php
// (Methods for iterating and accessing results)
getAll(): array // Returns ElementExtended[]
getFirst(): ?ElementExtended
byTagName(string $tagName): TagNameFinder
```

**Usage:**
```php
$shipElement = $dom->byTagName('ship')->getFirst();

// Query children of element
$properties = $shipElement->findChildren()
    ->byTagName('property')
    ->getAll();

foreach ($properties as $property) {
    $name = $property->getAttribute('name');
    $value = $property->getAttribute('value');
    echo "{$name}: {$value}";
}
```

---

## Usage Patterns

### Parsing X4 Game XML

```php
// Load ship XML file
$dom = DOMExtended::createFromFile('ships/ship_arg_s_scout_01_a_macro.xml');

// Find ship element
$ship = $dom->byTagName('ship')->getFirst();

if ($ship) {
    // Get ship properties
    $properties = $ship->findChildren()->byTagName('property')->getAll();
    
    foreach ($properties as $property) {
        $name = $property->getAttribute('name');
        $value = $property->getAttribute('value');
        echo "Property: {$name} = {$value}";
    }
    
    // Get connection elements
    $connections = $ship->findChildren()->byTagName('connection')->getAll();
    
    foreach ($connections as $connection) {
        $ref = $connection->getAttribute('ref');
        echo "Connection: {$ref}";
    }
}
```

### Complex CSS Selectors

```php
$dom = DOMExtended::createFromFile('index/wares.xml');

// Find all medium weapons from Argon
$argonWeapons = $dom->bySelector('ware[id^="weapon_arg"][size="m"]')->getAll();

// Find all mk2 or mk3 items
$mkItems = $dom->bySelector('ware[id*="mk2"], ware[id*="mk3"]')->getAll();

// Find items with specific tags
$shields = $dom->bySelector('ware[tags~="shield"]')->getAll();
```

### Nested Element Traversal

```php
$dom = DOMExtended::createFromFile('components.xml');

// Find component, then query its children
$component = $dom->byTagName('component')->getFirst();

if ($component) {
    // Get connections within component
    $connections = $component->findChildren()
        ->byTagName('connection')
        ->getAll();
    
    foreach ($connections as $conn) {
        // Further query connection children
        $tags = $conn->findChildren()->byTagName('tag')->getAll();
        
        foreach ($tags as $tag) {
            echo $tag->getAttribute('value');
        }
    }
}
```

### Attribute Extraction with Defaults

```php
function extractAttribute(ElementExtended $element, string $attrName, $default = null) {
    if ($element->hasAttribute($attrName)) {
        return $element->getAttribute($attrName);
    }
    return $default;
}

$shipElement = $dom->byTagName('ship')->getFirst();
$hullValue = extractAttribute($shipElement, 'hull', 0);
$massValue = extractAttribute($shipElement, 'mass', 100.0);
```

### XPath Queries

```php
$dom = DOMExtended::createFromFile('macros.xml');
$xpath = $dom->getXPath();

// Complex XPath query
$results = $xpath->query('//component[@class="ship"]/connections/connection[@ref="weapon"]');

foreach ($results as $node) {
    $element = new ElementExtended($dom, $node);
    echo $element->getAttribute('ref');
}
```

### Converting Between DOMElement and ElementExtended

```php
$dom = DOMExtended::createFromFile('data.xml');

// Get ElementExtended
$element = $dom->byTagName('item')->getFirst();

// Get underlying DOMElement
$domElement = $element->getDOMElement();
echo $domElement->nodeName;
echo $domElement->nodeValue;

// Work with native DOM methods
$domElement->setAttribute('newattr', 'value');
```

---

## Notes

- **CSS Selectors**: Powered by Symfony's CssSelectorConverter
- **XPath Support**: Full XPath 1.0 query support via `getXPath()`
- **Fluent Interface**: All finder methods return `self` for chaining
- **Null Safety**: Methods return `null` when elements not found (use `getFirst()`)
- **ElementExtended**: Wraps `DOMElement` with enhanced functionality
- **XML Export**: Use `getXML()` to serialize elements or documents
- **Attribute Access**: `getAttribute()` returns `null` if attribute doesn't exist
- **Child Queries**: Use `findChildren()` for descendant queries within an element
