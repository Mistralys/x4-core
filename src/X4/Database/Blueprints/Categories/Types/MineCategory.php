<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\MineBlueprint;
use function AppLocalize\t;

/**
 * @method MineBlueprint[] getBlueprints()
 * @method MineBlueprint getBlueprintByID(string $blueprintID)
 */
class MineCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'mines';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Mines');
    }

    public function getBlueprintClass() : string
    {
        return MineBlueprint::class;
    }
}
