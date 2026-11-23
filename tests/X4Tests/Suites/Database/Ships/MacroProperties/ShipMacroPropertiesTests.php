<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipMacroProperties
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Ships\MacroProperties\ShipExplosionDamage;
use Mistralys\X4\Database\Ships\MacroProperties\ShipHangar;
use Mistralys\X4\Database\Ships\MacroProperties\ShipLoadout;
use Mistralys\X4\Database\Ships\MacroProperties\ShipMacroProperties;
use Mistralys\X4\Database\Ships\MacroProperties\ShipModifiers;
use Mistralys\X4\Database\Ships\MacroProperties\ShipSoftware;
use Mistralys\X4\Database\Ships\MacroProperties\ShipSteeringCurve;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipSize;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use ReflectionClass;
use ReflectionProperty;
use X4Tests\Helpers\X4TestCase;

final class ShipMacroPropertiesTests extends X4TestCase
{
    private const FULL_RECORD = array(
        'macroID' => 'ship_arg_s_fighter_01_a_macro',
        'cargoMacroID' => 'storage_arg_s_fighter_01_a_macro',
        'aliasMacroID' => 'ship_arg_s_fighter_01_macro',
        'hasOwnAccFactors' => true,
        'steeringCurve' => array(
            array('position' => 0.0, 'value' => 1.0),
            array('position' => 0.5, 'value' => 0.25),
        ),
        'explosionDamageHull' => 100.0,
        'explosionDamageShield' => 200.0,
        'explosionShieldDisruption' => 300.0,
        'purposeID' => 'fight',
        'thrusterSize' => 's',
        'iconID' => 'ship_fight_s',
        'secrecyLevel' => 2,
        'droneCapacity' => 5,
        'deployableCapacity' => 0,
        'countermeasureCapacity' => 0,
        'hangarCapacity' => array('s' => 4, 'm' => 2),
        'defaultSoftwareIDs' => array('software_dockmk2'),
        'optionalSoftwareIDs' => array('software_scannerlongrangemk1'),
        'travelDriveStability' => 1.5,
        'gasGatherRate' => 2.5,
        'radarRange' => 30000.0,
        'weaponHeatModifier' => 0.9,
        'shieldCapacityModifier' => 1.1,
        'shieldRechargeRateModifier' => 1.2,
        'shieldRechargeDelayModifier' => 0.8,
        'baseNameID' => '{20101,1001}',
        'variationNameID' => '{20101,1002}',
        'shortVariationNameID' => '{20101,1003}',
        'capturable' => false,
        'prestige' => 3,
        'boostRechargeDelay' => 4.0,
        'boostMinDuration' => 1.0,
        'threatScore' => 7,
        'playerCoverable' => true,
        'defaultLoadout' => array(
            array('type' => 'engine', 'macroID' => 'engine_arg_s_travel_01_mk1_macro', 'count' => 1, 'optional' => false),
        ),
    );

    private const ALWAYS_WRITTEN_KEYS = array(
        'macroID', 'hasOwnAccFactors', 'steeringCurve', 'purposeID', 'thrusterSize', 'iconID',
        'droneCapacity', 'hangarCapacity', 'defaultSoftwareIDs', 'optionalSoftwareIDs',
        'capturable', 'playerCoverable',
    );

