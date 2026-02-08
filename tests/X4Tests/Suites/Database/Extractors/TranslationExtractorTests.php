<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Extractors;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

class TranslationExtractorTests extends X4TestCase
{
    private string $englishFile;
    private int $englishID;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->englishID = 44; // 044
        $file = TranslationExtractor::getLanguageFile($this->englishID);
        $this->englishFile = $file->getPath();
    }

    public function test_extract() : void
    {
        // Extract English only to save time
        $extractor = new TranslationExtractor(DatabaseBuilder::getDataFolders());
        $extractor->selectLanguage($this->englishID);
        $extractor->extract();

        $this->assertFileExists($this->englishFile, 'English translation file not created.');
    }
    
    public function test_outputFileCreated() : void
    {
        $this->assertFileExists($this->englishFile);
    }

    public function test_extractedDataValid() : void
    {
        $json = file_get_contents($this->englishFile);
        $data = json_decode($json, true);
        
        $this->assertIsArray($data, 'JSON decode failed');
        $this->assertNotEmpty($data, 'Translation data is empty');
        
        // Structure is PageID => TextID => Text
        // Example: page 20001 (Game info)
        // Note: JSON keys are strings, but checking with integer might work depending on PHP version or just cast
        // Let's inspect known pages. 20101, 20203.
        
        $foundPage = false;
        if(isset($data[20001]) || isset($data['20001'])) {
            $foundPage = true;
        } elseif(isset($data[20101]) || isset($data['20101'])) {
            $foundPage = true;
        }
        
        $this->assertTrue($foundPage, 'No known translation pages found (20001 or 20101)');
    }

    public function test_dataIntegrity() : void
    {
        $json = file_get_contents($this->englishFile);
        $data = json_decode($json, true);
        
        // Page 20203 has ware names
        $pageKey = isset($data[20203]) ? 20203 : (isset($data['20203']) ? '20203' : null);
        
        $this->assertNotNull($pageKey, 'Page 20203 (Ware names) not found');
        $page = $data[$pageKey];
        
        // Text ID might be arbitrary but some should exist
        $this->assertNotEmpty($page);
        
        // Sample validation
        $foundText = false;
        foreach($page as $text) {
            if (is_string($text) && strlen($text) > 0) {
                $foundText = true;
                break;
            }
        }
        $this->assertTrue($foundText, 'No text strings found in page 20203');
    }
}
