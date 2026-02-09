# X4 Core - Project Manifest

**Version:** 1.0  
**Last Updated:** February 9, 2026  
**Purpose:** Source of Truth for AI Agent Sessions

---

## Overview

This **Project Manifest** provides comprehensive documentation of the X4 Core library architecture, patterns, and public APIs. It is designed to enable AI agents to understand the codebase without reading every line of code.

### What is X4 Core?

X4 Core is a PHP library that provides database and utility classes for accessing **X4: Foundations** game data in an object-oriented way. It includes:

- Faction, Ware, Ship, Module, and Blueprint databases
- Translation system for 7 languages
- UI component library with Bootstrap integration
- Data extraction and build tools
- XML processing utilities

The library is designed as a dependency for other projects and tools in the X4 ecosystem.

---

## Quick Navigation

### Core Documentation

| Document | Description |
|----------|-------------|
| [Tech Stack & Patterns](tech-stack.md) | Runtime environment, dependencies, and architectural patterns |
| [File Tree](file-tree.md) | Complete directory structure with explanations |
| [Public API](public-api.md) | Complete public method signatures (no implementations) |
| [Data Flows](data-flows.md) | How UI interacts with services and data flows through the system |
| [Constraints & Rules](constraints.md) | Established coding rules, conventions, and constraints |
| [Extraction Reference](extraction-reference.md) | XML data extraction patterns, algorithms, and troubleshooting (modular) |

---

## Document Purpose

### [Tech Stack & Patterns](tech-stack.md)

**What you'll find:**
- PHP 8.4+ runtime requirements and extensions
- Composer dependencies (framework, UI, testing)
- Architectural patterns used throughout:
  - Collection-Item Pattern
  - Finder Pattern
  - Extraction-Builder Pattern
  - UI Component Pattern
  - Exception Hierarchy
- Data storage approach (JSON files)
- Naming conventions
- Localization setup
- Caching strategy
- Composer build scripts

**Use this when:**
- Starting a new feature
- Understanding the overall architecture
- Adding new dependencies
- Working with collections or finders

---

### [File Tree](file-tree.md)

**What you'll find:**
- Complete directory structure
- Purpose of each major directory
- Organization of source code by namespace
- Database domain structure (Factions, Ships, Wares, etc.)
- UI component organization
- File naming conventions

**Use this when:**
- Locating specific files
- Understanding code organization
- Adding new classes
- Navigating the codebase

---

### [Public API](public-api.md)

**What you'll find:**
- **Signatures only** for all public classes
- Constants, properties, and methods
- Constructor signatures
- Return types and parameters
- **NO implementation details**

**Organized by namespace:**
- Root (X4Application, Exceptions)
- Game (X4Game)
- UI (UserInterface, Components, Pages, DataGrid)
- Database (Collections, Items, Finders, Extractors)
- XML (DOM utilities)

**Use this when:**
- Understanding available APIs
- Checking method signatures
- Implementing features that use existing classes
- Avoiding reading source code

---

### [Data Flows](data-flows.md)

**What you'll find:**
- Layer architecture diagram
- 9 core data flow patterns:
  1. Application Initialization
  2. Database Queries
  3. Page Rendering
  4. DataGrid Rendering
  5. AJAX Requests
  6. Translation System
  7. Database Build Process
  8. UI Component Creation
  9. Message System
- Entity relationship diagrams
- Common interaction patterns
- Performance considerations
- Extension points

**Use this when:**
- Understanding how data moves through the system
- Implementing new pages or components
- Working with collections and finders
- Adding new features

---

### [Constraints & Rules](constraints.md)

**What you'll find:**
- Code style requirements (PHP 8.4+, strict types)
- Type declaration requirements
- Naming conventions (classes, methods, constants)
- Architectural constraints:
  - Singleton pattern for collections
  - Collection-Item interfaces
  - Data immutability rules
  - JSON format requirements
- UI layer constraints
- Database layer constraints
- File I/O rules (synchronous only)
- Error handling (exception hierarchy)
- Session management
- Localization rules
- Testing requirements
- Security rules
- Build process

**Use this when:**
- Writing new code
- Reviewing code
- Ensuring consistency with existing patterns
- Making architectural decisions

---

### [Extraction Reference](extraction-reference.md)

**What you'll find:**
- **Modular documentation** split across 6 focused documents:
  - XML Sources & Schema - Data locations and file structure
  - Extraction Patterns - Macro resolution, Two-Phase/Single-Phase patterns
  - Advanced Features - Variant IDs, DLC inheritance
  - Equipment Compatibility - Filtering algorithms
  - Troubleshooting - Common errors and solutions
  - Development Guide - Step-by-step extractor creation
- XML file location mappings for all game data
- Complete extractor inventory (11 extractors)
- Build dependency chain
- DOM query patterns
- Error handling strategies
- Complete working examples

**Use this when:**
- Creating new extractors
- Debugging extraction failures
- Understanding where game data is stored
- Learning the extraction architecture
- Troubleshooting build errors

---

## Key Concepts

### Collections & Items

The library uses a **Collection-Item Pattern**:

```
FactionDefs (Collection)
    ├─ Singleton: getInstance()
    ├─ getAll() → FactionDef[]
    └─ getByID(id) → FactionDef

FactionDef (Item)
    ├─ getID() → string
    ├─ getLabel() → string
    └─ [domain-specific methods]
```

**All domain entities follow this pattern:**
- Wares & WareDefs
- Ships & ShipDefs
- Modules & ModuleDefs
- Blueprints & BlueprintDefs
- DataSources & DataSourceDefs

