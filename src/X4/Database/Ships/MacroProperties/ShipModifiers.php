<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

/**
 * The equipment modifiers of a ship macro (`<modifiers>`). All values are
 * multipliers; a value that is absent in the game data is NULL.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipModifiers
{
    public const KEY_WEAPON_HEAT = 'weaponHeat';
    public const KEY_SHIELD_CAPACITY = 'shieldCapacity';
    public const KEY_SHIELD_RECHARGE_RATE = 'shieldRechargeRate';
    public const KEY_SHIELD_RECHARGE_DELAY = 'shieldRechargeDelay';

    public function __construct(
        public readonly ?float $weaponHeat = null,
        public readonly ?float $shieldCapacity = null,
        public readonly ?float $shieldRechargeRate = null,
        public readonly ?float $shieldRechargeDelay = null
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
            self::toFloat($data[self::KEY_WEAPON_HEAT] ?? null),
            self::toFloat($data[self::KEY_SHIELD_CAPACITY] ?? null),
            self::toFloat($data[self::KEY_SHIELD_RECHARGE_RATE] ?? null),
            self::toFloat($data[self::KEY_SHIELD_RECHARGE_DELAY] ?? null)
        );
    }

    /**
     * @return array<string,float> Absent (NULL) values are omitted.
     */
    public function toArray() : array
    {
        return array_filter(
            array(
                self::KEY_WEAPON_HEAT => $this->weaponHeat,
                self::KEY_SHIELD_CAPACITY => $this->shieldCapacity,
                self::KEY_SHIELD_RECHARGE_RATE => $this->shieldRechargeRate,
                self::KEY_SHIELD_RECHARGE_DELAY => $this->shieldRechargeDelay
            ),
            static fn(?float $value) : bool => $value !== null
        );
    }

    public function isEmpty() : bool
    {
        return $this->weaponHeat === null
            && $this->shieldCapacity === null
            && $this->shieldRechargeRate === null
            && $this->shieldRechargeDelay === null;
    }

    private static function toFloat(mixed $value) : ?float
    {
        return $value === null ? null : (float)$value;
    }
}
