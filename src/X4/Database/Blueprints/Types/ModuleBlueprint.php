<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Types;

use AppUtils\ConvertHelper;
use Mistralys\X4\Database\Blueprints\BlueprintDef;
use function AppLocalize\t;

class ModuleBlueprint extends BlueprintDef
{
    private int $version;

    protected function init() : void
    {
        $this->version = $this->detectVersion(ConvertHelper::explodeTrim('_', $this->getID()));
    }

    public function getTypeLabel() : string
    {
        return t('Module');
    }

    public function getVersion() : int
    {
        return $this->version;
    }

    private function detectVersion(array $parts) : int
    {
        $versions = array(
            'mk1' => 1,
            'mk2' => 2,
            'mk3' => 3,
            'mk4' => 4
        );

        foreach($versions as $version => $number)
        {
            if (in_array($version, $parts, true)) {
                return $number;
            }
        }

        return 0;
    }
}
