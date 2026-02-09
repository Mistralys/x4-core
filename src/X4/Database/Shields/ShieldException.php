<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Shields\ShieldException
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Shields;

use Mistralys\X4\X4Exception;

/**
 * @package X4Core
 * @subpackage Exceptions
 */
class ShieldException extends X4Exception
{
    public const ERROR_SHIELD_NOT_FOUND = 143001;
    public const ERROR_INVALID_SHIELD_SIZE = 143002;
    public const ERROR_INVALID_SHIELD_DATA = 143003;
    public const ERROR_INVALID_SHIELD_TYPE = 143004;
}
