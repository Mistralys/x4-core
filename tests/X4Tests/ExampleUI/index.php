<?php
/**
 * @package X4Tests
 * @subpackage ExampleUI
 */

declare(strict_types=1);

use X4Tests\Helpers\TestApplication;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/config.php';

$app = new TestApplication();

$app->createUI(
    X4TESTS_INSTALL_URL.'/tests/X4Tests/ExampleUI',
    X4TESTS_INSTALL_URL.'/vendor'
)
    ->setUnitTestingURL(X4TESTS_INSTALL_URL)
    ->display();
