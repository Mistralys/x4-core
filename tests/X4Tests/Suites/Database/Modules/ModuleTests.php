<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Modules;

use Mistralys\X4\Database\Modules\Module;
use Mistralys\X4\Database\Modules\ModuleDefs;
use X4Tests\Helpers\X4TestCase;

class ModuleTests extends X4TestCase
{
    public function test_getRace() : void
    {
        $id = 'pier_arg_harbor_03_macro';

        $this->assertSame(
            'arg',
            ModuleDefs::getInstance()->getType($id)->getRace()->getID()
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
                ->getType($id)
                ->getLabel()
        );
    }

    public function test_getRaceException() : void
    {
        $id = 'unknown_module';

        $this->expectExceptionCode(Module::ERROR_COULD_NOT_DETECT_RACE);

        ModuleDefs::getInstance()->getType($id)->getRace();
    }
}
