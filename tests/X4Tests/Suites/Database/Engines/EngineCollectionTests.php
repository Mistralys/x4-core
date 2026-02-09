<?php
/**
 * @package X4Tests
 * @subpackage Database\Engines
 * @see \Mistralys\X4\Database\Engines\EngineDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Engines;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Engines\EngineDef;
use Mistralys\X4\Database\Engines\EngineDefs;
use Mistralys\X4\Database\Engines\EngineFinder;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the EngineDefs collection class
 *
 * @package X4Tests
 * @subpackage Database\Engines
 */
final class EngineCollectionTests extends X4TestCase
{
    // =========================================================================
    // Singleton Tests
    // =========================================================================

    public function test_singleton(): void
    {
        $instance1 = EngineDefs::getInstance();
        $instance2 = EngineDefs::getInstance();
        
        $this->assertSame($instance1, $instance2);
    }

    // =========================================================================
    // Collection Access Tests
    // =========================================================================

    public function test_getAll(): void
    {
        $engines = EngineDefs::getInstance()->getAll();
        
        $this->assertIsArray($engines);
        $this->assertNotEmpty($engines);
        $this->assertGreaterThan(100, count($engines), 'Should have at least 100 engines');
        
        foreach ($engines as $engine) {
            $this->assertInstanceOf(EngineDef::class, $engine);
        }
    }

    public function test_getByID_validID(): void
    {
        $engines = EngineDefs::getInstance();
        $engine = $engines->getByID('engine_arg_l_allround_01_mk1');
        
        $this->assertInstanceOf(EngineDef::class, $engine);
        $this->assertSame('engine_arg_l_allround_01_mk1', $engine->getID());
    }

    public function test_getByID_invalidID(): void
    {
        $this->expectException(RecordNotExistsException::class);
        
        EngineDefs::getInstance()->getByID('invalid_engine_id');
    }

    public function test_getDefault(): void
    {
        $engine = EngineDefs::getInstance()->getDefault();
        
        $this->assertInstanceOf(EngineDef::class, $engine);
    }

    public function test_getDefaultID(): void
    {
        $id = EngineDefs::getInstance()->getDefaultID();
        
        $this->assertIsString($id);
        $this->assertNotEmpty($id);
        
        // Should be able to get the default engine by its ID
        $engine = EngineDefs::getInstance()->getByID($id);
        $this->assertInstanceOf(EngineDef::class, $engine);
    }

    // =========================================================================
    // Find Methods Tests
    // =========================================================================

    public function test_find_byWareID(): void
    {
        $engine = EngineDefs::getInstance()->find('engine_arg_l_allround_01_mk1');
        
        $this->assertInstanceOf(EngineDef::class, $engine);
        $this->assertSame('engine_arg_l_allround_01_mk1', $engine->getID());
    }

    public function test_find_byMacroID(): void
    {
        $engine = EngineDefs::getInstance()->find('engine_arg_l_allround_01_mk1_macro');
        
        $this->assertInstanceOf(EngineDef::class, $engine);
        $this->assertSame('engine_arg_l_allround_01_mk1_macro', $engine->getMacroID());
    }

    public function test_find_invalidID(): void
    {
        $engine = EngineDefs::getInstance()->find('invalid_id');
        
        $this->assertNull($engine);
    }

    public function test_findByMacro_validMacro(): void
    {
        $engine = EngineDefs::getInstance()->findByMacro('engine_arg_l_allround_01_mk1_macro');
        
        $this->assertInstanceOf(EngineDef::class, $engine);
        $this->assertSame('engine_arg_l_allround_01_mk1_macro', $engine->getMacroID());
    }

    public function test_findByMacro_invalidMacro(): void
    {
        $engine = EngineDefs::getInstance()->findByMacro('invalid_macro');
        
        $this->assertNull($engine);
    }

    // =========================================================================
    // Finder Integration Tests
    // =========================================================================

    public function test_findEngines(): void
    {
        $finder = EngineDefs::getInstance()->findEngines();
        
        $this->assertInstanceOf(EngineFinder::class, $finder);
    }

    public function test_findEngines_returnsResults(): void
    {
        $engines = EngineDefs::getInstance()->findEngines()->getAll();
        
        $this->assertIsArray($engines);
        $this->assertNotEmpty($engines);
    }

