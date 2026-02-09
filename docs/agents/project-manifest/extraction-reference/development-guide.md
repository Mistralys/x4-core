# Extractor Development Guide

> **Module:** Project Manifest  
> **Document Type:** Development Guide  
> **Audience:** AI Agents & Developers  
> **Last Updated:** February 9, 2026

---

## 📋 Overview

### Purpose

This guide provides a complete, step-by-step process for creating new data extractors in the X4 Core system. Following this guide ensures:

- **Consistency:** New extractors match existing patterns
- **Quality:** All required components created and tested
- **Integration:** Proper dependencies and build order
- **Documentation:** Manifest stays current

### Development Phases

| Phase | Focus | Duration | Output |
|-------|-------|----------|--------|
| **1. Planning** | Architecture & dependencies | 30-60 min | Design document, JSON schema |
| **2. Implementation** | Code creation | 2-4 hours | Working extractor classes |
| **3. Testing** | Validation | 1-2 hours | Test suite passing |
| **4. Documentation** | Manifest updates | 30-60 min | Updated manifest docs |
| **5. Verification** | Quality checks | 30 min | Production-ready extractor |

**Total Time Estimate:**
- Simple extractor (Single-Phase): 3-5 hours
- Complex extractor (Two-Phase): 5-8 hours

### Success Criteria

A complete extractor implementation includes:
- ✅ Extractor class(es) following established patterns
- ✅ Collection classes (Defs, Def, Finder)
- ✅ Integration with DatabaseBuilder
- ✅ JSON data file created
- ✅ Test suite with 100% coverage
- ✅ All manifest documents updated
- ✅ No static analysis errors
- ✅ Build succeeds with data populated

---

## 📐 Phase 1: Planning

### Step 1.1: Identify XML Data Sources

**Goal:** Determine what game data files you need to extract from.

#### Discovery Process

1. **Search extraction output:**
   ```bash
   # Find relevant XML files
   Get-ChildItem -Path "F:\...\output\" -Recurse -Filter "*turret*.xml"
   ```

2. **Check libraries /** folder:**
   ```
   output/
   └── vanilla/
       └── libraries/
           ├── wares.xml         ← Contains ware definitions
           ├── modules.xml       ← Contains module definitions
           └── baskets.xml       ← Contains baskets (not used currently)
   ```

3. **Check index/ folder:**
   ```
   output/
   └── vanilla/
       └── index/
           └── macros.xml        ← Lists all macro files
   ```

4. **Check assets/structures/macros/ folder:**
   ```
   output/
   └── vanilla/
       └── assets/
           └── structures/
               └── macros/
                   ├── turret_arg_m_beam_01_mk1_macro.xml
                   ├── shield_gen_m_standard_01_mk1_macro.xml
                   └── ... (thousands of macro files)
   ```

#### Common Data Source Patterns

| Data Type | Primary Source | Secondary Source | Pattern |
|-----------|---------------|------------------|---------|
| Equipment | `libraries/wares.xml` | `assets/*/macros/*.xml` | Two-Phase |
| Ships | `libraries/wares.xml` | `assets/*/macros/*.xml` | Two-Phase |
| Factions | `libraries/factions.xml` | None | Single-Phase |
| Wares | `libraries/wares.xml` | None | Single-Phase |
| Blueprints | `libraries/wares.xml` | None | Single-Phase (derived) |

#### Example: Turrets

**Primary Source:** `libraries/wares.xml`
```xml
<ware id="turret_arg_m_beam_01_mk1" name="Argon Beam Turret" group="turrets">
  <price min="50000" max="75000" />
  <production>
    <primary>
      <ware ware="turret_arg_m_beam_01_mk1" amount="1" />
    </primary>
  </production>
</ware>
```

**Secondary Source:** `assets/structures/macros/turret_arg_m_beam_01_mk1_macro.xml`
```xml
<macro name="turret_arg_m_beam_01_mk1_macro">
  <component>
    <turret rotation="45" />
    <weapon>
      <bullet damage="500" range="3000" />
    </weapon>
  </component>
</macro>
```

**Conclusion:** Turrets require **Two-Phase extraction** (ware + macro).

---

### Step 1.2: Identify Dependencies

**Goal:** Determine what other collections this extractor depends on.

#### Dependency Analysis

**Ask these questions:**

1. **Does it use MacroIndex?**
   - YES: Depends on `MacroIndex` (must be built first)
   - Applies to: All equipment extractors (engines, shields, weapons, turrets)

2. **Does it use Wares collection?**
   - YES: Depends on `WareDefs` (must be built first)
   - Applies to: All equipment extractors, blueprints

3. **Does it reference other equipment?**
   - YES: Depends on that equipment collection
   - Example: Ships reference engines for compatibility

4. **Is it referenced by others?**
   - YES: Must be built before those collections
   - Example: Engines must be built before Ships

#### Dependency Chain Example

```
MacroIndex          (Foundation - no dependencies)
    ↓
WareDefs           (Uses MacroIndex)
    ↓
├─ EngineDefs      (Uses WareDefs, MacroIndex)
├─ ShieldDefs      (Uses WareDefs, MacroIndex)
├─ WeaponDefs      (Uses WareDefs, MacroIndex)
└─ TurretDefs      (Uses WareDefs, MacroIndex) ← NEW
    ↓
ShipDefs           (Uses WareDefs, MacroIndex, EngineDefs)
    ↓
BlueprintDefs      (Uses WareDefs, ShipDefs)
```

#### Build Order Determination

**For Turrets:**
- **Depends on:** MacroIndex, WareDefs
- **Build after:** WareDefs
- **Build before:** Ships (if ships reference turrets)

**DatabaseBuilder order:**
```php
$this->buildMacroIndex();  // 1
$this->buildDataSources(); // 2
$this->buildFactions();    // 3
$this->buildWares();       // 4
$this->buildEngines();     // 5
$this->buildShields();     // 6
$this->buildWeapons();     // 7
$this->buildTurrets();     // 8 ← NEW
$this->buildShips();       // 9
$this->buildBlueprints();  // 10
```

---

### Step 1.3: Design JSON Schema

**Goal:** Define the exact structure of the JSON output.

#### Schema Design Principles

1. **Include only necessary data:** Don't extract unused properties
2. **Use consistent naming:** Match existing collection conventions
3. **Type safety:** Use appropriate types (float, int, bool, string, array)
4. **Data source tracking:** Always include `dataSource` field
5. **Unique identifiers:** Always include `id` field

#### Example: Turrets JSON Schema

```json
{
  "$schema": "http://json-schema.org/draft-07/schema#",
  "type": "array",
  "items": {
    "type": "object",
    "required": ["id", "name", "size", "dataSource"],
    "properties": {
      "id": {
        "type": "string",
        "description": "Unique turret ID (ware ID)",
        "example": "turret_arg_m_beam_01_mk1"
      },
      "name": {
        "type": "string",
        "description": "Localized turret name",
        "example": "Argon Beam Turret Mk1"
      },
      "size": {
        "type": "string",
        "enum": ["s", "m", "l"],
        "description": "Turret size category"
      },
      "rotationSpeed": {
        "type": "number",
        "description": "Rotation speed in degrees per second",
        "example": 45.0
      },
      "damage": {
        "type": "number",
        "description": "Damage per shot",
        "example": 500.0
      },
      "range": {
        "type": "number",
        "description": "Maximum effective range",
        "example": 3000.0
      },
      "reloadTime": {
        "type": "number",
        "description": "Time between shots in seconds",
        "example": 0.5
      },
      "dataSource": {
        "type": "string",
        "description": "DLC that provides this turret",
        "example": "vanilla"
      }
    }
  }
}
```

#### Example Output

```json
[
  {
    "id": "turret_arg_m_beam_01_mk1",
    "name": "Argon Beam Turret Mk1",
    "size": "m",
    "rotationSpeed": 45.0,
    "damage": 500.0,
    "range": 3000.0,
    "reloadTime": 0.5,
    "dataSource": "vanilla"
  },
  {
    "id": "turret_par_l_plasma_01_mk1",
    "name": "Paranid Plasma Turret Mk1",
    "size": "l",
    "rotationSpeed": 30.0,
    "damage": 800.0,
    "range": 3500.0,
    "reloadTime": 0.8,
    "dataSource": "vanilla"
  }
]
```

---

### Step 1.4: Choose Extractor Pattern

**Goal:** Determine which extraction pattern to use.

#### Pattern Decision Tree

```
Does the data require reading macro XML files?
│
├─ YES: Is the data complex with multiple properties?
│   │
│   ├─ YES: Use Two-Phase Pattern
│   │        (Main Extractor + Macro Extractor)
│   │        Examples: Engines, Shields, Weapons, Turrets
│   │
│   └─ NO:  Use Single-Phase with DOM Helper
│            Example: Simple library file parsing
│
└─ NO: Use Single-Phase Pattern
        Examples: Factions, DataSources, basic Wares
```

#### Pattern Comparison

| Pattern | Characteristics | Use When | Examples |
|---------|----------------|----------|----------|
| **Single-Phase** | One extractor class, no macro files | Data comes from single XML source | Factions, Wares, DataSources |
| **Two-Phase** | Main extractor + Macro extractor | Equipment requiring macro properties | Engines, Shields, Weapons, Turrets |
| **Derived** | Processes existing collection data | No XML parsing needed | Blueprints (derives from Wares) |

#### Decision: Turrets

**Characteristics:**
- ✅ Requires macro XML files (for rotation, damage, range)
- ✅ Complex properties (multiple XML nodes)
- ✅ Based on wares collection

**Pattern:** Two-Phase Extraction

**Implementation:**
- `TurretsExtractor` (Main) - Filters wares, orchestrates extraction
- `TurretMacroExtractor` (Macro) - Extracts properties from macro XML

---

## 🔨 Phase 2: Implementation

### Step 2.1: Create Main Extractor Class

**Location:** `src/X4/Database/Turrets/TurretsExtractor.php`

```php
<?php
/**
 * Turrets Extractor
 * 
 * Extracts turret data from wares collection and macro XML files.
 * This is a Two-Phase extractor.
 * 
 * @package X4\Database\Turrets
 */

