<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

/**
 * The known primary ship purposes (`<purpose primary>` of the ship macros).
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipPurposes
{
    public const PURPOSE_FIGHT = 'fight';
    public const PURPOSE_TRADE = 'trade';
    public const PURPOSE_MINE = 'mine';
    public const PURPOSE_BUILD = 'build';
    public const PURPOSE_AUXILIARY = 'auxiliary';
    public const PURPOSE_RACING = 'racing';
    public const PURPOSE_SALVAGE = 'salvage';
    public const PURPOSE_DISMANTLING = 'dismantling';

    /**
     * @return string[]
     */
    public static function getIDs() : array
    {
        return array(
            self::PURPOSE_FIGHT,
            self::PURPOSE_TRADE,
            self::PURPOSE_MINE,
            self::PURPOSE_BUILD,
            self::PURPOSE_AUXILIARY,
            self::PURPOSE_RACING,
            self::PURPOSE_SALVAGE,
            self::PURPOSE_DISMANTLING,
        );
    }

    public static function idExists(string $id) : bool
    {
        return in_array($id, self::getIDs(), true);
    }
}
