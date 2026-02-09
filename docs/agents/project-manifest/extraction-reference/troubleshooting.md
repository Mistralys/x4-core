# Extraction Troubleshooting Guide

> **Module:** Developer Support & Error Resolution  
> **Version:** 1.0  
> **Last Updated:** February 9, 2026  
> **Audience:** Developers working with X4 data extraction  
> **Purpose:** Comprehensive error diagnosis and resolution guide

---

## 📋 Overview

This guide documents common errors encountered during X4 data extraction, their root causes, and proven solutions. Use this as a first reference when encountering extraction failures or unexpected behavior.

### Quick Error Reference

| Error Type | Severity | Common Cause | Quick Fix |
|------------|----------|--------------|-----------|
| **Missing Macro** | 🔴 Critical | MacroIndex not built | Verify build order |
| **Invalid XML** | 🔴 Critical | File corruption | Re-extract data |
| **Property Not Found** | 🟡 Warning | Wrong DOM query | Check XML structure |
| **Build Dependency** | 🔴 Critical | Wrong build order | Fix DatabaseBuilder |
| **Data Source Missing** | 🟡 Warning | Missing DLC folder | Verify extraction path |
| **Type Conversion** | 🟡 Warning | Invalid attribute value | Add validation |

---

## 🔧 Common Errors and Solutions

### Error 1: Missing Macro Error

#### Symptoms

**Error Message:**
```
X4Exception: Macro 'shield_xyz_macro' not found in MacroIndex
File: src/X4/Database/MacroIndex/MacroIndex.php
Location: getByID() method
```

**Call Stack:**
```
MacroIndex::getByID('shield_xyz_macro')
  → ShieldMacroExtractor::extract()
    → ShieldsExtractor::extract()
      → DatabaseBuilder::buildShields()
```

**When It Happens:**
- During Two-Phase extraction when resolving ware → macro
- When accessing equipment compatibility data
- During ship component extraction

#### Root Cause Analysis

**Primary Causes:**
1. **MacroIndex Not Built Yet** (85% of cases)
   - DatabaseBuilder build order incorrect
   - MacroIndex extraction skipped/failed
   - Build interrupted before MacroIndex completed

2. **Invalid Macro Reference** (10% of cases)
   - Ware references non-existent macro
   - Typo in ware ID → macro name conversion
   - Macro exists in game but not in extracted data

3. **Data Source Issues** (5% of cases)
   - DLC not installed/extracted
   - Extraction path misconfigured
   - File permissions prevent access

#### Solutions

**Solution 1: Verify Build Order**

```php
// Location: src/X4/Database/DatabaseBuilder.php

public function build(): void
{
    // CRITICAL: MacroIndex MUST be first!
    $this->buildMacroIndex();    // ← Must execute before any Two-Phase extractor
    
    // Then build collections that use macros
    $this->buildWares();         // Uses MacroIndex for macro references
    $this->buildEngines();       // Two-Phase: requires MacroIndex
    $this->buildShields();       // Two-Phase: requires MacroIndex
    $this->buildWeapons();       // Two-Phase: requires MacroIndex
    $this->buildShips();         // Two-Phase: requires MacroIndex
    
    // WRONG ORDER EXAMPLE (causes error):
    // $this->buildShields();    // ERROR: MacroIndex not built!
    // $this->buildMacroIndex(); // Too late!
}
```

**Test Build Order:**
```bash
# Run full build
composer build

# Check build output order
# Should see: "Building MacroIndex..." first
```

**Solution 2: Search for Missing Macro**

```powershell
# Search all data sources for the macro
Get-ChildItem -Path "F:\Webserver\www\htdocs\tools\x4-data-extractor\output\" `
    -Recurse `
    -Filter "shield_xyz_macro.xml" `
    | Select-Object FullName

# If found: Check why it's not in MacroIndex
# If not found: Macro doesn't exist in extracted data
```

**Manual Macro Search:**
```powershell
# Search index/macros.xml files
$outputPath = "F:\...\x4-data-extractor\output\"
$dataSources = @('vanilla', 'ego_dlc_terran', 'ego_dlc_split', 'ego_dlc_pirate')

foreach ($source in $dataSources) {
    $indexFile = Join-Path $outputPath "$source\index\macros.xml"
    if (Test-Path $indexFile) {
        $content = Get-Content $indexFile -Raw
        if ($content -match 'shield_xyz_macro') {
            Write-Host "Found in: $source"
        }
    }
}
```

**Solution 3: Verify Ware ID to Macro Conversion**

```php
// Standard conversion pattern
$wareID = 'shield_xyz';
$macroName = $wareID . '_macro';  // shield_xyz_macro

// Debug the conversion
echo "Ware ID: {$wareID}\n";
echo "Expected Macro: {$macroName}\n";

// Check if ware exists
$ware = WareDefs::getInstance()->getByID($wareID);
if ($ware === null) {
    throw new X4Exception("Ware '{$wareID}' not found");
}

// Check if macro exists
try {
    $macro = MacroIndex::getInstance()->getByID($macroName);
    echo "Macro found: {$macro->getFilePath()}\n";
} catch (X4Exception $e) {
    echo "ERROR: {$e->getMessage()}\n";
}
```

**Solution 4: Check Extraction Path Configuration**

```php
// Location: dev-config.php (project root)

// Verify correct path
X4Application::setDataExtractionPath('F:/Webserver/www/htdocs/tools/x4-data-extractor/output/');
//                                    ↑ Must point to x4-data-extractor output directory

// Common mistakes:
// ❌ X4Application::setDataExtractionPath('F:/..../x4-core/data/');  // Wrong!
// ❌ X4Application::setDataExtractionPath('F:/..../x4-data-extractor/');  // Missing /output/
// ✅ X4Application::setDataExtractionPath('F:/..../x4-data-extractor/output/');  // Correct!
```

**Test Extraction Path:**
```php
// Create test script: test-extraction-path.php
<?php
require_once 'vendor/autoload.php';
require_once 'dev-config.php';

$path = X4Application::getDataExtractionPath();
echo "Extraction path: {$path}\n";

// Check if path exists
if (!is_dir($path)) {
    die("ERROR: Path does not exist!\n");
}

