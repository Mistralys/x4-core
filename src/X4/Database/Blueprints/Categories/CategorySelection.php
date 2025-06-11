<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use AppUtils\ClassHelper;
use Mistralys\X4\Database\Blueprints\Categories\Types\ShipCategory;

class CategorySelection
{
    private BlueprintCategories $categories;

    public function __construct(BlueprintCategories $categories)
    {
        $this->categories = $categories;
    }

    public function ships() : ShipCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            ShipCategory::class,
            $this->categories->getByID(ShipCategory::CATEGORY_ID)
        );
    }
}
