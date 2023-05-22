<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\SkinBlueprint;
use function AppLocalize\t;

/**
 * @method SkinBlueprint[] getBlueprints()
 * @method SkinBlueprint getBlueprintByID(string $blueprintID)
 */
class SkinCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_SKINS;
    }

    public function getLabel() : string
    {
        return t('Skins');
    }

    public function getBlueprintClass() : string
    {
        return SkinBlueprint::class;
    }
}
