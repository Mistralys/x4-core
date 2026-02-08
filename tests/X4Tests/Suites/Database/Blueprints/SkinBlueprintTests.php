<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\SkinCategory;
use Mistralys\X4\Database\Blueprints\Types\SkinBlueprint;
use X4Tests\Helpers\X4TestCase;

final class SkinBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?SkinBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->skins()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No skin blueprints found.');
        }

        $this->assertInstanceOf(SkinCategory::class, $blueprint->getCategory());
        $this->assertSame(SkinCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No skin blueprints found.');
        }

        $this->assertEquals('Skin', $blueprint->getTypeLabel());
    }
}
