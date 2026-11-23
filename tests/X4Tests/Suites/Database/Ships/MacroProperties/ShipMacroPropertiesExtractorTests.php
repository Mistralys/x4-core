<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipMacroPropertiesExtractor
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Ships\MacroProperties\ShipLoadoutItem;
use Mistralys\X4\Database\Ships\MacroProperties\ShipMacroProperties;
use Mistralys\X4\Database\Ships\MacroProperties\ShipMacroPropertiesExtractor;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use X4Tests\Helpers\X4TestCase;

final class ShipMacroPropertiesExtractorTests extends X4TestCase
{
    private const FIXTURE_XML = <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<macros>
  <macro name="fixture_ship_macro" class="ship_m">
    <properties>
      <identification basename="{1,1}" variation="{1,2}" icon="fixture_icon" />
      <software>
        <software ware="software_flightassistmk1" default="1" />
        <software ware="software_does_not_exist" compatible="1" />
      </software>
      <storage unit="3" deployable="0" countermeasure="0" />
      <purpose primary="no_such_purpose" />
      <thruster tags="gigantic" />
      <capture allow="0" />
      <ownership playercoverable="1" />
      <ship type="gunboat" prestige="4" />
      <radar range="25000" />
      <boost rechargedelay="2.5" minduration="1.5" />
      <threatscore value="7" />
      <traveldrivestability maxvalue="0.5" />
      <gatherrate gas="1.25" />
      <secrecy level="3" />
      <modifiers>
        <weapon heat="1.5" />
        <shield capacity="2" rechargerate="0.5" rechargedelay="3" />
      </modifiers>
      <explosiondamage value="100" shielddisruption="30" />
      <physics><accfactors forward="1.3" /></physics>
      <thruster macro="not_a_property" />
      <loadouts>
        <loadout id="default">
          <macros>
            <engine macro="engine_a_macro" path="../con_engine_01" />
            <engine macro="engine_a_macro" path="../con_engine_02" />
            <weapon macro="weapon_a_macro" path="../con_weapon_01" optional="1" />
            <weapon macro="weapon_a_macro" path="../con_weapon_02" />
            <bogus macro="bogus_macro" />
          </macros>
          <groups>
            <turrets macro="turret_a_macro" path=".." group="g1" exact="2" />
            <turrets macro="turret_a_macro" path=".." group="g2" exact="3" />
            <shields macro="shield_a_macro" path=".." group="g3" min="0" max="5" optional="1" />
          </groups>
          <virtualmacros>
            <thruster macro="thruster_a_macro" />
          </virtualmacros>
          <software>
            <software ware="software_flightassistmk1" />
          </software>
          <ammunition>
            <ammunition macro="ammo_a_macro" min="1" max="40" optional="1" />
            <ammunition min="0" max="40" optional="1" />
          </ammunition>
          <unknownthing />
        </loadout>
      </loadouts>
    </properties>
  </macro>
</macros>
XML;

    private const ALIAS_FIXTURE_XML = <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<macros>
  <macro name="fixture_alias_target_macro" class="ship_m">
    <properties>
      <identification basename="{9,1}" icon="alias_icon" />
      <purpose primary="trade" />
      <thruster tags="large" />
      <secrecy level="1" />
      <steeringcurve>
        <point position="1" value="1" />
        <point position="2" value="0.5" />
      </steeringcurve>
      <loadouts>
        <loadout id="default">
          <macros><engine macro="alias_engine_macro" path="../con_engine_01" /></macros>
        </loadout>
      </loadouts>
    </properties>
  </macro>
</macros>
XML;

    private const OWN_FIXTURE_XML = <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<macros>
  <macro name="fixture_alias_macro" class="ship_m" alias="fixture_alias_target_macro">
    <properties>
      <identification icon="own_icon" />
      <secrecy level="4" />
    </properties>
  </macro>
</macros>
XML;

    private function extractFixture() : array
    {
        $extractor = new ShipMacroPropertiesExtractor(
            DOMExtended::createFromString(self::FIXTURE_XML),
            null,
            null,
            'vanilla',
            'fixture_ship'
        );

        return $extractor->extract();
    }

    /**
     * @return array{0:array<string,mixed>,1:string} The extracted record and the console output.
     */
    private function extractFixtureWithOutput() : array
    {
        Console::setEnabled(true);
        ob_start();

        try {
            $record = $this->extractFixture();
        } finally {
            $output = (string)ob_get_clean();
            Console::setEnabled(false);
        }

        return array($record, $output);
    }

