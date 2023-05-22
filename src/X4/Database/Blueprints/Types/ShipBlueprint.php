<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Types;

use Mistralys\X4\Database\Blueprints\BlueprintCategory;
use Mistralys\X4\Database\Blueprints\BlueprintDef;
use Mistralys\X4\Database\Races\RaceDef;
use function AppLocalize\t;

class ShipBlueprint extends BlueprintDef
{
    public const CLASS_BUILDER = 'builder';
    public const CLASS_TRANSPORT_CONTAINER = 'transport-container';
    public const CLASS_TRANSPORT_CONDENSATE = 'transport-condensate';
    public const CLASS_MINER_SOLID = 'miner-solid';
    public const CLASS_MINER_LIQUID = 'miner-liquid';
    public const CLASS_CARRIER = 'carrier';
    public const CLASS_AUXILIARY = 'auxiliary';
    public const CLASS_SCOUT = 'scout';
    public const CLASS_DESTROYER = 'destroyer';
    public const CLASS_BATTLESHIP = 'battleship';
    public const CLASS_CORVETTE = 'corvette';
    public const CLASS_FRIGATE = 'frigate';
    public const CLASS_FIGHTER = 'fighter';
    public const CLASS_GUNBOAT = 'gunboat';
    public const CLASS_HEAVY_FIGHTER = 'heavy-fighter';
    public const CLASS_BOMBER = 'bomber';
    public const CLASS_SCAVENGER = 'scavenger';
    public const CLASS_SCRAPPER = 'scrapper';
    public const CLASS_TUG = 'tug';

    public const ROLE_MILITARY = 'military';
    public const ROLE_INDUSTRY = 'industry';

    private string $size;

    /**
     * @var array<string,string|string[]>
     */
    private static array $classTerms = array(
        self::CLASS_BUILDER => 'builder',
        self::CLASS_TRANSPORT_CONTAINER => array('trans', 'container'),
        self::CLASS_TRANSPORT_CONDENSATE => array('trans', 'condensate'),
        self::CLASS_MINER_LIQUID => array('miner', 'liquid'),
        self::CLASS_MINER_SOLID => array('miner', 'solid'),
        self::CLASS_CARRIER => 'carrier',
        self::CLASS_AUXILIARY => 'resupplier',
        self::CLASS_SCOUT => 'scout',
        self::CLASS_DESTROYER => 'destroyer',
        self::CLASS_FRIGATE => 'frigate',
        self::CLASS_CORVETTE => 'corvette',
        self::CLASS_BATTLESHIP => 'battleship',
        self::CLASS_FIGHTER => 'fighter',
        self::CLASS_HEAVY_FIGHTER => 'heavyfighter',
        self::CLASS_GUNBOAT => 'gunboat',
        self::CLASS_BOMBER => 'bomber',
        self::CLASS_SCRAPPER => 'scrapper',
        self::CLASS_SCAVENGER => 'scavenger',
        self::CLASS_TUG => 'tugboat'
    );

    /**
     * @var string[]
     */
    private static array $industryRoles = array(
        self::CLASS_SCRAPPER,
        self::CLASS_SCAVENGER,
        self::CLASS_TRANSPORT_CONTAINER,
        self::CLASS_TRANSPORT_CONDENSATE,
        self::CLASS_MINER_LIQUID,
        self::CLASS_MINER_SOLID,
        self::CLASS_BUILDER,
        self::CLASS_TUG
    );

    private string $class;

    public function __construct(BlueprintCategory $category, string $id, RaceDef $race)
    {
        parent::__construct($category, $id, $race);

        $parts = explode('_', $id);

        $this->size = $this->detectSize($parts);
        $this->class = $this->detectClass($parts);
    }

    public function getTypeLabel() : string
    {
        return t('Ship');
    }

    /**
     * @return string Lowercase size - "xs", "s", "m", "l" or "xl"
     */
    public function getSizeID() : string
    {
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

    private function detectSize(array $parts) : string
    {
        $sizes = array(
            'xs',
            's',
            'm',
            'l',
            'xl'
        );

        foreach($sizes as $size)
        {
            if(in_array($size, $parts, true)) {
                return $size;
            }
        }

        return '';
    }

    private function detectClass(array $parts) : string
    {
        foreach(self::$classTerms as $class => $terms)
        {
            if(!is_array($terms)) {
                $terms = array($terms);
            }

            $found = true;
            foreach($terms as $term) {
                if(!in_array($term, $parts, true)) {
                    $found = false;
                    break;
                }
            }

            if($found) {
                return $class;
            }
        }

        return '';
    }
}