    public function test_shipDefKeyConstants() : void
    {
        $expected = array(
            'KEY_MACRO_ID' => 'macroID',
            'KEY_CARGO_MACRO_ID' => 'cargoMacroID',
            'KEY_ALIAS_MACRO_ID' => 'aliasMacroID',
            'KEY_HAS_OWN_ACC_FACTORS' => 'hasOwnAccFactors',
            'KEY_STEERING_CURVE' => 'steeringCurve',
            'KEY_EXPLOSION_DAMAGE_HULL' => 'explosionDamageHull',
            'KEY_EXPLOSION_DAMAGE_SHIELD' => 'explosionDamageShield',
            'KEY_EXPLOSION_SHIELD_DISRUPTION' => 'explosionShieldDisruption',
            'KEY_PURPOSE_ID' => 'purposeID',
            'KEY_THRUSTER_SIZE' => 'thrusterSize',
            'KEY_ICON_ID' => 'iconID',
            'KEY_SECRECY_LEVEL' => 'secrecyLevel',
            'KEY_DRONE_CAPACITY' => 'droneCapacity',
            'KEY_DEPLOYABLE_CAPACITY' => 'deployableCapacity',
            'KEY_COUNTERMEASURE_CAPACITY' => 'countermeasureCapacity',
            'KEY_HANGAR_CAPACITY' => 'hangarCapacity',
            'KEY_DEFAULT_SOFTWARE_IDS' => 'defaultSoftwareIDs',
            'KEY_OPTIONAL_SOFTWARE_IDS' => 'optionalSoftwareIDs',
            'KEY_TRAVEL_DRIVE_STABILITY' => 'travelDriveStability',
            'KEY_GAS_GATHER_RATE' => 'gasGatherRate',
            'KEY_RADAR_RANGE' => 'radarRange',
            'KEY_WEAPON_HEAT_MODIFIER' => 'weaponHeatModifier',
            'KEY_SHIELD_CAPACITY_MODIFIER' => 'shieldCapacityModifier',
            'KEY_SHIELD_RECHARGE_RATE_MODIFIER' => 'shieldRechargeRateModifier',
            'KEY_SHIELD_RECHARGE_DELAY_MODIFIER' => 'shieldRechargeDelayModifier',
            'KEY_BASE_NAME_ID' => 'baseNameID',
            'KEY_VARIATION_NAME_ID' => 'variationNameID',
            'KEY_SHORT_VARIATION_NAME_ID' => 'shortVariationNameID',
            'KEY_CAPTURABLE' => 'capturable',
            'KEY_PRESTIGE' => 'prestige',
            'KEY_BOOST_RECHARGE_DELAY' => 'boostRechargeDelay',
            'KEY_BOOST_MIN_DURATION' => 'boostMinDuration',
            'KEY_THREAT_SCORE' => 'threatScore',
            'KEY_PLAYER_COVERABLE' => 'playerCoverable',
            'KEY_DEFAULT_LOADOUT' => 'defaultLoadout',
        );

        $constants = (new ReflectionClass(ShipDef::class))->getConstants();

        $this->assertCount(35, $expected);

        foreach($expected as $name => $value) {
            $this->assertArrayHasKey($name, $constants);
            $this->assertSame($value, $constants[$name], $name);
        }
    }

    public function test_emptyRecordYieldsDefaults() : void
    {
        $props = ShipMacroProperties::fromArray(array());

        $this->assertSame('', $props->getMacroID());
        $this->assertNull($props->getCargoMacroID());
        $this->assertNull($props->getAliasMacroID());
        $this->assertFalse($props->hasOwnAccFactors());
        $this->assertTrue($props->getSteeringCurve()->isEmpty());
        $this->assertNull($props->getExplosionDamage());
        $this->assertSame('', $props->getPurposeID());
        $this->assertSame('', $props->getThrusterSizeID());
        $this->assertSame('', $props->getIconID());
        $this->assertNull($props->getSecrecyLevel());
        $this->assertSame(0, $props->getDroneCapacity());
        $this->assertNull($props->getDeployableCapacityOverride());
        $this->assertNull($props->getCountermeasureCapacityOverride());
        $this->assertFalse($props->hasHangar());
        $this->assertSame(0, $props->getHangarCapacity('s'));
        $this->assertTrue($props->getSoftware()->isEmpty());
        $this->assertNull($props->getTravelDriveStability());
        $this->assertNull($props->getGasGatherRate());
        $this->assertNull($props->getRadarRangeOverride());
        $this->assertTrue($props->getModifiers()->isEmpty());
        $this->assertNull($props->getBaseNameID());
        $this->assertSame('', $props->getBaseName());
        $this->assertTrue($props->isCapturable());
        $this->assertNull($props->getPrestige());
        $this->assertNull($props->getBoostRechargeDelay());
        $this->assertNull($props->getBoostMinDuration());
        $this->assertNull($props->getThreatScore());
        $this->assertFalse($props->isPlayerCoverable());
        $this->assertNull($props->getDefaultLoadout());
        $this->assertFalse($props->hasDefaultLoadout());

        $this->assertInstanceOf(ShipMacroProperties::class, new ShipMacroProperties());
    }

