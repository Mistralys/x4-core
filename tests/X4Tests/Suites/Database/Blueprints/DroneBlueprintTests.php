<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\DroneCategory;
use Mistralys\X4\Database\Blueprints\Types\DroneBlueprint;
use X4Tests\Helpers\X4TestCase;

final class DroneBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?DroneBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->drones()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No drone blueprints found.');
        }

        $this->assertInstanceOf(DroneCategory::class, $blueprint->getCategory());
        $this->assertSame(DroneCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No drone blueprints found.');
        }

        $this->assertEquals('Drone', $blueprint->getTypeLabel());
    }
}
