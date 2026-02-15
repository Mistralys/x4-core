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

### Engines

#### Mistralys\X4\Database\Engines\EngineException

Exception for engine-related errors.

##### Constants
```php
ERROR_ENGINE_NOT_FOUND: int = 142001
ERROR_INVALID_ENGINE_SIZE: int = 142002
ERROR_INVALID_ENGINE_DATA: int = 142003
```

---

#### Mistralys\X4\Database\Engines\EngineDef

Definition of an engine with performance characteristics.

##### Constants
```php
KEY_WARE_ID: string = 'wareID'
KEY_MACRO_ID: string = 'macroID'
KEY_LABEL: string = 'label'
KEY_SIZE: string = 'size'
KEY_DATA_SOURCE_ID: string = 'dataSourceID'
KEY_MAKER_RACE: string = 'makerRace'
KEY_MAKER_RACES: string = 'makerRaces'
KEY_MK: string = 'mk'
KEY_VARIANT_ID: string = 'variantID'
KEY_BOOST_DURATION: string = 'boostDuration'
KEY_BOOST_RECHARGE: string = 'boostRecharge'
KEY_BOOST_THRUST: string = 'boostThrust'
KEY_BOOST_ACCELERATION: string = 'boostAcceleration'
KEY_BOOST_ATTACK: string = 'boostAttack'
KEY_BOOST_RELEASE: string = 'boostRelease'
KEY_BOOST_COAST: string = 'boostCoast'
KEY_TRAVEL_CHARGE: string = 'travelCharge'
KEY_TRAVEL_THRUST: string = 'travelThrust'
KEY_TRAVEL_ATTACK: string = 'travelAttack'
KEY_TRAVEL_RELEASE: string = 'travelRelease'
KEY_THRUST_FORWARD: string = 'thrustForward'
KEY_THRUST_REVERSE: string = 'thrustReverse'
KEY_HULL_MAX: string = 'hullMax'
KEY_HULL_THRESHOLD: string = 'hullThreshold'
KEY_DECELERATION_CURVE: string = 'decelerationCurve'
```

##### Methods
```php
__construct(string $wareID, string $macroID, string $label, string $size, string $dataSourceID, string $makerRace, int $mk, VariantID $variantID, float $boostDuration, float $boostRecharge, float $boostThrust, float $boostAcceleration, float $boostAttack, float $boostRelease, float $boostCoast, float $travelCharge, float $travelThrust, float $travelAttack, float $travelRelease, float $thrustForward, float $thrustReverse, float $hullMax, float $hullThreshold, array $decelerationCurve): void
static fromArray(mixed $engineDef): EngineDef
getID(): string
getLabel(): string
getVariantID(): VariantID
getMacroID(): string
getSize(): string
getDataSourceID(): string
getMakerRace(): string
getMakerRaces(): array // Returns string[]
hasMultipleMakerRaces(): bool
getMk(): int
getBoostDuration(): float
getBoostRecharge(): float
getBoostThrust(): float
getBoostAcceleration(): float
getBoostAttack(): float
getBoostRelease(): float
getBoostCoast(): float
getTravelCharge(): float
getTravelThrust(): float
getTravelAttack(): float
getTravelRelease(): float
getThrustForward(): float
getThrustReverse(): float
getHullMax(): float  
getHullThreshold(): float
getDecelerationCurve(): array
hasDecelerationCurve(): bool
getWareID(): string
getWare(): WareDef
```

---

#### Mistralys\X4\Database\Engines\EngineDefs

Collection of all engine definitions.

##### Constants
```php
DATA_FILE: string = 'engines.json'
ERROR_ENGINE_NOT_FOUND: int = 142001
```

##### Methods
```php
static getInstance(): EngineDefs
__construct(): void
getDataFile(): JSONFile
find(string $idOrMacro): ?EngineDef
findByMacro(string $macro): ?EngineDef
getDefaultID(): string
findEngines(): EngineFinder
getByID(string $id): EngineDef
getAll(): array // Returns EngineDef[]
getDefault(): EngineDef
```

