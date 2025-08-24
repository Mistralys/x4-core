<?php
/**
 * @package X4Tests
 * @subpackage ExampleUI
 */

declare(strict_types=1);

use X4Tests\Helpers\X4TestCase;

if(!file_exists(__DIR__.'/../../../vendor/autoload.php')) {
    die('Please run "composer install" to set up the test environment.');
}

if(!file_exists(__DIR__.'/../../../dev-config.php')) {
    die('Please create a "dev-config.php" file based on "dev-config.dist.php".');
}

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../dev-config.php';

X4TestCase::createTestApp()->getUI()->display();
