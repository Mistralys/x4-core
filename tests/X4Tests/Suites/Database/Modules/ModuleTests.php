<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Modules;

use Mistralys\X4\Database\Modules\ModuleCategories;
use Mistralys\X4\Database\Modules\ModuleCategory;
use Mistralys\X4\Database\Modules\ModuleDef;
use Mistralys\X4\Database\Modules\ModuleDefs;
use X4Tests\Helpers\X4TestCase;

class ModuleTests extends X4TestCase
{
    public function test_getRace() : void
    {
        $id = 'pier_arg_harbor_03';

        $this->assertSame(
            'arg',
            ModuleDefs::getInstance()->getByID($id)->getRace()->getID()
        );
    }

    /**
     * An exception must be thrown for unknown module IDs.
     */
    public function test_createFromUnknownID() : void
    {
        $id = 'unknown_module';

        $this->expectExceptionCode(ModuleDefs::ERROR_UNKNOWN_MODULE_ID);

        ModuleDefs::getInstance()->getByID($id);
    }

    public function test_allModulesHaveARace() : void
    {
        $modules = ModuleDefs::getInstance()->getAll();

        foreach($modules as $module) {
            $module->getRace();
        }

        $this->addToAssertionCount(1);
    }

    public function test_getCategory() : void
    {
        $id = 'struct_arg_vertical_01';

        $this->assertSame(
            ModuleCategories::CATEGORY_STRUCTURAL_ELEMENTS,
            ModuleDefs::getInstance()->getByID($id)->getCategory()->getID()
        );
    }

    public function test_findByMacro() : void
    {
        $this->assertNotNull(
            ModuleDefs::getInstance()->findByMacro('struct_arg_vertical_01_macro')
        );
    }
}