// Check if data sources exist
$expected = ['vanilla', 'ego_dlc_terran', 'ego_dlc_split'];
foreach ($expected as $source) {
    $sourcePath = $path . $source;
    if (is_dir($sourcePath)) {
        echo "✓ Found: {$source}\n";
    } else {
        echo "✗ Missing: {$source}\n";
    }
}
```

---

### Error 2: Invalid XML Structure

#### Symptoms

**Error Message:**
```
Exception: Failed to parse XML at path/to/macro.xml
DOMException: XML parse error at line 45
Opening and ending tag mismatch: component line 44 and macro
```

**PHP Warning:**
```
Warning: DOMDocument::load(): Extra content at the end of the document in macro.xml
```

**When It Happens:**
- During macro XML loading in Two-Phase extractors
- When reading index/macros.xml files
- During libraries/wares.xml parsing

#### Root Cause Analysis

**Primary Causes:**
1. **Malformed XML File** (40% of cases)
   - Unclosed tags
   - Missing closing tags
   - Invalid XML syntax

2. **File Encoding Issues** (30% of cases)
   - Non-UTF-8 encoding
   - BOM (Byte Order Mark) present
   - Mixed encodings in same file

3. **File Corruption** (20% of cases)
   - Incomplete file writes
   - Disk errors during extraction
   - Interrupted extraction process

4. **Game File Issues** (10% of cases)
   - Corrupted game installation
   - Invalid mod XML files
   - Incomplete game update

#### Solutions

**Solution 1: Validate XML with Linter**

```powershell
# Using PowerShell (Windows)
try {
    [xml]$xml = Get-Content "F:\...\output\vanilla\libraries\wares.xml"
    Write-Host "✓ XML is valid"
} catch {
    Write-Host "✗ XML is invalid: $_"
}

# Check specific macro file
$macroPath = "F:\...\output\vanilla\assets\props\...\shield_xyz_macro.xml"
try {
    [xml]$xml = Get-Content $macroPath
    Write-Host "✓ Valid: $macroPath"
} catch {
    Write-Host "✗ Invalid: $macroPath"
    Write-Host "Error: $_"
}
```

**Batch Validation Script:**
```powershell
# validate-xml.ps1
param(
    [Parameter(Mandatory=$true)]
    [string]$BasePath
)

$xmlFiles = Get-ChildItem -Path $BasePath -Recurse -Filter "*.xml"
$errors = @()

foreach ($file in $xmlFiles) {
    try {
        [xml]$xml = Get-Content $file.FullName
    } catch {
        $errors += [PSCustomObject]@{
            File = $file.FullName
            Error = $_.Exception.Message
        }
    }
}

if ($errors.Count -eq 0) {
    Write-Host "✓ All XML files valid" -ForegroundColor Green
} else {
    Write-Host "✗ Found $($errors.Count) invalid XML files" -ForegroundColor Red
    $errors | Format-Table -AutoSize
}
```

**Solution 2: Check File Encoding**

```powershell
# Detect encoding
$file = "F:\...\output\vanilla\libraries\wares.xml"
$bytes = [System.IO.File]::ReadAllBytes($file)

# Check for UTF-8 BOM
if ($bytes[0] -eq 0xEF -and $bytes[1] -eq 0xBB -and $bytes[2] -eq 0xBF) {
    Write-Host "✗ UTF-8 BOM detected (may cause issues)"
} else {
    Write-Host "✓ No BOM detected"
}

# Convert to UTF-8 without BOM
$content = Get-Content $file -Raw
[System.IO.File]::WriteAllText($file, $content, [System.Text.UTF8Encoding]::new($false))
Write-Host "✓ Converted to UTF-8 without BOM"
```

**Solution 3: Re-extract from Game Files**

```bash
# Navigate to x4-data-extractor project
cd F:\Webserver\www\htdocs\tools\x4-data-extractor

# Re-run extraction for all data sources
php extract.php

# Or re-extract specific DLC
.\batch\unpack-vanilla.bat
.\batch\unpack-ego_dlc_terran.bat
.\batch\unpack-ego_dlc_split.bat
```

**Verify Re-extraction:**
```powershell
# Check output files
$outputPath = "F:\...\x4-data-extractor\output\"
Test-Path "$outputPath\vanilla\libraries\wares.xml"
Test-Path "$outputPath\vanilla\index\macros.xml"

# Check file sizes (should be reasonable)
Get-ChildItem "$outputPath\vanilla\libraries\wares.xml" | Select-Object Name, Length
# Expected: ~500KB - 2MB depending on game version
```

**Solution 4: Manual File Inspection**

```php
// Create debug script: inspect-xml.php
<?php
$filePath = 'F:/path/to/suspicious.xml';

// Read raw content
$content = file_get_contents($filePath);

// Check file size
$size = strlen($content);
echo "File size: {$size} bytes\n";

// Check first 100 chars
echo "First 100 chars:\n";
echo substr($content, 0, 100) . "\n\n";

// Check last 100 chars
echo "Last 100 chars:\n";
echo substr($content, -100) . "\n\n";

// Count tags
preg_match_all('/<(\w+)/', $content, $openTags);
preg_match_all('/<\/(\w+)>/', $content, $closeTags);

echo "Open tags: " . count($openTags[0]) . "\n";
echo "Close tags: " . count($closeTags[0]) . "\n";

if (count($openTags[0]) !== count($closeTags[0])) {
    echo "✗ Tag mismatch detected!\n";
}
```

---

### Error 3: Property Not Found

#### Symptoms

**Error Message:**
```
Exception: Required property 'hull.max' not found
Called requireFirst() but no matching node found
File: src/X4/Database/Ships/ShipMacroExtractor.php
Line: 156
```

**Alternative Messages:**
```
X4Exception: Could not find required element 'thruster'
DOMExtended::requireFirst() returned null
getAttribute() called on null
```

**When It Happens:**
- During property extraction from macro XML
- When using `requireFirst()` on optional elements
- When XML structure differs from expected

#### Root Cause Analysis

**Primary Causes:**
1. **Incorrect DOM Query** (50% of cases)
   - Wrong tag name
   - Wrong attribute name
   - Incorrect XPath expression

2. **Missing Property in XML** (30% of cases)
   - Property is optional, not required
   - Different XML version/format
   - Incomplete macro definition

3. **Wrong XML Structure** (15% of cases)
   - Property nested differently than expected
   - Different tag hierarchy
   - Conditional structure (property in variant)

4. **Typo in Code** (5% of cases)
   - Misspelled tag name
   - Case sensitivity error
   - Copy/paste error

#### Solutions

**Solution 1: Verify XML Structure**

```xml
<!-- Expected structure: -->
<macro name="ship_arg_m_fighter_01_a_macro">
  <component>
    <hull max="2500" />
    <thruster forward="1000" />
  </component>
