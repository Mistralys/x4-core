<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\CountermeasureCategory;
use Mistralys\X4\Database\Blueprints\Types\CountermeasureBlueprint;
use X4Tests\Helpers\X4TestCase;

final class CountermeasureBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?CountermeasureBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->countermeasures()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No countermeasure blueprints found.');
        }

        $this->assertInstanceOf(CountermeasureCategory::class, $blueprint->getCategory());
        $this->assertSame(CountermeasureCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No countermeasure blueprints found.');
        }

        $this->assertEquals('Countermeasure', $blueprint->getTypeLabel());
    }
}
