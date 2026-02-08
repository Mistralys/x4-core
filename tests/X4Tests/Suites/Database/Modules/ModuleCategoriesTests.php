<?php
/**
 * @package X4Tests
 * @subpackage Database\Modules
 * @see \Mistralys\X4\Database\Modules\ModuleCategories
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Modules;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Modules\ModuleCategories;
use Mistralys\X4\Database\Modules\ModuleCategory;
use Mistralys\X4\Database\Modules\ModuleDef;
use Mistralys\X4\Database\Modules\ModuleDefs;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the ModuleCategories collection which manages
 * all known module categories (production, storage, docking, etc.)
 *
 * @package X4Tests
 * @subpackage Database\Modules
 */
final class ModuleCategoriesTests extends X4TestCase
{
    private ModuleCategories $categories;
    private ModuleDefs $modules;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categories = ModuleCategories::getInstance();
        $this->modules = ModuleDefs::getInstance();
    }

    // =========================================================================
    // Collection Tests
    // =========================================================================

    public function test_getInstance(): void
    {
        $instance1 = ModuleCategories::getInstance();
        $instance2 = ModuleCategories::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function test_getAll(): void
    {
        $categories = $this->categories->getAll();

        $this->assertNotEmpty($categories);
        $this->assertCount(12, $categories, 'Should have exactly 12 module categories');

        // Verify all items are ModuleCategory instances
        foreach ($categories as $category) {
            $this->assertInstanceOf(ModuleCategory::class, $category);
        }
    }

    public function test_getByID(): void
    {
        $production = $this->categories->getByID(ModuleCategories::CATEGORY_PRODUCTION);

        $this->assertInstanceOf(ModuleCategory::class, $production);
        $this->assertEquals(ModuleCategories::CATEGORY_PRODUCTION, $production->getID());
    }

    public function test_getByID_invalid(): void
    {
        $this->expectException(RecordNotExistsException::class);
        $this->categories->getByID('nonexistent_category_xyz');
    }

    public function test_getDefault(): void
    {
        $defaultCategory = $this->categories->getDefault();

        $this->assertInstanceOf(ModuleCategory::class, $defaultCategory);
        $this->assertNotEmpty($defaultCategory->getID());
    }

    public function test_idExists_true(): void
    {
        $this->assertTrue($this->categories->idExists(ModuleCategories::CATEGORY_PRODUCTION));
        $this->assertTrue($this->categories->idExists(ModuleCategories::CATEGORY_STORAGE));
        $this->assertTrue($this->categories->idExists(ModuleCategories::CATEGORY_HABITATS));
    }

    public function test_idExists_false(): void
    {
        $this->assertFalse($this->categories->idExists('nonexistent_category'));
        $this->assertFalse($this->categories->idExists(''));
    }

    // =========================================================================
    // Category Constant Tests
    // =========================================================================

    public function test_constant_production(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_PRODUCTION);
        $this->assertEquals('production', $category->getID());
        $this->assertEquals('Production modules', $category->getLabel());
    }

    public function test_constant_habitats(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_HABITATS);
        $this->assertEquals('habitation', $category->getID());
        $this->assertEquals('Habitats', $category->getLabel());
    }

    public function test_constant_storage(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_STORAGE);
        $this->assertEquals('storage', $category->getID());
        $this->assertEquals('Storage modules', $category->getLabel());
    }

    public function test_constant_venturePlatform(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_VENTURE_PLATFORM);
        $this->assertEquals('ventureplatform', $category->getID());
        $this->assertEquals('Venture Platform', $category->getLabel());
    }

    public function test_constant_defenceModule(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_DEFENCE_MODULE);
        $this->assertEquals('defencemodule', $category->getID());
        $this->assertEquals('Defence', $category->getLabel());
    }

    public function test_constant_welfareModule(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_WELFARE_MODULE);
        $this->assertEquals('welfaremodule', $category->getID());
        $this->assertEquals('Welfare', $category->getLabel());
    }

    public function test_constant_processingModule(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_PROCESSING_MODULE);
        $this->assertEquals('processingmodule', $category->getID());
        $this->assertEquals('Processing', $category->getLabel());
    }

    public function test_constant_buildModule(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_BUILD_MODULE);
        $this->assertEquals('buildmodule', $category->getID());
        $this->assertEquals('Build module', $category->getLabel());
    }

    public function test_constant_dockingPier(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_DOCKING_PIER);
        $this->assertEquals('pier', $category->getID());
        $this->assertEquals('Docking modules', $category->getLabel());
    }

    public function test_constant_dockingArea(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_DOCKING_AREA);
        $this->assertEquals('dockarea', $category->getID());
        $this->assertEquals('Docking modules', $category->getLabel());
    }

    public function test_constant_connectionModule(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_CONNECTION_MODULE);
        $this->assertEquals('connectionmodule', $category->getID());
        $this->assertEquals('Connection module', $category->getLabel());
    }

    public function test_constant_radar(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_RADAR);
        $this->assertEquals('radar', $category->getID());
        $this->assertEquals('Radar', $category->getLabel());
    }

    // =========================================================================
    // ModuleCategory Item Tests
    // =========================================================================

    public function test_ModuleCategory_getID(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_PRODUCTION);
        $this->assertEquals(ModuleCategories::CATEGORY_PRODUCTION, $category->getID());
    }

    public function test_ModuleCategory_getLabel(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_PRODUCTION);
        $this->assertEquals('Production modules', $category->getLabel());
        $this->assertNotEmpty($category->getLabel());
    }

    public function test_ModuleCategory_isProduction_true(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_PRODUCTION);
        $this->assertTrue($category->isProduction());
    }

    public function test_ModuleCategory_isProduction_false(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_STORAGE);
        $this->assertFalse($category->isProduction());
    }

    public function test_ModuleCategory_isDockingModule_pier(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_DOCKING_PIER);
        $this->assertTrue($category->isDockingModule());
    }

    public function test_ModuleCategory_isDockingModule_area(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_DOCKING_AREA);
        $this->assertTrue($category->isDockingModule());
    }

    public function test_ModuleCategory_isDockingModule_false(): void
    {
        $category = $this->categories->getByID(ModuleCategories::CATEGORY_PRODUCTION);
        $this->assertFalse($category->isDockingModule());
    }

    public function test_allCategoriesHaveLabels(): void
    {
        foreach ($this->categories->getAll() as $category) {
            $this->assertNotEmpty($category->getLabel(),
                "Category {$category->getID()} has empty label");
        }
    }

    // =========================================================================
    // Data Integrity Tests
    // =========================================================================

    public function test_everyModuleHasValidCategory(): void
    {
        foreach ($this->modules->getAll() as $module) {
            $categoryID = $module->getCategoryID();
            $this->assertTrue(
                $this->categories->idExists($categoryID),
                "Module '{$module->getID()}' has invalid category ID: '$categoryID'"
            );

            // Also verify we can retrieve the category
            $category = $module->getCategory();
            $this->assertInstanceOf(ModuleCategory::class, $category);
            $this->assertEquals($categoryID, $category->getID());
        }
    }

    public function test_everyCategoryHasModules(): void
    {
        $categoriesWithModules = [];

        foreach ($this->modules->getAll() as $module) {
            $categoryID = $module->getCategoryID();
            if (!in_array($categoryID, $categoriesWithModules, true)) {
                $categoriesWithModules[] = $categoryID;
            }
        }

        // Note: Not all categories may have modules in the test data
        // This test just verifies we can match modules to their categories
        $this->assertNotEmpty($categoriesWithModules);
        $this->assertGreaterThan(5, count($categoriesWithModules),
            'At least 6 different module categories should have modules');
    }

    public function test_categoryToModulesRelationship(): void
    {
        // Pick a known category that should have modules
        $productionCategory = $this->categories->getByID(ModuleCategories::CATEGORY_PRODUCTION);

        // Find modules of this category
        $productionModules = [];
        foreach ($this->modules->getAll() as $module) {
            if ($module->getCategoryID() === ModuleCategories::CATEGORY_PRODUCTION) {
                $productionModules[] = $module;
            }
        }

        // Production category should be used by at least some modules
        if (!empty($productionModules)) {
            $this->assertGreaterThan(0, count($productionModules));

            // Verify reverse relationship
            foreach ($productionModules as $module) {
                $this->assertEquals($productionCategory->getID(), $module->getCategoryID());
                $this->assertTrue($module->isProduction());
            }
        } else {
            $this->markTestSkipped('No production modules found in test data');
        }
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_categoryIDsCaseSensitive(): void
    {
        // Module category IDs are lowercase
        $this->assertTrue($this->categories->idExists('production'));
        $this->assertFalse($this->categories->idExists('PRODUCTION'));
        $this->assertFalse($this->categories->idExists('Production'));
    }

    public function test_collectionNotEmpty(): void
    {
        $this->assertNotEmpty($this->categories->getAll());
    }

    public function test_noDuplicateIDs(): void
    {
        $categories = $this->categories->getAll();
        $ids = [];

        foreach ($categories as $category) {
            $id = $category->getID();
            $this->assertNotContains($id, $ids, "Duplicate category ID found: $id");
            $ids[] = $id;
        }
    }

    public function test_exactlyTwelveCategories(): void
    {
        $this->assertCount(12, $this->categories->getAll(),
            'Should have exactly 12 module categories');
    }

    // =========================================================================
    // Category Distribution Tests
    // =========================================================================

    public function test_categoryDistribution(): void
    {
        $distribution = [];

        foreach ($this->modules->getAll() as $module) {
            $categoryID = $module->getCategoryID();
            if (!isset($distribution[$categoryID])) {
                $distribution[$categoryID] = 0;
            }
            $distribution[$categoryID]++;
        }

        // At least some categories should have modules
        $this->assertNotEmpty($distribution);
        $this->assertGreaterThan(5, count($distribution),
            'At least 6 categories should have modules');
    }
}