    private function extractRealShip(string $wareID) : array
    {
        $ware = WareDefs::getInstance()->getByID($wareID);
        $macros = MacroFileDefs::getInstance();
        $dom = $macros->getByMacroName($ware->getMacroID(), $ware->getDataSourceID())->getDOM();

        $aliasID = (string)$dom->byTagName('macro')->requireFirst()->getAttribute('alias');
        $aliasDom = null;
        if($aliasID !== '') {
            $aliasDom = $macros->getByMacroName($aliasID, $ware->getDataSourceID())->getDOM();
        }

        return (new ShipMacroPropertiesExtractor($dom, $aliasDom, $aliasID, $ware->getDataSourceID(), $wareID))->extract();
    }

    public function test_ownOnlyShip() : void
    {
        $record = $this->extractRealShip('ship_arg_m_bomber_01_a');

        $this->assertArrayNotHasKey(ShipDef::KEY_MACRO_ID, $record);
        $this->assertArrayNotHasKey(ShipDef::KEY_ALIAS_MACRO_ID, $record);
        $this->assertTrue($record[ShipDef::KEY_HAS_OWN_ACC_FACTORS]);

        $this->assertSame('fight', $record[ShipDef::KEY_PURPOSE_ID]);
        $this->assertSame('m', $record[ShipDef::KEY_THRUSTER_SIZE]);
        $this->assertSame('ship_m_gunboat_01', $record[ShipDef::KEY_ICON_ID]);
        $this->assertSame(2, $record[ShipDef::KEY_SECRECY_LEVEL]);
        $this->assertSame(0, $record[ShipDef::KEY_DRONE_CAPACITY]);
        $this->assertSame(500.0, $record[ShipDef::KEY_EXPLOSION_DAMAGE_HULL]);
        $this->assertSame(2500.0, $record[ShipDef::KEY_EXPLOSION_DAMAGE_SHIELD]);
        $this->assertArrayNotHasKey(ShipDef::KEY_EXPLOSION_SHIELD_DISRUPTION, $record);

        $this->assertSame('{20101,10501}', $record[ShipDef::KEY_BASE_NAME_ID]);
        $this->assertSame('{20111,1101}', $record[ShipDef::KEY_VARIATION_NAME_ID]);
        $this->assertSame('{20111,1103}', $record[ShipDef::KEY_SHORT_VARIATION_NAME_ID]);

        $this->assertSame(
            array('software_flightassistmk1', 'software_scannerlongrangemk1', 'software_scannerobjectmk1', 'software_targetmk1'),
            $record[ShipDef::KEY_DEFAULT_SOFTWARE_IDS]
        );
        $this->assertContains('software_dockmk2', $record[ShipDef::KEY_OPTIONAL_SOFTWARE_IDS]);

        $curve = $record[ShipDef::KEY_STEERING_CURVE];
        $this->assertSame(array('position' => 1.01, 'value' => 1.0), $curve[0]);
        $this->assertSame(array('position' => 2.7, 'value' => 0.15), $curve[count($curve) - 1]);

        $this->assertSame(array('xs' => 10), $record[ShipDef::KEY_HANGAR_CAPACITY]);

        $this->assertTrue($record[ShipDef::KEY_CAPTURABLE]);
        $this->assertFalse($record[ShipDef::KEY_PLAYER_COVERABLE]);
        $this->assertArrayNotHasKey(ShipDef::KEY_TRAVEL_DRIVE_STABILITY, $record);

        // This macro has no loadout.
        $this->assertArrayNotHasKey(ShipDef::KEY_DEFAULT_LOADOUT, $record);
    }

    public function test_realShipWithDefaultLoadout() : void
    {
        $record = $this->extractRealShip('ship_kha_l_destroyer_01_a');

        $this->assertSame(
            array(
                array('type' => 'engine', 'macroID' => 'engine_kha_l_destroyer_01_allround_01_mk1_macro', 'count' => 1, 'optional' => false),
                array('type' => 'shield', 'macroID' => 'shield_kha_l_standard_01_mk1_macro', 'count' => 3, 'optional' => true),
                array('type' => 'weapon', 'macroID' => 'weapon_kha_l_beam_01_mk1_macro', 'count' => 1, 'optional' => true),
                array('type' => 'turret', 'macroID' => 'turret_kha_l_beam_01_mk1_macro', 'count' => 8, 'optional' => false),
                array('type' => 'thruster', 'macroID' => 'thruster_gen_l_allround_01_mk3_macro', 'count' => 1, 'optional' => false),
            ),
            $record[ShipDef::KEY_DEFAULT_LOADOUT]
        );
    }