---

####Mistralys\X4\Database\Engines\EngineFinder

Finder for filtering engine collections.

##### Methods
```php
getCollection(): ItemCollectionInterface
selectSize(string $size): self
selectSizes(array $sizes): self
selectMakerRace(string $race): self
selectMk(int $mk): self
selectMinMk(int $minMk): self
selectMinThrust(float $minThrust): self
selectMaxThrust(float $maxThrust): self
selectMinBoostDuration(float $minDuration): self
selectMaxBoostRecharge(float $maxRecharge): self
selectMinBoostThrust(float $minMultiplier): self
selectMinTravelThrust(float $minTravel): self
selectMaxTravelCharge(float $maxCharge): self
selectMinHull(float $minHull): self
selectWithDecelerationCurve(): self
selectDataSource(string|DataSourceDef $dataSource): self
selectLabelSearch(string $searchTerm): self
getAll(): array // Returns EngineDef[]
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

### Weapons

#### Mistralys\X4\Database\Weapons\WeaponDef

Definition of a weapon with performance characteristics.

##### Constants
```php
KEY_WARE_ID: string = 'wareID'
KEY_MACRO_ID: string = 'macroID'
KEY_BULLET_CLASS: string = 'bulletClass'
KEY_LABEL: string = 'label'
KEY_SIZE: string = 'size'
KEY_DATA_SOURCE_ID: string = 'dataSourceID'
KEY_MAKER_RACE: string = 'makerRace'
KEY_MAKER_RACES: string = 'makerRaces'
KEY_MK: string = 'mk'
KEY_VARIANT_ID: string = 'variantID'
KEY_WEAPON_SYSTEM: string = 'weaponSystem'
KEY_WEAPON_CATEGORY: string = 'weaponCategory'
KEY_HEAT_OVERHEAT: string = 'heatOverheat'
KEY_HEAT_COOLDELAY: string = 'heatCooldelay'
KEY_HEAT_COOLRATE: string = 'heatCoolrate'
KEY_HEAT_REENABLE: string = 'heatReenable'
KEY_ROTATION_SPEED: string = 'rotationSpeed'
KEY_ROTATION_ACCELERATION: string = 'rotationAcceleration'
KEY_HULL_MAX: string = 'hullMax'
KEY_HULL_HITTABLE: string = 'hullHittable'
KEY_AMMO_VALUE: string = 'ammoValue'
KEY_AMMO_RELOAD: string = 'ammoReload'
KEY_BULLET_SPEED: string = 'bulletSpeed'
KEY_BULLET_LIFETIME: string = 'bulletLifetime'
KEY_BULLET_RANGE: string = 'bulletRange'
KEY_BULLET_AMOUNT: string = 'bulletAmount'
KEY_BULLET_BARRELAMOUNT: string = 'bulletBarrelamount'
KEY_BULLET_ICON: string = 'bulletIcon'
KEY_BULLET_TIMEDIFF: string = 'bulletTimediff'
KEY_BULLET_ANGLE: string = 'bulletAngle'
KEY_BULLET_MAXHITS: string = 'bulletMaxhits'
KEY_BULLET_RICOCHET: string = 'bulletRicochet'
KEY_BULLET_ATTACH: string = 'bulletAttach'
KEY_HEAT_PER_SHOT: string = 'heatPerShot'
KEY_RELOAD_RATE: string = 'reloadRate'
KEY_DAMAGE_VALUE: string = 'damageValue'
KEY_REPAIR_VALUE: string = 'repairValue'
```

##### Methods
```php
static fromArray(mixed $weaponDef): WeaponDef
getID(): string
getLabel(): string
getVariantID(): VariantID
getWareID(): string
getMacroID(): string
getBulletClass(): string
getSize(): string
getDataSourceID(): string
getMakerRace(): string
getMakerRaces(): array // Returns string[]
hasMultipleMakerRaces(): bool
getMk(): int
getWeaponSystem(): string
getWeaponCategory(): string
getHeatOverheat(): float
getHeatCooldelay(): float
getHeatCoolrate(): float
getHeatReenable(): float
getRotationSpeed(): float
getRotationAcceleration(): float
getHullMax(): float
getHullHittable(): int
isHullHittable(): bool
getAmmoValue(): float
getAmmoReload(): float
getBulletSpeed(): float
getBulletLifetime(): float
getBulletRange(): float
getBulletAmount(): int
getBulletBarrelamount(): int
getBulletIcon(): string
getBulletTimediff(): float
getBulletAngle(): float
getBulletMaxhits(): int
getBulletRicochet(): int
canRicochet(): bool
getBulletAttach(): int
canAttach(): bool
getHeatPerShot(): float
getReloadRate(): float
getDamageValue(): float
getRepairValue(): float
isRepairWeapon(): bool
getDPS(): float
getShotsUntilOverheat(): float
getTimeUntilOverheat(): float
getCooldownTime(): float
isTurret(): bool
isBeamWeapon(): bool
isMissileWeapon(): bool
isMiningWeapon(): bool
```

---

#### Mistralys\X4\Database\Weapons\WeaponDefs

Collection of all weapon definitions.

##### Constants
```php
DATA_FILE: string = 'weapons.json'
ERROR_WEAPON_NOT_FOUND: int = 143001
```

##### Methods
```php
static getInstance(): WeaponDefs
getDataFile(): JSONFile
find(string $idOrMacro): ?WeaponDef
findByMacro(string $macro): ?WeaponDef
findByBulletClass(string $bulletClass): ?WeaponDef
getByWeaponSystem(string $weaponSystem): array // Returns WeaponDef[]
getByCategory(string $category): array // Returns WeaponDef[]
getDefaultID(): string
findWeapons(): WeaponFinder
getByID(string $id): WeaponDef
getAll(): array // Returns WeaponDef[]
getDefault(): WeaponDef
```

---

#### Mistralys\X4\Database\Weapons\WeaponFinder

Finder for filtering weapon collections.

##### Methods
```php
getCollection(): ItemCollectionInterface
selectSize(string $size): self
selectSizes(array $sizes): self
selectMakerRace(string $race): self
selectWeaponSystem(string $system): self
selectWeaponCategory(string $category): self
selectMk(int $mk): self
selectMinMk(int $minMk): self
selectMinDamage(float $minDamage): self
selectMaxDamage(float $maxDamage): self
selectMinDPS(float $minDPS): self
selectMaxDPS(float $maxDPS): self
selectMinRange(float $minRange): self
selectMaxRange(float $maxRange): self
selectMinReloadRate(float $minReloadRate): self
selectMaxReloadRate(float $maxReloadRate): self
selectMinBulletSpeed(float $minBulletSpeed): self
selectMinRotationSpeed(float $minRotationSpeed): self
selectTurret(bool $isTurret = true): self
selectBeamWeapons(bool $isBeam = true): self
selectMissileWeapons(bool $isMissile = true): self
selectMiningWeapons(bool $isMining = true): self
selectRepairWeapons(bool $isRepair = true): self
selectDataSource(string|DataSourceDef $dataSource): self
sortByDPS(): self
sortByDamage(): self
sortByRange(): self
sortByReloadRate(): self
sortByLabel(): self
getAll(): array // Returns WeaponDef[]
```

---

#### Mistralys\X4\Database\Weapons\WeaponException

Exception class for weapon-related errors.

##### Constants
```php
ERROR_WEAPON_NOT_FOUND: int = 143001
ERROR_INVALID_WEAPON_SIZE: int = 143002
ERROR_INVALID_WEAPON_DATA: int = 143003
ERROR_INVALID_WEAPON_SYSTEM: int = 143004
ERROR_UNKNOWN_WEAPON_SYSTEM: int = 143005
```

---

### Weapon Systems

#### Mistralys\X4\Database\WeaponSystems\WeaponSystems

Singleton collection of all weapon system types. Provides centralized metadata for weapon system classification (turrets, missiles, torpedoes, etc.) with human-readable labels.

##### Methods
```php
static getInstance(): WeaponSystems
getCollectionName(): string // Returns 'Weapon Systems'
getCollectionDescription(): string
getDefaultID(): string // Returns 'weapon_standard'
getAll(): array // Returns WeaponSystem[]
getByID(string $id): ?WeaponSystem
idExists(string $id): bool
getIDs(): array // Returns string[]
isKnownSystem(string $systemID): bool
requireKnownSystem(string $systemID): void // Throws WeaponException if unknown
getTurretSystems(): array // Returns WeaponSystem[]
getMissileSystems(): array // Returns WeaponSystem[]
getStandardWeaponSystems(): array // Returns WeaponSystem[]
```

**Usage:**
```php
$systems = WeaponSystems::getInstance();
$shortRange = $systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
echo $shortRange->getLabel(); // "Short-Range Turret"

