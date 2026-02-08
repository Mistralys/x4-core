<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Extractors;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\MacroIndex\MacroFileDef;
use Mistralys\X4\Database\MacroIndex\MacroIndexExtractor;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

class MacroIndexExtractorTests extends X4TestCase
{
    private string $dataFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        $folder = X4Application::getDataFolder();
        $this->dataFile = $folder->getPath().'/macro-index.json';
    }

    public function test_extract() : void
    {
        $extractor = new MacroIndexExtractor(DatabaseBuilder::getDataFolders());
        $extractor->extract();

        $this->assertFileExists($this->dataFile, 'Macro index file not created.');
    }
    
    public function test_outputFileCreated() : void
    {
        $this->assertFileExists($this->dataFile);
    }

    public function test_extractedDataValid() : void
    {
        $map = $this->getDataMap();
        $this->assertNotEmpty($map, 'Macro index data is empty or invalid');
        
        // Check for a common macro name, e.g. player ship connection or standard ship macro
        // Since names are unique per file but maybe not globally unique without folder, 
        // the extractor logic uses name as key: $this->macros[$name] = ...
        // So the last one wins if duplicates exist?
        // Wait, looking at extractor: $this->macros[$name] = array(...)
        // Yes, it overwrites. Which assumes unique macro names globally? 
        // Or that they only care about one resolution.
        
        // Let's just check for any valid entry
        $first = reset($map);
        $this->assertIsArray($first);
        $this->assertArrayHasKey(MacroFileDef::KEY_NAME, $first);
        $this->assertArrayHasKey(MacroFileDef::KEY_DATA_FOLDER, $first);
        $this->assertArrayHasKey(MacroFileDef::KEY_FULL_PATH, $first);
    }

    public function test_dataIntegrity() : void
    {
        $map = $this->getDataMap();
        
        // Ensure we have a significant number of macros (game has thousands)
        $this->assertGreaterThan(1000, count($map), 'Too few macros extracted.');
    }

    public function test_extractionFromGameFiles() : void
    {
        // This implicitly tests reading from game files since default validation passed
        $map = $this->getDataMap();
        $this->assertArrayHasKey('ship_arg_s_fighter_01_a_macro', $map);
    }
    
    private function getDataMap() : array
    {
        $json = file_get_contents($this->dataFile);
        $data = json_decode($json, true);
        $this->assertIsArray($data, 'JSON decode failed or data is not an array');
        
        $map = [];
        foreach($data as $item) {
            if(isset($item[MacroFileDef::KEY_NAME])) {
                $map[$item[MacroFileDef::KEY_NAME]] = $item;
            }
        }
        return $map;
    }
}
