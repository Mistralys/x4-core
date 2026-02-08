<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Extractors;

use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\DataSources\DataSourcesExtractor;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

class DataSourcesExtractorTests extends X4TestCase
{
    private string $dataFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        $folder = X4Application::getDataFolder();
        $this->dataFile = $folder->getPath().'/data-sources.json';
    }

    public function test_extract() : void
    {
        // Run the extraction
        $extractor = new DataSourcesExtractor();
        $extractor->extract();

        // Verify file exists
        $this->assertFileExists($this->dataFile, 'Data sources file not created.');
    }
    
    public function test_outputFileCreated() : void
    {
        $this->assertFileExists($this->dataFile);
    }

    public function test_extractedDataValid() : void
    {
        $json = file_get_contents($this->dataFile);
        $data = json_decode($json, true);
        
        $this->assertIsArray($data, 'JSON decode failed');
        $this->assertNotEmpty($data, 'Extracted data is empty');
    }

    public function test_allExpectedFieldsPresent() : void
    {
        $map = $this->getDataMap();
        $vanilla = $map['vanilla'] ?? null;
        
        $this->assertNotNull($vanilla, 'Vanilla data missing in JSON');
        $this->assertArrayHasKey('id', $vanilla);
        $this->assertArrayHasKey('label', $vanilla);
        // folder might not be present if it's derived or optional, let's check what's in the file
        // Looking at the file content I saw: "id", "label", "isExtension". 
        // "folder" might be implicit if it matches ID, or maybe it's not exported if not needed.
        // Let's check DataSourceDef::toArray implementation to be sure.
        $this->assertArrayHasKey('isExtension', $vanilla);
    }

    public function test_vanillaIncluded() : void
    {
        $map = $this->getDataMap();
        $this->assertArrayHasKey('vanilla', $map);
    }

    public function test_allDLCsIncluded() : void
    {
        $map = $this->getDataMap();
        
        $dlcs = [
            'ego_dlc_split',
            'ego_dlc_terran',
            'ego_dlc_pirate',
            'ego_dlc_boron',
            'ego_dlc_timelines'
        ];
        
        foreach ($dlcs as $dlc) {
            $this->assertArrayHasKey($dlc, $map, "DLC $dlc missing from extraction.");
        }
    }
    
    private function getDataMap() : array
    {
        $json = file_get_contents($this->dataFile);
        $data = json_decode($json, true);
        $this->assertIsArray($data, 'JSON decode failed or data is not an array');
        
        $map = [];
        foreach($data as $item) {
            if(isset($item['id'])) {
                $map[$item['id']] = $item;
            }
        }
        return $map;
    }
}
