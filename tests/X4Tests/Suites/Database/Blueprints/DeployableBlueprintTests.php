<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\Types\DeployableCategory;
use Mistralys\X4\Database\Blueprints\Types\DeployableBlueprint;
use X4Tests\Helpers\X4TestCase;

final class DeployableBlueprintTests extends X4TestCase
{
    private function getBlueprint(): ?DeployableBlueprint
    {
        $all = BlueprintCategories::getInstance()->selectType()->deployables()->getBlueprints();
        if (empty($all)) {
            return null;
        }
        return reset($all);
    }

    public function test_getCategory(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No deployable blueprints found.');
        }

        $this->assertInstanceOf(DeployableCategory::class, $blueprint->getCategory());
        $this->assertSame(DeployableCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }

    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getBlueprint();
        if ($blueprint === null) {
            $this->markTestSkipped('No deployable blueprints found.');
        }

        $this->assertEquals('Deployable', $blueprint->getTypeLabel());
    }
}
