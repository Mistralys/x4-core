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

    /**
     * Test that an empty builder faction string defaults to 'generic'.
     * 
     * This tests the edge case where the old format contains an empty string,
     * which should be handled gracefully by falling back to the generic faction.
     */
    public function test_emptyBuilderFactionString(): void
    {
        $testData = [
            'wareID' => 'test_ship_empty_faction',
            'label' => 'Test Ship',
            'variantID' => 'test_variant',
            'size' => 'M',
            'builderFactionID' => '',  // Empty string
            'classID' => 'corvette',
            'usedBy' => [],
            'dataSourceID' => 'vanilla'
        ];
        
        $ship = \Mistralys\X4\Database\Ships\ShipDef::fromArray($testData);
        
        $this->assertEquals(
            KnownFactions::FACTION_GENERIC,
            $ship->getBuilderFactionID(),
            'Empty faction string should default to generic'
        );
        
        $this->assertFalse(
            $ship->hasMultipleBuilderFactions(),
            'Single default faction should not report multiple factions'
        );
        
        $this->assertEquals(
            [KnownFactions::FACTION_GENERIC],
            $ship->getBuilderFactionIDs(),
            'Empty string should result in array with generic faction'
        );
    }

    /**
     * Test robust parsing of faction strings with irregular whitespace.
     * 
     * This ensures that space-separated faction strings are properly trimmed
     * and parsed even when they contain extra whitespace.
     */
    public function test_whitespaceInFactionString(): void
    {
        $testData = [
            'wareID' => 'test_ship_whitespace',
            'label' => 'Test Ship',
            'variantID' => 'test_variant',
            'size' => 'M',
            'builderFactionID' => '  argon    teladi  ',  // Extra whitespace
            'classID' => 'corvette',
            'usedBy' => [],
            'dataSourceID' => 'vanilla'
        ];
        
        $ship = \Mistralys\X4\Database\Ships\ShipDef::fromArray($testData);
        
        $factionIDs = $ship->getBuilderFactionIDs();
        
        $this->assertCount(
            2,
            $factionIDs,
            'Whitespace-padded string should parse to 2 factions'
        );
        
        $this->assertContains(
            'argon',
            $factionIDs,
            'Parsed factions should include argon (whitespace trimmed)'
        );
        
        $this->assertContains(
            'teladi',
            $factionIDs,
            'Parsed factions should include teladi (whitespace trimmed)'
        );
        
        $this->assertTrue(
            $ship->hasMultipleBuilderFactions(),
            'Should correctly detect multiple factions despite whitespace'
        );
    }

    /**
     * Test that single-element arrays work correctly and don't report as multiple factions.
     * 
     * This verifies backward compatibility when the new array format contains only one faction.
     */
    public function test_singleValueInArrayFormat(): void
    {
        $testData = [
            'wareID' => 'test_ship_single_array',
            'label' => 'Test Ship',
            'variantID' => 'test_variant',
            'size' => 'M',
            'builderFactionIDs' => ['argon'],  // Single-element array (new format)
            'classID' => 'corvette',
            'usedBy' => [],
            'dataSourceID' => 'vanilla'
        ];
        
        $ship = \Mistralys\X4\Database\Ships\ShipDef::fromArray($testData);
        
        $this->assertFalse(
            $ship->hasMultipleBuilderFactions(),
            'Single-element array should not report multiple factions'
        );
        
        $this->assertEquals(
            'argon',
            $ship->getBuilderFactionID(),
            'Backward-compatible getter should return the single faction'
        );
        
        $this->assertEquals(
            ['argon'],
            $ship->getBuilderFactionIDs(),
            'Multi-value getter should return array with single element'
        );
        
        $factions = $ship->getBuilderFactions();
        $this->assertCount(
            1,
            $factions,
            'Should resolve to exactly one FactionDef object'
        );
        
        $this->assertEquals(
            'argon',
            $factions[0]->getID(),
            'Resolved faction should be argon'
        );
    }

    /**
     * Test handling of invalid or unknown faction IDs in compound strings.
     * 
     * This documents the current behavior when space-separated strings contain
     * non-existent faction IDs. The system filters out empty strings but does NOT
     * validate faction IDs during parsing - validation happens later when resolving
     * to FactionDef objects via getBuilderFactions().
     */
    public function test_invalidFactionInCompoundString(): void
    {
        $testData = [
            'wareID' => 'test_ship_invalid_faction',
            'label' => 'Test Ship',
            'variantID' => 'test_variant',
            'size' => 'M',
            'builderFactionID' => 'argon nonexistent teladi',  // Invalid faction in middle
            'classID' => 'corvette',
            'usedBy' => [],
            'dataSourceID' => 'vanilla'
        ];
        
        $ship = \Mistralys\X4\Database\Ships\ShipDef::fromArray($testData);
        
        // The parsing stage does NOT validate faction IDs - it just splits the string
        $factionIDs = $ship->getBuilderFactionIDs();
        
        $this->assertCount(
            3,
            $factionIDs,
            'Parser should split string into 3 IDs without validation'
        );
        
        $this->assertContains(
            'argon',
            $factionIDs,
            'Valid faction argon should be preserved'
        );
        
        $this->assertContains(
            'nonexistent',
            $factionIDs,
            'Invalid faction ID is preserved during parsing (validation happens later)'
        );
        
        $this->assertContains(
            'teladi',
            $factionIDs,
            'Valid faction teladi should be preserved'
        );
        
        // Document that validation happens when resolving to FactionDef objects
        // getBuilderFactions() will handle invalid IDs via FactionDefs::getByID()
        // which throws FactionException for unknown IDs
    }
}
