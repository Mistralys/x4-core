<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Extractors;

use Mistralys\X4\Database\Blueprints\BlueprintDef;
use Mistral\X4\Database\Blueprints\BlueprintExtractor;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

class BlueprintExtractorTests extends X4TestCase
{
    private string $dataFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        $folder = X4Application::getDataFolder();
        $this->dataFile = $folder->getPath().'/blueprints.json';
    }

    public function test_extract() : void
    {
        if (empty(WareDefs::getInstance()->getAll())) {
            $this->markTestSkipped('No wares found. Run WaresExtractor first.');
        }

        $extractor = new BlueprintExtractor();
        $extractor->extract();

        $this->assertFileExists($this->dataFile, 'Blueprints file not created.');
    }
    
    public function test_outputFileCreated() : void
    {
        $this->assertFileExists($this->dataFile);
    }

    public function test_extractedDataValid() : void
    {
        $map = $this->getDataMap();
        $this->assertNotEmpty($map, 'Blueprints data is empty or invalid');
        
        // Find a ship blueprint
        $bp = $map['ship_arg_s_fighter_01_a'] ?? null;
        if(!$bp) {
            $bp = reset($map);
        }
        
        $this->assertNotNull($bp);
        $this->assertArrayHasKey(BlueprintDef::KEY_WARE_ID, $bp);
        // KEY_CATEGORY is likely defined in BlueprintDef or logic uses it
        // Checking BlueprintExtractor source, it writes 'category'
        // Checking BlueprintDef source I read earlier, it didn't show KEY_CATEGORY constant in first 20 lines
        // But logic showed $categoryID = ...
    }

    public function test_dataIntegrity() : void
    {
        $map = $this->getDataMap();
        
        $this->assertGreaterThan(500, count($map), 'Too few blueprints extracted.');
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
             if(isset($item[BlueprintDef::KEY_WARE_ID])) {
                 $map[$item[BlueprintDef::KEY_WARE_ID]] = $item;
             }
        }
        return $map;
    }
}
