<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\ThrusterBlueprint;
use function AppLocalize\t;

/**
 * @method ThrusterBlueprint[] getBlueprints()
 * @method ThrusterBlueprint getBlueprintByID(string $blueprintID)
 */
class ThrusterCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'thruster';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Thrusters');
    }

    public function getBlueprintClass() : string
    {
        return ThrusterBlueprint::class;
    }
}
