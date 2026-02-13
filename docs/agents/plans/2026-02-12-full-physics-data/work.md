# Work Packages: Full Ship Physics Data

> **Version:** 1.1  
> **Created:** February 12, 2026  
> **Updated:** February 13, 2026  
> **Plan Reference:** [plan.md](plan.md)  
> **Research Reference:** [cargo-capacity-research.md](cargo-capacity-research.md)  
> **Project:** X4 Core - Ship Physics Data Extension

---

## Overview

This document defines work packages for extending the `ShipDef` class and `ShipsExtractor` to include complete physics data (drag, inertia, jerk) and per-ship cargo capacity. The implementation adds 18 physics fields and 2 cargo fields to support the Cargo Sizes Mod's Physics Tuning GUI.

---

## Work Package Summary Table

| ID | Title | Dependencies | Priority | Estimated Effort | Status |
|----|-------|--------------|----------|------------------|--------|
| WP-001 | Add Missing Drag Fields | None | High | 1-2 hours | READY |
| WP-002 | Add Missing Inertia Fields | None | High | 30 min | READY |
| WP-003 | Add Jerk Fields | WP-001, WP-002 | High | 2-3 hours | READY |
| WP-004 | Regenerate Data and Verify | WP-001, WP-002, WP-003 | High | 30 min | READY |
| WP-005 | Update Manifest Documentation | WP-004 | Medium | 1 hour | READY |
| WP-006 | Add Acceleration Factors (Nice-to-Have) | WP-004 | Low | 1-2 hours | READY |
| WP-007 | Implement Cargo Capacity Extraction | WP-004 | Medium | 2-3 hours | READY |
| WP-008 | Regenerate Data and Verify Cargo Values | WP-007 | Medium | 30 min | READY |
| WP-009 | Update Manifest for Cargo Capacity | WP-008 | Medium | 30 min | READY |

**Total Estimated Effort:** 10-15 hours

---

## WP-001: Add Missing Drag Fields

### Objective
Add 6 missing drag coefficient fields to `ShipDef` using the existing extraction pattern.

### Context
The `<drag>` element in ship macro XML already has 7 attributes, but only `forward` is currently extracted. The extraction pattern already exists and works for `dragForward`, so this is a straightforward extension.

### Files to Modify

| File | Location | Action |
|------|----------|--------|
| `ShipDef.php` | `src/X4/Database/Ships/ShipDef.php` | Add 6 constants + 6 getter methods |
| `ShipsExtractor.php` | `src/X4/Database/Ships/ShipsExtractor.php` | Add 6 fields to `extractStats()` |

### Implementation Details

#### 1. ShipDef.php - Add Constants
```php
public const string KEY_DRAG_REVERSE = 'dragReverse';
public const string KEY_DRAG_HORIZONTAL = 'dragHorizontal';
public const string KEY_DRAG_VERTICAL = 'dragVertical';
public const string KEY_DRAG_PITCH = 'dragPitch';
public const string KEY_DRAG_YAW = 'dragYaw';
public const string KEY_DRAG_ROLL = 'dragRoll';
```

#### 2. ShipDef.php - Add Getter Methods
```php
public function getDragReverse(): float
{
    return $this->getFloatKey(self::KEY_DRAG_REVERSE);
}

public function getDragHorizontal(): float
{
    return $this->getFloatKey(self::KEY_DRAG_HORIZONTAL);
}

public function getDragVertical(): float
{
    return $this->getFloatKey(self::KEY_DRAG_VERTICAL);
}

public function getDragPitch(): float
{
    return $this->getFloatKey(self::KEY_DRAG_PITCH);
}

public function getDragYaw(): float
{
    return $this->getFloatKey(self::KEY_DRAG_YAW);
}

public function getDragRoll(): float
{
    return $this->getFloatKey(self::KEY_DRAG_ROLL);
}
```

#### 3. ShipsExtractor.php - Add to extractStats()
```php
'dragReverse'    => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'reverse', 0),
'dragHorizontal' => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'horizontal', 0),
'dragVertical'   => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'vertical', 0),
'dragPitch'      => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'pitch', 0),
'dragYaw'        => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'yaw', 0),
'dragRoll'       => (float) resolvePropertyAttribute($dom, $parentDom, 'drag', 'roll', 0),
```

