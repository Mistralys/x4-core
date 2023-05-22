<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Types;

use Mistralys\X4\Database\Blueprints\BlueprintDef;
use function AppLocalize\t;

class UnknownBlueprint extends BlueprintDef
{
    public function getTypeLabel() : string
    {
        return t('Unknown');
    }
}
