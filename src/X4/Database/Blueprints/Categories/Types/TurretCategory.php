<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\TurretBlueprint;
use function AppLocalize\t;

/**
 * @method TurretBlueprint[] getBlueprints()
 * @method TurretBlueprint getBlueprintByID(string $blueprintID)
 */
class TurretCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'turrets';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Turrets');
    }

    public function getBlueprintClass() : string
    {
        return TurretBlueprint::class;
    }
}
