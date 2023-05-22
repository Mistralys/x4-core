<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\TurretBlueprint;
use function AppLocalize\t;

/**
 * @method TurretBlueprint[] getBlueprints()
 * @method TurretBlueprint getBlueprintByID(string $blueprintID)
 */
class TurretCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_TURRETS;
    }

    public function getLabel() : string
    {
        return t('Turrets');
    }

    public function getBlueprintClass() : string
    {
        return TurretBlueprint::class;
    }
}