### Finders

Finders provide **fluent filtering** over collections:

```php
WareDefs::getInstance()
    ->findWares()
    ->selectGroup(WareGroups::GROUP_ENGINES)
    ->selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
    ->selectLabel('Argon')
    ->getAll(); // Returns filtered WareDef[]
```

### UI Components

All UI components follow a **fluent builder pattern**:

```php
Button::create('Save')
    ->setIcon(Icon::save())
    ->colorPrimary()
    ->makeSubmit('saveBtn')
    ->render(); // Returns HTML string
```

### Page Hierarchy

```
BasePage (abstract)
    ├─ preRender()    [Load data]
    ├─ _render()      [Generate output]
    └─ getURL()       [Build URLs]

BasePageWithNav (extends BasePage)
    ├─ initSubPages() [Register sub-pages]
    └─ getSubPage()   [Get active sub-page]

BaseSubPage (abstract)
    └─ renderContent() [Generate sub-page content]
```

---

## Development Workflow

### 1. Understanding Existing Code

1. Start with [Tech Stack](tech-stack.md) to understand patterns
2. Check [File Tree](file-tree.md) to locate relevant files
3. Review [Public API](public-api.md) for available methods
4. Check [Data Flows](data-flows.md) for interaction patterns
5. Verify [Constraints](constraints.md) before making changes

### 2. Adding New Features

1. Identify pattern from [Tech Stack](tech-stack.md)
2. Follow naming conventions from [Constraints](constraints.md)
3. Implement required interfaces from [Public API](public-api.md)
4. Test data flow using patterns from [Data Flows](data-flows.md)
5. Document in PHPDoc format

### 3. Database Updates

```bash
composer build  # Full rebuild from game data
```

Follow the extraction order documented in [Data Flows § Database Build Flow](data-flows.md#7-database-build-flow).

---

## Common Tasks

### Task: Add a New Collection

**Required Files:**
1. `*Def.php` - Item class (implements `CollectionItemInterface`)
2. `*Defs.php` - Collection class (singleton, implements `ItemCollectionInterface`)
3. `*Extractor.php` - Extraction logic
4. Update `composer.json` scripts section
5. Create `/data/*.json` file

**References:**
- [Tech Stack § Collection-Item Pattern](tech-stack.md#1-collection-item-pattern)
- [Constraints § Collection-Item Interface](constraints.md#collection-item-interface)
- [Data Flows § Database Build Flow](data-flows.md#7-database-build-flow)

### Task: Add a New Page

**Required:**
1. Extend `BasePage` or `BasePageWithNav`
2. Implement abstract methods
3. Register in `X4Application::registerPages()`

**References:**
- [Public API § UI\Page\BasePage](public-api.md#mistralysx4uipagebasepage)
- [Data Flows § Page Rendering Flow](data-flows.md#3-page-rendering-flow)
- [Constraints § Page Structure](constraints.md#page-structure)

### Task: Filter Collection Data

**Pattern:**
```php
ShipDefs::getInstance()
    ->findShips()
    ->selectClass(ShipClasses::CLASS_FIGHTER)
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->getAll();
```

**References:**
- [Tech Stack § Finder Pattern](tech-stack.md#2-finder-pattern)
- [Data Flows § Database Query Flow](data-flows.md#2-database-query-flow)
- [Public API § Database\Ships\ShipFinder](public-api.md#mistralysx4databaseshipsshipfinder)

### Task: Create UI Component

**Pattern:**
```php
$grid = $ui->createDataGrid();
$grid->addColumn('id', 'ID');
$grid->addColumn('name', 'Name');
$grid->addRowsFromObjects($items);
echo $grid->render();
```

**References:**
- [Tech Stack § UI Component Pattern](tech-stack.md#4-ui-component-pattern)
- [Data Flows § DataGrid Rendering Flow](data-flows.md#4-datagrid-rendering-flow)
- [Public API § UI\DataGrid](public-api.md#mistralysx4uidatagrid)

---

## External Resources

### Project Links

- **GitHub Repository**: [mistralys/x4-core](https://github.com/Mistralys/x4-core)
- **Packagist**: [mistralys/x4-core](https://packagist.org/packages/mistralys/x4-core)
- **License**: MIT

### Related Projects

- [X4 Data Extractor](https://github.com/Mistralys/x4-data-extractor) - Unpacks game data files
- [X4 Game Notes](https://github.com/Mistralys/x4-game-notes) - Documentation and guides
- [X4 Savegame Parser](https://github.com/Mistralys/x4-savegame-parser) - Parse X4 savegames
- [X4 Cargo Size Mod](https://github.com/Mistralys/x4-mod-cargo-sizes) - Example mod using X4 Core

---

## Maintenance

### Updating This Manifest

When making significant architectural changes:

1. Update the relevant document(s)
2. Update the "Last Updated" date above
3. Add a note in the project's `changelog.md`
4. Increment the version number if warranted

### Changelog

- **1.0** (Feb 8, 2026) - Initial manifest creation

---

## Questions or Issues?

If you find gaps in this documentation or encounter patterns not documented here:

1. Check the actual source code for implementation details
2. Update this manifest with your findings
3. Submit a pull request or issue on GitHub

---

## License

This documentation is part of the X4 Core project and is licensed under the MIT License.

---

**For AI Agents**: This manifest is your primary reference. Start here for any task involving the X4 Core library. The documents linked above provide comprehensive information without requiring you to read implementation code.
