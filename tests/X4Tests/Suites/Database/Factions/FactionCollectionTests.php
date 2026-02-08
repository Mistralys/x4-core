<?php
/**
 * @package X4Tests
 * @subpackage Database\Factions
 * @see \Mistralys\X4\Database\Factions\FactionDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Factions;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Factions\KnownFactions;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the FactionDefs collection which manages
 * all game factions.
 *
 * @package X4Tests
 * @subpackage Database\Factions
 */
final class FactionCollectionTests extends X4TestCase
{
    private function getFactions(): FactionDefs
    {
        return FactionDefs::getInstance();
    }

    /**
     * Test that getInstance returns a singleton instance
     */
    public function test_getInstance(): void
    {
        $instance1 = FactionDefs::getInstance();
        $instance2 = FactionDefs::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    /**
     * Test that getAll returns all factions
     */
    public function test_getAll(): void
    {
        $factions = $this->getFactions()->getAll();

        $this->assertNotEmpty($factions);
        $this->assertGreaterThanOrEqual(33, count($factions), 'Should have at least 33 factions');
        
        // Verify all items are FactionDef instances
        foreach ($factions as $faction) {
            $this->assertInstanceOf(FactionDef::class, $faction);
        }
    }

    /**
     * Test that getByID retrieves a specific faction
     */
    public function test_getByID(): void
    {
        $faction = $this->getFactions()->getByID(KnownFactions::FACTION_ARGON_FEDERATION);

        $this->assertInstanceOf(FactionDef::class, $faction);
        $this->assertSame(KnownFactions::FACTION_ARGON_FEDERATION, $faction->getID());
    }

    /**
     * Test that getByID throws exception for invalid ID
     */
    public function test_getByID_invalid(): void
    {
        $this->expectException(RecordNotExistsException::class);
        $this->getFactions()->getByID('nonexistent_faction_xyz');
    }

    /**
     * Test that getDefault returns a faction
     */
    public function test_getDefault(): void
    {
        $faction = $this->getFactions()->getDefault();
        
        $this->assertInstanceOf(FactionDef::class, $faction);
        $this->assertNotEmpty($faction->getID());
    }

    /**
     * Test detectFactionByID with full faction ID part
     */
    public function test_detectFactionByID_longID(): void
    {
        // detectFactionByID looks for faction short IDs in the parts of the ID
        // argon_station_macro → splits to ["argon", "station", "macro"]
        // Since "argon" is longer than 3 chars, it won't match the 3-char short ID "arg"
        // We need to use IDs that contain the 3-letter short codes
        $detected = $this->getFactions()->detectFactionByID('arg_station_macro');
        
        $this->assertNotNull($detected);
        $this->assertSame(KnownFactions::FACTION_ARGON_FEDERATION, $detected);
    }

    /**
     * Test detectFactionByID with variant full faction ID
     */
    public function test_detectFactionByID_longID_variant(): void
    {
        $detected = $this->getFactions()->detectFactionByID('tel_trading_station');
        
        $this->assertNotNull($detected);
        $this->assertSame(KnownFactions::FACTION_TELADI_COMPANY, $detected);
    }

    /**
     * Test detectFactionByID with short ID (3 letter code)
     */
    public function test_detectFactionByID_shortID(): void
    {
        $detected = $this->getFactions()->detectFactionByID('arg_mining_station');
        
        $this->assertNotNull($detected);
        $this->assertSame(KnownFactions::FACTION_ARGON_FEDERATION, $detected);
    }

    /**
     * Test detectFactionByID with special short ID mapping (ATF → Terran)
     */
    public function test_detectFactionByID_specialShortID_atf(): void
    {
        $detected = $this->getFactions()->detectFactionByID('atf_destroyer_macro');
        
        $this->assertNotNull($detected);
        $this->assertSame(KnownFactions::FACTION_TERRAN_PROTECTORATE, $detected);
    }

    /**
     * Test detectFactionByID with special short ID mapping (PIR → Riptide Rakers)
     */
    public function test_detectFactionByID_specialShortID_pir(): void
    {
        $detected = $this->getFactions()->detectFactionByID('pir_ship_macro');
        
        $this->assertNotNull($detected);
        $this->assertSame(KnownFactions::FACTION_RIPTIDE_RAKERS, $detected);
    }

    /**
     * Test detectFactionByID returns null for undetectable ID
     */
    public function test_detectFactionByID_invalid(): void
    {
        $detected = $this->getFactions()->detectFactionByID('unknown_thing_xyz');
        
        $this->assertNull($detected);
    }

    /**
     * Test SHORT_ID_MAPPINGS constant contains expected mappings
     */
    public function test_SHORT_ID_MAPPINGS(): void
    {
        $this->assertArrayHasKey(FactionDefs::SHORT_ID_ATF, FactionDefs::SHORT_ID_MAPPINGS);
        $this->assertArrayHasKey(FactionDefs::SHORT_ID_PIR, FactionDefs::SHORT_ID_MAPPINGS);
        
        $this->assertSame(
            KnownFactions::FACTION_TERRAN_PROTECTORATE,
            FactionDefs::SHORT_ID_MAPPINGS[FactionDefs::SHORT_ID_ATF]
        );
        
        $this->assertSame(
            KnownFactions::FACTION_RIPTIDE_RAKERS,
            FactionDefs::SHORT_ID_MAPPINGS[FactionDefs::SHORT_ID_PIR]
        );
    }

    /**
     * Test that all factions have valid short IDs (at least 3-letter prefix)
     */
    public function test_allFactionsHaveShortIDs(): void
    {
        $factions = $this->getFactions()->getAll();

        foreach ($factions as $faction) {
            $shortIDs = $faction->getShortIDs();
            $this->assertNotEmpty($shortIDs, "Faction {$faction->getID()} should have short IDs");
            $this->assertIsArray($shortIDs);
            
            foreach ($shortIDs as $shortID) {
                $this->assertIsString($shortID);
                $this->assertNotEmpty($shortID);
            }
        }
    }

    /**
     * Test that idExists validates faction existence
     */
    public function test_idExists(): void
    {
        $this->assertTrue(
            $this->getFactions()->idExists(KnownFactions::FACTION_ARGON_FEDERATION),
            'Argon faction should exist'
        );

        $this->assertFalse(
            $this->getFactions()->idExists('nonexistent_faction_xyz'),
            'Nonexistent faction should not exist'
        );
    }

    /**
     * Test that collection is not empty (smoke test)
     */
    public function test_collectionNotEmpty(): void
    {
        $factions = $this->getFactions()->getAll();
        $this->assertNotEmpty($factions);
    }

    /**
     * Test that all factions have valid IDs (no empty/null)
     */
    public function test_allFactionsHaveValidIDs(): void
    {
        $factions = $this->getFactions()->getAll();

        foreach ($factions as $faction) {
            $this->assertNotEmpty($faction->getID(), 'Faction ID should not be empty');
            $this->assertIsString($faction->getID(), 'Faction ID should be string');
        }
    }

    /**
     * Test that all factions have labels
     */
    public function test_allFactionsHaveLabels(): void
    {
        $factions = $this->getFactions()->getAll();

        foreach ($factions as $faction) {
            $this->assertNotEmpty($faction->getLabel(), "Faction {$faction->getID()} should have label");
            $this->assertIsString($faction->getLabel());
        }
    }

    /**
     * Test that all factions have valid data source IDs
     */
    public function test_allFactionsHaveValidDataSources(): void
    {
        $factions = $this->getFactions()->getAll();
        $validSources = ['vanilla', 'ego_dlc_boron', 'ego_dlc_split', 'ego_dlc_terran', 'ego_dlc_pirate', 'ego_dlc_timelines', 'ego_dlc_mini_01', 'ego_dlc_mini_02'];

        foreach ($factions as $faction) {
            $dataSource = $faction->getDataSourceID();
            $this->assertNotEmpty($dataSource, "Faction {$faction->getID()} should have data source");
            $this->assertContains(
                $dataSource,
                $validSources,
                "Faction {$faction->getID()} should have valid data source: $dataSource"
            );
        }
    }

    /**
     * Test that getFromList returns KnownFactions instance
     */
    public function test_getFromList(): void
    {
        $knownFactions = $this->getFactions()->getFromList();
        
        $this->assertInstanceOf(KnownFactions::class, $knownFactions);
    }

    /**
     * Test that known major factions exist
     */
    public function test_knownMajorFactionsExist(): void
    {
        $defs = $this->getFactions();
        
        // Test major playable factions
        $this->assertTrue($defs->idExists(KnownFactions::FACTION_ARGON_FEDERATION));
        $this->assertTrue($defs->idExists(KnownFactions::FACTION_QUEENDOM_BORON));
        $this->assertTrue($defs->idExists(KnownFactions::FACTION_GODREALM_PARANID));
        $this->assertTrue($defs->idExists(KnownFactions::FACTION_TELADI_COMPANY));
        $this->assertTrue($defs->idExists(KnownFactions::FACTION_ZYARTH_PATRIARCHY));
        $this->assertTrue($defs->idExists(KnownFactions::FACTION_TERRAN_PROTECTORATE));
        
        // Test major NPC factions
        $this->assertTrue($defs->idExists(KnownFactions::FACTION_XENON));
        $this->assertTrue($defs->idExists(KnownFactions::FACTION_KHAAK));
    }
}
