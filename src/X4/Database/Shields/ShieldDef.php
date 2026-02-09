<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Shields\ShieldDef
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Shields;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;

/**
 * Represents a single shield with performance characteristics.
 * 
 * Links to WareDef via wareID for basic ware info (label, tags, price).
 * Stores shield-specific performance data extracted from X4 macro XML.
 *
 * @package X4Core
 * @subpackage Database
 */
class ShieldDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    // Property key constants for JSON serialization
    public const KEY_WARE_ID = 'wareID';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_LABEL = 'label';
    public const KEY_SIZE = 'size';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';
    public const KEY_MAKER_RACE = 'makerRace';
    public const KEY_MK = 'mk';
    public const KEY_VARIANT_ID = 'variantID';
    public const KEY_SHIELD_TYPE = 'shieldType';
    
    public const KEY_RECHARGE_MAX = 'rechargeMax';
    public const KEY_RECHARGE_RATE = 'rechargeRate';
    public const KEY_RECHARGE_DELAY = 'rechargeDelay';
    
    public const KEY_HULL_MAX = 'hullMax';
    public const KEY_HULL_THRESHOLD = 'hullThreshold';
    public const KEY_HULL_INTEGRATED = 'hullIntegrated';
    
    // Core identification
    private string $wareID;
    private string $macroID;
    private string $label;
    private string $size;
    private string $dataSourceID;
    private string $makerRace;
    private int $mk;
    private VariantID $variantID;
    private string $shieldType;
    
    // Recharge properties (3 fields)
    private float $rechargeMax;
    private float $rechargeRate;
    private float $rechargeDelay;
    
    // Hull durability (3 fields, some optional)
    private float $hullMax;
    private float $hullThreshold;
    private bool $hullIntegrated;

    public function __construct(
        string $wareID,
        string $macroID,
        string $label,
        string $size,
        string $dataSourceID,
        string $makerRace,
        int $mk,
        VariantID $variantID,
        string $shieldType,
        float $rechargeMax,
        float $rechargeRate,
        float $rechargeDelay,
        float $hullMax,
        float $hullThreshold,
        bool $hullIntegrated
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
        $this->shieldType = $shieldType;
        
        $this->rechargeMax = $rechargeMax;
        $this->rechargeRate = $rechargeRate;
        $this->rechargeDelay = $rechargeDelay;
        
        $this->hullMax = $hullMax;
        $this->hullThreshold = $hullThreshold;
        $this->hullIntegrated = $hullIntegrated;
    }

    public static function fromArray(mixed $shieldDef): ShieldDef
    {
        $data = ArrayDataCollection::create($shieldDef);

        return new ShieldDef(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_MACRO_ID),
            $data->getString(self::KEY_LABEL),
            $data->getString(self::KEY_SIZE),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $data->getString(self::KEY_MAKER_RACE, 'unknown'),
            $data->getInt(self::KEY_MK, 1),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID)),
            $data->getString(self::KEY_SHIELD_TYPE, 'standard'),
            $data->getFloat(self::KEY_RECHARGE_MAX, 0.0),
            $data->getFloat(self::KEY_RECHARGE_RATE, 0.0),
            $data->getFloat(self::KEY_RECHARGE_DELAY, 0.0),
            $data->getFloat(self::KEY_HULL_MAX, 0.0),
            $data->getFloat(self::KEY_HULL_THRESHOLD, 0.0),
            $data->getBool(self::KEY_HULL_INTEGRATED, false)
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

    public function getShieldType(): string
    {
        return $this->shieldType;
    }

    // Recharge getters
    public function getRechargeMax(): float
    {
        return $this->rechargeMax;
    }

    public function getRechargeRate(): float
    {
        return $this->rechargeRate;
    }

    public function getRechargeDelay(): float
    {
        return $this->rechargeDelay;
    }

    /**
     * Get shield capacity (alias for rechargeMax for clarity).
     */
    public function getCapacity(): float
    {
        return $this->rechargeMax;
    }

    /**
     * Calculate time to fully recharge from 0 (in seconds).
     * Formula: (capacity / rate) + delay
     */
    public function getFullRechargeTime(): float
    {
        if ($this->rechargeRate <= 0.0) {
            return 0.0;
        }
        return ($this->rechargeMax / $this->rechargeRate) + $this->rechargeDelay;
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

    public function isHullIntegrated(): bool
    {
        return $this->hullIntegrated;
    }

    public function hasHull(): bool
    {
        return $this->hullMax > 0.0;
    }

    // Type checks
    public function isStandard(): bool
    {
        return $this->shieldType === 'standard';
    }

    public function isRacer(): bool
    {
        return $this->shieldType === 'racer';
    }

    public function isCorvette(): bool
    {
        return $this->shieldType === 'corvette';
    }

    public function isMothership(): bool
    {
        return $this->shieldType === 'mothership';
    }

    public function isYacht(): bool
    {
        return $this->shieldType === 'yacht';
    }

    public function isExperimental(): bool
    {
        return $this->shieldType === 'experimental';
    }

    public function isVirtual(): bool
    {
        return $this->shieldType === 'virtual';
    }
}
