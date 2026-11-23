<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipModifiers
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Ships\MacroProperties\ShipModifiers;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ShipModifiersTests extends TestCase
{
    public function test_fullRoundTrip() : void
    {
        $data = array(
            'weaponHeat' => 0.9,
            'shieldCapacity' => 1.1,
            'shieldRechargeRate' => 1.25,
            'shieldRechargeDelay' => 0.8
        );

        $modifiers = ShipModifiers::fromArray($data);

        $this->assertSame(0.9, $modifiers->weaponHeat);
        $this->assertSame(1.1, $modifiers->shieldCapacity);
        $this->assertSame(1.25, $modifiers->shieldRechargeRate);
        $this->assertSame(0.8, $modifiers->shieldRechargeDelay);
        $this->assertFalse($modifiers->isEmpty());
        $this->assertSame($data, $modifiers->toArray());
    }

    public function test_partialValues() : void
    {
        $modifiers = ShipModifiers::fromArray(array('shieldCapacity' => 2));

        $this->assertNull($modifiers->weaponHeat);
        $this->assertSame(2.0, $modifiers->shieldCapacity);
        $this->assertFalse($modifiers->isEmpty());
        $this->assertEquals($modifiers, ShipModifiers::fromArray($modifiers->toArray()));
    }

    public function test_emptyWhenAllNull() : void
    {
        $this->assertTrue((new ShipModifiers())->isEmpty());
        $this->assertTrue(ShipModifiers::fromArray(array())->isEmpty());
        $this->assertSame(array(), (new ShipModifiers())->toArray());
    }

    public function test_finalAndReadonly() : void
    {
        $class = new ReflectionClass(ShipModifiers::class);

        $this->assertTrue($class->isFinal());
        $this->assertTrue($class->getProperty('weaponHeat')->isReadOnly());
    }
}
