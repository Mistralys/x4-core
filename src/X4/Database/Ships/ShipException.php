<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Ships\ShipException
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use Mistralys\X4\X4Exception;

/**
 * @package X4Core
 * @subpackage Exceptions
 */
class ShipException extends X4Exception
{
    /**
     * The ship record has no macro name, so its macro file cannot be resolved.
     */
    public const ERROR_NO_MACRO_ID = 144001;

    /**
     * The ship has no storage macro, so no cargo macro file can be resolved.
     */
    public const ERROR_NO_CARGO_MACRO_ID = 144002;

    /**
     * A ship purpose ID was used that is not known, see {@see ShipPurposes}.
     */
    public const ERROR_UNKNOWN_PURPOSE = 144003;
}
