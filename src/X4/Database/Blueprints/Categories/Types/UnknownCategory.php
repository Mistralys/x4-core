<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories\Types;

use Mistralys\X4\Database\Blueprints\Categories\BaseBlueprintCategory;
use Mistralys\X4\Database\Blueprints\Types\UnknownBlueprint;
use function AppLocalize\t;

class UnknownCategory extends BaseBlueprintCategory
{
    public const CATEGORY_ID = 'unknown';

    public function getID() : string
    {
        return self::CATEGORY_ID;
    }

    public function getLabel() : string
    {
        return t('Unknown');
    }

    public function getBlueprintClass() : string
    {
        return UnknownBlueprint::class;
    }
}
