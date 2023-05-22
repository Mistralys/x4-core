<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\CountermeasureBlueprint;
use function AppLocalize\t;

/**
 * @method CountermeasureBlueprint[] getBlueprints()
 * @method CountermeasureBlueprint getBlueprintByID(string $blueprintID)
 */
class CountermeasureCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_COUNTERMEASURES;
    }

    public function getLabel() : string
    {
        return t('Countermeasures');
    }

    public function getBlueprintClass() : string
    {
        return CountermeasureBlueprint::class;
    }
}
