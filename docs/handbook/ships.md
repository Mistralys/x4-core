# Ship Data API Reference

> **Audience:** Library users  
> **Version:** 1.0  
> **Last Updated:** February 9, 2026

## Table of Contents

- [Quick Start](#quick-start)
- [Finding Ships](#finding-ships)
- [Ship Properties](#ship-properties)
- [Equipment Slots](#equipment-slots)
- [Equipment Compatibility](#equipment-compatibility)
- [Weapon Performance Data](#weapon-performance-data)

**See also:** [Quick Reference Appendix](ships-reference.md)

---

## Quick Start

### Get All Ships

```php
use Mistralys\X4\Database\Ships\ShipDefs;

$ships = ShipDefs::getInstance()->getAll(); // Returns ShipDef[]
```

### Get Specific Ship

```php
use Mistralys\X4\Database\Ships\KnownShips;

$ship = ShipDefs::getInstance()->getByID(KnownShips::SHIP_CERBERUS_SENTINEL);
echo $ship->getLabel(); // "Cerberus Sentinel"
```

### Find Ships by Criteria

```php
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\Ships\ShipClasses;

$argonFrigates = ShipDefs::getInstance()
    ->findShips()
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->selectClass(ShipClasses::CLASS_FRIGATE)
    ->getAll();
```

---

## Finding Ships

### Basic Access

| Method | Description | Returns |
|--------|-------------|---------|
| `ShipDefs::getInstance()` | Get singleton collection | `ShipDefs` |
| `getAll()` | Get all ships | `ShipDef[]` |
| `getByID(string $id)` | Get ship by ID | `ShipDef` |
| `findShips()` | Create finder for filtering | `ShipFinder` |

### Filter by Builder Faction

```php
use Mistralys\X4\Database\Factions\KnownFactions;

$argonShips = ShipDefs::getInstance()
    ->findShips()
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->getAll();
```

**Accepts:** Faction ID string or `FactionDef` object

### Filter by Ship Class

```php
use Mistralys\X4\Database\Ships\ShipClasses;

$battleships = ShipDefs::getInstance()
    ->findShips()
    ->selectClass(ShipClasses::CLASS_BATTLESHIP)
    ->getAll();
```

**Accepts:** Class ID string or `ShipClass` object  
**Available Classes:** See [Ship Classes Reference](ships-reference.md#ship-classes)

### Filter by Size

```php
use Mistralys\X4\Database\Ships\ShipSizes;

$mediumShips = ShipDefs::getInstance()
    ->findShips()
    ->selectSize(ShipSizes::SIZE_M)
    ->getAll();
```

**Accepts:** Size ID string (`'xs'`, `'s'`, `'m'`, `'l'`, `'xl'`) or `ShipSize` object

### Filter by Data Source (DLC)

```php
use Mistralys\X4\Database\DataSources\KnownDataSources;

$vanillaShips = ShipDefs::getInstance()
    ->findShips()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
    ->getAll();
```

**Accepts:** Data source ID string or `DataSourceDef` object

### Filter by Label (Search)

```php
$cerberusVariants = ShipDefs::getInstance()
    ->findShips()
    ->selectLabelSearch('Cerberus')
    ->getAll();
```

**Note:** Case-insensitive substring match

### Chain Multiple Filters

```php
// Find Argon medium frigates from vanilla game
$ships = ShipDefs::getInstance()
    ->findShips()
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->selectClass(ShipClasses::CLASS_FRIGATE)
    ->selectSize(ShipSizes::SIZE_M)
    ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
    ->getAll();
```

**Filter Logic:** Filters are cumulative (AND logic). Multiple calls to the same filter type add alternatives (OR logic within that filter).

---

## Ship Properties

### Identification & Classification

| Method | Description | Returns |
|--------|-------------|---------|
| `getID()` | Ship ID | `string` |
| `getLabel()` | Display name | `string` |
| `getVariantID()` | Variant identifier | `VariantID` |
| `hasVariants()` | Check if other variants exist | `bool` |
| `getSizeID()` | Size code | `string` |
| `getSize()` | Size object | `ShipSize` |
| `getClassID()` | Class code | `string` |
| `getClass()` | Class object | `ShipClass` |

```php
$ship = ShipDefs::getInstance()->getByID(KnownShips::SHIP_CERBERUS_SENTINEL);

echo $ship->getLabel();        // "Cerberus Sentinel"
echo $ship->getSizeID();       // "m"
echo $ship->getSize()->getLabel(); // "Medium"
echo $ship->getClassID();      // "frigate"
echo $ship->getClass()->getLabel(); // "Frigate"
```

### Faction & Data Source

| Method | Description | Returns |
|--------|-------------|---------|
| `getBuilderFactionID()` | Faction ID that builds this ship | `string` |
| `getBuilderFaction()` | Faction object | `FactionDef` |
| `getUsedBy()` | Factions that use this ship | `FactionDef[]` |
| `getDataSourceID()` | Data source ID (DLC/vanilla) | `string` |
| `getDataSource()` | Data source object | `DataSourceDef` |

```php
$builderFaction = $ship->getBuilderFaction();
echo $builderFaction->getLabel(); // "Argon Federation"

foreach ($ship->getUsedBy() as $faction) {
    echo $faction->getLabel();
}
```

### Physical & Performance Stats

| Method | Description | Returns |
|--------|-------------|---------|
| `getHull()` | Hull strength | `int` |
| `getMass()` | Physics mass | `float` |
| `getDragForward()` | Forward drag coefficient | `float` |
| `getInertiaPitch()` | Pitch inertia coefficient | `float` |
| `getPeopleCapacity()` | Crew capacity | `int` |
| `getMissileStorage()` | Missile storage capacity | `int` |

```php
echo "Hull: " . $ship->getHull();
echo "Crew: " . $ship->getPeopleCapacity();
echo "Missiles: " . $ship->getMissileStorage();
```

### Ware Cross-Reference

```php
$ware = $ship->getWare();      // WareDef object
$wareID = $ship->getWareID();  // Same as getID()
```

**Note:** Ships implement `CollectionItemInterface`. The ware ID is identical to the ship ID.

### Working with Variants

Some ships have multiple variants with the same name but different equipment/stats.

```php
$ship = ShipDefs::getInstance()->getByID('ship_arg_s_heavyfighter_01_a');

$variantID = $ship->getVariantID();
echo $variantID->getID();          // "01-a"
echo $variantID->getNumber();      // 1
echo $variantID->getNumberString(); // "01"
echo $variantID->getQualifier();   // "a"

if ($ship->hasVariants()) {
    echo "Other variants exist";
}
```

---

## Equipment Slots

### Counting Slots

| Method | Description | Returns |
|--------|-------------|---------|
| `countWeapons()` | Number of weapon slots | `int` |
| `countShields()` | Number of shield slots | `int` |
| `countTurrets()` | Number of turret slots | `int` |
| `countEngines()` | Number of engine slots | `int` |
| `countDockingBays()` | Number of docking bay slots | `int` |
| `countCountermeasures()` | Number of countermeasure launchers | `int` |
| `getSlotCount(string $typeID)` | Generic slot count by type | `int` |

```php
$ship = ShipDefs::getInstance()->getByID(KnownShips::SHIP_CERBERUS_SENTINEL);

echo "Weapons: " . $ship->countWeapons();
echo "Turrets: " . $ship->countTurrets();
echo "Shields: " . $ship->countShields();

// Generic access
use Mistralys\X4\Database\SlotTypes\KnownSlotTypes;
echo $ship->getSlotCount(KnownSlotTypes::ENGINE);
```

### Docking Bays

| Method | Description | Returns |
|--------|-------------|---------|
| `getDocks()` | All docks grouped by size | `array<string,int>` |
| `getDockCount(string $size)` | Count for specific size | `int` |
| `getTotalDockCount()` | Total dock count (all sizes) | `int` |
| `hasDocks()` | Check if ship has docking bays | `bool` |
| `getDockSizes()` | Available dock sizes | `string[]` |

```php
$docks = $ship->getDocks();
// ['s' => 8, 'm' => 4]

echo $ship->getDockCount('s');     // 8
echo $ship->getTotalDockCount();   // 12
echo $ship->hasDocks();            // true

$sizes = $ship->getDockSizes();    // ['s', 'm']
```

### Detailed Slot Information

```php
// Get all slot definitions with sizes and tags
$slots = $ship->getEquipmentGroups(); // ShipSlotDefinition[]

// Filter by equipment type
$engineSlots = $ship->getEquipmentGroups('engines');
$shieldSlots = $ship->getEquipmentGroups('shields');
$weaponSlots = $ship->getEquipmentGroups('weapons');

foreach ($engineSlots as $slot) {
    echo $slot->getCount();    // Number of slots
    echo $slot->getSize();     // Size requirement (s, m, l, xl)
    echo $slot->getTags();     // Compatibility tags
}
```

---

## Equipment Compatibility

### Overview

Get equipment (engines, shields, weapons, turrets, etc.) that can be installed on a specific ship, with optional filtering by size, faction, DLC, and more.

**Returns:** `ShipEquipmentFinder` which provides a fluent interface for filtering equipment. Call `getAll()` to get `WareDef[]` results.

### Getting Equipment Finders

| Method | Description | Returns |
|--------|-------------|---------|
| `getEngines()` | Compatible engines | `ShipEquipmentFinder` |
| `getShields()` | Compatible shields | `ShipEquipmentFinder` |
| `getWeapons()` | Compatible weapons | `ShipEquipmentFinder` |
| `getTurrets()` | Compatible turrets | `ShipEquipmentFinder` |
| `getCountermeasures()` | Compatible countermeasures | `ShipEquipmentFinder` |
| `getDockingBays()` | Compatible docking bays | `ShipEquipmentFinder` |
| `findEquipmentForSlot(string $slotTypeID)` | Generic slot finder | `ShipEquipmentFinder` |

```php
$ship = ShipDefs::getInstance()->getByID(KnownShips::SHIP_CERBERUS_SENTINEL);

// Get all compatible engines (no filtering)
$allEngines = $ship->getEngines()->getAll(); // WareDef[]

// Get all compatible shields
$allShields = $ship->getShields()->getAll(); // WareDef[]
```

### Filtering Equipment

#### Filter by Data Source

```php
use Mistralys\X4\Database\DataSources\KnownDataSources;

$vanillaEngines = $ship->getEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
    ->getAll();
```

#### Filter by Size

```php
$largeEngines = $ship->getEngines()
    ->selectSize('l')
    ->getAll();
```

#### Filter by Tag

```php
$militaryShields = $ship->getShields()
    ->selectTag('military')
    ->getAll();
```

#### Filter by Label Search

```php
$argonWeapons = $ship->getWeapons()
    ->selectLabelSearch('Argon')
    ->getAll();
```

#### Chain Multiple Filters

```php
$filteredEngines = $ship->getEngines()
    ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
    ->selectSize('l')
    ->selectTag('military')
    ->selectLabelSearch('boost')
    ->getAll();
```

### Check Equipment Compatibility

```php
use Mistralys\X4\Database\Wares\WareDefs;

$engine = WareDefs::getInstance()->getByID('engine_arg_m_allround_01_mk3');

if ($ship->canEquip($engine)) {
    echo "Compatible!";
} else {
    echo "Incompatible.";
}
```

**Checks:** Slot existence, size matching, tag compatibility

**Note:** `canEquip()` checks if at least one slot is compatible. It doesn't track slot availability.

### Example: Building a Ship Loadout UI

```php
$shipID = $_GET['ship_id'];
$ship = ShipDefs::getInstance()->getByID($shipID);

// Get available equipment for dropdowns
$engines = $ship->getEngines()
    ->selectDataSource($selectedDLC)
    ->getAll();

$shields = $ship->getShields()
    ->selectSize($requiredSize)
    ->getAll();

$weapons = $ship->getWeapons()
    ->selectLabelSearch($searchTerm)
    ->getAll();

// Render UI
foreach ($engines as $engine) {
    echo sprintf('<option value="%s">%s</option>', 
        $engine->getID(), 
        $engine->getLabel()
    );
}
```

---

## Weapon Performance Data

### Overview

The equipment finders return `WareDef[]` objects with basic ware information (label, price, faction). For weapons and turrets, you can access detailed performance statistics using dedicated methods.

**Key Difference:**
- `getWeapons()` / `getTurrets()` → Returns `WareDef[]` (basic ware info)
- `getCompatibleWeapons()` / `getCompatibleTurrets()` → Returns `WeaponDef[]` (performance stats)

### Getting Weapon Definitions

| Method | Description | Returns |
|--------|-------------|---------|
| `getCompatibleWeapons()` | Weapons with performance data | `WeaponDef[]` |
| `getCompatibleTurrets()` | Turrets with performance data | `WeaponDef[]` |

```php
$ship = ShipDefs::getInstance()->getByID(KnownShips::SHIP_CERBERUS_SENTINEL);

// Get basic ware info
$weaponWares = $ship->getWeapons()->getAll(); // WareDef[]

// Get performance data
$weaponDefs = $ship->getCompatibleWeapons(); // WeaponDef[]

// Both return the same ware IDs
assert(count($weaponWares) === count($weaponDefs));
```

### Weapon Performance Properties

```php
foreach ($weaponDefs as $weapon) {
    // Basic info (same as WareDef)
    $weapon->getLabel();          // "Argon Pulse Mk3"
    $weapon->getWareID();         // Links to ware system
    $weapon->getSize();           // "m"
    $weapon->getMk();             // Mark level: 1, 2, 3
    
    // Performance stats (only in WeaponDef)
    $weapon->getDPS();            // Damage per second
    $weapon->getDamageValue();    // Damage per shot
    $weapon->getReloadRate();     // Reload time (seconds)
    $weapon->getBulletRange();    // Range (meters)
    $weapon->getBulletSpeed();    // Projectile speed
    
    // Classification
    $weapon->getWeaponSystem();   // "weapon_standard", "turret_shortrange", etc.
    $weapon->getWeaponCategory(); // Category identifier
    
    // Heat management
    $weapon->getHeatPerShot();
    $weapon->getShotsUntilOverheat();
    $weapon->getTimeUntilOverheat(); // seconds
    $weapon->getCooldownTime();      // seconds
    
    // Capabilities
    $weapon->isTurret();          // bool
    $weapon->isBeamWeapon();      // bool
    $weapon->isMissileWeapon();   // bool
    $weapon->isMiningWeapon();    // bool
    $weapon->isRepairWeapon();    // bool
    
    // Turret-specific
    $weapon->getRotationSpeed();  // rad/s (turrets only)
    $weapon->getRotationAcceleration();
}
```

### Example: Comparing Weapon Options

```php
$ship = ShipDefs::getInstance()->getByID(KnownShips::SHIP_ASGARD);

// Get all compatible weapons with performance data
$weapons = $ship->getCompatibleWeapons();

// Sort by DPS to find best weapons
usort($weapons, fn($a, $b) => $b->getDPS() <=> $a->getDPS());

// Display top 5 weapons
foreach (array_slice($weapons, 0, 5) as $weapon) {
    echo sprintf(
        "%s - DPS: %.2f, Range: %.0fm, Reload: %.2fs\n",
        $weapon->getLabel(),
        $weapon->getDPS(),
        $weapon->getBulletRange(),
        $weapon->getReloadRate()
    );
}
```

---

## Additional Resources

- **Source Code:** `src/X4/Database/Ships/`
- **Test Examples:** `tests/X4Tests/Suites/Database/Ships/`
- **Quick Reference:** [ships-reference.md](ships-reference.md)
