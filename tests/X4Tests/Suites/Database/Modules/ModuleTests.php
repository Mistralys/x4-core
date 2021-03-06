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
        $id = 'pier_arg_harbor_03_macro';

        $this->assertSame(
            'arg',
            ModuleDefs::getInstance()->getByID($id)->getRace()->getID()
        );
    }

    /**
     * No error is thrown for an unknown module, but
     * it will have the ID as its label.
     */
    public function test_createFromUnknownID() : void
    {
        $id = 'unknown_module';

        $this->assertSame(
            $id,
            ModuleDefs::getInstance()
                ->getByID($id)
                ->getLabel()
        );
    }

    public function test_getRaceException() : void
    {
        $id = 'unknown_module';

        $this->expectExceptionCode(ModuleDef::ERROR_COULD_NOT_DETECT_RACE);

        ModuleDefs::getInstance()->getByID($id)->getRace();
    }

    public function test_getCategory() : void
    {
        $id = 'pier_arg_harbor_03_macro';

        $this->assertSame(
            'pier',
            ModuleDefs::getInstance()->getByID($id)->getCategory()->getID()
        );
    }

    public function test_getCategoryException() : void
    {
        $id = 'unknown_category_module';

        $this->expectExceptionCode(ModuleCategories::ERROR_UNKNOWN_CATEGORY_ID);

        ModuleDefs::getInstance()->getByID($id)->getCategory();
    }
}
