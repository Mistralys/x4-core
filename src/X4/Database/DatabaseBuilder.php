<?php
/**
 * @package X4 Database
 * @subpackage Build Tools
 */

declare(strict_types=1);

namespace Mistralys\X4\Database;

use AppUtils\FileHelper\FolderInfo;
use Mistral\X4\Database\Blueprints\BlueprintExtractor;
use Mistralys\X4\Database\Factions\FactionsExtractor;
use Mistral\X4\Database\Wares\WaresExtractor;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\MacroIndex\MacroIndexExtractor;
use Mistralys\X4\Database\Modules\ModuleExtractor;
use Mistralys\X4\Database\Ships\ShipsExtractor;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use Mistralys\X4\ExtractedData\DataFolders;
use const Mistralys\X4\X4_EXTRACTED_CAT_FILES_FOLDER;

/**
 * Command endpoint for the Composer script commands
 * used to build the database.
 *
 * @package X4 Database
 * @subpackage Build Tools
 */
class DatabaseBuilder
{
    private static bool $initialized = false;

    private static function init() : void
    {
        if (self::$initialized) {
            return;
        }

        self::$initialized = true;

        $autoloader = __DIR__.'/../../../vendor/autoload.php';

        if(!file_exists($autoloader)) {
            die('Error: Autoloader not found. Run `composer install` first.');
        }

        require_once $autoloader;
    }

    public static function extractBlueprints() : void
    {
        self::init();

        (new BlueprintExtractor(self::getDataFolders()))->extract();
    }

    public static function extractWares() : void
    {
        self::init();

        (new WaresExtractor(self::getDataFolders()))->extract();
    }

    public static function extractFactions() : void
    {
        self::init();

        (new FactionsExtractor(self::getDataFolders()))->extract();
    }

    public static function extractTranslations() : void
    {
        self::init();

        $extractor = new TranslationExtractor(DataFolders::create(FolderInfo::factory(X4_EXTRACTED_CAT_FILES_FOLDER)));

        foreach(Languages::getInstance()->getIDs() as $langID) {
            $extractor->selectLanguage($langID);
        }

        $extractor->extract();
    }

    public static function extractModules() : void
    {
        self::init();

        (new ModuleExtractor(self::getDataFolders()))->extract();
    }

    public static function build() : void
    {
        // Prerequisites
        self::extractMacroIndex();
        self::extractTranslations();
        self::writeDataSources();
        self::extractFactions();
        self::extractWares();

        // Based on the wares
        self::extractModules();
        self::extractBlueprints();
        self::extractShips();
    }

    public static function extractShips() : void
    {
        self::init();

        (new ShipsExtractor())->extract();
    }

    public static function extractMacroIndex() : void
    {
        self::init();

        (new MacroIndexExtractor(self::getDataFolders()))->extract();
    }

    public static function writeDataSources() : void
    {
        self::init();

        $data = array();
        foreach(self::getDataFolders()->getAll() as $dataFolder) {
            $data[] = DataSourceDef::toArray($dataFolder);
        }

        ksort($data);

        DataSourceDefs::getInstance()
            ->getDataFile()
            ->putData($data);
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
