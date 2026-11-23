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
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;

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
     * @var string[]
     */
    private array $purposes = array();

    /**
     * Selects ships with the given primary purpose.
     *
     * Combination rule: ANY (a ship has a single purpose). When called
     * several times, a ship matches if it has one of the selected purposes.
     *
     * @param string $purposeID One of the {@see ShipPurposes} IDs.
     * @return $this
     * @throws ShipException {@see ShipException::ERROR_UNKNOWN_PURPOSE} if the ID is not known.
     */
    public function selectPurpose(string $purposeID) : self
    {
        if(!ShipPurposes::idExists($purposeID)) {
            throw new ShipException(
                sprintf('Unknown ship purpose [%s].', $purposeID),
                sprintf('Known purposes: %s', implode(', ', ShipPurposes::getIDs())),
                ShipException::ERROR_UNKNOWN_PURPOSE
            );
        }

        if(!in_array($purposeID, $this->purposes, true)) {
            $this->purposes[] = $purposeID;
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $thrusterSizes = array();

    /**
     * Selects ships whose thrusters have the given size.
     *
     * Combination rule: ANY (a ship has a single thruster size). When
     * called several times, a ship matches if it has one of the selected sizes.
     *
     * @param string|ShipSize $size Ship size ID or instance.
     * @return $this
     */
    public function selectThrusterSize(string|ShipSize $size) : self
    {
        if(!$size instanceof ShipSize) {
            $size = ShipSizes::getInstance()->getByID($size);
        }

        if(!in_array($size->getID(), $this->thrusterSizes, true)) {
            $this->thrusterSizes[] = $size->getID();
        }

        return $this;
    }

    private ?bool $capturable = null;

    /**
     * Selects ships by whether the player can capture them.
     *
     * Combination rule: a single value. The last call wins; the filter is
     * not cumulative.
     *
     * @param bool $capturable True for capturable ships (the default), false for ships that cannot be captured.
     * @return $this
     */
    public function selectCapturable(bool $capturable = true) : self
    {
        $this->capturable = $capturable;

        return $this;
    }

    /**
     * @var string[]
     */
    private array $hangarSizes = array();

    /**
     * Selects ships whose internal hangar can store ships of the given size.
     *
     * Combination rule: ALL. When called several times, a ship matches only
     * if its hangar capacity is above 0 for every selected size.
     *
     * @param string|ShipSize $size Ship size ID or instance.
     * @return $this
     */
    public function selectWithHangar(string|ShipSize $size) : self
    {
        if(!$size instanceof ShipSize) {
            $size = ShipSizes::getInstance()->getByID($size);
        }

        if(!in_array($size->getID(), $this->hangarSizes, true)) {
            $this->hangarSizes[] = $size->getID();
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $softwareWareIDs = array();

    /**
     * Selects ships that can run the given software, as a default or as an
     * optional installation.
     *
     * Combination rule: ALL. When called several times, a ship matches only
     * if every selected software can be installed on it. The ware ID is not
     * required to exist in {@see WareDefs}.
     *
     * @param string|WareDef $software Software ware ID or ware instance.
     * @return $this
     */
    public function selectWithSoftware(string|WareDef $software) : self
    {
        $wareID = $software instanceof WareDef ? $software->getID() : $software;

        if(!in_array($wareID, $this->softwareWareIDs, true)) {
            $this->softwareWareIDs[] = $wareID;
        }

        return $this;
    }

    /**
     * Combination rules: builder factions, classes, sizes, purposes and thruster
     * sizes are ANY; hangar sizes and software are ALL; capturable is a single
     * value. Different kinds of filters always combine as AND.
     *
     * @param ShipDef $item
     * @return bool
     */
    protected function isMatch(CollectionItemInterface $item) : bool
    {
        if(!empty($this->builderFactions)) {
            $intersection = array_intersect($item->getBuilderFactionIDs(), $this->builderFactions);
            if (empty($intersection)) {
                return false;
            }
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

        $properties = $item->getMacroProperties();

        if(!empty($this->purposes) && !in_array($properties->getPurposeID(), $this->purposes, true)) {
            return false;
        }

        if(!empty($this->thrusterSizes) && !in_array($properties->getThrusterSizeID(), $this->thrusterSizes, true)) {
            return false;
        }

        if($this->capturable !== null && $properties->isCapturable() !== $this->capturable) {
            return false;
        }

        foreach($this->hangarSizes as $sizeID) {
            if($properties->getHangarCapacity($sizeID) <= 0) {
                return false;
            }
        }

        foreach($this->softwareWareIDs as $wareID) {
            if(!$properties->getSoftware()->isInstallable($wareID)) {
                return false;
            }
        }

        return true;
    }
}