    public function test_aliasShips() : void
    {
        $pirate = $this->extractRealShip('ship_pir_l_scavenger_01_a_storyhighcapacity');
        $this->assertSame('ship_pir_l_scavenger_01_a_macro', $pirate[ShipDef::KEY_ALIAS_MACRO_ID]);
        $this->assertTrue($pirate[ShipDef::KEY_HAS_OWN_ACC_FACTORS], 'The pirate story ship has its own physics.');

        $boron = $this->extractRealShip('ship_bor_s_miner_solid_01_story');
        $this->assertSame('ship_bor_s_miner_solid_01_a_macro', $boron[ShipDef::KEY_ALIAS_MACRO_ID]);
        $this->assertFalse($boron[ShipDef::KEY_HAS_OWN_ACC_FACTORS], 'The alias target is never inspected.');

        // The alias ship falls back to the values of its target.
        $target = $this->extractRealShip('ship_bor_s_miner_solid_01_a');
        foreach(array(ShipDef::KEY_PURPOSE_ID, ShipDef::KEY_THRUSTER_SIZE, ShipDef::KEY_STEERING_CURVE, ShipDef::KEY_HANGAR_CAPACITY, ShipDef::KEY_DEFAULT_SOFTWARE_IDS) as $key) {
            $this->assertSame($target[$key], $boron[$key], $key);
        }
    }

    public function test_aliasFallbackAndOwnPrecedence() : void
    {
        $extractor = new ShipMacroPropertiesExtractor(
            DOMExtended::createFromString(self::OWN_FIXTURE_XML),
            DOMExtended::createFromString(self::ALIAS_FIXTURE_XML),
            'fixture_alias_target_macro',
            'vanilla',
            'fixture_alias'
        );

        $record = $extractor->extract();

        $this->assertSame('fixture_alias_target_macro', $record[ShipDef::KEY_ALIAS_MACRO_ID]);
        $this->assertFalse($record[ShipDef::KEY_HAS_OWN_ACC_FACTORS]);

        // Own values win, the rest comes from the alias target.
        $this->assertSame('own_icon', $record[ShipDef::KEY_ICON_ID]);
        $this->assertSame(4, $record[ShipDef::KEY_SECRECY_LEVEL]);
        $this->assertSame('trade', $record[ShipDef::KEY_PURPOSE_ID]);
        $this->assertSame('l', $record[ShipDef::KEY_THRUSTER_SIZE]);
        $this->assertSame('{9,1}', $record[ShipDef::KEY_BASE_NAME_ID]);
        $this->assertCount(2, $record[ShipDef::KEY_STEERING_CURVE]);
        $this->assertSame('alias_engine_macro', $record[ShipDef::KEY_DEFAULT_LOADOUT][0][ShipLoadoutItem::KEY_MACRO_ID]);
    }

    public function test_unknownPurposeKeepsRawValue() : void
    {
        list($record, $output) = $this->extractFixtureWithOutput();

        $this->assertSame('no_such_purpose', $record[ShipDef::KEY_PURPOSE_ID]);
        $this->assertStringContainsString('WARNING | Unknown purpose [no_such_purpose]', $output);
    }

    public function test_unknownThrusterTagKeepsRawValue() : void
    {
        list($record, $output) = $this->extractFixtureWithOutput();

        $this->assertSame('gigantic', $record[ShipDef::KEY_THRUSTER_SIZE]);
        $this->assertStringContainsString('WARNING | Unknown thruster size tag [gigantic]', $output);
    }

    public function test_thrusterTagsMapToSizeIDs() : void
    {
        $map = array('small' => 's', 'medium' => 'm', 'large' => 'l', 'extralarge' => 'xl');

        foreach($map as $tag => $size) {
            $xml = '<macros><macro name="x"><properties><thruster tags="'.$tag.'" /></properties></macro></macros>';
            $record = (new ShipMacroPropertiesExtractor(DOMExtended::createFromString($xml), null, null, 'vanilla', 'x'))->extract();
            $this->assertSame($size, $record[ShipDef::KEY_THRUSTER_SIZE], $tag);
        }
    }