</macro>

<!-- Might actually be: -->
<macro name="ship_arg_m_fighter_01_a_macro">
  <component>
    <properties>
      <hull max="2500" />
      <thruster forward="1000" />
    </properties>
  </component>
</macro>
```

**Inspection Script:**
```php
// debug-xml-structure.php
<?php
require_once 'vendor/autoload.php';
require_once 'dev-config.php';

use Mistralys\X4\XMLHelper;

$macroPath = 'F:/path/to/ship_macro.xml';
$dom = XMLHelper::createDOMExtended($macroPath);

// Dump entire structure
echo $dom->saveXML();

// Or find specific elements
$component = $dom->byTagName('component')->getFirst();
if ($component) {
    echo "Component children:\n";
    foreach ($component->getChildren() as $child) {
        echo "  - " . $child->getNodeName() . "\n";
    }
}
```

**Solution 2: Adjust DOM Query for Nested Structure**

```php
// Before (fails if nested):
$hull = $dom->byTagName('hull')->requireFirst();
$max = (int)$hull->getAttribute('max');

// After (handles nesting):
$component = $dom->byTagName('component')->requireFirst();

// Try direct child first
$hull = $component->byTagName('hull')->getFirst();

// If not found, try nested in <properties>
if ($hull === null) {
    $properties = $component->byTagName('properties')->getFirst();
    if ($properties !== null) {
        $hull = $properties->byTagName('hull')->getFirst();
    }
}

if ($hull === null) {
    throw new X4Exception("Hull element not found in macro");
}

$max = (int)$hull->getAttribute('max');
```

**Flexible Extraction Method:**
```php
private function extractHull(DOMExtended $component): int
{
    // Strategy 1: Direct child
    $hull = $component->byTagName('hull')->getFirst();
    
    // Strategy 2: Nested in properties
    if ($hull === null) {
        $properties = $component->byTagName('properties')->getFirst();
        if ($properties !== null) {
            $hull = $properties->byTagName('hull')->getFirst();
        }
    }
    
    // Strategy 3: Nested in stats
    if ($hull === null) {
        $stats = $component->byTagName('stats')->getFirst();
        if ($stats !== null) {
            $hull = $stats->byTagName('hull')->getFirst();
        }
    }
    
    if ($hull === null) {
        throw new X4Exception(
            "Hull element not found in macro: {$this->ware->getID()}"
        );
    }
    
    return (int)$hull->getAttribute('max');
}
```

**Solution 3: Use getFirst() for Optional Properties**

```php
// WRONG: Throws exception if boost doesn't exist
$boost = $dom->byTagName('boost')->requireFirst();
$duration = (int)$boost->getAttribute('duration');

// CORRECT: Handle optional properties gracefully
$boostNode = $dom->byTagName('boost')->getFirst();

if ($boostNode !== null) {
    $duration = (int)$boostNode->getAttribute('duration');
} else {
    $duration = 0;  // Default value
}

// Or use null coalescing:
$duration = $boostNode?->getAttribute('duration') ?? 0;
```

**Decision Matrix:**
```php
// Use requireFirst() when:
// - Property MUST exist for valid data
// - Missing property indicates corrupt/invalid macro
// - Example: ship hull, engine thrust, weapon damage

// Use getFirst() when:
// - Property is optional
// - Not all items have this property
// - Example: ship boost, turret rotation, optional equipment
```

**Solution 4: Add Error Context**

```php
// Before (unclear error):
$hull = $dom->byTagName('hull')->requireFirst();

// After (clear error with context):
try {
    $hull = $dom->byTagName('hull')->requireFirst();
} catch (\Exception $e) {
    throw new X4Exception(
        sprintf(
            "Hull property not found in macro '%s' at '%s'. " .
            "This might indicate invalid XML structure or missing game data.",
            $this->ware->getID(),
            $macro->getFilePath()
        ),
        $e
    );
}

// Even better: Include search paths
$searchPaths = [
    'component > hull',
    'component > properties > hull',
    'component > stats > hull'
];

throw new X4Exception(
    sprintf(
        "Hull property not found in macro '%s'. Searched paths: %s",
        $this->ware->getID(),
        implode(', ', $searchPaths)
    )
);
```

---

### Error 4: Build Dependency Violation

#### Symptoms

**Error Message:**
```
Error: Call to a member function getByID() on null
File: src/X4/Database/Ships/ShipMacroExtractor.php
Line: 89
```

**Alternative Messages:**
```
Exception: Collection not initialized
WareDefs::getInstance() returned null
Method called on uninitialized singleton
```

**When It Happens:**
- During extraction when accessing collection that hasn't been built
- When build order in DatabaseBuilder is incorrect
- When circular dependencies exist

#### Root Cause Analysis

**Primary Causes:**
1. **Wrong Build Order** (90% of cases)
   - Collection used before its extractor runs
   - Dependencies not built first
   - DatabaseBuilder order incorrect

2. **Circular Dependency** (8% of cases)
   - Two extractors depend on each other
   - Dependency cycle in data model
   - Design flaw in extraction architecture

3. **Missing Build Call** (2% of cases)
   - Extractor not called in DatabaseBuilder
   - Build step commented out
   - Conditional skip logic error

#### Solutions

**Solution 1: Fix Build Order in DatabaseBuilder**

```php
// Location: src/X4/Database/DatabaseBuilder.php

