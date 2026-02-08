<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Extractors;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionsExtractor;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

class FactionsExtractorTests extends X4TestCase
{
    private string $dataFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        $folder = X4Application::getDataFolder();
        $this->dataFile = $folder->getPath().'/factions.json';
    }

    public function test_extract() : void
    {
        $extractor = new FactionsExtractor(DatabaseBuilder::getDataFolders());
        $extractor->extract();

        $this->assertFileExists($this->dataFile, 'Factions file not created.');
    }
    
    public function test_outputFileCreated() : void
    {
        $this->assertFileExists($this->dataFile);
    }

    public function test_extractedDataValid() : void
    {
        $map = $this->getDataMap();
        $this->assertNotEmpty($map, 'Factions data is empty or invalid');
        
        $ant = $map['antigone'] ?? null;
        $this->assertNotNull($ant, 'Antigone faction missing');
        
        $this->assertArrayHasKey(FactionDef::KEY_ID, $ant);
        $this->assertArrayHasKey(FactionDef::KEY_NAME, $ant);
        $this->assertArrayHasKey(FactionDef::KEY_DATA_SOURCE_ID, $ant);
    }

    public function test_dataIntegrity() : void
    {
        $map = $this->getDataMap();
        
        $this->assertGreaterThan(30, count($map), 'Too few factions extracted.');
        $this->assertArrayHasKey('argon', $map);
        $this->assertArrayHasKey('xenon', $map);
        $this->assertArrayHasKey('khaak', $map);
        
        // DLC factions
        $this->assertArrayHasKey('zyarth', $map); 
        $this->assertArrayHasKey('terran', $map);
        $this->assertArrayHasKey('pioneers', $map);
        $this->assertArrayHasKey('boron', $map);
    }
    
    private function getDataMap() : array
    {
        $json = file_get_contents($this->dataFile);
        $data = json_decode($json, true);
        $this->assertIsArray($data, 'JSON decode failed or data is not an array');
        
        $map = [];
        foreach($data as $item) {
            if(isset($item[FactionDef::KEY_ID])) {
                $map[$item[FactionDef::KEY_ID]] = $item;
            }
        }
        return $map;
    }
}
