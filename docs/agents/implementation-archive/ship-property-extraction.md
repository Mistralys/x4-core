# Implementation Plan: Ship Data Extraction Upgrade (Stats & Slots)

> **Status:** Draft
> **Goal:** Expand `Ship` data model and extraction logic to include critical gameplay statistics (Hull, Speed) and equipment slots (Weapons, Shields).

## 📄 Context & Architecture

Currently, `x4-core` only extracts metadata (ID, Name, Race, Class) for ships. It ignores gameplay data.
Ship data in X4 is split across two XML files:
1.  **Macro File** (e.g., `ship_arg_s_fighter_01_a_macro.xml`): Contains physical stats like Hull, Mass, Drag, Crew.
2.  **Component File** (e.g., `ship_arg_s_fighter_01.xml`): Contains the 3D geometry and "Connections" (hardpoints) for Weapons, Shields, and Engines.

To get a complete picture, the extractor must read the Macro file, find the reference to the Component file, and then read that Component file to count slots.

## 📦 Work Packages

### WP0: Slot Types Collection (New Requirement)

**Objective:** Create a dedicated Collection to manage available slot types (e.g., Weapon, Shield), their internal tags, and human-readable labels.

**Files:**
*   `src/X4/Database/SlotTypes/SlotType.php` (Item)
*   `src/X4/Database/SlotTypes/SlotTypes.php` (Collection)
*   `src/X4/Database/SlotTypes/KnownSlotTypes.php` (Static Access)

**Tasks:**
1.  **Architecture:** Implement the standard Collection-Item pattern for `SlotType`.
2.  **Definition:** Define standard slot types based on known XML tags:
    *   `tags="weapon"` → Label: "Weapon"
    *   `tags="shield"` → Label: "Shield"
    *   `tags="turret"` → Label: "Turret"
    *   `tags="dockingbay"` → Label: "Docking Bay"
    *   `tags="countermeasures"` → Label: "Countermeasures"
    *   `tags="engine"` → Label: "Engine"
3.  **Manifest:** Update `AGENTS.md` to register this new collection (Database namespace).

**Dependencies:** None. This defines the vocabulary for later WPs.

---

### WP1: Data Modeling (`ShipDef` Class)

**Objective:** Update the data container to store the new fields, using the new Slot Definitions.

**File:** `src/X4/Database/Ships/ShipDef.php`

**Tasks:**
1.  Add private properties (typed as `int` or `float`) for:
    *   **Stats:** `$hull`, `$mass`, `$dragForward`, `$inertiaPitch`, `$people`, `$storageMissile`.
    *   **Slots:** Store an array `$slots` (type => count) or specific properties mapped to `KnownSlotTypes` constants.
2.  Initialize them in `__construct()` (from the `$data` array).
3.  Add public Getters:
    *   `getHull()`, `getMass()`, etc.
    *   `getSlotCount(string $typeID): int` - Generic accessor using `SlotTypes`.
    *   Convenience getters: `getWeaponSlots()`, `getShieldSlots()`.
4.  Update `toArray()` to include these fields.

**Dependencies:** WP0 (for constants/types).

---

### WP2: Stats Extraction (Macro File)

**Objective:** Extract scalar values from the main XML file we are already reading.

**File:** `src/X4/Database/Ships/ShipsExtractor.php`

**Tasks:**
1.  In `extractItem()`, locate the `<properties>` node.
2.  Extract attributes:
    *   Hull: `<hull max="3000" />`
    *   People: `<people capacity="3" />`
    *   Storage: `<storage missile="20" />`
    *   Physics: `<physics mass="5"><drag forward="3" /><inertia pitch="1" /></physics>`
3.  Add these to the output array.
4.  **Verification:** Run extractor and check `data/ships.json` for new fields.

**Dependencies:** WP1 must be complete so the class can receive the data, or at least the array structure is defined.

---

### WP3: Component Resolution Logic

**Objective:** Logic to find the path to the "Component" file based on the "Macro" file.

**File:** `src/X4/Database/Ships/ShipsExtractor.php`

**Logic:**
1.  Read the `<component ref="...">` attribute from the Macro file.
2.  The `ref` usually points to a file in the same directory structure, but without `_macro` suffix and often in the parent folder or a parallel asset folder.
3.  Implement a helper method `resolveComponentPath(string $macroPath, string $componentRef): ?string` which searches the `x4-data-extractor` output directories for this file.

**Dependencies:** Functional environment with `x4-data-extractor` available.

---

### WP4: Slot Extraction (Component File)

**Objective:** Open the resolved component file and count hardpoints using standard Slot Types.

**File:** `src/X4/Database/Ships/ShipsExtractor.php`

**Tasks:**
1.  In `extractItem()`, call the resolver from WP3.
2.  If found, parse the Component XML.
3.  Iterate over `<connection>` nodes.
4.  Inspect `tags` attribute (space-separated string).
    *   Match tags against `SlotTypes` collection.
    *   Example: `tags="weapon small standard"` matches `SlotTypes::WEAPON`.
    *   Update the corresponding count in an internal accumulator (e.g. `$slotCounts[$typeID]++`).
5.  Add counts to the output array as `counts` map or individual fields.

**Dependencies:** WP3 (Resolution logic), WP0 (SlotTypes).

---

### WP5: Test Coverage

**Objective:** Ensure new fields are correctly typed and populated.

**Files:**
*   `tests/X4Tests/Suites/Database/Ships/ShipDefTests.php`
*   `tests/X4Tests/Suites/Database/Extractors/ShipsExtractorTests.php`

**Tasks:**
1.  **Unit Tests:** Add tests to `ShipDefTests` verifying the new getters return the expected types (int/float).
2.  **Integration Tests:** Update `ShipsExtractorTests` to verify that specific known ships (e.g., `ship_arg_s_fighter_01`) have > 0 hull and > 0 weapon slots.

---

## 📅 Execution Order
1.  **WP0**: Implement Slot Types.
2.  **WP1 & WP5 (Unit Tests)**: Update Ship class and add unit tests.
3.  **WP2**: Implement basic stats extraction.
4.  **WP3 & WP4**: Implement advanced multi-file slot extraction.
5.  **WP5 (Integration)**: Final verification.
