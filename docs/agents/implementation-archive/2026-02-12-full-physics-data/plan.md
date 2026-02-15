# X4 Core Data Request: Full Ship Physics Data

> **Date:** February 12, 2026  
> **From:** Cargo Sizes Mod / Physics Tuning GUI  
> **To:** X4 Core library agent  
> **Purpose:** Request to extend `ShipDef` / `ships.json` with complete physics data

---

## Summary

The Cargo Sizes Mod's Physics Tuning GUI needs full per-ship physics data (drag, inertia, jerk) to compute absolute performance metrics and class-wide impact ranges. Currently `ships.json` only stores `dragForward` and `inertiaPitch` — we need the remaining 18 fields.

---

## What Currently Exists in `ShipDef`

The `ShipsExtractor::extractStats()` method reads 6 values from game XML:

```php
'hull'          => (int)   resolvePropertyAttribute($dom, $parentDom, 'hull', 'max', 0),
'mass'          => (float) resolvePropertyAttribute($dom, $parentDom, 'physics', 'mass', 0),
'dragForward'   => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'forward', 0),
'inertiaPitch'  => (float) resolvePropertyAttribute($dom, $parentDom, 'inertia', 'pitch', 0),
'people'        => (int)   resolvePropertyAttribute($dom, $parentDom, 'people', 'capacity', 0),
'storageMissile'=> (int)   resolvePropertyAttribute($dom, $parentDom, 'storage', 'missile', 0),
```

---

## What We Need Added

### Drag (6 missing components)

All come from `<drag>` element attributes in the ship macro XML, same element that `dragForward` already reads from.

| Field Name | XML Path | Type | Description |
|---|---|---|---|
| `dragReverse` | `drag/@reverse` | `float` | Reverse drag coefficient |
| `dragHorizontal` | `drag/@horizontal` | `float` | Lateral (strafe) drag |
| `dragVertical` | `drag/@vertical` | `float` | Vertical drag |
| `dragPitch` | `drag/@pitch` | `float` | Rotational drag around pitch axis |
| `dragYaw` | `drag/@yaw` | `float` | Rotational drag around yaw axis |
| `dragRoll` | `drag/@roll` | `float` | Rotational drag around roll axis |

**Extraction pattern** (same as existing `dragForward`):
```php
'dragReverse'    => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'reverse', 0),
'dragHorizontal' => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'horizontal', 0),
'dragVertical'   => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'vertical', 0),
'dragPitch'      => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'pitch', 0),
'dragYaw'        => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'yaw', 0),
'dragRoll'       => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'roll', 0),
```

### Inertia (2 missing components)

All come from `<inertia>` element attributes, same element that `inertiaPitch` already reads from.

| Field Name | XML Path | Type | Description |
|---|---|---|---|
| `inertiaYaw` | `inertia/@yaw` | `float` | Rotational inertia around yaw axis |
| `inertiaRoll` | `inertia/@roll` | `float` | Rotational inertia around roll axis |

**Extraction pattern:**
```php
'inertiaYaw'  => (float) resolvePropertyAttribute($dom, $parentDom, 'inertia', 'yaw', 0),
'inertiaRoll' => (float) resolvePropertyAttribute($dom, $parentDom, 'inertia', 'roll', 0),
```

### Jerk (10 fields — new element)

These come from the `<jerk>` element and its children. This is a **new element** not currently read by the extractor.

| Field Name | XML Path | Type | Description |
|---|---|---|---|
| `jerkStrafe` | `jerk/strafe/@value` | `float` | Strafe jerk |
| `jerkAngular` | `jerk/angular/@value` | `float` | Rotational jerk |
| `jerkForwardAccel` | `jerk/forward/@accel` | `float` | Forward acceleration jerk |
| `jerkForwardDecel` | `jerk/forward/@decel` | `float` | Forward deceleration jerk |
| `jerkForwardRatio` | `jerk/forward/@ratio` | `float` | Forward accel/decel ratio |
| `jerkBoostAccel` | `jerk/forward_boost/@accel` | `float` | Boost acceleration jerk |
| `jerkBoostRatio` | `jerk/forward_boost/@ratio` | `float` | Boost accel ratio |
| `jerkTravelAccel` | `jerk/forward_travel/@accel` | `float` | Travel acceleration jerk |
| `jerkTravelDecel` | `jerk/forward_travel/@decel` | `float` | Travel deceleration jerk |
| `jerkTravelRatio` | `jerk/forward_travel/@ratio` | `float` | Travel accel/decel ratio |

**Extraction note:** The `<jerk>` element has child elements rather than being a flat attribute list. The child elements `<strafe>`, `<angular>`, `<forward>`, `<forward_boost>`, and `<forward_travel>` each have their own attributes. This may need a different extraction approach than the simple `resolvePropertyAttribute()` used for drag/inertia; it may require resolving child elements first.

**Example game XML structure:**
```xml
<physics mass="196.016">
  <drag forward="99.004" reverse="99.004" horizontal="99.004" vertical="99.004"
        pitch="96.271" yaw="96.271" roll="96.271"/>
  <inertia pitch="96.271" yaw="96.271" roll="96.271"/>
  <jerk>
    <strafe value="20.0"/>
    <angular value="15.0"/>
    <forward accel="50.0" decel="50.0" ratio="1.0"/>
    <forward_boost accel="100.0" ratio="1.0"/>
    <forward_travel accel="200.0" decel="200.0" ratio="1.0"/>
  </jerk>
</physics>
```

---

## Required Getter Methods on `ShipDef`

