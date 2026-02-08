<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\ShieldCategory;
use Mistralys\X4\Database\Blueprints\Types\ShieldBlueprint;
use X4Tests\Helpers\X4TestCase;

final class ShieldBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?ShieldBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->shields()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No shield blueprints found.');
        }

        $this->assertInstanceOf(ShieldCategory::class, $blueprint->getCategory());
        $this->assertSame(ShieldCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No shield blueprints found.');
        }

        $this->assertEquals('Shield', $blueprint->getTypeLabel());
    }
}