### Acceptance Criteria
- [ ] 6 new constants added to `ShipDef.php`
- [ ] 6 new getter methods added to `ShipDef.php`
- [ ] 6 new fields extracted in `ShipsExtractor::extractStats()`
- [ ] PHPStan passes with no errors
- [ ] Existing tests continue to pass

### Verification
1. Run `composer analyze` - should pass
2. Run `composer test` - should pass
3. Run `composer build:ships` - should generate ships.json with new fields

---

## WP-002: Add Missing Inertia Fields

### Objective
Add 2 missing inertia fields to `ShipDef` using the existing extraction pattern.

### Context
The `<inertia>` element has 3 attributes (`pitch`, `yaw`, `roll`), but only `pitch` is extracted. Same pattern as WP-001.

### Files to Modify

| File | Location | Action |
|------|----------|--------|
| `ShipDef.php` | `src/X4/Database/Ships/ShipDef.php` | Add 2 constants + 2 getter methods |
| `ShipsExtractor.php` | `src/X4/Database/Ships/ShipsExtractor.php` | Add 2 fields to `extractStats()` |

### Implementation Details

#### 1. ShipDef.php - Add Constants
```php
public const string KEY_INERTIA_YAW = 'inertiaYaw';
public const string KEY_INERTIA_ROLL = 'inertiaRoll';
```

#### 2. ShipDef.php - Add Getter Methods
```php
public function getInertiaYaw(): float
{
    return $this->getFloatKey(self::KEY_INERTIA_YAW);
}

public function getInertiaRoll(): float
{
    return $this->getFloatKey(self::KEY_INERTIA_ROLL);
}
```

#### 3. ShipsExtractor.php - Add to extractStats()
```php
'inertiaYaw'  => (float) resolvePropertyAttribute($dom, $parentDom, 'inertia', 'yaw', 0),
'inertiaRoll' => (float) resolvePropertyAttribute($dom, $parentDom, 'inertia', 'roll', 0),
```

### Acceptance Criteria
- [ ] 2 new constants added to `ShipDef.php`
- [ ] 2 new getter methods added to `ShipDef.php`
- [ ] 2 new fields extracted in `ShipsExtractor::extractStats()`
- [ ] PHPStan passes with no errors
- [ ] Existing tests continue to pass

### Verification
1. Run `composer analyze` - should pass
2. Run `composer test` - should pass
3. Run `composer build:ships` - should generate ships.json with new fields

---

## WP-003: Add Jerk Fields

### Objective
Add 10 jerk fields to `ShipDef` using a new extraction pattern for nested XML elements.

### Context
The `<jerk>` element is **structurally different** from drag/inertia. It has **child elements** (`<strafe>`, `<angular>`, `<forward>`, `<forward_boost>`, `<forward_travel>`) rather than flat attributes. This requires a different extraction approach.

**XML Structure:**
```xml
<jerk>
  <strafe value="20.0"/>
  <angular value="15.0"/>
  <forward accel="50.0" decel="50.0" ratio="1.0"/>
  <forward_boost accel="100.0" ratio="1.0"/>
  <forward_travel accel="200.0" decel="200.0" ratio="1.0"/>
</jerk>
```

### Dependencies
- WP-001: Understanding of existing extraction patterns
- WP-002: Understanding of existing extraction patterns

### Files to Modify

| File | Location | Action |
|------|----------|--------|
| `ShipDef.php` | `src/X4/Database/Ships/ShipDef.php` | Add 10 constants + 10 getter methods |
| `ShipsExtractor.php` | `src/X4/Database/Ships/ShipsExtractor.php` | Add 10 fields with nested element extraction |

### Implementation Details

#### 1. ShipDef.php - Add Constants
```php
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

#### 2. ShipDef.php - Add Getter Methods
```php
public function getJerkStrafe(): float
{
    return $this->getFloatKey(self::KEY_JERK_STRAFE);
}

public function getJerkAngular(): float
{
    return $this->getFloatKey(self::KEY_JERK_ANGULAR);
}

