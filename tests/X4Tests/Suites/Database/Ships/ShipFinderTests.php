<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\Ships\KnownShips;
use Mistralys\X4\Database\Ships\ShipClasses;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\Ships\ShipException;
use Mistralys\X4\Database\Ships\ShipPurposes;
use Mistralys\X4\Database\Ships\ShipSizes;
use Mistralys\X4\Database\Wares\WareDefs;
use X4Tests\Helpers\RawShipMacroScanner;
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

    // region: Purpose, thruster size, capturable, hangar and software filters

    /**
     * @param ShipDef[] $ships
     * @return string[]
     */
    private function sortedShipIDs(array $ships) : array
    {
        $ids = array_map(static fn(ShipDef $ship) : string => $ship->getID(), $ships);
        sort($ids);

        return $ids;
    }

    /**
     * @param callable(ShipDef):bool $predicate
     * @return string[]
     */
    private function scanShipIDs(callable $predicate) : array
    {
        return $this->sortedShipIDs(array_filter(ShipDefs::getInstance()->getAll(), $predicate));
    }

    public function test_selectPurpose() : void
    {
        $trade = ShipDefs::getInstance()->findShips()->selectPurpose(ShipPurposes::PURPOSE_TRADE)->getAll();

        $this->assertNotEmpty($trade);
        foreach($trade as $ship) {
            $this->assertSame(ShipPurposes::PURPOSE_TRADE, $ship->getMacroProperties()->getPurposeID());
        }

        $this->assertSame(
            $this->scanShipIDs(static fn(ShipDef $s) => $s->getMacroProperties()->getPurposeID() === 'trade'),
            $this->sortedShipIDs($trade)
        );

        // ANY: two purposes give the union.
        $union = ShipDefs::getInstance()->findShips()
            ->selectPurpose(ShipPurposes::PURPOSE_TRADE)
            ->selectPurpose(ShipPurposes::PURPOSE_MINE)
            ->getAll();
        $this->assertSame(
            $this->scanShipIDs(static fn(ShipDef $s) => in_array($s->getMacroProperties()->getPurposeID(), array('trade', 'mine'), true)),
            $this->sortedShipIDs($union)
        );

        try {
            ShipDefs::getInstance()->findShips()->selectPurpose('no_such_purpose');
            $this->fail('An exception should have been thrown.');
        } catch (ShipException $e) {
            $this->assertSame(ShipException::ERROR_UNKNOWN_PURPOSE, $e->getCode());
        }
    }

    public function test_selectThrusterSize() : void
    {
        $counts = array();
        foreach(array('s', 'm', 'l', 'xl') as $size) {
            $ships = ShipDefs::getInstance()->findShips()->selectThrusterSize($size)->getAll();
            $counts[$size] = count($ships);

            foreach($ships as $ship) {
                $this->assertSame($size, $ship->getMacroProperties()->getThrusterSizeID());
            }
        }

        $this->assertSame(array('s' => 81, 'm' => 70, 'l' => 68, 'xl' => 37), $counts);

        $union = ShipDefs::getInstance()->findShips()
            ->selectThrusterSize(ShipSizes::getInstance()->getByID('s'))
            ->selectThrusterSize('xl')
            ->getAll();
        $this->assertCount($counts['s'] + $counts['xl'], $union);
    }

    public function test_selectCapturable() : void
    {
        $notCapturable = ShipDefs::getInstance()->findShips()->selectCapturable(false)->getAll();

        $this->assertCount(14, $notCapturable);
        // The game flags the Khaak and Xenon ships (the plan listed only the Khaak ones).
        foreach($notCapturable as $ship) {
            $this->assertMatchesRegularExpression('/\Aship_(kha|xen)_/', $ship->getID());
            $this->assertSame('0', RawShipMacroScanner::attr($ship, 'capture', 'allow'), $ship->getID());
        }

        $capturable = ShipDefs::getInstance()->findShips()->selectCapturable()->getAll();
        $this->assertCount(count(ShipDefs::getInstance()->getAll()) - 14, $capturable);
        $this->assertCount(242, $capturable);

        // Single value: the last call wins.
        $last = ShipDefs::getInstance()->findShips()->selectCapturable(false)->selectCapturable(true)->getAll();
        $this->assertSame($this->sortedShipIDs($capturable), $this->sortedShipIDs($last));
    }

    public function test_selectWithHangar() : void
    {
        $medium = ShipDefs::getInstance()->findShips()->selectWithHangar(ShipSizes::SIZE_M)->getAll();

        $this->assertContains('ship_spl_xl_carrier_01_a', $this->sortedShipIDs($medium));
        foreach($medium as $ship) {
            $this->assertGreaterThan(0, $ship->getMacroProperties()->getHangarCapacity('m'));
        }

        // ALL: a ship needs capacity for every selected size.
        $both = ShipDefs::getInstance()->findShips()
            ->selectWithHangar(ShipSizes::SIZE_XS)
            ->selectWithHangar(ShipSizes::SIZE_S)
            ->getAll();
        $this->assertSame(
            $this->scanShipIDs(static fn(ShipDef $s) => $s->getMacroProperties()->getHangarCapacity('xs') > 0 && $s->getMacroProperties()->getHangarCapacity('s') > 0),
            $this->sortedShipIDs($both)
        );

        // The union over all sizes is the set of ships with a hangar.
        $union = array();
        foreach(array('xs', 's', 'm', 'l', 'xl') as $size) {
            $union = array_merge($union, $this->sortedShipIDs(ShipDefs::getInstance()->findShips()->selectWithHangar($size)->getAll()));
        }
        $union = array_values(array_unique($union));
        sort($union);

        $this->assertCount(242, $union);
        $this->assertSame($this->scanShipIDs(static fn(ShipDef $s) => $s->getMacroProperties()->hasHangar()), $union);
    }

    public function test_selectWithSoftware() : void
    {
        $flightAssist = 'software_flightassistmk1';
        $scanner = 'software_scannerlongrangemk1';

        $results = ShipDefs::getInstance()->findShips()->selectWithSoftware($flightAssist)->getAll();

        $this->assertNotEmpty($results);
        $this->assertSame(
            $this->scanShipIDs(static fn(ShipDef $s) => $s->getMacroProperties()->getSoftware()->isInstallable('software_flightassistmk1')),
            $this->sortedShipIDs($results)
        );

        // ALL: both software wares must be installable.
        $both = ShipDefs::getInstance()->findShips()
            ->selectWithSoftware(WareDefs::getInstance()->getByID($flightAssist))
            ->selectWithSoftware($scanner)
            ->getAll();
        $this->assertSame(
            $this->scanShipIDs(static function(ShipDef $s) : bool {
                $software = $s->getMacroProperties()->getSoftware();
                return $software->isInstallable('software_flightassistmk1') && $software->isInstallable('software_scannerlongrangemk1');
            }),
            $this->sortedShipIDs($both)
        );

        // The ware ID does not have to exist.
        $this->assertSame(array(), ShipDefs::getInstance()->findShips()->selectWithSoftware('no_such_software')->getAll());
    }

    // endregion
}