declare(strict_types=1);

namespace X4\Database\Turrets;

use X4\Database\BaseExtractor;
use X4\Database\Wares\WareDefs;
use X4\X4Exception;

class TurretsExtractor extends BaseExtractor
{
    /**
     * Output JSON file name
     */
    public const OUTPUT_FILE = 'turrets.json';
    
    /**
     * Extract all turret data
     * 
     * Phase 1: Filter wares collection for turrets
     * Phase 2: Extract properties from macro XML files
     * 
     * @return array<int, array<string, mixed>> Array of turret data
     * @throws X4Exception If wares collection not built
     */
    public function extract(): array
    {
        $this->log('Starting turret extraction...');
        
        // Verify dependencies
        if (!WareDefs::getInstance()->isBuilt()) {
            throw new X4Exception('Wares collection must be built before turrets');
        }
        
        $result = [];
        $processedCount = 0;
        $errorCount = 0;
        
        // Phase 1: Filter wares for turrets
        $turretWares = WareDefs::getInstance()
            ->findWares()
            ->selectGroup('turrets')
            ->getAll();
        
        $this->log(sprintf('Found %d turret wares', count($turretWares)));
        
        // Phase 2: Extract properties from each turret
        foreach ($turretWares as $ware) {
            try {
                $macroExtractor = new TurretMacroExtractor($ware);
                $turretData = $macroExtractor->extract();
                
                $result[] = $turretData;
                $processedCount++;
                
                if ($processedCount % 10 === 0) {
                    $this->log(sprintf('Processed %d turrets...', $processedCount));
                }
                
            } catch (\Exception $e) {
                $errorCount++;
                $this->logWarning(sprintf(
                    'Failed to extract turret %s: %s',
                    $ware->getID(),
                    $e->getMessage()
                ));
                continue;  // Skip this turret, continue with others
            }
        }
        
        // Summary
        $this->log(sprintf(
            'Turret extraction complete: %d successful, %d errors',
            $processedCount,
            $errorCount
        ));
        
        return $result;
    }
}
```

**Key Implementation Points:**

1. **Extends BaseExtractor:** Provides logging, file writing capabilities
2. **OUTPUT_FILE constant:** Defines JSON output filename
3. **Dependency checking:** Verifies WareDefs built before proceeding
4. **Error handling:** Per-item error handling (doesn't stop entire extraction)
5. **Progress logging:** Reports progress every 10 items
6. **Summary logging:** Reports final statistics
7. **Type safety:** Uses type hints and PHPDoc annotations

---

### Step 2.2: Create Macro Extractor Class

**Location:** `src/X4/Database/Turrets/TurretMacroExtractor.php`

```php
<?php
/**
 * Turret Macro Extractor
 * 
 * Extracts turret properties from macro XML files.
 * This is the Phase 2 component of the Two-Phase extraction pattern.
 * 
 * @package X4\Database\Turrets
 */

declare(strict_types=1);

