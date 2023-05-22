<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\ShieldBlueprint;
use function AppLocalize\t;

/**
 * @method ShieldBlueprint[] getBlueprints()
 * @method ShieldBlueprint getBlueprintByID(string $blueprintID)
 */
class ShieldCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_SHIELDS;
    }

    public function getLabel() : string
    {
        return t('Shields');
    }

    public function getBlueprintClass() : string
    {
        return ShieldBlueprint::class;
    }
}
