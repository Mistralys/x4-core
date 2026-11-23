# Weapon Systems Collection Implementation Plan

**Status:** Ready for Implementation  
**Created:** February 9, 2026  
**Priority:** Medium (Enhancement)  
**Estimated Effort:** 2-3 hours

---

## 📋 Background

### Current State
The weapon metadata system extracts 269 weapons with a `weaponSystem` property. Filtering by weapon system **already works** via:
- `WeaponFinder::selectWeaponSystem(string $system)` - Fluent filtering
- `WeaponDefs::getByWeaponSystem(string $weaponSystem)` - Direct access

**Problem:** Weapon system IDs are magic strings (`''turret_shortrange''`) with no:
- Type-safe constants
- Human-readable labels
- Centralized metadata
- Validation (unknown systems fail silently)

### Proposed Solution
Add a `WeaponSystems` collection following the [SlotTypes](../../../src/X4/Database/SlotTypes/SlotTypes.php) pattern:
- Small, finite set (8 weapon systems)
- Hardcoded data in `loadInitialData()` method
- Type-safe constants via `KnownWeaponSystems`
- Human-readable labels and descriptions
- **Exception thrown for unknown weapon system types** (future-proofing)

### Benefits
1. **Type Safety:** IDE autocomplete for `KnownWeaponSystems::TURRET_SHORTRANGE`
2. **UI Ready:** Labels available for dropdowns/tables
3. **Future-Proof:** Exception alerts developers if game adds new weapon systems
4. **Consistency:** Follows established patterns (SlotTypes, DataSources)

---

## 🔍 Research Context

### Weapon Systems Identified
From [data/weapons.json](../../../data/weapons.json), 8 unique weapon system types exist:

| ID | Count | Context |
|----|-------|---------|
| `turret_shortrange` | ~30 | Beam weapons, gatling guns |
| `turret_midrange` | ~80 | Pulse lasers, plasma |
| `turret_longrange` | ~60 | Bolt repeaters, flak |
| `weapon_standard` | ~20 | Fixed ship weapons |
| `weapon_mining` | ~5 | Mining lasers |
| `missile_dumbfire` | ~10 | Unguided missiles |
| `missile_guided` | ~20 | Tracking missiles |
| `torpedo` | ~5 | Capital ship weapons |

### Pattern to Follow
**Reference Implementation:** [src/X4/Database/SlotTypes/](../../../src/X4/Database/SlotTypes/)

**Structure:**
```
SlotTypes/
├── KnownSlotTypes.php    (Constants class)
├── SlotType.php          (Item class)
└── SlotTypes.php         (Collection class)
```

**Key Pattern Features:**
- Extends `BaseStringPrimaryCollection<T>`
- Implements `ItemCollectionInterface`
- Singleton pattern with `getInstance()`
- Hardcoded data in `loadInitialData()` method
- No JSON file needed
- ~100 lines per class

### Related Files
- [src/X4/Database/Weapons/WeaponDef.php](../../../src/X4/Database/Weapons/WeaponDef.php) - Has `getWeaponSystem(): string`
- [src/X4/Database/Weapons/WeaponFinder.php](../../../src/X4/Database/Weapons/WeaponFinder.php) - Has `selectWeaponSystem()` at line 127
- [src/X4/Database/Weapons/WeaponDefs.php](../../../src/X4/Database/Weapons/WeaponDefs.php) - Has `getByWeaponSystem()` at line 105
- [src/X4/Database/Weapons/WeaponsExtractor.php](../../../src/X4/Database/Weapons/WeaponsExtractor.php) - Extracts weaponSystem from XML

---

## 📦 Work Package 1: Create Constants Class

**File:** `src/X4/Database/WeaponSystems/KnownWeaponSystems.php`

**Dependencies:** None  
**Estimated Time:** 10 minutes

### Implementation Details

1. **Create new file** at `src/X4/Database/WeaponSystems/KnownWeaponSystems.php`

2. **Structure:** Mirror [KnownSlotTypes.php](../../../src/X4/Database/SlotTypes/KnownSlotTypes.php) exactly

3. **Add 8 constants:**
   ```php
   <?php
   declare(strict_types=1);
   namespace Mistralys\X4\Database\WeaponSystems;
   
   class KnownWeaponSystems
   {
       public const TURRET_SHORTRANGE = ''turret_shortrange'';
       public const TURRET_MIDRANGE = ''turret_midrange'';
       public const TURRET_LONGRANGE = ''turret_longrange'';
       public const WEAPON_STANDARD = ''weapon_standard'';
       public const WEAPON_MINING = ''weapon_mining'';
       public const MISSILE_DUMBFIRE = ''missile_dumbfire'';
       public const MISSILE_GUIDED = ''missile_guided'';
       public const TORPEDO = ''torpedo'';
   }
   ```

