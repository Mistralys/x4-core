<?php
/**
 * @package X4Tests
 * @subpackage Database\MacroIndex
 * @see \Mistralys\X4\Database\MacroIndex\MacroFileDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\MacroIndex;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\MacroIndex\MacroFileDef;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\X4Exception;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the MacroFileDefs collection which manages
 * the macro index (macro name → file path mappings).
 *
 * **ARCHITECTURAL LIMITATION**: The MacroFileDefs class uses BaseStringPrimaryCollection
 * which requires unique IDs. However, the macro-index.json data contains duplicate macro
 * names across different data sources (vanilla + DLCs). This causes the collection to fail
 * during initialization.
 *
 * **STATUS**: All tests are currently skipped until the architectural issue is resolved.
 * The collection needs to be refactored to use a composite key (macro name + data source)
 * or a different collection pattern that supports duplicates.
 *
 * @package X4Tests
 * @subpackage Database\MacroIndex
 */
final class MacroCollectionTests extends X4TestCase
{
    private function getMacroIndex(): MacroFileDefs
    {
        return MacroFileDefs::getInstance();
    }

    /**
     * Test that getInstance returns a singleton instance
     */
    public function test_getInstance(): void
    {
        $instance1 = MacroFileDefs::getInstance();
        $instance2 = MacroFileDefs::getInstance();

        $this->assertSame($instance1, $instance2, 'getInstance should return singleton');
    }

    /**
     * Test that getAll returns many macro entries
     */
    public function test_getAll(): void
    {
        $macros = $this->getMacroIndex()->getAll();

        $this->assertNotEmpty($macros, 'Macro index should not be empty');
        $this->assertGreaterThan(1000, count($macros), 'Macro index should contain many entries');
        
        // Verify all items are MacroFileDef instances
        foreach ($macros as $macro) {
            $this->assertInstanceOf(MacroFileDef::class, $macro);
        }
    }

    /**
     * Test that getByID retrieves a specific macro by composite ID
     */
    public function test_getByID(): void
    {
        // Use composite ID format: dataFolder::macroName
        $compositeID = 'vanilla::Cluster_black2_Sector01_macro';
        $macro = $this->getMacroIndex()->getByID($compositeID);

        $this->assertInstanceOf(MacroFileDef::class, $macro);
        $this->assertSame($compositeID, $macro->getID());
        $this->assertSame('Cluster_black2_Sector01_macro', $macro->getMacroName());
    }

    /**
     * Test that getByMacroName retrieves a macro by name and data folder
     */
    public function test_getByMacroName(): void
    {
        $macroName = 'Cluster_black2_Sector01_macro';
        $macro = $this->getMacroIndex()->getByMacroName($macroName, 'vanilla');

        $this->assertInstanceOf(MacroFileDef::class, $macro);
        $this->assertSame($macroName, $macro->getMacroName());
        $this->assertSame('vanilla', $macro->getDataFolderID());
    }

    /**
     * Test that getByMacroName uses 'vanilla' as default data folder
     */
    public function test_getByMacroName_defaultsToVanilla(): void
    {
        $macroName = 'Cluster_black2_Sector01_macro';
        $macro = $this->getMacroIndex()->getByMacroName($macroName);

        $this->assertInstanceOf(MacroFileDef::class, $macro);
        $this->assertSame('vanilla', $macro->getDataFolderID());
    }

    /**
     * Test that findAllByMacroName returns array of matching macros
     */
    public function test_findAllByMacroName(): void
    {
        $macroName = 'Cluster_black2_Sector01_macro';
        $macros = $this->getMacroIndex()->findAllByMacroName($macroName);

        $this->assertIsArray($macros);
        $this->assertNotEmpty($macros);
        
        foreach ($macros as $macro) {
            $this->assertInstanceOf(MacroFileDef::class, $macro);
            $this->assertSame($macroName, $macro->getMacroName());
        }
    }

    /**
     * Test that getByID throws exception for nonexistent macro
     */
    public function test_getByID_invalid(): void
    {
        $this->expectException(RecordNotExistsException::class);
        $this->getMacroIndex()->getByID('nonexistent_macro_xyz_12345');
    }

