# Database Localization & Metadata API Reference

> **Domain**: Translations, Languages, SlotTypes, MacroIndex  
> **Last Updated**: February 15, 2026

[← Back to API Index](README.md)

---

## Overview

The Localization & Metadata namespace contains:

- **Translations**: Multi-language text translations for game UI
- **Languages**: Supported game languages (English, German, French, etc.)
- **SlotTypes**: Equipment slot type definitions (weapon, shield, engine, etc.)
- **MacroIndex**: XML macro file index for game data lookup

---

## Table of Contents

- [Translations](#translations)
- [Slot Types](#slot-types)
- [MacroIndex](#macroindex)

---

## Translations

### Mistralys\X4\Database\Translations\Language

Game language definition.

#### Methods
```php
__construct(int $id, string $locale): void
getID(): int
getLocale(): string
ts(string $code): string // Translates a translation code e.g. {1005,42}
t(int $pageID, int $textID): string // Translates a text ID
getTranslator(): TranslationDefs
```

**Usage:**
```php
$english = Languages::getInstance()->getEnglish();
echo $english->getID();     // 44
echo $english->getLocale(); // "en_EN"

// Translate text using code
$text = $english->ts('{1005,42}');

// Translate text using page and text IDs
$text = $english->t(1005, 42);
```

---

### Mistralys\X4\Database\Translations\Languages

Collection of available languages.

#### Constants
```php
LANGUAGE_ENGLISH: int = 44
LANGUAGE_GERMAN: int = 49
LANGUAGE_FRENCH: int = 33
LANGUAGE_SPANISH: int = 34
LANGUAGE_ITALIAN: int = 39
LANGUAGE_RUSSIAN: int = 7
LANGUAGE_COREAN: int = 82  // Korean
DEFAULT_LANGUAGE: int = 44
LANGUAGES: array
```

#### Methods
```php
static getInstance(): self
getDefaultID(): int
getEnglish(): Language
getGerman(): Language
getFrench(): Language
getSpanish(): Language
getItalian(): Language
getRussian(): Language
getKorean(): Language
getByID(int $id): Language
getAll(): array // Returns Language[]
getDefault(): Language
```

**Usage:**
```php
$languages = Languages::getInstance();

// Get specific language
$english = $languages->getEnglish();
$german = $languages->getGerman();

// Get by ID
$french = $languages->getByID(Languages::LANGUAGE_FRENCH);

// Get all languages
$allLanguages = $languages->getAll();
foreach ($allLanguages as $lang) {
    echo $lang->getLocale() . ": " . $lang->getID();
}
```

---

## Slot Types

### Mistralys\X4\Database\SlotTypes\SlotType

Represents a single equipment slot type (Weapon, Shield, etc.).

**Extends:** `CollectionItem`

#### Methods
```php
getLabel(): string
getPrimaryTag(): string
```

**Usage:**
```php
$weaponSlot = SlotTypes::getInstance()->getByID(KnownSlotTypes::WEAPON);
echo $weaponSlot->getLabel();      // "Weapon"
echo $weaponSlot->getPrimaryTag(); // "weapon"
```

---

### Mistralys\X4\Database\SlotTypes\SlotTypes

Collection of all known slot types.

**Extends:** `Collection<SlotType>`

#### Methods
```php
static getInstance(): SlotTypes
getByID(string $id): SlotType
getAll(): array // Returns SlotType[]
```

**Usage:**
```php
$slotTypes = SlotTypes::getInstance();

// Get all slot types
foreach ($slotTypes->getAll() as $slotType) {
    echo $slotType->getLabel() . ": " . $slotType->getPrimaryTag();
}

// Get specific slot type
$shieldSlot = $slotTypes->getByID(KnownSlotTypes::SHIELD);
```

---

### Mistralys\X4\Database\SlotTypes\KnownSlotTypes

Class constants for known slot type IDs.

#### Constants
```php
WEAPON: string = 'weapon'
SHIELD: string = 'shield'
TURRET: string = 'turret'
ENGINE: string = 'engine'
DOCKING_BAY: string = 'dockingbay'
COUNTERMEASURES: string = 'countermeasures'
```

**Usage:**
```php
use Mistralys\X4\Database\SlotTypes\KnownSlotTypes;

// Get weapon slot type
$weaponSlot = SlotTypes::getInstance()->getByID(KnownSlotTypes::WEAPON);

// Count weapon slots on a ship
$ship = ShipDefs::getInstance()->getByID('ship_arg_m_frigate_01_a');
$weaponCount = $ship->getSlotCount(KnownSlotTypes::WEAPON);
```

---

## MacroIndex

### Mistralys\X4\Database\MacroIndex\MacroFileDef

Macro file definition (tracks XML macro files in game data).

#### Methods
```php
// (Detailed method signatures in source)
```

**Usage:**
```php
// Get macro file from ware
$ware = WareDefs::getInstance()->getByID('ship_arg_s_scout_01_a');
$macro = $ware->getMacro();
// Returns MacroFileDef instance
```

---

### Mistralys\X4\Database\MacroIndex\MacroFileDefs

Collection of macro files.

#### Methods
```php
// (Detailed method signatures in source)
```

**Usage:**
```php
// MacroIndex is typically accessed through WareDefs
$wares = WareDefs::getInstance();
$macroIndex = $wares->getMacroIndex();
// Returns array<string, WareDef> mapping macro names to wares
```

---

## Usage Patterns

### Multi-Language Support

```php
$languages = Languages::getInstance();

// Display text in all languages
$translationCode = '{1005,42}';

foreach ($languages->getAll() as $language) {
    $translated = $language->ts($translationCode);
    echo $language->getLocale() . ": " . $translated;
}
```

### Slot Type Validation

```php
use Mistralys\X4\Database\SlotTypes\{SlotTypes, KnownSlotTypes};

// Check if slot type exists
$slotTypes = SlotTypes::getInstance();
$slotID = 'weapon';

if ($slotTypes->idExists($slotID)) {
    $slotType = $slotTypes->getByID($slotID);
    echo "Valid slot type: " . $slotType->getLabel();
}

// List all slot types
foreach ($slotTypes->getAll() as $slotType) {
    echo "- " . $slotType->getLabel();
}
```

### Equipment Slot Analysis

```php
use Mistralys\X4\Database\SlotTypes\KnownSlotTypes;

$ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');

// Count each slot type
$slotTypes = SlotTypes::getInstance();
foreach ($slotTypes->getAll() as $slotType) {
    $count = $ship->getSlotCount($slotType->getID());
    if ($count > 0) {
        echo "{$slotType->getLabel()}: {$count}";
    }
}

// Or use specific methods
echo "Weapons: " . $ship->countWeapons();
echo "Turrets: " . $ship->countTurrets();
echo "Shields: " . $ship->countShields();
```

### Macro Lookup

```php
// Find ware by macro name
$wares = WareDefs::getInstance();
$macroName = 'ship_arg_s_scout_01_a_macro';

$ware = $wares->findByMacro($macroName);
if ($ware) {
    echo "Found ware: " . $ware->getLabel();
    $macro = $ware->getMacro();
    // Access macro data
}

// Get full macro index
$macroIndex = $wares->getMacroIndex();
foreach ($macroIndex as $macroName => $ware) {
    echo "Macro: {$macroName} -> Ware: {$ware->getLabel()}";
}
```

### Language-Specific Translation

```php
// Get translation in specific language
$german = Languages::getInstance()->getGerman();
$shipName = $german->t(1001, 100); // Page 1001, Text 100

// Fallback to English if translation missing
$english = Languages::getInstance()->getEnglish();
$fallbackName = $english->t(1001, 100);

echo "German: " . $shipName;
echo "English: " . $fallbackName;
```

---

## Notes

- **Translation Codes**: Format is `{pageID,textID}` (e.g., `{1005,42}`)
- **Language IDs**: Integer IDs match X4 game language codes
- **Slot Types**: Used for equipment compatibility checking
- **MacroIndex**: Maps XML macro files to game wares for lookups
- **Primary Tag**: Slot type's main XML tag used in game data files
- **Translation Fallback**: Always provide English as fallback option