namespace X4\Database\Turrets;

use X4\Database\Wares\WareDef;
use X4\Database\MacroIndex\MacroIndex;
use Mistralys\X4\XMLHelper;
use Mistralys\X4\DOM\DOMExtended;
use X4\X4Exception;

class TurretMacroExtractor
{
    private WareDef $ware;
    
    /**
     * Constructor
     * 
     * @param WareDef $ware The ware definition to extract turret data for
     */
    public function __construct(WareDef $ware)
    {
        $this->ware = $ware;
    }
    
    /**
     * Extract turret data from macro XML
     * 
     * @return array<string, mixed> Turret data array
     * @throws X4Exception If macro not found or XML invalid
     */
    public function extract(): array
    {
        // Step 1: Resolve macro
        $macroName = $this->resolveMacroName();
        $macro = MacroIndex::getInstance()->getByID($macroName);
        
        if ($macro === null) {
            throw new X4Exception(sprintf(
                'Macro not found for turret: %s (expected: %s)',
                $this->ware->getID(),
                $macroName
            ));
        }
        
        // Step 2: Load DOM
        $dom = XMLHelper::createDOMExtended($macro->getFilePath());
        
        // Step 3: Extract all properties
        return [
            'id' => $this->ware->getID(),
            'name' => $this->ware->getName(),
            'size' => $this->extractSize(),
            'rotationSpeed' => $this->extractRotationSpeed($dom),
            'damage' => $this->extractDamage($dom),
            'range' => $this->extractRange($dom),
            'reloadTime' => $this->extractReloadTime($dom),
            'dataSource' => $macro->getDataSource()
        ];
    }
    
    /**
     * Resolve macro name from ware ID
     * 
     * Convention: ware ID + '_macro' suffix
     * Example: 'turret_arg_m_beam_01_mk1' → 'turret_arg_m_beam_01_mk1_macro'
     * 
     * @return string Macro name
     */
    private function resolveMacroName(): string
    {
        return $this->ware->getID() . '_macro';
    }
    
    /**
     * Extract turret size from ware ID
     * 
     * Turret ID format: turret_{race}_{SIZE}_{type}_{variant}
     * Example: turret_arg_m_beam_01_mk1 → 'm'
     * 
     * @return string Size code (s, m, l)
     * @throws X4Exception If size cannot be determined
     */
    private function extractSize(): string
    {
        $parts = explode('_', $this->ware->getID());
        
        if (count($parts) < 3) {
            throw new X4Exception(sprintf(
                'Cannot determine size from turret ID: %s',
                $this->ware->getID()
            ));
        }
        
        $size = $parts[2];
        
        // Validate size
        if (!in_array($size, ['s', 'm', 'l'], true)) {
            throw new X4Exception(sprintf(
                'Invalid turret size "%s" in ID: %s',
                $size,
                $this->ware->getID()
            ));
        }
        
        return $size;
    }
    
    /**
     * Extract rotation speed from turret component
     * 
     * XML structure:
     * <component>
     *   <turret rotation="45" />
     * </component>
     * 
     * @param DOMExtended $dom Root DOM element
     * @return float Rotation speed in degrees per second
     */
    private function extractRotationSpeed(DOMExtended $dom): float
    {
        $turretNode = $dom->byTagName('turret')->getFirst();
        
        if ($turretNode === null) {
            // Some turrets may be fixed (no rotation)
            return 0.0;
        }
        
        $rotation = $turretNode->getAttribute('rotation');
        
        if ($rotation === null || !is_numeric($rotation)) {
            return 0.0;
        }
        
        return (float)$rotation;
    }
    
    /**
     * Extract damage from weapon bullet
     * 
     * XML structure:
     * <weapon>
     *   <bullet damage="500" />
     * </weapon>
     * 
     * @param DOMExtended $dom Root DOM element
     * @return float Damage per shot
     * @throws X4Exception If damage property not found
     */
    private function extractDamage(DOMExtended $dom): float
    {
        $weaponNode = $dom->byTagName('weapon')->requireFirst();
        $bulletNode = $weaponNode->byTagName('bullet')->requireFirst();
        
        $damage = $bulletNode->getAttribute('damage');
        
        if ($damage === null || !is_numeric($damage)) {
            throw new X4Exception(sprintf(
                'Invalid or missing damage attribute in turret: %s',
                $this->ware->getID()
            ));
        }
        
        return (float)$damage;
    }
    
    /**
     * Extract effective range from weapon
     * 
     * XML structure:
     * <weapon range="3000" />
     * 
     * @param DOMExtended $dom Root DOM element
     * @return float Maximum range
     * @throws X4Exception If range property not found
     */
    private function extractRange(DOMExtended $dom): float
    {
        $weaponNode = $dom->byTagName('weapon')->requireFirst();
        
        $range = $weaponNode->getAttribute('range');
        
        if ($range === null || !is_numeric($range)) {
            throw new X4Exception(sprintf(
                'Invalid or missing range attribute in turret: %s',
                $this->ware->getID()
            ));
        }
        
        return (float)$range;
    }
    
    /**
     * Extract reload time from weapon
     * 
     * XML structure:
     * <weapon reload="0.5" />
     * 
     * @param DOMExtended $dom Root DOM element
     * @return float Reload time in seconds
     */
    private function extractReloadTime(DOMExtended $dom): float
    {
        $weaponNode = $dom->byTagName('weapon')->getFirst();
        
        if ($weaponNode === null) {
            return 1.0;  // Default reload time
        }
        
        $reload = $weaponNode->getAttribute('reload');
        
        if ($reload === null || !is_numeric($reload)) {
            return 1.0;  // Default reload time
        }
        
        return (float)$reload;
    }
}
```

**Key Implementation Points:**

1. **Constructor injection:** Takes WareDef as dependency
2. **Macro resolution:** Converts ware ID to macro name
3. **DOM navigation:** Uses DOMExtended fluent API
4. **Required vs optional:** Uses `requireFirst()` for required, `getFirst()` for optional
5. **Error context:** Detailed error messages with turret ID
6. **Type validation:** Checks numeric values before conversion
7. **Defaults:** Provides sensible defaults for optional properties
8. **Documentation:** Complete PHPDoc for all methods

---

### Step 2.3: Create Collection Classes

#### TurretDefs (Singleton Collection)

**Location:** `src/X4/Database/Turrets/TurretDefs.php`

```php
<?php
/**
 * Turret Definitions Collection
 * 
 * Singleton collection of all turrets in the game.
 * Provides access to turret data and filtering capabilities.
 * 
 * @package X4\Database\Turrets
 */