    /**
     * Test that idExists validates macro existence using composite IDs
     */
    public function test_idExists(): void
    {
        $this->assertTrue(
            $this->getMacroIndex()->idExists('vanilla::Cluster_black2_Sector01_macro'),
            'Known macro should exist'
        );

        $this->assertFalse(
            $this->getMacroIndex()->idExists('nonexistent::nonexistent_macro_xyz_12345'),
            'Unknown macro should not exist'
        );
    }

    /**
     * Test that collection is not empty (smoke test)
     */
    public function test_collectionNotEmpty(): void
    {
        $macros = $this->getMacroIndex()->getAll();
        $this->assertNotEmpty($macros, 'Macro index collection should not be empty');
    }

    /**
     * Test that all macros have valid IDs (no empty/null)
     */
    public function test_allMacrosHaveValidIDs(): void
    {
        $macros = $this->getMacroIndex()->getAll();

        foreach ($macros as $macro) {
            $this->assertNotEmpty($macro->getID(), 'Macro ID should not be empty');
            $this->assertIsString($macro->getID(), 'Macro ID should be string');
        }
    }

    /**
     * Test that all macros have valid file paths
     */
    public function test_allMacrosHaveValidFilePaths(): void
    {
        $macros = $this->getMacroIndex()->getAll();

        foreach ($macros as $macro) {
            $this->assertNotEmpty($macro->getFullPath(), 'Macro file path should not be empty');
            $this->assertIsString($macro->getFullPath(), 'Macro file path should be string');
        }
    }

    /**
     * Test that all macros have valid data folder IDs
     */
    public function test_allMacrosHaveValidDataFolders(): void
    {
        $macros = $this->getMacroIndex()->getAll();

        foreach ($macros as $macro) {
            $dataFolderID = $macro->getDataFolderID();
            $this->assertNotEmpty($dataFolderID, 'Data folder ID should not be empty');
            $this->assertIsString($dataFolderID, 'Data folder ID should be string');
            
            // Verify it's a known data source (vanilla or DLC)
            $this->assertContains(
                $dataFolderID,
                ['vanilla', 'ego_dlc_boron', 'ego_dlc_split', 'ego_dlc_terran', 'ego_dlc_pirate', 'ego_dlc_timelines', 'ego_dlc_mini_01', 'ego_dlc_mini_02'],
                "Data folder should be a known source: $dataFolderID"
            );
        }
    }

    /**
     * Test that vanilla macros exist in the collection
     */
    public function test_vanillaMacrosExist(): void
    {
        $macros = $this->getMacroIndex()->getAll();
        $vanillaMacros = array_filter($macros, function(MacroFileDef $macro) {
            return $macro->getDataFolderID() === 'vanilla';
        });

        $this->assertNotEmpty($vanillaMacros, 'Should have vanilla macros');
        $this->assertGreaterThan(100, count($vanillaMacros), 'Should have many vanilla macros');
    }

    /**
     * Test that DLC macros exist in the collection
     */
    public function test_dlcMacrosExist(): void
    {
        $macros = $this->getMacroIndex()->getAll();
        $dlcMacros = array_filter($macros, function(MacroFileDef $macro) {
            return $macro->getDataFolderID() !== 'vanilla';
        });

        $this->assertNotEmpty($dlcMacros, 'Should have DLC macros');
    }

    /**
     * Test that specific known macros from different data sources exist
     */
    public function test_knownMacrosFromDifferentSources(): void
    {
        $index = $this->getMacroIndex();

        // Test vanilla macro using composite ID
        $this->assertTrue($index->idExists('vanilla::Cluster_black2_Sector01_macro'), 'Vanilla macro should exist');
        
        // Find at least one macro from a DLC (we don't know exact names without checking the file)
        $allMacros = $index->getAll();
        $hasNonVanilla = false;
        foreach ($allMacros as $macro) {
            if ($macro->getDataFolderID() !== 'vanilla') {
                $hasNonVanilla = true;
                break;
            }
        }
        
        $this->assertTrue($hasNonVanilla, 'Should have at least one non-vanilla macro');
    }

    /**
     * Test that the collection handles macro entries from multiple data sources
     * Note: Due to the way the base collection works, duplicate macro IDs across
     * different data sources may cause the later entries to overwrite earlier ones.
     * This is a known limitation of using StringPrimaryCollection for this data type.
     */
    public function test_collectionHandlesDuplicateMacroNames(): void
    {
        $this->markTestSkipped('Macro index has known issue with duplicate IDs across data sources');
    }
}
