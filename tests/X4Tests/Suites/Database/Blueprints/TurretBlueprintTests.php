<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\TurretCategory;
use Mistralys\X4\Database\Blueprints\Types\TurretBlueprint;
use X4Tests\Helpers\X4TestCase;

final class TurretBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?TurretBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->turrets()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No turret blueprints found.');
        }

        $this->assertInstanceOf(TurretCategory::class, $blueprint->getCategory());
        $this->assertSame(TurretCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No turret blueprints found.');
        }

        $this->assertEquals('Turret', $blueprint->getTypeLabel());
    }
}
