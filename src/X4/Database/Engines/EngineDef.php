<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Engines\EngineDef
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Engines;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;

/**
 * Represents a single engine with performance characteristics.
 * 
 * Links to WareDef via wareID for basic ware info (label, tags, price).
 * Stores engine-specific performance data extracted from X4 macro XML.
 *
 * @package X4Core
 * @subpackage Database
 */
class EngineDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_WARE_ID = 'wareID';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_LABEL = 'label';
    public const KEY_SIZE = 'size';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';
    public const KEY_MAKER_RACE = 'makerRace';
    public const KEY_MK = 'mk';
    public const KEY_VARIANT_ID = 'variantID';
    
    // Boost properties
    public const KEY_BOOST_DURATION = 'boostDuration';
    public const KEY_BOOST_RECHARGE = 'boostRecharge';
    public const KEY_BOOST_THRUST = 'boostThrust';
    public const KEY_BOOST_ACCELERATION = 'boostAcceleration';
    public const KEY_BOOST_ATTACK = 'boostAttack';
    public const KEY_BOOST_RELEASE = 'boostRelease';
    public const KEY_BOOST_COAST = 'boostCoast';
    
    // Travel properties
    public const KEY_TRAVEL_CHARGE = 'travelCharge';
    public const KEY_TRAVEL_THRUST = 'travelThrust';
    public const KEY_TRAVEL_ATTACK = 'travelAttack';
    public const KEY_TRAVEL_RELEASE = 'travelRelease';
    
    // Thrust properties
    public const KEY_THRUST_FORWARD = 'thrustForward';
    public const KEY_THRUST_REVERSE = 'thrustReverse';
    
    // Hull properties
    public const KEY_HULL_MAX = 'hullMax';
    public const KEY_HULL_THRESHOLD = 'hullThreshold';
    
    // Curves
    public const KEY_DECELERATION_CURVE = 'decelerationCurve';

    // Core identification
    private string $wareID;
    private string $macroID;
    private string $label;
    private string $size;
    private string $dataSourceID;
    private string $makerRace;
    private int $mk;
    private VariantID $variantID;
    
    // Boost properties (7 fields)
    private float $boostDuration;
    private float $boostRecharge;
    private float $boostThrust;
    private float $boostAcceleration;
    private float $boostAttack;
    private float $boostRelease;
    private float $boostCoast;
    
    // Travel drive properties (4 fields)
    private float $travelCharge;
    private float $travelThrust;
    private float $travelAttack;
    private float $travelRelease;
    
    // Standard thrust (2 fields)
    private float $thrustForward;
    private float $thrustReverse;
    
    // Hull durability (2 fields)
    private float $hullMax;
    private float $hullThreshold;
    
    // Performance curves (optional, may be empty)
    /**
     * @var array<int, array{position: float, value: float}>
     */
    private array $decelerationCurve;

    public function __construct(
        string $wareID,
        string $macroID,
        string $label,
        string $size,
        string $dataSourceID,
        string $makerRace,
        int $mk,
        VariantID $variantID,
        float $boostDuration,
        float $boostRecharge,
        float $boostThrust,
        float $boostAcceleration,
        float $boostAttack,
        float $boostRelease,
        float $boostCoast,
        float $travelCharge,
        float $travelThrust,
        float $travelAttack,
        float $travelRelease,
        float $thrustForward,
        float $thrustReverse,
        float $hullMax,
        float $hullThreshold,
        array $decelerationCurve
    )
    {
        $this->wareID = $wareID;
        $this->macroID = $macroID;
        $this->label = $label;
        $this->size = $size;
        $this->dataSourceID = $dataSourceID;
        $this->makerRace = $makerRace;
        $this->mk = $mk;
        $this->variantID = $variantID;
        
        $this->boostDuration = $boostDuration;
        $this->boostRecharge = $boostRecharge;
        $this->boostThrust = $boostThrust;
        $this->boostAcceleration = $boostAcceleration;
        $this->boostAttack = $boostAttack;
        $this->boostRelease = $boostRelease;
        $this->boostCoast = $boostCoast;
        
        $this->travelCharge = $travelCharge;
        $this->travelThrust = $travelThrust;
        $this->travelAttack = $travelAttack;
        $this->travelRelease = $travelRelease;
        
        $this->thrustForward = $thrustForward;
        $this->thrustReverse = $thrustReverse;
        
        $this->hullMax = $hullMax;
        $this->hullThreshold = $hullThreshold;
        
        $this->decelerationCurve = $decelerationCurve;
    }

    public static function fromArray(mixed $engineDef): EngineDef
    {
        $data = ArrayDataCollection::create($engineDef);

        return new EngineDef(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_MACRO_ID),
            $data->getString(self::KEY_LABEL),
            $data->getString(self::KEY_SIZE),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $data->getString(self::KEY_MAKER_RACE, 'unknown'),
            $data->getInt(self::KEY_MK, 1),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID)),
            $data->getFloat(self::KEY_BOOST_DURATION, 0.0),
            $data->getFloat(self::KEY_BOOST_RECHARGE, 0.0),
            $data->getFloat(self::KEY_BOOST_THRUST, 1.0),
            $data->getFloat(self::KEY_BOOST_ACCELERATION, 1.0),
            $data->getFloat(self::KEY_BOOST_ATTACK, 0.0),
            $data->getFloat(self::KEY_BOOST_RELEASE, 0.0),
            $data->getFloat(self::KEY_BOOST_COAST, 1.0),
            $data->getFloat(self::KEY_TRAVEL_CHARGE, 0.0),
            $data->getFloat(self::KEY_TRAVEL_THRUST, 0.0),
            $data->getFloat(self::KEY_TRAVEL_ATTACK, 0.0),
            $data->getFloat(self::KEY_TRAVEL_RELEASE, 0.0),
            $data->getFloat(self::KEY_THRUST_FORWARD, 0.0),
            $data->getFloat(self::KEY_THRUST_REVERSE, 0.0),
            $data->getFloat(self::KEY_HULL_MAX, 0.0),
            $data->getFloat(self::KEY_HULL_THRESHOLD, 0.0),
            $data->getArray(self::KEY_DECELERATION_CURVE)
        );
    }

    public function getID(): string
    {
        return $this->wareID;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getVariantID(): VariantID
    {
        return $this->variantID;
    }

    public function getMacroID(): string
    {
        return $this->macroID;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getDataSourceID(): string
    {
        return $this->dataSourceID;
    }

    public function getMakerRace(): string
    {
        return $this->makerRace;
    }

    public function getMk(): int
    {
        return $this->mk;
    }

    // Boost getters
    public function getBoostDuration(): float
    {
        return $this->boostDuration;
    }

    public function getBoostRecharge(): float
    {
        return $this->boostRecharge;
    }

    public function getBoostThrust(): float
    {
        return $this->boostThrust;
    }

    public function getBoostAcceleration(): float
    {
        return $this->boostAcceleration;
    }

    public function getBoostAttack(): float
    {
        return $this->boostAttack;
    }

    public function getBoostRelease(): float
    {
        return $this->boostRelease;
    }

    public function getBoostCoast(): float
    {
        return $this->boostCoast;
    }

    // Travel getters
    public function getTravelCharge(): float
    {
        return $this->travelCharge;
    }

    public function getTravelThrust(): float
    {
        return $this->travelThrust;
    }

    public function getTravelAttack(): float
    {
        return $this->travelAttack;
    }

    public function getTravelRelease(): float
    {
        return $this->travelRelease;
    }

    // Thrust getters
    public function getThrustForward(): float
    {
        return $this->thrustForward;
    }

    public function getThrustReverse(): float
    {
        return $this->thrustReverse;
    }

    // Hull getters
    public function getHullMax(): float
    {
        return $this->hullMax;
    }

    public function getHullThreshold(): float
    {
        return $this->hullThreshold;
    }

    // Curve getters
    /**
     * @return array<int, array{position: float, value: float}>
     */
    public function getDecelerationCurve(): array
    {
        return $this->decelerationCurve;
    }

    public function hasDecelerationCurve(): bool
    {
        return !empty($this->decelerationCurve);
    }
}