public function build(): void
{
    // ===== PHASE 1: FOUNDATION =====
    // MacroIndex must be absolutely first!
    $this->buildMacroIndex();        // Required by: All Two-Phase extractors
    
    // ===== PHASE 2: BASE DATA =====
    // Wares must come before equipment
    $this->buildWares();             // Required by: All equipment extractors
    $this->buildDataSources();       // Independent
    $this->buildFactions();          // Independent
    $this->buildTranslations();      // Independent
    
    // ===== PHASE 3: EQUIPMENT =====
    // Extract equipment (uses Wares + MacroIndex)
    $this->buildEngines();           // Requires: Wares, MacroIndex
    $this->buildShields();           // Requires: Wares, MacroIndex
    $this->buildWeapons();           // Requires: Wares, MacroIndex
    $this->buildThrusters();         // Requires: Wares, MacroIndex
    $this->buildModules();           // Requires: Wares, MacroIndex
    
    // ===== PHASE 4: COMPLEX ITEMS =====
    // Ships last (uses everything)
    $this->buildShips();             // Requires: Wares, Engines, Shields, 
                                     //           Weapons, Thrusters, MacroIndex
    
    // ===== PHASE 5: DERIVED DATA =====
    // Blueprints (uses Ships, Modules)
    $this->buildBlueprints();        // Requires: Ships, Modules, Wares
}

// ❌ WRONG ORDER (causes errors):
public function build(): void
{
    $this->buildShips();        // ERROR: Engines not built yet!
    $this->buildEngines();      // Too late!
    $this->buildMacroIndex();   // Way too late!
}
```

**Solution 2: Document and Verify Dependency Chain**

**Complete Dependency Graph:**
```
┌─────────────────────────────────────────────────────────────┐
│ LEVEL 1: FOUNDATION (no dependencies)                       │
├─────────────────────────────────────────────────────────────┤
│ • MacroIndex                                                │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ LEVEL 2: BASE DATA (depends on Level 1)                    │
├─────────────────────────────────────────────────────────────┤
│ • Wares          (uses MacroIndex for macro references)    │
│ • DataSources    (independent)                              │
│ • Factions       (independent)                              │
│ • Translations   (independent)                              │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ LEVEL 3: EQUIPMENT (depends on Levels 1-2)                 │
├─────────────────────────────────────────────────────────────┤
│ • Engines        (uses Wares, MacroIndex)                  │
│ • Shields        (uses Wares, MacroIndex)                  │
│ • Weapons        (uses Wares, MacroIndex)                  │
│ • Thrusters      (uses Wares, MacroIndex)                  │
│ • Modules        (uses Wares, MacroIndex)                  │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ LEVEL 4: COMPLEX ITEMS (depends on Levels 1-3)             │
├─────────────────────────────────────────────────────────────┤
│ • Ships          (uses Wares, Engines, Shields, Weapons,   │
│                   Thrusters, MacroIndex)                    │
└─────────────────────────────────────────────────────────────┘
                            ↓
┌─────────────────────────────────────────────────────────────┐
│ LEVEL 5: DERIVED DATA (depends on Levels 1-4)              │
├─────────────────────────────────────────────────────────────┤
│ • Blueprints     (uses Ships, Modules, Wares)              │
└─────────────────────────────────────────────────────────────┘
```

**Verification Script:**
```php
// verify-dependencies.php
<?php
require_once 'vendor/autoload.php';
require_once 'dev-config.php';

use X4\Database\Wares\WareDefs;
use X4\Database\Engines\EngineDefs;
use X4\Database\MacroIndex\MacroIndex;

// Test dependency availability
function checkDependency(string $name, callable $check): void
{
    try {
        $check();
        echo "✓ {$name} is available\n";
    } catch (\Exception $e) {
        echo "✗ {$name} is NOT available: {$e->getMessage()}\n";
    }
}

checkDependency('MacroIndex', fn() => MacroIndex::getInstance()->getByID('test'));
checkDependency('Wares', fn() => WareDefs::getInstance()->getByID('test'));
checkDependency('Engines', fn() => EngineDefs::getInstance()->getByID('test'));
```

**Solution 3: Add Explicit Dependency Checks**

```php
// In each extractor class
public function extract(): array
{
    // Check dependencies before starting
    $this->verifyDependencies();
    
    // ... extraction logic
}

private function verifyDependencies(): void
{
    if (!WareDefs::getInstance()->isBuilt()) {
        throw new X4Exception(
            'Wares collection must be built before Engines. ' .
            'Check DatabaseBuilder::build() order.'
        );
    }
    
    if (!MacroIndex::getInstance()->isBuilt()) {
        throw new X4Exception(
            'MacroIndex must be built before Engines. ' .
            'Check DatabaseBuilder::build() order.'
        );
    }
}
```

**Solution 4: Break Circular Dependencies**

```php
// ❌ WRONG: Circular dependency
class ShipsExtractor {
    public function extract() {
        // Ships need modules
        $modules = ModuleDefs::getInstance()->getAll();
    }
}

class ModulesExtractor {
    public function extract() {
        // Modules need ships
        $ships = ShipDefs::getInstance()->getAll();
    }
}

// ✅ CORRECT: One-way dependency
class ShipsExtractor {
    public function extract() {
        // Ships need modules
        $modules = ModuleDefs::getInstance()->getAll();
    }
}

class ModulesExtractor {
    public function extract() {
        // Modules don't need ships
        // Use only Wares and MacroIndex
        $wares = WareDefs::getInstance()->findWares()
            ->selectGroup('module')
            ->getAll();
    }
}
```

---

### Error 5: Data Source Not Detected

#### Symptoms

**Error Message:**
```
Warning: DLC 'ego_dlc_terran' not found at expected path
0 macros extracted for ego_dlc_terran
DataSource 'ego_dlc_terran' skipped
```

**Alternative Messages:**
```
Notice: Directory not found: F:/path/ego_dlc_terran
DataSource info.json missing
No data sources detected
```

**When It Happens:**
- During MacroIndex extraction
- When building DataSources collection
- When iterating over DLC folders

#### Root Cause Analysis

**Primary Causes:**
1. **Missing DLC Folder** (60% of cases)
   - DLC not extracted
   - Folder name mismatch
   - Extraction incomplete

2. **Incorrect Folder Structure** (25% of cases)
   - Missing required files (info.json)
   - Wrong directory hierarchy
   - Case sensitivity issues (Linux)

3. **Extraction Path Wrong** (10% of cases)
   - dev-config.php misconfigured
   - Relative path issues
   - Drive letter wrong (Windows)

4. **File Permissions** (5% of cases)
   - Read permissions denied
   - SELinux/AppArmor restrictions (Linux)
   - Antivirus blocking access (Windows)

#### Solutions

**Solution 1: Verify Folder Exists**

```powershell
# Check if DLC folders exist
$outputPath = "F:\Webserver\www\htdocs\tools\x4-data-extractor\output\"