// Validate weapon system during extraction
$systems->requireKnownSystem('turret_shortrange'); // OK
$systems->requireKnownSystem('fake_system'); // Throws WeaponException
```

---

#### Mistralys\X4\Database\WeaponSystems\WeaponSystem

Represents a single weapon system type with metadata.

##### Constants
```php
KEY_LABEL: string = 'label'
KEY_DESCRIPTION: string = 'description'
```

##### Methods
```php
getID(): string
getVariantID(): VariantID
getLabel(): string // Human-readable label (e.g., 'Short-Range Turret')
getDescription(): string // Detailed description
isTurret(): bool
isMissile(): bool
isStandardWeapon(): bool
```

---

#### Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems

Type-safe constants for weapon system IDs.

##### Constants
```php
TURRET_SHORTRANGE: string = 'turret_shortrange'
TURRET_MIDRANGE: string = 'turret_midrange'
TURRET_LONGRANGE: string = 'turret_longrange'
WEAPON_STANDARD: string = 'weapon_standard'
WEAPON_MINING: string = 'weapon_mining'
MISSILE_DUMBFIRE: string = 'missile_dumbfire'
MISSILE_GUIDED: string = 'missile_guided'
TORPEDO: string = 'torpedo'
```

**Usage:**
```php
use Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems;
use Mistralys\X4\Database\Weapons\WeaponDefs;

