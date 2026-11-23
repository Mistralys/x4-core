<?php
/**
 * @package X4 Database
 * @subpackage Build Tools
 */

declare(strict_types=1);

namespace Mistralys\X4\Database;

use AppUtils\ConvertHelper;
use AppUtils\FileHelper\FolderInfo;
use Mistral\X4\Database\Blueprints\BlueprintExtractor;
use Mistralys\X4\Database\Builder\LineEndingNormalizer;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DataSources\DataSourcesExtractor;
use Mistralys\X4\Database\Engines\EnginesExtractor;
use Mistralys\X4\Database\Factions\FactionsExtractor;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodsExtractor;
use Mistralys\X4\Database\Shields\ShieldsExtractor;
use Mistralys\X4\Database\Wares\WaresExtractor;
use Mistralys\X4\Database\Weapons\WeaponsExtractor;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\MacroIndex\MacroIndexExtractor;
use Mistralys\X4\Database\Modules\ModuleExtractor;
use Mistralys\X4\Database\Ships\ShipsExtractor;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\ExtractedData\X4GameInfo;
use function AppUtils\array_remove_values;
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

    /**
     * Number of nested {@see self::run()} calls currently active.
     * @var int
     */
    private static int $runDepth = 0;

    /**
     * Runs a build task. Once the outermost task has finished, the
     * line endings of the written data files are normalized, so that
     * the files match the repository versions byte for byte.
     *
     * Tasks may be nested (e.g. {@see self::build()} runs all the
     * extract commands), in which case the normalization happens once
     * at the very end.
     *
     * @param callable():void $task
     */
    private static function run(callable $task) : void
    {
        self::init();

        self::$runDepth++;

        try {
            $task();
        } finally {
            self::$runDepth--;
        }

        if(self::$runDepth === 0) {
            LineEndingNormalizer::normalizeDataFiles();
        }
    }

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
        self::run(function() : void {
            new BlueprintExtractor()->extract();
        });
    }

    public static function extractWares() : void
    {
        self::run(function() : void {
            new WaresExtractor(self::getDataFolders())->extract();
        });
    }

    public static function extractProductionMethods() : void
    {
        self::run(function() : void {
            new ProductionMethodsExtractor(self::getDataFolders())->extract();
        });
    }

    public static function extractFactions() : void
    {
        self::run(function() : void {
            new FactionsExtractor(self::getDataFolders())->extract();
        });
    }

    public static function extractTranslations() : void
    {
        self::run(function() : void {
            $extractor = new TranslationExtractor(self::getDataFolders());

            foreach(Languages::getInstance()->getIDs() as $langID) {
                $extractor->selectLanguage($langID);
            }

            $extractor->extract();
        });
    }

    public static function extractModules() : void
    {
        self::run(function() : void {
            new ModuleExtractor(self::getDataFolders())->extract();
        });
    }

    public static function extractEngines() : void
    {
        self::run(function() : void {
            new EnginesExtractor(self::getDataFolders())->extract();
        });
    }

    public static function extractShields() : void
    {
        self::run(function() : void {
            new ShieldsExtractor(self::getDataFolders())->extract();
        });
    }

    public static function extractWeapons() : void
    {
        self::run(function() : void {
            new WeaponsExtractor(self::getDataFolders())->extract();
        });
    }

    public static function build() : void
    {
        self::run(function() : void {
            // Prerequisites
            self::extractMacroIndex();
            self::extractTranslations();
            self::extractDataSources();
            self::extractFactions();
            self::extractProductionMethods();
            self::extractWares();

            // Based on the wares
            self::extractEngines();
            self::extractShields();
            self::extractWeapons();
            self::extractModules();
            self::extractBlueprints();
            self::extractShips();
        });
    }

    public static function extractShips() : void
    {
        self::run(function() : void {
            new ShipsExtractor()->extract();
        });
    }

    public static function extractMacroIndex() : void
    {
        self::run(function() : void {
            new MacroIndexExtractor(self::getDataFolders())->extract();
        });
    }

    public static function extractDataSources() : void
    {
        self::run(function() : void {
            new DataSourcesExtractor()->extract();
        });
    }

    public static ?X4GameInfo $gameInfo = null;

    public static function getGameInfo() : X4GameInfo
    {
        if(!isset(self::$gameInfo)) {
            self::init();
            self::$gameInfo = X4GameInfo::create()->setExtractedDataFolder(FolderInfo::factory(X4_EXTRACTED_CAT_FILES_FOLDER));
        }

        return self::$gameInfo;
    }

    public static function getDataFolders() : DataFolders
    {
        return self::getGameInfo()->getFolderCollection();
    }

    public static function resolveMethodName(string $label, ?VariantID $variantID=null): string
    {
        $parts = self::stripVariantElements($label, $variantID);

        return 'get' . implode('', array_map('ucfirst', $parts));
    }

    private static function stripVariantElements(string $label, ?VariantID $variantID=null) : array
    {
        $label = ConvertHelper::transliterate($label);
        $parts = ConvertHelper::explodeTrim('-', $label);

        if($variantID)
        {
            $parts = ConvertHelper::arrayRemoveValues($parts, VariantID::MARKS);

            $number = $variantID->getNumberString();
            if(!empty($number)) {
                $parts = ConvertHelper::arrayRemoveValues($parts, array($number));
            }

            $qualifier = $variantID->getQualifier();
            if(!empty($qualifier)) {
                $qualifier = strtoupper(str_replace('-', '_', $qualifier));
                $parts = ConvertHelper::arrayRemoveValues($parts, array($qualifier));
            }
        }

        return $parts;
    }

    /**
     * @param string $label
     * @param string|NULL $prefix Optional prefix to add to the constant name.
     * @return string
     */
    public static function resolveConstantName(string $label, ?string $prefix=null, ?VariantID $variantID=null): string
    {
        $parts = self::stripVariantElements($label, $variantID);
        $parts = ConvertHelper::arrayRemoveValues($parts, array('of', 'the'));

        if(!empty($prefix)) {
            $prefix = rtrim(strtoupper($prefix), '_').'_';
        }

        return $prefix.strtoupper(implode('_', $parts));
    }
}
