<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints;

use AppUtils\ClassHelper;
use Mistralys\X4\Database\Races\RaceDef;
use function AppUtils\t;

abstract class BlueprintCategory
{
    public const ERROR_UNKNOWN_BLUEPRINT_ID = 136801;

    /**
     * @var array<string,BlueprintDef>
     */
    protected array $blueprints = array();

    private bool $sorted = false;

    abstract public function getID(): string;

    /**
     * @return class-string
     */
    abstract public function getBlueprintClass() : string;

    public function registerBlueprint(string $blueprintID, RaceDef $race) : BlueprintDef
    {
        $class = $this->getBlueprintClass();

        $blueprint = ClassHelper::requireObjectInstanceOf(
            BlueprintDef::class,
            new $class($this, $blueprintID, $race)
        );

        $this->blueprints[$blueprintID] = $blueprint;
        return $blueprint;
    }

    public function createSelection() : BlueprintSelection
    {
        return BlueprintSelection::create($this->getBlueprints());
    }

    /**
     * @return BlueprintDef[]
     */
    public function getBlueprints() : array
    {
        $this->sortBlueprints();

        return array_values($this->blueprints);
    }

    public function countBlueprints() : int
    {
        return count($this->blueprints);
    }

    private function sortBlueprints() : void
    {
        if($this->sorted)
        {
            return;
        }

        $this->sorted = true;

        uasort($this->blueprints, static function (BlueprintDef $a, BlueprintDef $b) : int
        {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });
    }

    /**
     * @return string
     */
    abstract public function getLabel(): string;

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