$weapons = WeaponDefs::getInstance()->findWeapons()
    ->selectWeaponSystem(KnownWeaponSystems::TURRET_SHORTRANGE)
    ->selectMinDPS(1000.0)
    ->getAll();
```

---

### Ships

#### Mistralys\X4\Database\Ships\ShipDef

Ship definition.

##### Constants
```php
// Identity
KEY_WARE_ID: string = 'wareID'
KEY_LABEL: string = 'label'
KEY_SIZE: string = 'size'
KEY_BUILDER_FACTION_ID: string = 'builderFactionID'
KEY_BUILDER_FACTION_IDS: string = 'builderFactionIDs'
KEY_CLASS_ID: string = 'classID'
KEY_USED_BY: string = 'usedBy'
KEY_DATA_SOURCE_ID: string = 'dataSourceID'
KEY_VARIANT_ID: string = 'variantID'
KEY_VARIANTS: string = 'variants'

// Hull & Mass
KEY_HULL: string = 'hull'
KEY_MASS: string = 'mass'

// Drag Coefficients
KEY_DRAG_FORWARD: string = 'dragForward'
KEY_DRAG_REVERSE: string = 'dragReverse'
KEY_DRAG_HORIZONTAL: string = 'dragHorizontal'
KEY_DRAG_VERTICAL: string = 'dragVertical'
KEY_DRAG_PITCH: string = 'dragPitch'
KEY_DRAG_YAW: string = 'dragYaw'
KEY_DRAG_ROLL: string = 'dragRoll'

