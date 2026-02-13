<?php
/**
 * @package X4Tests
 * @subpackage Database\Shields
 * @see \Mistralys\X4\Database\Shields\ShieldDef
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Shields;

use Mistralys\X4\Database\Shields\ShieldDef;
use Mistralys\X4\Database\Shields\ShieldDefs;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the ShieldDef item class
 *
 * @package X4Tests
 * @subpackage Database\Shields
 */
final class ShieldDefTests extends X4TestCase
{
    private ShieldDef $shield;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Get a shield for testing
        $allShields = ShieldDefs::getInstance()->getAll();
        $this->assertNotEmpty($allShields, 'Shield collection should not be empty');
        
        $this->shield = $allShields[0];
    }

    // =========================================================================
    // Basic Property Tests
    // =========================================================================

    public function test_getID(): void
    {
        $id = $this->shield->getID();
        
        $this->assertIsString($id);
        $this->assertNotEmpty($id);
        $this->assertStringStartsWith('shield_', $id);
    }

    public function test_getLabel(): void
    {
        $label = $this->shield->getLabel();
        
        $this->assertIsString($label);
        $this->assertNotEmpty($label);
    }

    public function test_getSize(): void
    {
        $size = $this->shield->getSize();
        
        $this->assertIsString($size);
        $this->assertContains($size, ['s', 'm', 'l', 'xl']);
    }

    public function test_getMacroID(): void
    {
        $macroID = $this->shield->getMacroID();
        
        $this->assertIsString($macroID);
        $this->assertNotEmpty($macroID);
    }

    public function test_getDataSourceID(): void
    {
        $dataSourceID = $this->shield->getDataSourceID();
        
        $this->assertIsString($dataSourceID);
        $this->assertNotEmpty($dataSourceID);
    }

    public function test_getMakerRace(): void
    {
        $makerRace = $this->shield->getMakerRace();
        
        $this->assertIsString($makerRace);
        $this->assertNotEmpty($makerRace);
    }

    public function test_getMk(): void
    {
        $mk = $this->shield->getMk();
        
        $this->assertIsInt($mk);
        $this->assertGreaterThanOrEqual(1, $mk);
        $this->assertLessThanOrEqual(3, $mk);
    }

    public function test_getShieldType(): void
    {
        $type = $this->shield->getShieldType();
        
        $this->assertIsString($type);
        $this->assertContains($type, [
            'standard', 'racer', 'corvette', 'mothership', 
            'yacht', 'experimental', 'virtual'
        ]);
    }

    // =========================================================================
    // Recharge Property Tests
    // =========================================================================

    public function test_getRechargeMax(): void
    {
        $rechargeMax = $this->shield->getRechargeMax();
        
        $this->assertIsFloat($rechargeMax);
        $this->assertGreaterThanOrEqual(0.0, $rechargeMax);
    }

    public function test_getCapacity(): void
    {
        $capacity = $this->shield->getCapacity();
        
        $this->assertIsFloat($capacity);
        $this->assertSame($this->shield->getRechargeMax(), $capacity);
    }

    public function test_getRechargeRate(): void
    {
        $rechargeRate = $this->shield->getRechargeRate();
        
        $this->assertIsFloat($rechargeRate);
        $this->assertGreaterThanOrEqual(0.0, $rechargeRate);
    }

    public function test_getRechargeDelay(): void
    {
        $rechargeDelay = $this->shield->getRechargeDelay();
        
        $this->assertIsFloat($rechargeDelay);
        $this->assertGreaterThanOrEqual(0.0, $rechargeDelay);
    }

    public function test_getFullRechargeTime(): void
    {
        $rechargeTime = $this->shield->getFullRechargeTime();
        
        $this->assertIsFloat($rechargeTime);
        $this->assertGreaterThanOrEqual(0.0, $rechargeTime);
        
        // If shield has recharge rate, verify calculation
        if ($this->shield->getRechargeRate() > 0.0) {
            $expectedTime = ($this->shield->getCapacity() / $this->shield->getRechargeRate()) 
                          + $this->shield->getRechargeDelay();
            $this->assertEqualsWithDelta($expectedTime, $rechargeTime, 0.01);
        }
    }

    // =========================================================================
    // Hull Property Tests
    // =========================================================================

    public function test_getHullMax(): void
    {
        $hullMax = $this->shield->getHullMax();
        
        $this->assertIsFloat($hullMax);
        $this->assertGreaterThanOrEqual(0.0, $hullMax);
    }

    public function test_getHullThreshold(): void
    {
        $hullThreshold = $this->shield->getHullThreshold();
        
        $this->assertIsFloat($hullThreshold);
        $this->assertGreaterThanOrEqual(0.0, $hullThreshold);
    }

    public function test_isHullIntegrated(): void
    {
        $integrated = $this->shield->isHullIntegrated();
        
        $this->assertIsBool($integrated);
    }

    public function test_hasHull(): void
    {
        $hasHull = $this->shield->hasHull();
        
        $this->assertIsBool($hasHull);
        
        if ($this->shield->getHullMax() > 0.0) {
            $this->assertTrue($hasHull);
        }
    }

    // =========================================================================
    // Type Check Tests
    // =========================================================================

    public function test_typeChecks(): void
    {
        $type = $this->shield->getShieldType();
        
        // Only one type check should return true
        $typeChecks = [
            'standard' => $this->shield->isStandard(),
            'racer' => $this->shield->isRacer(),
            'corvette' => $this->shield->isCorvette(),
            'mothership' => $this->shield->isMothership(),
            'yacht' => $this->shield->isYacht(),
            'experimental' => $this->shield->isExperimental(),
            'virtual' => $this->shield->isVirtual(),
        ];
        
        $trueCount = count(array_filter($typeChecks));
        $this->assertSame(1, $trueCount, 'Exactly one type check should return true');
        
        // The correct type should match
        $this->assertTrue($typeChecks[$type], "Type check for '{$type}' should return true");
    }

    // =========================================================================
    // Multi-Maker Race Tests
    // =========================================================================

    /**
     * Test multi-maker-race support for shields.
     * Some shields have multiple maker races (e.g., "argon teladi").
     */
    public function test_multiMakerRace(): void
    {
        $shields = ShieldDefs::getInstance();
        
        // Try to find a shield with compound makerRace
        // Look through all shields to find one with multiple races
        $multiRaceShield = null;
        foreach ($shields->getAll() as $shield) {
            if ($shield->hasMultipleMakerRaces()) {
                $multiRaceShield = $shield;
                break;
            }
        }
        
        // If no multi-race shield found, test with mock data
        if ($multiRaceShield === null) {
            // Test fromArray with compound makerRace string (old format)
            $mockData = [
                ShieldDef::KEY_WARE_ID => 'shield_test_multi',
                ShieldDef::KEY_MACRO_ID => 'shield_test_multi_macro',
                ShieldDef::KEY_LABEL => 'Test Multi-Race Shield',
                ShieldDef::KEY_SIZE => 'm',
                ShieldDef::KEY_DATA_SOURCE_ID => 'vanilla',
                ShieldDef::KEY_MAKER_RACE => 'argon teladi',
                ShieldDef::KEY_MK => 1,
                ShieldDef::KEY_VARIANT_ID => '01',
                ShieldDef::KEY_SHIELD_TYPE => 'standard',
                ShieldDef::KEY_RECHARGE_MAX => 1000.0,
                ShieldDef::KEY_RECHARGE_RATE => 100.0,
                ShieldDef::KEY_RECHARGE_DELAY => 2.0,
                ShieldDef::KEY_HULL_MAX => 500.0,
                ShieldDef::KEY_HULL_THRESHOLD => 0.5,
                ShieldDef::KEY_HULL_INTEGRATED => false
            ];
            
            $multiRaceShield = ShieldDef::fromArray($mockData);
        }
        
        // Test the new methods
        $this->assertTrue($multiRaceShield->hasMultipleMakerRaces(), 'Shield should have multiple maker races');
        $races = $multiRaceShield->getMakerRaces();
        $this->assertIsArray($races, 'getMakerRaces() should return an array');
        $this->assertGreaterThanOrEqual(2, count($races), 'Should have at least 2 maker races');
        $this->assertContains('argon', $races, 'Should have argon as a maker race');
        $this->assertContains('teladi', $races, 'Should have teladi as a maker race');
        
        // Test backward compatibility - should return the first (primary) race
        $this->assertEquals('argon', $multiRaceShield->getMakerRace(), 'Primary maker race should be argon');
    }
}
