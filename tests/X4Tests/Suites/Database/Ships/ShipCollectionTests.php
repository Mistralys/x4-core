<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Ships\ShipDefs;
use X4Tests\Helpers\X4TestCase;

final class ShipCollectionTests extends X4TestCase
{
    public function test_collectionNotEmpty() : void
    {
        $this->assertNotEmpty($this->getShipDefs()->getAll());
    }

    public function test_dlcsHaveShips() : void
    {
        $sources =$this->getShipDefs()->getDataSources();

        $expectedSources = array(
            KnownDataSources::DATA_SOURCE_BASE_GAME,
            KnownDataSources::DATA_SOURCE_SPLIT_VENDETTA,
            KnownDataSources::DATA_SOURCE_CRADLE_HUMANITY,
            KnownDataSources::DATA_SOURCE_KINGDOM_END,
            KnownDataSources::DATA_SOURCE_KINGDOM_END
        );

        foreach($expectedSources as $sourceID) {
            $found = false;
            foreach($sources as $source) {
                if($source->getID() === $sourceID) {
                    $found = true;
                    break;
                }
            }

            $this->assertTrue($found, "Data source [$sourceID] not found in ship definitions.");
        }
    }

    private function getShipDefs() : ShipDefs
    {
        return ShipDefs::getInstance();
    }
}
