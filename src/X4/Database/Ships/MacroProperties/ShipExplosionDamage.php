<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

/**
 * The explosion damage of a ship macro (`<explosiondamage>`).
 * Each value may be absent in the game data, in which case it is NULL.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipExplosionDamage
{
    public const KEY_HULL = 'hull';
    public const KEY_SHIELD = 'shield';
    public const KEY_SHIELD_DISRUPTION = 'shieldDisruption';

    /**
     * @param float|null $hull The game's `value` attribute.
     * @param float|null $shield The game's `shield` attribute.
     * @param float|null $shieldDisruption The game's `shielddisruption` attribute.
     */
    public function __construct(
        public readonly ?float $hull = null,
        public readonly ?float $shield = null,
        public readonly ?float $shieldDisruption = null
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
            self::toFloat($data[self::KEY_HULL] ?? null),
            self::toFloat($data[self::KEY_SHIELD] ?? null),
            self::toFloat($data[self::KEY_SHIELD_DISRUPTION] ?? null)
        );
    }

    /**
     * @return array<string,float> Absent (NULL) values are omitted.
     */
    public function toArray() : array
    {
        return array_filter(
            array(
                self::KEY_HULL => $this->hull,
                self::KEY_SHIELD => $this->shield,
                self::KEY_SHIELD_DISRUPTION => $this->shieldDisruption
            ),
            static fn(?float $value) : bool => $value !== null
        );
    }

    public function isEmpty() : bool
    {
        return $this->hull === null && $this->shield === null && $this->shieldDisruption === null;
    }

    private static function toFloat(mixed $value) : ?float
    {
        return $value === null ? null : (float)$value;
    }
}
