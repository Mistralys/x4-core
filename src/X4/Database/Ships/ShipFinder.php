<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\Finder\BaseFinder;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionInterface;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionTrait;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;

class ShipFinder extends BaseFinder implements DataSourceSelectionInterface
{
    use DataSourceSelectionTrait;

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
    private array $sizes = array();

    public function getCollection(): ItemCollectionInterface
    {
        return ShipDefs::getInstance();
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
     * @param ShipDef $item
     * @return bool
     */
    protected function isMatch(CollectionItemInterface $item) : bool
    {
        if(!empty($this->builderFactions) && !in_array($item->getBuilderFactionID(), $this->builderFactions, true)) {
            return false;
        }

        if(!empty($this->classes) && !in_array($item->getClassID(), $this->classes, true)) {
            return false;
        }

        if(!$this->isDataSourceMatch($item->getDataSourceID())) {
            return false;
        }

        if(!empty($this->sizes) && !in_array($item->getSizeID(), $this->sizes, true)) {
            return false;
        }

        if(!$this->isLabelMatch($item->getLabel())) {
            return false;
        }

        return true;
    }
}
