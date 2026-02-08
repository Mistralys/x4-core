<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Extractors;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Wares\WareDef;
use Mistral\X4\Database\Wares\WaresExtractor;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

class WaresExtractorTests extends X4TestCase
{
    private string $dataFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        $folder = X4Application::getDataFolder();
        $this->dataFile = $folder->getPath().'/wares.json';
    }

    public function test_extract() : void
    {
        $extractor = new WaresExtractor(DatabaseBuilder::getDataFolders());
        $extractor->extract();

        $this->assertFileExists($this->dataFile, 'Wares file not created.');
    }
    
    public function test_outputFileCreated() : void
    {
        $this->assertFileExists($this->dataFile);
    }

    public function test_extractedDataValid() : void
    {
        $map = $this->getDataMap();
        $this->assertNotEmpty($map, 'Wares data is empty or invalid');
        
        $ware = $map['energycells'] ?? null;
        $this->assertNotNull($ware, 'Energy Cells missing');
        
        $this->assertArrayHasKey(WareDef::KEY_WARE_ID, $ware);
        $this->assertArrayHasKey(WareDef::KEY_LABEL, $ware);
        $this->assertArrayHasKey(WareDef::KEY_GROUP, $ware);
    }

    public function test_dataIntegrity() : void
    {
        $map = $this->getDataMap();
        
        $this->assertGreaterThan(100, count($map), 'Too few wares extracted.');
        $this->assertArrayHasKey('hullparts', $map);
        $this->assertArrayHasKey('claytronics', $map);
        $this->assertArrayHasKey('foodrations', $map);
        
        // Terran wares
        $this->assertArrayHasKey('computronicsubstrate', $map);
    }
    
    private function getDataMap() : array
    {
        $json = file_get_contents($this->dataFile);
        $data = json_decode($json, true);
        $this->assertIsArray($data, 'JSON decode failed or data is not an array');
        
        $map = [];
        foreach($data as $item) {
            if(isset($item[WareDef::KEY_WARE_ID])) {
                $map[$item[WareDef::KEY_WARE_ID]] = $item;
            }
        }
        return $map;
    }
}