public function getJerkForwardAccel(): float
{
    return $this->getFloatKey(self::KEY_JERK_FORWARD_ACCEL);
}

public function getJerkForwardDecel(): float
{
    return $this->getFloatKey(self::KEY_JERK_FORWARD_DECEL);
}

public function getJerkForwardRatio(): float
{
    return $this->getFloatKey(self::KEY_JERK_FORWARD_RATIO);
}

public function getJerkBoostAccel(): float
{
    return $this->getFloatKey(self::KEY_JERK_BOOST_ACCEL);
}

public function getJerkBoostRatio(): float
{
    return $this->getFloatKey(self::KEY_JERK_BOOST_RATIO);
}

public function getJerkTravelAccel(): float
{
    return $this->getFloatKey(self::KEY_JERK_TRAVEL_ACCEL);
}

public function getJerkTravelDecel(): float
{
    return $this->getFloatKey(self::KEY_JERK_TRAVEL_DECEL);
}

public function getJerkTravelRatio(): float
{
    return $this->getFloatKey(self::KEY_JERK_TRAVEL_RATIO);
}
```

#### 3. ShipsExtractor.php - Add Jerk Extraction

**Research Required:** The developer will need to examine how `resolvePropertyAttribute()` works and potentially:
- Create a new helper function for nested element extraction
- Use XPath queries directly: `//properties/physics/jerk/strafe/@value`
- Or use DOM traversal to find child elements

**Suggested approach:**
```php
// Get the jerk element first, then read child element attributes
$jerkDom = $dom->querySelector('properties physics jerk') ?? $parentDom?->querySelector('properties physics jerk');

// Extract values (pseudo-code - actual implementation may differ)
'jerkStrafe' => (float) ($jerkDom?->querySelector('strafe')?->getAttribute('value') ?? 0.0),
'jerkAngular' => (float) ($jerkDom?->querySelector('angular')?->getAttribute('value') ?? 0.0),
// ... etc
```

### Technical Notes
- The `resolvePropertyAttribute()` function may not support nested paths like `jerk/strafe/@value`
- May need to create a helper function or inline the DOM queries
- Ensure default values (0.0) are used when elements are missing
- Some ships may not have jerk data defined (older content)

### Acceptance Criteria
- [ ] 10 new constants added to `ShipDef.php`
- [ ] 10 new getter methods added to `ShipDef.php`
- [ ] 10 new fields extracted in `ShipsExtractor::extractStats()`
- [ ] Nested XML element extraction implemented correctly
- [ ] Default values (0.0) used for missing elements
- [ ] PHPStan passes with no errors
- [ ] Existing tests continue to pass

### Verification
1. Run `composer analyze` - should pass
2. Run `composer test` - should pass
3. Run `composer build:ships` - should generate with jerk data
4. Spot-check 3-5 ships to verify jerk values match game XML

---

## WP-004: Regenerate Data and Verify

### Objective
Rebuild the ships database and verify all 18 new fields are correctly extracted.

### Dependencies
- WP-001: Drag fields implementation
- WP-002: Inertia fields implementation
- WP-003: Jerk fields implementation

### Tasks

1. **Run Full Build**
   ```bash
   composer build:ships
   # or
   composer build:all
   ```

2. **Verify ships.json Structure**
   - Open `data/ships.json`
   - Verify new fields present in entries
   - Check that values are reasonable (not all zeros)

3. **Spot-Check Ship Data**
   - Select 3-5 ships of different sizes (S, M, L, XL)
   - Compare extracted values against source XML files
   - Document any discrepancies

### Validation Checklist

| Ship Type | Ship Name | Verify Drag | Verify Inertia | Verify Jerk |
|-----------|-----------|-------------|----------------|-------------|
| S Fighter | (any) | [ ] | [ ] | [ ] |
| M Freighter | (any) | [ ] | [ ] | [ ] |
| L Destroyer | Behemoth | [ ] | [ ] | [ ] |
| XL Carrier | (any) | [ ] | [ ] | [ ] |

### Acceptance Criteria
- [ ] `composer build:ships` completes without errors
- [ ] `ships.json` contains all 18 new fields
- [ ] Values are correctly extracted (non-zero for ships with data)
- [ ] Spot-check of 4+ ships shows correct values

