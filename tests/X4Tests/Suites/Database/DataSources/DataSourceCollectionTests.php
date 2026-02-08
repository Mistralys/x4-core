<?php
/**
 * @package X4Tests
 * @subpackage Database\DataSources
 * @see \Mistralys\X4\Database\DataSources\DataSourceDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\DataSources;

use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\DataSources\KnownDataSources;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the DataSourceDefs collection which manages
 * all game data sources (base game + DLCs).
 *
 * @package X4Tests
 * @subpackage Database\DataSources
 */
final class DataSourceCollectionTests extends X4TestCase
{
    private function getDataSources(): DataSourceDefs
    {
        return DataSourceDefs::getInstance();
    }

    /**
     * Test that getInstance returns a singleton instance
     */
    public function test_getInstance(): void
    {
        $instance1 = DataSourceDefs::getInstance();
        $instance2 = DataSourceDefs::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    /**
     * Test that getAll returns all data sources
     */
    public function test_getAll(): void
    {
        $dataSources = $this->getDataSources()->getAll();

        $this->assertNotEmpty($dataSources);
        $this->assertCount(8, $dataSources, 'Expected 8 data sources (vanilla + 7 DLCs)');
    }

    /**
     * Test that getByID returns the correct data source
     */
    public function test_getByID_vanilla(): void
    {
        $dataSource = $this->getDataSources()->getByID('vanilla');

        $this->assertSame('vanilla', $dataSource->getID());
        $this->assertSame('Base game', $dataSource->getLabel());
        $this->assertFalse($dataSource->isExtension());
    }

    /**
     * Test retrieving a DLC by ID
     */
    public function test_getByID_dlc(): void
    {
        $dataSource = $this->getDataSources()->getByID('ego_dlc_boron');

        $this->assertSame('ego_dlc_boron', $dataSource->getID());
        $this->assertSame('Kingdom End', $dataSource->getLabel());
        $this->assertTrue($dataSource->isExtension());
    }

    /**
     * Test that getByID throws exception for nonexistent ID
     */
    public function test_getByID_invalid(): void
    {
        $this->expectException(\Exception::class);

        $this->getDataSources()->getByID('nonexistent_dlc');
    }

    /**
     * Test idExists validation method
     */
    public function test_idExists(): void
    {
        $this->assertTrue($this->getDataSources()->idExists('vanilla'));
        $this->assertTrue($this->getDataSources()->idExists('ego_dlc_boron'));
        $this->assertFalse($this->getDataSources()->idExists('nonexistent_dlc'));
    }

    /**
     * Test that getDefault returns the first data source (alphabetically)
     */
    public function test_getDefault(): void
    {
        $default = $this->getDataSources()->getDefault();

        // The default is auto-selected as the first one alphabetically
        $this->assertSame('ego_dlc_boron', $default->getID());
        $this->assertTrue($default->isExtension());
    }

    /**
     * Test that collection is not empty
     */
    public function test_collectionNotEmpty(): void
    {
        $this->assertNotEmpty($this->getDataSources()->getAll());
    }

    /**
     * Test that all data sources have valid IDs (no empty strings)
     */
    public function test_allHaveValidIDs(): void
    {
        foreach ($this->getDataSources()->getAll() as $dataSource) {
            $this->assertNotEmpty($dataSource->getID());
            $this->assertIsString($dataSource->getID());
        }
    }

    /**
     * Test that vanilla is included
     */
    public function test_vanillaExists(): void
    {
        $vanilla = $this->getDataSources()->getByID('vanilla');

        $this->assertNotNull($vanilla);
        $this->assertSame('vanilla', $vanilla->getID());
    }

    /**
     * Test that all expected DLCs are present
     */
    public function test_allDLCsPresent(): void
    {
        $expectedDLCs = [
            'ego_dlc_boron',
            'ego_dlc_mini_01',
            'ego_dlc_mini_02',
            'ego_dlc_pirate',
            'ego_dlc_split',
            'ego_dlc_terran',
            'ego_dlc_timelines'
        ];

        foreach ($expectedDLCs as $dlcID) {
            $this->assertTrue(
                $this->getDataSources()->idExists($dlcID),
                sprintf('Expected DLC [%s] not found in data sources.', $dlcID)
            );
        }
    }

    /**
     * Test that all data sources have labels
     */
    public function test_allHaveLabels(): void
    {
        foreach ($this->getDataSources()->getAll() as $dataSource) {
            $this->assertNotEmpty(
                $dataSource->getLabel(),
                sprintf('Data source [%s] has no label.', $dataSource->getID())
            );
        }
    }

    /**
     * Test that vanilla is the only non-extension
     */
    public function test_onlyVanillaIsNotExtension(): void
    {
        $nonExtensions = [];
        
        foreach ($this->getDataSources()->getAll() as $dataSource) {
            if (!$dataSource->isExtension()) {
                $nonExtensions[] = $dataSource->getID();
            }
        }

        $this->assertCount(
            1,
            $nonExtensions,
            'Expected exactly one non-extension (vanilla)'
        );
        $this->assertContains('vanilla', $nonExtensions);
    }

    /**
     * Test that all DLCs are marked as extensions
     */
    public function test_allDLCsAreExtensions(): void
    {
        $dlcIDs = [
            'ego_dlc_boron',
            'ego_dlc_mini_01',
            'ego_dlc_mini_02',
            'ego_dlc_pirate',
            'ego_dlc_split',
            'ego_dlc_terran',
            'ego_dlc_timelines'
        ];

        foreach ($dlcIDs as $dlcID) {
            $dataSource = $this->getDataSources()->getByID($dlcID);
            
            $this->assertTrue(
                $dataSource->isExtension(),
                sprintf('DLC [%s] should be marked as extension.', $dlcID)
            );
        }
    }

    /**
     * Test that data file is accessible
     */
    public function test_dataFileIsAccessible(): void
    {
        $dataFile = $this->getDataSources()->getDataFile();

        $this->assertNotNull($dataFile);
        $this->assertTrue($dataFile->exists());
    }

    /**
     * Test that getDefaultID returns the first data source ID
     */
    public function test_getDefaultID(): void
    {
        $defaultID = $this->getDataSources()->getDefaultID();

        // The default is auto-selected as the first one alphabetically
        $this->assertSame('ego_dlc_boron', $defaultID);
    }
}
