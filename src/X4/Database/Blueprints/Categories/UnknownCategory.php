<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Types\UnknownBlueprint;
use function AppLocalize\t;

class UnknownCategory extends BlueprintCategory
{
    public function getID() : string
    {
        return BlueprintDefs::CATEGORY_UNKNOWN;
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
