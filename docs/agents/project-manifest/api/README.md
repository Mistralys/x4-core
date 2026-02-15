# Public API Reference

> **Navigation Hub**: Quick access to API documentation organized by functional domain.  
> **Last Updated**: February 15, 2026

This document provides **signatures only** for all public classes, methods, properties, and constants in the X4 Core library. Implementation details are excluded.

---

## 📚 API Documentation by Domain

### Core Application
- **[Root & Game Namespace](#root-namespace)** - Application foundation, exceptions, and game installation

### User Interface
- **[UI Components & Pages](ui-api.md)** - Button, Icon, Text, DataGrid, BasePage, Messages, AJAX

### Database Layer - Core Infrastructure
- **[Core Patterns](database-core-api.md)** - CollectionItem, ItemCollection, Finder, VariantID interfaces

### Database Layer - Game Data
- **[Factions & Wares](database-game-data-api.md)** - FactionDef, WareDef, DataSourceDef, WareGroups
- **[Ships & Engines](database-ships-api.md)** - ShipDef, EngineDef, ShipSettings, ShipClasses, ShipSizes
- **[Equipment & Combat](database-equipment-api.md)** - WeaponDef, ModuleDef, ShieldDef, WeaponSystems
- **[Blueprints](database-crafting-api.md)** - BlueprintDef, BlueprintCategories
- **[Localization & Metadata](database-localization-api.md)** - Translations, Languages, SlotTypes, MacroIndex

### XML Processing
- **[XML Utilities](xml-api.md)** - DOMExtended, ElementExtended, TagFinders, XPath utilities

---

## 🔍 Quick Reference Guide

**When to use which API file:**

| Task | API File |
|------|----------|
| Creating UI components (buttons, icons) | [ui-api.md](ui-api.md) |
| Working with pages and navigation | [ui-api.md](ui-api.md) |
| Implementing collection patterns | [database-core-api.md](database-core-api.md) |
| Filtering ships or equipment | [database-core-api.md](database-core-api.md) (Finder pattern) |
| Accessing faction data | [database-game-data-api.md](database-game-data-api.md) |
| Querying ships or engines | [database-ships-api.md](database-ships-api.md) |
| Finding compatible weapons | [database-equipment-api.md](database-equipment-api.md) |
| Working with blueprints | [database-crafting-api.md](database-crafting-api.md) |
| Adding translations | [database-localization-api.md](database-localization-api.md) |
| Parsing X4 game XML | [xml-api.md](xml-api.md) |

---

## Root Namespace

### Mistralys\X4\X4Exception

Base exception class for X4 Core library.

**Extends:** `BaseException`

---

### Mistralys\X4\UnexpectedClassException

Specialized exception for cases where a class instance does not match the expected class.

#### Constants
```php
ERROR_CODE: int = 106901
```

#### Methods
```php
__construct(string $expectedClass, mixed $actual): void
```

---

### Mistralys\X4\X4Application

Base class for an X4 application.

#### Constants
```php
PACKAGE_NAME: string = 'mistralys/x4-core'
ERROR_UI_INSTANCE_NOT_CREATED: int = 106501
```

#### Methods
```php
__construct(): void
static getDataFolder(): FolderInfo
exit(): never
abstract getTitle(): string
abstract registerPages(UserInterface $ui): void
abstract registerAjaxMethods(AjaxMethods $methods): void
abstract getDefaultPageID(): ?string
abstract getVersion(): string
createUI(string $webrootURL, string $vendorURL = ''): UserInterface
getUI(): UserInterface
static initCache(): void
```

---

## Game Namespace

### Mistralys\X4\Game\X4Game

Handles X4 game installation information.

#### Methods
```php
__construct(FolderInfo $gameFolder): void
static create(string|PathInfoInterface|SplFileInfo $gameFolder): self
getVersion(): string
```

---

## Notes

- **Return Type Annotations**: When a method returns an array of specific objects, the comment indicates the type (e.g., `// Returns WareDef[]`).
- **Stringable Types**: `Interface_Stringable` and `StringableInterface` refer to objects implementing `__toString()`.
- **Factory Methods**: Many classes provide `static create()` or `static getInstance()` methods.
- **Fluent Interfaces**: Methods returning `self` are chainable.
- **Abstract Methods**: Classes extending base classes must implement abstract methods.
- **Interface Implementations**: Many classes implement specific interfaces defining their contracts.
