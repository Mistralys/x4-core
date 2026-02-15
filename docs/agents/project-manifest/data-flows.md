# Data Flows & Architecture

## Overview

The X4 Core library follows a **layered architecture** with clear separation between data access, business logic, and presentation layers. Game data flows from JSON files → Collection classes → UI components → HTML output.

---

## Layer Architecture

```
┌─────────────────────────────────────────────────┐
│              User Interface Layer                │
│  (Pages, Components, DataGrids, Forms)          │
└────────────────┬────────────────────────────────┘
                 │
                 ↓
┌─────────────────────────────────────────────────┐
│           Application Layer                      │
│  (X4Application, UserInterface, Messages)       │
└────────────────┬────────────────────────────────┘
                 │
                 ↓
┌─────────────────────────────────────────────────┐
│            Database Layer                        │
│  (Collections, Items, Finders)                  │
└────────────────┬────────────────────────────────┘
                 │
                 ↓
┌─────────────────────────────────────────────────┐
│           Data Storage Layer                     │
│  (JSON files in /data folder)                   │
└─────────────────────────────────────────────────┘
```

---

## Core Data Flow Patterns

### 1. Application Initialization Flow

```
X4Application (User Implementation)
    │
    ├─→ session_start()
    ├─→ initLocalization()
    ├─→ createUI(webrootURL, vendorURL)
    │       │
    │       └─→ new UserInterface()
    │               │
    │               ├─→ registerPages() [abstract method]
    │               │       └─→ $ui->registerPage(urlName, className)
    │               │
    │               └─→ registerAjaxMethods() [abstract method]
    │                       └─→ $methods->register(ajaxMethod)
    │
    └─→ $ui->render()
            │
            ├─→ Determine active page
            ├─→ Create page instance
            ├─→ page->preRender()
            ├─→ page->render()
            └─→ Output HTML
```

**Key Points:**
- Application extends `X4Application`
- Must implement abstract methods for page/AJAX registration
- UI creation is explicit via `createUI()`
- Pages are created on-demand per request

---

### 2. Database Query Flow

```
UI Component / Page
    │
    ↓
Request Data via Collection
    │
    ├─→ Simple Query: WareDefs::getInstance()->getByID('ware_id')
    │       │
    │       └─→ Read from cached collection
    │
    └─→ Filtered Query: WareDefs::getInstance()->findWares()
            │
            ├─→ selectGroup(WareGroups::GROUP_ENGINES)
            ├─→ selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
            ├─→ selectLabel('Argon')
            └─→ getAll()
                    │
                    └─→ Filter collection in memory
                            │
                            └─→ Return WareDef[]
```

**Key Points:**
- Collections are singletons (`getInstance()`)
- Data loaded lazily on first access
- Finders provide fluent filtering without database queries
- All filtering happens in-memory (no query language)

---

### 2.1. Ship Equipment Query Flow

```
UI Component / Page
    │
    ↓
Get Ship Instance
    │
    └─→ ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a')
            │
            └─→ ShipDef instance
                    │
                    ↓
Request Compatible Equipment
    │
    ├─→ ship.getEngines()
    ├─→ ship.getShields()
    ├─→ ship.getWeapons()
    └─→ ship.findEquipmentForSlot(slotTypeID)
            │
            └─→ ShipEquipmentFinder instance
                    │
                    ├─→ selectDataSource(...)
                    ├─→ selectSize('l')
                    ├─→ selectTag('equipment')
                    └─→ getAll()
                            │
                            └─→ Filter WareDefs collection
                                    │
                                    ├─→ Check ware is equipment
                                    ├─→ Match ware group to slot type
                                    ├─→ Verify ship.canEquip(ware)
                                    ├─→ Apply additional filters
                                    └─→ Return compatible WareDef[]
```

**Key Points:**
- Ship-specific filtering combines slot type + ship compatibility
- Leverages existing `ShipDef::canEquip()` compatibility logic
- Pre-filters by ware group for performance (engines, shields, etc.)
- Returns standard `WareDef` instances (not equipment-specific classes)
- Supports all Finder features (data source, label search, custom filters)

