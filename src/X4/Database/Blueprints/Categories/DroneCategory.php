<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\DroneBlueprint;
use function AppLocalize\t;

/**
 * @method DroneBlueprint[] getBlueprints()
 * @method DroneBlueprint getBlueprintByID(string $blueprintID)
 */
class DroneCategory extends BlueprintCategory
{
    public const DRONE_TYPES = array(
        'transdrone',
        'miningdrone',
        'buildingdrone',
        'cargodrone',
        'fightingdrone',
        'repairdrone'
    );

    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_DRONES;
    }

    public function getLabel() : string
    {
        return t('Drones');
    }

    public function getBlueprintClass() : string
    {
        return DroneBlueprint::class;
    }
}