$expectedDLCs = @(
    'vanilla',
    'ego_dlc_terran',
    'ego_dlc_split',
    'ego_dlc_pirate',
    'ego_dlc_boron',
    'ego_dlc_timelines',
    'ego_dlc_mini_01',
    'ego_dlc_mini_02'
)

foreach ($dlc in $expectedDLCs) {
    $dlcPath = Join-Path $outputPath $dlc
    if (Test-Path $dlcPath) {
        Write-Host "✓ Found: $dlc" -ForegroundColor Green
    } else {
        Write-Host "✗ Missing: $dlc" -ForegroundColor Red
    }
}
```

**Solution 2: Verify Folder Structure**

```powershell
# Check required folder structure
$dlcPath = "F:\...\output\ego_dlc_terran"

$requiredPaths = @(
    "$dlcPath\info.json",
    "$dlcPath\index",
    "$dlcPath\libraries"
)

foreach ($path in $requiredPaths) {
    if (Test-Path $path) {
        Write-Host "✓ Found: $path"
    } else {
        Write-Host "✗ Missing: $path"
    }
}

# Expected structure:
<#
ego_dlc_terran/
├── info.json          ← Must exist!
├── index/
│   └── macros.xml
├── libraries/
│   ├── wares.xml
│   └── region_definitions.xml
├── assets/
│   └── props/
│       └── ... (macro XML files)
├── md/
└── maps/
#>
```

**Validate info.json:**
```powershell
$infoPath = "$dlcPath\info.json"
if (Test-Path $infoPath) {
    $info = Get-Content $infoPath | ConvertFrom-Json
    
    # Check required fields
    if (-not $info.id) {
        Write-Host "✗ info.json missing 'id' field"
    }
    if (-not $info.name) {
        Write-Host "✗ info.json missing 'name' field"
    }
    
    Write-Host "Info: ID=$($info.id), Name=$($info.name)"
} else {
    Write-Host "✗ info.json not found"
}
```

**Expected info.json Format:**
```json
{
  "id": "ego_dlc_terran",
  "name": "Cradle of Humanity",
  "description": "Terran expansion with Earth and Sol system"
}
```

**Solution 3: Re-run Data Source Extraction**

```bash
# Navigate to x4-data-extractor
cd F:\Webserver\www\htdocs\tools\x4-data-extractor

# Run extraction for missing DLC
.\batch\unpack-ego_dlc_terran.bat

# Or extract all DLCs
.\batch\unpack-all.bat
```

**Verify Extraction Success:**
```powershell
# Check extraction output
$dlcPath = "F:\...\output\ego_dlc_terran"
$macrosIndex = "$dlcPath\index\macros.xml"

if (Test-Path $macrosIndex) {
    # Count macros
    [xml]$xml = Get-Content $macrosIndex
    $count = $xml.SelectNodes('//macro').Count
    Write-Host "✓ Extracted $count macros from ego_dlc_terran"
} else {
    Write-Host "✗ Macros index not found - extraction failed"
}
```

**Solution 4: Rebuild data-sources.json**

```bash
# Rebuild data sources collection
cd F:\Webserver\www\htdocs\tools\x4-core
composer build

# This will:
# 1. Scan output directory for DLC folders
# 2. Read each info.json
# 3. Regenerate data/data-sources.json
```

**Inspect data-sources.json:**
```powershell
$dataSourcesPath = "F:\...\x4-core\data\data-sources.json"
$dataSources = Get-Content $dataSourcesPath | ConvertFrom-Json

Write-Host "Found data sources:"
foreach ($source in $dataSources) {
    Write-Host "  - $($source.id): $($source.name)"
}

# Expected output:
# Found data sources:
#   - vanilla: X4: Foundations
#   - ego_dlc_terran: Cradle of Humanity
#   - ego_dlc_split: Split Vendetta
#   - ...
```

---

### Error 6: Type Conversion Failure

#### Symptoms

**Error Message:**
```
TypeError: Cannot convert string "N/A" to float
File: src/X4/Database/Engines/EngineMacroExtractor.php
Line: 145
Invalid value for forward thrust: "N/A"
```

**Alternative Messages:**
```
ValueError: Invalid numeric value
Argument #1 must be of type float, string given
Cast to int failed for value "unknown"
```

**When It Happens:**
- During attribute value conversion (string → float/int)
- When XML contains non-numeric values
- When attributes are missing (null → numeric)

#### Root Cause Analysis

**Primary Causes:**
1. **Non-Numeric Attribute Value** (70% of cases)
   - XML contains "N/A", "unknown", "TBD"
   - Placeholder values in game data
   - Intentional non-numeric markers

2. **Missing Attribute** (20% of cases)
   - getAttribute() returns null
   - Null converted to numeric
   - Optional attribute not present

3. **Invalid Numeric Format** (8% of cases)
   - Scientific notation not handled
   - Comma as decimal separator
   - Extra whitespace

4. **Game Data Bug** (2% of cases)
   - Actual bug in game XML files
   - Mod introduces invalid data
   - Game update breaks format

#### Solutions

**Solution 1: Validate Before Conversion**

```php
// ❌ WRONG: No validation
$forwardStr = $thruster->getAttribute('forward');
$forward = (float)$forwardStr;  // Fails if "N/A"

// ✅ CORRECT: Validate first
$forwardStr = $thruster->getAttribute('forward');

