<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\ThrusterCategory;
use Mistralys\X4\Database\Blueprints\Types\ThrusterBlueprint;
use X4Tests\Helpers\X4TestCase;

final class ThrusterBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?ThrusterBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->thrusters()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No thruster blueprints found.');
        }

        $this->assertInstanceOf(ThrusterCategory::class, $blueprint->getCategory());
        $this->assertSame(ThrusterCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No thruster blueprints found.');
        }

        $this->assertEquals('Thruster', $blueprint->getTypeLabel());
    }
}
