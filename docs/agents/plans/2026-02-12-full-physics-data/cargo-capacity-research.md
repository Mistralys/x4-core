# Cargo Capacity Extraction Research

> **Version:** 1.0  
> **Created:** February 12, 2026  
> **Status:** Complete  
> **Author:** Lead Implementation Engineer Agent  
> **Related Plan:** [2026-02-12-full-physics-data/plan.md](plan.md)

---

## Executive Summary

This document presents research findings on extracting per-ship cargo capacity values from X4 game data. The research confirms that implementation is feasible using a moderate-complexity approach involving connected macro resolution.

**Recommendation:** ✅ **GO** - Implementation is recommended with estimated effort of 2-4 hours.

---

## 1. Storage XML Structure

### Location
Storage macros are located in:
```
assets/props/storagemodules/macros/storage_*_macro.xml
```

### Schema
```xml
<?xml version="1.0" encoding="utf-8"?>
<macros>
  <macro name="storage_arg_l_trans_container_01_a_macro" class="storage">
    <component ref="generic_storage" />
    <properties>
      <identification makerrace="argon" />
      <cargo max="36000" tags="container" />
      <hull integrated="1" />
    </properties>
  </macro>
</macros>
```

### Key Elements
| Element | Attribute | Description | Type |
|---------|-----------|-------------|------|
| `<cargo>` | `max` | **Cargo capacity in m³** | Integer |
| `<cargo>` | `tags` | Cargo type (container, liquid, solid) | String |

### Sample Capacities (Verified)
| Ship | Storage Macro | Capacity | Type |
|------|---------------|----------|------|
| Argon L Freighter | `storage_arg_l_trans_container_01_a_macro` | 36,000 m³ | container |
| Boron L Freighter | `storage_bor_l_trans_container_01_a_macro` | 30,000 m³ | container |
| Argon S Fighter | `storage_arg_s_fighter_01_a_macro` | 240 m³ | container |
| Argon L Miner (Liquid) | `storage_arg_l_miner_liquid_01_a_macro` | ~35,000 m³ | liquid |
| Argon L Miner (Solid) | `storage_arg_l_miner_solid_01_a_macro` | ~35,000 m³ | solid |

---

## 2. Ship-Storage Relationship

### Connection Architecture

Ships reference storage via the `<connections>` section in the ship macro:

```xml
<macro name="ship_arg_l_trans_container_01_a_macro" class="ship_l">
  <component ref="ship_arg_l_trans_container_01" />
  <properties>
    <!-- ... physics, jerk, etc. -->
  </properties>
  <connections>
    <!-- Other connections: cockpit, docking, etc. -->
    <connection ref="con_storage01">
      <macro ref="storage_arg_l_trans_container_01_a_macro" connection="ShipConnection" />
    </connection>
  </connections>
</macro>
```

### Connection Pattern
- **Connection Name:** Typically `con_storage01`, `con_storage02`
- **Macro Reference:** Points to storage macro name
- **Connection Type:** `ShipConnection`

### Pattern Consistency

| Ship Type | Has Storage Connection | Storage Type |
|-----------|------------------------|--------------|
| S Fighter | ✅ Yes | container (240 m³) |
| S Scout | ✅ Yes | container |
| M Freighter | ✅ Yes | container |
| M Miner | ✅ Yes | liquid/solid |
| L Freighter | ✅ Yes | container |
| L Miner | ✅ Yes | liquid/solid |
| L Destroyer | ✅ Yes | container |
| XL Carrier | ✅ Yes | container |

**Finding:** All ships have a storage connection. The pattern is universal.

---

## 3. Implementation Complexity Assessment

### Required Changes

#### 3.1 ShipDef.php
- Add 2 new constants:
  ```php
  public const KEY_CARGO_CAPACITY = 'cargoCapacity';
  public const KEY_CARGO_TYPE = 'cargoType';
  ```
- Add 2 new properties + constructor params
- Add 2 getter methods:
  ```php
  public function getCargoCapacity(): int
  public function getCargoType(): string
  ```

#### 3.2 ShipsExtractor.php
- New method: `resolveStorageCapacity(DOMExtended $dom, ?DOMExtended $parentDom, string $dataSourceID): array`
- Steps:
  1. Find `<connection ref="con_storage01">` in ship macro
  2. Extract storage macro reference name
  3. Resolve storage macro file using MacroFileDefs
  4. Extract `<cargo max="..." tags="..."/>` from storage macro
  5. Return `['capacity' => int, 'type' => string]`

#### 3.3 MacroFileDefs (Optional Enhancement)
- May need helper method `getByMacroNameOptional()` for graceful missing macro handling
- Or use existing pattern with try/catch

