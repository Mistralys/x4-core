<?php
/**
 * @package X4Tests
 * @subpackage Database\DataSources
 * @see \Mistralys\X4\Database\DataSources\DataSourceDef
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\DataSources;

use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the DataSourceDef item class which represents
 * a single game data source (base game or DLC).
 *
 * @package X4Tests
 * @subpackage Database\DataSources
 */
final class DataSourceDefTests extends X4TestCase
{
    private function getVanilla(): DataSourceDef
    {
        return DataSourceDefs::getInstance()->getByID('vanilla');
    }

    private function getDLC(): DataSourceDef
    {
        return DataSourceDefs::getInstance()->getByID('ego_dlc_boron');
    }

    /**
     * Test getID returns the data source ID
     */
    public function test_getID(): void
    {
        $vanilla = $this->getVanilla();

        $this->assertSame('vanilla', $vanilla->getID());
    }

    /**
     * Test getID for DLC
     */
    public function test_getID_dlc(): void
    {
        $dlc = $this->getDLC();

        $this->assertSame('ego_dlc_boron', $dlc->getID());
    }

    /**
     * Test getLabel returns human-readable name
     */
    public function test_getLabel(): void
    {
        $vanilla = $this->getVanilla();

        $this->assertSame('Base game', $vanilla->getLabel());
    }

    /**
     * Test getLabel for DLC
     */
    public function test_getLabel_dlc(): void
    {
        $dlc = $this->getDLC();

        $this->assertSame('Kingdom End', $dlc->getLabel());
    }

    /**
     * Test isExtension returns false for vanilla
     */
    public function test_isExtension_vanilla(): void
    {
        $vanilla = $this->getVanilla();

        $this->assertFalse($vanilla->isExtension());
    }

    /**
     * Test isExtension returns true for DLC
     */
    public function test_isExtension_dlc(): void
    {
        $dlc = $this->getDLC();

        $this->assertTrue($dlc->isExtension());
    }

    /**
     * Test fromArray constructs object correctly
     */
    public function test_fromArray(): void
    {
        $data = [
            'id' => 'test_dlc',
            'label' => 'Test DLC',
            'isExtension' => true
        ];

        $dataSource = DataSourceDef::fromArray($data);

        $this->assertSame('test_dlc', $dataSource->getID());
        $this->assertSame('Test DLC', $dataSource->getLabel());
        $this->assertTrue($dataSource->isExtension());
    }

    /**
     * Test fromArray with vanilla data
     */
    public function test_fromArray_vanilla(): void
    {
        $data = [
            'id' => 'vanilla',
            'label' => 'Base game',
            'isExtension' => false
        ];

        $dataSource = DataSourceDef::fromArray($data);

        $this->assertSame('vanilla', $dataSource->getID());
        $this->assertSame('Base game', $dataSource->getLabel());
        $this->assertFalse($dataSource->isExtension());
    }

    /**
     * Test that all data sources can be retrieved by ID
     */
    public function test_allDataSourcesHaveUniqueIDs(): void
    {
        $dataSources = DataSourceDefs::getInstance()->getAll();
        $ids = [];

        foreach ($dataSources as $dataSource) {
            $id = $dataSource->getID();
            
            $this->assertNotContains(
                $id,
                $ids,
                sprintf('Duplicate data source ID found: [%s]', $id)
            );
            
            $ids[] = $id;
        }

        $this->assertCount(8, $ids, 'Expected 8 unique data source IDs');
    }

    /**
     * Test that vanilla has expected properties
     */
    public function test_vanillaProperties(): void
    {
        $vanilla = $this->getVanilla();

        $this->assertSame('vanilla', $vanilla->getID());
        $this->assertSame('Base game', $vanilla->getLabel());
        $this->assertFalse($vanilla->isExtension());
    }

    /**
     * Test Split Vendetta DLC properties
     */
    public function test_splitVendettaProperties(): void
    {
        $split = DataSourceDefs::getInstance()->getByID('ego_dlc_split');

        $this->assertSame('ego_dlc_split', $split->getID());
        $this->assertSame('Split Vendetta', $split->getLabel());
        $this->assertTrue($split->isExtension());
    }

    /**
     * Test Cradle of Humanity DLC properties
     */
    public function test_cradleOfHumanityProperties(): void
    {
        $terran = DataSourceDefs::getInstance()->getByID('ego_dlc_terran');

        $this->assertSame('ego_dlc_terran', $terran->getID());
        $this->assertSame('Cradle of Humanity', $terran->getLabel());
        $this->assertTrue($terran->isExtension());
    }

    /**
     * Test Tides of Avarice DLC properties
     */
    public function test_tidesOfAvariceProperties(): void
    {
        $pirate = DataSourceDefs::getInstance()->getByID('ego_dlc_pirate');

        $this->assertSame('ego_dlc_pirate', $pirate->getID());
        $this->assertSame('Tides of Avarice', $pirate->getLabel());
        $this->assertTrue($pirate->isExtension());
    }

    /**
     * Test Timelines DLC properties
     */
    public function test_timelinesProperties(): void
    {
        $timelines = DataSourceDefs::getInstance()->getByID('ego_dlc_timelines');

        $this->assertSame('ego_dlc_timelines', $timelines->getID());
        $this->assertSame('Timelines', $timelines->getLabel());
        $this->assertTrue($timelines->isExtension());
    }

    /**
     * Test Hyperion Pack DLC properties
     */
    public function test_hyperionPackProperties(): void
    {
        $hyperion = DataSourceDefs::getInstance()->getByID('ego_dlc_mini_01');

        $this->assertSame('ego_dlc_mini_01', $hyperion->getID());
        $this->assertSame('Hyperion Pack', $hyperion->getLabel());
        $this->assertTrue($hyperion->isExtension());
    }

    /**
     * Test Envoy Pack DLC properties
     */
    public function test_envoyPackProperties(): void
    {
        $envoy = DataSourceDefs::getInstance()->getByID('ego_dlc_mini_02');

        $this->assertSame('ego_dlc_mini_02', $envoy->getID());
        $this->assertSame('Envoy Pack', $envoy->getLabel());
        $this->assertTrue($envoy->isExtension());
    }

    /**
     * Test that all labels are non-empty strings
     */
    public function test_allLabelsAreNonEmpty(): void
    {
        foreach (DataSourceDefs::getInstance()->getAll() as $dataSource) {
            $this->assertNotEmpty(
                $dataSource->getLabel(),
                sprintf('Data source [%s] has empty label', $dataSource->getID())
            );
            $this->assertIsString($dataSource->getLabel());
        }
    }

    /**
     * Test that all IDs are non-empty strings
     */
    public function test_allIDsAreNonEmpty(): void
    {
        foreach (DataSourceDefs::getInstance()->getAll() as $dataSource) {
            $this->assertNotEmpty($dataSource->getID());
            $this->assertIsString($dataSource->getID());
        }
    }

    /**
     * Test that isExtension returns boolean
     */
    public function test_isExtensionReturnsBoolean(): void
    {
        foreach (DataSourceDefs::getInstance()->getAll() as $dataSource) {
            $this->assertIsBool($dataSource->isExtension());
        }
    }
}
