<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\ShipBlueprint;
use function AppLocalize\t;

/**
 * @method ShipBlueprint[] getBlueprints()
 * @method ShipBlueprint getBlueprintByID(string $blueprintID)
 */
class ShipCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_SHIPS;
    }

    public function getLabel() : string
    {
        return t('Ships');
    }

    public function getBlueprintClass() : string
    {
        return ShipBlueprint::class;
    }
}
