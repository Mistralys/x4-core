<?php
/**
 * @package X4Tests
 * @subpackage Database\Engines
 * @see \Mistralys\X4\Database\Engines\EngineDef
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Engines;

use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\Engines\EngineDef;
use Mistralys\X4\Database\Engines\EngineDefs;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the EngineDef item class which represents
 * a single engine with performance characteristics.
 *
 * @package X4Tests
 * @subpackage Database\Engines
 */
final class EngineDefTests extends X4TestCase
{
    private function getSampleEngine(): EngineDef
    {
        return EngineDefs::getInstance()->getByID('engine_arg_l_allround_01_mk1');
    }

    // =========================================================================
    // Core Property Tests
    // =========================================================================

    public function test_getID(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsString($engine->getID());
        $this->assertSame('engine_arg_l_allround_01_mk1', $engine->getID());
    }

    public function test_getWareID(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsString($engine->getWareID());
        $this->assertSame($engine->getID(), $engine->getWareID());
    }

    public function test_getLabel(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsString($engine->getLabel());
        $this->assertNotEmpty($engine->getLabel());
        $this->assertStringContainsString('ARG', $engine->getLabel());
    }

    public function test_getMacroID(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsString($engine->getMacroID());
        $this->assertNotEmpty($engine->getMacroID());
        $this->assertStringContainsString('macro', $engine->getMacroID());
    }

    public function test_getSize(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsString($engine->getSize());
        $this->assertSame('l', $engine->getSize());
    }

    public function test_getDataSourceID(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsString($engine->getDataSourceID());
        $this->assertSame('vanilla', $engine->getDataSourceID());
    }

    public function test_getMakerRace(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsString($engine->getMakerRace());
        $this->assertSame('argon', $engine->getMakerRace());
    }

    public function test_getMk(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsInt($engine->getMk());
        $this->assertSame(1, $engine->getMk());
    }

    public function test_getVariantID(): void
    {
        $engine = $this->getSampleEngine();
        $variantID = $engine->getVariantID();
        
        $this->assertInstanceOf(VariantID::class, $variantID);
    }

    // =========================================================================
    // Boost Property Tests
    // =========================================================================

