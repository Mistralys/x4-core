<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\EngineBlueprint;
use function AppLocalize\t;

/**
 * @method EngineBlueprint[] getBlueprints()
 * @method EngineBlueprint getBlueprintByID(string $blueprintID)
 */
class EngineCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'engines';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Engines');
    }

    public function getBlueprintClass() : string
    {
        return EngineBlueprint::class;
    }
}
