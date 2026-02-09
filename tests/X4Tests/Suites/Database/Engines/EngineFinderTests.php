<?php
/**
 * @package X4Tests
 * @subpackage Database\Engines
 * @see \Mistralys\X4\Database\Engines\EngineFinder
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Engines;

use Mistralys\X4\Database\Engines\EngineDef;
use Mistralys\X4\Database\Engines\EngineDefs;
use Mistralys\X4\Database\Engines\EngineFinder;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the EngineFinder which provides filtered
 * searching capabilities for the engines collection
 *
 * @package X4Tests
 * @subpackage Database\Engines
 */
final class EngineFinderTests extends X4TestCase
{
    private EngineDefs $engines;

    protected function setUp(): void
    {
        parent::setUp();
        $this->engines = EngineDefs::getInstance();
    }

    // =========================================================================
    // Basic Finder Tests
    // =========================================================================

    public function test_finderInstantiation(): void
    {
        $finder = new EngineFinder();
        $this->assertInstanceOf(EngineFinder::class, $finder);
    }

    public function test_getAll_noFilters(): void
    {
        $finder = new EngineFinder();
        $results = $finder->getAll();

        $this->assertNotEmpty($results);
        $this->assertEquals(count($this->engines->getAll()), count($results));
    }

    // =========================================================================
    // Size Filter Tests
    // =========================================================================