    public function test_getBoostDuration(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getBoostDuration());
        $this->assertGreaterThan(0, $engine->getBoostDuration());
    }

    public function test_getBoostRecharge(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getBoostRecharge());
        $this->assertGreaterThan(0, $engine->getBoostRecharge());
    }

    public function test_getBoostThrust(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getBoostThrust());
        $this->assertGreaterThan(0, $engine->getBoostThrust());
    }

    public function test_getBoostAcceleration(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getBoostAcceleration());
        $this->assertGreaterThan(0, $engine->getBoostAcceleration());
    }

    public function test_getBoostAttack(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getBoostAttack());
        $this->assertGreaterThanOrEqual(0, $engine->getBoostAttack());
    }

    public function test_getBoostRelease(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getBoostRelease());
        $this->assertGreaterThanOrEqual(0, $engine->getBoostRelease());
    }

    public function test_getBoostCoast(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getBoostCoast());
        $this->assertGreaterThan(0, $engine->getBoostCoast());
    }

    // =========================================================================
    // Travel Property Tests
    // =========================================================================

    public function test_getTravelCharge(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getTravelCharge());
        $this->assertGreaterThan(0, $engine->getTravelCharge());
    }

    public function test_getTravelThrust(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getTravelThrust());
        $this->assertGreaterThan(0, $engine->getTravelThrust());
    }

    public function test_getTravelAttack(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getTravelAttack());
        $this->assertGreaterThanOrEqual(0, $engine->getTravelAttack());
    }

    public function test_getTravelRelease(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getTravelRelease());
        $this->assertGreaterThanOrEqual(0, $engine->getTravelRelease());
    }

    // =========================================================================
    // Thrust Property Tests
    // =========================================================================

    public function test_getThrustForward(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getThrustForward());
        $this->assertSame(3900.0, $engine->getThrustForward());
    }

    public function test_getThrustReverse(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getThrustReverse());
        $this->assertSame(3705.0, $engine->getThrustReverse());
    }

    // =========================================================================
    // Hull Property Tests
    // =========================================================================

    public function test_getHullMax(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getHullMax());
        $this->assertGreaterThan(0, $engine->getHullMax());
    }

    public function test_getHullThreshold(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertIsFloat($engine->getHullThreshold());
        $this->assertGreaterThanOrEqual(0, $engine->getHullThreshold());
    }

    // =========================================================================
    // Deceleration Curve Tests
    // =========================================================================

    public function test_getDecelerationCurve(): void
    {
        $engine = $this->getSampleEngine();
        $curve = $engine->getDecelerationCurve();
        
        $this->assertIsArray($curve);
    }

    public function test_hasDecelerationCurve_true(): void
    {
        $engine = $this->getSampleEngine();
        
        $this->assertTrue($engine->hasDecelerationCurve());
    }

    public function test_decelerationCurve_structure(): void
    {
        $engine = $this->getSampleEngine();
        $curve = $engine->getDecelerationCurve();
        
        $this->assertNotEmpty($curve);
        
        foreach ($curve as $point) {
            $this->assertIsArray($point);
            $this->assertArrayHasKey('position', $point);
            $this->assertArrayHasKey('value', $point);
            $this->assertIsNumeric($point['position']);
            $this->assertIsNumeric($point['value']);
        }
    }

    // =========================================================================
    // Ware Integration Tests
    // =========================================================================

    public function test_getWare(): void
    {
        $engine = $this->getSampleEngine();
        $ware = $engine->getWare();
        
        $this->assertInstanceOf(\Mistralys\X4\Database\Wares\WareDef::class, $ware);
        $this->assertSame($engine->getWareID(), $ware->getID());
    }

    // =========================================================================
    // fromArray Tests
    // =========================================================================

    public function test_fromArray(): void
    {
        $data = [
            EngineDef::KEY_WARE_ID => 'test_engine',
            EngineDef::KEY_MACRO_ID => 'test_engine_macro',
            EngineDef::KEY_LABEL => 'Test Engine',
            EngineDef::KEY_SIZE => 'm',
            EngineDef::KEY_DATA_SOURCE_ID => 'vanilla',
            EngineDef::KEY_MAKER_RACE => 'argon',
            EngineDef::KEY_MK => 2,
            EngineDef::KEY_VARIANT_ID => '01::mk2',
            EngineDef::KEY_BOOST_DURATION => 25.0,
            EngineDef::KEY_BOOST_RECHARGE => 90.0,
            EngineDef::KEY_BOOST_THRUST => 7.0,
            EngineDef::KEY_BOOST_ACCELERATION => 5.0,
            EngineDef::KEY_BOOST_ATTACK => 12.0,
            EngineDef::KEY_BOOST_RELEASE => 6.0,
            EngineDef::KEY_BOOST_COAST => 1.1,
            EngineDef::KEY_TRAVEL_CHARGE => 18.0,
            EngineDef::KEY_TRAVEL_THRUST => 32.0,
            EngineDef::KEY_TRAVEL_ATTACK => 95.0,
            EngineDef::KEY_TRAVEL_RELEASE => 24.0,
            EngineDef::KEY_THRUST_FORWARD => 4200.0,
            EngineDef::KEY_THRUST_REVERSE => 3900.0,
            EngineDef::KEY_HULL_MAX => 4500.0,
            EngineDef::KEY_HULL_THRESHOLD => 0.35,
            EngineDef::KEY_DECELERATION_CURVE => [],
        ];

        $engine = EngineDef::fromArray($data);

        $this->assertInstanceOf(EngineDef::class, $engine);
        $this->assertSame('test_engine', $engine->getID());
        $this->assertSame('Test Engine', $engine->getLabel());
        $this->assertSame('m', $engine->getSize());
        $this->assertSame(2, $engine->getMk());
        $this->assertSame(4200.0, $engine->getThrustForward());
    }

    // =========================================================================
    // Edge Case Tests
    // =========================================================================

    public function test_allEnginesHaveValidData(): void
    {
        $engines = EngineDefs::getInstance()->getAll();
        
        $this->assertNotEmpty($engines);
        
        foreach ($engines as $engine) {
            $this->assertInstanceOf(EngineDef::class, $engine);
            $this->assertNotEmpty($engine->getID());
            $this->assertNotEmpty($engine->getLabel());
            $this->assertIsString($engine->getSize()); // Can be empty for spacesuits
            $this->assertIsString($engine->getMakerRace());
            $this->assertGreaterThan(0, $engine->getMk());
        }
    }

    public function test_performanceValuesAreReasonable(): void
    {
        $engines = EngineDefs::getInstance()->getAll();
        
        foreach ($engines as $engine) {
            // Thrust values should be positive
            $this->assertGreaterThan(0, $engine->getThrustForward(), 
                "Engine {$engine->getID()} has invalid forward thrust");
            
            // Boost duration should be reasonable (0-4000 seconds, spacesuits have 3600)
            $this->assertGreaterThanOrEqual(0, $engine->getBoostDuration());
            $this->assertLessThan(5000, $engine->getBoostDuration());
            
            // Hull should be non-negative (0 or positive)
            $this->assertGreaterThanOrEqual(0, $engine->getHullMax(),
                "Engine {$engine->getID()} has invalid hull");
        }
    }
}
