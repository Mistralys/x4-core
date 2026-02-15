# Tech Stack & Architectural Patterns

## Runtime Environment

- **PHP Version**: 8.4+
- **Required Extensions**:
  - `ext-simplexml` - XML parsing
  - `ext-json` - JSON data handling
  - `ext-mbstring` - Multi-byte string support
  - `ext-dom` - DOM document manipulation
  - `ext-curl` - HTTP requests

## Core Dependencies

### Application Framework
- **mistralys/application-utils** (>=2.3.2) - Core utility library
- **mistralys/application-utils-core** (>=2.3.12) - Core utilities
- **mistralys/application-localization** (>=2.1.1) - Localization support

### Data Extraction
- **mistralys/x4-data-extractor** (>=2.0.0) - Game data extraction library

### Frontend Libraries
- **twbs/bootstrap** (^v5.1.3) - UI framework
- **thomaspark/bootswatch** (^v5.1.3) - Bootstrap themes
- **components/jquery** (>=3.5.1) - JavaScript library
- **fortawesome/font-awesome** (^5.15) - Icon library

### XML/CSS Processing
- **symfony/css-selector** (>=v7.2.0) - CSS selector to XPath conversion

### Development Tools
- **phpunit/phpunit** (>=9.5.20) - Unit testing
- **phpstan/phpstan** (>=1.6.1) - Static analysis
- **roave/security-advisories** (dev-latest) - Security checks

## Architectural Patterns

### 1. Collection-Item Pattern

The codebase extensively uses a **Collection-Item** pattern for managing game data:

- **Collection Classes** (e.g., `FactionDefs`, `WareDefs`, `ShipDefs`, `ShieldDefs`):
  - Implement singleton pattern via `getInstance()`
  - Provide `getAll()` to retrieve all items
  - Provide `getByID()` for specific item lookup
  - Manage data loading from JSON files
  - Act as factories for their respective item types

- **Item Classes** (e.g., `FactionDef`, `WareDef`, `ShipDef`, `ShieldDef`):
  - Implement `CollectionItemInterface`
  - Provide domain-specific getter methods
  - Support serialization via `toArray()` and `fromArray()`
  - Use `VariantID` for variant identification

**Example - Shields:**
```php
// Get shield by ID
$shield = ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1');
echo $shield->getCapacity();  // 38844
echo $shield->getRechargeRate();  // 173
echo $shield->getFullRechargeTime();  // ~224 seconds

// Filter shields with finder
$racers = ShieldDefs::getInstance()->findShields()
    ->selectType('racer')
    ->selectSize('m')
    ->selectMinCapacity(10000)
    ->sortByRechargeRate()
    ->getAll();
```

### 2. Finder Pattern

Specialized finder classes provide fluent filtering interfaces:

```
WareFinder, ShipFinder, ModuleFinder, ShipEquipmentFinder, ShieldFinder
  - Chainable selection methods (selectDataSource, selectGroup, selectSize, etc.)
  - Final getAll() returns filtered results
  - Implements FinderInterface
```

**Example - Ship Equipment Compatibility:**
```php
$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');

// Get all compatible engines for this ship
$engines = $ship->getEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
    ->selectSize('l')
    ->getAll();

// Filter shields from specific DLC
$shields = $ship->getShields()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_SPLIT_VENDETTA)
    ->selectTag('equipment')
    ->getAll();
```

**Collections in the Project:**
- `FactionDefs` → `FactionDef`
- `WareDefs` → `WareDef`
- `ShipDefs` → `ShipDef`
- `ModuleDefs` → `ModuleDef`
- `BlueprintDefs` → `BlueprintDef`
- `ShieldDefs` → `ShieldDef`
- `EngineDefs` → `EngineDef`
- `WeaponDefs` → `WeaponDef`
- `SlotTypes` → `SlotType` (hardcoded metadata)
- `WeaponSystems` → `WeaponSystem` (hardcoded metadata)
- `DataSources` → `DataSource`
- `Translations` → `Translation`

### 3. Extraction-Builder Pattern

Database building follows a two-phase approach:

