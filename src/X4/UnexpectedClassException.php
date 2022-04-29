<?php
/**
 * @package X4Core
 * @subpackage Exceptions
 * @see \Mistralys\X4\UnexpectedClassException
 */

declare(strict_types=1);

namespace Mistralys\X4;

use function AppUtils\parseVariable;

/**
 * Specialized exception for cases where a class instance
 * does not match the expected class.
 *
 * @package X4Core
 * @subpackage Exceptions
 */
class UnexpectedClassException extends X4Exception
{
    public const ERROR_CODE = 106901;

    public function __construct(string $expectedClass, $actual)
    {
        parent::__construct(
            'Unexpected class type given.',
            sprintf(
                'Expected an object of class [%s], given: [%s].',
                $expectedClass,
                parseVariable($actual)->enableType()->toString()
            ),
            self::ERROR_CODE
        );
    }
}
