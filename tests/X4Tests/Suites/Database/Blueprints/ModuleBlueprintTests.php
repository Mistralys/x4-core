<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\ModuleCategory;
use Mistralys\X4\Database\Blueprints\Types\ModuleBlueprint;
use X4Tests\Helpers\X4TestCase;

final class ModuleBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?ModuleBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->modules()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No module blueprints found.');
        }

        $this->assertInstanceOf(ModuleCategory::class, $blueprint->getCategory());
        $this->assertSame(ModuleCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No module blueprints found.');
        }

        $this->assertEquals('Module', $blueprint->getTypeLabel());
    }
}
