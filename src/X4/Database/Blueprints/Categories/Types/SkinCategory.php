<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\SkinBlueprint;
use function AppLocalize\t;

/**
 * @method SkinBlueprint[] getBlueprints()
 * @method SkinBlueprint getBlueprintByID(string $blueprintID)
 */
class SkinCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'skins';

    public function getID() : string
    {
        return self::CATEGORY_ID;
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