    // =========================================================================
    // Data Coverage Tests
    // =========================================================================

    public function test_hasEnginesFromAllSizes(): void
    {
        $sizes = ['s', 'm', 'l', 'xl'];
        $engines = EngineDefs::getInstance()->getAll();
        
        $foundSizes = [];
        foreach ($engines as $engine) {
            $size = $engine->getSize();
            if (!in_array($size, $foundSizes, true)) {
                $foundSizes[] = $size;
            }
        }
        
        foreach ($sizes as $size) {
            $this->assertContains($size, $foundSizes, "No engines found with size: $size");
        }
    }

    public function test_hasEnginesFromMultipleRaces(): void
    {
        $expectedRaces = ['argon', 'paranid', 'teladi', 'split'];
        $engines = EngineDefs::getInstance()->getAll();
        
        $foundRaces = [];
        foreach ($engines as $engine) {
            $race = $engine->getMakerRace();
            if (!in_array($race, $foundRaces, true)) {
                $foundRaces[] = $race;
            }
        }
        
        foreach ($expectedRaces as $race) {
            $this->assertContains($race, $foundRaces, "No engines found from race: $race");
        }
    }

    public function test_hasEnginesFromMultipleMarkLevels(): void
    {
        $engines = EngineDefs::getInstance()->getAll();
        
        $foundMks = [];
        foreach ($engines as $engine) {
            $mk = $engine->getMk();
            if (!in_array($mk, $foundMks, true)) {
                $foundMks[] = $mk;
            }
        }
        
        $this->assertContains(1, $foundMks, 'No Mk1 engines found');
        $this->assertContains(2, $foundMks, 'No Mk2 engines found');
    }

    public function test_hasEnginesFromMultipleDataSources(): void
    {
        $engines = EngineDefs::getInstance()->getAll();
        
        $foundSources = [];
        foreach ($engines as $engine) {
            $source = $engine->getDataSourceID();
            if (!in_array($source, $foundSources, true)) {
                $foundSources[] = $source;
            }
        }
        
        $this->assertContains('vanilla', $foundSources, 'No vanilla engines found');
        $this->assertGreaterThan(1, count($foundSources), 'Should have engines from multiple DLCs');
    }

    // =========================================================================
    // Data Integrity Tests
    // =========================================================================

    public function test_allEnginesHaveUniqueIDs(): void
    {
        $engines = EngineDefs::getInstance()->getAll();
        $ids = [];
        
        foreach ($engines as $engine) {
            $id = $engine->getID();
            $this->assertNotContains($id, $ids, "Duplicate engine ID found: $id");
            $ids[] = $id;
        }
    }

    public function test_allEnginesHaveValidWareReferences(): void
    {
        $engines = EngineDefs::getInstance()->getAll();
        
        foreach ($engines as $engine) {
            // Should not throw exception
            $ware = $engine->getWare();
            $this->assertNotNull($ware);
            $this->assertSame($engine->getWareID(), $ware->getID());
        }
        
        $this->addToAssertionCount(1);
    }

    public function test_dataFileExists(): void
    {
        $dataFile = EngineDefs::getInstance()->getDataFile();
        
        $this->assertTrue($dataFile->exists(), 'engines.json file should exist');
    }

    public function test_dataFileHasValidJSON(): void
    {
        $dataFile = EngineDefs::getInstance()->getDataFile();
        $data = $dataFile->getData();
        
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);
    }

    // =========================================================================
    // Performance Benchmarking Tests
    // =========================================================================

    public function test_collectionLoadPerformance(): void
    {
        $start = microtime(true);
        $engines = EngineDefs::getInstance()->getAll();
        $duration = microtime(true) - $start;
        
        $this->assertLessThan(2.0, $duration, 
            'Loading engine collection should take less than 2 seconds');
        $this->assertNotEmpty($engines);
    }

    public function test_finderInstantiationPerformance(): void
    {
        $start = microtime(true);
        for ($i = 0; $i < 100; $i++) {
            $finder = EngineDefs::getInstance()->findEngines();
        }
        $duration = microtime(true) - $start;
        
        $this->assertLessThan(0.5, $duration,
            'Creating 100 finders should take less than 0.5 seconds');
    }
}