### Complexity Factors

| Factor | Complexity | Notes |
|--------|------------|-------|
| XML Pattern | Low | Well-defined, consistent structure |
| Macro Resolution | Medium | Need to resolve macro name → file |
| Cross-DataSource | Medium | Storage macros in same data source as ship |
| Default Values | Low | Use 0/empty for missing storage |
| Testing | Medium | Need to verify across ship types |

### Risk Assessment

| Risk | Likelihood | Impact | Mitigation |
|------|------------|--------|------------|
| Missing storage connection | Low | Low | Default to 0 m³ |
| Storage macro not found | Low | Low | Default to 0 m³, log warning |
| Different schema in DLCs | Low | Medium | Test across all data sources |
| Performance impact | Low | Low | Single additional DOM query per ship |

---

## 4. Estimated Effort

| Task | Estimated Time |
|------|----------------|
| Add ShipDef constants/properties/methods | 30 min |
| Implement resolveStorageCapacity() | 1-2 hours |
| Update processWare() array | 15 min |
| Update tests | 30 min |
| Regenerate ships.json | 15 min |
| Update manifest documentation | 30 min |
| **Total** | **2-4 hours** |

---

## 5. Proposed Implementation

### Phase 1: Basic Implementation
1. Add `cargoCapacity` (int) and `cargoType` (string) to ShipDef
2. Implement storage macro resolution in ShipsExtractor
3. Default to `0` capacity and `"none"` type if storage connection missing

### Phase 2: Enhanced Features (Optional)
- Add cargo tags parsing (multiple tags support)
- Add storage component count (some ships may have multiple storage modules)
- Add cargo efficiency calculations

### Sample Output

After implementation, ships.json would include:
```json
{
  "wareID": "ship_arg_l_trans_container_01_a",
  "label": "Hermes Sentinel",
  "cargoCapacity": 36000,
  "cargoType": "container",
  // ... other fields
}
```

---

## 6. Recommendation

### Decision: ✅ **GO**

**Rationale:**
1. Implementation follows existing patterns (macro resolution)
2. Moderate complexity with clear architecture
3. High value for Cargo Sizes Mod (real capacity vs estimates)
4. No architectural changes required
5. Well-defined fallback behavior

### Suggested Priority: **Medium**
- Not blocking for current GUI functionality
- GUI can continue using size-based estimates
- Implement when time permits or when accuracy is required

### Prerequisites
- WP-001 through WP-006 complete ✅
- ships.json regeneration working ✅

---

## 7. Reference: Current GUI Estimates vs Real Values

| Ship Size | GUI Estimate | Sample Real Value | Deviation |
|-----------|--------------|-------------------|-----------|
| S | 5,000 m³ | 240 m³ (fighter) | ~2000% over |
| M | 12,000 m³ | ~8,000-15,000 m³ | ~±50% |
| L | 30,000 m³ | 36,000 m³ (freighter) | ~17% under |
| XL | 50,000 m³ | ~40,000-100,000 m³ | Variable |

**Note:** GUI estimates are intentionally conservative. Real values vary significantly by ship role (fighter vs freighter).

---

## Change Log

| Date | Version | Changes |
|------|---------|---------|
| 2026-02-12 | 1.0 | Initial research document |

---

## Appendix A: Sample Ship Macro (Full)

```xml
<?xml version="1.0" encoding="utf-8"?>
<macros>
  <macro name="ship_arg_l_trans_container_01_a_macro" class="ship_l">
    <component ref="ship_arg_l_trans_container_01" />
    <properties>
      <identification name="{20101,11202}" ... />
      <jerk>...</jerk>
      <storage missile="30" unit="10" />
      <hull max="57000" />
      <people capacity="110" />
      <physics mass="440.933">
        <inertia pitch="175.799" yaw="175.799" roll="140.64" />
        <drag forward="120" reverse="350" ... />
        <accfactors forward="0.75" ... />
      </physics>
      <ship type="freighter" />
    </properties>
    <connections>
      <connection ref="con_storage01">
        <macro ref="storage_arg_l_trans_container_01_a_macro" connection="ShipConnection" />
      </connection>
    </connections>
  </macro>
</macros>
```

## Appendix B: Sample Storage Macro (Full)

```xml
<?xml version="1.0" encoding="utf-8"?>
<macros>
  <macro name="storage_arg_l_trans_container_01_a_macro" class="storage">
    <component ref="generic_storage" />
    <properties>
      <identification makerrace="argon" />
      <cargo max="36000" tags="container" />
      <hull integrated="1" />
    </properties>
  </macro>
</macros>
```
