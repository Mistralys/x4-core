<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use X4Tests\Helpers\X4TestCase;

final class UnknownBlueprintTests extends X4TestCase
{
    public function test_registerUnknown() : void
    {
        $collection = BlueprintDefs::getInstance();
        $id = 'some_bp_id';

        $blueprint = $collection->registerUnknownBlueprint($id);

        $this->assertSame($id, $blueprint->getID());
        $this->assertTrue($collection->blueprintIDExists($id));
    }

    public function test_registerUnknownCannotOverwriteExisting() : void
    {
        $collection = BlueprintDefs::getInstance();
        $id = 'shield_arg_l_standard_01_mk1';

        $this->assertTrue($collection->blueprintIDExists($id));

        $this->expectExceptionCode(BlueprintDefs::ERROR_BLUEPRINT_ALREADY_REGISTERED);

        $collection->registerUnknownBlueprint($id);
    }
}
