<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;

/**
 * An entry of a ship's default loadout: what gets installed, without
 * the information where it is mounted.
 *
 * The matching ware is not stored, but resolved at runtime with
 * {@see self::getWare()}, so that it cannot drift from the ware data.
 *
 * @package X4 Database
 * @subpackage Ships
 *
 * @see ShipLoadout
 */
final class ShipLoadoutItem
{
    public const TYPE_ENGINE = 'engine';
    public const TYPE_SHIELD = 'shield';
    public const TYPE_WEAPON = 'weapon';
    public const TYPE_TURRET = 'turret';
    public const TYPE_THRUSTER = 'thruster';
    public const TYPE_SOFTWARE = 'software';
    public const TYPE_AMMUNITION = 'ammunition';

    public const KEY_TYPE = 'type';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_COUNT = 'count';
    public const KEY_OPTIONAL = 'optional';

    /**
     * @param string $type One of the `TYPE_*` constants.
     * @param string $macroID The equipment macro ID. For software entries, the software ware ID.
     * @param int $count How many of the item get installed.
     * @param bool $optional Whether the item is an optional part of the loadout.
     */
    public function __construct(
        public readonly string $type,
        public readonly string $macroID,
        public readonly int $count = 1,
        public readonly bool $optional = false
    )
    {
    }

    /**
     * @return string[]
     */
    public static function getTypes() : array
    {
        return array(
            self::TYPE_ENGINE,
            self::TYPE_SHIELD,
            self::TYPE_WEAPON,
            self::TYPE_TURRET,
            self::TYPE_THRUSTER,
            self::TYPE_SOFTWARE,
            self::TYPE_AMMUNITION,
        );
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data) : self
    {
        return new self(
            (string)($data[self::KEY_TYPE] ?? ''),
            (string)($data[self::KEY_MACRO_ID] ?? ''),
            (int)($data[self::KEY_COUNT] ?? 1),
            (bool)($data[self::KEY_OPTIONAL] ?? false)
        );
    }

    /**
     * @return array{type:string,macroID:string,count:int,optional:bool}
     */
    public function toArray() : array
    {
        return array(
            self::KEY_TYPE => $this->type,
            self::KEY_MACRO_ID => $this->macroID,
            self::KEY_COUNT => $this->count,
            self::KEY_OPTIONAL => $this->optional
        );
    }

    /**
     * Resolves the ware of the item at runtime, by macro ID
     * (or ware ID, for software entries).
     *
     * Note: All software wares share the macro `software_dummy_macro`, so a
     * software item is only resolved correctly when its macro ID holds the
     * software ware ID, as documented for the constructor.
     *
     * @return WareDef|null NULL if no ware matches.
     */
    public function getWare() : ?WareDef
    {
        return WareDefs::getInstance()->findByMacro($this->macroID);
    }
}
