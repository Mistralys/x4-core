<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\ShipBlueprint;
use function AppLocalize\t;

/**
 * @method ShipBlueprint[] getBlueprints()
 * @method ShipBlueprint getBlueprintByID(string $blueprintID)
 */
class ShipCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'ships';

    public function getID() : string
    {
        return self::CATEGORY_ID;
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
