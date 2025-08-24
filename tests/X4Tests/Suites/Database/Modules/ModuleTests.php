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
}
