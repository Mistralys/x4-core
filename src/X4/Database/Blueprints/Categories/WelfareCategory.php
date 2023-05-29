<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\WeaponBlueprint;
use function AppLocalize\t;

/**
 * @method WeaponBlueprint[] getBlueprints()
 * @method WeaponBlueprint getBlueprintByID(string $blueprintID)
 */
class WelfareCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_WELFARE;
    }

    public function getLabel() : string
    {
        return t('Welfare modules');
    }

    public function getBlueprintClass() : string
    {
        return WeaponBlueprint::class;
    }
}