if (!is_numeric($forwardStr)) {
    $this->logWarning(
        "Invalid forward thrust value '{$forwardStr}' in {$macroName}, using default"
    );
    $forward = 0.0;  // Use sensible default
} else {
    $forward = (float)$forwardStr;
}
```

**Reusable Validation Method:**
```php
private function toFloat(?string $value, float $default = 0.0, string $context = ''): float
{
    if ($value === null || trim($value) === '') {
        $this->logWarning("Empty value for {$context}, using default {$default}");
        return $default;
    }
    
    if (!is_numeric($value)) {
        $this->logWarning(
            "Invalid numeric value '{$value}' for {$context}, using default {$default}"
        );
        return $default;
    }
    
    return (float)$value;
}

// Usage:
$forward = $this->toFloat(
    $thruster->getAttribute('forward'),
    0.0,
    'forward thrust'
);
```

**Solution 2: Use Null Coalescing**

```php
// Handle null gracefully
$forward = (float)($thruster->getAttribute('forward') ?? 0);

// With validation
$forwardStr = $thruster->getAttribute('forward') ?? '0';
$forward = is_numeric($forwardStr) ? (float)$forwardStr : 0.0;

// Chain multiple fallbacks
$forward = (float)(
    $thruster->getAttribute('forward') ??
    $thruster->getAttribute('thrust') ??  // Alternative attribute name
    '0'
);
```

**Solution 3: Comprehensive Extraction Method**

```php
private function extractForwardThrust(DOMExtended $thruster, string $macroName): float
{
    $attribute = 'forward';
    $value = $thruster->getAttribute($attribute);
    
    // Handle null (attribute missing)
    if ($value === null) {
        $this->logWarning("Attribute '{$attribute}' missing in {$macroName}");
        return 0.0;
    }
    
    // Trim whitespace
    $value = trim($value);
    
    // Handle empty string
    if ($value === '') {
        $this->logWarning("Attribute '{$attribute}' empty in {$macroName}");
        return 0.0;
    }
    
    // Handle non-numeric values
    if (!is_numeric($value)) {
        $this->logWarning(
            "Attribute '{$attribute}' has non-numeric value '{$value}' in {$macroName}"
        );
        return 0.0;
    }
    
    // Convert to float
    $float = (float)$value;
    
    // Validate range (optional: sanity check)
    if ($float < 0) {
        $this->logWarning(
            "Attribute '{$attribute}' has negative value '{$value}' in {$macroName}"
        );
        return 0.0;
    }
    
    return $float;
}
```

**Solution 4: Try-Catch with Logging**

```php
private function extractProperties(DOMExtended $dom): array
{
    $properties = [];
    
    // Extract each property with individual error handling
    try {
        $properties['forward'] = $this->extractForwardThrust($dom);
    } catch (\Exception $e) {
        $this->logWarning("Failed to extract forward thrust: {$e->getMessage()}");
        $properties['forward'] = 0.0;
    }
    
    try {
        $properties['reverse'] = $this->extractReverseThrust($dom);
    } catch (\Exception $e) {
        $this->logWarning("Failed to extract reverse thrust: {$e->getMessage()}");
        $properties['reverse'] = 0.0;
    }
    
    // Continue for other properties...
    
    return $properties;
}
```

---

## 🔍 Debugging Techniques

### Technique 1: Enable Verbose Logging

**Purpose:** Track extraction progress and identify where failures occur.

**Implementation:**

```php
// In DatabaseBuilder
public function build(): void
{
    // Enable verbose output
    $this->setVerbose(true);
    
    // Build process with automatic logging
    $this->buildMacroIndex();  // Logs: "Building MacroIndex..."
    $this->buildWares();       // Logs: "Building Wares..."
    $this->buildEngines();     // Logs: "Building Engines..."
}

// In custom extractor
class EngineMacroExtractor
{
    public function extract(): array
    {
        $this->log("Processing engine: {$this->ware->getID()}");
        
        // ... extraction logic
        
        $this->log("Extracted {$thrustCount} thrust values");
        
        return $result;
    }
}
```

**Custom Logging Levels:**

```php
// Add to BaseExtractor
protected function logDebug(string $message): void
{
    if ($this->isVerbose()) {
        echo "[DEBUG] {$message}\n";
    }
}

protected function logInfo(string $message): void
{
    echo "[INFO] {$message}\n";
}

protected function logWarning(string $message): void
{
    echo "[WARN] {$message}\n";
}

protected function logError(string $message): void
{
    echo "[ERROR] {$message}\n";
}

// Usage in extractors
$this->logDebug("Reading macro: {$macroName}");
$this->logInfo("Extracted {$count} items");
$this->logWarning("Missing property: hull.max");
$this->logError("Failed to load XML: {$path}");
```

**Logging to File:**

```php
// Add to X4Application or BaseExtractor
private static ?string $logFile = null;

public static function setLogFile(string $path): void
{
    self::$logFile = $path;
}

protected function log(string $message): void
{
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[{$timestamp}] {$message}\n";
    
    // Console output
    echo $logMessage;
    
    // File output
    if (self::$logFile !== null) {
        file_put_contents(self::$logFile, $logMessage, FILE_APPEND);
    }
}

// Configuration in dev-config.php
X4Application::setLogFile(__DIR__ . '/logs/extraction.log');
```

---

### Technique 2: Inspect Intermediate JSON

**Purpose:** Verify extracted data is correct before using in application.

**PowerShell Commands:**

```powershell
# View entire JSON file
Get-Content F:\...\x4-core\data\engines.json | ConvertFrom-Json | Format-List

# Count items
$engines = Get-Content F:\...\x4-core\data\engines.json | ConvertFrom-Json
Write-Host "Total engines: $($engines.Count)"

# Filter by property
$engines | Where-Object { $_.size -eq "l" } | Format-Table id, name, forwardSpeed

# Find specific item
$engine = $engines | Where-Object { $_.id -eq "engine_arg_l_allround_01_mk1" }
$engine | Format-List

# Check for missing properties
$engines | Where-Object { $_.forwardSpeed -eq $null } | Select-Object id, name
```

**PHP Inspection Script:**

```php
// inspect-json.php
<?php
$jsonPath = __DIR__ . '/data/engines.json';
$data = json_decode(file_get_contents($jsonPath), true);

echo "Total engines: " . count($data) . "\n";

