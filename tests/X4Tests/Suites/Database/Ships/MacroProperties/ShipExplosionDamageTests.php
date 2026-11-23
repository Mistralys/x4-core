<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipExplosionDamage
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Ships\MacroProperties\ShipExplosionDamage;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ShipExplosionDamageTests extends TestCase
{
    public function test_fullRoundTrip() : void
    {
        $data = array('hull' => 100.0, 'shield' => 250.0, 'shieldDisruption' => 5.5);
        $damage = ShipExplosionDamage::fromArray($data);

        $this->assertSame(100.0, $damage->hull);
        $this->assertSame(250.0, $damage->shield);
        $this->assertSame(5.5, $damage->shieldDisruption);
        $this->assertFalse($damage->isEmpty());
        $this->assertSame($data, $damage->toArray());
    }

    public function test_absentValuesStayNull() : void
    {
        $damage = ShipExplosionDamage::fromArray(array('shield' => 40));

        $this->assertNull($damage->hull);
        $this->assertSame(40.0, $damage->shield);
        $this->assertNull($damage->shieldDisruption);
        $this->assertSame(array('shield' => 40.0), $damage->toArray());

        $this->assertEquals($damage, ShipExplosionDamage::fromArray($damage->toArray()));
    }

    public function test_zeroIsNotAbsent() : void
    {
        $damage = ShipExplosionDamage::fromArray(array('hull' => 0));

        $this->assertSame(0.0, $damage->hull);
        $this->assertFalse($damage->isEmpty());
    }

    public function test_empty() : void
    {
        $this->assertTrue((new ShipExplosionDamage())->isEmpty());
        $this->assertSame(array(), (new ShipExplosionDamage())->toArray());
    }

    public function test_finalAndReadonly() : void
    {
        $class = new ReflectionClass(ShipExplosionDamage::class);

        $this->assertTrue($class->isFinal());
        $this->assertTrue($class->getProperty('hull')->isReadOnly());
    }
}
