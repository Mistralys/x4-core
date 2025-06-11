<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use AppUtils\Collections\BaseClassLoaderCollection;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\Blueprints\Categories;
use Mistralys\X4\Database\Blueprints\Categories\Types\ShipCategory;
use Mistralys\X4\X4Application;

/**
 * @method BlueprintCategoryInterface getByID(string $id)
 * @method BlueprintCategoryInterface[] getAll()
 * @method BlueprintCategoryInterface getDefault()
 */
class BlueprintCategories extends BaseClassLoaderCollection
{
    private static ?BlueprintCategories $instance = null;

    public static function getInstance(): BlueprintCategories
    {
        if (!isset(self::$instance)) {
            self::$instance = new BlueprintCategories();
        }

        return self::$instance;
    }

    public function __construct()
    {
        X4Application::initCache();
    }

    protected function createItemInstance(string $class): StringPrimaryRecordInterface
    {
        return new $class();
    }

    public function getInstanceOfClassName(): ?string
    {
        return BaseBlueprintCategory::class;
    }

    public function isRecursive(): bool
    {
        return false;
    }

    public function getClassesFolder(): FolderInfo
    {
        return FolderInfo::factory(__DIR__.'/Types');
    }

    public function getDefaultID(): string
    {
        return ShipCategory::CATEGORY_ID;
    }

    public function selectType() : CategorySelection
    {
        return new CategorySelection($this);
    }
}
