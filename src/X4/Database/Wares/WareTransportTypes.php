<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

/**
 * The known ware transport types (the `transport` attribute of the wares).
 *
 * The four cargo types match the cargo types of ship storage
 * (`<cargo tags>`). The other six are not stored in cargo holds.
 *
 * @package X4 Database
 * @subpackage Wares
 */
final class WareTransportTypes
{
    public const TRANSPORT_CONTAINER = 'container';
    public const TRANSPORT_SOLID = 'solid';
    public const TRANSPORT_LIQUID = 'liquid';
    public const TRANSPORT_CONDENSATE = 'condensate';
    public const TRANSPORT_INVENTORY = 'inventory';
    public const TRANSPORT_EQUIPMENT = 'equipment';
    public const TRANSPORT_SHIP = 'ship';
    public const TRANSPORT_RESEARCH = 'research';
    public const TRANSPORT_SOFTWARE = 'software';
    public const TRANSPORT_PASSENGER = 'passenger';

    /**
     * @return string[]
     */
    public static function getIDs() : array
    {
        return array_merge(
            self::getCargoIDs(),
            array(
                self::TRANSPORT_INVENTORY,
                self::TRANSPORT_EQUIPMENT,
                self::TRANSPORT_SHIP,
                self::TRANSPORT_RESEARCH,
                self::TRANSPORT_SOFTWARE,
                self::TRANSPORT_PASSENGER,
            )
        );
    }

    /**
     * @return string[]
     */
    public static function getCargoIDs() : array
    {
        return array(
            self::TRANSPORT_CONTAINER,
            self::TRANSPORT_SOLID,
            self::TRANSPORT_LIQUID,
            self::TRANSPORT_CONDENSATE,
        );
    }

    public static function idExists(string $id) : bool
    {
        return in_array($id, self::getIDs(), true);
    }

    public static function isCargoTypeID(string $id) : bool
    {
        return in_array($id, self::getCargoIDs(), true);
    }
}