declare(strict_types=1);

namespace X4\Database\Turrets;

use X4\Database\Core\BaseCollection;

class TurretDefs extends BaseCollection
{
    /**
     * Singleton instance
     */
    protected static ?self $instance = null;
    
    /**
     * Get singleton instance
     * 
     * @return self Singleton instance
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Reset singleton instance (for testing)
     * 
     * @internal
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
    
    /**
     * Get JSON data file name
     * 
     * @return string Filename relative to data/ directory
     */
    protected function getJSONFile(): string
    {
        return 'turrets.json';
    }
    
    /**
     * Create turret item from JSON data
     * 
     * @param array<string, mixed> $data Raw JSON data
     * @return TurretDef Turret definition instance
     */
    protected function createItem(array $data): TurretDef
    {
        return new TurretDef($data);
    }
    
    /**
     * Get turret by ID
     * 
     * @param string $id Turret ID
     * @return TurretDef|null Turret definition or null if not found
     */
    public function getByID(string $id): ?TurretDef
    {
        return parent::getByID($id);
    }
    
    /**
     * Get all turrets
     * 
     * @return TurretDef[] Array of turret definitions
     */
    public function getAll(): array
    {
        return parent::getAll();
    }
    
    /**
     * Create finder for filtering turrets
     * 
     * @return TurretFinder Finder instance
     */
    public function findTurrets(): TurretFinder
    {
        return new TurretFinder($this);
    }
}
```

---

#### TurretDef (Item)

**Location:** `src/X4/Database/Turrets/TurretDef.php`

```php
<?php
/**
 * Turret Definition
 * 
 * Represents a single turret with all its properties.
 * 
 * @package X4\Database\Turrets
 */

declare(strict_types=1);

namespace X4\Database\Turrets;

use X4\Database\Core\BaseItem;

class TurretDef extends BaseItem
{
    /**
     * Get turret ID
     * 
     * @return string Turret ID (ware ID)
     */
    public function getID(): string
    {
        return (string)$this->data['id'];
    }
    
    /**
     * Get turret name
     * 
     * @return string Localized name
     */
    public function getName(): string
    {
        return (string)$this->data['name'];
    }
    
    /**
     * Get turret size
     * 
     * @return string Size code (s, m, l)
     */
    public function getSize(): string
    {
        return (string)$this->data['size'];
    }
    
    /**
     * Get rotation speed
     * 
     * @return float Rotation speed in degrees per second
     */
    public function getRotationSpeed(): float
    {
        return (float)$this->data['rotationSpeed'];
    }
    
    /**
     * Get damage per shot
     * 
     * @return float Damage amount
     */
    public function getDamage(): float
    {
        return (float)$this->data['damage'];
    }
    
    /**
     * Get maximum range
     * 
     * @return float Range in meters
     */
    public function getRange(): float
    {
        return (float)$this->data['range'];
    }
    
    /**
     * Get reload time
     * 
     * @return float Reload time in seconds
     */
    public function getReloadTime(): float
    {
        return (float)$this->data['reloadTime'];
    }
    
    /**
     * Get data source
     * 
     * @return string Data source ID (e.g., 'vanilla', 'ego_dlc_terran')
     */
    public function getDataSource(): string
    {
        return (string)$this->data['dataSource'];
    }
    
    /**
     * Check if turret is fixed (no rotation)
     * 
     * @return bool True if fixed, false if rotates
     */
    public function isFixed(): bool
    {
        return $this->getRotationSpeed() === 0.0;
    }
    
    /**
     * Calculate DPS (Damage Per Second)
     * 
     * @return float DPS value
     */
    public function getDPS(): float
    {
        if ($this->getReloadTime() === 0.0) {
            return 0.0;
        }
        
        return $this->getDamage() / $this->getReloadTime();
    }
    
    /**
     * Check if turret is small
     * 
     * @return bool True if size is 's'
     */
    public function isSmall(): bool
    {
        return $this->getSize() === 's';
    }
    
    /**
     * Check if turret is medium
     * 
     * @return bool True if size is 'm'
     */
    public function isMedium(): bool
    {
        return $this->getSize() === 'm';
    }
    
    /**
     * Check if turret is large
     * 
     * @return bool True if size is 'l'
     */
    public function isLarge(): bool
    {
        return $this->getSize() === 'l';
    }
}
```

---

#### TurretFinder (Filtering)

**Location:** `src/X4/Database/Turrets/TurretFinder.php`

```php
<?php
/**
 * Turret Finder
 * 
 * Fluent interface for filtering and finding turrets.
 * 
 * @package X4\Database\Turrets
 */

declare(strict_types=1);

namespace X4\Database\Turrets;

use X4\Database\Core\BaseFinder;

class TurretFinder extends BaseFinder
{
    /**
     * Filter by turret size
     * 
     * @param string $size Size code (s, m, l)
     * @return self Fluent interface
     */
    public function selectSize(string $size): self
    {
        return $this->addFilter(function(TurretDef $turret) use ($size): bool {
            return $turret->getSize() === $size;
        });
    }
    
    /**
     * Filter by minimum damage
     * 
     * @param float $minDamage Minimum damage value
     * @return self Fluent interface
     */
    public function selectMinDamage(float $minDamage): self
    {
        return $this->addFilter(function(TurretDef $turret) use ($minDamage): bool {
            return $turret->getDamage() >= $minDamage;
        });
    }
    
    /**
     * Filter by maximum damage
     * 
     * @param float $maxDamage Maximum damage value
     * @return self Fluent interface
     */
    public function selectMaxDamage(float $maxDamage): self
    {
        return $this->addFilter(function(TurretDef $turret) use ($maxDamage): bool {
            return $turret->getDamage() <= $maxDamage;
        });
    }
    
    /**
     * Filter by minimum range
     * 
     * @param float $minRange Minimum range value
     * @return self Fluent interface
     */
    public function selectMinRange(float $minRange): self
    {
        return $this->addFilter(function(TurretDef $turret) use ($minRange): bool {
            return $turret->getRange() >= $minRange;
        });
    }
    
    /**
     * Filter by minimum rotation speed
     * 
     * @param float $minSpeed Minimum rotation speed
     * @return self Fluent interface
     */
    public function selectMinRotationSpeed(float $minSpeed): self
    {
        return $this->addFilter(function(TurretDef $turret) use ($minSpeed): bool {
            return $turret->getRotationSpeed() >= $minSpeed;
        });
    }
    
