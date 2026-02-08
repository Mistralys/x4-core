<?php
/**
 * @package X4Tests
 * @subpackage Database\Modules
 * @see \Mistralys\X4\Database\Modules\ModuleFinder
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Modules;

use Mistralys\X4\Database\Modules\ModuleCategories;
use Mistralys\X4\Database\Modules\ModuleCategory;
use Mistralys\X4\Database\Modules\ModuleDef;
use Mistralys\X4\Database\Modules\ModuleDefs;
use Mistralys\X4\Database\Modules\ModuleFinder;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the ModuleFinder which provides filtered
 * searching capabilities for the modules collection
 *
 * @package X4Tests
 * @subpackage Database\Modules
 */
final class ModuleFinderTests extends X4TestCase
{
    private ModuleDefs $modules;

    protected function setUp(): void
    {
        parent::setUp();
        $this->modules = ModuleDefs::getInstance();
    }

    // =========================================================================
    // Basic Finder Tests
    // =========================================================================

    public function test_finderInstantiation(): void
    {
        $finder = new ModuleFinder();
        $this->assertInstanceOf(ModuleFinder::class, $finder);
    }

    public function test_getAll_noFilters(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->getAll();

        $this->assertNotEmpty($results);
        $this->assertEquals(count($this->modules->getAll()), count($results));
    }

    // =========================================================================
    // Category Filter Tests
    // =========================================================================

