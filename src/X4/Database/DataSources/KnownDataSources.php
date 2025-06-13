<?php
/**
 * @package X4 Database
 * @subpackage Data Sources 
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\DataSources;

/**
 * This class contains constants and method for all known game
 * data sources.
 * 
 * This utility is meant to be used in tandem with the main
 * collection class {@see DataSourceDefs}. When you want to
 * access items manually in your code, the getter methods are a
 * great help to find what you need.
 * 
 * ## Usage
 * 
 * Use the method {@see self::getInstance()} to get the
 * instance of this class, then call the getter methods to
 * retrieve the items you need.
 * 
 * ----
 * 
 * > NOTE: This is an auto-generated class.
 * 
 * @package X4 Database
 * @subpackage Data Sources 
 */
class KnownDataSources
{
    public const DATA_SOURCE_BASE_GAME = 'vanilla';
    public const DATA_SOURCE_CRADLE_HUMANITY = 'ego_dlc_terran';
    public const DATA_SOURCE_HYPERION_PACK = 'ego_dlc_mini_01';
    public const DATA_SOURCE_KINGDOM_END = 'ego_dlc_boron';
    public const DATA_SOURCE_SPLIT_VENDETTA = 'ego_dlc_split';
    public const DATA_SOURCE_TIDES_AVARICE = 'ego_dlc_pirate';
    public const DATA_SOURCE_TIMELINES = 'ego_dlc_timelines';
    
    public const DATA_SOURCES = array(
        self::DATA_SOURCE_BASE_GAME,
        self::DATA_SOURCE_CRADLE_HUMANITY,
        self::DATA_SOURCE_HYPERION_PACK,
        self::DATA_SOURCE_KINGDOM_END,
        self::DATA_SOURCE_SPLIT_VENDETTA,
        self::DATA_SOURCE_TIDES_AVARICE,
        self::DATA_SOURCE_TIMELINES
    );

    private DataSourceDefs $defs;

    private function __construct()
    {
        $this->defs = DataSourceDefs::getInstance();
    }

    private static ?KnownDataSources $instance = null;

    public static function getInstance() : KnownDataSources
    {
        if (!isset(self::$instance)) {
            self::$instance = new KnownDataSources();
        }

        return self::$instance;
    }

    public function getBaseGame() : DataSourceDef
    {
        return $this->defs->getByID(self::DATA_SOURCE_BASE_GAME);
    }

    public function getCradleOfHumanity() : DataSourceDef
    {
        return $this->defs->getByID(self::DATA_SOURCE_CRADLE_HUMANITY);
    }

    public function getHyperionPack() : DataSourceDef
    {
        return $this->defs->getByID(self::DATA_SOURCE_HYPERION_PACK);
    }

    public function getKingdomEnd() : DataSourceDef
    {
        return $this->defs->getByID(self::DATA_SOURCE_KINGDOM_END);
    }

    public function getSplitVendetta() : DataSourceDef
    {
        return $this->defs->getByID(self::DATA_SOURCE_SPLIT_VENDETTA);
    }

    public function getTidesOfAvarice() : DataSourceDef
    {
        return $this->defs->getByID(self::DATA_SOURCE_TIDES_AVARICE);
    }

    public function getTimelines() : DataSourceDef
    {
        return $this->defs->getByID(self::DATA_SOURCE_TIMELINES);
    }
}
