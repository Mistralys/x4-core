<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\MissileCategory;
use Mistralys\X4\Database\Blueprints\Types\MissileBlueprint;
use X4Tests\Helpers\X4TestCase;

final class MissileBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?MissileBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->missiles()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No missile blueprints found.');
        }

        $this->assertInstanceOf(MissileCategory::class, $blueprint->getCategory());
        $this->assertSame(MissileCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No missile blueprints found.');
        }

        $this->assertEquals('Missile', $blueprint->getTypeLabel());
    }
}