---

## WP-005: Update Manifest Documentation

### Objective
Update the project manifest with new public API additions.

### Dependencies
- WP-004: Data verified and working

### Files to Update

| File | Section | Changes |
|------|---------|---------|
| `public-api.md` | `ShipDef` class | Add 18 constants + 18 getter method signatures |
| `data-flows.md` | (if needed) | Document new physics data extraction flow |

### Implementation Details

#### public-api.md Updates

Add to the `ShipDef` class section:

**Constants:**
```markdown
#### Physics Constants (Drag)
KEY_DRAG_REVERSE: string
KEY_DRAG_HORIZONTAL: string
KEY_DRAG_VERTICAL: string
KEY_DRAG_PITCH: string
KEY_DRAG_YAW: string
KEY_DRAG_ROLL: string

#### Physics Constants (Inertia)
KEY_INERTIA_YAW: string
KEY_INERTIA_ROLL: string

#### Physics Constants (Jerk)
KEY_JERK_STRAFE: string
KEY_JERK_ANGULAR: string
KEY_JERK_FORWARD_ACCEL: string
KEY_JERK_FORWARD_DECEL: string
KEY_JERK_FORWARD_RATIO: string
KEY_JERK_BOOST_ACCEL: string
KEY_JERK_BOOST_RATIO: string
KEY_JERK_TRAVEL_ACCEL: string
KEY_JERK_TRAVEL_DECEL: string
KEY_JERK_TRAVEL_RATIO: string
```

**Methods:**
```markdown
#### Drag Methods
getDragReverse(): float
getDragHorizontal(): float
getDragVertical(): float
getDragPitch(): float
getDragYaw(): float
getDragRoll(): float

#### Inertia Methods
getInertiaYaw(): float
getInertiaRoll(): float

#### Jerk Methods
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
```

### Acceptance Criteria
- [ ] `public-api.md` updated with all 18 constants
- [ ] `public-api.md` updated with all 18 getter methods
- [ ] Documentation matches actual implementation
- [ ] "Last Updated" date updated in manifest

---

## WP-006: Add Acceleration Factors (Nice-to-Have)

### Objective
Add 4 acceleration factor fields from the `<accfactors>` element.

### Priority
**Low** - Nice-to-have enhancement for future physics tuning features.

### Context
The `<accfactors>` element provides multipliers that affect how acceleration scales. The Cargo Sizes Mod uses these to adjust acceleration when mass changes.

**XML Structure:**
```xml
<accfactors forward="1.0" reverse="1.0" horizontal="1.0" vertical="1.0"/>
```

### Files to Modify

| File | Location | Action |
|------|----------|--------|
| `ShipDef.php` | `src/X4/Database/Ships/ShipDef.php` | Add 4 constants + 4 getter methods |
| `ShipsExtractor.php` | `src/X4/Database/Ships/ShipsExtractor.php` | Add 4 fields to `extractStats()` |

### Implementation Details

Same pattern as WP-001 (flat attributes on element):

```php
// Constants
public const string KEY_ACCFACTOR_FORWARD = 'accFactorForward';
public const string KEY_ACCFACTOR_REVERSE = 'accFactorReverse';
public const string KEY_ACCFACTOR_HORIZONTAL = 'accFactorHorizontal';
public const string KEY_ACCFACTOR_VERTICAL = 'accFactorVertical';

// Extraction (defaults to 1.0 if not present)
'accFactorForward'    => (float) resolvePropertyAttribute($dom, $parentDom, 'accfactors', 'forward', 1.0),
'accFactorReverse'    => (float) resolvePropertyAttribute($dom, $parentDom, 'accfactors', 'reverse', 1.0),
'accFactorHorizontal' => (float) resolvePropertyAttribute($dom, $parentDom, 'accfactors', 'horizontal', 1.0),
'accFactorVertical'   => (float) resolvePropertyAttribute($dom, $parentDom, 'accfactors', 'vertical', 1.0),
```

### Acceptance Criteria
- [ ] 4 new constants added to `ShipDef.php`
- [ ] 4 new getter methods added to `ShipDef.php`
- [ ] 4 new fields extracted with default of 1.0
- [ ] PHPStan passes with no errors
- [ ] Manifest documentation updated

