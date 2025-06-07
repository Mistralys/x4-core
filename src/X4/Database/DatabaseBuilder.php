<?php

declare(strict_types=1);

namespace Mistralys\X4\Database;

use AppUtils\FileHelper\FolderInfo;
use Mistralys\X4\Database\Modules\ModuleExtractor;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use Mistralys\X4\ExtractedData\DataFolders;
use const Mistralys\X4\X4_EXTRACTED_CAT_FILES_FOLDER;

class DatabaseBuilder
{
    private static bool $initialized = false;

    private static function init() : void
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;

        $configFile = __DIR__.'/../../../dev-config.php';
        $autoloader = __DIR__.'/../../../vendor/autoload.php';

        if(!file_exists($configFile)) {
            die('Error: Configuration file does not exist. See the README.md for instructions.');
        }

        if(!file_exists($autoloader)) {
            die('Error: Autoloader not found. Run `composer install` first.');
        }

        require_once $configFile;
        require_once $autoloader;
    }

    public static function extractTranslations() : void
    {
        self::init();

        $extractor = new TranslationExtractor(DataFolders::create(FolderInfo::factory(X4_EXTRACTED_CAT_FILES_FOLDER)));
        $extractor->selectLanguage(TranslationExtractor::LANGUAGE_ENGLISH);
        $extractor->extract();
    }

    public static function extractModules() : void
    {
        self::init();

        (new ModuleExtractor(self::getDataFolders()))->extract();
    }

    public static function build() : void
    {
        self::extractTranslations();
        self::extractModules();
    }

    private static ?DataFolders $dataFolders = null;

    public static function getDataFolders() : DataFolders
    {
        if(!isset(self::$dataFolders)) {
            self::init();
            self::$dataFolders = DataFolders::create(FolderInfo::factory(X4_EXTRACTED_CAT_FILES_FOLDER));
        }

        return self::$dataFolders;
    }
}
