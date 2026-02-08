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
