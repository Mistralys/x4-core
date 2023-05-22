<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints;

use AppUtils\ClassHelper;
use Mistralys\X4\Database\Blueprints\Categories\CountermeasureCategory;
use Mistralys\X4\Database\Blueprints\Categories\DeployableCategory;
use Mistralys\X4\Database\Blueprints\Categories\DroneCategory;
use Mistralys\X4\Database\Blueprints\Categories\EngineCategory;
use Mistralys\X4\Database\Blueprints\Categories\MissileCategory;
use Mistralys\X4\Database\Blueprints\Categories\ModificationCategory;
use Mistralys\X4\Database\Blueprints\Categories\ModuleCategory;
use Mistralys\X4\Database\Blueprints\Categories\ShieldCategory;
use Mistralys\X4\Database\Blueprints\Categories\ShipCategory;
use Mistralys\X4\Database\Blueprints\Categories\SkinCategory;
use Mistralys\X4\Database\Blueprints\Categories\ThrusterCategory;
use Mistralys\X4\Database\Blueprints\Categories\TurretCategory;
use Mistralys\X4\Database\Blueprints\Categories\UnknownCategory;
use Mistralys\X4\Database\Blueprints\Categories\WeaponCategory;
use Mistralys\X4\Database\Races\RaceDef;
use Mistralys\X4\Database\Races\RaceDefs;
use function AppUtils\array_remove_values;
use function AppUtils\t;

class BlueprintDefs extends BlueprintCategory
{
    public const CATEGORY_MODULES = 'modules';
    public const CATEGORY_SHIELDS = 'shields';
    public const CATEGORY_WEAPONS = 'weapons';
    public const CATEGORY_TURRETS = 'turrets';
    public const CATEGORY_ENGINES = 'engines';
    public const CATEGORY_SHIPS = 'ships';
    public const CATEGORY_THRUSTERS = 'thruster';
    public const CATEGORY_DEPLOYABLES = 'deployables';
    public const CATEGORY_MODIFICATIONS = 'modifications';
    public const CATEGORY_SKINS = 'skins';
    public const CATEGORY_COUNTERMEASURES = 'countermeasures';
    public const CATEGORY_MISSILES = 'missiles';
    public const CATEGORY_DRONES = 'drones';
    public const CATEGORY_UNKNOWN = 'unknown';

    public const ERROR_UNKNOWN_CATEGORY_ID = 136901;

    /**
     * @var array<string,class-string>
     */
    protected array $partDefs = array(
        'turret' => TurretCategory::class,
        'ship' => ShipCategory::class,
        'shield' => ShieldCategory::class,
        'module' => ModuleCategory::class,
        'engine' => EngineCategory::class,
        'mod' => ModificationCategory::class,
        'weapon' => WeaponCategory::class,
        'satellite' => DeployableCategory::class,
        'resourceprobe' => DeployableCategory::class,
        'waypointmarker' => DeployableCategory::class,
        'survey' => DeployableCategory::class,
        'paintmod' => SkinCategory::class,
        'clothingmod' => SkinCategory::class,
        'countermeasure' => CountermeasureCategory::class,
        'missile' => MissileCategory::class,
        'thruster' => ThrusterCategory::class
    );

    /**
     * @var BlueprintCategory[]
     */
    private array $categories = array();
    private static ?BlueprintDefs $instance = null;

    private function __construct()
    {
        foreach(BlueprintEnum::KNOWN_BLUEPRINTS as $blueprintID)
        {
            $this->createBlueprint($blueprintID);
        }
    }

    public function getID() : string
    {
        return 'all-blueprints';
    }

    public function getBlueprintClass() : string
    {
        return BlueprintDef::class;
    }

    public static function getInstance() : BlueprintDefs
    {
        if(!isset(self::$instance)) {
            self::$instance = new BlueprintDefs();
        }

        return self::$instance;
    }

    private function createCategory(string $categoryClass) : BlueprintCategory
    {
        $category = ClassHelper::requireObjectInstanceOf(
            BlueprintCategory::class,
            new $categoryClass()
        );

        $categoryID = $category->getID();

        if(!isset($this->categories[$categoryID])) {
            $this->categories[$categoryID] = $category;
        }

        return $this->categories[$categoryID];
    }

    /**
     * @return BlueprintCategory[]
     */
    public function getCategories() : array
    {
        return array_values($this->categories);
    }

    public function getLabel() : string
    {
        return t('All blueprints');
    }

    private function createBlueprint(string $blueprintID) : void
    {
        $parts = explode('_', $blueprintID);
        $type = array_shift($parts);
        $race = $this->detectRace($parts);
        $categoryClass = $this->detectCategoryClass($type, $parts);

        $blueprint = $this->createCategory($categoryClass)->registerBlueprint($blueprintID, $race);

        $this->blueprints[$blueprintID] = $blueprint;
    }

    private function detectCategoryClass(string $type, array $parts) : string
    {
        // Special case for laser towers, which are categorized as
        // ships but considered deployable.
        if(in_array('lasertower', $parts, true)) {
            return DeployableCategory::class;
        }

        // Special case for drones, which are classified as ships.
        foreach(DroneCategory::DRONE_TYPES as $droneType) {
            if(in_array($droneType, $parts, true)) {
                return DroneCategory::class;
            }
        }

        return $this->partDefs[$type] ?? UnknownCategory::class;
    }

    public function detectRace(array &$idParts) : RaceDef
    {
        $collection = RaceDefs::getInstance();
        $raceIDs = $collection->getIDs();

        foreach($raceIDs as $raceID)
        {
            if(in_array($raceID, $idParts, true)) {
                $idParts = array_remove_values($idParts, array($raceID));
                return $collection->getByID($raceID);
            }
        }

        return $collection->getByID(RaceDefs::RACE_GENERIC);
    }

    /**
     * @param string $categoryID
     * @return BlueprintCategory
     * @throws BlueprintException {@see self::ERROR_UNKNOWN_CATEGORY_ID}
     */
    public function getCategoryByID(string $categoryID) : BlueprintCategory
    {
        if(isset($this->categories[$categoryID])) {
            return $this->categories[$categoryID];
        }

        throw new BlueprintException(
            'Unknown blueprint category.',
            sprintf(
                'The category ID [%s] does not exist.',
                $categoryID
            ),
            self::ERROR_UNKNOWN_CATEGORY_ID
        );
    }

    public function getShipsCategory() : ShipCategory
    {
        return ClassHelper::requireObjectInstanceOf(
            ShipCategory::class,
            $this->getCategoryByID(self::CATEGORY_SHIPS)
        );
    }
}