// Inertia Coefficients
KEY_INERTIA_PITCH: string = 'inertiaPitch'
KEY_INERTIA_YAW: string = 'inertiaYaw'
KEY_INERTIA_ROLL: string = 'inertiaRoll'

// Jerk Values (rate of acceleration change)
KEY_JERK_STRAFE: string = 'jerkStrafe'
KEY_JERK_ANGULAR: string = 'jerkAngular'
KEY_JERK_FORWARD_ACCEL: string = 'jerkForwardAccel'
KEY_JERK_FORWARD_DECEL: string = 'jerkForwardDecel'
KEY_JERK_FORWARD_RATIO: string = 'jerkForwardRatio'
KEY_JERK_BOOST_ACCEL: string = 'jerkBoostAccel'
KEY_JERK_BOOST_RATIO: string = 'jerkBoostRatio'
KEY_JERK_TRAVEL_ACCEL: string = 'jerkTravelAccel'
KEY_JERK_TRAVEL_DECEL: string = 'jerkTravelDecel'
KEY_JERK_TRAVEL_RATIO: string = 'jerkTravelRatio'

// Acceleration Factors (multipliers affecting acceleration scaling)
KEY_ACCFACTOR_FORWARD: string = 'accFactorForward'
KEY_ACCFACTOR_REVERSE: string = 'accFactorReverse'
KEY_ACCFACTOR_HORIZONTAL: string = 'accFactorHorizontal'
KEY_ACCFACTOR_VERTICAL: string = 'accFactorVertical'

// Storage
KEY_PEOPLE: string = 'people'
KEY_STORAGE_MISSILE: string = 'storageMissile'
KEY_CARGO_CAPACITY = 'cargoCapacity'
KEY_CARGO_TYPE = 'cargoType'
KEY_SLOTS: string = 'slots'
KEY_EQUIPMENT: string = 'equipment'
```

##### Methods
```php
__construct(string $id, string $label, VariantID $variantID, string $size, string $builderFactionID, string $classID, array $usedBy, string $dataSourceID, array $variants, int $hull, float $mass, float $dragForward, float $dragReverse, float $dragHorizontal, float $dragVertical, float $dragPitch, float $dragYaw, float $dragRoll, float $inertiaPitch, float $inertiaYaw, float $inertiaRoll, float $jerkStrafe, float $jerkAngular, float $jerkForwardAccel, float $jerkForwardDecel, float $jerkForwardRatio, float $jerkBoostAccel, float $jerkBoostRatio, float $jerkTravelAccel, float $jerkTravelDecel, float $jerkTravelRatio, float $accFactorForward, float $accFactorReverse, float $accFactorHorizontal, float $accFactorVertical, int $people, int $storageMissile, int $cargoCapacity, string $cargoType, array $slots, array $equipment): void
static fromArray(array $def): ShipDef
getID(): string
getLabel(): string
getSizeID(): string
getVariantID(): VariantID
hasVariants(): bool
getSize(): ShipSize
getBuilderFactionID(): string
getBuilderFaction(): FactionDef
getBuilderFactionIDs(): array // Returns string[]
getBuilderFactions(): array // Returns FactionDef[]
hasMultipleBuilderFactions(): bool
getClassID(): string
getClass(): ShipClass
getDataSourceID(): string
getDataSource(): DataSourceDef
getUsedBy(): array // Returns FactionDef[]
toArray(): array
getWareID(): string
getWare(): WareDef

// Physics - Hull & Mass
getHull(): int
getMass(): float

// Physics - Drag Coefficients
getDragForward(): float
getDragReverse(): float
getDragHorizontal(): float
getDragVertical(): float
getDragPitch(): float
getDragYaw(): float
getDragRoll(): float

