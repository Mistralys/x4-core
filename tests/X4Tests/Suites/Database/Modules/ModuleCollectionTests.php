<?php
/**
 * @package X4Tests
 * @subpackage Database\Modules
 * @see \Mistralys\X4\Database\Modules\ModuleDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Modules;

use AppUtils\Collections\RecordNotExistsException;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Modules\ModuleCategory;
use Mistralys\X4\Database\Modules\ModuleDef;
use Mistralys\X4\Database\Modules\ModuleDefs;
use Mistralys\X4\Database\Modules\ModuleFinder;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the ModuleDefs collection which manages
 * all game modules (station modules, connection modules, etc.)
 *
 * @package X4Tests
 * @subpackage Database\Modules
 */
final class ModuleCollectionTests extends X4TestCase
{
    private ModuleDefs $modules;

    protected function setUp(): void
    {
        parent::setUp();
        $this->modules = ModuleDefs::getInstance();
    }

    // =========================================================================
    // Collection Basic Tests
    // =========================================================================

    public function test_getInstance(): void
    {
        $instance1 = ModuleDefs::getInstance();
        $instance2 = ModuleDefs::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function test_getAll(): void
    {
        $modules = $this->modules->getAll();

        $this->assertNotEmpty($modules);
        $this->assertGreaterThan(50, count($modules), 'Should have at least 50 modules');

        // Verify all items are ModuleDef instances
        foreach ($modules as $module) {
            $this->assertInstanceOf(ModuleDef::class, $module);
        }
    }

    public function test_getByID(): void
    {
        $modules = $this->modules->getAll();
        $this->assertNotEmpty($modules);
        
        $firstModule = reset($modules);
        $retrieved = $this->modules->getByID($firstModule->getID());

        $this->assertInstanceOf(ModuleDef::class, $retrieved);
        $this->assertSame($firstModule->getID(), $retrieved->getID());
    }

    public function test_getByID_specificModule(): void
    {
        $module = $this->modules->getByID('module_arg_pier_l_01');

        $this->assertInstanceOf(ModuleDef::class, $module);
        $this->assertEquals('module_arg_pier_l_01', $module->getID());
    }

    public function test_getByID_invalid(): void
    {
        $this->expectException(RecordNotExistsException::class);
        $this->modules->getByID('nonexistent_module_xyz');
    }

    public function test_getDefault(): void
    {
        $defaultModule = $this->modules->getDefault();

        $this->assertInstanceOf(ModuleDef::class, $defaultModule);
        $this->assertNotEmpty($defaultModule->getID());
    }

    public function test_idExists_true(): void
    {
        $modules = $this->modules->getAll();
        $this->assertNotEmpty($modules);
        
        $firstModule = reset($modules);
        $this->assertTrue($this->modules->idExists($firstModule->getID()));
    }

    public function test_idExists_false(): void
    {
        $this->assertFalse($this->modules->idExists('nonexistent_module'));
        $this->assertFalse($this->modules->idExists(''));
    }

    public function test_collectionNotEmpty(): void
    {
        $this->assertNotEmpty($this->modules->getAll());
    }

    // =========================================================================
    // Data File Tests
    // =========================================================================

    public function test_getDataFile(): void
    {
        $dataFile = $this->modules->getDataFile();

        $this->assertInstanceOf(JSONFile::class, $dataFile);
        $this->assertTrue($dataFile->exists());
    }

    public function test_dataFileContainsValidJSON(): void
    {
        $dataFile = $this->modules->getDataFile();
        $data = $dataFile->getData();

        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

    // =========================================================================
    // Finder Tests
    // =========================================================================

    public function test_findByMacro(): void
    {
        $module = $this->modules->findByMacro('struct_arg_vertical_01_macro');

        $this->assertInstanceOf(ModuleDef::class, $module);
        $this->assertEquals('struct_arg_vertical_01_macro', $module->getMacroID());
    }

    public function test_findByMacro_notFound(): void
    {
        $module = $this->modules->findByMacro('nonexistent_macro');

        $this->assertNull($module);
    }

    public function test_find_byWareID(): void
    {
        $modules = $this->modules->getAll();
        $this->assertNotEmpty($modules);
        
        $firstModule = reset($modules);
        $found = $this->modules->find($firstModule->getID());

        $this->assertInstanceOf(ModuleDef::class, $found);
        $this->assertEquals($firstModule->getID(), $found->getID());
    }

    public function test_find_byMacroID(): void
    {
        $module = $this->modules->find('struct_arg_vertical_01_macro');

        $this->assertInstanceOf(ModuleDef::class, $module);
        $this->assertEquals('struct_arg_vertical_01_macro', $module->getMacroID());
    }

    public function test_find_notFound(): void
    {
        $module = $this->modules->find('nonexistent_module_or_macro');

        $this->assertNull($module);
    }

    // =========================================================================
    // Data Integrity Tests
    // =========================================================================

    public function test_allModulesHaveCategories(): void
    {
        foreach ($this->modules->getAll() as $module) {
            $category = $module->getCategory();
            $this->assertInstanceOf(ModuleCategory::class, $category);
            $this->assertNotEmpty($category->getID());
        }
    }

    public function test_allModulesHaveBuilderFactions(): void
    {
        foreach ($this->modules->getAll() as $module) {
            $faction = $module->getBuilderFaction();
            $this->assertInstanceOf(FactionDef::class, $faction);
            $this->assertNotEmpty($faction->getID());
        }
    }

    public function test_allModulesHaveMacroIDs(): void
    {
        foreach ($this->modules->getAll() as $module) {
            $this->assertNotEmpty($module->getMacroID());
        }
    }

    public function test_allModulesHaveSizes(): void
    {
        foreach ($this->modules->getAll() as $module) {
            $size = $module->getSize();
            $this->assertIsString($size);
            // Note: Size can be empty for some modules
        }
    }

    public function test_noDuplicateIDs(): void
    {
        $ids = [];
        foreach ($this->modules->getAll() as $module) {
            $id = $module->getID();
            $this->assertNotContains($id, $ids, "Duplicate module ID found: $id");
            $ids[] = $id;
        }
    }

    public function test_allMacroIDsValid(): void
    {
        foreach ($this->modules->getAll() as $module) {
            $macroID = $module->getMacroID();
            $this->assertIsString($macroID);
            $this->assertNotEmpty($macroID);
            
            // Macro IDs typically end with _macro
            $this->assertStringEndsWith('_macro', $macroID);
        }
    }

    // =========================================================================
    // Category Distribution Tests
    // =========================================================================

    public function test_multipleCategories(): void
    {
        $categories = [];
        
        foreach ($this->modules->getAll() as $module) {
            $categoryID = $module->getCategoryID();
            if (!in_array($categoryID, $categories, true)) {
                $categories[] = $categoryID;
            }
        }

        $this->assertGreaterThan(3, count($categories), 
            'Should have at least 4 different module categories');
    }

    public function test_multipleBuilders(): void
    {
        $builders = [];
        
        foreach ($this->modules->getAll() as $module) {
            $builderID = $module->getBuilderFactionID();
            if (!in_array($builderID, $builders, true)) {
                $builders[] = $builderID;
            }
        }

        $this->assertGreaterThan(2, count($builders), 
            'Should have modules from at least 3 different builder factions');
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_moduleIDsCaseSensitive(): void
    {
        $modules = $this->modules->getAll();
        $this->assertNotEmpty($modules);
        
        $firstModule = reset($modules);
        $id = $firstModule->getID();
        
        $this->assertTrue($this->modules->idExists($id));
        
        // IDs are case-sensitive
        if ($id !== strtoupper($id)) {
            $this->assertFalse($this->modules->idExists(strtoupper($id)));
        }
    }
}