    public function test_allPropertiesReadonlyWithDefaults() : void
    {
        $class = new ReflectionClass(ShipMacroProperties::class);

        foreach($class->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $this->assertTrue($property->isReadOnly(), $property->getName());
        }

        $constructor = $class->getConstructor();
        $this->assertNotNull($constructor);

        foreach($constructor->getParameters() as $parameter) {
            $this->assertTrue($parameter->isDefaultValueAvailable(), $parameter->getName());
        }
    }

    public function test_toArray_emptyWritesOnlyAlwaysKeys() : void
    {
        $array = ShipMacroProperties::fromArray(array())->toArray();

        $this->assertEqualsCanonicalizing(self::ALWAYS_WRITTEN_KEYS, array_keys($array));
    }

    public function test_toArray_noObjectKeys() : void
    {
        $array = ShipMacroProperties::fromArray(self::FULL_RECORD)->toArray();

        foreach(array('explosionDamage', 'modifiers', 'software', 'hangar', 'macroProperties') as $key) {
            $this->assertArrayNotHasKey($key, $array);
        }
    }

    public function test_fullRoundTripIsLossless() : void
    {
        $props = ShipMacroProperties::fromArray(self::FULL_RECORD);

        // Key order is not part of the contract
        $expected = self::FULL_RECORD;
        $actual = $props->toArray();
        ksort($expected);
        ksort($actual);

        $this->assertSame($expected, $actual);
        $this->assertEquals($props, ShipMacroProperties::fromArray($props->toArray()));
    }

    public function test_toArray_omitsNullableKeysWhenNull() : void
    {
        $record = self::FULL_RECORD;
        $nullable = array_diff(array_keys($record), self::ALWAYS_WRITTEN_KEYS);

        foreach($nullable as $key) {
            unset($record[$key]);
        }

        $array = ShipMacroProperties::fromArray($record)->toArray();

        $this->assertEqualsCanonicalizing(self::ALWAYS_WRITTEN_KEYS, array_keys($array));
    }

    public function test_accessors() : void
    {
        $props = ShipMacroProperties::fromArray(self::FULL_RECORD);

        $this->assertSame('ship_arg_s_fighter_01_a_macro', $props->getMacroID());
        $this->assertSame('storage_arg_s_fighter_01_a_macro', $props->getCargoMacroID());
        $this->assertSame('ship_arg_s_fighter_01_macro', $props->getAliasMacroID());
        $this->assertTrue($props->hasOwnAccFactors());
        $this->assertInstanceOf(ShipSteeringCurve::class, $props->getSteeringCurve());
        $this->assertCount(2, $props->getSteeringCurve()->getPoints());

        $explosion = $props->getExplosionDamage();
        $this->assertInstanceOf(ShipExplosionDamage::class, $explosion);
        $this->assertSame(100.0, $explosion->hull);
        $this->assertSame(200.0, $explosion->shield);
        $this->assertSame(300.0, $explosion->shieldDisruption);

        $this->assertSame('fight', $props->getPurposeID());
        $this->assertSame('s', $props->getThrusterSizeID());
        $this->assertInstanceOf(ShipSize::class, $props->getThrusterSize());
        $this->assertSame('s', $props->getThrusterSize()->getID());
        $this->assertSame('ship_fight_s', $props->getIconID());
        $this->assertSame(2, $props->getSecrecyLevel());
        $this->assertSame(5, $props->getDroneCapacity());
        $this->assertSame(0, $props->getDeployableCapacityOverride());
        $this->assertSame(0, $props->getCountermeasureCapacityOverride());

        $this->assertInstanceOf(ShipHangar::class, $props->getHangar());
        $this->assertTrue($props->hasHangar());
        $this->assertSame(4, $props->getHangarCapacity('s'));
        $this->assertSame(2, $props->getHangarCapacity('m'));
        $this->assertSame(0, $props->getHangarCapacity('xl'));

        $this->assertInstanceOf(ShipSoftware::class, $props->getSoftware());
        $this->assertSame(array('software_dockmk2'), $props->getSoftware()->defaultWareIDs);
        $this->assertSame(array('software_scannerlongrangemk1'), $props->getSoftware()->optionalWareIDs);

        $this->assertSame(1.5, $props->getTravelDriveStability());
        $this->assertSame(2.5, $props->getGasGatherRate());
        $this->assertSame(30000.0, $props->getRadarRangeOverride());

        $this->assertInstanceOf(ShipModifiers::class, $props->getModifiers());
        $this->assertSame(0.9, $props->getModifiers()->weaponHeat);
        $this->assertSame(1.1, $props->getModifiers()->shieldCapacity);
        $this->assertSame(1.2, $props->getModifiers()->shieldRechargeRate);
        $this->assertSame(0.8, $props->getModifiers()->shieldRechargeDelay);

        $this->assertSame('{20101,1001}', $props->getBaseNameID());
        $this->assertSame('{20101,1002}', $props->getVariationNameID());
        $this->assertSame('{20101,1003}', $props->getShortVariationNameID());
        $this->assertFalse($props->isCapturable());
        $this->assertSame(3, $props->getPrestige());
        $this->assertSame(4.0, $props->getBoostRechargeDelay());
        $this->assertSame(1.0, $props->getBoostMinDuration());
        $this->assertSame(7, $props->getThreatScore());
        $this->assertTrue($props->isPlayerCoverable());

        $this->assertInstanceOf(ShipLoadout::class, $props->getDefaultLoadout());
        $this->assertTrue($props->hasDefaultLoadout());
    }

