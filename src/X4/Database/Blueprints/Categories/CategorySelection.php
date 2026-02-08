<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use AppUtils\ClassHelper;
use Mistralys\X4\Database\Blueprints\Categories\Types\CountermeasureCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\DeployableCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\DroneCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\EngineCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\MineCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\MissileCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ModificationCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ModuleCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ShieldCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ShipCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\SkinCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ThrusterCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\TurretCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\WeaponCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\WelfareCategory;

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

    public function weapons() : WeaponCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            WeaponCategory::class,
            $this->categories->getByID(WeaponCategory::CATEGORY_ID)
        );
    }

    public function missiles() : MissileCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            MissileCategory::class,
            $this->categories->getByID(MissileCategory::CATEGORY_ID)
        );
    }

    public function turrets() : TurretCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            TurretCategory::class,
            $this->categories->getByID(TurretCategory::CATEGORY_ID)
        );
    }

    public function mines() : MineCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            MineCategory::class,
            $this->categories->getByID(MineCategory::CATEGORY_ID)
        );
    }

    public function countermeasures() : CountermeasureCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            CountermeasureCategory::class,
            $this->categories->getByID(CountermeasureCategory::CATEGORY_ID)
        );
    }

    public function engines() : EngineCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            EngineCategory::class,
            $this->categories->getByID(EngineCategory::CATEGORY_ID)
        );
    }

    public function shields() : ShieldCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            ShieldCategory::class,
            $this->categories->getByID(ShieldCategory::CATEGORY_ID)
        );
    }

    public function thrusters() : ThrusterCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            ThrusterCategory::class,
            $this->categories->getByID(ThrusterCategory::CATEGORY_ID)
        );
    }

    public function modules() : ModuleCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            ModuleCategory::class,
            $this->categories->getByID(ModuleCategory::CATEGORY_ID)
        );
    }

    public function welfare() : WelfareCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            WelfareCategory::class,
            $this->categories->getByID(WelfareCategory::CATEGORY_ID)
        );
    }

    public function deployables() : DeployableCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            DeployableCategory::class,
            $this->categories->getByID(DeployableCategory::CATEGORY_ID)
        );
    }

    public function drones() : DroneCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            DroneCategory::class,
            $this->categories->getByID(DroneCategory::CATEGORY_ID)
        );
    }

    public function modifications() : ModificationCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            ModificationCategory::class,
            $this->categories->getByID(ModificationCategory::CATEGORY_ID)
        );
    }

    public function skins() : SkinCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            SkinCategory::class,
            $this->categories->getByID(SkinCategory::CATEGORY_ID)
        );
    }
}
