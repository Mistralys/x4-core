<?php
/**
 * @package X4Tests
 * @subpackage Database\Shields
 * @see \Mistralys\X4\Database\Shields\ShieldDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Shields;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Shields\ShieldDef;
use Mistralys\X4\Database\Shields\ShieldDefs;
use Mistralys\X4\Database\Shields\ShieldFinder;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the ShieldDefs collection class
 *
 * @package X4Tests
 * @subpackage Database\Shields
 */
final class ShieldCollectionTests extends X4TestCase
{
    // =========================================================================
    // Singleton Tests
    // =========================================================================

    public function test_singleton(): void
    {
        $instance1 = ShieldDefs::getInstance();
        $instance2 = ShieldDefs::getInstance();
        
        $this->assertSame($instance1, $instance2);
    }

    // =========================================================================
    // Collection Access Tests
    // =========================================================================

    public function test_getAll(): void
    {
        $shields = ShieldDefs::getInstance()->getAll();
        
        $this->assertIsArray($shields);
        $this->assertNotEmpty($shields);
        $this->assertGreaterThan(100, count($shields), 'Should have at least 100 shields');
        
        foreach ($shields as $shield) {
            $this->assertInstanceOf(ShieldDef::class, $shield);
        }
    }

    public function test_getByID_validID(): void
    {
        $shields = ShieldDefs::getInstance();
        
        // Get the first shield from the collection to use as a test case
        $allShields = $shields->getAll();
        $this->assertNotEmpty($allShields, 'Shield collection should not be empty');
        
        $firstShield = $allShields[0];
        $shield = $shields->getByID($firstShield->getID());
        
        $this->assertInstanceOf(ShieldDef::class, $shield);
        $this->assertSame($firstShield->getID(), $shield->getID());
    }

    public function test_getByID_invalidID(): void
    {
        $this->expectException(RecordNotExistsException::class);
        
        ShieldDefs::getInstance()->getByID('invalid_shield_id');
    }

    public function test_getDefault(): void
    {
        $shield = ShieldDefs::getInstance()->getDefault();
        
        $this->assertInstanceOf(ShieldDef::class, $shield);
    }

    public function test_getDefaultID(): void
    {
        $id = ShieldDefs::getInstance()->getDefaultID();
        
        $this->assertIsString($id);
        $this->assertNotEmpty($id);
        
        // Should be able to get the default shield by its ID
        $shield = ShieldDefs::getInstance()->getByID($id);
        $this->assertInstanceOf(ShieldDef::class, $shield);
    }

    // =========================================================================
    // Find Methods Tests
    // =========================================================================

    public function test_find_byWareID(): void
    {
        $allShields = ShieldDefs::getInstance()->getAll();
        $this->assertNotEmpty($allShields);
        
        $firstShield = $allShields[0];
        $shield = ShieldDefs::getInstance()->find($firstShield->getID());
        
        $this->assertInstanceOf(ShieldDef::class, $shield);
        $this->assertSame($firstShield->getID(), $shield->getID());
    }

    public function test_findByMacro(): void
    {
        $allShields = ShieldDefs::getInstance()->getAll();
        $this->assertNotEmpty($allShields);
        
        $firstShield = $allShields[0];
        $macroID = $firstShield->getMacroID();
        
        $shield = ShieldDefs::getInstance()->findByMacro($macroID);
        
        $this->assertInstanceOf(ShieldDef::class, $shield);
        $this->assertSame($macroID, $shield->getMacroID());
    }

    public function test_getByType_standard(): void
    {
        $standard = ShieldDefs::getInstance()->getByType('standard');
        
        $this->assertIsArray($standard);
        $this->assertNotEmpty($standard, 'Should have at least one standard shield');
        
        foreach ($standard as $shield) {
            $this->assertTrue($shield->isStandard());
            $this->assertSame('standard', $shield->getShieldType());
        }
    }

    // =========================================================================
    // Finder Tests
    // =========================================================================

    public function test_findShields(): void
    {
        $finder = ShieldDefs::getInstance()->findShields();
        
        $this->assertInstanceOf(ShieldFinder::class, $finder);
    }

    public function test_idExists_validID(): void
    {
        $allShields = ShieldDefs::getInstance()->getAll();
        $this->assertNotEmpty($allShields);
        
        $firstShield = $allShields[0];
        
        $this->assertTrue(ShieldDefs::getInstance()->idExists($firstShield->getID()));
    }

    public function test_idExists_invalidID(): void
    {
        $this->assertFalse(ShieldDefs::getInstance()->idExists('invalid_shield_id'));
    }
}
