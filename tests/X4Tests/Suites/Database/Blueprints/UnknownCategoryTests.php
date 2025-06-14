<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Categories\Types\UnknownCategory;
use X4Tests\Helpers\X4TestCase;

final class UnknownCategoryTests extends X4TestCase
{
    public function test_noUnknownCategoryBlueprints() : void
    {
        $collection = BlueprintDefs::getInstance();

        $blueprints = $collection->getBlueprints();

        $this->assertNotEmpty($blueprints);

        foreach($blueprints as $blueprint)
        {
            $this->assertNotSame(
                UnknownCategory::CATEGORY_ID,
                $blueprint->getCategory()->getID(),
                sprintf(
                    'The blueprint [%s] has been filed in the unknown category.',
                    $blueprint->getID()
                )
            );
        }
    }
}
