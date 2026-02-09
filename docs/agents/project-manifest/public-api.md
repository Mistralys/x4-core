# Public API Reference

This document contains **signatures only** for all public classes, methods, properties, and constants in the X4 Core library. Implementation details are excluded.

## Table of Contents

- [Root Namespace](#root-namespace)
- [Game Namespace](#game-namespace)
- [UI Namespace](#ui-namespace)
- [Database Namespace](#database-namespace)
- [XML Namespace](#xml-namespace)

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

## UI Namespace

### Mistralys\X4\UI\UIException

Base exception for UI-related errors.

**Extends:** `X4Exception`

---

### Mistralys\X4\UI\UserInterface

User interface handler - renders the UI.

#### Constants
```php
ERROR_NO_PAGES_REGISTERED: int = 105801
ERROR_PAGE_CLASS_NOT_FOUND: int = 105802
ERROR_INVALID_PAGE_CLASS: int = 105803
ERROR_UNKNOWN_PAGE_ID: int = 105804
THEME_SUPERHERO: string = 'superhero'
```

#### Methods
```php
__construct(X4Application $application, string $webrootURL, string $vendorURL = ''): void
getMessages(): Messages
static displayException(BaseException $e): void
addJSHead(string $statement): self
addJSOnload(string $statement): self
getTitle(): string
getRequest(): Request
getWebrootURL(): string
setUnitTestingURL(string $unitTestingURL): self
createDataGrid(): DataGrid
registerPage(string $urlName, string $className): void
registerAjaxMethod(AjaxMethodInterface $ajaxMethod): void
getAjaxMethods(): AjaxMethods
getPageClass(string $id): string
createPage(string $id): BasePage
getApplication(): X4Application
getActivePageID(): string
addInternalStylesheet(string $file): self
addExternalStylesheet(string $url): self
addVendorStylesheet(string $packageName, string $file): self
addInternalJS(string $file): self
addExternalJS(string $url): self
addVendorJS(string $packageName, string $file): self
makeFooterFixed(bool $fixed = true): self
setFooterContent(StringableInterface|string|null $content): self
render(): string
```

---

### Mistralys\X4\UI\Text

Text utility class with Bootstrap color support.

#### Methods
```php
__construct(string|number|Interface_Stringable|null $label): void
static create(string|number|Interface_Stringable|null $label = null): Text
colorSuccess(): self
colorWarning(): self
colorPrimary(): self
colorMuted(): self
colorDanger(): self
colorInfo(): self
setColorName(string $name): self
render(): string
```

---

### Mistralys\X4\UI\Button

Button component with Bootstrap styling.

#### Methods
```php
__construct(string|Interface_Stringable|null $label): void
static create(string|Interface_Stringable|null $label = ''): Button
setLabel(string|Interface_Stringable|null $label): self
setIcon(Icon $icon): self
setTooltip(string|Interface_Stringable|null $tooltip): self
makeSubmit(string $name, string $value = ''): self
link(string $url, bool $newTab = false): self
click(string|Interface_Stringable|null $statement): self
colorPrimary(): self
colorSuccess(): self
colorDanger(): self
colorWarning(): self
colorInfo(): self
makeOutline(): self
setColorType(string $type): self
sizeLarge(): self
sizeSmall(): self
sizeExtraSmall(): self
render(): string
```

---

### Mistralys\X4\UI\Icon

Icon component using FontAwesome.

#### Methods
```php
__construct(string $name, string $type): void
static typeSolid(string $name): Icon
static typeRegular(string $name): Icon
colorPrimary(): self
colorSuccess(): self
colorDanger(): self
colorMuted(): self
colorWarning(): self
colorInfo(): self
setColorClass(string $class): self
setTooltip(string $text): self
static yes(): Icon
static no(): Icon
static delete(): Icon
static unpack(): Icon
static backup(): Icon
static back(): Icon
static analyze(): Icon
static previous(): Icon
static next(): Icon
static first(): Icon
static last(): Icon
static allItems(): Icon
static save(): Icon
render(): string
```

---

### Mistralys\X4\UI\Console

Console output utility.

#### Methods
```php
// (Public interface not extensively documented in extraction)
```

---

### Mistralys\X4\UI\Messaging\Messages

Message collection handler for user notifications.

#### Constants
```php
TYPE_SUCCESS: string = 'success'
TYPE_INFO: string = 'info'
TYPE_WARNING: string = 'warning'
TYPE_ERROR: string = 'error'
```

#### Methods
```php
__construct(): void
writeToSession(): void
addSuccess(string|StringableInterface|null $message, ?int $code = null): self
addInfo(string|StringableInterface|null $message, ?int $code = null): self
addWarning(string|StringableInterface|null $message, int $code): self
addError(string|StringableInterface|null $message, int $code): self
addMessage(string|StringableInterface|null $message, string $type, ?int $code = null): self
getMessages(): array // Returns Message[]
hasMessages(): bool
clear(): self
```

---

### Mistralys\X4\UI\Messaging\Message

Individual message instance.

#### Methods
```php
__construct(string $type, string|StringableInterface|null $message, ?int $code): void
getType(): string
getMessage(): string
getCode(): ?int
render(): string
```

---

### Mistralys\X4\UI\Page\BasePage

Base class for application pages.

#### Constants
```php
REQUEST_PARAM_PAGE: string = 'page'
REQUEST_PARAM_VIEW: string = 'view'
```

#### Methods
```php
__construct(UserInterface $ui): void
getRequest(): Request
getApplication(): X4Application
getUI(): UserInterface
getID(): string
abstract getTitle(): string
abstract getSubtitle(): string
abstract getAbstract(): string
abstract getNavTitle(): string
abstract preRender(): void
abstract _render(): void
abstract getNavItems(): array // Returns NavItem[]
redirectWithSuccessMessage(string $url, string|StringableInterface|null $message, ?int $code = null): never
redirectWithErrorMessage(string $url, string|StringableInterface|null $message, int $code): never
redirectWithInfoMessage(string $url, string|StringableInterface|null $message, ?int $code = null): never
redirectWithWarningMessage(string $url, string|StringableInterface|null $message, int $code): never
redirect(string $url): never
getURL(array $params = []): string
abstract getURLParams(): array
render(): string
```

---

### Mistralys\X4\UI\Page\BasePageWithNav

Base class for pages with sub-navigation.

#### Constants
```php
ERROR_INVALID_SUBPAGE_ID: int = 89401
```

#### Methods
```php
__construct(UserInterface $ui): void
abstract getDefaultSubPageID(): string
abstract initSubPages(): void
getNavItems(): array
getSubPage(): BaseSubPage
```

---

### Mistralys\X4\UI\Page\BaseSubPage

Base class for sub-pages within a page.

#### Methods
```php
__construct(BasePage $page): void
getURL(array $params = []): string
generateOutput(): void
abstract getURLParams(): array
abstract isInSubnav(): bool
abstract getURLName(): string
abstract renderContent(): void
abstract getTitle(): string
abstract getSubtitle(): string
abstract getAbstract(): string
```

---

### Mistralys\X4\UI\Page\NavItem

Navigation item representation.

#### Methods
```php
__construct(string $label, string $url): void
getLabel(): string
getUrl(): string
isActive(): bool
```

---

### Mistralys\X4\UI\Page\PageNavItem

Page-specific navigation item.

#### Methods
```php
__construct(BasePage $page): void
```

---

### Mistralys\X4\UI\DataGrid

Utility class used to generate HTML code for data grids.

#### Methods
```php
__construct(UserInterface $ui): void
getUI(): UserInterface
addColumn(string $keyName, string $label): GridColumn
addRow(GridRow $entry): self
createRow(): RegularRow
countColumns(): int
createMergedRow(): MergedRow
addRowFromArray(array $values): self
addRowFromObject(object $object): self
addRowsFromObjects(array $objects): self // Parameter: object[]
getColumns(): array // Returns GridColumn[]
getRows(): array // Returns GridRow[]
optionStriped(bool $enable): self
optionBordered(bool $enable): self
render(): string
```

---

### Mistralys\X4\UI\DataGrid\GridColumn

Represents a DataGrid column.

#### Methods
```php
// (Detailed method signatures in source)
```

---

### Mistralys\X4\UI\DataGrid\GridRow

Base class for DataGrid rows.

#### Methods
```php
// (Detailed method signatures in source)
```

---

### Mistralys\X4\UI\DataGrid\GridCell

Represents a DataGrid cell.

#### Methods
```php
// (Detailed method signatures in source)
```

---

### Mistralys\X4\UI\Ajax\AjaxMethodInterface

Interface for AJAX methods.

#### Methods
```php
getName(): string
process(): array
```

---

### Mistralys\X4\UI\Ajax\BaseAjaxMethod

Base class for AJAX methods.

#### Methods
```php
// (Detailed method signatures in source)
```

---

### Mistralys\X4\UI\Ajax\AjaxMethods

AJAX method registry.

#### Methods
```php
// (Detailed method signatures in source)
```

---

## Database Namespace

### Core Abstractions

#### Mistralys\X4\Database\Core\CollectionItemInterface

Interface for collection items.

```php
getWareID(): string
getLabel(): string
getWare(): WareDef
getVariantID(): VariantID
getID(): string
```

---

#### Mistralys\X4\Database\Core\ItemCollectionInterface

Interface for item collections.

```php
getAll(): array // Returns CollectionItemInterface[]
```

---

#### Mistralys\X4\Database\Core\VariantID

Handles ship/ware variant identification.

##### Constants
```php
MARKS: array = ['mk1', 'mk2', 'mk3']
```

##### Methods
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

---

### Factions

#### Mistralys\X4\Database\Factions\FactionException

Exception for faction-related errors.

---

#### Mistralys\X4\Database\Factions\FactionDef

Definition of a game faction.

##### Constants
```php
KEY_ID: string = 'id'
KEY_NAME: string = 'name'
KEY_DATA_SOURCE_ID: string = 'dataSourceID'
```

##### Methods
```php
__construct(string $raceID, string $label, string $dataSourceID): void
static fromArray(array $raceDef): self
getID(): string
getShortIDs(): array // Returns string[]
getLabel(): string
getDataSourceID(): string
isGeneric(): bool
```

---

#### Mistralys\X4\Database\Factions\FactionDefs

Collection of all factions in the game.

##### Constants
```php
SHORT_ID_ATF: string = 'atf'
SHORT_ID_PIR: string = 'pir'
SHORT_ID_MAPPINGS: array
```

##### Methods
```php
static getInstance(): FactionDefs
getDataFile(): JSONFile
static getStorageFile(): JSONFile
detectFactionByID(string $macroOrComponentID): ?string
getFromList(): KnownFactions
getDefaultID(): string
getByID(string $id): FactionDef
getAll(): array // Returns FactionDef[]
getDefault(): FactionDef
```

---

#### Mistralys\X4\Database\Factions\KnownFactions

Utility class with constants and getter methods for all known factions.

##### Constants
```php
FACTION_ALLIANCE_WORD: string = 'alliance'
FACTION_ANTIGONE_REPUBLIC: string = 'antigone'
FACTION_ARGON_FEDERATION: string = 'argon'
FACTION_CIVILIAN: string = 'civilian'
// ... (35+ faction constants)
```

##### Methods
```php
static getInstance(): KnownFactions
getAllianceOfTheWord(): FactionDef
getAntigoneRepublic(): FactionDef
getArgonFederation(): FactionDef
// ... (35+ getter methods for factions)
```

---

### Wares

#### Mistralys\X4\Database\Wares\WareDef

Definition of a ware.

##### Constants
```php
KEY_WARE_ID: string = 'wareID'
KEY_LABEL: string = 'label'
KEY_GROUP: string = 'group'
KEY_TAGS: string = 'tags'
KEY_DATA_SOURCE_ID: string = 'dataSourceID'
KEY_SIZE: string = 'size'
KEY_FACTIONS: string = 'factions'
KEY_MACRO_ID: string = 'macroID'
KEY_VARIANT_ID: string = 'variantID'
KEY_COMPONENT: string = 'component'
```

##### Methods
```php
__construct(string $id, string $macroID, string $label, string $groupID, VariantID $variantID, array $tags, string $dataSourceID, string $size, array $factionIDs, array $component): void
getID(): string
getLabel(): string
getVariantID(): VariantID
getWare(): WareDef
getGroupID(): string
getGroup(): WareGroup
getTags(): array // Returns string[]
getDataSourceID(): string
getDataSource(): DataSourceDef
getMacroID(): string
getMacro(): MacroFileDef
getSize(): string
getFactionIDs(): array // Returns string[]
getFactions(): array // Returns FactionDef[]
getComponent(): array // Returns array{tags:string[]}
getCompatibilityTags(): array // Returns string[] - Merged ware + component tags
static fromArray(array $wareDef): WareDef
hasTag(string $tag): bool
toArray(): array
getWareID(): string
getSpecs(): array // DEPRECATED: Use getComponent() instead
```

---

#### Mistralys\X4\Database\Wares\WareDefs

Collection of all wares available in the game.

##### Methods
```php
static getInstance(): WareDefs
__construct(): void
getDefaultID(): string
getByTag(string $tagName): array // Returns WareDef[]
findWares(): WareFinder
getDataFile(): JSONFile
findByMacro(string $macroNameOrFile): ?WareDef
getMacroIndex(): array // Returns array<string, WareDef>
getByID(string $id): WareDef
getAll(): array // Returns WareDef[]
getDefault(): WareDef
```

---

#### Mistralys\X4\Database\Wares\WareFinder

Specialized filtering utility to find wares based on various criteria.

##### Methods
```php
getCollection(): ItemCollectionInterface
selectTag(string $tagName): self
selectGroup(string|WareGroup $group): self
selectDataSource(string|DataSourceDef $dataSource): self
selectLabel(string $label): self
getAll(): array // Returns WareDef[]
```

---

#### Mistralys\X4\Database\Wares\WareGroup

Ware group definition.

##### Methods
```php
__construct(string $id, string $label): void
getID(): string
getLabel(): string
getWares(): array // Returns WareDef[]
```

---

#### Mistralys\X4\Database\Wares\WareGroups

Collection of all ware groups.

##### Constants
```php
GROUP_AGRICULTURAL: string = 'agricultural'
GROUP_COUNTERMEASURES: string = 'countermeasures'
GROUP_CRAFTING: string = 'crafting'
// ... (35+ group constants)
```

##### Methods
```php
static getInstance(): WareGroups
getDefaultID(): string
getByID(string $id): WareGroup
getAll(): array // Returns WareGroup[]
getDefault(): WareGroup
```

---

### Ships

#### Mistralys\X4\Database\Ships\ShipDef

Ship definition.

##### Constants
```php
KEY_WARE_ID: string = 'wareID'
KEY_LABEL: string = 'label'
KEY_SIZE: string = 'size'
KEY_BUILDER_FACTION_ID: string = 'builderFactionID'
KEY_CLASS_ID: string = 'classID'
KEY_USED_BY: string = 'usedBy'
KEY_DATA_SOURCE_ID: string = 'dataSourceID'
KEY_VARIANT_ID: string = 'variantID'
KEY_VARIANTS: string = 'variants'
```

##### Methods
```php
__construct(string $id, string $label, VariantID $variantID, string $size, string $builderFactionID, string $classID, array $usedBy, string $dataSourceID, array $variants): void
static fromArray(array $def): ShipDef
getID(): string
getLabel(): string
getSizeID(): string
getVariantID(): VariantID
hasVariants(): bool
getSize(): ShipSize
getBuilderFactionID(): string
getBuilderFaction(): FactionDef
getClassID(): string
getClass(): ShipClass
getDataSourceID(): string
getDataSource(): DataSourceDef
getUsedBy(): array // Returns FactionDef[]
toArray(): array
getWareID(): string
getWare(): WareDef
```

---

#### Mistralys\X4\Database\Ships\ShipDefs

Collection of all ships.

##### Methods
```php
static getInstance(): ShipDefs
__construct(): void
getDefaultID(): string
getDataFile(): JSONFile
getFactions(): array // Returns FactionDef[]
findShips(): ShipFinder
getDataSources(): array // Returns DataSourceDef[]
getByID(string $id): ShipDef
getAll(): array // Returns ShipDef[]
getDefault(): ShipDef
```

---

#### Mistralys\X4\Database\Ships\ShipClass

Ship class definition.

##### Methods
```php
__construct(string $id, string $label): void
getID(): string
getLabel(): string
```

---

#### Mistralys\X4\Database\Ships\ShipClasses

Collection of ship classes.

##### Constants
```php
CLASS_GUNBOAT: string = 'gunboat'
CLASS_BATTLESHIP: string = 'battleship'
CLASS_SCOUT: string = 'scout'
// ... (20+ class constants)
```

##### Methods
```php
static getInstance(): ShipClasses
getDefaultID(): string
getByID(string $id): ShipClass
getAll(): array // Returns ShipClass[]
getDefault(): ShipClass
```

---

#### Mistralys\X4\Database\Ships\ShipSize

Ship size definition.

##### Methods
```php
__construct(string $id, string $label): void
getID(): string
getLabel(): string
```

---

#### Mistralys\X4\Database\Ships\ShipSizes

Collection of ship sizes.

##### Constants
```php
SIZE_XS: string = 'xs'
SIZE_S: string = 's'
SIZE_M: string = 'm'
SIZE_L: string = 'l'
SIZE_XL: string = 'xl'
```

##### Methods
```php
static getInstance(): ShipSizes
getDefaultID(): string
idExists(string $id): bool
getByID(string $id): ShipSize
getAll(): array // Returns ShipSize[]
getDefault(): ShipSize
```

---

#### Mistralys\X4\Database\Ships\ShipFinder

Specialized filtering utility to find ships.

##### Methods
```php
getCollection(): ItemCollectionInterface
selectBuilderFaction(string|FactionDef $faction): self
selectClass(string|ShipClass $class): self
selectSize(string|ShipSize $size): self
selectDataSource(string|DataSourceDef $dataSource): self
selectLabel(string $label): self
getAll(): array // Returns ShipDef[]
```

---

### Slot Types

#### Mistralys\X4\Database\SlotTypes\SlotType

Represents a single equipment slot type (Weapon, Shield, etc.).

**Extends:** `CollectionItem`

| Visibility | Method | Return Type | Description |
|------------|--------|-------------|-------------|
| public | `getLabel()` | `string` | Returns the human-readable label. |
| public | `getPrimaryTag()` | `string` | Returns the primary XML tag for this slot type. |

---

#### Mistralys\X4\Database\SlotTypes\SlotTypes

Collection of all known slot types.

**Extends:** `Collection<SlotType>`

| Visibility | Method | Return Type | Description |
|------------|--------|-------------|-------------|
| public static | `getInstance()` | `SlotTypes` | Singleton accessor. |
| public | `getByID(string $id)` | `SlotType` | Retrieves a slot type by its ID. |
| public | `getAll()` | `SlotType[]` | Returns all slot types. |

---

#### Mistralys\X4\Database\SlotTypes\KnownSlotTypes

Class constants for known slot type IDs.

| Visibility | Constant | Type | Value |
|------------|----------|------|-------|
| public | `WEAPON` | `string` | `'weapon'` |
| public | `SHIELD` | `string` | `'shield'` |
| public | `TURRET` | `string` | `'turret'` |
| public | `ENGINE` | `string` | `'engine'` |
| public | `DOCKING_BAY` | `string` | `'dockingbay'` |
| public | `COUNTERMEASURES` | `string` | `'countermeasures'` |

---

### Blueprints

#### Mistralys\X4\Database\Blueprints\BlueprintException

Exception for blueprint-related errors.

---

#### Mistralys\X4\Database\Blueprints\BlueprintDef

Abstract base class for blueprint definitions.

##### Constants
```php
KEY_WARE_ID: string = 'wareID'
KEY_LABEL: string = 'label'
KEY_CATEGORY_ID: string = 'categoryID'
KEY_VARIANT_ID: string = 'variantID'
```

##### Methods
```php
__construct(string $id, string $label, VariantID $variantID, BlueprintCategoryInterface $category): void
static fromArray(array $def): self
getID(): string
getLabel(): string
getVariantID(): VariantID
abstract getTypeLabel(): string
getCategory(): BlueprintCategoryInterface
getCategoryID(): string
getName(): string // @deprecated Use getID()
getWareID(): string
getWare(): WareDef
```

---

#### Mistralys\X4\Database\Blueprints\BlueprintDefs

Collection of all blueprints.

##### Methods
```php
static getInstance(): BlueprintDefs
static resetInstance(): void
getDataFile(): JSONFile
getID(): string
getBlueprintClass(): string
getLabel(): string
createSelection(): BlueprintSelection
getBlueprints(): array // Returns BlueprintDef[]
countBlueprints(): int
getBlueprintByID(string $blueprintID): BlueprintDef
blueprintIDExists(string $blueprintID): bool
getDefaultID(): string
getByID(string $id): BlueprintDef
getAll(): array // Returns BlueprintDef[]
getDefault(): BlueprintDef
```

---

### Translations

#### Mistralys\X4\Database\Translations\Language

Game language definition.

##### Methods
```php
__construct(int $id, string $locale): void
getID(): int
getLocale(): string
ts(string $code): string // Translates a translation code e.g. {1005,42}
t(int $pageID, int $textID): string // Translates a text ID
getTranslator(): TranslationDefs
```

---

#### Mistralys\X4\Database\Translations\Languages

Collection of available languages.

##### Constants
```php
LANGUAGE_SPANISH: int = 34
LANGUAGE_RUSSIAN: int = 7
LANGUAGE_ENGLISH: int = 44
LANGUAGE_ITALIAN: int = 39
LANGUAGE_COREAN: int = 82
LANGUAGE_FRENCH: int = 33
LANGUAGE_GERMAN: int = 49
LANGUAGES: array
DEFAULT_LANGUAGE: int = 44
```

##### Methods
```php
static getInstance(): self
getDefaultID(): int
getEnglish(): Language
getRussian(): Language
getGerman(): Language
getFrench(): Language
getSpanish(): Language
getItalian(): Language
getKorean(): Language
getByID(int $id): Language
getAll(): array // Returns Language[]
getDefault(): Language
```

---

### Modules

#### Mistralys\X4\Database\Modules\ModuleDef

Module definition.

##### Constants
```php
KEY_CATEGORY: string = 'category'
KEY_BUILDER_FACTION_ID: string = 'builderFactionID'
KEY_REQUIRED_WORKFORCE: string = 'requiredWorkforce'
KEY_WARES_PRODUCED: string = 'waresProduced'
// ... (additional key constants)
```

##### Methods
```php
__construct(string $wareID, string $label, string $categoryID, string $macroID, string $builderFactionID, string $size, int $hull, int $droneCapacity, int $cargoCapacity, string $cargoType, int $housingCapacity, string $housingFactionID, VariantID $variantID, array $waresProduced): void
static fromArray(mixed $moduleDef): ModuleDef
getID(): string
getLabel(): string
getVariantID(): VariantID
getMacroID(): string
getSize(): string
getHullHitpoints(): int
getDroneCapacity(): int
getCargoCapacity(): int
getCargoType(): string
getHousingCapacity(): int
getHousingFactionID(): string
getMacros(): array // Returns string[]
getCategoryID(): string
getCategory(): ModuleCategory
getBuilderFaction(): FactionDef
getBuilderFactionID(): string
isProduction(): bool
getProducedWares(): array // Returns string[]
getWareID(): string
getWare(): WareDef
```

---

### DataSources

#### Mistralys\X4\Database\DataSources\DataSourceDef

Identifies a source data folder in the game (base game or DLC).

##### Constants
```php
KEY_ID: string = 'id'
KEY_LABEL: string = 'label'
KEY_IS_EXTENSION: string = 'isExtension'
```

##### Methods
```php
__construct(string $id, string $label, bool $isExtension): void
static toArray(DataFolder $dataFolder): array
getID(): string
getLabel(): string
isExtension(): bool
static fromArray(array $data): self
```

---

#### Mistralys\X4\Database\DataSources\DataSourceDefs

Collection tracking data sources.

##### Methods
```php
__construct(): void
static getInstance(): DataSourceDefs
getDefaultID(): string
getDataFile(): JSONFile
getByID(string $id): DataSourceDef
getDefault(): DataSourceDef
getAll(): array // Returns DataSourceDef[]
```

---

### MacroIndex

#### Mistralys\X4\Database\MacroIndex\MacroFileDef

Macro file definition.

##### Methods
```php
// (Detailed method signatures in source)
```

---

#### Mistralys\X4\Database\MacroIndex\MacroFileDefs

Collection of macro files.

##### Methods
```php
// (Detailed method signatures in source)
```

---

### DatabaseBuilder

#### Mistralys\X4\Database\DatabaseBuilder

Command endpoint for the Composer script commands used to build the database.

##### Methods
```php
static extractBlueprints(): void
static extractWares(): void
static extractFactions(): void
static extractTranslations(): void
static extractModules(): void
static build(): void
static extractShips(): void
static extractMacroIndex(): void
static extractDataSources(): void
static getGameInfo(): X4GameInfo
static getDataFolders(): DataFolders
```

---

## XML Namespace

### Mistralys\X4\XML\XMLException

Exception for XML-related errors.

---

### Mistralys\X4\XML\DOMExtended

Extended DOM document handler.

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

---

### Mistralys\X4\XML\ElementExtended

Extended DOM element wrapper.

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

---

## Notes

- **Return Type Annotations**: When a method returns an array of specific objects, the comment indicates the type (e.g., `// Returns WareDef[]`).
- **Stringable Types**: `Interface_Stringable` and `StringableInterface` refer to objects implementing `__toString()`.
- **Factory Methods**: Many classes provide `static create()` or `static getInstance()` methods.
- **Fluent Interfaces**: Methods returning `self` are chainable.
- **Abstract Methods**: Classes extending base classes must implement abstract methods.