    /**
     * Filter by minimum DPS
     * 
     * @param float $minDPS Minimum DPS value
     * @return self Fluent interface
     */
    public function selectMinDPS(float $minDPS): self
    {
        return $this->addFilter(function(TurretDef $turret) use ($minDPS): bool {
            return $turret->getDPS() >= $minDPS;
        });
    }
    
    /**
     * Filter by data source
     * 
     * @param string $dataSource Data source ID
     * @return self Fluent interface
     */
    public function selectDataSource(string $dataSource): self
    {
        return $this->addFilter(function(TurretDef $turret) use ($dataSource): bool {
            return $turret->getDataSource() === $dataSource;
        });
    }
    
    /**
     * Filter only rotating turrets (exclude fixed)
     * 
     * @return self Fluent interface
     */
    public function selectRotating(): self
    {
        return $this->addFilter(function(TurretDef $turret): bool {
            return !$turret->isFixed();
        });
    }
    
    /**
     * Filter only fixed turrets (no rotation)
     * 
     * @return self Fluent interface
     */
    public function selectFixed(): self
    {
        return $this->addFilter(function(TurretDef $turret): bool {
            return $turret->isFixed();
        });
    }
    
    /**
     * Get all filtered turrets
     * 
     * @return TurretDef[] Array of turret definitions
     */
    public function getAll(): array
    {
        return parent::getAll();
    }
    
    /**
     * Get first matching turret
     * 
     * @return TurretDef|null First turret or null if none found
     */
    public function getFirst(): ?TurretDef
    {
        return parent::getFirst();
    }
}
```

---

### Step 2.4: Integrate with DatabaseBuilder

**Location:** `src/X4/Database/DatabaseBuilder.php`

Add turrets to the build process:

```php
use X4\Database\Turrets\TurretsExtractor;

class DatabaseBuilder
{
    public function build(): void
    {
        $this->log('Starting X4 database build...');
        
        // Phase 1: Foundation
        $this->buildMacroIndex();
        $this->buildDataSources();
        
        // Phase 2: Core data
        $this->buildFactions();
        $this->buildWares();
        
        // Phase 3: Equipment
        $this->buildEngines();
        $this->buildShields();
        $this->buildWeapons();
        $this->buildTurrets();  // ← NEW: Add turrets here
        
        // Phase 4: Ships
        $this->buildShips();
        
        // Phase 5: Derived data
        $this->buildBlueprints();
        
        $this->log('Database build complete!');
    }
    
