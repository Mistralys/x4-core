<?php

declare(strict_types=1);

namespace Mistralys\X4;

$configFile = __DIR__.'/../dev-config.php';
$autoloader = __DIR__.'/../vendor/autoload.php';

if(!file_exists($configFile)) {
    die('Error: Configuration file does not exist.');
}

if(!file_exists($autoloader)) {
    die('Error: Autoloader not found. Run `composer install` first.');
}

require_once $configFile;
require_once $autoloader;
