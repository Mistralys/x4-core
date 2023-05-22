<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Selection;

use Mistralys\X4\Database\Blueprints\BlueprintSelection;
use Mistralys\X4\Database\Blueprints\Types\EngineBlueprint;
use Mistralys\X4\Database\Blueprints\Types\ModuleBlueprint;
use Mistralys\X4\Database\Blueprints\Types\ShieldBlueprint;
use Mistralys\X4\Database\Blueprints\Types\ShipBlueprint;
use Mistralys\X4\Database\Blueprints\Types\ThrusterBlueprint;
use Mistralys\X4\Database\Blueprints\Types\TurretBlueprint;
use Mistralys\X4\Database\Blueprints\Types\WeaponBlueprint;

class TypeSelection
{
    private BlueprintSelection $selection;

    public function __construct(BlueprintSelection $selection)
    {
        $this->selection = $selection;
    }

    /**
     * @return ShipBlueprint[]
     */
    public function ships() : array
    {
        $blueprints = $this->selection->getBlueprints();
        $result = array();

        foreach($blueprints as $blueprint) {
            if($blueprint instanceof ShipBlueprint) {
                $result[] = $blueprint;
            }
        }

        return $result;
    }

    /**
     * @return EngineBlueprint[]
     */
    public function engines() : array
    {
        $blueprints = $this->selection->getBlueprints();
        $result = array();

        foreach($blueprints as $blueprint) {
            if($blueprint instanceof EngineBlueprint) {
                $result[] = $blueprint;
            }
        }

        return $result;
    }

    /**
     * @return ModuleBlueprint[]
     */
    public function modules() : array
    {
        $blueprints = $this->selection->getBlueprints();
        $result = array();

        foreach($blueprints as $blueprint) {
            if($blueprint instanceof ModuleBlueprint) {
                $result[] = $blueprint;
            }
        }

        return $result;
    }

    /**
     * @return ShieldBlueprint[]
     */
    public function shields() : array
    {
        $blueprints = $this->selection->getBlueprints();
        $result = array();

        foreach($blueprints as $blueprint) {
            if($blueprint instanceof ShieldBlueprint) {
                $result[] = $blueprint;
            }
        }

        return $result;
    }

    /**
     * @return ThrusterBlueprint[]
     */
    public function thrusters() : array
    {
        $blueprints = $this->selection->getBlueprints();
        $result = array();

        foreach($blueprints as $blueprint) {
            if($blueprint instanceof ThrusterBlueprint) {
                $result[] = $blueprint;
            }
        }

        return $result;
    }

    /**
     * @return TurretBlueprint[]
     */
    public function turrets() : array
    {
        $blueprints = $this->selection->getBlueprints();
        $result = array();

        foreach($blueprints as $blueprint) {
            if($blueprint instanceof TurretBlueprint) {
                $result[] = $blueprint;
            }
        }

        return $result;
    }
    /**
     * @return WeaponBlueprint[]
     */
    public function weapons() : array
    {
        $blueprints = $this->selection->getBlueprints();
        $result = array();

        foreach($blueprints as $blueprint) {
            if($blueprint instanceof WeaponBlueprint) {
                $result[] = $blueprint;
            }
        }

        return $result;
    }
}