    /**
     * Build turrets collection
     * 
     * Extracts turret data from wares and macro XML files.
     * Requires: MacroIndex, Wares
     */
    private function buildTurrets(): void
    {
        $this->log('Building turrets...');
        
        $extractor = new TurretsExtractor($this->app);
        $this->runExtractor($extractor);
        
        $this->log('Turrets build complete.');
    }
}
```

**Verify Build Order:**
```
MacroIndex → DataSources → Factions → Wares → 
Engines → Shields → Weapons → Turrets → Ships → Blueprints
```

---

### Step 2.5: Create JSON Data File

**Location:** `data/turrets.json`

**Initial Content (empty):**
```json
[]
```

**After First Build:**
```bash
composer build
```

**Expected Output:**
```json
[
  {
    "id": "turret_arg_m_beam_01_mk1",
    "name": "Argon Beam Turret Mk1",
    "size": "m",
    "rotationSpeed": 45.0,
    "damage": 500.0,
    "range": 3000.0,
    "reloadTime": 0.5,
    "dataSource": "vanilla"
  },
  {
    "id": "turret_par_l_plasma_01_mk1",
    "name": "Paranid Plasma Turret Mk1",
    "size": "l",
    "rotationSpeed": 30.0,
    "damage": 800.0,
    "range": 3500.0,
    "reloadTime": 0.8,
    "dataSource": "vanilla"
  }
]
```

---

## 🧪 Phase 3: Testing

### Step 3.1: Create Test Class

**Location:** `tests/X4Tests/Suites/Database/Turrets/TurretDefTests.php`

```php
<?php
/**
 * Turret Definition Tests
 * 
 * Tests for turret collection, items, and finder.
 * 
 * @package X4Tests\Suites\Database\Turrets
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Turrets;

use X4\Database\Turrets\TurretDefs;
use X4\Database\Turrets\TurretDef;
use X4Tests\BaseTestCase;

class TurretDefTests extends BaseTestCase
{
    /**
     * Test that turrets collection loads successfully
     */
    public function testCollectionLoaded(): void
    {
        $turrets = TurretDefs::getInstance();
        
        $this->assertGreaterThan(0, $turrets->countItems(), 
            'Turrets collection should not be empty');
    }
    
    /**
     * Test retrieving a specific turret by ID
     */
    public function testGetTurretByID(): void
    {
        $turret = TurretDefs::getInstance()
            ->getByID('turret_arg_m_beam_01_mk1');
        
        $this->assertNotNull($turret, 'Turret should exist');
        $this->assertInstanceOf(TurretDef::class, $turret);
        $this->assertEquals('turret_arg_m_beam_01_mk1', $turret->getID());
    }
    
    /**
     * Test turret properties
     */
    public function testTurretProperties(): void
    {
        $turret = TurretDefs::getInstance()
            ->getByID('turret_arg_m_beam_01_mk1');
        
        $this->assertNotNull($turret);
        
        // Test basic properties
        $this->assertEquals('m', $turret->getSize());
        $this->assertIsString($turret->getName());
        $this->assertNotEmpty($turret->getName());
        
        // Test numeric properties
        $this->assertGreaterThanOrEqual(0, $turret->getRotationSpeed());
        $this->assertGreaterThan(0, $turret->getDamage());
        $this->assertGreaterThan(0, $turret->getRange());
        $this->assertGreaterThan(0, $turret->getReloadTime());
        
        // Test data source
        $this->assertNotEmpty($turret->getDataSource());
    }
    
    /**
     * Test finder: filter by size
     */
    public function testFinderFilterBySize(): void
    {
        $mediumTurrets = TurretDefs::getInstance()
            ->findTurrets()
            ->selectSize('m')
            ->getAll();
        
        $this->assertNotEmpty($mediumTurrets, 'Should find medium turrets');
        
        foreach ($mediumTurrets as $turret) {
            $this->assertEquals('m', $turret->getSize(),
                'All turrets should be medium size');
        }
    }
    
    /**
     * Test finder: filter by minimum damage
     */
    public function testFinderFilterByMinDamage(): void
    {
        $minDamage = 400.0;
        
        $highDamageTurrets = TurretDefs::getInstance()
            ->findTurrets()
            ->selectMinDamage($minDamage)
            ->getAll();
        
        foreach ($highDamageTurrets as $turret) {
            $this->assertGreaterThanOrEqual($minDamage, $turret->getDamage(),
                sprintf('Turret %s should have damage >= %f', 
                    $turret->getID(), $minDamage));
        }
    }
    
    /**
     * Test finder: filter by minimum range
     */
    public function testFinderFilterByMinRange(): void
    {
        $minRange = 2500.0;
        
        $longRangeTurrets = TurretDefs::getInstance()
            ->findTurrets()
            ->selectMinRange($minRange)
            ->getAll();
        
        foreach ($longRangeTurrets as $turret) {
            $this->assertGreaterThanOrEqual($minRange, $turret->getRange(),
                sprintf('Turret %s should have range >= %f',
                    $turret->getID(), $minRange));
        }
    }
    
    /**
     * Test finder: combined filters
     */
    public function testFinderCombinedFilters(): void
    {
        $results = TurretDefs::getInstance()
            ->findTurrets()
            ->selectSize('l')
            ->selectMinDamage(600.0)
            ->selectMinRange(3000.0)
            ->getAll();
        
        foreach ($results as $turret) {
            $this->assertEquals('l', $turret->getSize());
            $this->assertGreaterThanOrEqual(600.0, $turret->getDamage());
            $this->assertGreaterThanOrEqual(3000.0, $turret->getRange());
        }
    }
    
    /**
     * Test DPS calculation
     */
    public function testDPSCalculation(): void
    {
        $turret = TurretDefs::getInstance()
            ->getByID('turret_arg_m_beam_01_mk1');
        
        $this->assertNotNull($turret);
        
        $expectedDPS = $turret->getDamage() / $turret->getReloadTime();
        $actualDPS = $turret->getDPS();
        
        $this->assertEquals($expectedDPS, $actualDPS, 
            'DPS calculation should be correct');
    }
    
    /**
     * Test size helper methods
     */
    public function testSizeHelpers(): void
    {
        $smallTurret = TurretDefs::getInstance()
            ->findTurrets()
            ->selectSize('s')
            ->getFirst();
        
        if ($smallTurret !== null) {
            $this->assertTrue($smallTurret->isSmall());
            $this->assertFalse($smallTurret->isMedium());
            $this->assertFalse($smallTurret->isLarge());
        }
        
        $mediumTurret = TurretDefs::getInstance()
            ->findTurrets()
            ->selectSize('m')
            ->getFirst();
        
        if ($mediumTurret !== null) {
            $this->assertFalse($mediumTurret->isSmall());
            $this->assertTrue($mediumTurret->isMedium());
            $this->assertFalse($mediumTurret->isLarge());
        }
    }
    
    /**
     * Test fixed vs rotating turrets
     */
    public function testFixedVsRotating(): void
    {
        $allTurrets = TurretDefs::getInstance()->getAll();
        
        $hasFixed = false;
        $hasRotating = false;
        
        foreach ($allTurrets as $turret) {
            if ($turret->isFixed()) {
                $hasFixed = true;
                $this->assertEquals(0.0, $turret->getRotationSpeed());
            } else {
                $hasRotating = true;
                $this->assertGreaterThan(0.0, $turret->getRotationSpeed());
            }
        }
        
        // Note: This assertion may fail if game data has only one type
        // Adjust based on actual game data
        $this->assertTrue($hasFixed || $hasRotating, 
            'Should have at least one type of turret');
    }
    
    /**
     * Test JSON structure validation
     */
    public function testJSONStructure(): void
    {
        $jsonPath = $this->getDataPath('turrets.json');
        
        $this->assertFileExists($jsonPath, 'turrets.json should exist');
        
        $json = file_get_contents($jsonPath);
        $data = json_decode($json, true);
        
        $this->assertIsArray($data, 'JSON should decode to array');
        $this->assertNotEmpty($data, 'JSON should not be empty');
        
        $firstItem = $data[0];
        
        // Verify all required fields present
        $requiredFields = [
            'id', 'name', 'size', 'rotationSpeed', 
            'damage', 'range', 'reloadTime', 'dataSource'
        ];
        
        foreach ($requiredFields as $field) {
            $this->assertArrayHasKey($field, $firstItem,
                sprintf('Field "%s" should exist in JSON', $field));
        }
    }
}
```

---

### Step 3.2: Run Tests

```bash
# Run turret tests only
vendor/bin/phpunit tests/X4Tests/Suites/Database/Turrets/TurretDefTests.php

# Run all database tests
vendor/bin/phpunit tests/X4Tests/Suites/Database/

# Run all tests
vendor/bin/phpunit
```

**Expected Output:**
```
PHPUnit 9.5.x by Sebastian Bergmann and contributors.

............                                                      12 / 12 (100%)

Time: 00:00.234, Memory: 10.00 MB

OK (12 tests, 45 assertions)
```

---

## 📝 Phase 4: Documentation

### Step 4.1: Manifest Update Checklist

After implementation, update these manifest documents:

#### 1. tech-stack.md

**Section:** Collection-Item Pattern Examples

Add:
```markdown
- **TurretDefs/TurretDef:** Turret equipment with combat stats
```

#### 2. public-api.md

**Section:** Database Namespace

Add complete section:
```markdown
### Database\Turrets Namespace

#### TurretDefs

Singleton collection of all turrets.

**Methods:**
- `getInstance(): TurretDefs` - Get singleton instance
- `getByID(string $id): ?TurretDef` - Find turret by ID
- `getAll(): TurretDef[]` - Get all turrets
- `countItems(): int` - Count turrets
- `findTurrets(): TurretFinder` - Get finder for filtering

#### TurretDef

Represents a single turret.

**Properties:**
- `getID(): string` - Turret ID
- `getName(): string` - Localized name
- `getSize(): string` - Size (s, m, l)
- `getRotationSpeed(): float` - Degrees per second
- `getDamage(): float` - Damage per shot
- `getRange(): float` - Maximum range
- `getReloadTime(): float` - Time between shots
- `getDataSource(): string` - DLC providing this turret
- `getDPS(): float` - Calculated damage per second
- `isFixed(): bool` - True if no rotation
- `isSmall(): bool` - True if size is 's'
- `isMedium(): bool` - True if size is 'm'
- `isLarge(): bool` - True if size is 'l'

#### TurretFinder

Fluent finder for filtering turrets.