### Drag (6 new methods)
```php
public function getDragReverse(): float;
public function getDragHorizontal(): float;
public function getDragVertical(): float;
public function getDragPitch(): float;
public function getDragYaw(): float;
public function getDragRoll(): float;
```

### Inertia (2 new methods)
```php
public function getInertiaYaw(): float;
public function getInertiaRoll(): float;
```

### Jerk (10 new methods)
```php
public function getJerkStrafe(): float;
public function getJerkAngular(): float;
public function getJerkForwardAccel(): float;
public function getJerkForwardDecel(): float;
public function getJerkForwardRatio(): float;
public function getJerkBoostAccel(): float;
public function getJerkBoostRatio(): float;
public function getJerkTravelAccel(): float;
public function getJerkTravelDecel(): float;
public function getJerkTravelRatio(): float;
```

---

## Required Constants on `ShipDef`

```php
public const string KEY_DRAG_REVERSE = 'dragReverse';
public const string KEY_DRAG_HORIZONTAL = 'dragHorizontal';
public const string KEY_DRAG_VERTICAL = 'dragVertical';
public const string KEY_DRAG_PITCH = 'dragPitch';
public const string KEY_DRAG_YAW = 'dragYaw';
public const string KEY_DRAG_ROLL = 'dragRoll';
public const string KEY_INERTIA_YAW = 'inertiaYaw';
public const string KEY_INERTIA_ROLL = 'inertiaRoll';
public const string KEY_JERK_STRAFE = 'jerkStrafe';
public const string KEY_JERK_ANGULAR = 'jerkAngular';
public const string KEY_JERK_FORWARD_ACCEL = 'jerkForwardAccel';
public const string KEY_JERK_FORWARD_DECEL = 'jerkForwardDecel';
public const string KEY_JERK_FORWARD_RATIO = 'jerkForwardRatio';
public const string KEY_JERK_BOOST_ACCEL = 'jerkBoostAccel';
public const string KEY_JERK_BOOST_RATIO = 'jerkBoostRatio';
public const string KEY_JERK_TRAVEL_ACCEL = 'jerkTravelAccel';
public const string KEY_JERK_TRAVEL_DECEL = 'jerkTravelDecel';
public const string KEY_JERK_TRAVEL_RATIO = 'jerkTravelRatio';
```

---

## Updated `ships.json` Entry Example

After the change, a ship entry should look like this (new fields marked with `// NEW`):

```json
{
    "wareID": "ship_arg_l_destroyer_01_a",
    "label": "Behemoth Vanguard",
    "variantID": "01:a:",
    "dataSourceID": "vanilla",
    "size": "l",
    "classID": "destroyer",
    "builderFactionID": "argon",
    "usedBy": ["argon", "buccaneers", "hatikvah", "scaleplate"],
    "hull": 93000,
    "mass": 196.016,
    "dragForward": 99.004,
    "dragReverse": 99.004,
    "dragHorizontal": 99.004,
    "dragVertical": 99.004,
    "dragPitch": 96.271,
    "dragYaw": 96.271,
    "dragRoll": 96.271,
    "inertiaPitch": 96.271,
    "inertiaYaw": 96.271,
    "inertiaRoll": 96.271,
    "jerkStrafe": 20.0,
    "jerkAngular": 15.0,
    "jerkForwardAccel": 50.0,
    "jerkForwardDecel": 50.0,
    "jerkForwardRatio": 1.0,
    "jerkBoostAccel": 100.0,
    "jerkBoostRatio": 1.0,
    "jerkTravelAccel": 200.0,
    "jerkTravelDecel": 200.0,
    "jerkTravelRatio": 1.0,
    "people": 44,
    "storageMissile": 160,
    "slots": { "...": "..." },
    "equipment": { "...": "..." }
}
```

---

## Summary of Changes

| Area | Currently | Needed | Fields to Add |
|---|---|---|---|
| **Drag** | 1 field (`dragForward`) | All 7 axes | 6 new fields |
| **Inertia** | 1 field (`inertiaPitch`) | All 3 axes | 2 new fields |
| **Jerk** | 0 fields | All components | 10 new fields |
| **Total** | 2 physics fields | 20 physics fields | **18 new fields** |

### Files to modify in x4-core:

1. **`ShipsExtractor.php`** — add 18 fields to `extractStats()` (6 use same pattern as existing drag/inertia, 10 need child element reading from `<jerk>`)
2. **`ShipDef.php`** — add 18 constants + 18 getter methods
3. **`ships.json`** — regenerate (automatic from extractor)

---

## Priority

This is needed for the Cargo Sizes Mod's Physics Tuning GUI to compute:

- **Absolute top speed** per ship (requires drag per axis)
- **Turn rate estimates** (requires rotational drag + inertia per axis)
- **Acceleration responsiveness** (requires jerk values)
- **Class-wide impact ranges** ("worst-case ship at 4x cargo") — iterating all ships with real physics data

Without this data, the GUI falls back to hardcoded placeholder values, making the tuning tool's output unreliable.

---

## Nice-to-Have (Lower Priority)

These would also improve the GUI but are **not blocking**:

### Cargo capacity per ship
Currently not in `ShipDef`. The GUI uses hardcoded estimates per size (S=5000, M=12000, L=30000, XL=50000). Real values come from `storage_*.xml` files in the extracted game data, which are separate macros referenced from ship components. This is more complex to extract than physics data.

### Acceleration factors per ship
From `<accfactors>` element: `forward`, `reverse`, `horizontal`, `vertical` (all float). Used by the mod to scale acceleration with mass changes. Default is 1.0 for all.