    public function test_explosionDamage_nullWhenAllAbsent() : void
    {
        $partial = ShipMacroProperties::fromArray(array('explosionDamageShield' => 5.0))->getExplosionDamage();
        $this->assertNotNull($partial);
        $this->assertNull($partial->hull);
        $this->assertSame(5.0, $partial->shield);
        $this->assertNull(ShipMacroProperties::fromArray(array('purposeID' => 'fight'))->getExplosionDamage());
    }

    public function test_nameAccessors_ownLanguage() : void
    {
        $english = Languages::getInstance()->getEnglish();
        $props = ShipMacroProperties::fromArray(array('baseNameID' => '{1001,1}'));

        $this->assertSame($english->ts('{1001,1}'), $props->getBaseName());
        $this->assertSame($english->ts('{1001,1}'), $props->getBaseName($english));
    }

    public function test_nameAccessors_fallBackToEnglish() : void
    {
        $english = Languages::getInstance()->getEnglish();
        $props = ShipMacroProperties::fromArray(array(
            'baseNameID' => '{1001,1}',
            'variationNameID' => '{1001,1}',
            'shortVariationNameID' => '{1001,1}',
        ));

        $double = $this->createLanguageWithoutEntries();

        $this->assertNotSame('', $english->ts('{1001,1}'));
        $this->assertSame($english->ts('{1001,1}'), $props->getBaseName($double));
        $this->assertSame($english->ts('{1001,1}'), $props->getVariationName($double));
        $this->assertSame($english->ts('{1001,1}'), $props->getShortVariationName($double));
    }

    public function test_nameAccessors_neverReturnRawCode() : void
    {
        $code = '{999999,999999}';
        $props = ShipMacroProperties::fromArray(array('baseNameID' => $code, 'variationNameID' => $code, 'shortVariationNameID' => $code));
        $double = $this->createLanguageWithoutEntries();

        $this->assertSame('', $props->getBaseName());
        $this->assertSame('', $props->getBaseName($double));
        $this->assertSame('', $props->getVariationName($double));
        $this->assertSame('', $props->getShortVariationName($double));
    }

    private function createLanguageWithoutEntries() : Language
    {
        return new class(Languages::LANGUAGE_FRENCH, 'fr-FR') extends Language {
            public function hasCode(string $code) : bool
            {
                return false;
            }
        };
    }
}
