<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\DroneBlueprint;
use function AppLocalize\t;

/**
 * @method DroneBlueprint[] getBlueprints()
 * @method DroneBlueprint getBlueprintByID(string $blueprintID)
 */
class DroneCategory extends BaseBlueprintCategory
{
    public const DRONE_TYPES = array(
        'transdrone',
        'miningdrone',
        'buildingdrone',
        'cargodrone',
        'fightingdrone',
        'repairdrone'
    );
    public const CATEGORY_ID = 'drones';

    public function getID() : string
    {
        return self::CATEGORY_ID;
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
