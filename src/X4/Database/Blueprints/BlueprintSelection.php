<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\Selection\TypeSelection;

class BlueprintSelection
{
    public const ERROR_UNKNOWN_BLUEPRINT_ID = 137001;

    /**
     * @var array<string,BlueprintDef>
     */
    private array $blueprints = array();
    private ?TypeSelection $typeSelection = null;

    private function __construct()
    {
    }

    /**
     * @param BlueprintDef[]|BlueprintSelection|NULL $blueprints
     */
    public static function create($blueprints=null) : BlueprintSelection
    {
        $selection = new BlueprintSelection();

        if($blueprints instanceof self) {
            $selection->addSelection($blueprints);
        } else if(is_array($blueprints)) {
            $selection->addBlueprints($blueprints);
        }

        return $selection;
    }

    public function addBlueprint(BlueprintDef $blueprint) : self
    {
        $this->blueprints[$blueprint->getID()] = $blueprint;

        return $this->resetFilters();
    }

    private bool $filtersValid = false;
    private array $filteredCategories = array();
    private array $filteredBlueprints = array();
    private array $filteredBlueprintIDs = array();

    private function resetFilters() : self
    {
        $this->filtersValid = false;
        $this->filteredCategories = array();
        $this->filteredBlueprints = array();
        $this->filteredBlueprintIDs = array();

        return $this;
    }

    /**
     * @param BlueprintDef[] $blueprints
     * @return $this
     */
    public function addBlueprints(array $blueprints) : self
    {
        foreach($blueprints as $blueprint) {
            $this->addBlueprint($blueprint);
        }

        return $this;
    }

    public function addSelection(BlueprintSelection $selection) : self
    {
        return $this->addBlueprints($selection->getBlueprints());
    }

    /**
     * Fetches a list of all blueprint categories available in
     * the current blueprint selection.
     *
     * @return BlueprintCategory[]
     */
    public function getCategories() : array
    {
        $this->filterBlueprints();

        return array_values($this->filteredCategories);
    }

    /**
     * @return BlueprintDef[]
     */
    public function getBlueprints() : array
    {
        $this->filterBlueprints();

        return array_values($this->filteredBlueprints);
    }

    /**
     * @return string[]
     */
    public function getBlueprintIDs() : array
    {
        $this->filterBlueprints();

        return $this->filteredBlueprintIDs;
    }

    public function countBlueprints() : int
    {
        return count($this->getBlueprintIDs());
    }

    private function filterBlueprints() : void
    {
        if($this->filtersValid) {
            return;
        }

        $this->filtersValid = true;
        $this->filteredBlueprints = array();
        $this->filteredCategories = array();
        $this->filteredBlueprintIDs = array();

        foreach($this->blueprints as $blueprint)
        {
            if(!$this->isMatch($blueprint))
            {
                continue;
            }

            $id = $blueprint->getID();

            $this->filteredBlueprints[$id] = $blueprint;
            $this->filteredBlueprintIDs[] = $id;
            $this->filteredCategories[$blueprint->getCategoryID()] = $blueprint->getCategory();
        }

        uasort($this->filteredBlueprints, static function(BlueprintDef $a, BlueprintDef $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });

        uasort($this->filteredCategories, static function(BlueprintCategory $a, BlueprintCategory $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });
    }

    private function isMatch(BlueprintDef $blueprint) : bool
    {
        return true;
    }

    /**
     * @param string $blueprintID
     * @return BlueprintDef
     * @throws BlueprintException {@see self::ERROR_UNKNOWN_BLUEPRINT_ID}
     */
    public function getBlueprintByID(string $blueprintID) : BlueprintDef
    {
        $this->filterBlueprints();

        if(isset($this->filteredBlueprints[$blueprintID])) {
            return $this->filteredBlueprints[$blueprintID];
        }

        throw new BlueprintException(
            'Unknown blueprint ID',
            sprintf(
                'The blueprint ID [%s] does not exist in the selection.',
                $blueprintID
            ),
            self::ERROR_UNKNOWN_BLUEPRINT_ID
        );
    }

    public function hasBlueprint(BlueprintDef $blueprint) : bool
    {
        return $this->hasBlueprintID($blueprint->getID());
    }

    public function hasBlueprintID(string $blueprintID) : bool
    {
        $this->filterBlueprints();

        return isset($this->filteredBlueprints[$blueprintID]);
    }

    public function hasCategoryID(string $categoryID) : bool
    {
        $this->filterBlueprints();

        return isset($this->filteredCategories[$categoryID]);
    }

    public function getCategoryIDs() : array
    {
        $this->filterBlueprints();

        return array_keys($this->filteredCategories);
    }

    /**
     * Allows selecting blueprints by their type (only ships for example).
     *
     * @return TypeSelection
     */
    public function getByType() : TypeSelection
    {
        if(!isset($this->typeSelection)) {
            $this->typeSelection = new TypeSelection($this);
        }

        return $this->typeSelection;
    }
}
