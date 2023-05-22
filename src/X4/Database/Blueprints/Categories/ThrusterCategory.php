<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\ThrusterBlueprint;
use function AppLocalize\t;

/**
 * @method ThrusterBlueprint[] getBlueprints()
 * @method ThrusterBlueprint getBlueprintByID(string $blueprintID)
 */
class ThrusterCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_THRUSTERS;
    }

    public function getLabel() : string
    {
        return t('Thrusters');
    }

    public function getBlueprintClass() : string
    {
        return ThrusterBlueprint::class;
    }
}
