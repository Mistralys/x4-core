# Project File Tree

## Root Structure

```
x4-core/
├── cache/                      # Class and repository caching
│   └── class-repository-v1.php
├── css/                        # Stylesheets for UI components
│   ├── ui.css
│   └── datagrid/
│       └── row.css
├── data/                       # Extracted game data (JSON)
│   ├── blueprints.json
│   ├── data-sources.json
│   ├── engines.json
│   ├── factions.json
│   ├── lang-*.json            # Translation files (7 languages)
│   ├── macro-index.json
│   ├── modules.json
│   ├── ship-settings.json
│   ├── shields.json
│   ├── ships.json
│   ├── wares.json
│   └── weapons.json
├── docs/                       # Documentation
│   └── agents/
│       └── project-manifest/  # AI agent manifest (this folder)
├── js/                        # JavaScript for UI components
│   └── datagrid/
│       └── row.js
├── localization/              # Localization files and cache
│   └── cache.json
├── src/                       # Source code (main application)
│   └── X4/                    # Root namespace
│       ├── Database/          # Database layer
│       ├── Game/              # Game integration
│       ├── UI/                # User interface components
│       ├── XML/               # XML processing utilities
│       ├── UnexpectedClassException.php
│       ├── X4Application.php  # Base application class
│       └── X4Exception.php    # Base exception class
├── tests/                     # Unit tests
│   └── X4Tests/
│       ├── ExampleUI/
│       ├── Helpers/
│       └── Suites/
├── vendor/                    # Composer dependencies
│   ├── autoload.php
│   ├── bin/                   # Executable tools
│   ├── components/            # Frontend components
│   ├── mistralys/             # Mistralys packages
│   ├── phpunit/
│   ├── phpstan/
│   └── [other vendors]/
├── changelog.md               # Version history
├── composer.json              # Project dependencies
├── dev-config.dist.php        # Development config template
├── dev-config.php             # Development config (gitignored)
├── LICENSE                    # MIT License
├── phpunit.xml                # PHPUnit configuration
└── README.md                  # Project documentation
```

## Source Code Structure (`src/X4/`)

