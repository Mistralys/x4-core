<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\ModuleBlueprint;
use Mistralys\X4\Database\Modules\ModuleCategory as ModuleCategoryAlias;
use function AppLocalize\t;

/**
 * @method ModuleBlueprint[] getBlueprints()
 * @method ModuleBlueprint getBlueprintByID(string $blueprintID)
 */
class ModuleCategory extends BaseBlueprintCategory
{
    public function getID() : string
    {
        return ModuleCategoryAlias::CATEGORY_ID;
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
