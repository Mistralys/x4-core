<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\DataSources\DLCs;
use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use X4Tests\Helpers\X4TestCase;

final class WareCollectionTests extends X4TestCase
{
    // region: _Tests

    public function test_getByID() : void
    {
        $ware = WareDefs::getInstance()->getByID('ship_arg_m_bomber_01_a');

        $this->assertSame('ship_arg_m_bomber_01_a', $ware->getID());
    }

    public function test_getByTag() : void
    {
        $results = WareDefs::getInstance()
            ->findWares()
            ->selectTag('economy')
            ->getAll();

        $this->assertNotEmpty($results);
        $this->assertContainsWare('nividium', $results);
    }

    public function test_getByDLC() : void
    {
        $results = WareDefs::getInstance()
            ->findWares()
            ->selectDataSource(KnownDataSources::DATA_SOURCE_KINGDOM_END)
            ->getAll();

        $this->assertNotEmpty($results);
        $this->assertContainsWare('ship_bor_l_carrier_01_a', $results);
        $this->assertDLCOnly(DLCs::DLC_BORON, $results);
    }

    public function test_getByGroup() : void
    {
        $results = WareDefs::getInstance()
            ->findWares()
            ->selectGroup(WareGroups::GROUP_SHIPS)
            ->getAll();

        $this->assertNotEmpty($results);
        $this->assertContainsWare('ship_arg_m_bomber_01_a', $results);
    }

    // endregion

    // region: Support methods

    private function assertDLCOnly(string $dlcID, array $wares) : void
    {
        foreach($wares as $ware) {
            if($ware->getDataSourceID() !== $dlcID) {
                $this->fail("Found ware with ID '{$ware->getID()}' that does not belong to the DLC '$dlcID'.");
            }
        }
    }

    private function assertContainsWare(string $wareID, array $wares) : void
    {
        $this->assertTrue(
            in_array($wareID, array_map(fn($ware) => $ware->getID(), $wares), true),
            "Expected ware with ID '$wareID' not found in results."
        );
    }

    // endregion
}
