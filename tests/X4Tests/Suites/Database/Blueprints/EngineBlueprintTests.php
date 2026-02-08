<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\EngineCategory;
use Mistralys\X4\Database\Blueprints\Types\EngineBlueprint;
use X4Tests\Helpers\X4TestCase;

final class EngineBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?EngineBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->engines()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No engine blueprints found.');
        }

        $this->assertInstanceOf(EngineCategory::class, $blueprint->getCategory());
        $this->assertSame(EngineCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No engine blueprints found.');
        }

        $this->assertEquals('Engine', $blueprint->getTypeLabel());
    }
}
