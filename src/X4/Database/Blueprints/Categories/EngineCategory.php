<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\EngineBlueprint;
use function AppLocalize\t;

/**
 * @method EngineBlueprint[] getBlueprints()
 * @method EngineBlueprint getBlueprintByID(string $blueprintID)
 */
class EngineCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_ENGINES;
    }

    public function getLabel() : string
    {
        return t('Engines');
    }

    public function getBlueprintClass() : string
    {
        return EngineBlueprint::class;
    }
}
