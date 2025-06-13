<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Factions\KnownFactions;
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
        $sources = $this->getShipDefs()->getDataSources();

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

    public function test_factionsHaveShips() : void
    {
        $factions = $this->getShipDefs()->getFactions();

        $expectedFactions = array(
            KnownFactions::FACTION_ARGON_FEDERATION,
            KnownFactions::FACTION_GODREALM_PARANID,
            KnownFactions::FACTION_KHAAK,
            KnownFactions::FACTION_QUEENDOM_BORON,
            KnownFactions::FACTION_TELADI_COMPANY,
            KnownFactions::FACTION_TERRAN_PROTECTORATE,
            KnownFactions::FACTION_XENON,
            KnownFactions::FACTION_ZYARTH_PATRIARCHY,
        );

        foreach($expectedFactions as $factionID) {
            $found = false;
            foreach($factions as $faction) {
                if($faction->getID() === $factionID) {
                    $found = true;
                    break;
                }
            }

            $this->assertTrue($found, "Faction [$factionID] not found in ship definitions.");
        }
    }

    private function getShipDefs() : ShipDefs
    {
        return ShipDefs::getInstance();
    }
}