---

## WP-007: Implement Cargo Capacity Extraction

### Objective
Add per-ship cargo capacity (`cargoCapacity`) and cargo type (`cargoType`) to `ShipDef` by resolving connected storage macros from ship XML data.

### Priority
**Medium** - Provides real cargo values to replace hardcoded estimates in the Physics Tuning GUI.

### Context
Research completed in [cargo-capacity-research.md](cargo-capacity-research.md) confirms this is feasible. Ships reference storage via `<connections>` sections that point to storage macro files containing `<cargo max="N" tags="type"/>` elements. The pattern is universal across all ship types.

**Current state:** GUI uses hardcoded estimates (S=5000, M=12000, L=30000, XL=50000 m³).  
**Target state:** Real per-ship values (e.g., Argon L Freighter = 36,000 m³ container).

### Dependencies
- WP-004: Data regeneration working

### Files to Modify

| File | Location | Action |
|------|----------|--------|
| `ShipDef.php` | `src/X4/Database/Ships/ShipDef.php` | Add 2 constants + 2 properties + 2 getter methods |
| `ShipsExtractor.php` | `src/X4/Database/Ships/ShipsExtractor.php` | Add `resolveStorageCapacity()` method, integrate into `processWare()` |

### Implementation Details

#### 1. ShipDef.php - Add Constants
```php
public const string KEY_CARGO_CAPACITY = 'cargoCapacity';
public const string KEY_CARGO_TYPE = 'cargoType';
```

#### 2. ShipDef.php - Add Getter Methods
```php
public function getCargoCapacity(): int
{
    return $this->getIntKey(self::KEY_CARGO_CAPACITY);
}

public function getCargoType(): string
{
    return $this->getStringKey(self::KEY_CARGO_TYPE);
}
```

#### 3. ShipsExtractor.php - Add Storage Macro Resolution

New method `resolveStorageCapacity()` with the following algorithm:

1. Find `<connection ref="con_storage01">` in ship macro DOM
2. Extract the `<macro ref="storage_*_macro">` reference name
3. Resolve the storage macro file using `MacroFileDefs`
4. Parse the storage macro XML and extract `<cargo max="..." tags="..."/>`
5. Return `['capacity' => int, 'type' => string]`

```php
private function resolveStorageCapacity(
    DOMExtended $dom,
    ?DOMExtended $parentDom,
    string $dataSourceID
): array
{
    // 1. Find storage connection
    // 2. Get macro reference
    // 3. Resolve macro file via MacroFileDefs
    // 4. Extract cargo max and tags
    // 5. Return ['capacity' => int, 'type' => string]
    // Default: ['capacity' => 0, 'type' => 'none']
}
```

#### 4. ShipsExtractor.php - Update processWare()
```php
$storageInfo = $this->resolveStorageCapacity($dom, $parentDom, $dataSourceID);
// Add to ship data array:
'cargoCapacity' => $storageInfo['capacity'],
'cargoType'     => $storageInfo['type'],
```

### Error Handling
- **Missing storage connection:** Default to `0` capacity, `"none"` type
- **Storage macro not found:** Default to `0` capacity, `"none"` type, log warning
- **Missing cargo element:** Default to `0` capacity, `"none"` type

### Reference Values (from research)

| Ship | Expected Capacity | Expected Type |
|------|-------------------|---------------|
| Argon L Freighter | 36,000 m³ | container |
| Boron L Freighter | 30,000 m³ | container |
| Argon S Fighter | 240 m³ | container |
| Argon L Miner (Liquid) | ~35,000 m³ | liquid |
| Argon L Miner (Solid) | ~35,000 m³ | solid |

### Acceptance Criteria
- [ ] 2 new constants added to `ShipDef.php` (`KEY_CARGO_CAPACITY`, `KEY_CARGO_TYPE`)
- [ ] 2 new getter methods added (`getCargoCapacity(): int`, `getCargoType(): string`)
- [ ] `resolveStorageCapacity()` method implemented in `ShipsExtractor.php`
- [ ] Storage macro resolution works across all data sources
- [ ] Default values (0 / "none") used for missing storage connections
- [ ] PHPStan passes with no errors
- [ ] Existing tests continue to pass

