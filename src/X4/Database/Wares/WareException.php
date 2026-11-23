<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Wares\WareException
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use Mistralys\X4\X4Exception;

/**
 * @package X4Core
 * @subpackage Exceptions
 */
class WareException extends X4Exception
{
    /**
     * A ware transport type ID was used that is not known,
     * see {@see WareTransportTypes}.
     */
    public const ERROR_UNKNOWN_TRANSPORT_TYPE = 145001;
}
