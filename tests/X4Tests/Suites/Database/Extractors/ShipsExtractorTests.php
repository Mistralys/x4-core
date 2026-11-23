<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Extractors;

use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipsExtractor;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

class ShipsExtractorTests extends X4TestCase
{
    private string $dataFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        $folder = X4Application::getDataFolder();
        $this->dataFile = $folder->getPath().'/ships.json';
    }

    public function test_extract() : void
    {
        if (empty(WareDefs::getInstance()->getAll())) {
            $this->markTestSkipped('No wares found. Run WaresExtractor first.');
        }

        $extractor = new ShipsExtractor();
        $extractor->extract();

        $this->assertFileExists($this->dataFile, 'Ships file not created.');
    }
    
    public function test_outputFileCreated() : void
    {
        $this->assertFileExists($this->dataFile);
    }

    public function test_extractedDataValid() : void
    {
        $map = $this->getDataMap();
        $this->assertNotEmpty($map, 'Ships data is empty or invalid');
        
        $shipID = 'ship_arg_s_fighter_01_a';
        $ship = $map[$shipID] ?? null;
        
        $this->assertNotNull($ship, 'Argon Fighter 01 (ship_arg_s_fighter_01_a) not found in extraction.');
        
        $this->assertArrayHasKey(ShipDef::KEY_WARE_ID, $ship);
        $this->assertArrayHasKey(ShipDef::KEY_CLASS_ID, $ship);
        $this->assertArrayHasKey(ShipDef::KEY_SIZE, $ship);

        // Verification of properties added in this session
        $this->assertGreaterThan(0, $ship[ShipDef::KEY_HULL], 'Hull value should be > 0');
        $this->assertGreaterThan(0, $ship[ShipDef::KEY_MASS], 'Mass value should be > 0');
        
        // Slots verification
        $slots = $ship[ShipDef::KEY_SLOTS] ?? [];
        $this->assertIsArray($slots, 'Slots should be an array');
        $this->assertNotEmpty($slots, 'Slots array is empty - component resolution likely failed.');
        
        $this->assertArrayHasKey('weapon', $slots, 'Missing weapon slots key');
        $this->assertGreaterThan(0, $slots['weapon'], 'Should have at least 1 weapon slot');
        
        $this->assertArrayHasKey('shield', $slots, 'Missing shield slots key');
        $this->assertGreaterThan(0, $slots['shield'], 'Should have at least 1 shield slot');
    }

    public function test_dataIntegrity() : void
    {
        $map = $this->getDataMap();
        
        $this->assertGreaterThan(50, count($map), 'Too few ships extracted.');
    }
    
    public function test_extractedDataContainsMacroFields() : void
    {
        $ship = $this->getDataMap()['ship_arg_s_fighter_01_a'];

        foreach(array(
            ShipDef::KEY_MACRO_ID,
            ShipDef::KEY_CARGO_MACRO_ID,
            ShipDef::KEY_HAS_OWN_ACC_FACTORS,
            ShipDef::KEY_STEERING_CURVE,
            ShipDef::KEY_PURPOSE_ID,
            ShipDef::KEY_THRUSTER_SIZE,
            ShipDef::KEY_ICON_ID,
            ShipDef::KEY_DRONE_CAPACITY,
            ShipDef::KEY_HANGAR_CAPACITY,
            ShipDef::KEY_DEFAULT_SOFTWARE_IDS,
            ShipDef::KEY_OPTIONAL_SOFTWARE_IDS,
            ShipDef::KEY_CAPTURABLE,
            ShipDef::KEY_PLAYER_COVERABLE,
        ) as $key) {
            $this->assertArrayHasKey($key, $ship, $key);
        }

        $this->assertSame('ship_arg_s_fighter_01_a_macro', $ship[ShipDef::KEY_MACRO_ID]);
    }

    /**
     * The records hold only plain values: nesting is limited to the
     * keys that are documented as structures, and nothing holds an object.
     */
    public function test_noNestedGameStructures() : void
    {
        $structureKeys = array(
            ShipDef::KEY_USED_BY,
            ShipDef::KEY_BUILDER_FACTION_IDS,
            ShipDef::KEY_VARIANTS,
            ShipDef::KEY_SLOTS,
            ShipDef::KEY_EQUIPMENT,
            ShipDef::KEY_STEERING_CURVE,
            ShipDef::KEY_HANGAR_CAPACITY,
            ShipDef::KEY_DEFAULT_SOFTWARE_IDS,
            ShipDef::KEY_OPTIONAL_SOFTWARE_IDS,
            ShipDef::KEY_DEFAULT_LOADOUT,
        );

        foreach($this->getDataMap() as $shipID => $ship) {
            foreach($ship as $key => $value) {
                if(is_array($value)) {
                    $this->assertContains($key, $structureKeys, sprintf('Ship [%s]: unexpected structure in [%s].', $shipID, $key));
                    continue;
                }

                $this->assertTrue(is_scalar($value) || $value === null, sprintf('Ship [%s]: [%s] is not a plain value.', $shipID, $key));
            }
        }
    }

    private function getDataMap() : array
    {
        if(!file_exists($this->dataFile)) {
            return [];
        }
        
        $json = file_get_contents($this->dataFile);
        $data = json_decode($json, true);
        $this->assertIsArray($data, 'JSON decode failed or data is not an array');
        
        $map = [];
        foreach($data as $item) {
             if(isset($item[ShipDef::KEY_WARE_ID])) {
                 $map[$item[ShipDef::KEY_WARE_ID]] = $item;
             }
        }
        return $map;
    }
}
