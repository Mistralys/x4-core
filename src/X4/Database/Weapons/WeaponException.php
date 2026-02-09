<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Weapons\WeaponException
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Weapons;

use Mistralys\X4\X4Exception;

/**
 * @package X4Core
 * @subpackage Exceptions
 */
class WeaponException extends X4Exception
{
    public const ERROR_WEAPON_NOT_FOUND = 143001;
    public const ERROR_INVALID_WEAPON_SIZE = 143002;
    public const ERROR_INVALID_WEAPON_DATA = 143003;
    public const ERROR_INVALID_WEAPON_SYSTEM = 143004;
    public const ERROR_UNKNOWN_WEAPON_SYSTEM = 143005;
}
