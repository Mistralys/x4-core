<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

/**
 * The steering curve of a ship macro (`<steeringcurve><point>`), as a list
 * of points in document order.
 *
 * This is a plain point container. The in-game meaning of the `position`
 * and `value` of the points is unconfirmed, so the class deliberately
 * offers no interpolation: a wrong guess would be a published API claim.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipSteeringCurve
{
    /**
     * @var ShipSteeringCurvePoint[]
     */
    public readonly array $points;

    /**
     * @param ShipSteeringCurvePoint[] $points
     */
    public function __construct(array $points = array())
    {
        $this->points = array_values($points);
    }

    /**
     * @param array<int,array<string,mixed>> $points
     * @return self
     */
    public static function fromArray(array $points) : self
    {
        $result = array();

        foreach($points as $point) {
            $result[] = ShipSteeringCurvePoint::fromArray($point);
        }

        return new self($result);
    }

    /**
     * @return array<int,array{position:float,value:float}>
     */
    public function toArray() : array
    {
        $result = array();

        foreach($this->points as $point) {
            $result[] = $point->toArray();
        }

        return $result;
    }

    /**
     * @return ShipSteeringCurvePoint[] In document order.
     */
    public function getPoints() : array
    {
        return $this->points;
    }

    public function isEmpty() : bool
    {
        return empty($this->points);
    }
}