**Methods:**
- `selectSize(string $size): self` - Filter by size
- `selectMinDamage(float $min): self` - Minimum damage
- `selectMaxDamage(float $max): self` - Maximum damage
- `selectMinRange(float $min): self` - Minimum range
- `selectMinRotationSpeed(float $min): self` - Minimum rotation
- `selectMinDPS(float $min): self` - Minimum DPS
- `selectDataSource(string $source): self` - Filter by DLC
- `selectRotating(): self` - Only rotating turrets
- `selectFixed(): self` - Only fixed turrets
- `getAll(): TurretDef[]` - Get filtered results
- `getFirst(): ?TurretDef` - Get first result
- `countResults(): int` - Count results
```

#### 3. file-tree.md

**Section:** src/X4/Database/

Add:
```markdown
src/X4/Database/Turrets/
├── TurretDef.php              # Single turret definition
├── TurretDefs.php             # Turret collection
├── TurretFinder.php           # Turret filtering
├── TurretsExtractor.php       # Main extractor
└── TurretMacroExtractor.php   # Macro extractor
```

**Section:** data/

Add:
```markdown
data/
├── turrets.json               # Extracted turret data
```

**Section:** tests/X4Tests/Suites/Database/

Add:
```markdown
tests/X4Tests/Suites/Database/Turrets/
└── TurretDefTests.php         # Turret tests
```

#### 4. data-flows.md

If turrets have unique data flow, add diagram. Otherwise, reference existing Two-Phase pattern.

#### 5. extraction-reference.md

**Section:** Extractor Inventory

Add row:
```markdown
| Turrets | Two-Phase | Turret combat equipment | `turrets.json` | TurretsExtractor | 5 classes | ✅ |
```

---

### Step 4.2: Update AGENTS.md

If this adds a new pattern or significantly changes the architecture, update [AGENTS.md](../../../AGENTS.md).

---

## ✅ Phase 5: Verification

### Final Checklist

#### Code Quality

- [ ] All classes have proper namespaces matching folder structure
- [ ] All classes have complete PHPDoc comments
- [ ] All methods have type hints (parameters + return types)
- [ ] No `@SuppressWarnings` annotations unless absolutely necessary
- [ ] Code follows PSR-12 style guidelines
- [ ] No unused imports
- [ ] No unused variables

#### Functionality

- [ ] Extractor successfully extracts data
- [ ] JSON file created and populated
- [ ] Collection loads without errors
- [ ] All properties accessible via getter methods
- [ ] Finder filters work correctly
- [ ] DatabaseBuilder includes new extractor
- [ ] Build order correct (dependencies satisfied)

#### Testing

- [ ] Test class created
- [ ] All tests pass
- [ ] Test coverage includes:
  - [ ] Collection loading
  - [ ] Specific item retrieval
  - [ ] All properties
  - [ ] All finder filters
  - [ ] Combined filters
  - [ ] JSON structure validation
  - [ ] Edge cases

#### Documentation

- [ ] tech-stack.md updated
- [ ] public-api.md updated
- [ ] file-tree.md updated
- [ ] data-flows.md updated (if needed)
- [ ] extraction-reference.md updated
- [ ] AGENTS.md updated (if needed)
- [ ] All code has PHPDoc comments

#### Static Analysis

- [ ] PHPStan passes: `vendor/bin/phpstan analyze`
- [ ] PHPCS passes: `vendor/bin/phpcs`
- [ ] Composer autoload updated: `composer dump-autoload`

#### Integration

- [ ] Full build succeeds: `composer build`
- [ ] JSON file generated
- [ ] Item count reasonable
- [ ] No warnings in build output
- [ ] All tests pass: `vendor/bin/phpunit`

---

### Verification Commands

```bash
# 1. Static analysis
vendor/bin/phpstan analyze src/X4/Database/Turrets
vendor/bin/phpcs src/X4/Database/Turrets

# 2. Autoload update
composer dump-autoload

# 3. Full build
composer build

# 4. Verify JSON
Test-Path data/turrets.json
(Get-Content data/turrets.json | ConvertFrom-Json).Count

# 5. Run tests
vendor/bin/phpunit tests/X4Tests/Suites/Database/Turrets/TurretDefTests.php

# 6. Run all tests
vendor/bin/phpunit
```

---

## 🚨 Common Pitfalls

### 1. Wrong Build Order

**Symptom:**
```
X4Exception: Wares collection must be built before turrets
Error: Call to a member function getAll() on null
```

**Cause:**
Extractor called before its dependencies built.

**Fix:**
1. Review dependency chain
2. Ensure DatabaseBuilder calls extractors in correct order
3. Add explicit dependency checks in extractor

```php
public function extract(): array
{
    if (!WareDefs::getInstance()->isBuilt()) {
        throw new X4Exception('Wares must be built first');
    }
    // ... extraction logic
}
```

---

### 2. Missing Composer Autoload

**Symptom:**
```
Fatal error: Class 'X4\Database\Turrets\TurretsExtractor' not found
```

**Cause:**
New classes not registered with Composer's autoloader.

**Fix:**
```bash
composer dump-autoload
```

---

### 3. Wrong Namespace

**Symptom:**
```
Cannot declare class X4\Database\TurretsExtractor because the name is already in use
```

**Cause:**
Namespace doesn't match folder structure (PSR-4 violation).

**Fix:**
Ensure namespace matches path:
```php
// File: src/X4/Database/Turrets/TurretsExtractor.php
namespace X4\Database\Turrets;  // CORRECT

// NOT:
namespace X4\Database;  // WRONG
```

---

### 4. Forgot DatabaseBuilder Integration

**Symptom:**
- `turrets.json` not created
- No extraction output
- Tests fail with "Collection not built"

**Cause:**
Extractor class created but never called.

**Fix:**
Add to DatabaseBuilder:
```php
public function build(): void
{
    // ... other extractors
    $this->buildTurrets();  // ← Add this
}

