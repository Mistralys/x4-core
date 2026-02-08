<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\WeaponCategory;
use Mistralys\X4\Database\Blueprints\Types\WeaponBlueprint;
use X4Tests\Helpers\X4TestCase;

final class WeaponBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?WeaponBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->weapons()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No weapon blueprints found.');
        }

        $this->assertInstanceOf(WeaponCategory::class, $blueprint->getCategory());
        $this->assertSame(WeaponCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No weapon blueprints found.');
        }

        $this->assertEquals('Weapon', $blueprint->getTypeLabel());
    }
}
