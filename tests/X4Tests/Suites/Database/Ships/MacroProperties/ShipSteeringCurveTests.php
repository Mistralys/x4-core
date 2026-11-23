<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipSteeringCurve
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Ships\MacroProperties\ShipSteeringCurve;
use Mistralys\X4\Database\Ships\MacroProperties\ShipSteeringCurvePoint;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ShipSteeringCurveTests extends TestCase
{
    private const POINTS = array(
        array('position' => 0.0, 'value' => 0.5),
        array('position' => 0.25, 'value' => 1.0),
        array('position' => 1.0, 'value' => 0.75),
    );

    public function test_roundTripPreservesPointOrder() : void
    {
        $curve = ShipSteeringCurve::fromArray(self::POINTS);

        $this->assertFalse($curve->isEmpty());
        $this->assertCount(3, $curve->getPoints());
        $this->assertSame(self::POINTS, $curve->toArray());
        $this->assertSame(0.25, $curve->getPoints()[1]->position);
        $this->assertSame(0.75, $curve->getPoints()[2]->value);
    }

    public function test_emptyCurve() : void
    {
        $curve = ShipSteeringCurve::fromArray(array());

        $this->assertTrue($curve->isEmpty());
        $this->assertSame(array(), $curve->getPoints());
        $this->assertSame(array(), $curve->toArray());
        $this->assertTrue((new ShipSteeringCurve())->isEmpty());
    }

    public function test_constructorAcceptsPointObjects() : void
    {
        $curve = new ShipSteeringCurve(array(new ShipSteeringCurvePoint(0.5, 2.0)));

        $this->assertSame(array(array('position' => 0.5, 'value' => 2.0)), $curve->toArray());
    }

    public function test_noInterpolationApi() : void
    {
        $methods = array_map(
            static fn(\ReflectionMethod $method) : string => strtolower($method->getName()),
            (new ReflectionClass(ShipSteeringCurve::class))->getMethods()
        );

        $this->assertNotContains('getvalueat', $methods);
        $this->assertNotContains('interpolate', $methods);
    }

    public function test_classIsFinalAndReadonly() : void
    {
        $this->assertTrue((new ReflectionClass(ShipSteeringCurve::class))->isFinal());
        $this->assertTrue((new ReflectionClass(ShipSteeringCurvePoint::class))->isFinal());
        $this->assertTrue((new ReflectionClass(ShipSteeringCurve::class))->getProperty('points')->isReadOnly());
        $this->assertTrue((new ReflectionClass(ShipSteeringCurvePoint::class))->getProperty('position')->isReadOnly());
    }

    public function test_docStatesMeaningIsUnconfirmed() : void
    {
        $doc = (string)(new ReflectionClass(ShipSteeringCurve::class))->getDocComment();

        $this->assertStringContainsString('unconfirmed', $doc);
    }
}
