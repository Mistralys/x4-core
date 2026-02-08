<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Extractors;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Modules\ModuleDef;
use Mistralys\X4\Database\Modules\ModuleExtractor;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

class ModuleExtractorTests extends X4TestCase
{
    private string $dataFile;

    protected function setUp(): void
    {
        parent::setUp();
        
        $folder = X4Application::getDataFolder();
        $this->dataFile = $folder->getPath().'/modules.json';
    }

    public function test_extract() : void
    {
        // Pre-condition check
        $wares = WareDefs::getInstance();
        $moduleWares = $wares->findWares()->selectGroup(WareGroups::GROUP_MODULES)->getAll();
        if (empty($moduleWares)) {
            $this->markTestSkipped('No module wares found in wares.json. Run WaresExtractor first.');
        }

        $extractor = new ModuleExtractor(DatabaseBuilder::getDataFolders());
        $extractor->extract();

        $this->assertFileExists($this->dataFile, 'Modules file not created.');
    }
    
    public function test_outputFileCreated() : void
    {
        $this->assertFileExists($this->dataFile);
    }

    public function test_extractedDataValid() : void
    {
        $map = $this->getDataMap();
        $this->assertNotEmpty($map, 'Modules data is empty or invalid');
        
        // Find a dock module
        $mod = $map['module_arg_dock_m_01_a_macro'] ?? null;
        if(!$mod) {
             // Try searching values if key is wrong
             foreach($map as $item) {
                 if(str_contains($item[ModuleDef::KEY_WARE_ID] ?? '', 'dock')) {
                     $mod = $item;
                     break;
                 }
             }
        }
        
        $this->assertNotNull($mod, 'No module found related to dock');
        
        $this->assertArrayHasKey(ModuleDef::KEY_WARE_ID, $mod);
        $this->assertArrayHasKey(ModuleDef::KEY_MACRO_ID, $mod);
        $this->assertArrayHasKey(ModuleDef::KEY_CATEGORY, $mod);
    }

    public function test_dataIntegrity() : void
    {
        $map = $this->getDataMap();
        
        $this->assertGreaterThan(50, count($map), 'Too few modules extracted.');
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
             if(isset($item[ModuleDef::KEY_WARE_ID])) { // using proper key constant from ModuleDef if available there, or assume 'wareID'
                 $map[$item[ModuleDef::KEY_WARE_ID]] = $item;
             } elseif (isset($item['wareID'])) {
                 $map[$item['wareID']] = $item;
             }
        }
        return $map;
    }
}
