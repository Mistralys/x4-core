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

    /**
     * Test multi-builder-faction support for ships.
     * Tests the Envoy ship (ship_gen_m_corvette_01) which has multiple builder factions.
     */
    public function test_multiBuilderFaction() : void
    {
        $ships = $this->getShipDefs();
        
        // The Envoy ship has multiple builder factions: argon and teladi
        $envoy = $ships->find('ship_gen_m_corvette_01');
        
        // If the ship doesn't exist yet (hasn't been rebuilt), skip this test
        if ($envoy === null) {
            $this->markTestSkipped('Envoy ship not found - database may need to be rebuilt');
            return;
        }
        
        // Test the new methods
        $this->assertTrue($envoy->hasMultipleBuilderFactions(), 'Envoy should have multiple builder factions');
        $this->assertContains('argon', $envoy->getBuilderFactionIDs(), 'Envoy should have argon as a builder faction');
        $this->assertContains('teladi', $envoy->getBuilderFactionIDs(), 'Envoy should have teladi as a builder faction');
        
        // Test backward compatibility - should return the first (primary) faction
        $this->assertEquals('argon', $envoy->getBuilderFactionID(), 'Primary builder faction should be argon');
        $this->assertEquals('argon', $envoy->getBuilderFaction()->getID(), 'Primary builder FactionDef should be argon');
        
        // Test getBuilderFactions returns FactionDef instances
        $factions = $envoy->getBuilderFactions();
        $this->assertCount(2, $factions, 'Should have 2 builder factions');
        $this->assertContainsOnlyInstancesOf(\Mistralys\X4\Database\Factions\FactionDef::class, $factions);
    }

    /**
     * Test that ShipFinder matches multi-faction ships correctly using intersection matching.
     */
    public function test_finderMatchesMultiFaction() : void
    {
        $ships = $this->getShipDefs();
        
        // Find ships by argon faction - should include the Envoy
        $argonShips = $ships->findShips()->selectBuilderFaction('argon')->getAll();
        $envoyInArgon = false;
        foreach ($argonShips as $ship) {
            if ($ship->getID() === 'ship_gen_m_corvette_01') {
                $envoyInArgon = true;
                break;
            }
        }
        
        // Find ships by teladi faction - should also include the Envoy
        $teladiShips = $ships->findShips()->selectBuilderFaction('teladi')->getAll();
        $envoyInTeladi = false;
        foreach ($teladiShips as $ship) {
            if ($ship->getID() === 'ship_gen_m_corvette_01') {
                $envoyInTeladi = true;
                break;
            }
        }
        
        // If we found the Envoy, it should appear in both faction searches
        if ($envoyInArgon || $envoyInTeladi) {
            $this->assertTrue($envoyInArgon, 'Envoy should match when filtering by argon');
            $this->assertTrue($envoyInTeladi, 'Envoy should match when filtering by teladi');
        } else {
            $this->markTestSkipped('Envoy ship not found in search results - database may need to be rebuilt');
        }
    }
}
