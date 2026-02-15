# Database Game Data API Reference

> **Domain**: Factions, Wares, DataSources, WareGroups  
> **Last Updated**: February 15, 2026

[← Back to API Index](README.md)

---

## Overview

The Database Game Data namespace contains fundamental game world data:

- **Factions**: All game races/factions (Argon, Teladi, Xenon, etc.)
- **Wares**: All game items (ships, equipment, resources, modules)
- **DataSources**: DLC and expansion pack identification
- **WareGroups**: Organizational categories for wares

---

## Table of Contents

- [Factions](#factions)
- [Wares](#wares)
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
```

#### Methods
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

**Usage:**
```php
$ware = WareDefs::getInstance()->getByID('ship_arg_s_scout_01_a');
echo $ware->getLabel();        // "Argon Scout Mk1"
echo $ware->getGroupID();      // "ships"
print_r($ware->getTags());     // ["ship", "scout", "small"]
print_r($ware->getFactionIDs()); // ["argon"]
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
getAll(): array // Returns WareDef[]
```

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
