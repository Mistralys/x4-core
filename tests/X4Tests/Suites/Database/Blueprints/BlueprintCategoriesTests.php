<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategoryInterface;
use Mistralys\X4\Database\Blueprints\Categories\CategorySelection;
use Mistralys\X4\Database\Blueprints\Categories\Types\ShipCategory;
use X4Tests\Helpers\X4TestCase;

final class BlueprintCategoriesTests extends X4TestCase
{
    private BlueprintCategories $categories;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categories = BlueprintCategories::getInstance();
    }

    public function test_getAll(): void
    {
        $all = $this->categories->getAll();
        $this->assertGreaterThan(5, count($all)); // Should be around 16
        $this->assertContainsOnlyInstancesOf(BlueprintCategoryInterface::class, $all);
    }

    public function test_getByID(): void
    {
        // SHIP category should always exist
        $id = ShipCategory::CATEGORY_ID;
        $category = $this->categories->getByID($id);
        
        $this->assertSame($id, $category->getID());
        $this->assertInstanceOf(ShipCategory::class, $category);
    }
    
    public function test_idExists(): void
    {
        $this->assertTrue($this->categories->idExists(ShipCategory::CATEGORY_ID));
        $this->assertFalse($this->categories->idExists('non_existent_category_999'));
    }

    public function test_getDefault(): void
    {
        $default = $this->categories->getDefault();
        $this->assertInstanceOf(ShipCategory::class, $default);
        $this->assertSame(ShipCategory::CATEGORY_ID, $default->getID());
    }

    public function test_selectType(): void
    {
        $selection = $this->categories->selectType();
        $this->assertInstanceOf(CategorySelection::class, $selection);
    }
    
    public function test_CategoryInterfaceMethods(): void
    {
        $category = $this->categories->getByID(ShipCategory::CATEGORY_ID);
        
        $this->assertNotEmpty($category->getLabel());
        $this->assertSame(ShipCategory::CATEGORY_ID, $category->getID());
        
        // BaseBlueprintCategory methods
        if ($category instanceof BaseBlueprintCategory) {
            // Test any base methods if needed
        }
    }
}
