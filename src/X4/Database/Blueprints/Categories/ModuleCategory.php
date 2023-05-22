<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\ModuleBlueprint;
use function AppLocalize\t;

/**
 * @method ModuleBlueprint[] getBlueprints()
 * @method ModuleBlueprint getBlueprintByID(string $blueprintID)
 */
class ModuleCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_MODULES;
    }

    public function getLabel() : string
    {
        return t('Station modules');
    }

    public function getBlueprintClass() : string
    {
        return ModuleBlueprint::class;
    }
}
