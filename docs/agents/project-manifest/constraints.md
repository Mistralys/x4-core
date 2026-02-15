# Current Constraints & Rules

This document lists established constraints, conventions, and rules that must be followed when working with the X4 Core codebase.

---

## Code Style & Standards

### PHP Version
- **Minimum PHP 8.4 required**
- All files must use `declare(strict_types=1);`
- Use PHP 8.x features (typed properties, constructor property promotion, etc.)

### Type Declarations
- **REQUIRED**: All parameters must have type declarations
- **REQUIRED**: All return types must be declared
- **Exception**: `mixed` type when truly necessary
- Use nullable types: `?string` instead of `string|null` (unless union types needed)

### Namespace Convention
```php
namespace Mistralys\X4\[Domain]\[Subdomain];
```
- Root namespace: `Mistralys\X4`
- One class per file
- File path must match namespace structure

### PHPDoc Comments
- **Required** for all public methods
- Include `@package` and `@subpackage` tags
- Document parameter types that aren't obvious from signature
- Document complex return array structures

---

## Naming Conventions

### Classes

| Pattern | Purpose | Example |
|---------|---------|---------|
| `*Def` | Individual item/entity | `WareDef`, `ShipDef` |
| `*Defs` | Collection (plural) | `WareDefs`, `ShipDefs` |
| `Known*` | Static helper with constants | `KnownFactions`, `KnownShips` |
| `*Finder` | Fluent filter interface | `WareFinder`, `ShipFinder` |
| `*Extractor` | Data extraction logic | `WaresExtractor` |
| `*Builder` | Build orchestration | `DatabaseBuilder` |
| `*Exception` | Error class | `UIException`, `FactionException` |
| `*Interface` | Interface definition | `FinderInterface` |
| `*Trait` | Reusable trait | `CollectionItemTrait` |
| `Base*` | Abstract base class | `BasePage`, `BaseAjaxMethod` |

### Methods

| Pattern | Purpose | Example |
|---------|---------|---------|
| `get*()` | Property getter | `getLabel()`, `getID()` |
| `set*()` | Fluent setter (return `self`) | `setLabel()`, `setIcon()` |
| `is*()` | Boolean check | `isGeneric()`, `isExtension()` |
| `has*()` | Boolean check (existence) | `hasVariants()`, `hasTag()` |
| `create*()` | Factory method | `createUI()`, `createDataGrid()` |
| `make*()` | Configuration method | `makeSubmit()`, `makeOutline()` |
| `add*()` | Add to collection | `addColumn()`, `addRow()` |
| `register*()` | Register handler | `registerPage()`, `registerAjaxMethod()` |
| `select*()` | Finder filter method | `selectGroup()`, `selectFaction()` |
| `find*()` | Return finder instance | `findWares()`, `findShips()` |

### Constants

- **ALL_CAPS_SNAKE_CASE**
- Group related constants with common prefix
- Example: `ERROR_*`, `KEY_*`, `FACTION_*`

### Properties

- **camelCase** (never snake_case)
- Private by default
- Public only when necessary for external access
- Use typed properties: `private string $name;`

---

## Architectural Constraints

### Singleton Pattern (Collections)
```php
class WareDefs
{
    private static ?self $instance = null;
    
    public static function getInstance(): self
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() { /* Load data */ }
}
```
- **MUST** use for all `*Defs` collection classes
- Private constructor
- `getInstance()` returns singleton
- Data loaded lazily on first access

### Collection-Item Interface
```php
// Collections MUST implement:
interface ItemCollectionInterface
{
    public function getAll(): array;
    public function getByID(string $id): CollectionItemInterface;
    public function getDefaultID(): string;
    public function getDefault(): CollectionItemInterface;
}

// Items MUST implement:
interface CollectionItemInterface
{
    public function getID(): string;
    public function getLabel(): string;
    // ... domain-specific methods
}
```

### Data Immutability
- **Items are read-only** once created
- No setters on `*Def` classes (except internal initialization)
- Modifications trigger full database rebuild

### JSON Data Format
- **Pretty-printed** (JSON_PRETTY_PRINT)
- **Consistent key names** (use constants: `KEY_*`)
- **Associative arrays** keyed by ID where appropriate
- Support `fromArray()` and `toArray()` methods

---

## UI Layer Constraints

