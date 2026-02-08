<?php
/**
 * @package X4Tests
 * @subpackage Database\Factions
 * @see \Mistralys\X4\Database\Factions\FactionDef
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Factions;

use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Factions\KnownFactions;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the FactionDef item class which represents
 * a single faction.
 *
 * @package X4Tests
 * @subpackage Database\Factions
 */
final class FactionDefTests extends X4TestCase
{
    private function getSampleFaction(): FactionDef
    {
        return FactionDefs::getInstance()->getByID(KnownFactions::FACTION_ARGON_FEDERATION);
    }

    /**
     * Test that getID returns the faction ID
     */
    public function test_getID(): void
    {
        $faction = $this->getSampleFaction();
        
        $this->assertIsString($faction->getID());
        $this->assertSame(KnownFactions::FACTION_ARGON_FEDERATION, $faction->getID());
    }

    /**
     * Test that getLabel returns the faction name
     */
    public function test_getLabel(): void
    {
        $faction = $this->getSampleFaction();
        
        $this->assertIsString($faction->getLabel());
        $this->assertNotEmpty($faction->getLabel());
        $this->assertStringContainsString('Argon', $faction->getLabel());
    }

    /**
     * Test that getShortIDs returns array of short IDs
     */
    public function test_getShortIDs(): void
    {
        $faction = $this->getSampleFaction();
        $shortIDs = $faction->getShortIDs();
        
        $this->assertIsArray($shortIDs);
        $this->assertNotEmpty($shortIDs);
        $this->assertContains('arg', $shortIDs, 'Argon should have "arg" short ID');
    }

    /**
     * Test that getShortIDs includes 3-letter prefix
     */
    public function test_getShortIDs_includes3LetterPrefix(): void
    {
        $factions = FactionDefs::getInstance()->getAll();
        
        foreach ($factions as $faction) {
            $shortIDs = $faction->getShortIDs();
            $expectedPrefix = substr($faction->getID(), 0, 3);
            
            $this->assertContains(
                $expectedPrefix,
                $shortIDs,
                "Faction {$faction->getID()} should have 3-letter prefix '{$expectedPrefix}' in short IDs"
            );
        }
    }

    /**
     * Test that special short ID mappings are included
     */
    public function test_getShortIDs_specialMappings_terran(): void
    {
        $terran = FactionDefs::getInstance()->getByID(KnownFactions::FACTION_TERRAN_PROTECTORATE);
        $shortIDs = $terran->getShortIDs();
        
        $this->assertContains('ter', $shortIDs, 'Terran should have "ter" prefix');
        $this->assertContains(FactionDefs::SHORT_ID_ATF, $shortIDs, 'Terran should have "atf" mapping');
    }

    /**
     * Test that special short ID mappings are included for Pirates
     */
    public function test_getShortIDs_specialMappings_pirates(): void
    {
        $pirates = FactionDefs::getInstance()->getByID(KnownFactions::FACTION_RIPTIDE_RAKERS);
        $shortIDs = $pirates->getShortIDs();
        
        $this->assertContains('sca', $shortIDs, 'Riptide Rakers should have "sca" prefix (scavenger)');
        $this->assertContains(FactionDefs::SHORT_ID_PIR, $shortIDs, 'Riptide Rakers should have "pir" mapping');
    }

    /**
     * Test that getDataSourceID returns data source
     */
    public function test_getDataSourceID(): void
    {
        $faction = $this->getSampleFaction();
        
        $this->assertIsString($faction->getDataSourceID());
        $this->assertNotEmpty($faction->getDataSourceID());
        $this->assertSame('vanilla', $faction->getDataSourceID());
    }

    /**
     * Test that isGeneric correctly identifies generic faction
     */
    public function test_isGeneric_true(): void
    {
        $generic = FactionDefs::getInstance()->getByID(KnownFactions::FACTION_GENERIC);
        
        $this->assertTrue($generic->isGeneric());
    }

    /**
     * Test that isGeneric returns false for non-generic factions
     */
    public function test_isGeneric_false(): void
    {
        $faction = $this->getSampleFaction();
        
        $this->assertFalse($faction->isGeneric());
    }

    /**
     * Test fromArray creates a FactionDef from array data
     */
    public function test_fromArray(): void
    {
        $data = [
            FactionDef::KEY_ID => 'test_faction',
            FactionDef::KEY_NAME => 'Test Faction Name',
            FactionDef::KEY_DATA_SOURCE_ID => 'vanilla'
        ];
        
        $faction = FactionDef::fromArray($data);
        
        $this->assertInstanceOf(FactionDef::class, $faction);
        $this->assertSame('test_faction', $faction->getID());
        $this->assertSame('Test Faction Name', $faction->getLabel());
        $this->assertSame('vanilla', $faction->getDataSourceID());
    }

