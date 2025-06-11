<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\WeaponBlueprint;
use function AppLocalize\t;

/**
 * @method WeaponBlueprint[] getBlueprints()
 * @method WeaponBlueprint getBlueprintByID(string $blueprintID)
 */
class WeaponCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'weapons';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Weapons');
    }

    public function getBlueprintClass() : string
    {
        return WeaponBlueprint::class;
    }
}