### Component Interface
```php
class Component
{
    // Fluent methods return $this
    public function setConfig(...): self;
    
    // Final render returns string
    public function render(): string;
}
```
- All UI components MUST have `render(): string`
- Configuration methods MUST return `self` for chaining
- No direct echo in components (return HTML string)

### Page Structure
```php
abstract class BasePage
{
    // Load data, validate, etc.
    abstract public function preRender(): void;
    
    // Generate HTML output
    abstract public function _render(): void;
    
    // Don't override render() - it calls preRender() then _render()
    final public function render(): string;
}
```
- **NEVER** override `render()` in page implementations
- Use `preRender()` for data loading
- Use `_render()` for output generation

### Request Parameters
- Page routing: `$_GET['page']`
- Sub-page routing: `$_GET['view']`
- **DON'T** use custom parameter names for routing

---

## Database Layer Constraints

### Finder Pattern
```php
class WareFinder extends BaseFinder
{
    public function selectGroup(string|WareGroup $group): self
    {
        // Filter in-memory
        return $this;
    }
    
    public function getAll(): array
    {
        // Return filtered results
        return $results;
    }
}
```
- **MUST** extend `BaseFinder`
- Filter methods MUST return `self`
- Final `getAll()` returns array
- **NO DATABASE QUERIES** - all filtering in-memory

### Extractor Pattern
```php
class WaresExtractor
{
    public function __construct(DataFolders $folders) { }
    
    public function extract(): void
    {
        // 1. Read unpacked game files
        // 2. Parse and transform
        // 3. Save to JSON
    }
}
```
- Accept `DataFolders` in constructor (access to unpacked data)
- Single `extract()` method
- Write to `/data/*.json`
- **Idempotent** - can run multiple times safely

### Collection Data Source
- **ALWAYS** load from JSON files in `/data`
- **NEVER** query game files directly at runtime
- Use extractors to build/update data files

### Multi-Value Fields Convention

Some entity fields support multiple values (e.g., ships built by multiple factions):

**Primary = First Convention:**
- When a field stores multiple values as an array, the **first element is considered primary**
- Backward-compatible single-value getters MUST return the first element
- Example:
  ```php
  // Ship has builderFactionIDs: ["argon", "teladi"]
  $ship->getBuilderFactionID();   // Returns "argon" (first = primary)
  $ship->getBuilderFactionIDs();  // Returns ["argon", "teladi"]
  ```

**Field Naming:**
- Single-value key (deprecated): `builderFactionID`, `makerRace`
- Multi-value key (current): `builderFactionIDs`, `makerRaces` (plural)
- Internal storage: `private array $builderFactionIDs` (typed as `string[]`)

**API Requirements:**
- **MUST** preserve backward-compatible single-value getter (returns first element)
- **MUST** provide new multi-value getter returning `string[]`
- **MUST** provide entity array getter (e.g., `getBuilderFactions(): FactionDef[]`)
- **MUST** provide `hasMultiple*(): bool` predicate

**Format Migration:**
- `fromArray()` MUST handle:
  1. New format: `builderFactionIDs: ["argon", "teladi"]`
  2. Old format (string): `builderFactionID: "argon teladi"` (space-separated)
  3. Old format (array): `builderFactionID: ["argon", "teladi"]` (intermediate rebuild state)
- Empty values default to `["generic"]` (Ships/Modules) or `["unknown"]` (Shields/Engines/Weapons)

**Applies To:**
- Ships: `builderFactionIDs`
- Modules: `builderFactionIDs`
- Shields: `makerRaces`
- Engines: `makerRaces`
- Weapons: `makerRaces`

---

## File I/O Constraints

### Reading Files
- **Use AppUtils FileHelper** classes:
  - `FolderInfo::factory($path)`
  - `FileInfo::factory($path)`
- Check existence before reading
- Handle errors gracefully

### Writing Data Files
```php
// REQUIRED format
file_put_contents(
    $filePath,
    json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
);
```
- Pretty-print for version control
- Preserve Unicode characters

### File Operations
- **All file operations are synchronous**
- No async file I/O
- No file locking (single-process environment)

---

## Error Handling

### Exception Hierarchy
```php
X4Exception (base)
  ├─ UIException
  ├─ FactionException
  ├─ BlueprintException
  └─ [DomainException]
```
- **MUST** extend appropriate domain exception
- Define error code constants
- Provide descriptive messages

