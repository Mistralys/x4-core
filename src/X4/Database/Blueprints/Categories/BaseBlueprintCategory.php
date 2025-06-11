<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintDef;
use Mistralys\X4\Database\Blueprints\BlueprintException;
use Mistralys\X4\Database\Blueprints\BlueprintSelection;

abstract class BaseBlueprintCategory implements BlueprintCategoryInterface
{
    /**
     * @var array<string,BlueprintDef>|null
     */
    private ?array $blueprints = null;

    public function createSelection() : BlueprintSelection
    {
        return BlueprintSelection::create($this->getBlueprints());
    }

    /**
     * @return BlueprintDef[]
     */
    public function getBlueprints() : array
    {
        return array_values($this->blueprints);
    }

    public function countBlueprints() : int
    {
        return count($this->blueprints);
    }

    /**
     * @param string $blueprintID
     * @return BlueprintDef
     * @throws BlueprintException {@see self::ERROR_UNKNOWN_BLUEPRINT_ID}
     */
    public function getBlueprintByID(string $blueprintID) : BlueprintDef
    {
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
        return isset($this->blueprints[$blueprintID]);
    }
}
