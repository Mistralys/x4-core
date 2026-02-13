<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Weapons\WeaponDef
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Weapons;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;

/**
 * Represents a single weapon with performance characteristics.
 * 
 * Links to WareDef via wareID for basic ware info (label, tags, price).
 * Stores weapon-specific performance data extracted from X4 weapon and bullet macro XML.
 *
 * @package X4Core
 * @subpackage Database
 */
class WeaponDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    // Key constants for JSON structure
    public const KEY_WARE_ID = 'wareID';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_BULLET_CLASS = 'bulletClass';
    public const KEY_LABEL = 'label';
    public const KEY_SIZE = 'size';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';
    public const KEY_MAKER_RACE = 'makerRace';
    public const KEY_MAKER_RACES = 'makerRaces';
    public const KEY_MK = 'mk';
    public const KEY_VARIANT_ID = 'variantID';
    public const KEY_WEAPON_SYSTEM = 'weaponSystem';
    public const KEY_WEAPON_CATEGORY = 'weaponCategory';
    
    // Heat properties
    public const KEY_HEAT_OVERHEAT = 'heatOverheat';
    public const KEY_HEAT_COOLDELAY = 'heatCooldelay';
    public const KEY_HEAT_COOLRATE = 'heatCoolrate';
    public const KEY_HEAT_REENABLE = 'heatReenable';
    
    // Rotation properties
    public const KEY_ROTATION_SPEED = 'rotationSpeed';
    public const KEY_ROTATION_ACCELERATION = 'rotationAcceleration';
    
    // Hull properties
    public const KEY_HULL_MAX = 'hullMax';
    public const KEY_HULL_HITTABLE = 'hullHittable';
    
    // Ammunition properties
    public const KEY_AMMO_VALUE = 'ammoValue';
    public const KEY_AMMO_RELOAD = 'ammoReload';
    
    // Bullet properties
    public const KEY_BULLET_SPEED = 'bulletSpeed';
    public const KEY_BULLET_LIFETIME = 'bulletLifetime';
    public const KEY_BULLET_RANGE = 'bulletRange';
    public const KEY_BULLET_AMOUNT = 'bulletAmount';
    public const KEY_BULLET_BARRELAMOUNT = 'bulletBarrelamount';
    public const KEY_BULLET_ICON = 'bulletIcon';
    public const KEY_BULLET_TIMEDIFF = 'bulletTimediff';
    public const KEY_BULLET_ANGLE = 'bulletAngle';
    public const KEY_BULLET_MAXHITS = 'bulletMaxhits';
    public const KEY_BULLET_RICOCHET = 'bulletRicochet';
    public const KEY_BULLET_ATTACH = 'bulletAttach';
    
    // Combat properties
    public const KEY_HEAT_PER_SHOT = 'heatPerShot';
    public const KEY_RELOAD_RATE = 'reloadRate';
    public const KEY_DAMAGE_VALUE = 'damageValue';
    public const KEY_REPAIR_VALUE = 'repairValue';

    // Core identification
    private string $wareID;
    private string $macroID;
    private string $bulletClass;
    private string $label;
    private string $size;
    private string $dataSourceID;
    /**
     * @var string[]
     */
    private array $makerRaces;
    private int $mk;
    private VariantID $variantID;
    private string $weaponSystem;
    private string $weaponCategory;
    
    // Weapon heat properties (4 fields)
    private float $heatOverheat;
    private float $heatCooldelay;
    private float $heatCoolrate;
    private float $heatReenable;
    
    // Weapon rotation (2 fields)
    private float $rotationSpeed;
    private float $rotationAcceleration;
    
    // Weapon hull (2 fields)
    private float $hullMax;
    private int $hullHittable;
    
    // Ammunition properties (2 fields)
    private float $ammoValue;
    private float $ammoReload;
    
    // Bullet properties (11 fields)
    private float $bulletSpeed;
    private float $bulletLifetime;
    private float $bulletRange;
    private int $bulletAmount;
    private int $bulletBarrelamount;
    private string $bulletIcon;
    private float $bulletTimediff;
    private float $bulletAngle;
    private int $bulletMaxhits;
    private int $bulletRicochet;
    private int $bulletAttach;
    
    // Combat properties (4 fields)
    private float $heatPerShot;
    private float $reloadRate;
    private float $damageValue;
    private float $repairValue;

    public function __construct(
        string $wareID,
        string $macroID,
        string $bulletClass,
        string $label,
        string $size,
        string $dataSourceID,
        array $makerRaces,
        int $mk,
        VariantID $variantID,
        string $weaponSystem,
        string $weaponCategory,
        float $heatOverheat,
        float $heatCooldelay,
        float $heatCoolrate,
        float $heatReenable,
        float $rotationSpeed,
        float $rotationAcceleration,
        float $hullMax,
        int $hullHittable,
        float $ammoValue,
        float $ammoReload,
        float $bulletSpeed,
        float $bulletLifetime,
        float $bulletRange,
        int $bulletAmount,
        int $bulletBarrelamount,
        string $bulletIcon,
        float $bulletTimediff,
        float $bulletAngle,
        int $bulletMaxhits,
        int $bulletRicochet,
        int $bulletAttach,
        float $heatPerShot,
        float $reloadRate,
        float $damageValue,
        float $repairValue
    )
    {
        $this->wareID = $wareID;
        $this->macroID = $macroID;
        $this->bulletClass = $bulletClass;
        $this->label = $label;
        $this->size = $size;
        $this->dataSourceID = $dataSourceID;
        $this->makerRaces = $makerRaces;
        $this->mk = $mk;
        $this->variantID = $variantID;
        $this->weaponSystem = $weaponSystem;
        $this->weaponCategory = $weaponCategory;
        
        $this->heatOverheat = $heatOverheat;
        $this->heatCooldelay = $heatCooldelay;
        $this->heatCoolrate = $heatCoolrate;
        $this->heatReenable = $heatReenable;
        
        $this->rotationSpeed = $rotationSpeed;
        $this->rotationAcceleration = $rotationAcceleration;
        
        $this->hullMax = $hullMax;
        $this->hullHittable = $hullHittable;
        
        $this->ammoValue = $ammoValue;
        $this->ammoReload = $ammoReload;
        
        $this->bulletSpeed = $bulletSpeed;
        $this->bulletLifetime = $bulletLifetime;
        $this->bulletRange = $bulletRange;
        $this->bulletAmount = $bulletAmount;
        $this->bulletBarrelamount = $bulletBarrelamount;
        $this->bulletIcon = $bulletIcon;
        $this->bulletTimediff = $bulletTimediff;
        $this->bulletAngle = $bulletAngle;
        $this->bulletMaxhits = $bulletMaxhits;
        $this->bulletRicochet = $bulletRicochet;
        $this->bulletAttach = $bulletAttach;
        
        $this->heatPerShot = $heatPerShot;
        $this->reloadRate = $reloadRate;
        $this->damageValue = $damageValue;
        $this->repairValue = $repairValue;
    }

    public static function fromArray(mixed $weaponDef): WeaponDef
    {
        $data = ArrayDataCollection::create($weaponDef);

        // Handle both new array format and old string format for backward compatibility
        $makerRaces = [];
        if ($data->hasKey(self::KEY_MAKER_RACES)) {
            $makerRaces = $data->getArray(self::KEY_MAKER_RACES);
        } elseif ($data->hasKey(self::KEY_MAKER_RACE)) {
            $raceString = $data->getString(self::KEY_MAKER_RACE, 'unknown');
            $makerRaces = array_values(array_filter(explode(' ', $raceString)));
        }
        
        // Default to unknown if empty
        if (empty($makerRaces)) {
            $makerRaces = ['unknown'];
        }

        // Calculate bullet range if not provided (speed × lifetime)
        $bulletSpeed = $data->getFloat(self::KEY_BULLET_SPEED, 0.0);
        $bulletLifetime = $data->getFloat(self::KEY_BULLET_LIFETIME, 0.0);
        $bulletRange = $data->getFloat(self::KEY_BULLET_RANGE, $bulletSpeed * $bulletLifetime);

        return new WeaponDef(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_MACRO_ID),
            $data->getString(self::KEY_BULLET_CLASS, ''),
            $data->getString(self::KEY_LABEL),
            $data->getString(self::KEY_SIZE),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $makerRaces,
            $data->getInt(self::KEY_MK, 1),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID)),
            $data->getString(self::KEY_WEAPON_SYSTEM, 'weapon_standard'),
            $data->getString(self::KEY_WEAPON_CATEGORY, 'standard'),
            $data->getFloat(self::KEY_HEAT_OVERHEAT, 0.0),
            $data->getFloat(self::KEY_HEAT_COOLDELAY, 0.0),
            $data->getFloat(self::KEY_HEAT_COOLRATE, 0.0),
            $data->getFloat(self::KEY_HEAT_REENABLE, 0.0),
            $data->getFloat(self::KEY_ROTATION_SPEED, 0.0),
            $data->getFloat(self::KEY_ROTATION_ACCELERATION, 0.0),
            $data->getFloat(self::KEY_HULL_MAX, 0.0),
            $data->getInt(self::KEY_HULL_HITTABLE, 0),
            $data->getFloat(self::KEY_AMMO_VALUE, 0.0),
            $data->getFloat(self::KEY_AMMO_RELOAD, 0.0),
            $bulletSpeed,
            $bulletLifetime,
            $bulletRange,
            $data->getInt(self::KEY_BULLET_AMOUNT, 1),
            $data->getInt(self::KEY_BULLET_BARRELAMOUNT, 1),
            $data->getString(self::KEY_BULLET_ICON, ''),
            $data->getFloat(self::KEY_BULLET_TIMEDIFF, 0.0),
            $data->getFloat(self::KEY_BULLET_ANGLE, 0.0),
            $data->getInt(self::KEY_BULLET_MAXHITS, 1),
            $data->getInt(self::KEY_BULLET_RICOCHET, 0),
            $data->getInt(self::KEY_BULLET_ATTACH, 0),
            $data->getFloat(self::KEY_HEAT_PER_SHOT, 0.0),
            $data->getFloat(self::KEY_RELOAD_RATE, 0.0),
            $data->getFloat(self::KEY_DAMAGE_VALUE, 0.0),
            $data->getFloat(self::KEY_REPAIR_VALUE, 0.0)
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

    public function getWareID(): string
    {
        return $this->wareID;
    }

    public function getMacroID(): string
    {
        return $this->macroID;
    }

    public function getBulletClass(): string
    {
        return $this->bulletClass;
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
        if (empty($this->makerRaces)) {
            return 'unknown';
        }
        return $this->makerRaces[0];
    }

    /**
     * Returns all maker races for this weapon.
     * @return string[]
     */
    public function getMakerRaces(): array
    {
        return $this->makerRaces;
    }

    /**
     * Checks if this weapon has multiple maker races.
     * @return bool
     */
    public function hasMultipleMakerRaces(): bool
    {
        return count($this->makerRaces) > 1;
    }

    public function getMk(): int
    {
        return $this->mk;
    }

    public function getWeaponSystem(): string
    {
        return $this->weaponSystem;
    }

    public function getWeaponCategory(): string
    {
        return $this->weaponCategory;
    }

    // Heat getters
    public function getHeatOverheat(): float
    {
        return $this->heatOverheat;
    }

    public function getHeatCooldelay(): float
    {
        return $this->heatCooldelay;
    }

    public function getHeatCoolrate(): float
    {
        return $this->heatCoolrate;
    }

    public function getHeatReenable(): float
    {
        return $this->heatReenable;
    }

    // Rotation getters
    public function getRotationSpeed(): float
    {
        return $this->rotationSpeed;
    }

    public function getRotationAcceleration(): float
    {
        return $this->rotationAcceleration;
    }

    // Hull getters
    public function getHullMax(): float
    {
        return $this->hullMax;
    }

    public function getHullHittable(): int
    {
        return $this->hullHittable;
    }

    public function isHullHittable(): bool
    {
        return $this->hullHittable === 1;
    }

    // Ammunition getters
    public function getAmmoValue(): float
    {
        return $this->ammoValue;
    }

    public function getAmmoReload(): float
    {
        return $this->ammoReload;
    }

    // Bullet getters
    public function getBulletSpeed(): float
    {
        return $this->bulletSpeed;
    }

    public function getBulletLifetime(): float
    {
        return $this->bulletLifetime;
    }

    public function getBulletRange(): float
    {
        return $this->bulletRange;
    }

    public function getBulletAmount(): int
    {
        return $this->bulletAmount;
    }

    public function getBulletBarrelamount(): int
    {
        return $this->bulletBarrelamount;
    }

    public function getBulletIcon(): string
    {
        return $this->bulletIcon;
    }

    public function getBulletTimediff(): float
    {
        return $this->bulletTimediff;
    }

    public function getBulletAngle(): float
    {
        return $this->bulletAngle;
    }

    public function getBulletMaxhits(): int
    {
        return $this->bulletMaxhits;
    }

    public function getBulletRicochet(): int
    {
        return $this->bulletRicochet;
    }

    public function canRicochet(): bool
    {
        return $this->bulletRicochet === 1;
    }

    public function getBulletAttach(): int
    {
        return $this->bulletAttach;
    }

    public function canAttach(): bool
    {
        return $this->bulletAttach === 1;
    }

    // Combat getters
    public function getHeatPerShot(): float
    {
        return $this->heatPerShot;
    }

    public function getReloadRate(): float
    {
        return $this->reloadRate;
    }

    public function getDamageValue(): float
    {
        return $this->damageValue;
    }

    public function getRepairValue(): float
    {
        return $this->repairValue;
    }

    /**
     * Check if this is a repair weapon (has repair value).
     * 
     * @return bool
     */
    public function isRepairWeapon(): bool
    {
        return $this->repairValue > 0.0;
    }

    /**
     * Calculate effective DPS (damage per second).
     * Takes into account reload rate, damage, and bullet amount.
     * 
     * @return float
     */
    public function getDPS(): float
    {
        if ($this->reloadRate <= 0.0) {
            return 0.0;
        }
        return $this->damageValue * $this->reloadRate * $this->bulletAmount;
    }

    /**
     * Calculate shots until overheat.
     * Returns float infinity if weapon doesn't overheat.
     * 
     * @return float
     */
    public function getShotsUntilOverheat(): float
    {
        if ($this->heatPerShot <= 0.0) {
            return INF;
        }
        return $this->heatOverheat / $this->heatPerShot;
    }

    /**
     * Calculate time to fire until overheat (seconds).
     * Returns float infinity if weapon doesn't overheat.
     * 
     * @return float
     */
    public function getTimeUntilOverheat(): float
    {
        if ($this->reloadRate <= 0.0 || $this->heatPerShot <= 0.0) {
            return INF;
        }
        return $this->getShotsUntilOverheat() / $this->reloadRate;
    }

    /**
     * Calculate cooldown time from overheat to re-enable threshold (seconds).
     * 
     * @return float
     */
    public function getCooldownTime(): float
    {
        if ($this->heatCoolrate <= 0.0) {
            return 0.0;
        }
        
        $heatToCool = $this->heatOverheat - $this->heatReenable;
        return $this->heatCooldelay + ($heatToCool / $this->heatCoolrate);
    }

    /**
     * Check if this is a turret weapon (has rotation speed).
     * 
     * @return bool
     */
    public function isTurret(): bool
    {
        return $this->rotationSpeed > 0.0;
    }

    /**
     * Check if this is a beam weapon.
     * 
     * @return bool
     */
    public function isBeamWeapon(): bool
    {
        return str_contains($this->weaponSystem, 'beam') || 
               $this->weaponCategory === 'energy';
    }

    /**
     * Check if this is a missile weapon.
     * 
     * @return bool
     */
    public function isMissileWeapon(): bool
    {
        return str_contains($this->weaponSystem, 'missile') || 
               str_contains($this->weaponCategory, 'missile') ||
               str_contains($this->weaponCategory, 'guided') ||
               str_contains($this->weaponCategory, 'dumbfire') ||
               str_contains($this->weaponCategory, 'torpedo');
    }

    /**
     * Check if this is a mining weapon.
     * 
     * @return bool
     */
    public function isMiningWeapon(): bool
    {
        return $this->weaponCategory === 'mining';
    }

    public function toArray(): array
    {
        return [
            self::KEY_WARE_ID => $this->wareID,
            self::KEY_MACRO_ID => $this->macroID,
            self::KEY_BULLET_CLASS => $this->bulletClass,
            self::KEY_LABEL => $this->label,
            self::KEY_SIZE => $this->size,
            self::KEY_DATA_SOURCE_ID => $this->dataSourceID,
            self::KEY_MAKER_RACES => $this->makerRaces,
            self::KEY_MK => $this->mk,
            self::KEY_VARIANT_ID => $this->variantID->getID(),
            self::KEY_WEAPON_SYSTEM => $this->weaponSystem,
            self::KEY_WEAPON_CATEGORY => $this->weaponCategory,
            self::KEY_HEAT_OVERHEAT => $this->heatOverheat,
            self::KEY_HEAT_COOLDELAY => $this->heatCooldelay,
            self::KEY_HEAT_COOLRATE => $this->heatCoolrate,
            self::KEY_HEAT_REENABLE => $this->heatReenable,
            self::KEY_ROTATION_SPEED => $this->rotationSpeed,
            self::KEY_ROTATION_ACCELERATION => $this->rotationAcceleration,
            self::KEY_HULL_MAX => $this->hullMax,
            self::KEY_HULL_HITTABLE => $this->hullHittable,
            self::KEY_AMMO_VALUE => $this->ammoValue,
            self::KEY_AMMO_RELOAD => $this->ammoReload,
            self::KEY_BULLET_SPEED => $this->bulletSpeed,
            self::KEY_BULLET_LIFETIME => $this->bulletLifetime,
            self::KEY_BULLET_RANGE => $this->bulletRange,
            self::KEY_BULLET_AMOUNT => $this->bulletAmount,
            self::KEY_BULLET_BARRELAMOUNT => $this->bulletBarrelamount,
            self::KEY_BULLET_ICON => $this->bulletIcon,
            self::KEY_BULLET_TIMEDIFF => $this->bulletTimediff,
            self::KEY_BULLET_ANGLE => $this->bulletAngle,
            self::KEY_BULLET_MAXHITS => $this->bulletMaxhits,
            self::KEY_BULLET_RICOCHET => $this->bulletRicochet,
            self::KEY_BULLET_ATTACH => $this->bulletAttach,
            self::KEY_HEAT_PER_SHOT => $this->heatPerShot,
            self::KEY_RELOAD_RATE => $this->reloadRate,
            self::KEY_DAMAGE_VALUE => $this->damageValue,
            self::KEY_REPAIR_VALUE => $this->repairValue
        ];
    }
}