---

### 3. Page Rendering Flow

#### Simple Page

```
User Request
    │
    ↓
UserInterface->render()
    │
    ├─→ getActivePageID() from $_GET['page']
    ├─→ createPage(pageID)
    │       │
    │       └─→ new PageClass($ui)
    │
    ├─→ page->preRender()    [Data loading, validation]
    │
    ├─→ page->_render()      [HTML generation]
    │       │
    │       ├─→ Access collections: ShipDefs::getInstance()
    │       ├─→ Create UI components: $ui->createDataGrid()
    │       └─→ Echo HTML content
    │
    └─→ Wrap in layout template
            └─→ Include nav, header, footer, messages
```

#### Page with Sub-Navigation

```
User Request
    │
    ↓
UserInterface->render()
    │
    ├─→ createPage(pageID)
    │       │
    │       └─→ new PageWithNavClass($ui)
    │
    ├─→ page->preRender()
    │       │
    │       ├─→ initSubPages()     [Register sub-pages]
    │       └─→ getSubPage()       [Based on $_GET['view']]
    │
    └─→ page->_render()
            │
            ├─→ subPage->generateOutput()
            │       │
            │       └─→ subPage->renderContent()
            │
            └─→ Render with sub-navigation
```

**Key Points:**
- Request parameters: `page` (top-level), `view` (sub-page)
- `preRender()` for data loading
- `_render()` for output generation
- Sub-pages handle their own content rendering

---

### 4. DataGrid Rendering Flow

```
Page or Component
    │
    ↓
$grid = $ui->createDataGrid()
    │
    ├─→ addColumn('id', 'ID')
    ├─→ addColumn('name', 'Name')
    ├─→ addColumn('faction', 'Faction')
    │
    ├─→ Get data: ShipDefs::getInstance()->getAll()
    │
    ├─→ addRowsFromObjects($ships)
    │       │
    │       └─→ For each ship:
    │               │
    │               ├─→ createRow()
    │               ├─→ setValue('id', $ship->getID())
    │               ├─→ setValue('name', $ship->getLabel())
    │               └─→ setValue('faction', $ship->getBuilderFaction()->getLabel())
    │
    └─→ echo $grid->render()
            │
            └─→ Generates HTML <table> with Bootstrap classes
```

**Key Points:**
- Columns defined first
- Rows added from arrays or objects
- Automatic property mapping for objects
- Supports custom cell decorations and formatting

---

### 5. AJAX Request Flow

```
Client JavaScript
    │
    └─→ POST /ajax.php
            │
            ├─→ method: 'methodName'
            ├─→ params: {...}
            │
            ↓
AjaxMethods->handleRequest()
    │
    ├─→ Get method name from $_POST
    ├─→ Find registered method
    │       │
    │       └─→ AjaxMethod instance
    │
    ├─→ method->process()
    │       │
    │       ├─→ Validate input
    │       ├─→ Access collections
    │       ├─→ Perform operations
    │       └─→ Return data array
    │
    └─→ JSON response
            │
            ├─→ {status: 'success', data: {...}}
            └─→ OR {status: 'error', message: '...'}
```

**Key Points:**
- AJAX methods registered in `registerAjaxMethods()`
- Each method implements `AjaxMethodInterface`
- Returns associative arrays (auto-encoded to JSON)
- Exception handling built-in

---

### 6. Translation Flow

```
Code
    │
    └─→ Language->t(pageID, textID)
            │
            ├─→ TranslationDefs::getInstance()
            │       │
            │       └─→ Load from lang-{id}_{locale}.json
            │
            └─→ Return translated string

Alternative (code format):
    │
    └─→ Language->ts('{20101,42}')
            │
            ├─→ Parse code format
            ├─→ Extract pageID and textID
            └─→ Call t(pageID, textID)
```

**Key Points:**
- Translations stored in JSON by language
- Multiple languages supported (7 included)
- Translation IDs from game files
- Code format: `{pageID,textID}`

---

### 7. Database Build Flow