    public function test_selectSize_small(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectSize('s')->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $engine) {
            $this->assertInstanceOf(EngineDef::class, $engine);
            $this->assertSame('s', $engine->getSize());
        }
    }

    public function test_selectSize_medium(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectSize('m')->getAll();

        foreach ($results as $engine) {
            $this->assertSame('m', $engine->getSize());
        }
    }

    public function test_selectSize_large(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectSize('l')->getAll();

        foreach ($results as $engine) {
            $this->assertSame('l', $engine->getSize());
        }
    }

    public function test_selectSize_extraLarge(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectSize('xl')->getAll();

        foreach ($results as $engine) {
            $this->assertSame('xl', $engine->getSize());
        }
    }

    public function test_selectSize_multipleSizes(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectSize('s')
            ->selectSize('m')
            ->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $engine) {
            $this->assertContains($engine->getSize(), ['s', 'm']);
        }
    }

    public function test_selectSize_noDuplicates(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectSize('m')
            ->selectSize('m') // Select same size twice
            ->getAll();

        $ids = [];
        foreach ($results as $engine) {
            $id = $engine->getID();
            $this->assertNotContains($id, $ids, "Duplicate engine found: $id");
            $ids[] = $id;
        }
    }

    // =========================================================================
    // Maker Race Filter Tests
    // =========================================================================

    public function test_selectMakerRace_argon(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectMakerRace('argon')->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $engine) {
            $this->assertSame('argon', $engine->getMakerRace());
        }
    }

    public function test_selectMakerRace_paranid(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectMakerRace('paranid')->getAll();

        foreach ($results as $engine) {
            $this->assertSame('paranid', $engine->getMakerRace());
        }
    }

    public function test_selectMakerRace_multipleRaces(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectMakerRace('argon')
            ->selectMakerRace('teladi')
            ->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $engine) {
            $this->assertContains($engine->getMakerRace(), ['argon', 'teladi']);
        }
    }

    // =========================================================================
    // Mk (Mark Level) Filter Tests
    // =========================================================================

    public function test_selectMk_mk1(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectMk(1)->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $engine) {
            $this->assertSame(1, $engine->getMk());
        }
    }

    public function test_selectMk_mk2(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectMk(2)->getAll();

        foreach ($results as $engine) {
            $this->assertSame(2, $engine->getMk());
        }
    }

    public function test_selectMk_mk3(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectMk(3)->getAll();

        foreach ($results as $engine) {
            $this->assertSame(3, $engine->getMk());
        }
    }

    public function test_selectMk_multipleMks(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectMk(1)
            ->selectMk(2)
            ->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $engine) {
            $this->assertContains($engine->getMk(), [1, 2]);
        }
    }

    // =========================================================================
    // Thrust Filter Tests
    // =========================================================================

    public function test_selectMinThrust(): void
    {
        $minThrust = 5000.0;
        $finder = new EngineFinder();
        $results = $finder->selectMinThrust($minThrust)->getAll();

        foreach ($results as $engine) {
            $this->assertGreaterThanOrEqual($minThrust, $engine->getThrustForward());
        }
    }

    public function test_selectMaxThrust(): void
    {
        $maxThrust = 5000.0;
        $finder = new EngineFinder();
        $results = $finder->selectMaxThrust($maxThrust)->getAll();

        foreach ($results as $engine) {
            $this->assertLessThanOrEqual($maxThrust, $engine->getThrustForward());
        }
    }

    // =========================================================================
    // Boost Duration Tests
    // =========================================================================

    public function test_selectMinBoostDuration(): void
    {
        $minDuration = 20.0;
        $finder = new EngineFinder();
        $results = $finder->selectMinBoostDuration($minDuration)->getAll();

        foreach ($results as $engine) {
            $this->assertGreaterThanOrEqual($minDuration, $engine->getBoostDuration());
        }
    }

    public function test_selectMaxBoostRecharge(): void
    {
        $maxRecharge = 100.0;
        $finder = new EngineFinder();
        $results = $finder->selectMaxBoostRecharge($maxRecharge)->getAll();

        foreach ($results as $engine) {
            $this->assertLessThanOrEqual($maxRecharge, $engine->getBoostRecharge());
        }
    }

    // =========================================================================
    // Boost Thrust Tests
    // =========================================================================

    public function test_selectMinBoostThrust(): void
    {
        $minThrust = 5.0;
        $finder = new EngineFinder();
        $results = $finder->selectMinBoostThrust($minThrust)->getAll();

        foreach ($results as $engine) {
            $this->assertGreaterThanOrEqual($minThrust, $engine->getBoostThrust());
        }
    }

    // =========================================================================
    // Travel Tests
    // =========================================================================

    public function test_selectMinTravelThrust(): void
    {
        $minThrust = 20.0;
        $finder = new EngineFinder();
        $results = $finder->selectMinTravelThrust($minThrust)->getAll();

        foreach ($results as $engine) {
            $this->assertGreaterThanOrEqual($minThrust, $engine->getTravelThrust());
        }
    }

    public function test_selectMaxTravelCharge(): void
    {
        $maxCharge = 30.0;
        $finder = new EngineFinder();
        $results = $finder->selectMaxTravelCharge($maxCharge)->getAll();

        foreach ($results as $engine) {
            $this->assertLessThanOrEqual($maxCharge, $engine->getTravelCharge());
        }
    }

    // =========================================================================
    // Hull Tests
    // =========================================================================

    public function test_selectMinHull(): void
    {
        $minHull = 3000.0;
        $finder = new EngineFinder();
        $results = $finder->selectMinHull($minHull)->getAll();

        foreach ($results as $engine) {
            $this->assertGreaterThanOrEqual($minHull, $engine->getHullMax());
        }
    }

    // =========================================================================
    // Deceleration Curve Tests
    // =========================================================================

    public function test_selectWithDecelerationCurve(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectWithDecelerationCurve()->getAll();

        foreach ($results as $engine) {
            $this->assertTrue($engine->hasDecelerationCurve());
        }
    }

    // =========================================================================
    // Label Filter Tests
    // =========================================================================

    public function test_selectLabelSearch_exactMatch(): void
    {
        $allEngines = $this->engines->getAll();
        $this->assertNotEmpty($allEngines);
        
        $sampleEngine = reset($allEngines);
        $label = $sampleEngine->getLabel();

        $finder = new EngineFinder();
        $results = $finder->selectLabelSearch($label)->getAll();

        $this->assertNotEmpty($results);
        
        $found = false;
        foreach ($results as $engine) {
            if ($engine->getID() === $sampleEngine->getID()) {
                $found = true;
                break;
            }
        }
        
        $this->assertTrue($found, "Sample engine not found in label-filtered results");
    }

    public function test_selectLabelSearch_partialMatch(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectLabelSearch('allround')->getAll();

        if (empty($results)) {
            $this->markTestSkipped('No engines with "allround" in label found in test data');
        }

        foreach ($results as $engine) {
            $this->assertStringContainsStringIgnoringCase('allround', $engine->getLabel());
        }
    }

    public function test_selectLabelSearch_caseInsensitive(): void
    {
        $finder1 = new EngineFinder();
        $results1 = $finder1->selectLabelSearch('ENGINE')->getAll();

        $finder2 = new EngineFinder();
        $results2 = $finder2->selectLabelSearch('engine')->getAll();

        $this->assertEquals(count($results1), count($results2));
    }

    public function test_selectLabelSearch_noMatch(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectLabelSearch('xyz_nonexistent_label_xyz')->getAll();

        $this->assertEmpty($results);
    }

    // =========================================================================
    // Data Source Filter Tests
    // =========================================================================

    public function test_selectDataSource_vanilla(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectDataSource('vanilla')->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $engine) {
            $this->assertSame('vanilla', $engine->getDataSourceID());
        }
    }

    public function test_selectDataSource_dlc(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectDataSource('ego_dlc_terran')->getAll();

        // May be empty if DLC not included, so we don't assert non-empty
        foreach ($results as $engine) {
            $this->assertSame('ego_dlc_terran', $engine->getDataSourceID());
        }
    }

    public function test_selectDataSource_multipleSources(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectDataSource('vanilla')
            ->selectDataSource('ego_dlc_terran')
            ->getAll();

        foreach ($results as $engine) {
            $this->assertContains($engine->getDataSourceID(), ['vanilla', 'ego_dlc_terran']);
        }
    }

    // =========================================================================
    // Combined Filter Tests
    // =========================================================================

    public function test_sizeAndRace(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectSize('l')
            ->selectMakerRace('argon')
            ->getAll();

        foreach ($results as $engine) {
            $this->assertSame('l', $engine->getSize());
            $this->assertSame('argon', $engine->getMakerRace());
        }
    }

    public function test_sizeAndMk(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectSize('m')
            ->selectMk(1)
            ->getAll();

        foreach ($results as $engine) {
            $this->assertSame('m', $engine->getSize());
            $this->assertSame(1, $engine->getMk());
        }
    }

    public function test_thrustRange(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectMinThrust(3000.0)
            ->selectMaxThrust(5000.0)
            ->getAll();

        foreach ($results as $engine) {
            $this->assertGreaterThanOrEqual(3000.0, $engine->getThrustForward());
            $this->assertLessThanOrEqual(5000.0, $engine->getThrustForward());
        }
    }

    public function test_boostFilters(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectMinBoostDuration(20.0)
            ->selectMaxBoostRecharge(100.0)
            ->getAll();

        foreach ($results as $engine) {
            $this->assertGreaterThanOrEqual(20.0, $engine->getBoostDuration());
            $this->assertLessThanOrEqual(100.0, $engine->getBoostRecharge());
        }
    }

    public function test_complexChain(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectSize('l')
            ->selectMakerRace('argon')
            ->selectMk(1)
            ->selectMinThrust(3000.0)
            ->selectMinBoostDuration(20.0)
            ->selectDataSource('vanilla')
            ->getAll();

        foreach ($results as $engine) {
            $this->assertSame('l', $engine->getSize());
            $this->assertSame('argon', $engine->getMakerRace());
            $this->assertSame(1, $engine->getMk());
            $this->assertGreaterThanOrEqual(3000.0, $engine->getThrustForward());
            $this->assertGreaterThanOrEqual(20.0, $engine->getBoostDuration());
            $this->assertSame('vanilla', $engine->getDataSourceID());
        }
    }

    public function test_allFiltersApplied(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectSize('m')
            ->selectSize('l')
            ->selectMakerRace('argon')
            ->selectMk(1)
            ->selectMk(2)
            ->selectMinThrust(1000.0)
            ->getAll();

        $this->assertNotEmpty($results);

        foreach ($results as $engine) {
            $this->assertContains($engine->getSize(), ['m', 'l']);
            $this->assertSame('argon', $engine->getMakerRace());
            $this->assertContains($engine->getMk(), [1, 2]);
            $this->assertGreaterThanOrEqual(1000.0, $engine->getThrustForward());
        }
    }

    // =========================================================================
    // Edge Case Tests
    // =========================================================================

    public function test_findHighPerformanceEngines(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectMinThrust(10000.0)
            ->selectMinBoostThrust(10.0)
            ->selectMinBoostDuration(30.0)
            ->getAll();

        // High performance engines may not exist, so just verify filter logic works
        if (empty($results)) {
            $this->addToAssertionCount(1);
            return;
        }
        
        foreach ($results as $engine) {
            $this->assertGreaterThanOrEqual(10000.0, $engine->getThrustForward());
            $this->assertGreaterThanOrEqual(10.0, $engine->getBoostThrust());
            $this->assertGreaterThanOrEqual(30.0, $engine->getBoostDuration());
        }
    }

    public function test_findLowPerformanceEngines(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectMaxThrust(2000.0)
            ->getAll();

        foreach ($results as $engine) {
            $this->assertLessThanOrEqual(2000.0, $engine->getThrustForward());
        }
    }

    public function test_contradictoryFilters_returnsEmpty(): void
    {
        $finder = new EngineFinder();
        $results = $finder
            ->selectMinThrust(10000.0)
            ->selectMaxThrust(1000.0) // Contradictory: max < min
            ->getAll();

        $this->assertEmpty($results);
    }

    // =========================================================================
    // Result Handling Tests
    // =========================================================================

    public function test_getAll_returnsArray(): void
    {
        $finder = new EngineFinder();
        $results = $finder->getAll();

        $this->assertIsArray($results);
    }

    public function test_results_areEngineDefs(): void
    {
        $finder = new EngineFinder();
        $results = $finder->selectSize('m')->getAll();

        foreach ($results as $engine) {
            $this->assertInstanceOf(EngineDef::class, $engine);
        }
    }

    public function test_fluentInterface(): void
    {
        $finder = new EngineFinder();
        
        $result = $finder
            ->selectSize('l')
            ->selectMakerRace('argon')
            ->selectMk(1);

        $this->assertInstanceOf(EngineFinder::class, $result);
    }
}