### Error Codes
- Use class constants: `ERROR_*`
- 6-digit codes: `[domain][sequence]`
  - Example: `106501` (X4 Application domain, error #01)
- Document error codes in class constants

### Try-Catch Usage
- Catch specific exceptions when possible
- Re-throw with context when necessary
- Log or display user-friendly messages

---

## Session Management

### Session Lifecycle
```php
// In X4Application constructor:
if (PHP_SAPI !== 'cli') {
    session_start();
} else {
    $_SESSION = [];
}
```
- **Auto-start** in web context
- **Mock session** in CLI context
- Don't manually start sessions elsewhere

### Session Storage
- **Messages only** (user notifications)
- Minimal data storage
- Clear data after use

---

## Localization

### Translation Keys
- Use game translation codes: `{pageID,textID}`
- Example: `{20101,42}`
- Convert via `Language->ts('{20101,42}')`

### Supported Languages
- English (44) - default
- German (49)
- French (33)
- Spanish (34)
- Italian (39)
- Russian (7)
- Korean (82)

### Adding Translations
- **DON'T** add custom translations
- **ONLY** use game-provided translations
- Languages extracted from game files

---

## Testing Constraints

### Unit Tests
- Located in `/tests/X4Tests`
- Use PHPUnit 9.5+
- Configuration in `phpunit.xml`
- Helper classes in `/tests/X4Tests/Helpers`

### Dev Config
- `dev-config.php` for local settings
- **NEVER** commit `dev-config.php`
- Provide `dev-config.dist.php` template

---

## Dependencies

### Adding Dependencies
- **MUST** use Composer
- Update `composer.json`
- Run `composer update`
- Commit `composer.lock`

### Forbidden Dependencies
- **NO** database libraries (no MySQL, PostgreSQL, etc.)
- **NO** async/promise libraries
- **NO** heavy frameworks (Laravel, Symfony full stack)
- Use lightweight utilities only

### Version Constraints
- PHP extensions: Use `*` (any version)
- Composer packages: Use `^` (compatible)
- Example: `"twbs/bootstrap": "^v5.1.3"`

---

## Version Control

### Git Ignore
- `/vendor/` - Composer dependencies
- `/dev-config.php` - Local config
- `/cache/*` - Runtime cache
- IDE files (`.idea/`, `.vscode/`)

### Commit Data Files
- **DO commit** `/data/*.json` (extracted game data)
- These are **distribution files**
- Keep them updated with game patches

---

## Performance Rules

### Memory Management
- Collections cache loaded data
- Single load per request
- No memory cleanup needed (PHP handles)

### Avoid Loops
- Use `array_*` functions when possible
- Use `array_map`, `array_filter`, `array_reduce`

### No Premature Optimization
- Clarity over cleverness
- Profile before optimizing
- In-memory filtering is acceptable for <10K items

---

## Security Rules

### Input Validation
- **ALWAYS** validate request parameters
- Use typed parameters where possible
- Sanitize output (htmlspecialchars)

### No Direct File Access
- Don't expose `/data` files directly via web
- Access only through collections

### No User-Generated Code
- **NEVER** use `eval()`
- **NEVER** execute user-provided code
- Parse, don't execute

---

## Documentation Rules

### Required Documentation
- All public classes need class-level PHPDoc
- All public methods need method-level PHPDoc
- README.md must be kept updated
- changelog.md for version history

### Code Comments
- Explain **WHY**, not **WHAT**
- Complex logic needs inline comments
- No commented-out code in commits

---

## Build Process

### Composer Scripts
```bash
composer build              # Full rebuild
composer extract-wares      # Individual extractor
composer extract-factions   # Individual extractor
# etc.
```
- Each extractor is callable independently
- Order matters: macros → translations → wares → ships

### Database Updates
- Run after game patches
- Regenerate all JSON files
- Commit updated data files

---

## Summary Checklist

When adding new features:

- ✓ Use PHP 8.4+ features
- ✓ Include type declarations
- ✓ Follow naming conventions
- ✓ Implement required interfaces
- ✓ Support `fromArray()` / `toArray()` for data classes
- ✓ Use singleton pattern for collections
- ✓ Make UI components return HTML strings
- ✓ Use fluent interfaces for builders
- ✓ Validate all inputs
- ✓ Document public APIs
- ✓ Add PHPStan-compatible types
- ✓ Test with PHPUnit
- ✓ Update this manifest if adding new patterns