1. **Extractors** (`WaresExtractor`, `FactionsExtractor`, etc.):
   - Read from unpacked game data files
   - Parse XML and other game formats
   - Transform to internal representation

2. **Builder** (`DatabaseBuilder`):
   - Orchestrates extraction process
   - Manages dependencies (e.g., macros before wares)
   - Saves to JSON files in `/data` folder

### 4. UI Component Pattern

UI elements follow a builder/fluent interface pattern:

```
Button, Icon, Text, DataGrid
  - Chainable configuration methods
  - Consistent naming: makeX(), setX(), colorX()
  - Final render() produces HTML
```

### 5. Page-SubPage Hierarchy

UI pages use a hierarchical structure:

- **BasePage**: Top-level pages
- **BasePageWithNav**: Pages with sub-navigation
- **BaseSubPage**: Sub-pages within a parent page
- **Request routing**: Uses `page` and `view` parameters

### 6. Exception Hierarchy

Custom exceptions extend from domain-specific base classes:

```
X4Exception (base)
  ├─ UIException
  ├─ FactionException
  ├─ BlueprintException
  ├─ ModuleException
  ├─ XMLException
  └─ TranslationException
```

Each typically defines error code constants.

### 7. Static Factory Methods

Many classes use static factory methods:

- `create()` - For builder-style initialization
- `getInstance()` - For singletons
- `fromArray()` - For deserialization
- `factory()` - For creating instances from various input types

### 8. Extended DOM Pattern

XML processing uses extended wrappers:

- `DOMExtended` wraps `DOMDocument`
- `ElementExtended` wraps `DOMElement`
- Provides fluent finder interfaces (`byTagName()`, `bySelector()`)
- Uses Symfony CSS Selector for jQuery-like queries

### 9. Multi-Value Faction/Race Pattern

Game entities support multiple builder factions or maker races (e.g., ships built by multiple factions like "argon teladi"):

**Pattern Components:**

1. **Internal Storage**: `string[]` instead of single `string`
2. **New JSON Key**: Plural form (`builderFactionIDs`, `makerRaces`) for array storage
3. **Backward-Compatible API**: Original singular getter returns first element
4. **New API Methods**:
   - `get*IDs(): string[]` - Returns array of all IDs
   - `get*s(): EntityDef[]` - Returns array of all entity objects
   - `hasMultiple*(): bool` - Predicate for multi-value entries
5. **Format Migration**: `fromArray()` handles both old (string) and new (array) formats, including space-separated values in old key
6. **Finder Integration**: Uses `array_intersect()` for matching any faction/race

**Implementation Pattern:**

```php
// Entity Definition (ShipDef, ModuleDef, ShieldDef, EngineDef, WeaponDef)
class ShipDef {
    const KEY_BUILDER_FACTION_IDS = 'builderFactionIDs';  // New plural key
    private array $builderFactionIDs;  // Internal storage
    
    // Backward-compatible (returns first)
    public function getBuilderFactionID(): string { 
        return $this->builderFactionIDs[0]; 
    }
    
    // New multi-value API
    public function getBuilderFactionIDs(): array { 
        return $this->builderFactionIDs; 
    }
    
    public function getBuilderFactions(): array {
        return array_map(
            fn($id) => FactionDefs::getInstance()->getByID($id),
            $this->builderFactionIDs
        );
    }
    
    public function hasMultipleBuilderFactions(): bool {
        return count($this->builderFactionIDs) > 1;
    }
    
    // Format migration in fromArray()
    public static function fromArray(array $data): self {
        // Try new format first
        $factionIDs = $data[self::KEY_BUILDER_FACTION_IDS] ?? null;
        
        // Fallback to old format
        if ($factionIDs === null && isset($data[self::KEY_BUILDER_FACTION_ID])) {
            $old = $data[self::KEY_BUILDER_FACTION_ID];
            // Handle both string and array in old key
            $factionIDs = is_array($old) ? $old : explode(' ', $old);
        }
        
        // Default to generic if empty
        if (empty($factionIDs)) {
            $factionIDs = [KnownFactions::FACTION_GENERIC];
        }
        
        return new self(...$factionIDs);
    }
}

// Finder Integration
class ShipFinder {
    private function isMatch(ShipDef $ship): bool {
        if (!empty($this->builderFactions)) {
            $shipFactions = $ship->getBuilderFactionIDs();
            // Match if ANY faction matches (intersection)
            if (empty(array_intersect($shipFactions, $this->builderFactions))) {
                return false;
            }
        }
        return true;
    }
}

// Extractor (parses space-separated values from XML)
class ShipsExtractor {
    private function resolveFaction(string $makerrace): array {
        return explode(' ', $makerrace);  // Returns string[]
    }
}
```

