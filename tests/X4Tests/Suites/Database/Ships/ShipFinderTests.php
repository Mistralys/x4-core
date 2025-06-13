<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\Ships\KnownShips;
use Mistralys\X4\Database\Ships\ShipClasses;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\Ships\ShipSizes;
use X4Tests\Helpers\X4TestCase;

final class ShipFinderTests extends X4TestCase
{
    public function test_selectLabelSearch() : void
    {
        $ships = ShipDefs::getInstance()
            ->findShips()
            ->selectLabelSearch('Cerberus')
            ->getAll();

        $this->assertNotEmpty($ships);
        $this->assertShipsContainID(KnownShips::SHIP_CERBERUS_SENTINEL, $ships);
    }

    public function test_selectSize() : void
    {
        $ships = ShipDefs::getInstance()
            ->findShips()
            ->selectSize(ShipSizes::SIZE_M)
            ->getAll();

        $this->assertNotEmpty($ships);
        $this->assertShipsContainID(KnownShips::SHIP_CERBERUS_SENTINEL, $ships);
        $this->assertShipsContainOnlySize(ShipSizes::SIZE_M, $ships);
    }

    public function test_selectDataSource() : void
    {
        $ships = ShipDefs::getInstance()
            ->findShips()
            ->selectDataSource(KnownDataSources::DATA_SOURCE_SPLIT_VENDETTA)
            ->getAll();

        $this->assertNotEmpty($ships);
        $this->assertShipsContainID(KnownShips::SHIP_BOA, $ships);
        $this->assertShipsContainID(KnownShips::SHIP_ASP_RAIDER, $ships);
    }

    public function test_selectClass() : void
    {
        $ships = ShipDefs::getInstance()
            ->findShips()
            ->selectClass(ShipClasses::CLASS_BATTLESHIP)
            ->getAll();

        $this->assertNotEmpty($ships);
        $this->assertShipsContainID(KnownShips::SHIP_ASGARD, $ships);
        $this->assertShipsContainOnlyClass(ShipClasses::CLASS_BATTLESHIP, $ships);
    }

    public function test_genericShips() : void
    {
        $ships = ShipDefs::getInstance()
            ->findShips()
            ->selectBuilderFaction(KnownFactions::FACTION_GENERIC)
            ->getAll();

        $this->assertNotEmpty($ships);
        $this->assertShipsContainID(KnownShips::SHIP_DART, $ships);
    }

    /**
     * @param string $class
     * @param ShipDef[] $ships
     * @return void
     */
    public function assertShipsContainOnlyClass(string $class, array $ships) : void
    {
        foreach($ships as $ship) {
            if($ship->getClassID() !== $class) {
                $this->fail("Ship with ID [{$ship->getID()}] is not of class [$class]. ".PHP_EOL.$this->renderShipList($ships));
            }
        }
    }

    /**
     * @param string $size
     * @param ShipDef[] $ships
     * @return void
     */
    public function assertShipsContainOnlySize(string $size, array $ships) : void
    {
        foreach($ships as $ship) {
            if($ship->getSizeID() !== $size) {
                $this->fail("Ship with ID [{$ship->getID()}] is not of size [$size]. ".PHP_EOL.$this->renderShipList($ships));
            }
        }
    }

    /**
     * @param ShipDef[] $ships
     * @return string
     */
    public function renderShipList(array $ships) : string
    {
        $output = 'List of ships ('.count($ships).'):'.PHP_EOL;
        foreach($ships as $ship) {
            $output .= '- '.$ship->getID() . ' "' . $ship->getLabel().'" '.$ship->getClassID()." ".$ship->getSizeID().' '.PHP_EOL;
        }

        return $output;
    }

    /**
     * @param string $id
     * @param ShipDef[] $ships
     * @return void
     */
    public function assertShipsContainID(string $id, array $ships) : void
    {
        foreach($ships as $ship) {
            if($ship->getID() === $id) {
                return;
            }
        }

        $this->fail("Ship with ID [$id] not found in the list of ships. ".PHP_EOL.$this->renderShipList($ships));
    }
}
