<?php
/**
 * @package X4Tests
 * @subpackage ExampleUI
 */

declare(strict_types=1);

use X4Tests\Helpers\X4TestCase;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/config.php';

X4TestCase::createTestApp()->getUI()->display();
