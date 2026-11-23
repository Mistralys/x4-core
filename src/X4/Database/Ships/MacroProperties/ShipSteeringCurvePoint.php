<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

/**
 * A single point of a {@see ShipSteeringCurve}.
 *
 * The names `position` and `value` are the game's own. Their exact
 * in-game meaning is not documented in the game data, so they are
 * kept as they are instead of guessing.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipSteeringCurvePoint
{
    public const KEY_POSITION = 'position';
    public const KEY_VALUE = 'value';

    public function __construct(
        public readonly float $position,
        public readonly float $value
    )
    {
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data) : self
    {
        return new self(
            (float)($data[self::KEY_POSITION] ?? 0),
            (float)($data[self::KEY_VALUE] ?? 0)
        );
    }

    /**
     * @return array{position:float,value:float}
     */
    public function toArray() : array
    {
        return array(
            self::KEY_POSITION => $this->position,
            self::KEY_VALUE => $this->value
        );
    }
}
