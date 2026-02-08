<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Types\ShipBlueprint;
use Mistralys\X4\Database\Ships\ShipClasses;
use X4Tests\Helpers\X4TestCase;

final class ShipBlueprintTests extends X4TestCase
{
    private function getShipBlueprint(string $id = 'ship_arg_m_miner_liquid_01_a'): ShipBlueprint
    {
        return BlueprintCategories::getInstance()->selectType()->ships()->getBlueprintByID($id);
    }

    public function test_getShip(): void 
    {
        $blueprint = $this->getShipBlueprint();
        $this->assertEquals($blueprint->getID(), $blueprint->getShip()->getID());
    }
    
    public function test_getWare(): void
    {
        $blueprint = $this->getShipBlueprint();
        $this->assertEquals($blueprint->getID(), $blueprint->getWare()->getID());
    }
    
    public function test_getCategory(): void
    {
        $blueprint = $this->getShipBlueprint();
        $this->assertInstanceOf(\Mistralys\X4\Database\Blueprints\Categories\Types\ShipCategory::class, $blueprint->getCategory());
        $this->assertSame(\Mistralys\X4\Database\Blueprints\Categories\Types\ShipCategory::CATEGORY_ID, $blueprint->getCategoryID());
    }
    
    public function test_getTypeLabel(): void
    {
        $blueprint = $this->getShipBlueprint();
        $this->assertNotEmpty($blueprint->getTypeLabel());
    }

    public function test_allShipsHaveAClass() : void
    {
        $ships = BlueprintCategories::getInstance()->selectType()->ships()->getBlueprints();

        $this->assertNotEmpty($ships);

        foreach ($ships as $ship)
        {
            $this->assertNotEmpty($ship->getClassID(), $ship->getID().' has no class.');
        }
    }

    public function test_industryShip() : void
    {
        $category = BlueprintCategories::getInstance()->selectType()->ships();

        $ship = $category->getBlueprintByID('ship_arg_m_miner_liquid_01_a');
        $this->assertSame('m', $ship->getSizeID());
        $this->assertSame(ShipBlueprint::ROLE_INDUSTRY, $ship->getRoleID());
        $this->assertSame(ShipClasses::CLASS_MINER, $ship->getClassID());
    }

    public function test_militaryShip() : void
    {
        $category = BlueprintCategories::getInstance()->selectType()->ships();

        $ship = $category->getBlueprintByID('ship_bor_l_destroyer_01_a');
        $this->assertSame('l', $ship->getSizeID());
        $this->assertSame(ShipBlueprint::ROLE_MILITARY, $ship->getRoleID());
        $this->assertSame(ShipClasses::CLASS_DESTROYER, $ship->getClassID());
    }
}