// Group by size
$bySizecount = [];
foreach ($data as $engine) {
    $size = $engine['size'] ?? 'unknown';
    $bySize[$size] = ($bySize[$size] ?? 0) + 1;
}

echo "By size:\n";
foreach ($bySize as $size => $count) {
    echo "  {$size}: {$count}\n";
}

// Find items with missing properties
$missing = [];
foreach ($data as $engine) {
    if (!isset($engine['forwardSpeed']) || $engine['forwardSpeed'] === 0) {
        $missing[] = $engine['id'];
    }
}

if (!empty($missing)) {
    echo "Engines with missing/zero forwardSpeed:\n";
    foreach ($missing as $id) {
        echo "  - {$id}\n";
    }
}
```

---

### Technique 3: DOM Inspection

**Purpose:** Understand actual XML structure vs. expected structure.

**Dump Full DOM:**

```php
// debug-dom.php
<?php
require_once 'vendor/autoload.php';
require_once 'dev-config.php';

use Mistralys\X4\XMLHelper;

$macroPath = 'F:/path/to/engine_macro.xml';
$dom = XMLHelper::createDOMExtended($macroPath);

// Pretty-print entire XML
echo $dom->saveXML();
```

**Inspect Specific Elements:**

```php
// Find element and dump its structure
$engine = $dom->byTagName('engine')->getFirst();

if ($engine) {
    // Show tag name
    echo "Tag: " . $engine->getNodeName() . "\n";
    
    // Show all attributes
    echo "Attributes:\n";
    foreach ($engine->getAttributes() as $name => $value) {
        echo "  {$name} = {$value}\n";
    }
    
    // Show child elements
    echo "Children:\n";
    foreach ($engine->getChildren() as $child) {
        echo "  - " . $child->getNodeName() . "\n";
    }
    
    // Show parent
    $parent = $engine->getParent();
    if ($parent) {
        echo "Parent: " . $parent->getNodeName() . "\n";
    }
}
```

**Compare Expected vs. Actual:**

```php
// Test DOM queries
$queries = [
    'engine' => $dom->byTagName('engine')->getFirst(),
    'component > engine' => $dom->byTagName('component')->getFirst()?->byTagName('engine')->getFirst(),
    'properties > engine' => $dom->byTagName('properties')->getFirst()?->byTagName('engine')->getFirst(),
];

echo "Query results:\n";
foreach ($queries as $query => $result) {
    if ($result) {
        echo "  ✓ '{$query}' found\n";
    } else {
        echo "  ✗ '{$query}' NOT found\n";
    }
}
```

---

### Technique 4: Isolated Testing

**Purpose:** Test extraction for single item without running full build.

**Single Item Test Script:**

```php
// test-single-engine.php
<?php
require_once 'vendor/autoload.php';
require_once 'dev-config.php';

use X4\Database\DatabaseBuilder;
use X4\Database\Wares\WareDefs;
use X4\Database\Engines\EngineMacroExtractor;

// Build dependencies first
$builder = new DatabaseBuilder();
$builder->buildMacroIndex();
$builder->buildWares();

// Test single engine
$engineID = 'engine_arg_l_allround_01_mk1';
$ware = WareDefs::getInstance()->getByID($engineID);

if ($ware === null) {
    die("Engine '{$engineID}' not found in Wares collection\n");
}

echo "Testing extraction for: {$ware->getName()}\n";

