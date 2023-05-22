<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\ModificationBlueprint;
use function AppLocalize\t;

/**
 * @method ModificationBlueprint[] getBlueprints()
 * @method ModificationBlueprint getBlueprintByID(string $blueprintID)
 */
class ModificationCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_MODIFICATIONS;
    }

    public function getLabel() : string
    {
        return t('Modifications');
    }

    public function getBlueprintClass() : string
    {
        return ModificationBlueprint::class;
    }
}
