<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\ShieldBlueprint;
use function AppLocalize\t;

/**
 * @method ShieldBlueprint[] getBlueprints()
 * @method ShieldBlueprint getBlueprintByID(string $blueprintID)
 */
class ShieldCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'shields';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Shields');
    }

    public function getBlueprintClass() : string
    {
        return ShieldBlueprint::class;
    }
}
