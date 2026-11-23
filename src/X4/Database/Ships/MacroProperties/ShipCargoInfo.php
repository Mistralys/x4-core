<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

/**
 * The cargo storage of a ship, as resolved from its storage macro.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipCargoInfo
{
    public const DEFAULT_TYPE = 'none';

    /**
     * @param int $capacity Cargo capacity in m³.
     * @param string $type Cargo type tags, e.g. `container`, or `none`.
     * @param string|null $macroID Canonical name of the storage macro ({@see \Mistralys\X4\Database\MacroIndex\MacroFileDef::getMacroName()}), or NULL if the ship has none.
     */
    public function __construct(
        public readonly int $capacity = 0,
        public readonly string $type = self::DEFAULT_TYPE,
        public readonly ?string $macroID = null
    )
    {
    }
}