```
src/X4/
├── Database/                  # Core database layer
│   ├── Blueprints/           # Blueprint system
│   │   ├── Categories/       # Blueprint categorization
│   │   │   ├── Types/        # Specific category types
│   │   │   │   ├── CountermeasureCategory.php
│   │   │   │   ├── DeployableCategory.php
│   │   │   │   ├── DroneCategory.php
│   │   │   │   ├── EngineCategory.php
│   │   │   │   ├── MineCategory.php
│   │   │   │   ├── MissileCategory.php
│   │   │   │   ├── ModificationCategory.php
│   │   │   │   ├── ModuleCategory.php
│   │   │   │   ├── ShieldCategory.php
│   │   │   │   ├── ShipCategory.php
│   │   │   │   ├── SkinCategory.php
│   │   │   │   ├── ThrusterCategory.php
│   │   │   │   ├── TurretCategory.php
│   │   │   │   ├── UnknownCategory.php
│   │   │   │   ├── WeaponCategory.php
│   │   │   │   └── WelfareCategory.php
│   │   │   ├── BaseBlueprintCategory.php
│   │   │   ├── BlueprintCategories.php
│   │   │   ├── BlueprintCategoryInterface.php
│   │   │   └── CategorySelection.php
│   │   ├── Selection/        # Blueprint selection utilities
│   │   │   └── TypeSelection.php
│   │   ├── Types/            # Typed blueprint classes
│   │   │   ├── CountermeasureBlueprint.php
│   │   │   ├── DeployableBlueprint.php
│   │   │   ├── DroneBlueprint.php
│   │   │   ├── EngineBlueprint.php
│   │   │   ├── MineBlueprint.php
│   │   │   ├── MissileBlueprint.php
│   │   │   ├── ModificationBlueprint.php
│   │   │   ├── ModuleBlueprint.php
│   │   │   ├── ShieldBlueprint.php
│   │   │   ├── ShipBlueprint.php
│   │   │   ├── SkinBlueprint.php
│   │   │   ├── ThrusterBlueprint.php
│   │   │   ├── TurretBlueprint.php
│   │   │   ├── UnknownBlueprint.php
│   │   │   ├── WeaponBlueprint.php
│   │   │   └── WelfareBlueprint.php
│   │   ├── BlueprintDef.php
│   │   ├── BlueprintDefs.php
│   │   ├── BlueprintException.php
│   │   ├── BlueprintExtractor.php
│   │   └── BlueprintSelection.php
│   ├── Builder/              # Code generation utilities
│   │   └── KnownItemsClassGenerator.php
│   ├── Core/                 # Core database abstractions
│   │   ├── Finder/           # Finder pattern implementations
│   │   │   ├── BaseFinder.php
│   │   │   ├── DataSourceSelectionInterface.php
│   │   │   ├── DataSourceSelectionTrait.php
│   │   │   └── FinderInterface.php
│   │   ├── CollectionItemInterface.php
│   │   ├── CollectionItemTrait.php
│   │   ├── ItemCollectionInterface.php
│   │   ├── MultiValueFieldTrait.php
│   │   └── VariantID.php
│   ├── DataSources/          # DLC and data source tracking
│   │   ├── DataSourceDef.php
│   │   ├── DataSourceDefs.php
│   │   ├── DataSourcesExtractor.php
│   │   ├── DLCs.php
│   │   └── KnownDataSources.php
│   ├── Engines/              # Engine performance database
│   │   ├── EngineDef.php
│   │   ├── EngineDefs.php
│   │   ├── EngineException.php
│   │   ├── EngineExtractor.php
│   │   ├── EngineFinder.php
│   │   └── EngineMacroExtractor.php
│   ├── Factions/             # Faction database
│   │   ├── FactionDef.php
│   │   ├── FactionDefs.php
│   │   ├── FactionException.php
│   │   ├── FactionsExtractor.php
│   │   └── KnownFactions.php
│   ├── MacroIndex/           # Macro file indexing
│   │   ├── MacroFileDef.php
│   │   ├── MacroFileDefs.php
│   │   └── MacroIndexExtractor.php
│   ├── Modules/              # Station modules
│   │   ├── ModuleCategories.php
│   │   ├── ModuleCategory.php
│   │   ├── ModuleDef.php
│   │   ├── ModuleDefs.php
│   │   ├── ModuleException.php
│   │   ├── ModuleExtractor.php
│   │   ├── ModuleFinder.php
│   │   └── ModuleMacroExtractor.php
│   ├── Ships/                # Ship database
│   │   ├── Equipment/        # Ship equipment compatibility
│   │   │   ├── ShipEquipmentFinder.php
│   │   │   └── ShipSlotDefinition.php
│   │   ├── KnownShips.php
│   │   ├── ShipClass.php
│   │   ├── ShipClasses.php
│   │   ├── ShipDef.php
│   │   ├── ShipDefs.php
│   │   ├── ShipFinder.php
│   │   ├── ShipSize.php
│   │   ├── ShipSizes.php
│   │   └── ShipsExtractor.php
│   ├── Shields/              # Shield performance database
│   │   ├── ShieldDef.php
│   │   ├── ShieldDefs.php
│   │   ├── ShieldException.php
│   │   ├── ShieldFinder.php
│   │   ├── ShieldMacroExtractor.php
│   │   └── ShieldsExtractor.php
│   ├── SlotTypes/            # Equipment slot types
│   │   ├── KnownSlotTypes.php
│   │   ├── SlotType.php
│   │   └── SlotTypes.php
│   ├── Translations/         # Translation system
│   │   ├── Language.php
│   │   ├── Languages.php
│   │   ├── TranslationDefs.php
│   │   ├── TranslationException.php
│   │   └── TranslationExtractor.php
│   ├── Wares/                # Ware (item) database
│   │   ├── WareDef.php
│   │   ├── WareDefs.php
│   │   ├── WareFinder.php
│   │   ├── WareGroup.php
│   │   ├── WareGroups.php
│   │   └── WaresExtractor.php
│   ├── Weapons/              # Weapon performance database
│   │   ├── BulletMacroExtractor.php
│   │   ├── WeaponDef.php
│   │   ├── WeaponDefs.php
│   │   ├── WeaponException.php
│   │   ├── WeaponFinder.php
│   │   ├── WeaponMacroExtractor.php
│   │   └── WeaponsExtractor.php
│   ├── WeaponSystems/        # Weapon system classification
│   │   ├── KnownWeaponSystems.php
│   │   ├── WeaponSystem.php
│   │   └── WeaponSystems.php
│   └── DatabaseBuilder.php   # Main build orchestrator
├── Game/                     # Game installation integration
│   └── X4Game.php
├── UI/                       # User interface layer
│   ├── Ajax/                 # AJAX handling
│   │   ├── AjaxMethodException.php
│   │   ├── AjaxMethodInterface.php
│   │   ├── AjaxMethods.php
│   │   └── BaseAjaxMethod.php
│   ├── DataGrid/             # Data grid component
│   │   ├── Cell/             # Cell types
│   │   │   └── MergedCell.php
│   │   ├── Column/           # Column types and handlers
│   │   │   ├── BaseHandler.php
│   │   │   ├── DecorationHandler.php
│   │   │   ├── FormatHandler.php
│   │   │   ├── MergedColumn.php
│   │   │   └── ObjectHandler.php
│   │   ├── Decorations/      # Cell decorations
│   │   │   ├── BaseDecoration.php
│   │   │   ├── BaseLinkDecoration.php
│   │   │   ├── LinkByCallback.php
│   │   │   └── LinkByMethod.php
│   │   ├── Row/              # Row types
│   │   │   ├── MergedRow.php
│   │   │   └── RegularRow.php
│   │   ├── DataGridException.php
│   │   ├── GridCell.php
│   │   ├── GridColumn.php
│   │   ├── GridRow.php
│   │   ├── ValueFetcherInterface.php
│   │   └── ValueFetcherTrait.php
│   ├── Messaging/            # User messaging system
│   │   ├── Message.php
│   │   └── Messages.php
│   ├── Page/                 # Page system
│   │   ├── BasePage.php
│   │   ├── BasePageWithNav.php
│   │   ├── BaseSubPage.php
│   │   ├── NavItem.php
│   │   └── PageNavItem.php
│   ├── Button.php            # Button component
│   ├── Console.php           # Console output
│   ├── DataGrid.php          # Main DataGrid class
│   ├── Icon.php              # Icon component
│   ├── Text.php              # Text component
│   ├── UIException.php       # UI exceptions
│   └── UserInterface.php     # Main UI controller
├── XML/                      # XML processing
│   ├── Finders/              # XML element finders
│   │   ├── BaseTagFinder.php
│   │   ├── TagFinderInterface.php
│   │   ├── TagNameFinder.php
│   │   ├── TagSelection.php
│   │   └── TagSelectorFinder.php
│   ├── DOMExtended.php       # Extended DOM document
│   ├── ElementExtended.php   # Extended DOM element
│   └── XMLException.php      # XML exceptions
├── UnexpectedClassException.php
├── X4Application.php         # Base application class
└── X4Exception.php           # Base exception class
```

