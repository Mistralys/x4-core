<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\Finder\BaseFinder;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionInterface;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionTrait;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDef;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs;

/**
 * Specialized filtering utility to find wares based on
 * various criteria. Criteria can be combined to refine
 * your search.
 *
 * For example, you can search for all ships for a specific
 * DLC by combining {@see self::selectGroup()} and
 * {@see self::selectDataSource()}.
 *
 * ## Combining filters
 *
 * Different kinds of filters always combine as AND. Within one kind,
 * filters on a single-valued attribute (group, licence, transport type)
 * and on a set-valued attribute where one hit is enough (production
 * method, illegal-for faction) combine as ANY. Capability filters that
 * must all hold (tags, used resources) combine as ALL. Each method states
 * its own rule.
 *
 * @package X4 Database
 * @subpackage Wares
 */
class WareFinder extends BaseFinder implements DataSourceSelectionInterface
{
    use DataSourceSelectionTrait;

    /**
     * @var string[]
     */
    private array $tags = array();

    public function getCollection(): ItemCollectionInterface
    {
        return WareDefs::getInstance();
    }

    /**
     * Returns all wares that have the given tag.
     * @param string $tagName
     * @return $this
     */
    public function selectTag(string $tagName): self
    {
        if (!in_array($tagName, $this->tags, true)) {
            $this->tags[] = $tagName;
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $groups = array();

    public function selectGroup(string|WareGroup $group): self
    {
        if ($group instanceof WareGroup) {
            $group = $group->getID();
        }

        if (!in_array($group, $this->groups, true)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $producedByMethodIDs = array();

    /**
     * Selects wares that can be produced with the given production method.
     *
     * Combination rule: ANY. When called several times, a ware matches if it
     * has a production with at least one of the selected methods.
     *
     * @param string|ProductionMethodDef $method Method ID or method object. IDs are resolved through {@see ProductionMethodDefs}.
     * @return $this
     * @throws RecordNotExistsException If the method ID is not known.
     */
    public function selectProducedBy(string|ProductionMethodDef $method) : self
    {
        if(is_string($method)) {
            $method = ProductionMethodDefs::getInstance()->getByID($method);
        }

        if(!in_array($method->getID(), $this->producedByMethodIDs, true)) {
            $this->producedByMethodIDs[] = $method->getID();
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $resourceIDs = array();

    /**
     * Selects wares whose production recipes consume the given ware as a resource.
     *
     * Combination rule: ALL. When called several times, a ware matches only
     * if its productions consume every selected resource (not necessarily
     * in the same recipe).
     *
     * The check reads the raw production records and does not build
     * {@see WareProduction} objects.
     *
     * @param string|WareDef $resource Ware ID or ware object. IDs are resolved through {@see WareDefs}.
     * @return $this
     * @throws RecordNotExistsException If the ware ID is not known.
     */
    public function selectUsingResource(string|WareDef $resource) : self
    {
        if(is_string($resource)) {
            $resource = WareDefs::getInstance()->getByID($resource);
        }

        if(!in_array($resource->getID(), $this->resourceIDs, true)) {
            $this->resourceIDs[] = $resource->getID();
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $licenceIDs = array();

    /**
     * Selects wares that require the given licence type.
     *
     * Combination rule: ANY (a ware has a single licence type). The ID is not
     * validated: licence types are defined per faction in the game data, so an
     * unknown ID simply matches nothing.
     *
     * @param string $licenceID See {@see WareDef::getLicenceID()}.
     * @return $this
     */
    public function selectLicence(string $licenceID) : self
    {
        if(!in_array($licenceID, $this->licenceIDs, true)) {
            $this->licenceIDs[] = $licenceID;
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $illegalForFactionIDs = array();

    /**
     * Selects wares that are illegal for the given faction.
     *
     * Combination rule: ANY. When called several times, a ware matches if it
     * is illegal for at least one of the selected factions.
     *
     * @param string|FactionDef $faction Faction ID or faction object. IDs are resolved through {@see FactionDefs}.
     * @return $this
     * @throws RecordNotExistsException If the faction ID is not known.
     */
    public function selectIllegalFor(string|FactionDef $faction) : self
    {
        if(is_string($faction)) {
            $faction = FactionDefs::getInstance()->getByID($faction);
        }

        if(!in_array($faction->getID(), $this->illegalForFactionIDs, true)) {
            $this->illegalForFactionIDs[] = $faction->getID();
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $transportTypeIDs = array();

    /**
     * Selects wares that use the given transport type.
     *
     * Combination rule: ANY (a ware has a single transport type). When called
     * several times, a ware matches if it has one of the selected types.
     *
     * @param string $transportTypeID One of the {@see WareTransportTypes} IDs.
     * @return $this
     * @throws WareException {@see WareException::ERROR_UNKNOWN_TRANSPORT_TYPE} if the ID is not known.
     */
    public function selectTransportType(string $transportTypeID) : self
    {
        if(!WareTransportTypes::idExists($transportTypeID)) {
            throw new WareException(
                sprintf('Unknown ware transport type [%s].', $transportTypeID),
                sprintf('Known types: %s', implode(', ', WareTransportTypes::getIDs())),
                WareException::ERROR_UNKNOWN_TRANSPORT_TYPE
            );
        }

        if(!in_array($transportTypeID, $this->transportTypeIDs, true)) {
            $this->transportTypeIDs[] = $transportTypeID;
        }

        return $this;
    }

    /**
     * Combination rules: tags are ALL, groups, production methods,
     * licences, illegal-for factions and transport types are ANY,
     * resources are ALL. Different kinds of filters always combine as AND.
     *
     * @param WareDef $item
     * @return bool
     */
    protected function isMatch(CollectionItemInterface $item): bool
    {
        foreach ($this->tags as $tag) {
            if (!in_array($tag, $item->getTags(), true)) {
                return false;
            }
        }

        if(!$this->isDataSourceMatch($item->getDataSourceID())) {
            return false;
        }

        if (!empty($this->groups) && !in_array($item->getGroupID(), $this->groups, true)) {
            return false;
        }

        if(!$this->isLabelMatch($item->getLabel())) {
            return false;
        }

        if(!empty($this->producedByMethodIDs) && empty(array_intersect($this->producedByMethodIDs, $item->getProductionMethodIDs()))) {
            return false;
        }

        foreach($this->resourceIDs as $resourceID) {
            if(!$item->usesResource($resourceID)) {
                return false;
            }
        }

        if(!empty($this->licenceIDs) && !in_array($item->getLicenceID(), $this->licenceIDs, true)) {
            return false;
        }

        if(!empty($this->illegalForFactionIDs) && empty(array_intersect($this->illegalForFactionIDs, $item->getIllegalFactionIDs()))) {
            return false;
        }

        if(!empty($this->transportTypeIDs) && !in_array($item->getTransportTypeID(), $this->transportTypeIDs, true)) {
            return false;
        }

        return true;
    }
}
