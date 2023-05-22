<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\MissileBlueprint;
use function AppLocalize\t;

/**
 * @method MissileBlueprint[] getBlueprints()
 * @method MissileBlueprint getBlueprintByID(string $blueprintID)
 */
class MissileCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_MISSILES;
    }

    public function getLabel() : string
    {
        return t('Missiles');
    }

    public function getBlueprintClass() : string
    {
        return MissileBlueprint::class;
    }
}