4. **Naming Convention:** Follow [constraints.md](../project-manifest/constraints.md#naming-conventions):
   - Class name: PascalCase
   - Constants: SCREAMING_SNAKE_CASE
   - Namespace: Follows directory structure

### Verification
- File compiles without errors
- Constants accessible: `KnownWeaponSystems::TURRET_SHORTRANGE`

---

## 📦 Work Package 2: Create Item Class

**File:** `src/X4/Database/WeaponSystems/WeaponSystem.php`

**Dependencies:** Work Package 1  
**Estimated Time:** 20 minutes

### Implementation Details

1. **Create new file** at `src/X4/Database/WeaponSystems/WeaponSystem.php`

2. **Structure:** Mirror [SlotType.php](../../../src/X4/Database/SlotTypes/SlotType.php) exactly

3. **Required properties:**
   ```php
   private string $id;           // e.g., ''turret_shortrange''
   private string $label;        // e.g., ''Short-Range Turret''
   private string $description;  // e.g., ''Automated turrets optimized...''
   ```

4. **Implement interface:**
   ```php
   class WeaponSystem implements CollectionItemInterface
   {
       use CollectionItemTrait;
       
       public const KEY_LABEL = ''label'';
       public const KEY_DESCRIPTION = ''description'';
   ```

5. **Required methods:**
   - `__construct(WeaponSystems $collection, array $data)` - Initialize from array
   - `getID(): string` - Return weapon system ID
   - `getVariantID(): VariantID` - Return `VariantID::fromID($this->id)`
   - `getLabel(): string` - Return human-readable label
   - `getDescription(): string` - Return description text

6. **Add helper methods:**
   ```php
   public function isTurret(): bool
   {
       return str_starts_with($this->id, ''turret_'');
   }
   
   public function isMissile(): bool
   {
       return str_starts_with($this->id, ''missile_'') 
           || $this->id === ''torpedo'';
   }
   
   public function isStandardWeapon(): bool
   {
       return str_starts_with($this->id, ''weapon_'');
   }
   ```

### Verification
- File compiles without errors
- All required methods from `CollectionItemInterface` implemented
- Trait usage correct (see [tech-stack.md](../project-manifest/tech-stack.md#collection-item-pattern))

---

## 📦 Work Package 3: Create Collection Class

**File:** `src/X4/Database/WeaponSystems/WeaponSystems.php`

**Dependencies:** Work Packages 1, 2  
**Estimated Time:** 30 minutes

### Implementation Details

1. **Create new file** at `src/X4/Database/WeaponSystems/WeaponSystems.php`

2. **Structure:** Mirror [SlotTypes.php](../../../src/X4/Database/SlotTypes/SlotTypes.php) exactly

3. **Class declaration:**
   ```php
   /**
    * @extends BaseStringPrimaryCollection<WeaponSystem>
    */
   class WeaponSystems extends BaseStringPrimaryCollection 
       implements ItemCollectionInterface
   {
       private static ?WeaponSystems $instance = null;
   ```

4. **Required methods:**
   - `getInstance(): WeaponSystems` - Singleton accessor
   - `getCollectionName(): string` - Return `''Weapon Systems''`
   - `getCollectionDescription(): string` - Return description
   - `getDefaultID(): string` - Return `KnownWeaponSystems::WEAPON_STANDARD`
   - `registerItems(): void` - Load and register items
   - `createItem(array $data): CollectionItemInterface` - Factory method

5. **Hardcoded data in `loadInitialData()`:**
   ```php
   protected function loadInitialData(): array
   {
       return [
           [
               ''id'' => KnownWeaponSystems::TURRET_SHORTRANGE,
               WeaponSystem::KEY_LABEL => ''Short-Range Turret'',
               WeaponSystem::KEY_DESCRIPTION => ''Automated turrets optimized for close combat: beam weapons, gatling guns, and short-range plasma.''
           ],
           [
               ''id'' => KnownWeaponSystems::TURRET_MIDRANGE,
               WeaponSystem::KEY_LABEL => ''Mid-Range Turret'',
               WeaponSystem::KEY_DESCRIPTION => ''Automated turrets for balanced engagement: pulse lasers, plasma cannons, and mass drivers.''
           ],
           [
               ''id'' => KnownWeaponSystems::TURRET_LONGRANGE,
               WeaponSystem::KEY_LABEL => ''Long-Range Turret'',
               WeaponSystem::KEY_DESCRIPTION => ''Automated turrets for distance fighting: bolt repeaters, flak arrays, and ion weaponry.''
           ],
           [
               ''id'' => KnownWeaponSystems::WEAPON_STANDARD,
               WeaponSystem::KEY_LABEL => ''Standard Weapon'',
               WeaponSystem::KEY_DESCRIPTION => ''Fixed ship-mounted weapons for direct fire combat across all range bands.''
           ],
           [
               ''id'' => KnownWeaponSystems::WEAPON_MINING,
               WeaponSystem::KEY_LABEL => ''Mining Laser'',
               WeaponSystem::KEY_DESCRIPTION => ''Specialized lasers for extracting resources from asteroids and mineral deposits.''
           ],
           [
               ''id'' => KnownWeaponSystems::MISSILE_DUMBFIRE,
               WeaponSystem::KEY_LABEL => ''Dumbfire Missile'',
               WeaponSystem::KEY_DESCRIPTION => ''Unguided missiles that travel in a straight line at high speed.''
           ],
           [
               ''id'' => KnownWeaponSystems::MISSILE_GUIDED,
               WeaponSystem::KEY_LABEL => ''Guided Missile'',
               WeaponSystem::KEY_DESCRIPTION => ''Tracking missiles that pursue and lock onto targets with varying degrees of agility.''
           ],
           [
               ''id'' => KnownWeaponSystems::TORPEDO,
               WeaponSystem::KEY_LABEL => ''Torpedo'',
               WeaponSystem::KEY_DESCRIPTION => ''Heavy capital ship weapons with devastating damage at the cost of slow speed and maneuverability.''
           ]
       ];
   }
   ```

6. **Add filter helper methods:**
   ```php
   public function getTurretSystems(): array
   {
       return array_filter($this->getAll(), 
           fn(WeaponSystem $system) => $system->isTurret());
   }
   
   public function getMissileSystems(): array
   {
       return array_filter($this->getAll(), 
           fn(WeaponSystem $system) => $system->isMissile());
   }
   
   public function getStandardWeaponSystems(): array
   {
       return array_filter($this->getAll(), 
           fn(WeaponSystem $system) => $system->isStandardWeapon());
   }
   ```

### Verification
- File compiles without errors
- `getInstance()` returns singleton instance
- All 8 weapon systems load correctly
- Filter methods return correct subsets

---

## 📦 Work Package 4: Add Exception for Unknown Weapon Systems

**Files:** 
- `src/X4/Database/Weapons/WeaponException.php` (modify)
- `src/X4/Database/Weapons/WeaponsExtractor.php` (modify)

**Dependencies:** Work Package 3  
**Estimated Time:** 20 minutes

### Implementation Details

1. **Add error constant to WeaponException:**
   ```php
   // File: src/X4/Database/Weapons/WeaponException.php
   // Add after existing constants (around line 20)
   
   public const ERROR_UNKNOWN_WEAPON_SYSTEM = 12005;
   ```

2. **Add validation method to WeaponSystems:**
   ```php
   // File: src/X4/Database/WeaponSystems/WeaponSystems.php
   // Add as new method
   
   /**
    * Check if a weapon system ID is known.
    * 
    * @param string $systemID
    * @return bool
    */
   public function isKnownSystem(string $systemID): bool
   {
       return $this->idExists($systemID);
   }
   
   /**
    * Validate that a weapon system exists, throw exception if not.
    * 
    * @param string $systemID
    * @throws WeaponException
    * @return void
    */
   public function requireKnownSystem(string $systemID): void
   {
       if (!$this->isKnownSystem($systemID)) {
           throw new WeaponException(
               sprintf(
                   ''Unknown weapon system type: "%s". Known systems: %s'',
                   $systemID,
                   implode('', '', $this->getIDs())
               ),
               WeaponException::ERROR_UNKNOWN_WEAPON_SYSTEM
           );
       }
   }
   ```

3. **Add validation in WeaponsExtractor:**
   ```php
   // File: src/X4/Database/Weapons/WeaponsExtractor.php
   // In processWare() method, after extracting weapon data
   
   use Mistralys\X4\Database\WeaponSystems\WeaponSystems;
   
   // After: $weaponData = $extractor->extract();
   // Add validation:
   
   $weaponSystem = $weaponData[''weaponSystem''] ?? '''';
   if (!empty($weaponSystem)) {
       WeaponSystems::getInstance()->requireKnownSystem($weaponSystem);
   }
   ```

4. **Alternative: Add validation in WeaponDef:**
   ```php
   // File: src/X4/Database/Weapons/WeaponDef.php
   // In fromArray() factory method or constructor
   
   use Mistralys\X4\Database\WeaponSystems\WeaponSystems;
   
   // Before returning new WeaponDef:
   $weaponSystem = $data[''weaponSystem''] ?? '''';
   if (!empty($weaponSystem)) {
       WeaponSystems::getInstance()->requireKnownSystem($weaponSystem);
   }
   ```

### Decision Point
**Where to validate?**
- **Option A: In WeaponsExtractor** - Fails fast during extraction, clear error context
- **Option B: In WeaponDef::fromArray()** - Validates on every load, catches manual JSON edits
- **Recommendation:** Option A (extractor) for clearer error messages during build

### Verification
- `composer extract-weapons` succeeds with current data
- Manual test: Add fake weapon system to JSON, run extraction, expect exception with helpful message
- Exception message includes: unknown system ID + list of valid systems

---

## 📦 Work Package 5: Create Tests

**Files:** 
- `tests/test-weapon-systems.php` (Quick validation script)
- `tests/X4Tests/Suites/WeaponSystemsTest.php` (PHPUnit test class)

**Dependencies:** Work Packages 1-4  
**Estimated Time:** 30 minutes

### Implementation Details

#### Part A: Quick Test Script

1. **Create test script** at `tests/test-weapon-systems.php`

2. **Test structure:**
   ```php
   <?php
   declare(strict_types=1);
   require_once __DIR__ . ''/../vendor/autoload.php'';
   
   use Mistralys\X4\Database\WeaponSystems\WeaponSystems;
   use Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems;
   use Mistralys\X4\Database\Weapons\WeaponDefs;
   
   echo "Testing Weapon Systems Collection..." . PHP_EOL . PHP_EOL;
   
   // Test 1: Load collection
   // Test 2: Verify all 8 systems exist
   // Test 3: Check labels and descriptions
   // Test 4: Test type checking methods
   // Test 5: Test filter methods
   // Test 6: Test integration with WeaponFinder
   // Test 7: Test validation (unknown system)
   ```

3. **Test cases:**

   **Test 1: Basic Loading**
   ```php
   $systems = WeaponSystems::getInstance();
   $all = $systems->getAll();
   assert(count($all) === 8, ''Should have 8 weapon systems'');
   echo "✓ Loaded 8 weapon systems" . PHP_EOL;
   ```

   **Test 2: Constants Work**
   ```php
   $shortRange = $systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
   assert($shortRange !== null, ''Short-range turret should exist'');
   assert($shortRange->getLabel() === ''Short-Range Turret'');
   echo "✓ Constants and IDs work correctly" . PHP_EOL;
   ```

   **Test 3: Labels and Descriptions**
   ```php
   foreach ($all as $system) {
       assert(!empty($system->getLabel()), ''Label should not be empty'');
       assert(!empty($system->getDescription()), ''Description should not be empty'');
   }
   echo "✓ All systems have labels and descriptions" . PHP_EOL;
   ```

   **Test 4: Type Checking**
   ```php
   $turretCount = count(array_filter($all, fn($s) => $s->isTurret()));
   $missileCount = count(array_filter($all, fn($s) => $s->isMissile()));
   $weaponCount = count(array_filter($all, fn($s) => $s->isStandardWeapon()));
   
   assert($turretCount === 3, ''Should have 3 turret systems'');
   assert($missileCount === 3, ''Should have 3 missile systems (2 missile + 1 torpedo)'');
   assert($weaponCount === 2, ''Should have 2 standard weapon systems'');
   echo "✓ Type checking methods work" . PHP_EOL;
   ```

   **Test 5: Filter Methods**
   ```php
   $turrets = $systems->getTurretSystems();
   $missiles = $systems->getMissileSystems();
   $weapons = $systems->getStandardWeaponSystems();
   
   assert(count($turrets) === 3);
   assert(count($missiles) === 3);
   assert(count($weapons) === 2);
   echo "✓ Filter methods work correctly" . PHP_EOL;
   ```

   **Test 6: Integration with WeaponFinder**
   ```php
   $weaponDefs = WeaponDefs::getInstance();
   $shortRangeWeapons = $weaponDefs->findWeapons()
       ->selectWeaponSystem(KnownWeaponSystems::TURRET_SHORTRANGE)
       ->getAll();
   assert(count($shortRangeWeapons) > 0, ''Should find short-range weapons'');
   echo sprintf("✓ Integration works: found %d short-range turrets" . PHP_EOL, count($shortRangeWeapons));
   ```

   **Test 7: Exception Handling**
   ```php
   try {
       $systems->requireKnownSystem(''fake_system_type'');
       assert(false, ''Should have thrown exception'');
   } catch (\Mistralys\X4\Database\Weapons\WeaponException $e) {
       assert($e->getCode() === \Mistralys\X4\Database\Weapons\WeaponException::ERROR_UNKNOWN_WEAPON_SYSTEM);
       echo "✓ Exception thrown for unknown weapon system" . PHP_EOL;
   }
   ```

#### Part B: PHPUnit Test Class

1. **Create PHPUnit test** at `tests/X4Tests/Suites/WeaponSystemsTest.php`

2. **Test class structure:**
   ```php
   <?php
   declare(strict_types=1);
   
   namespace X4Tests\Suites;
   
   use Mistralys\X4\Database\WeaponSystems\WeaponSystems;
   use Mistralys\X4\Database\WeaponSystems\WeaponSystem;
   use Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems;
   use Mistralys\X4\Database\Weapons\WeaponDefs;
   use Mistralys\X4\Database\Weapons\WeaponException;
   use PHPUnit\Framework\TestCase;
   
   class WeaponSystemsTest extends TestCase
   {
       private WeaponSystems $systems;
       
       protected function setUp(): void
       {
           $this->systems = WeaponSystems::getInstance();
       }
       
       public function test_collectionLoads(): void
       {
           $this->assertCount(8, $this->systems->getAll());
       }
       
       public function test_constantsExist(): void
       {
           $this->assertSame('turret_shortrange', KnownWeaponSystems::TURRET_SHORTRANGE);
           $this->assertSame('weapon_standard', KnownWeaponSystems::WEAPON_STANDARD);
           $this->assertSame('torpedo', KnownWeaponSystems::TORPEDO);
       }
       
       public function test_getByIDReturnsCorrectSystem(): void
       {
           $system = $this->systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
           
           $this->assertInstanceOf(WeaponSystem::class, $system);
           $this->assertSame('turret_shortrange', $system->getID());
           $this->assertSame('Short-Range Turret', $system->getLabel());
       }
       
       public function test_allSystemsHaveLabelsAndDescriptions(): void
       {
           foreach ($this->systems->getAll() as $system) {
               $this->assertNotEmpty($system->getLabel());
               $this->assertNotEmpty($system->getDescription());
               $this->assertInstanceOf(WeaponSystem::class, $system);
           }
       }
       
       public function test_turretTypeCheckingWorks(): void
       {
           $shortRange = $this->systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
           $standard = $this->systems->getByID(KnownWeaponSystems::WEAPON_STANDARD);
           
           $this->assertTrue($shortRange->isTurret());
           $this->assertFalse($shortRange->isMissile());
           $this->assertFalse($shortRange->isStandardWeapon());
           
           $this->assertFalse($standard->isTurret());
           $this->assertTrue($standard->isStandardWeapon());
       }
       
       public function test_missileTypeCheckingWorks(): void
       {
           $guided = $this->systems->getByID(KnownWeaponSystems::MISSILE_GUIDED);
           $torpedo = $this->systems->getByID(KnownWeaponSystems::TORPEDO);
           
           $this->assertTrue($guided->isMissile());
           $this->assertTrue($torpedo->isMissile());
           $this->assertFalse($guided->isTurret());
       }
       
       public function test_getTurretSystemsReturnsThreeSystems(): void
       {
           $turrets = $this->systems->getTurretSystems();
           
           $this->assertCount(3, $turrets);
           foreach ($turrets as $turret) {
               $this->assertTrue($turret->isTurret());
           }
       }
       
       public function test_getMissileSystemsReturnsThreeSystems(): void
       {
           $missiles = $this->systems->getMissileSystems();
           
           $this->assertCount(3, $missiles);
           foreach ($missiles as $missile) {
               $this->assertTrue($missile->isMissile());
           }
       }
       
       public function test_getStandardWeaponSystemsReturnsTwoSystems(): void
       {
           $weapons = $this->systems->getStandardWeaponSystems();
           
           $this->assertCount(2, $weapons);
           foreach ($weapons as $weapon) {
               $this->assertTrue($weapon->isStandardWeapon());
           }
       }
       
       public function test_isKnownSystemReturnsTrueForValidSystems(): void
       {
           $this->assertTrue($this->systems->isKnownSystem('turret_shortrange'));
           $this->assertTrue($this->systems->isKnownSystem('weapon_mining'));
           $this->assertTrue($this->systems->isKnownSystem('torpedo'));
       }
       
       public function test_isKnownSystemReturnsFalseForInvalidSystems(): void
       {
           $this->assertFalse($this->systems->isKnownSystem('fake_system'));
           $this->assertFalse($this->systems->isKnownSystem(''));
           $this->assertFalse($this->systems->isKnownSystem('unknown'));
       }
       
       public function test_requireKnownSystemThrowsExceptionForUnknownSystem(): void
       {
           $this->expectException(WeaponException::class);
           $this->expectExceptionCode(WeaponException::ERROR_UNKNOWN_WEAPON_SYSTEM);
           $this->expectExceptionMessageMatches('/Unknown weapon system type/');
           
           $this->systems->requireKnownSystem('fake_system_type');
       }
       
       public function test_requireKnownSystemDoesNotThrowForValidSystem(): void
       {
           $this->systems->requireKnownSystem(KnownWeaponSystems::TURRET_SHORTRANGE);
           $this->assertTrue(true); // If we get here, no exception was thrown
       }
       
       public function test_integrationWithWeaponFinder(): void
       {
           $weapons = WeaponDefs::getInstance()->findWeapons()
               ->selectWeaponSystem(KnownWeaponSystems::TURRET_SHORTRANGE)
               ->getAll();
           
           $this->assertGreaterThan(0, count($weapons));
           
           foreach ($weapons as $weapon) {
               $this->assertSame('turret_shortrange', $weapon->getWeaponSystem());
           }
       }
   }
   ```

3. **Test focuses:**
   - Collection loading and singleton
   - Constant definitions
   - Item retrieval by ID
   - Label and description completeness
   - Type checking methods (isTurret, isMissile, isStandardWeapon)
   - Filter methods (getTurretSystems, etc.)
   - Validation methods (isKnownSystem, requireKnownSystem)
   - Exception handling for unknown systems
   - Integration with WeaponFinder

### Verification Commands
```bash
# Quick test script
composer dump-autoload
php tests/test-weapon-systems.php

# Expected output:
# ✓ Loaded 8 weapon systems
# ✓ Constants and IDs work correctly
# ✓ All systems have labels and descriptions
# ✓ Type checking methods work
# ✓ Filter methods work correctly
# ✓ Integration works: found X short-range turrets
# ✓ Exception thrown for unknown weapon system
# ✓ All tests passed!

# PHPUnit tests
vendor/bin/phpunit tests/X4Tests/Suites/WeaponSystemsTest.php

# Expected output:
# PHPUnit 9.5.x
# ...............  15 / 15 (100%)
# OK (15 tests, XX assertions)
```

---

## 📦 Work Package 6: Update Manifest Documentation

**Files:**
- `docs/agents/project-manifest/file-tree.md`
- `docs/agents/project-manifest/tech-stack.md`
- `docs/agents/project-manifest/public-api.md`

**Dependencies:** Work Packages 1-5  
**Estimated Time:** 30 minutes

### Implementation Details

Per [AGENTS.md Change Mapping Table](../../AGENTS.md#change--document-mapping-table), adding a new Collection class requires updates to all three manifest documents.

#### 1. Update file-tree.md

**Location:** Search for `Database/` section (around line 100)

**Add under `Weapons/`:**
```markdown
│   │   ├── Weapons/
│   │   │   ├── BulletMacroExtractor.php
│   │   │   ├── WeaponDef.php
│   │   │   ├── WeaponDefs.php
│   │   │   ├── WeaponException.php
│   │   │   ├── WeaponFinder.php
│   │   │   ├── WeaponMacroExtractor.php
│   │   │   └── WeaponsExtractor.php
│   │   ├── WeaponSystems/              ← ADD THIS SECTION
│   │   │   ├── KnownWeaponSystems.php
│   │   │   ├── WeaponSystem.php
│   │   │   └── WeaponSystems.php
```

#### 2. Update tech-stack.md

**Location:** Find "Collection-Item Pattern" section (around line 50)

**Add to "Collections in the Project" list:**
```markdown
#### Collections in the Project
- **Factions** (`FactionDefs`) → `FactionDef`
- **Wares** (`WareDefs`) → `WareDef`
- **Ships** (`ShipDefs`) → `ShipDef`
- **Modules** (`ModuleDefs`) → `ModuleDef`
- **Blueprints** (`BlueprintDefs`) → `BlueprintDef`
- **Weapons** (`WeaponDefs`) → `WeaponDef`
- **Slot Types** (`SlotTypes`) → `SlotType`
- **Weapon Systems** (`WeaponSystems`) → `WeaponSystem` ← ADD THIS
```

**Add description in relevant section:**
```markdown
### Weapon Systems Collection
Small, hardcoded collection of weapon system types (turret_shortrange, weapon_standard, etc.).
Provides type-safe constants and human-readable labels for UI display.
Follows SlotTypes pattern - no JSON file needed.
```

#### 3. Update public-api.md

**Location:** Add new section under `Database\WeaponSystems` namespace (alphabetically after `Weapons`)

**Add complete namespace documentation:**
```markdown
### Database\WeaponSystems

#### WeaponSystems (Collection)
**Singleton collection of all weapon system types.**

` + '```php' + `
// Singleton access
public static function getInstance(): WeaponSystems

// Collection info
public function getCollectionName(): string  // Returns ''Weapon Systems''
public function getCollectionDescription(): string
public function getDefaultID(): string  // Returns ''weapon_standard''

// Item access (inherited from BaseStringPrimaryCollection)
public function getAll(): array<WeaponSystem>
public function getByID(string $id): ?WeaponSystem
public function idExists(string $id): bool
public function getIDs(): array<string>

// Validation
public function isKnownSystem(string $systemID): bool
public function requireKnownSystem(string $systemID): void  // throws WeaponException

// Filtering
public function getTurretSystems(): array<WeaponSystem>
public function getMissileSystems(): array<WeaponSystem>
public function getStandardWeaponSystems(): array<WeaponSystem>
` + '```' + `

#### WeaponSystem (Item)
**Represents a single weapon system type with metadata.**

` + '```php' + `
// Identification
public function getID(): string
public function getVariantID(): VariantID
public function getLabel(): string
public function getDescription(): string

// Type checking
public function isTurret(): bool
public function isMissile(): bool
public function isStandardWeapon(): bool
` + '```' + `

#### KnownWeaponSystems (Constants)
**Type-safe constants for weapon system IDs.**

` + '```php' + `
public const TURRET_SHORTRANGE = ''turret_shortrange'';
public const TURRET_MIDRANGE = ''turret_midrange'';
public const TURRET_LONGRANGE = ''turret_longrange'';
public const WEAPON_STANDARD = ''weapon_standard'';
public const WEAPON_MINING = ''weapon_mining'';
public const MISSILE_DUMBFIRE = ''missile_dumbfire'';
public const MISSILE_GUIDED = ''missile_guided'';
public const TORPEDO = ''torpedo'';
` + '```' + `
```

**Update WeaponException documentation:**
```markdown
// Add to existing WeaponException constants:
public const ERROR_UNKNOWN_WEAPON_SYSTEM = 12005;
```

### Verification
- All three manifest files updated
- Documentation follows existing formatting
- No broken markdown links
- "Last Updated" dates updated in document headers

---

## 🧪 Final Integration Test

**Run after completing all work packages**

```bash
# 1. Regenerate autoloader
composer dump-autoload

# 2. Run weapon systems tests
php tests/test-weapon-systems.php

# 3. Verify no compilation errors
composer phpstan

# 4. Run weapon extraction (should validate systems)
composer extract-weapons

# 5. Test with existing weapon queries
php -r "require ''vendor/autoload.php'';
use Mistralys\X4\Database\Weapons\WeaponDefs;
use Mistralys\X4\Database\WeaponSystems\WeaponSystems;
use Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems;

// Test collection loads
\$systems = WeaponSystems::getInstance();
echo ''Loaded '' . count(\$systems->getAll()) . '' weapon systems'' . PHP_EOL;

// Test integration
\$weapons = WeaponDefs::getInstance()->findWeapons()
    ->selectWeaponSystem(KnownWeaponSystems::TURRET_SHORTRANGE)
    ->getAll();
echo ''Found '' . count(\$weapons) . '' short-range turrets'' . PHP_EOL;

// Test labels
\$system = \$systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
echo ''Label: '' . \$system->getLabel() . PHP_EOL;
"

# Expected output:
# Loaded 8 weapon systems
# Found [X] short-range turrets
# Label: Short-Range Turret
```

---

## 📚 Reference Documentation

### Key Files to Reference During Implementation

1. **Pattern Examples:**
   - [SlotTypes.php](../../../src/X4/Database/SlotTypes/SlotTypes.php) - Collection pattern
   - [SlotType.php](../../../src/X4/Database/SlotTypes/SlotType.php) - Item pattern
   - [KnownSlotTypes.php](../../../src/X4/Database/SlotTypes/KnownSlotTypes.php) - Constants pattern

2. **Integration Points:**
   - [WeaponDef.php](../../../src/X4/Database/Weapons/WeaponDef.php) - Line 370: `getWeaponSystem()`
   - [WeaponFinder.php](../../../src/X4/Database/Weapons/WeaponFinder.php) - Line 127: `selectWeaponSystem()`
   - [WeaponDefs.php](../../../src/X4/Database/Weapons/WeaponDefs.php) - Line 105: `getByWeaponSystem()`
   - [WeaponsExtractor.php](../../../src/X4/Database/Weapons/WeaponsExtractor.php) - Extraction logic

3. **Constraint Documents:**
   - [constraints.md](../project-manifest/constraints.md) - Naming conventions, Collection-Item interface requirements
   - [tech-stack.md](../project-manifest/tech-stack.md) - Collection-Item pattern details
   - [AGENTS.md](../../AGENTS.md) - Manifest maintenance rules

### Usage Examples After Implementation

```php
// Type-safe constant usage
use Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems;
use Mistralys\X4\Database\Weapons\WeaponDefs;

$weapons = WeaponDefs::getInstance()->findWeapons()
    ->selectWeaponSystem(KnownWeaponSystems::TURRET_SHORTRANGE)
    ->selectMinDPS(1000.0)
    ->getAll();

// Get human-readable labels for UI
use Mistralys\X4\Database\WeaponSystems\WeaponSystems;

$systems = WeaponSystems::getInstance();
foreach ($systems->getTurretSystems() as $system) {
    echo $system->getLabel() . '': '' . $system->getDescription() . PHP_EOL;
}

// Validate weapon system IDs
$systems->requireKnownSystem(''turret_shortrange'');  // OK
$systems->requireKnownSystem(''fake_system'');        // Throws WeaponException
```

---

## ✅ Completion Checklist

### Work Package 1: Constants Class
- [ ] File created at `src/X4/Database/WeaponSystems/KnownWeaponSystems.php`
- [ ] All 8 constants defined
- [ ] File compiles without errors
- [ ] Run `composer dump-autoload`

### Work Package 2: Item Class
- [ ] File created at `src/X4/Database/WeaponSystems/WeaponSystem.php`
- [ ] Implements `CollectionItemInterface` with `CollectionItemTrait`
- [ ] All required methods implemented
- [ ] Helper methods added (`isTurret()`, `isMissile()`, `isStandardWeapon()`)
- [ ] File compiles without errors

### Work Package 3: Collection Class
- [ ] File created at `src/X4/Database/WeaponSystems/WeaponSystems.php`
- [ ] Singleton pattern implemented
- [ ] `loadInitialData()` has all 8 systems with labels and descriptions
- [ ] Filter methods implemented
- [ ] File compiles without errors

### Work Package 4: Exception Handling
- [ ] Constant added to `WeaponException`
- [ ] `isKnownSystem()` and `requireKnownSystem()` added to `WeaponSystems`
- [ ] Validation added to `WeaponsExtractor` or `WeaponDef`
- [ ] Manual test: fake system ID throws correct exception

### Work Package 5: Tests
- [ ] Quick test script created at `tests/test-weapon-systems.php`
- [ ] All 7 quick test cases implemented
- [ ] PHPUnit test created at `tests/X4Tests/Suites/WeaponSystemsTest.php`
- [ ] All 15 PHPUnit test methods implemented
- [ ] Quick tests pass: `php tests/test-weapon-systems.php`
- [ ] PHPUnit tests pass: `vendor/bin/phpunit tests/X4Tests/Suites/WeaponSystemsTest.php`

### Work Package 6: Manifest Updates
- [ ] `file-tree.md` updated with `WeaponSystems/` folder
- [ ] `tech-stack.md` updated with WeaponSystems collection
- [ ] `public-api.md` updated with complete namespace documentation
- [ ] All "Last Updated" dates updated

### Final Integration
- [ ] `composer dump-autoload` completes successfully
- [ ] `composer phpstan` passes (no errors)
- [ ] `composer extract-weapons` succeeds
- [ ] Integration test script runs successfully
- [ ] All tests pass

---

## 🎯 Success Criteria

Implementation is complete when:
1. ✅ All 3 classes created and compile without errors
2. ✅ All 8 weapon systems load with labels and descriptions
3. ✅ Type-safe constants work in IDE (autocomplete)
4. ✅ Integration with `WeaponFinder` works seamlessly
5. ✅ Unknown weapon systems throw `WeaponException` with helpful message
6. ✅ All tests pass
7. ✅ All manifest documents updated per AGENTS.md requirements
8. ✅ No regressions in existing weapon functionality

---

**Estimated Total Time:** 2-3 hours  
**Files Created:** 5 (3 classes + 2 test files)  
**Files Modified:** 4 (1 exception class + 1 extractor + 3 manifest docs)  
**Lines of Code:** ~600
