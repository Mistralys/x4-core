<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\CountermeasureBlueprint;
use function AppLocalize\t;

/**
 * @method CountermeasureBlueprint[] getBlueprints()
 * @method CountermeasureBlueprint getBlueprintByID(string $blueprintID)
 */
class CountermeasureCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'countermeasures';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Countermeasures');
    }

    public function getBlueprintClass() : string
    {
        return CountermeasureBlueprint::class;
    }
}
