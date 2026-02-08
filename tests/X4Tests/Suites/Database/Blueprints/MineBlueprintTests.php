<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\MineCategory;
use Mistralys\X4\Database\Blueprints\Types\MineBlueprint;
use X4Tests\Helpers\X4TestCase;

final class MineBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?MineBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->mines()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No mine blueprints found.');
        }

        $this->assertInstanceOf(MineCategory::class, $blueprint->getCategory());
        $this->assertSame(MineCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No mine blueprints found.');
        }

        $this->assertEquals('Mine', $blueprint->getTypeLabel());
    }
}
