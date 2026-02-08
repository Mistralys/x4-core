<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\WelfareCategory;
use Mistralys\X4\Database\Blueprints\Types\WelfareBlueprint;
use X4Tests\Helpers\X4TestCase;

final class WelfareBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?WelfareBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->welfare()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No welfare blueprints found.');
        }

        $this->assertInstanceOf(WelfareCategory::class, $blueprint->getCategory());
        $this->assertSame(WelfareCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No welfare blueprints found.');
        }

        $this->assertEquals('Welfare', $blueprint->getTypeLabel());
    }
}
