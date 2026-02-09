<?php
/**
 * @package X4Tests
 * @subpackage Database\Shields
 * @see \Mistralys\X4\Database\Shields\ShieldFinder
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Shields;

use Mistralys\X4\Database\Shields\ShieldDef;
use Mistralys\X4\Database\Shields\ShieldDefs;
use Mistralys\X4\Database\Shields\ShieldFinder;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the ShieldFinder filter class
 *
 * @package X4Tests
 * @subpackage Database\Shields
 */
final class ShieldFinderTests extends X4TestCase
{
    // =========================================================================
    // Basic Finder Tests
    // =========================================================================

    public function test_create(): void
    {
        $finder = ShieldDefs::getInstance()->findShields();
        
        $this->assertInstanceOf(ShieldFinder::class, $finder);
    }

    public function test_getAll(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()->getAll();
        
        $this->assertIsArray($shields);
        $this->assertNotEmpty($shields);
        
        foreach ($shields as $shield) {
            $this->assertInstanceOf(ShieldDef::class, $shield);
        }
    }

    // =========================================================================
    // Size Filter Tests
    // =========================================================================

    public function test_selectSize(): void
    {
        $allShields = ShieldDefs::getInstance()->getAll();
        
        // Get a size that exists in the collection
        $testSize = $allShields[0]->getSize();
        
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectSize($testSize)
            ->getAll();
        
        $this->assertNotEmpty($shields);
        
        foreach ($shields as $shield) {
            $this->assertSame($testSize, $shield->getSize());
        }
    }

    public function test_selectSizes(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectSizes(['s', 'm'])
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertContains($shield->getSize(), ['s', 'm']);
        }
    }

    // =========================================================================
    // Maker Race Filter Tests
    // =========================================================================

    public function test_selectMakerRace(): void
    {
        $allShields = ShieldDefs::getInstance()->getAll();
        
        // Find a non-unknown maker race
        $testRace = 'argon';
        foreach ($allShields as $shield) {
            if ($shield->getMakerRace() !== 'unknown') {
                $testRace = $shield->getMakerRace();
                break;
            }
        }
        
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMakerRace($testRace)
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertSame($testRace, $shield->getMakerRace());
        }
    }

    // =========================================================================
    // Type Filter Tests
    // =========================================================================

    public function test_selectType_standard(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectType('standard')
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertTrue($shield->isStandard());
            $this->assertSame('standard', $shield->getShieldType());
        }
    }

    public function test_selectTypes(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectTypes(['standard', 'racer'])
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertContains($shield->getShieldType(), ['standard', 'racer']);
        }
    }

    // =========================================================================
    // Mark Level Filter Tests
    // =========================================================================

    public function test_selectMk(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMk(1)
            ->getAll();
        
        $this->assertNotEmpty($shields);
        
        foreach ($shields as $shield) {
            $this->assertSame(1, $shield->getMk());
        }
    }

    public function test_selectMinMk(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMinMk(2)
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertGreaterThanOrEqual(2, $shield->getMk());
        }
    }

    // =========================================================================
    // Performance Filter Tests
    // =========================================================================

    public function test_selectMinCapacity(): void
    {
        $minCapacity = 10000.0;
        
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMinCapacity($minCapacity)
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertGreaterThanOrEqual($minCapacity, $shield->getCapacity());
        }
    }

    public function test_selectMaxCapacity(): void
    {
        $maxCapacity = 50000.0;
        
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMaxCapacity($maxCapacity)
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertLessThanOrEqual($maxCapacity, $shield->getCapacity());
        }
    }

    public function test_selectMinRechargeRate(): void
    {
        $minRate = 100.0;
        
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMinRechargeRate($minRate)
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertGreaterThanOrEqual($minRate, $shield->getRechargeRate());
        }
    }

    public function test_selectMaxRechargeDelay(): void
    {
        $maxDelay = 5.0;
        
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMaxRechargeDelay($maxDelay)
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertLessThanOrEqual($maxDelay, $shield->getRechargeDelay());
        }
    }

    // =========================================================================
    // Hull Filter Tests
    // =========================================================================

    public function test_selectMinHull(): void
    {
        $minHull = 1000.0;
        
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectMinHull($minHull)
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertGreaterThanOrEqual($minHull, $shield->getHullMax());
        }
    }

    public function test_selectWithHull(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectWithHull()
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertTrue($shield->hasHull());
        }
    }

    public function test_selectIntegrated(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectIntegrated()
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertTrue($shield->isHullIntegrated());
        }
    }

    public function test_selectNonIntegrated(): void
    {
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectNonIntegrated()
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertFalse($shield->isHullIntegrated());
        }
    }

    // =========================================================================
    // Complex Filter Tests
    // =========================================================================

    public function test_complexFilter(): void
    {
        $allShields = ShieldDefs::getInstance()->getAll();
        
        // Get a size and race that we know exist
        $testSize = $allShields[0]->getSize();
        $testRace = $allShields[0]->getMakerRace();
        
        $shields = ShieldDefs::getInstance()->findShields()
            ->selectSize($testSize)
            ->selectMakerRace($testRace)
            ->selectMinMk(1)
            ->getAll();
        
        foreach ($shields as $shield) {
            $this->assertSame($testSize, $shield->getSize());
            $this->assertSame($testRace, $shield->getMakerRace());
            $this->assertGreaterThanOrEqual(1, $shield->getMk());
        }
    }

    // =========================================================================
    // Chaining Tests
    // =========================================================================

    public function test_fluentInterface(): void
    {
        $finder = ShieldDefs::getInstance()->findShields();
        
        $result = $finder
            ->selectSize('l')
            ->selectType('standard')
            ->selectMinCapacity(1000.0);
        
        $this->assertSame($finder, $result, 'Methods should return self for fluent interface');
    }
}