### Verification
1. Run `composer analyze` - should pass
2. Run `composer test` - should pass
3. Run `composer build:ships` - verify no errors during extraction

---

## WP-008: Regenerate Data and Verify Cargo Values

### Objective
Rebuild ships.json and verify cargo capacity and type values are correctly extracted.

### Dependencies
- WP-007: Cargo capacity extraction implemented

### Tasks

1. **Run Full Build**
   ```bash
   composer build:ships
   ```

2. **Verify ships.json Structure**
   - Open `data/ships.json`
   - Verify `cargoCapacity` and `cargoType` present in entries
   - Check that freighters have high values, fighters have low values

3. **Spot-Check Against Research**
   Compare extracted values against known values from [cargo-capacity-research.md](cargo-capacity-research.md).

### Validation Checklist

| Ship Type | Expected Capacity Range | Expected Type | Verified |
|-----------|------------------------|---------------|----------|
| S Fighter | 100-500 m³ | container | [ ] |
| M Freighter | 5,000-15,000 m³ | container | [ ] |
| L Freighter | 25,000-40,000 m³ | container | [ ] |
| L Miner (Liquid) | 25,000-40,000 m³ | liquid | [ ] |
| L Miner (Solid) | 25,000-40,000 m³ | solid | [ ] |
| XL Carrier | 10,000-100,000 m³ | container | [ ] |

### Acceptance Criteria
- [ ] `composer build:ships` completes without errors
- [ ] `ships.json` contains `cargoCapacity` and `cargoType` fields for all ships
- [ ] Values are non-zero for ships with storage connections
- [ ] Cargo types include `container`, `liquid`, and `solid` where expected
- [ ] Spot-check of 6+ ships matches expected ranges

---

## WP-009: Update Manifest Documentation for Cargo Capacity

### Objective
Update the project manifest with cargo capacity additions to `ShipDef`.

### Dependencies
- WP-008: Cargo data verified and working

### Files to Update

| File | Section | Changes |
|------|---------|--------|
| `public-api.md` | `ShipDef` class | Add 2 constants + 2 getter method signatures |
| `data-flows.md` | Extraction flows | Document storage macro resolution pattern |
| `extraction-reference.md` | Ships extractor | Document connected macro resolution for storage |

### Implementation Details

#### public-api.md Updates

Add to the `ShipDef` class section:

**Constants:**
```markdown
#### Cargo Constants
KEY_CARGO_CAPACITY: string
KEY_CARGO_TYPE: string
```

**Methods:**
```markdown
#### Cargo Methods
getCargoCapacity(): int
getCargoType(): string
```

#### data-flows.md Updates

Document the storage macro resolution flow:
```
Ship Macro XML
  → Find <connection ref="con_storage01">
  → Extract storage macro reference
  → Resolve via MacroFileDefs
  → Parse storage macro XML
  → Extract <cargo max="N" tags="type"/>
  → Return capacity + type
```

### Acceptance Criteria
- [ ] `public-api.md` updated with 2 new constants and 2 getter methods
- [ ] `data-flows.md` updated with storage macro resolution flow
- [ ] Documentation matches actual implementation
- [ ] "Last Updated" date updated in manifest

---

## Change Log

| Date | Version | Changes |
|------|---------|---------|| 2026-02-13 | 1.1 | Replaced WP-007 (research) with WP-007/008/009 (cargo capacity implementation) based on completed research || 2026-02-12 | 1.0 | Initial work package definition |

---

## References

- **Plan Document:** [plan.md](plan.md)
- **X4 Core Manifest:** [project-manifest/README.md](../../project-manifest/README.md)
- **Public API Reference:** [project-manifest/public-api.md](../../project-manifest/public-api.md)
- **Constraints:** [project-manifest/constraints.md](../../project-manifest/constraints.md)
- **ShipDef Location:** `src/X4/Database/Ships/ShipDef.php`
- **ShipsExtractor Location:** `src/X4/Database/Ships/ShipsExtractor.php`
- **Cargo Capacity Research:** [cargo-capacity-research.md](cargo-capacity-research.md)