```
composer build
    │
    └─→ DatabaseBuilder::build()
            │
            ├─→ extractMacroIndex()
            │       └─→ Read unpacked game files → macro-index.json
            │
            ├─→ extractTranslations()
            │       └─→ Read .xml files → lang-*.json (7 files)
            │
            ├─→ extractDataSources()
            │       └─→ Detect DLCs → data-sources.json
            │
            ├─→ extractFactions()
            │       └─→ Parse factions → factions.json
            │
            ├─→ extractWares()
            │       └─→ Parse wares → wares.json
            │
            ├─→ extractEngines()
            │       └─→ Parse engines from wares → engines.json
            │               └─→ Resolve makerRace as array (space-separated)
            │
            ├─→ extractShields()
            │       └─→ Parse shields from wares → shields.json
            │               └─→ Resolve makerRace as array (space-separated)
            │
            ├─→ extractModules()
            │       └─→ Parse modules → modules.json
            │               └─→ Resolve builderFaction as array (space-separated)
            │
            ├─→ extractBlueprints()
            │       └─→ Parse blueprints → blueprints.json
            │
            └─→ extractShips()
                    └─→ Derive ships from wares → ships.json
                            ├─→ Extract physics (drag, inertia, jerk, accfactors)
                            │   └─→ Uses BaseExtractor::resolveNestedPropertyAttribute()
                            │       to extract nested <property> elements with attributes
                            ├─→ Resolve storage macros → cargoCapacity + cargoType
                            │   └─→ Uses str_starts_with($ref, 'con_storage') for
                            │       precise matching of X4's cargo storage connections
                            └─→ Resolve builderFaction as array (space-separated)
                                └─→ Handles multi-faction entries like "argon teladi"
```

**Key Points:**
- Depends on unpacked game data (X4 Data Extractor)
- Order matters (macros before wares, wares before ships)
- Each step is idempotent
- Can run individual extractors
- **Multi-faction/race support**: Extractors parse space-separated values from XML (e.g., `makerrace="argon teladi"`) and store as arrays in JSON (`builderFactionIDs: ["argon", "teladi"]` or `makerRaces: ["argon", "teladi"]`)

---

### 8. UI Component Creation Flow

```
Page Code
    │
    ├─→ Create Button
    │       │
    │       └─→ Button::create('Save')
    │               ->setIcon(Icon::save())
    │               ->colorPrimary()
    │               ->makeSubmit('saveBtn')
    │               ->render()
    │
    ├─→ Create Icon
    │       │
    │       └─→ Icon::typeSolid('check')
    │               ->colorSuccess()
    │               ->setTooltip('Verified')
    │               ->render()
    │
    └─→ Create Text
            │
            └─→ Text::create('Warning!')
                    ->colorWarning()
                    ->render()
```

**Key Points:**
- Fluent chainable interface
- Static factory methods preferred
- Final `render()` returns HTML string
- Bootstrap classes applied automatically

---

### 9. Message Flow

```
Page Action
    │
    ├─→ Success: $ui->getMessages()->addSuccess('Item saved')
    ├─→ Error:   $ui->getMessages()->addError('Invalid input', 101)
    ├─→ Warning: $ui->getMessages()->addWarning('Deprecated', 202)
    └─→ Info:    $ui->getMessages()->addInfo('Processing...')
            │
            ├─→ Stored in Messages collection
            │
            └─→ On shutdown: writeToSession()
                    │
                    └─→ $_SESSION['messages']

Next Request
    │
    └─→ Messages loaded from session
            │
            ├─→ Rendered in UI template
            │       └─→ Bootstrap alerts
            │
            └─→ clear() after display
```

**Key Points:**
- Messages persist across redirects via session
- Four types: success, info, warning, error
- Optional error codes
- Auto-cleared after display

---

## Data Relationships

### Entity Relationships