private function buildTurrets(): void
{
    $this->log('Building turrets...');
    $extractor = new TurretsExtractor($this->app);
    $this->runExtractor($extractor);
}
```

---

### 5. JSON File Not in .gitignore

**Symptom:**
Git wants to track `data/turrets.json`.

**Cause:**
JSON files are build artifacts, not source code.

**Fix:**
Verify `.gitignore` includes:
```gitignore
data/*.json
```

**Exception:** Only `data-sources.json` is tracked (manually maintained).

---

### 6. Manifest Docs Not Updated

**Symptom:**
- Future agents confused
- Manifest out of sync with code
- CI checks fail

**Impact:**
Breaks the manifest-first philosophy. Future agents won't know about new extractor.

**Fix:**
Update ALL 5 manifest files:
1. tech-stack.md
2. public-api.md
3. file-tree.md
4. data-flows.md (if needed)
5. extraction-reference.md

---

### 7. Per-Item Errors Stop Extraction

**Symptom:**
One invalid item causes entire extraction to fail.

**Cause:**
Not using try-catch around per-item extraction.

**Fix:**
```php
// BAD: Single error stops everything
foreach ($turretWares as $ware) {
    $extractor = new TurretMacroExtractor($ware);
    $result[] = $extractor->extract();  // Throws, stops loop
}

// GOOD: Skip invalid items, continue
foreach ($turretWares as $ware) {
    try {
        $extractor = new TurretMacroExtractor($ware);
        $result[] = $extractor->extract();
    } catch (\Exception $e) {
        $this->logWarning("Failed: {$ware->getID()}: {$e->getMessage()}");
        continue;  // Skip this item
    }
}
```

---

## ⚡ Performance Checklist

### DOM Loading

- [ ] Minimize DOM loads (cache if reused)
- [ ] Don't load DOM multiple times for same file
- [ ] Use static caching if same macro accessed repeatedly

```php
// BAD: Loads DOM multiple times
foreach ($items as $item) {
    $dom = XMLHelper::createDOMExtended($path);  // Slow!
}

// GOOD: Load once, reuse
$dom = XMLHelper::createDOMExtended($path);
foreach ($items as $item) {
    // Use cached $dom
}
```

### Filtering Efficiency

- [ ] Use single-pass filtering where possible
- [ ] Combine filters instead of multiple passes
- [ ] Use Finder pattern (lazy evaluation)

```php
// BAD: Multiple passes
$items = WareDefs::getInstance()->getAll();
$turrets = array_filter($items, fn($w) => $w->getGroup() === 'turrets');
$medium = array_filter($turrets, fn($w) => $w->getSize() === 'm');

// GOOD: Single pass
$items = WareDefs::getInstance()->getAll();
$medium = array_filter($items, fn($w) => 
    $w->getGroup() === 'turrets' && $w->getSize() === 'm'
);

// BEST: Use Finder (lazy)
$medium = WareDefs::getInstance()
    ->findWares()
    ->selectGroup('turrets')
    ->selectSize('m')
    ->getAll();  // Only filters once
```

### Property Extraction

- [ ] Only extract properties actually needed
- [ ] Skip optional properties not used
- [ ] Don't calculate derived values if not needed

```php
// BAD: Extracts unused properties
return [
    'id' => $this->ware->getID(),
    'name' => $this->ware->getName(),
    'price' => $this->extractPrice($dom),     // Not used
    'production' => $this->extractProduction($dom),  // Not used
    // ...
];

// GOOD: Only needed properties
return [
    'id' => $this->ware->getID(),
    'name' => $this->ware->getName(),
    'damage' => $this->extractDamage($dom),
    'range' => $this->extractRange($dom),
    // ...
];
```

---

## ✅ Testing Checklist

### Basic Tests

- [ ] Collection loads successfully
- [ ] Collection not empty
- [ ] Item count reasonable
- [ ] Specific items retrievable by ID
- [ ] All getters return correct types

### Property Tests

- [ ] All required properties present
- [ ] Numeric properties > 0 where expected
- [ ] String properties not empty
- [ ] Enum properties have valid values
- [ ] Data source valid

### Finder Tests

- [ ] Each filter method works
- [ ] Filters actually filter (result count < total)
- [ ] Filtered results match criteria
- [ ] Combined filters work correctly
- [ ] Empty results handled (no crash)

### Edge Cases

- [ ] Missing optional properties handled
- [ ] Invalid data handled gracefully
- [ ] Empty collection handled
- [ ] Null values handled
- [ ] Zero values handled correctly

### JSON Structure

- [ ] JSON file exists
- [ ] JSON parses successfully
- [ ] Array structure correct
- [ ] All required fields present
- [ ] Field types correct

---

## 🎯 Success Criteria

### Implementation Complete When:

1. **Code Quality:**
   - ✅ All classes follow established patterns
   - ✅ PHPStan level 9 passes
   - ✅ PHPCS passes
   - ✅ No code smells

2. **Functionality:**
   - ✅ Extractor runs without errors
   - ✅ JSON file generated with data
   - ✅ Collection loads successfully
   - ✅ All properties accessible
   - ✅ Finder filters work

3. **Testing:**
   - ✅ Test class created
   - ✅ All tests pass
   - ✅ Coverage includes all major paths
   - ✅ Edge cases tested

4. **Documentation:**
   - ✅ All 5 manifest docs updated
   - ✅ PHPDoc complete
   - ✅ Code self-documenting
   - ✅ No TODOs left

5. **Integration:**
   - ✅ Added to DatabaseBuilder
   - ✅ Build order correct
   - ✅ Full build succeeds
   - ✅ All tests pass

### Code Indistinguishable From Existing Codebase

The ultimate success criterion: another developer (or AI agent) cannot tell your code was added later. It should:
- Follow exact same patterns
- Have same code style
- Same documentation style
- Same error handling
- Same testing approach

**If your code looks different, it's wrong.** Study existing extractors carefully and match them exactly.

---

## 📚 Additional Resources

### Reference Extractors

Study these before implementing:

**Simple Single-Phase:**
- [FactionsExtractor.php](../../../../src/X4/Database/Factions/FactionsExtractor.php)
- [DataSourcesExtractor.php](../../../../src/X4/Database/DataSources/DataSourcesExtractor.php)

**Simple Two-Phase:**
- [EnginesExtractor.php](../../../../src/X4/Database/Engines/EnginesExtractor.php)
- [EngineMacroExtractor.php](../../../../src/X4/Database/Engines/EngineMacroExtractor.php)

**Complex Two-Phase:**
- [ShipsExtractor.php](../../../../src/X4/Database/Ships/ShipsExtractor.php)
- [ShipMacroExtractor.php](../../../../src/X4/Database/Ships/ShipMacroExtractor.php)

### Base Classes

- [BaseExtractor.php](../../../../src/X4/Database/BaseExtractor.php) - Extractor base
- [BaseCollection.php](../../../../src/X4/Database/Core/BaseCollection.php) - Collection base
- [BaseItem.php](../../../../src/X4/Database/Core/BaseItem.php) - Item base
- [BaseFinder.php](../../../../src/X4/Database/Core/BaseFinder.php) - Finder base

### Test Examples

- [ShipDefTests.php](../../../tests/X4Tests/Suites/Database/Ships/ShipDefTests.php)
- [EngineDefTests.php](../../../tests/X4Tests/Suites/Database/Engines/EngineDefTests.php)

---

**Total Estimated Time:** 4-8 hours for complete implementation

**Last Updated:** February 9, 2026
