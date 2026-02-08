<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Modules;

use AppUtils\Collections\RecordNotExistsException;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\Modules\ModuleCategories;
use Mistralys\X4\Database\Modules\ModuleCategory;
use Mistralys\X4\Database\Modules\ModuleDef;
use Mistralys\X4\Database\Modules\ModuleDefs;
use X4Tests\Helpers\X4TestCase;

class ModuleTests extends X4TestCase
{
    public function test_getRace() : void
    {
        $id = 'module_arg_pier_l_01';

        $this->assertSame(
            KnownFactions::FACTION_ARGON_FEDERATION,
            ModuleDefs::getInstance()->getByID($id)->getBuilderFaction()->getID()
        );
    }

    /**
     * An exception must be thrown for unknown module IDs.
     */
    public function test_createFromUnknownID() : void
    {
        $id = 'unknown_module';

        $this->expectException(RecordNotExistsException::class);

        ModuleDefs::getInstance()->getByID($id);
    }

    public function test_allModulesHaveARace() : void
    {
        $modules = ModuleDefs::getInstance()->getAll();

        foreach($modules as $module) {
            $module->getBuilderFaction();
        }

        $this->addToAssertionCount(1);
    }

    public function test_getCategory() : void
    {
        $id = 'module_arg_conn_vertical_01';

        $this->assertSame(
            ModuleCategories::CATEGORY_CONNECTION_MODULE,
            ModuleDefs::getInstance()->getByID($id)->getCategory()->getID()
        );
    }

    public function test_findByMacro() : void
    {
        $this->assertNotNull(
            ModuleDefs::getInstance()->findByMacro('struct_arg_vertical_01_macro')
        );
    }

    // =========================================================================
    // Additional Property Tests
    // =========================================================================

    public function test_getHullHitpoints(): void
    {
        $module = $this->getSampleModule();
        $hull = $module->getHullHitpoints();
        $this->assertIsInt($hull);
        $this->assertGreaterThanOrEqual(0, $hull);
    }

    public function test_getDroneCapacity(): void
    {
        $module = $this->getSampleModule();
        $capacity = $module->getDroneCapacity();
        $this->assertIsInt($capacity);
        $this->assertGreaterThanOrEqual(0, $capacity);
    }

    public function test_getCargoCapacity(): void
    {
        $module = $this->getSampleModule();
        $capacity = $module->getCargoCapacity();
        $this->assertIsInt($capacity);
        $this->assertGreaterThanOrEqual(0, $capacity);
    }

    public function test_getHousingCapacity(): void
    {
        $module = $this->getSampleModule();
        $capacity = $module->getHousingCapacity();
        $this->assertIsInt($capacity);
        $this->assertGreaterThanOrEqual(0, $capacity);
    }

    public function test_getSize(): void
    {
        $module = $this->getSampleModule();
        $size = $module->getSize();
        $this->assertIsString($size);
        // Note: Size can be empty for some modules
    }

    public function test_getCargoType(): void
    {
        $module = $this->getSampleModule();
        $cargoType = $module->getCargoType();
        $this->assertIsString($cargoType);
    }

    public function test_getMacroID(): void
    {
        $module = $this->getSampleModule();
        $macroID = $module->getMacroID();
        $this->assertIsString($macroID);
        $this->assertNotEmpty($macroID);
    }

    public function test_getLabel(): void
    {
        $module = $this->getSampleModule();
        $label = $module->getLabel();
        $this->assertIsString($label);
        $this->assertNotEmpty($label);
    }

    public function test_getID(): void
    {
        $module = $this->getSampleModule();
        $id = $module->getID();
        $this->assertIsString($id);
        $this->assertNotEmpty($id);
    }

    public function test_getVariantID(): void
    {
        $module = $this->getSampleModule();
        $variantID = $module->getVariantID();
        $this->assertInstanceOf(\Mistralys\X4\Database\Core\VariantID::class, $variantID);
    }

    public function test_getCategoryID(): void
    {
        $module = $this->getSampleModule();
        $categoryID = $module->getCategoryID();
        $this->assertIsString($categoryID);
        $this->assertNotEmpty($categoryID);
    }

    public function test_getBuilderFactionID(): void
    {
        $module = $this->getSampleModule();
        $factionID = $module->getBuilderFactionID();
        $this->assertIsString($factionID);
        $this->assertNotEmpty($factionID);
    }

    public function test_getHousingFactionID(): void
    {
        $module = $this->getSampleModule();
        $factionID = $module->getHousingFactionID();
        $this->assertIsString($factionID);
    }

    public function test_isProduction(): void
    {
        // Find a production module
        $productionModule = null;
        foreach (ModuleDefs::getInstance()->getAll() as $module) {
            if ($module->isProduction()) {
                $productionModule = $module;
                break;
            }
        }

        if ($productionModule === null) {
            $this->markTestSkipped('No production modules found in test data');
        }

        $this->assertTrue($productionModule->isProduction());
        $this->assertEquals(ModuleCategories::CATEGORY_PRODUCTION, $productionModule->getCategoryID());
    }

    public function test_isProduction_false(): void
    {
        // Find a non-production module
        $nonProductionModule = null;
        foreach (ModuleDefs::getInstance()->getAll() as $module) {
            if (!$module->isProduction()) {
                $nonProductionModule = $module;
                break;
            }
        }

        if ($nonProductionModule === null) {
            $this->markTestSkipped('All modules are production modules in test data');
        }

        $this->assertFalse($nonProductionModule->isProduction());
    }

    public function test_getMacros(): void
    {
        $module = $this->getSampleModule();
        $macros = $module->getMacros();
        $this->assertIsArray($macros);
        // Note: Current implementation returns empty array
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_allModulesHaveValidIDs(): void
    {
        foreach (ModuleDefs::getInstance()->getAll() as $module) {
            $this->assertNotEmpty($module->getID());
        }
    }

    public function test_allModulesHaveLabels(): void
    {
        foreach (ModuleDefs::getInstance()->getAll() as $module) {
            $this->assertNotEmpty($module->getLabel());
        }
    }

    public function test_allModulesHaveValidCategories(): void
    {
        foreach (ModuleDefs::getInstance()->getAll() as $module) {
            $category = $module->getCategory();
            $this->assertInstanceOf(ModuleCategory::class, $category);
            $this->assertNotEmpty($category->getID());
        }
    }

    public function test_getProducedWares() : void
    {
        $module = $this->getSampleModule();
        $wares = $module->getProducedWares();

        $this->assertIsArray($wares);

        // Find a known production module to verify it has wares
        $prodModule = ModuleDefs::getInstance()->find('module_arg_prod_foodrations_01');
        if($prodModule === null) {
            $this->markTestSkipped('Could not find sample production module.');
        }

        $this->assertNotEmpty($prodModule->getProducedWares());
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    private function getSampleModule(): ModuleDef
    {
        $modules = ModuleDefs::getInstance()->getAll();
        $this->assertNotEmpty($modules, 'No modules found in collection');
        
        return reset($modules);
    }
}
