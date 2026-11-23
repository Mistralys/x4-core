<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipSize;
use Mistralys\X4\Database\Ships\ShipSizes;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;

/**
 * Immutable aggregate of the values read from a ship's macro file
 * that are not part of the core {@see ShipDef} stats: macro links,
 * steering curve, hangar, software, modifiers, names, loadout and more.
 *
 * Future macro data is added here, so that the positional constructor
 * of {@see ShipDef} stops growing. The accessors are reached through
 * {@see ShipDef::getMacroProperties()}.
 *
 * ## Array form
 *
 * The array form uses the flat `ShipDef::KEY_*` keys of the ship record.
 * Nesting is used only for the steering curve, the default loadout and
 * the hangar capacity size map. Nullable values are omitted when null.
 *
 * Optional values of the macro where null means "the game default applies"
 * are exposed through accessors with an `Override` suffix.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipMacroProperties
{
    /**
     * @param string $macroID The ship's own macro name.
     * @param string|null $cargoMacroID Canonical name of the storage macro that supplied the cargo values.
     * @param string|null $aliasMacroID The `alias=` target of the ship's own macro.
     * @param bool $hasOwnAccFactors Whether the ship's own macro contains an `<accfactors>` element (the alias target is never inspected).
     * @param ShipSteeringCurve $steeringCurve
     * @param ShipExplosionDamage|null $explosionDamage Null when all three values are absent.
     * @param string $purposeID Primary purpose, see {@see \Mistralys\X4\Database\Ships\ShipPurposes}.
     * @param string $thrusterSizeID Size ID of the thrusters (xs, s, m, l, xl). Empty when unknown.
     * @param string $iconID
     * @param int|null $secrecyLevel
     * @param int $droneCapacity
     * @param int|null $deployableCapacityOverride
     * @param int|null $countermeasureCapacityOverride
     * @param ShipHangar $hangar
     * @param ShipSoftware $software
     * @param float|null $travelDriveStability
     * @param float|null $gasGatherRate
     * @param float|null $radarRangeOverride
     * @param ShipModifiers $modifiers
     * @param string|null $baseNameID
     * @param string|null $variationNameID
     * @param string|null $shortVariationNameID
     * @param bool $capturable
     * @param int|null $prestige
     * @param float|null $boostRechargeDelay
     * @param float|null $boostMinDuration
     * @param int|null $threatScore
     * @param bool $playerCoverable
     * @param ShipLoadout|null $defaultLoadout
     */
    public function __construct(
        public readonly string $macroID = '',
        public readonly ?string $cargoMacroID = null,
        public readonly ?string $aliasMacroID = null,
        public readonly bool $hasOwnAccFactors = false,
        public readonly ShipSteeringCurve $steeringCurve = new ShipSteeringCurve(),
        public readonly ?ShipExplosionDamage $explosionDamage = null,
        public readonly string $purposeID = '',
        public readonly string $thrusterSizeID = '',
        public readonly string $iconID = '',
        public readonly ?int $secrecyLevel = null,
        public readonly int $droneCapacity = 0,
        public readonly ?int $deployableCapacityOverride = null,
        public readonly ?int $countermeasureCapacityOverride = null,
        public readonly ShipHangar $hangar = new ShipHangar(),
        public readonly ShipSoftware $software = new ShipSoftware(),
        public readonly ?float $travelDriveStability = null,
        public readonly ?float $gasGatherRate = null,
        public readonly ?float $radarRangeOverride = null,
        public readonly ShipModifiers $modifiers = new ShipModifiers(),
        public readonly ?string $baseNameID = null,
        public readonly ?string $variationNameID = null,
        public readonly ?string $shortVariationNameID = null,
        public readonly bool $capturable = true,
        public readonly ?int $prestige = null,
        public readonly ?float $boostRechargeDelay = null,
        public readonly ?float $boostMinDuration = null,
        public readonly ?int $threatScore = null,
        public readonly bool $playerCoverable = false,
        public readonly ?ShipLoadout $defaultLoadout = null
    )
    {
    }

    /**
     * Creates the object from a ship record, which uses the flat
     * `ShipDef::KEY_*` keys. Missing keys fall back to the defaults.
     *
     * @param array<string,mixed> $shipRecord
     * @return self
     */
    public static function fromArray(array $shipRecord) : self
    {
        $explosion = new ShipExplosionDamage(
            self::floatOrNull($shipRecord, ShipDef::KEY_EXPLOSION_DAMAGE_HULL),
            self::floatOrNull($shipRecord, ShipDef::KEY_EXPLOSION_DAMAGE_SHIELD),
            self::floatOrNull($shipRecord, ShipDef::KEY_EXPLOSION_SHIELD_DISRUPTION)
        );

        $loadout = null;
        if(isset($shipRecord[ShipDef::KEY_DEFAULT_LOADOUT]) && is_array($shipRecord[ShipDef::KEY_DEFAULT_LOADOUT])) {
            $loadout = ShipLoadout::fromArray($shipRecord[ShipDef::KEY_DEFAULT_LOADOUT]);
        }

        return new self(
            (string)($shipRecord[ShipDef::KEY_MACRO_ID] ?? ''),
            self::stringOrNull($shipRecord, ShipDef::KEY_CARGO_MACRO_ID),
            self::stringOrNull($shipRecord, ShipDef::KEY_ALIAS_MACRO_ID),
            (bool)($shipRecord[ShipDef::KEY_HAS_OWN_ACC_FACTORS] ?? false),
            ShipSteeringCurve::fromArray((array)($shipRecord[ShipDef::KEY_STEERING_CURVE] ?? array())),
            $explosion->isEmpty() ? null : $explosion,
            (string)($shipRecord[ShipDef::KEY_PURPOSE_ID] ?? ''),
            (string)($shipRecord[ShipDef::KEY_THRUSTER_SIZE] ?? ''),
            (string)($shipRecord[ShipDef::KEY_ICON_ID] ?? ''),
            self::intOrNull($shipRecord, ShipDef::KEY_SECRECY_LEVEL),
            (int)($shipRecord[ShipDef::KEY_DRONE_CAPACITY] ?? 0),
            self::intOrNull($shipRecord, ShipDef::KEY_DEPLOYABLE_CAPACITY),
            self::intOrNull($shipRecord, ShipDef::KEY_COUNTERMEASURE_CAPACITY),
            ShipHangar::fromArray((array)($shipRecord[ShipDef::KEY_HANGAR_CAPACITY] ?? array())),
            new ShipSoftware(
                (array)($shipRecord[ShipDef::KEY_DEFAULT_SOFTWARE_IDS] ?? array()),
                (array)($shipRecord[ShipDef::KEY_OPTIONAL_SOFTWARE_IDS] ?? array())
            ),
            self::floatOrNull($shipRecord, ShipDef::KEY_TRAVEL_DRIVE_STABILITY),
            self::floatOrNull($shipRecord, ShipDef::KEY_GAS_GATHER_RATE),
            self::floatOrNull($shipRecord, ShipDef::KEY_RADAR_RANGE),
            new ShipModifiers(
                self::floatOrNull($shipRecord, ShipDef::KEY_WEAPON_HEAT_MODIFIER),
                self::floatOrNull($shipRecord, ShipDef::KEY_SHIELD_CAPACITY_MODIFIER),
                self::floatOrNull($shipRecord, ShipDef::KEY_SHIELD_RECHARGE_RATE_MODIFIER),
                self::floatOrNull($shipRecord, ShipDef::KEY_SHIELD_RECHARGE_DELAY_MODIFIER)
            ),
            self::stringOrNull($shipRecord, ShipDef::KEY_BASE_NAME_ID),
            self::stringOrNull($shipRecord, ShipDef::KEY_VARIATION_NAME_ID),
            self::stringOrNull($shipRecord, ShipDef::KEY_SHORT_VARIATION_NAME_ID),
            (bool)($shipRecord[ShipDef::KEY_CAPTURABLE] ?? true),
            self::intOrNull($shipRecord, ShipDef::KEY_PRESTIGE),
            self::floatOrNull($shipRecord, ShipDef::KEY_BOOST_RECHARGE_DELAY),
            self::floatOrNull($shipRecord, ShipDef::KEY_BOOST_MIN_DURATION),
            self::intOrNull($shipRecord, ShipDef::KEY_THREAT_SCORE),
            (bool)($shipRecord[ShipDef::KEY_PLAYER_COVERABLE] ?? false),
            $loadout
        );
    }

    /**
     * Serializes to the flat `ShipDef::KEY_*` keys. The always-written keys
     * are present in every record; every nullable key is omitted when null.
     *
     * @return array<string,mixed>
     */
    public function toArray() : array
    {
        $result = array(
            ShipDef::KEY_MACRO_ID => $this->macroID,
            ShipDef::KEY_HAS_OWN_ACC_FACTORS => $this->hasOwnAccFactors,
            ShipDef::KEY_STEERING_CURVE => $this->steeringCurve->toArray(),
            ShipDef::KEY_PURPOSE_ID => $this->purposeID,
            ShipDef::KEY_THRUSTER_SIZE => $this->thrusterSizeID,
            ShipDef::KEY_ICON_ID => $this->iconID,
            ShipDef::KEY_DRONE_CAPACITY => $this->droneCapacity,
            ShipDef::KEY_HANGAR_CAPACITY => $this->hangar->toArray(),
            ShipDef::KEY_DEFAULT_SOFTWARE_IDS => $this->software->defaultWareIDs,
            ShipDef::KEY_OPTIONAL_SOFTWARE_IDS => $this->software->optionalWareIDs,
            ShipDef::KEY_CAPTURABLE => $this->capturable,
            ShipDef::KEY_PLAYER_COVERABLE => $this->playerCoverable,
        );

        $optional = array(
            ShipDef::KEY_CARGO_MACRO_ID => $this->cargoMacroID,
            ShipDef::KEY_ALIAS_MACRO_ID => $this->aliasMacroID,
            ShipDef::KEY_EXPLOSION_DAMAGE_HULL => $this->explosionDamage?->hull,
            ShipDef::KEY_EXPLOSION_DAMAGE_SHIELD => $this->explosionDamage?->shield,
            ShipDef::KEY_EXPLOSION_SHIELD_DISRUPTION => $this->explosionDamage?->shieldDisruption,
            ShipDef::KEY_SECRECY_LEVEL => $this->secrecyLevel,
            ShipDef::KEY_DEPLOYABLE_CAPACITY => $this->deployableCapacityOverride,
            ShipDef::KEY_COUNTERMEASURE_CAPACITY => $this->countermeasureCapacityOverride,
            ShipDef::KEY_TRAVEL_DRIVE_STABILITY => $this->travelDriveStability,
            ShipDef::KEY_GAS_GATHER_RATE => $this->gasGatherRate,
            ShipDef::KEY_RADAR_RANGE => $this->radarRangeOverride,
            ShipDef::KEY_WEAPON_HEAT_MODIFIER => $this->modifiers->weaponHeat,
            ShipDef::KEY_SHIELD_CAPACITY_MODIFIER => $this->modifiers->shieldCapacity,
            ShipDef::KEY_SHIELD_RECHARGE_RATE_MODIFIER => $this->modifiers->shieldRechargeRate,
            ShipDef::KEY_SHIELD_RECHARGE_DELAY_MODIFIER => $this->modifiers->shieldRechargeDelay,
            ShipDef::KEY_BASE_NAME_ID => $this->baseNameID,
            ShipDef::KEY_VARIATION_NAME_ID => $this->variationNameID,
            ShipDef::KEY_SHORT_VARIATION_NAME_ID => $this->shortVariationNameID,
            ShipDef::KEY_PRESTIGE => $this->prestige,
            ShipDef::KEY_BOOST_RECHARGE_DELAY => $this->boostRechargeDelay,
            ShipDef::KEY_BOOST_MIN_DURATION => $this->boostMinDuration,
            ShipDef::KEY_THREAT_SCORE => $this->threatScore,
            ShipDef::KEY_DEFAULT_LOADOUT => $this->defaultLoadout?->toArray(),
        );

        foreach($optional as $key => $value) {
            if($value !== null) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    // region: Macro links

    public function getMacroID() : string
    {
        return $this->macroID;
    }

    public function getCargoMacroID() : ?string
    {
        return $this->cargoMacroID;
    }

    public function getAliasMacroID() : ?string
    {
        return $this->aliasMacroID;
    }

    /**
     * Whether the ship's own macro file contains an `<accfactors>`
     * element. Partial elements count; the alias target is never inspected.
     */
    public function hasOwnAccFactors() : bool
    {
        return $this->hasOwnAccFactors;
    }

    // endregion

    /**
     * Gets the steering curve points in document order.
     *
     * The point names (`position`, `value`) are kept from the game, because
     * their exact in-game meaning is not documented in the data. The curve
     * is a plain point container and does not interpolate.
     */
    public function getSteeringCurve() : ShipSteeringCurve
    {
        return $this->steeringCurve;
    }

    /**
     * @return ShipExplosionDamage|null Null when none of the three values is present.
     */
    public function getExplosionDamage() : ?ShipExplosionDamage
    {
        return $this->explosionDamage;
    }

    /**
     * @return string One of the {@see \Mistralys\X4\Database\Ships\ShipPurposes} IDs.
     */
    public function getPurposeID() : string
    {
        return $this->purposeID;
    }

    public function getThrusterSizeID() : string
    {
        return $this->thrusterSizeID;
    }

    /**
     * @return ShipSize
     */
    public function getThrusterSize() : ShipSize
    {
        return ShipSizes::getInstance()->getByID($this->thrusterSizeID);
    }

    public function getIconID() : string
    {
        return $this->iconID;
    }

    public function getSecrecyLevel() : ?int
    {
        return $this->secrecyLevel;
    }

    /**
     * Drone storage capacity (`<storage unit>`). Same name as `ModuleDef::getDroneCapacity()`.
     */
    public function getDroneCapacity() : int
    {
        return $this->droneCapacity;
    }

    /**
     * @return int|null Explicit override; null means the game default applies.
     */
    public function getDeployableCapacityOverride() : ?int
    {
        return $this->deployableCapacityOverride;
    }

    /**
     * @return int|null Explicit override; null means the game default applies.
     */
    public function getCountermeasureCapacityOverride() : ?int
    {
        return $this->countermeasureCapacityOverride;
    }

    /**
     * Internal ship storage for docked ships, not the docking points
     * reported by `ShipDef::getDocks()`.
     */
    public function getHangar() : ShipHangar
    {
        return $this->hangar;
    }

    /**
     * @param string $size A {@see ShipSizes} ID.
     * @return int
     */
    public function getHangarCapacity(string $size) : int
    {
        return $this->hangar->getCapacity($size);
    }

    public function hasHangar() : bool
    {
        return !$this->hangar->isEmpty();
    }

    public function getSoftware() : ShipSoftware
    {
        return $this->software;
    }

    public function getTravelDriveStability() : ?float
    {
        return $this->travelDriveStability;
    }

    public function getGasGatherRate() : ?float
    {
        return $this->gasGatherRate;
    }

    /**
     * @return float|null Null means the game default applies.
     */
    public function getRadarRangeOverride() : ?float
    {
        return $this->radarRangeOverride;
    }

    /**
     * All values are multipliers.
     */
    public function getModifiers() : ShipModifiers
    {
        return $this->modifiers;
    }

    // region: Names

    public function getBaseNameID() : ?string
    {
        return $this->baseNameID;
    }

    public function getVariationNameID() : ?string
    {
        return $this->variationNameID;
    }

    public function getShortVariationNameID() : ?string
    {
        return $this->shortVariationNameID;
    }

    /**
     * @param Language|null $language Defaults to English.
     * @return string Empty if the ship has no base name or no language has the text. Never a raw text code.
     */
    public function getBaseName(?Language $language = null) : string
    {
        return $this->translate($this->baseNameID, $language);
    }

    /**
     * @param Language|null $language Defaults to English.
     * @return string Empty if the ship has no variation name or no language has the text. Never a raw text code.
     */
    public function getVariationName(?Language $language = null) : string
    {
        return $this->translate($this->variationNameID, $language);
    }

    /**
     * @param Language|null $language Defaults to English.
     * @return string Empty if the ship has no short variation name or no language has the text. Never a raw text code.
     */
    public function getShortVariationName(?Language $language = null) : string
    {
        return $this->translate($this->shortVariationNameID, $language);
    }

    private function translate(?string $code, ?Language $language) : string
    {
        if(empty($code)) {
            return '';
        }

        $language ??= Languages::getInstance()->getEnglish();

        return $language->tsWithFallback($code);
    }

    // endregion

    /**
     * False when the macro sets `<capture allow="0">`.
     */
    public function isCapturable() : bool
    {
        return $this->capturable;
    }

    public function getPrestige() : ?int
    {
        return $this->prestige;
    }

    /**
     * @return float|null Null means the game default applies.
     */
    public function getBoostRechargeDelay() : ?float
    {
        return $this->boostRechargeDelay;
    }

    /**
     * @return float|null Null means the game default applies.
     */
    public function getBoostMinDuration() : ?float
    {
        return $this->boostMinDuration;
    }

    public function getThreatScore() : ?int
    {
        return $this->threatScore;
    }

    /**
     * The raw game flag `<ownership playercoverable="1">`. The game
     * does not document its exact meaning.
     */
    public function isPlayerCoverable() : bool
    {
        return $this->playerCoverable;
    }

    public function getDefaultLoadout() : ?ShipLoadout
    {
        return $this->defaultLoadout;
    }

    public function hasDefaultLoadout() : bool
    {
        return $this->defaultLoadout !== null;
    }

    /**
     * @param array<string,mixed> $record
     */
    private static function floatOrNull(array $record, string $key) : ?float
    {
        return isset($record[$key]) ? (float)$record[$key] : null;
    }

    /**
     * @param array<string,mixed> $record
     */
    private static function intOrNull(array $record, string $key) : ?int
    {
        return isset($record[$key]) ? (int)$record[$key] : null;
    }

    /**
     * @param array<string,mixed> $record
     */
    private static function stringOrNull(array $record, string $key) : ?string
    {
        return isset($record[$key]) ? (string)$record[$key] : null;
    }
}
