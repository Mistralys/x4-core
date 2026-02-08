<?php
/**
 * @package X4Tests
 * @subpackage Database\DataSources
 * @see \Mistralys\X4\Database\DataSources\KnownDataSources
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\DataSources;

use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\DataSources\DLCs;
use Mistralys\X4\Database\DataSources\KnownDataSources;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the KnownDataSources utility class which provides
 * constants and getter methods for all known data sources.
 *
 * @package X4Tests
 * @subpackage Database\DataSources
 */
final class KnownDataSourcesTests extends X4TestCase
{
    private function getKnownDataSources(): KnownDataSources
    {
        return KnownDataSources::getInstance();
    }

    /**
     * Test getInstance returns singleton
     */
    public function test_getInstance(): void
    {
        $instance1 = KnownDataSources::getInstance();
        $instance2 = KnownDataSources::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    /**
     * Test getBaseGame returns vanilla data source
     */
    public function test_getBaseGame(): void
    {
        $vanilla = $this->getKnownDataSources()->getBaseGame();

        $this->assertSame('vanilla', $vanilla->getID());
        $this->assertSame('Base game', $vanilla->getLabel());
        $this->assertFalse($vanilla->isExtension());
    }

    /**
     * Test getCradleOfHumanity returns Terran DLC
     */
    public function test_getCradleOfHumanity(): void
    {
        $terran = $this->getKnownDataSources()->getCradleOfHumanity();

        $this->assertSame('ego_dlc_terran', $terran->getID());
        $this->assertSame('Cradle of Humanity', $terran->getLabel());
        $this->assertTrue($terran->isExtension());
    }

    /**
     * Test getEnvoyPack returns mini DLC 02
     */
    public function test_getEnvoyPack(): void
    {
        $envoy = $this->getKnownDataSources()->getEnvoyPack();

        $this->assertSame('ego_dlc_mini_02', $envoy->getID());
        $this->assertSame('Envoy Pack', $envoy->getLabel());
        $this->assertTrue($envoy->isExtension());
    }

    /**
     * Test getHyperionPack returns mini DLC 01
     */
    public function test_getHyperionPack(): void
    {
        $hyperion = $this->getKnownDataSources()->getHyperionPack();

        $this->assertSame('ego_dlc_mini_01', $hyperion->getID());
        $this->assertSame('Hyperion Pack', $hyperion->getLabel());
        $this->assertTrue($hyperion->isExtension());
    }

    /**
     * Test getKingdomEnd returns Boron DLC
     */
    public function test_getKingdomEnd(): void
    {
        $boron = $this->getKnownDataSources()->getKingdomEnd();

        $this->assertSame('ego_dlc_boron', $boron->getID());
        $this->assertSame('Kingdom End', $boron->getLabel());
        $this->assertTrue($boron->isExtension());
    }

    /**
     * Test getSplitVendetta returns Split DLC
     */
    public function test_getSplitVendetta(): void
    {
        $split = $this->getKnownDataSources()->getSplitVendetta();

        $this->assertSame('ego_dlc_split', $split->getID());
        $this->assertSame('Split Vendetta', $split->getLabel());
        $this->assertTrue($split->isExtension());
    }

    /**
     * Test getTidesOfAvarice returns Pirate DLC
     */
    public function test_getTidesOfAvarice(): void
    {
        $pirate = $this->getKnownDataSources()->getTidesOfAvarice();

        $this->assertSame('ego_dlc_pirate', $pirate->getID());
        $this->assertSame('Tides of Avarice', $pirate->getLabel());
        $this->assertTrue($pirate->isExtension());
    }

    /**
     * Test getTimelines returns Timelines DLC
     */
    public function test_getTimelines(): void
    {
        $timelines = $this->getKnownDataSources()->getTimelines();

        $this->assertSame('ego_dlc_timelines', $timelines->getID());
        $this->assertSame('Timelines', $timelines->getLabel());
        $this->assertTrue($timelines->isExtension());
    }

    /**
     * Test that all constants exist in the data file
     */
    public function test_allConstantsExistInDataFile(): void
    {
        $expectedConstants = [
            KnownDataSources::DATA_SOURCE_BASE_GAME,
            KnownDataSources::DATA_SOURCE_CRADLE_HUMANITY,
            KnownDataSources::DATA_SOURCE_ENVOY_PACK,
            KnownDataSources::DATA_SOURCE_HYPERION_PACK,
            KnownDataSources::DATA_SOURCE_KINGDOM_END,
            KnownDataSources::DATA_SOURCE_SPLIT_VENDETTA,
            KnownDataSources::DATA_SOURCE_TIDES_AVARICE,
            KnownDataSources::DATA_SOURCE_TIMELINES
        ];

        $dataSources = DataSourceDefs::getInstance();

        foreach ($expectedConstants as $constantID) {
            $this->assertTrue(
                $dataSources->idExists($constantID),
                sprintf('Constant [%s] does not exist in data file', $constantID)
            );
        }
    }

    /**
     * Test that all data sources have a corresponding getter method
     */
    public function test_allDataSourcesHaveGetter(): void
    {
        $dataSources = DataSourceDefs::getInstance()->getAll();
        $knownSources = $this->getKnownDataSources();

        foreach ($dataSources as $dataSource) {
            $id = $dataSource->getID();
            
            // Try to get via KnownDataSources - should not throw exception
            switch ($id) {
                case 'vanilla':
                    $this->assertSame($dataSource->getID(), $knownSources->getBaseGame()->getID());
                    break;
                case 'ego_dlc_terran':
                    $this->assertSame($dataSource->getID(), $knownSources->getCradleOfHumanity()->getID());
                    break;
                case 'ego_dlc_mini_02':
                    $this->assertSame($dataSource->getID(), $knownSources->getEnvoyPack()->getID());
                    break;
                case 'ego_dlc_mini_01':
                    $this->assertSame($dataSource->getID(), $knownSources->getHyperionPack()->getID());
                    break;
                case 'ego_dlc_boron':
                    $this->assertSame($dataSource->getID(), $knownSources->getKingdomEnd()->getID());
                    break;
                case 'ego_dlc_split':
                    $this->assertSame($dataSource->getID(), $knownSources->getSplitVendetta()->getID());
                    break;
                case 'ego_dlc_pirate':
                    $this->assertSame($dataSource->getID(), $knownSources->getTidesOfAvarice()->getID());
                    break;
                case 'ego_dlc_timelines':
                    $this->assertSame($dataSource->getID(), $knownSources->getTimelines()->getID());
                    break;
                default:
                    $this->fail(sprintf('No getter method found for data source [%s]', $id));
            }
        }
    }

    /**
     * Test DATA_SOURCES constant contains all data sources
     */
    public function test_dataSourcesConstantComplete(): void
    {
        $constantSources = KnownDataSources::DATA_SOURCES;
        $actualSources = [];

        foreach (DataSourceDefs::getInstance()->getAll() as $dataSource) {
            $actualSources[] = $dataSource->getID();
        }

        sort($constantSources);
        sort($actualSources);

        $this->assertSame(
            $actualSources,
            $constantSources,
            'DATA_SOURCES constant does not match actual data sources in file'
        );
    }

    /**
     * Test that all getters return DataSourceDef instances
     */
    public function test_allGettersReturnDataSourceDef(): void
    {
        $knownSources = $this->getKnownDataSources();

        $this->assertInstanceOf(\Mistralys\X4\Database\DataSources\DataSourceDef::class, $knownSources->getBaseGame());
        $this->assertInstanceOf(\Mistralys\X4\Database\DataSources\DataSourceDef::class, $knownSources->getCradleOfHumanity());
        $this->assertInstanceOf(\Mistralys\X4\Database\DataSources\DataSourceDef::class, $knownSources->getEnvoyPack());
        $this->assertInstanceOf(\Mistralys\X4\Database\DataSources\DataSourceDef::class, $knownSources->getHyperionPack());
        $this->assertInstanceOf(\Mistralys\X4\Database\DataSources\DataSourceDef::class, $knownSources->getKingdomEnd());
        $this->assertInstanceOf(\Mistralys\X4\Database\DataSources\DataSourceDef::class, $knownSources->getSplitVendetta());
        $this->assertInstanceOf(\Mistralys\X4\Database\DataSources\DataSourceDef::class, $knownSources->getTidesOfAvarice());
        $this->assertInstanceOf(\Mistralys\X4\Database\DataSources\DataSourceDef::class, $knownSources->getTimelines());
    }

    /**
     * Test DLCs utility class constants
     */
    public function test_DLCs_constants(): void
    {
        $expectedDLCs = [
            DLCs::DLC_BORON,
            DLCs::DLC_HYPERION,
            DLCs::DLC_PIRATE,
            DLCs::DLC_SPLIT,
            DLCs::DLC_TERRAN,
            DLCs::DLC_TIMELINES
        ];

        foreach ($expectedDLCs as $dlcID) {
            $this->assertTrue(
                DataSourceDefs::getInstance()->idExists($dlcID),
                sprintf('DLC constant [%s] does not exist in data sources', $dlcID)
            );
        }
    }

    /**
     * Test DLCs::DLCS array contains valid DLC IDs
     */
    public function test_DLCs_arrayIsValid(): void
    {
        foreach (DLCs::DLCS as $dlcID) {
            $this->assertTrue(
                DataSourceDefs::getInstance()->idExists($dlcID),
                sprintf('DLC [%s] from DLCS array does not exist in data sources', $dlcID)
            );
        }
    }

    /**
     * Test that DLCs array does not include vanilla
     */
    public function test_DLCs_arrayDoesNotIncludeVanilla(): void
    {
        $this->assertNotContains(
            'vanilla',
            DLCs::DLCS,
            'DLCs::DLCS should not contain vanilla'
        );
    }

    /**
     * Test that all DLCs in array are actually extensions
     */
    public function test_DLCs_allAreExtensions(): void
    {
        $dataSources = DataSourceDefs::getInstance();

        foreach (DLCs::DLCS as $dlcID) {
            $dataSource = $dataSources->getByID($dlcID);
            
            $this->assertTrue(
                $dataSource->isExtension(),
                sprintf('DLC [%s] should be marked as extension', $dlcID)
            );
        }
    }

    /**
     * Test constant values match expected IDs
     */
    public function test_constantValuesCorrect(): void
    {
        $this->assertSame('vanilla', KnownDataSources::DATA_SOURCE_BASE_GAME);
        $this->assertSame('ego_dlc_terran', KnownDataSources::DATA_SOURCE_CRADLE_HUMANITY);
        $this->assertSame('ego_dlc_mini_02', KnownDataSources::DATA_SOURCE_ENVOY_PACK);
        $this->assertSame('ego_dlc_mini_01', KnownDataSources::DATA_SOURCE_HYPERION_PACK);
        $this->assertSame('ego_dlc_boron', KnownDataSources::DATA_SOURCE_KINGDOM_END);
        $this->assertSame('ego_dlc_split', KnownDataSources::DATA_SOURCE_SPLIT_VENDETTA);
        $this->assertSame('ego_dlc_pirate', KnownDataSources::DATA_SOURCE_TIDES_AVARICE);
        $this->assertSame('ego_dlc_timelines', KnownDataSources::DATA_SOURCE_TIMELINES);
    }
}
