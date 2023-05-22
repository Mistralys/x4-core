<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\DeployableBlueprint;
use function AppLocalize\t;

/**
 * @method DeployableBlueprint[] getBlueprints()
 * @method DeployableBlueprint getBlueprintByID(string $blueprintID)
 */
class DeployableCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_DEPLOYABLES;
    }

    public function getLabel() : string
    {
        return t('Deployable');
    }

    public function getBlueprintClass() : string
    {
        return DeployableBlueprint::class;
    }
}
