# Database Blueprints API Reference

> **Domain**: Blueprints, Blueprint Categories  
> **Last Updated**: February 15, 2026

[← Back to API Index](README.md)

---

## Overview

The Blueprints namespace contains all craftable item blueprints:

- **Blueprints**: Research and production licenses for ships, equipment, and wares
- **Blueprint Categories**: Organizational categories for blueprints

---

## Table of Contents

- [Blueprints](#blueprints)
- [Usage Patterns](#usage-patterns)

---

## Blueprints

### Mistralys\X4\Database\Blueprints\BlueprintException

Exception for blueprint-related errors.

---

### Mistralys\X4\Database\Blueprints\BlueprintDef

Abstract base class for blueprint definitions.

#### Constants
```php
KEY_WARE_ID: string = 'wareID'
KEY_LABEL: string = 'label'
KEY_CATEGORY_ID: string = 'categoryID'
KEY_VARIANT_ID: string = 'variantID'
```

#### Methods
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

**Usage:**
```php
$blueprint = BlueprintDefs::getInstance()->getByID('ship_arg_s_scout_01_a');
echo $blueprint->getLabel();      // "Argon Scout Mk1 Blueprint"
echo $blueprint->getCategoryID(); // "ships"
echo $blueprint->getTypeLabel();  // "Ship"
$ware = $blueprint->getWare();    // Get associated ware
```

---

### Mistralys\X4\Database\Blueprints\BlueprintDefs

Collection of all blueprints.

#### Methods
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

**Usage:**
```php
$blueprints = BlueprintDefs::getInstance();

// Get all blueprints
$allBlueprints = $blueprints->getAll();

// Check if blueprint exists
if ($blueprints->blueprintIDExists('ship_arg_m_frigate_01_a')) {
    $blueprint = $blueprints->getByID('ship_arg_m_frigate_01_a');
}

// Count blueprints
echo "Total blueprints: " . $blueprints->countBlueprints();
```

---

## Usage Patterns

### Finding Blueprints

```php
$blueprints = BlueprintDefs::getInstance();

// Get all blueprints
$all = $blueprints->getAll();

// Filter by category (if supported)
foreach ($all as $blueprint) {
    if ($blueprint->getCategoryID() === 'ships') {
        echo "Ship blueprint: " . $blueprint->getLabel();
    }
}
```

### Blueprint to Ware Cross-Reference

```php
// Get blueprint and its associated ware
$blueprint = BlueprintDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');
$ware = $blueprint->getWare();

echo "Blueprint: " . $blueprint->getLabel();
echo "Ware: " . $ware->getLabel();
echo "Ware Group: " . $ware->getGroupID();
echo "Builder Faction: " . $ware->getFactionIDs()[0];
```

### Checking Blueprint Availability

```php
$blueprintID = 'weapon_arg_m_plasmagun_01_mk1';

if (BlueprintDefs::getInstance()->blueprintIDExists($blueprintID)) {
    $blueprint = BlueprintDefs::getInstance()->getByID($blueprintID);
    echo "Blueprint available: " . $blueprint->getLabel();
} else {
    echo "Blueprint not found";
}
```

---

## Notes

- **Blueprints** represent craftable items in the game
- **Categories** organize blueprints by type (ships, equipment, modules, etc.)
- **Ware Association**: Every blueprint has a corresponding `WareDef`
- **Variant Support**: Blueprints use `VariantID` for different versions (mk1, mk2, etc.)
- Use `getWare()` to access full item data from a blueprint
