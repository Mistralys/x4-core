<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipHangar
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Ships\MacroProperties\ShipHangar;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ShipHangarTests extends TestCase
{
    public function test_capacities() : void
    {
        $hangar = ShipHangar::fromArray(array('s' => 12, 'm' => 4));

        $this->assertFalse($hangar->isEmpty());
        $this->assertSame(12, $hangar->getCapacity('s'));
        $this->assertSame(4, $hangar->getCapacity('m'));
        $this->assertSame(0, $hangar->getCapacity('xl'), 'Missing size');
        $this->assertSame(16, $hangar->getTotalCapacity());
        $this->assertSame(array('s', 'm'), $hangar->getSizes());
    }

    public function test_roundTrip() : void
    {
        $map = array('xs' => 2, 'l' => 1);

        $this->assertSame($map, ShipHangar::fromArray($map)->toArray());
    }

    public function test_empty() : void
    {
        $hangar = new ShipHangar();

        $this->assertTrue($hangar->isEmpty());
        $this->assertSame(0, $hangar->getTotalCapacity());
        $this->assertSame(array(), $hangar->getSizes());
        $this->assertSame(array(), $hangar->toArray());
    }

    public function test_totalCapacityDocNotesDoubleCounting() : void
    {
        $doc = (string)(new ReflectionClass(ShipHangar::class))
            ->getMethod('getTotalCapacity')
            ->getDocComment();

        $this->assertStringContainsString('once for each', $doc);
    }

    public function test_finalAndReadonly() : void
    {
        $class = new ReflectionClass(ShipHangar::class);

        $this->assertTrue($class->isFinal());
        $this->assertTrue($class->getProperty('capacities')->isReadOnly());
    }
}