```
WareDef
    ├─→ WareGroup (via groupID)
    ├─→ DataSourceDef (via dataSourceID)
    ├─→ MacroFileDef (via macroID)
    ├─→ FactionDef[] (via factionIDs)
    ├─→ VariantID
    ├─→ size (string: 's', 'm', 'l', 'xl' for equipment size)
    └─→ component (object with tags[] for component compatibility)

ShipDef
    ├─→ WareDef (via wareID) [Ships are wares]
    ├─→ ShipClass (via classID)
    ├─→ ShipSize (via sizeID)
    ├─→ FactionDef (builder via builderFactionID)
    ├─→ FactionDef[] (used by via usedBy)
    ├─→ DataSourceDef (via dataSourceID)
    ├─→ VariantID
    └─→ StorageMacro (via con_storage connection → cargoCapacity, cargoType)

BlueprintDef
    ├─→ WareDef (via wareID)
    ├─→ BlueprintCategory
    ├─→ FactionDef (via faction detection)
    └─→ VariantID

ModuleDef
    ├─→ WareDef (via wareID) [Modules are wares]
    ├─→ ModuleCategory (via categoryID)
    ├─→ FactionDef (builder via builderFactionID)
    ├─→ FactionDef (housing via housingFactionID)
    └─→ VariantID
```

### Variant System

```
VariantID
    ├─→ number: int (1, 2, 3...)
    ├─→ qualifier: ?string ('admin', 'police', etc.)
    └─→ mark: ?string ('mk1', 'mk2', 'mk3')

Examples:
    - 'ship_arg_s_scout_01_a'      → VariantID(1, 'a')
    - 'ship_arg_s_scout_02_b_mk2'  → VariantID(2, 'b', 'mk2')
    - 'module_gen_prod_food_01'    → VariantID(1)
```

---

## Common Interaction Patterns

### Pattern: Filtered List Page

```php
// 1. Get all items
$allShips = ShipDefs::getInstance()->getAll();

// 2. Or use finder for filtering
$argonFighters = ShipDefs::getInstance()
    ->findShips()
    ->selectBuilderFaction(KnownFactions::FACTION_ARGON_FEDERATION)
    ->selectClass(ShipClasses::CLASS_FIGHTER)
    ->getAll();

// 3. Display in DataGrid
$grid = $ui->createDataGrid();
$grid->addColumn('name', 'Ship Name');
$grid->addColumn('faction', 'Builder');
$grid->addRowsFromObjects($argonFighters);
echo $grid->render();
```

### Pattern: Detail View Page

```php
// 1. Get ID from request
$shipID = $request->getParam('id');

// 2. Load item
$ship = ShipDefs::getInstance()->getByID($shipID);

// 3. Load related data
$builder = $ship->getBuilderFaction();
$ware = $ship->getWare();
$class = $ship->getClass();

// 4. Render details
echo "<h2>{$ship->getLabel()}</h2>";
echo "<p>Builder: {$builder->getLabel()}</p>";
echo "<p>Class: {$class->getLabel()}</p>";
```

### Pattern: Form Submission with Messages

```php
if ($request->isPost()) {
    try {
        // Process form
        $result = processForm($request);
        
        // Success message
        $ui->getMessages()->addSuccess('Saved successfully');
        
        // Redirect
        $this->redirect($this->getURL());
    } catch (Exception $e) {
        // Error message
        $ui->getMessages()->addError($e->getMessage(), $e->getCode());
    }
}
```

---

## Performance Considerations

### Lazy Loading

- Collections load data only on first access
- JSON files cached in memory after initial load
- No database queries (all in-memory filtering)

### In-Memory Filtering

- Finders filter loaded collections
- Suitable for datasets up to ~10K items
- No query optimization needed

### Session Usage

- Messages stored in session
- Minimal session data
- Cleared after display

---

## Extension Points

### Adding New Collections

1. Create `*Def.php` (item class)
2. Create `*Defs.php` (collection class)
3. Create `*Extractor.php` (data extraction)
4. Add Composer script command
5. Generate JSON data file

### Adding New Pages

1. Extend `BasePage` or `BasePageWithNav`
2. Implement abstract methods
3. Register in `registerPages()`

### Adding AJAX Methods

1. Implement `AjaxMethodInterface`
2. Register in `registerAjaxMethods()`

### Adding UI Components

1. Create component class
2. Implement `render()` method
3. Use fluent interface pattern
4. Return HTML string