// Physics - Inertia Coefficients
getInertiaPitch(): float
getInertiaYaw(): float
getInertiaRoll(): float

// Physics - Jerk Values
getJerkStrafe(): float
getJerkAngular(): float
getJerkForwardAccel(): float
getJerkForwardDecel(): float
getJerkForwardRatio(): float
getJerkBoostAccel(): float
getJerkBoostRatio(): float
getJerkTravelAccel(): float
getJerkTravelDecel(): float
getJerkTravelRatio(): float

// Physics - Acceleration Factors
getAccFactorForward(): float
getAccFactorReverse(): float
getAccFactorHorizontal(): float
getAccFactorVertical(): float

// Storage & People
getPeopleCapacity(): int
getMissileStorage(): int
getCargoCapacity(): int      // Cargo storage capacity in m³ (0 if no storage)
getCargoType(): string       // Cargo type: container, liquid, solid, or none

// Slot count methods
getSlotCount(string $typeID): int
countWeapons(): int
countShields(): int
countTurrets(): int
countDockingBays(): int
countCountermeasures(): int
countEngines(): int

// Equipment compatibility methods - return finder for compatible equipment
findEquipmentForSlot(string $slotTypeID): Equipment\ShipEquipmentFinder
getEngines(): Equipment\ShipEquipmentFinder
getShields(): Equipment\ShipEquipmentFinder
getWeapons(): Equipment\ShipEquipmentFinder
getTurrets(): Equipment\ShipEquipmentFinder
getCountermeasures(): Equipment\ShipEquipmentFinder
getDockingBays(): Equipment\ShipEquipmentFinder

// Weapon performance data methods - return WeaponDef[] with performance stats
getCompatibleWeapons(): array // Returns WeaponDef[] for compatible weapons
getCompatibleTurrets(): array // Returns WeaponDef[] for compatible turrets

// Equipment groups and compatibility checking
getEquipmentGroups(?string $type = null): array // Returns ShipSlotDefinition[]
canEquip(WareDef $ware): bool
getEquipment(): array // Returns raw equipment data

// Dock information
getDocks(): array // Map of dock sizes to counts
getDockCount(string $size): int
getTotalDockCount(): int
hasDocks(): bool
getDockSizes(): array // Array of size keys
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
find(string $id): ?ShipDef
getFactions(): array // Returns FactionDef[]
findShips(): ShipFinder
getDataSources(): array // Returns DataSourceDef[]
getByID(string $id): ShipDef
getAll(): array // Returns ShipDef[]
getDefault(): ShipDef
```

---

#### Mistralys\X4\Database\Ships\Equipment\ShipEquipmentFinder

Finder for filtering equipment compatible with a specific ship and slot type.  
Extends `BaseFinder`, implements `DataSourceSelectionInterface`.

**Usage:**
```php
$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');
$engines = $ship->getEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
    ->selectSize('l')
    ->getAll();
```

##### Methods
```php
__construct(ShipDef $ship, string $slotTypeID): void
getCollection(): ItemCollectionInterface // Returns WareDefs instance
selectSize(string $size): self // Filter by equipment size (s, m, l, xl)
selectTag(string $tag): self // Filter by ware tag
selectDataSource(string|DataSourceDef $source): self // Filter by data source
selectLabelSearch(string $searchTerm): self // Filter by label text (inherited)
getAll(): array // Returns WareDef[] matching all filters
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

### Shields

#### Mistralys\X4\Database\Shields\ShieldException

Exception for shield-related errors.

##### Constants
```php
ERROR_SHIELD_NOT_FOUND: int = 143001
ERROR_INVALID_SHIELD_SIZE: int = 143002
ERROR_INVALID_SHIELD_DATA: int = 143003
ERROR_INVALID_SHIELD_TYPE: int = 143004
```

---

#### Mistralys\X4\Database\Shields\ShieldDef

Definition of a shield with performance characteristics.