    public function test_selectCategory_byString(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->selectCategory(ModuleCategories::CATEGORY_PRODUCTION)->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $module) {
            $this->assertInstanceOf(ModuleDef::class, $module);
            $this->assertEquals(ModuleCategories::CATEGORY_PRODUCTION, $module->getCategoryID());
        }
    }

    public function test_selectCategory_byObject(): void
    {
        $category = ModuleCategories::getInstance()->getByID(ModuleCategories::CATEGORY_DOCKING_PIER);
        
        $finder = new ModuleFinder();
        $results = $finder->selectCategory($category)->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $module) {
            $this->assertInstanceOf(ModuleDef::class, $module);
            $this->assertEquals(ModuleCategories::CATEGORY_DOCKING_PIER, $module->getCategoryID());
        }
    }

    public function test_selectCategory_storage(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->selectCategory(ModuleCategories::CATEGORY_STORAGE)->getAll();

        foreach ($results as $module) {
            $this->assertEquals(ModuleCategories::CATEGORY_STORAGE, $module->getCategoryID());
        }
    }

    public function test_selectCategory_habitats(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->selectCategory(ModuleCategories::CATEGORY_HABITATS)->getAll();

        foreach ($results as $module) {
            $this->assertEquals(ModuleCategories::CATEGORY_HABITATS, $module->getCategoryID());
        }
    }

    public function test_selectCategory_multipleCategories(): void
    {
        $finder = new ModuleFinder();
        $results = $finder
            ->selectCategory(ModuleCategories::CATEGORY_PRODUCTION)
            ->selectCategory(ModuleCategories::CATEGORY_STORAGE)
            ->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $module) {
            $this->assertContains(
                $module->getCategoryID(),
                [ModuleCategories::CATEGORY_PRODUCTION, ModuleCategories::CATEGORY_STORAGE]
            );
        }
    }

    public function test_selectCategory_noDuplicates(): void
    {
        $finder = new ModuleFinder();
        $results = $finder
            ->selectCategory(ModuleCategories::CATEGORY_PRODUCTION)
            ->selectCategory(ModuleCategories::CATEGORY_PRODUCTION) // Select same category twice
            ->getAll();

        // Should not return duplicates
        $ids = [];
        foreach ($results as $module) {
            $id = $module->getID();
            $this->assertNotContains($id, $ids, "Duplicate module found: $id");
            $ids[] = $id;
        }
    }

    // =========================================================================
    // Label Filter Tests
    // =========================================================================

    public function test_selectLabel_exactMatch(): void
    {
        // Get a known module label
        $allModules = $this->modules->getAll();
        $this->assertNotEmpty($allModules);
        
        $sampleModule = reset($allModules);
        $label = $sampleModule->getLabel();

        $finder = new ModuleFinder();
        $results = $finder->selectLabelSearch($label)->getAll();

        $this->assertNotEmpty($results);
        
        $found = false;
        foreach ($results as $module) {
            if ($module->getID() === $sampleModule->getID()) {
                $found = true;
                break;
            }
        }
        
        $this->assertTrue($found, "Sample module not found in label-filtered results");
    }

    public function test_selectLabel_partialMatch(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->selectLabelSearch('pier')->getAll();

        if (empty($results)) {
            $this->markTestSkipped('No modules with "pier" in label found in test data');
        }

        foreach ($results as $module) {
            $this->assertStringContainsStringIgnoringCase('pier', $module->getLabel());
        }
    }

    public function test_selectLabel_caseInsensitive(): void
    {
        $finder1 = new ModuleFinder();
        $results1 = $finder1->selectLabelSearch('PRODUCTION')->getAll();

        $finder2 = new ModuleFinder();
        $results2 = $finder2->selectLabelSearch('production')->getAll();

        $finder3 = new ModuleFinder();
        $results3 = $finder3->selectLabelSearch('Production')->getAll();

        // All should return the same results (case-insensitive)
        $this->assertEquals(count($results1), count($results2));
        $this->assertEquals(count($results1), count($results3));
    }

    public function test_selectLabel_noMatch(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->selectLabelSearch('xyz_nonexistent_label_xyz')->getAll();

        $this->assertEmpty($results);
    }

    // =========================================================================
    // Combined Filter Tests
    // =========================================================================

    public function test_categoryAndLabel(): void
    {
        $finder = new ModuleFinder();
        $results = $finder
            ->selectCategory(ModuleCategories::CATEGORY_PRODUCTION)
            ->selectLabelSearch('production')
            ->getAll();

        foreach ($results as $module) {
            $this->assertEquals(ModuleCategories::CATEGORY_PRODUCTION, $module->getCategoryID());
            $this->assertStringContainsStringIgnoringCase('production', $module->getLabel());
        }
    }

    public function test_multipleCategoriesWithLabel(): void
    {
        $finder = new ModuleFinder();
        $results = $finder
            ->selectCategory(ModuleCategories::CATEGORY_DOCKING_PIER)
            ->selectCategory(ModuleCategories::CATEGORY_DOCKING_AREA)
            ->selectLabelSearch('dock')
            ->getAll();

        foreach ($results as $module) {
            $this->assertContains(
                $module->getCategoryID(),
                [ModuleCategories::CATEGORY_DOCKING_PIER, ModuleCategories::CATEGORY_DOCKING_AREA]
            );
            $this->assertStringContainsStringIgnoringCase('dock', $module->getLabel());
        }
    }

    // =========================================================================
    // Result Handling Tests
    // =========================================================================

    public function test_getAll_returnsArray(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->getAll();

        $this->assertIsArray($results);
    }

    public function test_getAll_returnsModuleDefs(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->getAll();

        foreach ($results as $module) {
            $this->assertInstanceOf(ModuleDef::class, $module);
        }
    }

    public function test_emptyResult(): void
    {
        $finder = new ModuleFinder();
        $results = $finder
            ->selectLabelSearch('xyz_definitely_does_not_exist_xyz')
            ->getAll();

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    // =========================================================================
    // Fluent Interface Tests
    // =========================================================================

    public function test_fluentInterface_selectCategory(): void
    {
        $finder = new ModuleFinder();
        $result = $finder->selectCategory(ModuleCategories::CATEGORY_PRODUCTION);

        $this->assertInstanceOf(ModuleFinder::class, $result);
        $this->assertSame($finder, $result);
    }

    public function test_fluentInterface_selectLabelSearch(): void
    {
        $finder = new ModuleFinder();
        $result = $finder->selectLabelSearch('test');

        $this->assertInstanceOf(ModuleFinder::class, $result);
        $this->assertSame($finder, $result);
    }

    public function test_fluentInterface_chaining(): void
    {
        $finder = new ModuleFinder();
        $result = $finder
            ->selectCategory(ModuleCategories::CATEGORY_PRODUCTION)
            ->selectLabelSearch('module')
            ->selectCategory(ModuleCategories::CATEGORY_STORAGE);

        $this->assertInstanceOf(ModuleFinder::class, $result);
        $this->assertSame($finder, $result);
    }

    // =========================================================================
    // Collection Reference Tests
    // =========================================================================

    public function test_getCollection(): void
    {
        $finder = new ModuleFinder();
        $collection = $finder->getCollection();

        $this->assertInstanceOf(ModuleDefs::class, $collection);
        $this->assertSame($this->modules, $collection);
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_emptyStringLabel(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->selectLabelSearch('')->getAll();

        // Empty string should match all (no filtering)
        $this->assertEquals(count($this->modules->getAll()), count($results));
    }

    public function test_specialCharactersInLabel(): void
    {
        $finder = new ModuleFinder();
        $results = $finder->selectLabelSearch('(')->getAll();

        // Should not throw exception, might return empty or filtered results
        $this->assertIsArray($results);
    }
}
