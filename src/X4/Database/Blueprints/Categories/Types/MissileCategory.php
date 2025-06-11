<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\MissileBlueprint;
use function AppLocalize\t;

/**
 * @method MissileBlueprint[] getBlueprints()
 * @method MissileBlueprint getBlueprintByID(string $blueprintID)
 */
class MissileCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'missiles';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Missiles');
    }

    public function getBlueprintClass() : string
    {
        return MissileBlueprint::class;
    }
}
