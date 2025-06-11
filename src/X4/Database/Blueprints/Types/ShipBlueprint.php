<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Types;

use AppUtils\ConvertHelper;
use Mistralys\X4\Database\Blueprints\BlueprintDef;
use Mistralys\X4\Database\Ships\ShipClasses;
use function AppLocalize\t;

class ShipBlueprint extends BlueprintDef
{
    public const ROLE_MILITARY = 'military';
    public const ROLE_INDUSTRY = 'industry';

    private ?string $size = null;

    /**
     * @var string[]
     */
    private static array $industryRoles = array(
        ShipClasses::CLASS_SCRAPPER,
        ShipClasses::CLASS_SCAVENGER,
        ShipClasses::CLASS_MINER,
        ShipClasses::CLASS_BUILDER,
        ShipClasses::CLASS_TUG,
    );

    public function getTypeLabel() : string
    {
        return t('Ship');
    }

    /**
     * @return string Lowercase size - "xs", "s", "m", "l" or "xl"
     */
    public function getSizeID() : string
    {
        if(!isset($this->size)) {
            $this->size = $this->detectSize();
        }

        return $this->size;
    }

    public function getClassID() : string
    {
        return $this->class;
    }

    public function getRoleID() : string
    {
        if(in_array($this->class, self::$industryRoles, true)) {
            return self::ROLE_INDUSTRY;
        }

        return self::ROLE_MILITARY;
    }

    private function detectSize() : string
    {
        $sizes = array(
            'xs',
            's',
            'm',
            'l',
            'xl'
        );

        $parts = ConvertHelper::explodeTrim('_', $this->getID());

        foreach($sizes as $size) {
            if(in_array($size, $parts, true)) {
                return $size;
            }
        }

        return '';
    }
}