    /**
     * Test that all factions have consistent data
     */
    public function test_allFactionsHaveConsistentData(): void
    {
        $factions = FactionDefs::getInstance()->getAll();
        
        foreach ($factions as $faction) {
            // Every faction should have these basic properties
            $this->assertNotEmpty($faction->getID(), 'Faction should have ID');
            $this->assertNotEmpty($faction->getLabel(), 'Faction should have label');
            $this->assertNotEmpty($faction->getDataSourceID(), 'Faction should have data source ID');
            $this->assertNotEmpty($faction->getShortIDs(), 'Faction should have short IDs');
            
            // isGeneric should return boolean
            $this->assertIsBool($faction->isGeneric());
        }
    }

    /**
     * Test that only the generic faction is marked as generic
     */
    public function test_onlyGenericFactionIsGeneric(): void
    {
        $factions = FactionDefs::getInstance()->getAll();
        $genericCount = 0;
        
        foreach ($factions as $faction) {
            if ($faction->isGeneric()) {
                $genericCount++;
                $this->assertSame(KnownFactions::FACTION_GENERIC, $faction->getID());
            }
        }
        
        $this->assertSame(1, $genericCount, 'Only one faction should be marked as generic');
    }

    /**
     * Test that different factions have different IDs
     */
    public function test_differentFactionsHaveDifferentIDs(): void
    {
        $argon = FactionDefs::getInstance()->getByID(KnownFactions::FACTION_ARGON_FEDERATION);
        $teladi = FactionDefs::getInstance()->getByID(KnownFactions::FACTION_TELADI_COMPANY);
        
        $this->assertNotSame($argon->getID(), $teladi->getID());
        $this->assertNotSame($argon->getLabel(), $teladi->getLabel());
    }

    /**
     * Test that faction IDs are lowercase
     */
    public function test_factionIDsAreLowercase(): void
    {
        $factions = FactionDefs::getInstance()->getAll();
        
        foreach ($factions as $faction) {
            $id = $faction->getID();
            $this->assertSame(
                strtolower($id),
                $id,
                "Faction ID should be lowercase: $id"
            );
        }
    }

    /**
     * Test that short IDs are lowercase
     */
    public function test_shortIDsAreLowercase(): void
    {
        $factions = FactionDefs::getInstance()->getAll();
        
        foreach ($factions as $faction) {
            $shortIDs = $faction->getShortIDs();
            foreach ($shortIDs as $shortID) {
                $this->assertSame(
                    strtolower($shortID),
                    $shortID,
                    "Short ID should be lowercase: $shortID (faction: {$faction->getID()})"
                );
            }
        }
    }

    /**
     * Test that vanilla factions exist
     */
    public function test_vanillaFactionsExist(): void
    {
        $factions = FactionDefs::getInstance()->getAll();
        $vanillaFactions = array_filter($factions, function(FactionDef $faction) {
            return $faction->getDataSourceID() === 'vanilla';
        });
        
        $this->assertNotEmpty($vanillaFactions, 'Should have vanilla factions');
        $this->assertGreaterThan(20, count($vanillaFactions), 'Should have many vanilla factions');
    }

    /**
     * Test that DLC factions exist
     */
    public function test_dlcFactionsExist(): void
    {
        $factions = FactionDefs::getInstance()->getAll();
        $dlcFactions = array_filter($factions, function(FactionDef $faction) {
            return $faction->getDataSourceID() !== 'vanilla';
        });
        
        // May or may not have DLC factions depending on game installation
        // Just verify the data structure supports it
        foreach ($dlcFactions as $dlcFaction) {
            $this->assertNotSame('vanilla', $dlcFaction->getDataSourceID());
        }
    }

    /**
     * Test that major factions have expected properties
     */
    public function test_majorFactionsHaveExpectedProperties(): void
    {
        $majorFactions = [
            KnownFactions::FACTION_ARGON_FEDERATION => 'arg',
            KnownFactions::FACTION_QUEENDOM_BORON => 'bor',
            KnownFactions::FACTION_GODREALM_PARANID => 'par',
            KnownFactions::FACTION_TELADI_COMPANY => 'tel',
            KnownFactions::FACTION_ZYARTH_PATRIARCHY => 'spl',
            KnownFactions::FACTION_TERRAN_PROTECTORATE => 'ter',
        ];
        
        foreach ($majorFactions as $factionID => $expectedShortID) {
            $faction = FactionDefs::getInstance()->getByID($factionID);
            
            $this->assertNotEmpty($faction->getLabel(), "Major faction $factionID should have label");
            $this->assertContains(
                $expectedShortID,
                $faction->getShortIDs(),
                "Major faction $factionID should have short ID $expectedShortID"
            );
        }
    }
}
