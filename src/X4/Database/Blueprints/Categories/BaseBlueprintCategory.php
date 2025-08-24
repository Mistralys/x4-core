<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintDef;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\BlueprintException;
use Mistralys\X4\Database\Blueprints\BlueprintSelection;

abstract class BaseBlueprintCategory implements BlueprintCategoryInterface
{
    /**
     * @var array<string,BlueprintDef>
     */
    private array $blueprints = array();
    private bool $blueprintsLoaded = false;

    public function createSelection() : BlueprintSelection
    {
        return BlueprintSelection::create($this->getBlueprints());
    }

    private function registerBlueprint(BlueprintDef $def) : void
    {
        $this->blueprints[$def->getID()] = $def;
    }

    /**
     * @return BlueprintDef[]
     */
    public function getBlueprints() : array
    {
        $this->loadBlueprints();

        return array_values($this->blueprints);
    }

    private function loadBlueprints() : void
    {
        if($this->blueprintsLoaded) {
            return;
        }

        $this->blueprintsLoaded = true;

        $categoryID = $this->getID();

        foreach(BlueprintDefs::getInstance()->getBlueprints() as $blueprint) {
            if($blueprint->getCategoryID() === $categoryID) {
                $this->registerBlueprint($blueprint);
            }
        }
    }

    public function countBlueprints() : int
    {
        $this->loadBlueprints();

        return count($this->blueprints);
    }

    /**
     * @param string $blueprintID
     * @return BlueprintDef
     * @throws BlueprintException {@see self::ERROR_UNKNOWN_BLUEPRINT_ID}
     */
    public function getBlueprintByID(string $blueprintID) : BlueprintDef
    {
        $this->loadBlueprints();

        if(isset($this->blueprints[$blueprintID])) {
            return $this->blueprints[$blueprintID];
        }

        throw new BlueprintException(
            'Unknown blueprint ID',
            sprintf(
                'The blueprint ID [%s] does not exist in category [%s].',
                $blueprintID,
                $this->getID()
            ),
            self::ERROR_UNKNOWN_BLUEPRINT_ID
        );
    }

    public function blueprintIDExists(string $blueprintID) : bool
    {
        $this->loadBlueprints();

        return isset($this->blueprints[$blueprintID]);
    }
}
