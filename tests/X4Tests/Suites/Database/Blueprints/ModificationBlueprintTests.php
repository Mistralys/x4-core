<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\ModificationCategory;
use Mistralys\X4\Database\Blueprints\Types\ModificationBlueprint;
use X4Tests\Helpers\X4TestCase;

final class ModificationBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?ModificationBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->modifications()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No modification blueprints found.');
        }

        $this->assertInstanceOf(ModificationCategory::class, $blueprint->getCategory());
        $this->assertSame(ModificationCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No modification blueprints found.');
        }

        $this->assertEquals('Modification', $blueprint->getTypeLabel());
    }
}
