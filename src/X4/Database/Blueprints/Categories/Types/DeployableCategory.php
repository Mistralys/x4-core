<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\DeployableBlueprint;
use function AppLocalize\t;

/**
 * @method DeployableBlueprint[] getBlueprints()
 * @method DeployableBlueprint getBlueprintByID(string $blueprintID)
 */
class DeployableCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'deployables';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Deployable');
    }

    public function getBlueprintClass() : string
    {
        return DeployableBlueprint::class;
    }
}