    public function test_fixtureScalarValues() : void
    {
        list($record, $output) = $this->extractFixtureWithOutput();

        $this->assertFalse($record[ShipDef::KEY_CAPTURABLE]);
        $this->assertTrue($record[ShipDef::KEY_PLAYER_COVERABLE]);
        $this->assertSame(4, $record[ShipDef::KEY_PRESTIGE]);
        $this->assertSame(3, $record[ShipDef::KEY_DRONE_CAPACITY]);
        $this->assertSame(0, $record[ShipDef::KEY_DEPLOYABLE_CAPACITY]);
        $this->assertSame(0, $record[ShipDef::KEY_COUNTERMEASURE_CAPACITY]);
        $this->assertSame(25000.0, $record[ShipDef::KEY_RADAR_RANGE]);
        $this->assertSame(2.5, $record[ShipDef::KEY_BOOST_RECHARGE_DELAY]);
        $this->assertSame(1.5, $record[ShipDef::KEY_BOOST_MIN_DURATION]);
        $this->assertSame(7, $record[ShipDef::KEY_THREAT_SCORE]);
        $this->assertSame(0.5, $record[ShipDef::KEY_TRAVEL_DRIVE_STABILITY]);
        $this->assertSame(1.25, $record[ShipDef::KEY_GAS_GATHER_RATE]);
        $this->assertSame(3, $record[ShipDef::KEY_SECRECY_LEVEL]);
        $this->assertSame(1.5, $record[ShipDef::KEY_WEAPON_HEAT_MODIFIER]);
        $this->assertSame(2.0, $record[ShipDef::KEY_SHIELD_CAPACITY_MODIFIER]);
        $this->assertSame(0.5, $record[ShipDef::KEY_SHIELD_RECHARGE_RATE_MODIFIER]);
        $this->assertSame(3.0, $record[ShipDef::KEY_SHIELD_RECHARGE_DELAY_MODIFIER]);
        $this->assertSame(100.0, $record[ShipDef::KEY_EXPLOSION_DAMAGE_HULL]);
        $this->assertArrayNotHasKey(ShipDef::KEY_EXPLOSION_DAMAGE_SHIELD, $record);
        $this->assertSame(30.0, $record[ShipDef::KEY_EXPLOSION_SHIELD_DISRUPTION]);
        $this->assertTrue($record[ShipDef::KEY_HAS_OWN_ACC_FACTORS]);
        $this->assertSame('fixture_icon', $record[ShipDef::KEY_ICON_ID]);

        // Raw text codes, not translated.
        $this->assertSame('{1,1}', $record[ShipDef::KEY_BASE_NAME_ID]);
        $this->assertSame('{1,2}', $record[ShipDef::KEY_VARIATION_NAME_ID]);
        $this->assertArrayNotHasKey(ShipDef::KEY_SHORT_VARIATION_NAME_ID, $record);

        // The `<thruster macro>` element outside of the loadout is not a ship property.
        $this->assertSame('gigantic', $record[ShipDef::KEY_THRUSTER_SIZE]);

        $this->assertSame(array(), $record[ShipDef::KEY_STEERING_CURVE]);
        $this->assertSame(array(), $record[ShipDef::KEY_HANGAR_CAPACITY]);
        $this->assertArrayNotHasKey(ShipDef::KEY_ALIAS_MACRO_ID, $record);
    }

    public function test_absentElementsAreOmitted() : void
    {
        $xml = '<macros><macro name="x"><properties><purpose primary="fight" /></properties></macro></macros>';
        $record = (new ShipMacroPropertiesExtractor(DOMExtended::createFromString($xml), null, null, 'vanilla', 'x'))->extract();

        foreach(array(
            ShipDef::KEY_SECRECY_LEVEL, ShipDef::KEY_EXPLOSION_DAMAGE_HULL, ShipDef::KEY_DEPLOYABLE_CAPACITY,
            ShipDef::KEY_COUNTERMEASURE_CAPACITY, ShipDef::KEY_TRAVEL_DRIVE_STABILITY, ShipDef::KEY_GAS_GATHER_RATE,
            ShipDef::KEY_RADAR_RANGE, ShipDef::KEY_WEAPON_HEAT_MODIFIER, ShipDef::KEY_BASE_NAME_ID, ShipDef::KEY_PRESTIGE,
            ShipDef::KEY_BOOST_RECHARGE_DELAY, ShipDef::KEY_THREAT_SCORE, ShipDef::KEY_DEFAULT_LOADOUT
        ) as $key) {
            $this->assertArrayNotHasKey($key, $record, $key);
        }

        $this->assertFalse($record[ShipDef::KEY_HAS_OWN_ACC_FACTORS]);
        $this->assertTrue($record[ShipDef::KEY_CAPTURABLE]);
        $this->assertFalse($record[ShipDef::KEY_PLAYER_COVERABLE]);
        $this->assertSame(0, $record[ShipDef::KEY_DRONE_CAPACITY]);

        // The record round-trips through the value object.
        $this->assertSame(
            $record[ShipDef::KEY_PURPOSE_ID],
            ShipMacroProperties::fromArray($record)->getPurposeID()
        );
    }

