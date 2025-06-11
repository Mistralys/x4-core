<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\ModificationBlueprint;
use function AppLocalize\t;

/**
 * @method ModificationBlueprint[] getBlueprints()
 * @method ModificationBlueprint getBlueprintByID(string $blueprintID)
 */
class ModificationCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'modifications';

    public function getID() : string
    {
        return self::CATEGORY_ID;
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
