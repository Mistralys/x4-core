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
}
