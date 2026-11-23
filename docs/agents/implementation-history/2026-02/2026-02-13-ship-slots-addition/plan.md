# Plan: Detailed Ship Slots & Ware Compatibility

> **Status:** Draft / Ready for Implementation
> **Date:** February 8, 2026
> **Goal:** Upgrade Ship and Ware data to support detailed equipment compatibility logic (e.g., "Does this L Shield fit in this Ship?").

## 📋 Context & Philosophy

Currently, the database only stores simple counts (`"shield": 12`). This prevents advanced tools (like Loadout Editors) from knowing distinct slot sizes (L vs M) or restrictions.

**The Solution:**
1.  **Wares** will become "Physical Objects" knowing their Size (`S/M/L/XL`) and Tags.
2.  **Ships** will expose aggregated "Human-Readable" slot definitions.
3.  **Logic** will enable `Ship->canEquip(Ware)` checks.

---

## 🏗 Target JSON Structure

### Wares (`data/wares.json`)
Added fields for physical compatibility.

```json
{
    "wareID": "shield_arg_l_standard_01_mk1",
    // ... existing fields ...
    "specs": {
        "size": "l",
        "tags": ["component", "shield", "standard", "large"]
    }
}
```

### Ships (`data/ships.json`)
New `equipment` dictionary (keeping `slots` for backward compat or deprecating it).

```json
{
    "id": "ship_arg_l_destroyer_01_a",
    // ... existing fields ...
    "equipment": {
        "engines": {
            "count": 3,
            "size": "l",
            "tags": ["standard"]
        },
        "shields": [
            {
                "label": "Main Shields",
                "count": 3,
                "size": "l",
                "tags": ["standard"]
            },
            {
                "label": "Surface Protection",
                "count": 12,
                "size": "m",
                "tags": ["hittable", "standard"]
            }
        ],
        "turrets": [
             // ...
        ]
    }
}
```

---

## 📦 Work Packages

### WP1: Ware Physical Data Extraction
**Goal:** Wares must know their physical properties defined in their 3D components.

**Tasks:**
1.  **Update `WareDef.php`**:
    *   Add `$size` (string) and `$compatibilityTags` (array).
    *   Update `fromArray`/`toArray`.
2.  **Update `WaresExtractor.php`**:
    *   Depend on `MacroFileDefs` to follow: `Ware` -> `Macro` -> `Component`.
    *   Implement `extractComponentDetails(string $macroID)`:
        *   Find `component` file path from Macro.
        *   Parse the main `connection` (usually `name="container"`) to find `tags`.
        *   Map tags (`size_l`, `large`) to normalized size (`l`).
    *   **Optimization:** Only run deep extraction for relevant groups (`ships`, `modules`, `equipment`).

### WP2: Ship Slot Aggregation
**Goal:** Extract raw ship connections and group them into the readable format.

**Tasks:**
1.  **New Class `ShipSlotAggregator`**:
    *   Input: List of raw connection nodes from XML.
    *   Logic:
        *   Group by Type (Engine, Shield, Turret).
        *   Group by Size + Attributes (Connection Group, Tags).
        *   Generate the recursive/aggregated array structure.
2.  **Update `ShipsExtractor.php`**:
    *   Use `MacroFileDefs` to load the ship's component file.
    *   Extract ALL connections first.
    *   Pass to `ShipSlotAggregator`.
    *   Store in `equipment` key.
3.  **Update `ShipDef.php`**:
    *   Add `getEquipment()` accessor.

### WP3: Compatibility Logic
**Goal:** Code capable of verifying if a specific item fits a specific slot definition.

**Tasks:**
1.  **New Class `ShipSlotDefinition`**:
    *   Represents one entry in the `equipment` array (e.g., the "3x L Engines" block).
    *   Properties: `count`, `size`, `requiredTags`.
2.  **Usage Logic**:
    ```php
    public function canEquip(WareDef $ware, ShipSlotDefinition $slot): bool {
        if ($ware->getSize() !== $slot->getSize()) return false;
        // Check intersection of tags
        return $slot->matchesTags($ware->getCompatibilityTags());
    }
    ```

---

## 📝 Implementation Notes

*   **Macro Resolution:** Requires `Self::extractMacroIndex()` to run before Wares/Ships.
*   **Pathing:** Component files are often in `assets/units/size_X/` but referenced relatively. Use `MacroFileDef` to resolve absolute paths.
*   **Breaking Changes:** The target structure for Ships adds a new key. Existing `slots` (simple integer counts) should be preserved for now to avoid breaking the DataGrid UI, or the UI must be updated in a separate task.