##### Constants
```php
KEY_WARE_ID: string = 'wareID'
KEY_MACRO_ID: string = 'macroID'
KEY_LABEL: string = 'label'
KEY_SIZE: string = 'size'
KEY_DATA_SOURCE_ID: string = 'dataSourceID'
KEY_MAKER_RACE: string = 'makerRace'
KEY_MAKER_RACES: string = 'makerRaces'
KEY_MK: string = 'mk'
KEY_VARIANT_ID: string = 'variantID'
KEY_SHIELD_TYPE: string = 'shieldType'
KEY_RECHARGE_MAX: string = 'rechargeMax'
KEY_RECHARGE_RATE: string = 'rechargeRate'
KEY_RECHARGE_DELAY: string = 'rechargeDelay'
KEY_HULL_MAX: string = 'hullMax'
KEY_HULL_THRESHOLD: string = 'hullThreshold'
KEY_HULL_INTEGRATED: string = 'hullIntegrated'
```

##### Methods
```php
__construct(string $wareID, string $macroID, string $label, string $size, string $dataSourceID, string $makerRace, int $mk, VariantID $variantID, string $shieldType, float $rechargeMax, float $rechargeRate, float $rechargeDelay, float $hullMax, float $hullThreshold, bool $hullIntegrated): void
static fromArray(mixed $shieldDef): ShieldDef
getID(): string
getLabel(): string
getVariantID(): VariantID
getMacroID(): string
getSize(): string
getDataSourceID(): string
getMakerRace(): string
getMakerRaces(): array // Returns string[]
hasMultipleMakerRaces(): bool
getMk(): int
getShieldType(): string
getRechargeMax(): float
getRechargeRate(): float
getRechargeDelay(): float
getCapacity(): float
getFullRechargeTime(): float
getHullMax(): float
getHullThreshold(): float
isHullIntegrated(): bool
hasHull(): bool
isStandard(): bool
isRacer(): bool
isCorvette(): bool
isMothership(): bool
isYacht(): bool
isExperimental(): bool
isVirtual(): bool
getWareID(): string
getWare(): WareDef
```

---

#### Mistralys\X4\Database\Shields\ShieldDefs

Collection of all shield definitions.

##### Constants
```php
DATA_FILE: string = 'shields.json'
ERROR_SHIELD_NOT_FOUND: int = 143001
```

##### Methods
```php
static getInstance(): ShieldDefs
__construct(): void
getDataFile(): JSONFile
find(string $idOrMacro): ?ShieldDef
findByMacro(string $macro): ?ShieldDef
getByType(string $type): array // Returns ShieldDef[]
getDefaultID(): string
findShields(): ShieldFinder
getByID(string $id): ShieldDef
getAll(): array // Returns ShieldDef[]
getDefault(): ShieldDef
```

---

#### Mistralys\X4\Database\Shields\ShieldFinder

Finder for filtering shield collections.

##### Methods
```php
getCollection(): ItemCollectionInterface
selectSize(string $size): self
selectSizes(array $sizes): self
selectMakerRace(string $race): self
selectMk(int $mk): self
selectMinMk(int $minMk): self
selectType(string $type): self
selectTypes(array $types): self
selectMinCapacity(float $minCapacity): self
selectMaxCapacity(float $maxCapacity): self
selectMinRechargeRate(float $minRate): self
selectMaxRechargeDelay(float $maxDelay): self
selectMinHull(float $minHull): self
selectWithHull(): self
selectIntegrated(): self
selectNonIntegrated(): self
selectDataSource(string|DataSourceDef $dataSource): self
selectLabelSearch(string $searchTerm): self
getAll(): array // Returns ShieldDef[]
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
KEY_BUILDER_FACTION_IDS: string = 'builderFactionIDs'
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
getBuilderFactionIDs(): array // Returns string[]
getBuilderFactions(): array // Returns FactionDef[]
hasMultipleBuilderFactions(): bool
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