**Applied To:**
- **Ships**: `builderFactionIDs` / `getBuilderFactionIDs()` / `getBuilderFactions()` / `hasMultipleBuilderFactions()`
- **Modules**: Same as Ships
- **Shields**: `makerRaces` / `getMakerRaces()` / `hasMultipleMakerRaces()`
- **Engines**: Same as Shields
- **Weapons**: Same as Shields

**Key Design Decisions:**
- **Primary = First**: When only one value needed, first element is "primary" (backward compatibility)
- **Space-Separated Parsing**: Game XML uses space-separated values (e.g., `makerrace="argon teladi"`)
- **Default Fallback**: Empty values default to `generic` (Ships/Modules) or `unknown` (Shields/Engines/Weapons)
- **No Breaking Changes**: Original API preserved, new methods added

## Data Storage

### JSON Data Files

All extracted game data is stored in the `/data` folder:

```
blueprints.json
data-sources.json
engines.json
factions.json
macro-index.json
modules.json
ship-settings.json
shields.json
ships.json
wares.json
weapons.json
lang-{id}_{locale}.json (translations)
```

### File Format

- **Pretty-printed JSON** for version control friendliness
- **Structured arrays** matching class `fromArray()` expectations
- **Keyed by ID** for efficient lookup

## Dependency Injection

The codebase uses minimal dependency injection:

- **UserInterface** passed to pages via constructor
- **UI components** receive UserInterface instance
- **Application** accessed via `$ui->getApplication()`
- **Collections** use singleton pattern (no DI)

## Naming Conventions

### Classes
- **Defs**: Collection classes (e.g., `WareDefs`)
- **Def**: Individual item classes (e.g., `WareDef`)
- **Extractor**: Data extraction classes
- **Builder**: Build orchestration classes
- **Finder**: Fluent filter interfaces
- **Exception**: Error classes

### Methods
- **get**: Simple property getters
- **is/has**: Boolean checks
- **create**: Static factory methods
- **select**: Filter methods (Finders)
- **make**: Configuration methods returning self
- **register**: Registration methods (UI)

## Localization

- Uses **AppLocalize** library
- **Client library key**: Based on application version
- **Source folder**: `/localization`
- **Cache file**: `/localization/cache.json`
- **Default locale**: German (de_DE)

## Session Management

- **Auto-start**: Sessions started in `X4Application` constructor
- **CLI mode**: Initializes empty `$_SESSION` array
- **Messages**: Written to session on shutdown
- **Not used in CLI**: Gracefully handles CLI execution

## Caching

- **Class cache**: `/cache` folder
- **Repository cache**: `class-repository-v1.php`
- **Set via**: `ClassHelper::setCacheFolder()`

## Composer Scripts

Custom build commands exposed via Composer:

```bash
composer build              # Full rebuild
composer extract-blueprints # Extract blueprints only
composer extract-wares      # Extract wares only
composer extract-factions   # Extract factions only
composer extract-ships      # Extract ships only
composer extract-modules    # Extract modules only
composer extract-macro-index    # Extract macro index
composer extract-datasources    # Extract data sources
```

## Testing

- **Framework**: PHPUnit 9.5+
- **Test location**: `/tests/X4Tests`
- **Configuration**: `phpunit.xml`
- **Helper classes**: `/tests/X4Tests/Helpers`
- **Dev config**: `dev-config.php` (not tracked)
