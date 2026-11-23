# Database Game Data API Reference

> **Domain**: Factions, Wares, Production Methods, DataSources, WareGroups  
> **Last Updated**: November 22, 2026

[← Back to API Index](README.md)

---

## Overview

The Database Game Data namespace contains fundamental game world data:

- **Factions**: All game races/factions (Argon, Teladi, Xenon, etc.)
- **Wares**: All game items (ships, equipment, resources, modules)
- **DataSources**: DLC and expansion pack identification
- **WareGroups**: Organizational categories for wares
- **Production Methods**: How wares are produced (e.g. `terran`, `recycling`)

---

## Table of Contents

- [Factions](#factions)
- [Wares](#wares)
- [Production Methods](#production-methods)
- [DataSources](#datasources)

---

## Factions

### Mistralys\X4\Database\Factions\FactionException

Exception for faction-related errors.

---

### Mistralys\X4\Database\Factions\FactionDef

Definition of a game faction.

#### Constants
```php
KEY_ID: string = 'id'
KEY_NAME: string = 'name'
KEY_DATA_SOURCE_ID: string = 'dataSourceID'
```

#### Methods
```php
__construct(string $raceID, string $label, string $dataSourceID): void
static fromArray(array $raceDef): self
getID(): string
getShortIDs(): array // Returns string[]
getLabel(): string
getDataSourceID(): string
isGeneric(): bool
```

**Usage:**
```php
$argon = FactionDefs::getInstance()->getByID('argon');
echo $argon->getLabel(); // "Argon Federation"
echo $argon->getDataSourceID(); // "vanilla"
```

---

### Mistralys\X4\Database\Factions\FactionDefs

Collection of all factions in the game.

#### Constants
```php
SHORT_ID_ATF: string = 'atf'
SHORT_ID_PIR: string = 'pir'
SHORT_ID_MAPPINGS: array
```

#### Methods
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

**Usage:**
```php
$factions = FactionDefs::getInstance();
$allFactions = $factions->getAll();

// Detect faction from macro name
$factionID = $factions->detectFactionByID('ship_arg_s_scout_01_a');
// Returns: "argon"
```

---

### Mistralys\X4\Database\Factions\KnownFactions

Utility class with constants and getter methods for all known factions.

#### Constants
```php
FACTION_ALLIANCE_WORD: string = 'alliance'
FACTION_ANTIGONE_REPUBLIC: string = 'antigone'
FACTION_ARGON_FEDERATION: string = 'argon'
FACTION_CIVILIAN: string = 'civilian'
FACTION_HOLY_ORDER_PONTIFEX: string = 'hop'
FACTION_MINISTRY_FINANCE: string = 'ministry'
FACTION_PARANID: string = 'paranid'
FACTION_SPLIT: string = 'split'
FACTION_TELADI: string = 'teladi'
FACTION_XENON: string = 'xenon'
FACTION_KHAAK: string = 'khaak'
FACTION_TERRAN_PROTECTORATE: string = 'terran'
FACTION_SCALEPLATE_PACT: string = 'scaleplate'
FACTION_BUCCANEERS: string = 'buccaneers'
FACTION_SEGARIS_PIONEERS: string = 'segaris'
FACTION_GENERIC: string = 'generic'
FACTION_UNKNOWN: string = 'unknown'
// ... (35+ faction constants)
```

#### Methods
```php
static getInstance(): KnownFactions
getAllianceOfTheWord(): FactionDef
getAntigoneRepublic(): FactionDef
getArgonFederation(): FactionDef
getCivilian(): FactionDef
getHolyOrderOfThePontifex(): FactionDef
getMinistryOfFinance(): FactionDef
getParanid(): FactionDef
getSplit(): FactionDef
getTeladi(): FactionDef
getXenon(): FactionDef
getKhaak(): FactionDef
getTerranProtectorate(): FactionDef
getScaleplatePact(): FactionDef
getBuccaneers(): FactionDef
getSegarisPioneers(): FactionDef
// ... (35+ getter methods)
```

**Usage:**
```php
$knownFactions = KnownFactions::getInstance();
$argon = $knownFactions->getArgonFederation();
$teladi = $knownFactions->getTeladi();
```

---

## Wares

### Mistralys\X4\Database\Wares\WareDef

Definition of a ware.

#### Constants
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
KEY_PRICE_MIN: string = 'priceMin'
KEY_PRICE_AVERAGE: string = 'priceAverage'
KEY_PRICE_MAX: string = 'priceMax'
KEY_PRODUCTIONS: string = 'productions'
KEY_LICENCE_ID: string = 'licenceID'
KEY_DESCRIPTION_ID: string = 'descriptionID'
KEY_ILLEGAL_FACTION_IDS: string = 'illegalFactionIDs'
KEY_BUY_RESTRICTED: string = 'buyRestricted'
KEY_VOLUME: string = 'volume'
KEY_TRANSPORT_TYPE_ID: string = 'transportTypeID'
```

#### Methods
```php
__construct(string $id, string $macroID, string $label, string $groupID, VariantID $variantID, array $tags, string $dataSourceID, string $size, array $factionIDs, array $component, WarePrice|array|null $price = null, array $productions = [], ?string $licenceID = null, ?string $descriptionID = null, array $illegalFactionIDs = [], bool $buyRestricted = false, ?int $volume = null, ?string $transportTypeID = null): void
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
getPrice(): ?WarePrice // Built on first access, then cached
hasPrice(): bool // Does not build the price object
getProductions(): array // WareProduction[], including DLC patch recipes; built on first access, then cached
getProduction(string $methodID): ?WareProduction // First recipe using the method
getProductionMethodIDs(): array // string[], one entry per recipe (NOT de-duplicated)
getProductionResourceIDs(): array // string[], sorted and unique
usesResource(string $wareID): bool
getLicenceID(): ?string // Licence key, defined per faction in libraries/factions.xml (no licence collection)
getDescriptionID(): ?string // Translation code {page,id}
getDescription(?Language $language = null): string // English default, falls back via Language::tsWithFallback(); empty string when absent
getVolume(): ?int // Cargo space of one unit
getTransportTypeID(): ?string // See WareTransportTypes
getIllegalFactionIDs(): array // string[]
isIllegalFor(string|FactionDef $faction): bool
getIllegalFactions(): array // FactionDef[]
hasBuyRestriction(): bool // Raw <restriction buy="1"> flag; meaning undocumented by the game
```

**Serialization:** `fromArray()` accepts records without the new keys. `toArray()` omits empty optional keys and writes `buyRestricted` only when true. The price is stored as the three flat keys `priceMin`, `priceAverage`, `priceMax`.

**Lazy hydration:** `WareDefs` loads every ware for all consumers, so `fromArray()` builds no `WarePrice` or `WareProduction`. Raw records are kept and the value objects are built on first access of `getPrice()`, `getProductions()` or `getProduction()`, then cached (same instances on later calls). `hasPrice()`, `getProductionMethodIDs()`, `getProductionResourceIDs()` and `usesResource()` read the raw records only. The constructor accepts either value objects or records.

**Data coverage:** The shipped `data/wares.json` carries price, production, licence, description, illegal factions, buy restriction, volume and transport data, extracted by `WaresExtractor` (see [xml-sources.md](../extraction-reference/xml-sources.md)). DLC ware patches are applied during extraction: `getFactionIDs()` includes owners added by DLC patches (196 wares gained 547 owners compared with the pre-patch data), and `getProductions()` includes DLC-added production methods. Across all wares there are 1695 productions on 1305 wares, with 4820 resource lines over 98 distinct resource wares.

**Developer note:** The package uses classmap autoloading. After adding new classes (such as the ware value classes), run `composer dump-autoload`; until then they fail with "class not found".

**Usage:**
```php
$ware = WareDefs::getInstance()->getByID('ship_arg_s_scout_01_a');
echo $ware->getLabel();        // "Argon Scout Mk1"
echo $ware->getGroupID();      // "ships"
print_r($ware->getTags());     // ["ship", "scout", "small"]
print_r($ware->getFactionIDs()); // ["argon"]
```

---

### Mistralys\X4\Database\Wares\WarePrice

Final, readonly reference price range of a ware (`<price min average max>`). Stored in ware records as the flat keys `priceMin`, `priceAverage`, `priceMax`.

#### Methods
```php
__construct(int $min, int $average, int $max)
static fromArray(array $record): WarePrice
toArray(): array
```

---

### Mistralys\X4\Database\Wares\WareProduction

Final, readonly production recipe of a ware for one production method.

#### Constants
```php
KEY_METHOD_ID: string = 'methodID'
KEY_DURATION: string = 'duration'
KEY_QUANTITY: string = 'quantity'
KEY_RESOURCES: string = 'resources'
KEY_EFFECTS: string = 'effects'
```

#### Properties (public readonly)
```php
string $methodID
float $duration // Seconds per cycle
int $quantity // Units produced per cycle
array $resources // array<string,int>: ware ID => amount per cycle
array $effects // array<string,float>: work, sunlight, efficiency => value
```

#### Methods
```php
__construct(string $methodID, float $duration, int $quantity, array $resources = [], array $effects = [])
static fromArray(array $record): WareProduction
toArray(): array
getMethod(): ?ProductionMethodDef // Resolved through ProductionMethodDefs; null if unknown
getResourceWares(): array // WareDef[], resolved via WareDefs::findByMacro(); unknown IDs skipped
getResourceCost(): ?int // Sum of amount x average price; null if a resource is unknown or unpriced; 0 without resources
getResourceCostPerUnit(): ?float // Cost / quantity; null if cost is null or quantity is 0
```

The cost helpers use the reference average price and count input cost only (no effects, no production time).

---

### Mistralys\X4\Database\Wares\WareTransportTypes

Constants class for the `transport` vocabulary of wares. Not identical to the `cargoType` vocabulary: `transport` is a single value with six non-cargo values.

#### Constants
```php
TRANSPORT_CONTAINER, TRANSPORT_SOLID, TRANSPORT_LIQUID, TRANSPORT_CONDENSATE // cargo types
TRANSPORT_INVENTORY, TRANSPORT_EQUIPMENT, TRANSPORT_SHIP, TRANSPORT_RESEARCH, TRANSPORT_SOFTWARE, TRANSPORT_PASSENGER // non-cargo
```

#### Methods
```php
static getIDs(): array // string[], all transport types
static getCargoIDs(): array // container, solid, liquid, condensate
static idExists(string $id): bool
static isCargoTypeID(string $id): bool
```

---

### Mistralys\X4\Database\Wares\WareDefs

Collection of all wares available in the game.

#### Methods
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

**Usage:**
```php
$wares = WareDefs::getInstance();

// Get all wares
$allWares = $wares->getAll();

// Find by macro
$ware = $wares->findByMacro('ship_arg_s_scout_01_a_macro');

// Get wares by tag
$shipWares = $wares->getByTag('ship');
```

---

### Mistralys\X4\Database\Wares\WareFinder

Specialized filtering utility to find wares based on various criteria.

#### Methods
```php
getCollection(): ItemCollectionInterface
selectTag(string $tagName): self
selectGroup(string|WareGroup $group): self
selectDataSource(string|DataSourceDef $dataSource): self
selectLabel(string $label): self
selectProducedBy(string|ProductionMethodDef $method): self      // any
selectUsingResource(string|WareDef $resource): self             // all
selectLicence(string $licenceID): self                          // any
selectIllegalFor(string|FactionDef $faction): self              // any
selectTransportType(string $transportTypeID): self              // any
getAll(): array // Returns WareDef[]
```

**Combination rules:** Single-valued attributes (group, production method, licence, illegal faction, transport type) combine as any, so repeated calls widen the match. Capability filters (tag, resource) combine as all, so repeated calls narrow it. Different filters combine as AND.

**Validation:**
- `selectProducedBy()`, `selectUsingResource()` and `selectIllegalFor()` resolve string IDs through `ProductionMethodDefs`, `WareDefs::getByID()` and `FactionDefs`. An unknown ID throws that collection's `RecordNotExistsException`.
- `selectTransportType()` is validated against `WareTransportTypes::idExists()` and throws `WareException` (`ERROR_UNKNOWN_TRANSPORT_TYPE`).
- `selectLicence()` does not validate the ID; an unknown licence gives an empty result.
- `selectUsingResource()` reads only the raw production records and builds no `WareProduction` objects.

**Usage:**
```php
// Find all ship wares from Argon faction
$argonShips = WareDefs::getInstance()->findWares()
    ->selectTag('ship')
    ->selectGroup('ships')
    ->selectDataSource('vanilla')
    ->getAll();

// Search by label
$scouts = WareDefs::getInstance()->findWares()
    ->selectLabel('Scout')
    ->getAll();

// Wares that consume energy cells in production
$consumers = WareDefs::getInstance()->findWares()
    ->selectUsingResource('energycells')
    ->getAll();
```

### Mistralys\X4\Database\Wares\WareException

Extends `X4Exception`. Thrown for an unknown ware transport type.

#### Constants
```php
ERROR_UNKNOWN_TRANSPORT_TYPE: int = 145001
```

---

### Mistralys\X4\Database\Wares\WareGroup

Ware group definition.

#### Methods
```php
__construct(string $id, string $label): void
getID(): string
getLabel(): string
getWares(): array // Returns WareDef[]
```

**Usage:**
```php
$group = WareGroups::getInstance()->getByID('ships');
echo $group->getLabel(); // "Ships"
$ships = $group->getWares(); // All ship wares
```

---

### Mistralys\X4\Database\Wares\WareGroups

Collection of all ware groups.

#### Constants
```php
GROUP_AGRICULTURAL: string = 'agricultural'
GROUP_COUNTERMEASURES: string = 'countermeasures'
GROUP_CRAFTING: string = 'crafting'
GROUP_CONTAINERS: string = 'containers'
GROUP_DEFENCE: string = 'defence'
GROUP_DRONE: string = 'drone'
GROUP_EFFECTS: string = 'effects'
GROUP_ENGINES: string = 'engines'
GROUP_ENERGY: string = 'energy'
GROUP_FOOD: string = 'food'
GROUP_HIGHTECH: string = 'hightech'
GROUP_ILLEGAL: string = 'illegal'
GROUP_LUXURIES: string = 'luxuries'
GROUP_MEDICAL: string = 'medical'
GROUP_MINERALS: string = 'minerals'
GROUP_MODULES: string = 'modules'
GROUP_SHIPS: string = 'ships'
GROUP_SHIELDS: string = 'shields'
GROUP_SOFTWARE: string = 'software'
GROUP_TURRETS: string = 'turrets'
GROUP_WEAPONS: string = 'weapons'
// ... (35+ group constants)
```

#### Methods
```php
static getInstance(): WareGroups
getDefaultID(): string
getByID(string $id): WareGroup
getAll(): array // Returns WareGroup[]
getDefault(): WareGroup
```

**Usage:**
```php
$groups = WareGroups::getInstance();
$shipGroup = $groups->getByID(WareGroups::GROUP_SHIPS);
$allGroups = $groups->getAll();
```

---

### Mistralys\X4\Database\Wares\WarePatch

Immutable value object for one `<add>` operation of a DLC `libraries/wares.xml` diff file. Final class.

#### Properties (public readonly)
```php
string $dataFolderID // e.g. 'ego_dlc_split'
string $selector // The 'sel' attribute, e.g. "/wares/ware[@id='energycells']"
?string $targetWareID // Null unless the selector targets a single ware
ElementExtended $element // The whole <add> element; its children are the content to add
```

#### Methods
```php
isWareTargeted(): bool
```

---

### Mistralys\X4\Database\Wares\WaresPatchReader

Reads the DLC data folders' `libraries/wares.xml` diff files (in `DataFolders` order, then document order). Only `<add>` operations with the selectors `/wares`, `/wares/production` and `/wares/ware[@id='X']` are supported; `replace`/`remove` operations and other selectors are skipped with a `WARNING`.

#### Constants
```php
SELECTOR_WARES: string = '/wares'
SELECTOR_PRODUCTION: string = '/wares/production'
```

#### Methods
```php
__construct(DataFolders $dataFolders)
getWarePatches(): array // WarePatch[], only ware-targeted patches
getPatchesForSelector(string $selector): array // WarePatch[]
getAllPatches(): array // WarePatch[], cached per instance
readFile(FileInfo $file, string $dataFolderID): array // WarePatch[] of a single file
getWarnings(): array // string[] collected warnings
```

---

### Mistralys\X4\Database\Wares\WaresExtractor

Extractor that builds `data/wares.json` from `libraries/wares.xml`. It is a build-time class, used by `DatabaseBuilder`; it is not part of the runtime query API.

> **Deprecated alias**: the class was originally declared in the misspelled namespace `Mistral\X4\Database\Wares`. The old name still resolves through the shim `src/X4/Database/Wares/Legacy/WaresExtractor.php`, a subclass of the new class. Use the `Mistralys\` name in new code.

---

## Production Methods

### Mistralys\X4\Database\ProductionMethods\ProductionMethodDef

A ware production method (e.g. `terran`, `recycling`). Gives a production method ID its label, tags and default races.

#### Constants
```php
KEY_ID: string = 'id'
KEY_LABEL: string = 'label'
KEY_TAGS: string = 'tags'
KEY_DEFAULT_RACE_IDS: string = 'defaultRaceIDs'
TAG_NO_PLAYER_BUILD: string = 'noplayerbuild'
```

#### Methods
```php
__construct(string $id, string $label, array $tags = [], array $defaultRaceIDs = [])
static fromArray(array $record): ProductionMethodDef
toArray(): array
getID(): string
getLabel(): string
getTags(): array // string[]
hasTag(string $tag): bool
isPlayerBuildable(): bool // false exactly when the 'noplayerbuild' tag is present
getDefaultRaceIDs(): array // string[] faction IDs
```

---

### Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs

Collection of all production methods, read from `data/production-methods.json` (record keys: `id`, `label`, `tags`, `defaultRaceIDs`). Extends `BaseStringPrimaryCollection`.

#### Methods
```php
static getInstance(): ProductionMethodDefs
static getStorageFile(): JSONFile
getDataFile(): JSONFile
getDefaultID(): string
getByID(string $id): ProductionMethodDef
getAll(): array // Returns ProductionMethodDef[]
getDefault(): ProductionMethodDef
```

> **Note**: `getDefault()` returns the alphabetically first method. It is not a semantic default; do not rely on it.

---

## DataSources

### Mistralys\X4\Database\DataSources\DataSourceDef

Identifies a source data folder in the game (base game or DLC).

#### Constants
```php
KEY_ID: string = 'id'
KEY_LABEL: string = 'label'
KEY_IS_EXTENSION: string = 'isExtension'
```

#### Methods
```php
__construct(string $id, string $label, bool $isExtension): void
static toArray(DataFolder $dataFolder): array
getID(): string
getLabel(): string
isExtension(): bool
static fromArray(array $data): self
```

**Usage:**
```php
$dataSource = DataSourceDefs::getInstance()->getByID('vanilla');
echo $dataSource->getLabel(); // "Base Game"
echo $dataSource->isExtension(); // false

$dlc = DataSourceDefs::getInstance()->getByID('ego_dlc_terran');
echo $dlc->getLabel(); // "Cradle of Humanity"
echo $dlc->isExtension(); // true
```

---

### Mistralys\X4\Database\DataSources\DataSourceDefs

Collection tracking data sources.

#### Methods
```php
__construct(): void
static getInstance(): DataSourceDefs
getDefaultID(): string
getDataFile(): JSONFile
getByID(string $id): DataSourceDef
getDefault(): DataSourceDef
getAll(): array // Returns DataSourceDef[]
```

**Usage:**
```php
$sources = DataSourceDefs::getInstance();
$allSources = $sources->getAll();

// Filter items from specific DLC
$ships = ShipDefs::getInstance()->findShips()
    ->selectDataSource('ego_dlc_terran')
    ->getAll();
```

---

## Usage Patterns

### Finding Faction Ships

```php
$argonShips = ShipDefs::getInstance()->findShips()
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->getAll();

foreach ($argonShips as $ship) {
    echo $ship->getLabel();
    echo ' (Built by: ' . $ship->getBuilderFaction()->getLabel() . ')';
}
```

### Filtering Wares by Tag

```php
// Find all weapons from base game
$weapons = WareDefs::getInstance()->findWares()
    ->selectTag('weapon')
    ->selectDataSource('vanilla')
    ->getAll();

// Find all shield equipment
$shields = WareDefs::getInstance()->findWares()
    ->selectTag('shield')
    ->getAll();
```

### Working with WareGroups

```php
$groups = WareGroups::getInstance();

// Get all groups
foreach ($groups->getAll() as $group) {
    echo $group->getLabel() . ': ' . count($group->getWares()) . ' wares';
}

// Get specific group
$shipGroup = $groups->getByID(WareGroups::GROUP_SHIPS);
$allShips = $shipGroup->getWares();
```

### Detecting Factions from Macros

```php
$macroID = 'ship_arg_s_scout_01_a';
$factionID = FactionDefs::getInstance()->detectFactionByID($macroID);
// Returns: "argon"

if ($factionID) {
    $faction = FactionDefs::getInstance()->getByID($factionID);
    echo "Ship belongs to: " . $faction->getLabel();
}
```

### Using KnownFactions for Type Safety

```php
use Mistralys\X4\Database\Factions\KnownFactions;

// Type-safe faction access
$argon = FactionDefs::getInstance()->getByID(KnownFactions::FACTION_ARGON_FEDERATION);
$teladi = FactionDefs::getInstance()->getByID(KnownFactions::FACTION_TELADI);

// Or use convenience methods
$knownFactions = KnownFactions::getInstance();
$argon = $knownFactions->getArgonFederation();
$teladi = $knownFactions->getTeladi();
```

---

## Notes

- **Factions** are detected from macro/component IDs automatically
- **Wares** include ships, equipment, resources, modules, and more
- **DataSources** distinguish base game from DLC content
- **Tags** provide cross-cutting categorization (e.g., "ship", "weapon", "shield")
- **WareGroups** provide primary categorization of wares
- All finders support **fluent interface** (methods return `self`)
- Use `KnownFactions` constants for type-safe faction references
