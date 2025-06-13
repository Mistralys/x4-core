<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\Finder;

use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Ships\ShipClass;
use Mistralys\X4\Database\Ships\ShipClasses;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\Ships\ShipSize;
use Mistralys\X4\Database\Ships\ShipSizes;

class ShipFinder
{
    /**
     * @var string[]
     */
    private array $builderFactions = array();

    /**
     * @var string[]
     */
    private array $classes = array();

    /**
     * @var string[]
     */
    private array $dataSources = array();

    /**
     * @var string[]
     */
    private array $sizes = array();

    public function __construct()
    {
    }

    /**
     * Adds a faction to the list of factions that will be used to filter ships.
     *
     * @param string|FactionDef $faction Faction ID or instance.
     * @return $this
     */
    public function selectBuilderFaction(string|FactionDef $faction) : self
    {
        if(!$faction instanceof FactionDef) {
            $faction = FactionDefs::getInstance()->getByID($faction);
        }

        $factionID = $faction->getID();
        if(!in_array($factionID, $this->builderFactions, true)) {
            $this->builderFactions[] = $factionID;
        }

        return $this;
    }

    /**
     * Adds a ship class to the list of classes that will be used to filter ships.
     *
     * @param string|ShipClass $class Ship class ID or instance.
     * @return $this
     */
    public function selectClass(string|ShipClass $class) : self
    {
        if(!$class instanceof ShipClass) {
            $class = ShipClasses::getInstance()->getByID($class);
        }

        $classID = $class->getID();
        if(!in_array($classID, $this->classes, true)) {
            $this->classes[] = $classID;
        }

        return $this;
    }

    /**
     * Adds a data source to the list of data sources that will be used to filter ships.
     *
     * @param string|DataSourceDef $source Data source ID or instance.
     * @return $this
     */
    public function selectDataSource(string|DataSourceDef $source) : self
    {
        if(!$source instanceof DataSourceDef) {
            $source = DataSourceDefs::getInstance()->getByID($source);
        }

        $sourceID = $source->getID();
        if(!in_array($sourceID, $this->dataSources, true)) {
            $this->dataSources[] = $sourceID;
        }

        return $this;
    }

    /**
     * Adds a ship size to the list of sizes that will be used to filter ships.
     *
     * @param string|ShipSize $size Ship size ID or instance.
     * @return $this
     */
    public function selectSize(string|ShipSize $size) : self
    {
        if(!$size instanceof ShipSize) {
            $size = ShipSizes::getInstance()->getByID($size);
        }

        $sizeID = $size->getID();
        if(!in_array($sizeID, $this->sizes, true)) {
            $this->sizes[] = $sizeID;
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $labelSearches = array();

    /**
     * Adds a term to search for in ship labels. When multiple terms
     * are added, ships that match any of the terms will be returned.
     *
     * @param string $searchTerm
     * @return $this
     */
    public function selectLabelSearch(string $searchTerm) : self
    {
        $searchTerm = trim($searchTerm);
        if(strlen($searchTerm) < 2) {
            return $this;
        }

        if(!in_array($searchTerm, $this->labelSearches)) {
            $this->labelSearches[] = $searchTerm;
        }

        return $this;
    }

    /**
     * Returns all matching ships.
     * @return ShipDef[]
     */
    public function getAll() : array
    {
        $results = array();

        foreach(ShipDefs::getInstance()->getAll() as $ship) {
            if($this->isMatch($ship)) {
                $results[] = $ship;
            }
        }

        return $results;
    }

    private function isMatch(ShipDef $ship) : bool
    {
        if(!empty($this->builderFactions) && !in_array($ship->getBuilderFactionID(), $this->builderFactions, true)) {
            return false;
        }

        if(!empty($this->classes) && !in_array($ship->getClassID(), $this->classes, true)) {
            return false;
        }

        if(!empty($this->dataSources) && !in_array($ship->getDataSourceID(), $this->dataSources, true)) {
            return false;
        }

        if(!empty($this->sizes) && !in_array($ship->getSizeID(), $this->sizes, true)) {
            return false;
        }

        if(!empty($this->labelSearches)) {
            $found = false;
            foreach($this->labelSearches as $searchTerm) {
                if(stripos($ship->getLabel(), $searchTerm) !== false) {
                    $found = true;
                    break;
                }
            }

            if(!$found) {
                return false;
            }
        }

        return true;
    }
}
