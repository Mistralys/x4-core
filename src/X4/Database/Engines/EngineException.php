<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Engines\EngineException
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Engines;

use Mistralys\X4\X4Exception;

/**
 * @package X4Core
 * @subpackage Exceptions
 */
class EngineException extends X4Exception
{
    public const ERROR_ENGINE_NOT_FOUND = 142001;
    public const ERROR_INVALID_ENGINE_SIZE = 142002;
    public const ERROR_INVALID_ENGINE_DATA = 142003;
}