    public function test_softwareMissingFromWareDataLogsNoticeAndIsKept() : void
    {
        list($record, $output) = $this->extractFixtureWithOutput();

        $this->assertSame(array('software_flightassistmk1'), $record[ShipDef::KEY_DEFAULT_SOFTWARE_IDS]);
        $this->assertSame(array('software_does_not_exist'), $record[ShipDef::KEY_OPTIONAL_SOFTWARE_IDS]);
        $this->assertStringContainsString('NOTICE | Software ware [software_does_not_exist]', $output);
        $this->assertStringNotContainsString('Software ware [software_flightassistmk1]', $output);
    }

    public function test_defaultLoadoutIsFlattenedAndMerged() : void
    {
        list($record, $output) = $this->extractFixtureWithOutput();

        $expected = array(
            array('type' => 'engine', 'macroID' => 'engine_a_macro', 'count' => 2, 'optional' => false),
            array('type' => 'weapon', 'macroID' => 'weapon_a_macro', 'count' => 1, 'optional' => true),
            array('type' => 'weapon', 'macroID' => 'weapon_a_macro', 'count' => 1, 'optional' => false),
            array('type' => 'turret', 'macroID' => 'turret_a_macro', 'count' => 5, 'optional' => false),
            array('type' => 'shield', 'macroID' => 'shield_a_macro', 'count' => 5, 'optional' => true),
            array('type' => 'thruster', 'macroID' => 'thruster_a_macro', 'count' => 1, 'optional' => false),
            array('type' => 'software', 'macroID' => 'software_flightassistmk1', 'count' => 1, 'optional' => false),
            array('type' => 'ammunition', 'macroID' => 'ammo_a_macro', 'count' => 40, 'optional' => true),
        );

        $this->assertSame($expected, $record[ShipDef::KEY_DEFAULT_LOADOUT]);

        $this->assertStringContainsString('Unknown loadout element [macros/bogus]', $output);
        $this->assertStringContainsString('Unknown loadout element [unknownthing]', $output);
        $this->assertStringContainsString('has no macro, skipped', $output);
    }

    public function test_hiddenHangarBayIsSkipped() : void
    {
        $xml = <<<'XML'
<macros>
  <macro name="x">
    <properties><purpose primary="fight" /></properties>
    <connections>
      <connection ref="con_shipstorage_xs_01"><macro ref="shipstorage_gen_xs_01_macro" connection="object" /></connection>
      <connection ref="con_shipstorage_hidden"><macro ref="shipstorage_gen_sm_hidden_sixtyfour_macro" connection="object" /></connection>
    </connections>
  </macro>
</macros>
XML;

        $record = (new ShipMacroPropertiesExtractor(DOMExtended::createFromString($xml), null, null, 'vanilla', 'x'))->extract();

        $this->assertSame(array('xs' => 10), $record[ShipDef::KEY_HANGAR_CAPACITY]);
    }

    public function test_hangarFallsBackToAliasConnections() : void
    {
        $own = '<macros><macro name="x"><properties><purpose primary="fight" /></properties></macro></macros>';
        $alias = <<<'XML'
<macros>
  <macro name="y">
    <connections>
      <connection ref="con_shipstorage_xs_01"><macro ref="shipstorage_gen_xs_01_macro" connection="object" /></connection>
      <connection ref="con_shipstorage_xs_02"><macro ref="shipstorage_gen_xs_01_macro" connection="object" /></connection>
    </connections>
  </macro>
</macros>
XML;

        $record = (new ShipMacroPropertiesExtractor(
            DOMExtended::createFromString($own),
            DOMExtended::createFromString($alias),
            'y',
            'vanilla',
            'x'
        ))->extract();

        $this->assertSame(array('xs' => 20), $record[ShipDef::KEY_HANGAR_CAPACITY]);
    }
}