## Key Directory Purposes

### `/data`
Stores extracted game data in JSON format. Generated by extractor tools, version-controlled for distribution.

### `/src/X4/Database`
The data access layer. Each subdirectory represents a domain (Factions, Ships, Wares, etc.) with:
- `*Def.php` - Individual item class
- `*Defs.php` - Collection class (singleton)
- `*Extractor.php` - Extraction logic
- `*Finder.php` - Filtering utility (if applicable)

### `/src/X4/UI`
UI component library with Bootstrap integration. Includes:
- Component classes (Button, Icon, Text)
- DataGrid system
- Page/SubPage hierarchy
- AJAX method handling
- Messaging system

### `/src/X4/XML`
XML parsing utilities with fluent finder interface, wrapping PHP's DOMDocument with convenient methods.

### `/cache`
Runtime cache for class reflection and other temporary data.

### `/vendor`
Composer-managed dependencies including:
- Frontend libraries (Bootstrap, jQuery, FontAwesome)
- PHP utilities (AppUtils, Symfony components)
- Development tools (PHPUnit, PHPStan)

## File Naming Conventions

- **Classes**: PascalCase (e.g., `WareDef.php`)
- **Interfaces**: PascalCase + Interface suffix (e.g., `FinderInterface.php`)
- **Traits**: PascalCase + Trait suffix (e.g., `CollectionItemTrait.php`)
- **Exceptions**: Domain + Exception (e.g., `FactionException.php`)
- **Extractors**: Domain + Extractor (e.g., `WaresExtractor.php`)
- **Config files**: kebab-case (e.g., `dev-config.php`)
- **Data files**: kebab-case (e.g., `macro-index.json`)