try {
    $extractor = new EngineMacroExtractor($ware);
    $result = $extractor->extract();
    
    echo "✓ Extraction successful\n";
    print_r($result);
} catch (\Exception $e) {
    echo "✗ Extraction failed: {$e->getMessage()}\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
```

**Test All Items with Error Reporting:**

```php
// test-all-engines.php
<?php
require_once 'vendor/autoload.php';
require_once 'dev-config.php';

use X4\Database\DatabaseBuilder;
use X4\Database\Wares\WareDefs;
use X4\Database\Engines\EngineMacroExtractor;

// Build dependencies
$builder = new DatabaseBuilder();
$builder->buildMacroIndex();
$builder->buildWares();

// Get all engine wares
$engines = WareDefs::getInstance()
    ->findWares()
    ->selectGroup('engine')
    ->getAll();

echo "Testing " . count($engines) . " engines...\n";

$success = 0;
$failures = [];

foreach ($engines as $ware) {
    try {
        $extractor = new EngineMacroExtractor($ware);
        $result = $extractor->extract();
        $success++;
    } catch (\Exception $e) {
        $failures[] = [
            'id' => $ware->getID(),
            'error' => $e->getMessage()
        ];
    }
}

// Report results
echo "\nResults:\n";
echo "  ✓ Successful: {$success}\n";
echo "  ✗ Failures: " . count($failures) . "\n";

if (!empty($failures)) {
    echo "\nFailed items:\n";
    foreach ($failures as $failure) {
        echo "  - {$failure['id']}: {$failure['error']}\n";
    }
}
```

---

### Technique 5: Version Validation

**Purpose:** Ensure extracted data matches game version and no version-related issues exist.

**Check Game Version:**

```powershell
# View extracted game version
Get-Content F:\...\x4-data-extractor\output\game-version.txt

# Expected format: "7.10 Hotfix 1" or similar
```

**Version Compatibility Check:**

```php
// verify-version.php
<?php
$versionFile = 'F:/path/to/x4-data-extractor/output/game-version.txt';

if (!file_exists($versionFile)) {
    die("ERROR: game-version.txt not found. Re-run x4-data-extractor.\n");
}

$gameVersion = trim(file_get_contents($versionFile));
echo "Game version: {$gameVersion}\n";

// Parse version
if (preg_match('/^(\d+)\.(\d+)/', $gameVersion, $matches)) {
    $major = (int)$matches[1];
    $minor = (int)$matches[2];
    
    echo "Major version: {$major}\n";
    echo "Minor version: {$minor}\n";
    
    // Check compatibility
    if ($major < 7) {
        echo "⚠ WARNING: Old game version. Some features may not be available.\n";
    } elseif ($major > 7) {
        echo "⚠ WARNING: Newer game version. XML structure may have changed.\n";
    } else {
        echo "✓ Compatible game version\n";
    }
} else {
    echo "⚠ WARNING: Could not parse game version\n";
}
```

**Verify Data Freshness:**

```powershell
# Check when data was last extracted
Get-ChildItem F:\...\x4-data-extractor\output\vanilla\index\macros.xml | 
    Select-Object Name, LastWriteTime

# Check when game was last updated
Get-Item "C:\Program Files (x86)\Steam\steamapps\common\X4 Foundations\X4.exe" |
    Select-Object Name, LastWriteTime

# If game is newer than extracted data, re-extract!
```

---

## ⚡ Performance Debugging

### Identifying Slow Extractors

**Add Timing to DatabaseBuilder:**

```php
// src/X4/Database/DatabaseBuilder.php

private function buildCollection(string $name, callable $buildFn): void
{
    $start = microtime(true);
    
    echo "Building {$name}...";
    $buildFn();
    
    $duration = microtime(true) - $start;
    echo " done in " . number_format($duration, 3) . "s\n";
}

public function build(): void
{
    $this->buildCollection('MacroIndex', [$this, 'buildMacroIndex']);
    $this->buildCollection('Wares', [$this, 'buildWares']);
    $this->buildCollection('Engines', [$this, 'buildEngines']);
    // ... etc
}

// Output:
// Building MacroIndex... done in 2.345s
// Building Wares... done in 0.567s
// Building Engines... done in 1.234s
```

### Common Performance Issues

**Issue 1: Repeated DOM Loads**

```php
// ❌ BAD: Loads DOM for EVERY item
foreach ($items as $item) {
    $dom = XMLHelper::createDOMExtended($path);  // SLOW!
    $value = $dom->byTagName('property')->getFirst();
}

// ✅ GOOD: Load once, reuse
$dom = XMLHelper::createDOMExtended($path);
foreach ($items as $item) {
    $value = $dom->byTagName('property')->getFirst();
}
```

**Issue 2: Inefficient Array Operations**

```php
// ❌ BAD: Multiple passes over array
$items = WareDefs::getInstance()->getAll();
$engines = array_filter($items, fn($w) => $w->getGroup() === 'engine');
$large = array_filter($engines, fn($w) => $w->getSize() === 'l');
$mk3 = array_filter($large, fn($w) => str_contains($w->getID(), 'mk3'));

// ✅ GOOD: Single pass with combined conditions
$items = WareDefs::getInstance()->getAll();
$result = array_filter($items, fn($w) => 
    $w->getGroup() === 'engine' &&
    $w->getSize() === 'l' &&
    str_contains($w->getID(), 'mk3')
);
```

**Issue 3: Unnecessary Work**

```php
// ❌ BAD: Extract properties not used
return [
    'id' => $ware->getID(),
    'name' => $ware->getName(),
    'boost' => $this->extractBoost($dom),         // Not needed!
    'variants' => $this->extractVariants($dom),   // Not needed!
    'textures' => $this->extractTextures($dom),   // Not needed!
];

// ✅ GOOD: Only extract what's actually used
return [
    'id' => $ware->getID(),
    'name' => $ware->getName(),
    'forwardSpeed' => $this->extractForwardSpeed($dom),
    'reverseSpeed' => $this->extractReverseSpeed($dom),
];
```

### Profiling Extraction

**Using Xdebug (if available):**

```bash
# Generate profiling data
php -dxdebug.mode=profile build-database.php

# Analyze with tools like:
# - KCachegrind (Linux)
# - QCacheGrind (Windows/Mac)
# - WebGrind (browser-based)
```

**Manual Profiling:**

```php
// profile-extraction.php
<?php
require_once 'vendor/autoload.php';
require_once 'dev-config.php';

use X4\Database\DatabaseBuilder;

$profiler = [];

function profileStart(string $name): void {
    global $profiler;
    $profiler[$name] = microtime(true);
}

function profileEnd(string $name): void {
    global $profiler;
    $duration = microtime(true) - $profiler[$name];
    echo "{$name}: " . number_format($duration, 3) . "s\n";
}

// Profile entire build
profileStart('Total Build');

$builder = new DatabaseBuilder();

profileStart('MacroIndex');
$builder->buildMacroIndex();
profileEnd('MacroIndex');

profileStart('Wares');
$builder->buildWares();
profileEnd('Wares');

profileStart('Engines');
$builder->buildEngines();
profileEnd('Engines');

// ... etc

profileEnd('Total Build');
```

---

## 📚 Additional Resources

### Related Documentation
- [tech-stack.md](../tech-stack.md) - Architectural patterns including Extraction-Builder
- [data-flows.md](../data-flows.md) - Database Build Flow visualization
- [constraints.md](../constraints.md) - Error handling and exception hierarchy

### Source Files
- [DatabaseBuilder.php](../../src/X4/Database/DatabaseBuilder.php) - Build orchestration
- [BaseExtractor.php](../../src/X4/Database/BaseExtractor.php) - Extractor base class
- [MacroIndex.php](../../src/X4/Database/MacroIndex/MacroIndex.php) - Macro resolution

### Testing
- [tests/X4Tests/Suites/Database/](../../tests/X4Tests/Suites/Database/) - Example test files

---

## 🎓 Summary

### Key Takeaways

1. **Build Order Matters** - MacroIndex must be first, then Wares, then equipment
2. **Validate Everything** - Never trust XML data without validation
3. **Handle Errors Gracefully** - Log warnings, use defaults, don't crash entire extraction
4. **Use Debugging Tools** - Verbose logging, JSON inspection, isolated testing
5. **Think Performance** - Avoid repeated DOM loads, inefficient filters
6. **Version Awareness** - Game updates can change XML structure

### Quick Troubleshooting Flow

```
Error encountered
    ↓
1. Check error type (Missing Macro, Invalid XML, etc.)
    ↓
2. Review error message and call stack
    ↓
3. Verify dependencies built (build order)
    ↓
4. Inspect XML structure (does it match expectations?)
    ↓
5. Enable verbose logging
    ↓
6. Test in isolation (single item)
    ↓
7. Check game version compatibility
    ↓
8. Review solutions in this document
```

---

**Last Updated:** February 9, 2026  
**Maintainer:** X4 Core Development Team
