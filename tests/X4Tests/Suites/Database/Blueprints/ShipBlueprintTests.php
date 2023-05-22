<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\ShipBlueprint;
use X4Tests\Helpers\X4TestCase;

final class ShipBlueprintTests extends X4TestCase
{
    public function test_allShipsHaveAClass() : void
    {
        $ships = BlueprintDefs::getInstance()->getShipsCategory()->getBlueprints();

        $this->assertNotEmpty($ships);

        foreach ($ships as $ship)
        {
            $this->assertNotEmpty($ship->getClassID(), $ship->getID().' has no class.');
        }
    }

    public function test_industryShip() : void
    {
        $category = BlueprintDefs::getInstance()->getShipsCategory();

        $ship = $category->getBlueprintByID('ship_arg_m_miner_liquid_01_a');
        $this->assertSame('m', $ship->getSizeID());
        $this->assertSame(ShipBlueprint::ROLE_INDUSTRY, $ship->getRoleID());
        $this->assertSame(ShipBlueprint::CLASS_MINER_LIQUID, $ship->getClassID());
    }

    public function test_militaryShip() : void
    {
        $category = BlueprintDefs::getInstance()->getShipsCategory();

        $ship = $category->getBlueprintByID('ship_bor_l_destroyer_01_a');
        $this->assertSame('l', $ship->getSizeID());
        $this->assertSame(ShipBlueprint::ROLE_MILITARY, $ship->getRoleID());
        $this->assertSame(ShipBlueprint::CLASS_DESTROYER, $ship->getClassID());
    }
}
