<?php
/**
 * @package X4Tests
 * @subpackage Core
 * @see \Mistralys\X4\Database\DatabaseBuilder
 */

declare(strict_types=1);

namespace X4Tests\Suites\Core;

use Mistralys\X4\Database\DatabaseBuilder;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the DatabaseBuilder class which orchestrates
 * the extraction and building of all game data collections.
 *
 * @package X4Tests
 * @subpackage Core
 */
final class DatabaseBuilderTests extends X4TestCase
{
    /**
     * Test that all data files exist after build process
     */
    public function test_allDataFilesExist(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        
        $expectedFiles = [
            'blueprints.json',
            'data-sources.json',
            'factions.json',
            'macro-index.json',
            'modules.json',
            'ships.json',
            'ship-settings.json',
            'wares.json',
            'lang-007-ru_RU.json',
            'lang-033-fr_FR.json',
            'lang-034-es_ES.json',
            'lang-039-it_IT.json',
            'lang-044-en_EN.json',
            'lang-049-de_DE.json',
            'lang-082-ko_KR.json'
        ];

        foreach ($expectedFiles as $file) {
            $filePath = $dataFolder.'/'.$file;
            $this->assertFileExists(
                $filePath,
                sprintf('Expected data file [%s] not found.', $file)
            );
        }
    }

    /**
     * Test that data files contain valid JSON
     */
    public function test_dataFilesContainValidJSON(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        
        $jsonFiles = [
            'blueprints.json',
            'data-sources.json',
            'factions.json',
            'macro-index.json',
            'modules.json',
            'ships.json',
            'ship-settings.json',
            'wares.json',
            'lang-007-ru_RU.json',
            'lang-033-fr_FR.json',
            'lang-034-es_ES.json',
            'lang-039-it_IT.json',
            'lang-044-en_EN.json',
            'lang-049-de_DE.json',
            'lang-082-ko_KR.json'
        ];

        foreach ($jsonFiles as $file) {
            $filePath = $dataFolder.'/'.$file;
            
            if (!file_exists($filePath)) {
                $this->markTestSkipped(sprintf('Data file [%s] not found.', $file));
            }

            $content = file_get_contents($filePath);
            $decoded = json_decode($content, true);

            $this->assertNotNull(
                $decoded,
                sprintf('File [%s] does not contain valid JSON. Error: %s', $file, json_last_error_msg())
            );
        }
    }

    /**
     * Test that data files are not empty
     */
    public function test_dataFilesNotEmpty(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        
        $jsonFiles = [
            'blueprints.json',
            'data-sources.json',
            'factions.json',
            'macro-index.json',
            'modules.json',
            'ships.json',
            'wares.json'
        ];

        foreach ($jsonFiles as $file) {
            $filePath = $dataFolder.'/'.$file;
            
            if (!file_exists($filePath)) {
                $this->markTestSkipped(sprintf('Data file [%s] not found.', $file));
            }

            $content = file_get_contents($filePath);
            $decoded = json_decode($content, true);

            $this->assertNotEmpty(
                $decoded,
                sprintf('File [%s] is empty or contains no data.', $file)
            );
        }
    }

    /**
     * Test that translation files exist for all expected languages
     */
    public function test_allLanguageFilesExist(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        
        $expectedLanguages = [
            '007' => 'ru_RU', // Russian
            '033' => 'fr_FR', // French
            '034' => 'es_ES', // Spanish
            '039' => 'it_IT', // Italian
            '044' => 'en_EN', // English
            '049' => 'de_DE', // German
            '082' => 'ko_KR'  // Korean
        ];

        foreach ($expectedLanguages as $langID => $locale) {
            $file = sprintf('lang-%s-%s.json', $langID, $locale);
            $filePath = $dataFolder.'/'.$file;
            
            $this->assertFileExists(
                $filePath,
                sprintf('Expected language file [%s] not found.', $file)
            );
        }
    }

    /**
     * Test that data-sources.json contains vanilla and all DLCs
     */
    public function test_dataSourcesContainsExpectedEntries(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        $filePath = $dataFolder.'/data-sources.json';

        if (!file_exists($filePath)) {
            $this->markTestSkipped('data-sources.json not found.');
        }

        $content = file_get_contents($filePath);
        $dataSources = json_decode($content, true);

        $expectedSources = [
            'vanilla',
            'ego_dlc_boron',
            'ego_dlc_split',
            'ego_dlc_terran',
            'ego_dlc_pirate',
            'ego_dlc_timelines'
        ];

        $foundSources = [];
        foreach ($dataSources as $source) {
            if (isset($source['id'])) {
                $foundSources[] = $source['id'];
            }
        }

        foreach ($expectedSources as $sourceID) {
            $this->assertContains(
                $sourceID,
                $foundSources,
                sprintf('Expected data source [%s] not found in data-sources.json.', $sourceID)
            );
        }
    }

    /**
     * Test that factions.json contains expected factions
     */
    public function test_factionsContainsExpectedEntries(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        $filePath = $dataFolder.'/factions.json';

        if (!file_exists($filePath)) {
            $this->markTestSkipped('factions.json not found.');
        }

        $content = file_get_contents($filePath);
        $factions = json_decode($content, true);

        $expectedFactions = [
            'argon',
            'paranid',
            'teladi',
            'holyorder',
            'antigone',
            'split',
            'terran',
            'xenon',
            'khaak'
        ];

        foreach ($expectedFactions as $factionID) {
            $found = false;
            foreach ($factions as $faction) {
                if (isset($faction['id']) && $faction['id'] === $factionID) {
                    $found = true;
                    break;
                }
            }

            $this->assertTrue(
                $found,
                sprintf('Expected faction [%s] not found in factions.json.', $factionID)
            );
        }
    }

    /**
     * Test that macro-index.json contains entries
     */
    public function test_macroIndexContainsEntries(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        $filePath = $dataFolder.'/macro-index.json';

        if (!file_exists($filePath)) {
            $this->markTestSkipped('macro-index.json not found.');
        }

        $content = file_get_contents($filePath);
        $macroIndex = json_decode($content, true);

        $this->assertNotEmpty(
            $macroIndex,
            'macro-index.json should contain macro entries.'
        );

        // Check that entries have expected structure (name, dataFolder, fullPath)
        $firstEntry = reset($macroIndex);
        
        if ($firstEntry !== false) {
            $this->assertIsArray($firstEntry);
            $this->assertArrayHasKey('name', $firstEntry);
            $this->assertArrayHasKey('fullPath', $firstEntry);
        }
    }

    /**
     * Test that ships.json contains ship entries
     */
    public function test_shipsContainsEntries(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        $filePath = $dataFolder.'/ships.json';

        if (!file_exists($filePath)) {
            $this->markTestSkipped('ships.json not found.');
        }

        $content = file_get_contents($filePath);
        $ships = json_decode($content, true);

        $this->assertNotEmpty(
            $ships,
            'ships.json should contain ship entries.'
        );

        // Verify structure of first ship
        $firstShip = reset($ships);
        
        if ($firstShip !== false) {
            $this->assertIsArray($firstShip);
            $this->assertArrayHasKey('wareID', $firstShip);
            $this->assertArrayHasKey('label', $firstShip);
        }
    }

    /**
     * Test that wares.json contains ware entries
     */
    public function test_waresContainsEntries(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        $filePath = $dataFolder.'/wares.json';

        if (!file_exists($filePath)) {
            $this->markTestSkipped('wares.json not found.');
        }

        $content = file_get_contents($filePath);
        $wares = json_decode($content, true);

        $this->assertNotEmpty(
            $wares,
            'wares.json should contain ware entries.'
        );

        // Verify structure of first ware
        $firstWare = reset($wares);
        
        if ($firstWare !== false) {
            $this->assertIsArray($firstWare);
            $this->assertArrayHasKey('wareID', $firstWare);
            $this->assertArrayHasKey('label', $firstWare);
        }
    }

    /**
     * Test that modules.json contains module entries
     */
    public function test_modulesContainsEntries(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        $filePath = $dataFolder.'/modules.json';

        if (!file_exists($filePath)) {
            $this->markTestSkipped('modules.json not found.');
        }

        $content = file_get_contents($filePath);
        $modules = json_decode($content, true);

        $this->assertNotEmpty(
            $modules,
            'modules.json should contain module entries.'
        );

        // Verify structure of first module
        $firstModule = reset($modules);
        
        if ($firstModule !== false) {
            $this->assertIsArray($firstModule);
            $this->assertArrayHasKey('wareID', $firstModule);
            $this->assertArrayHasKey('label', $firstModule);
        }
    }

    /**
     * Test that blueprints.json contains blueprint entries
     */
    public function test_blueprintsContainsEntries(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        $filePath = $dataFolder.'/blueprints.json';

        if (!file_exists($filePath)) {
            $this->markTestSkipped('blueprints.json not found.');
        }

        $content = file_get_contents($filePath);
        $blueprints = json_decode($content, true);

        $this->assertNotEmpty(
            $blueprints,
            'blueprints.json should contain blueprint entries.'
        );

        // Verify structure of first blueprint
        $firstBlueprint = reset($blueprints);
        
        if ($firstBlueprint !== false) {
            $this->assertIsArray($firstBlueprint);
            $this->assertArrayHasKey('wareID', $firstBlueprint);
            $this->assertArrayHasKey('categoryID', $firstBlueprint);
        }
    }

    /**
     * Test that file sizes are reasonable (not corrupted or truncated)
     */
    public function test_dataFileSizesReasonable(): void
    {
        $dataFolder = __DIR__.'/../../../../data';
        
        $fileSizeExpectations = [
            'blueprints.json' => 1000,     // At least 1KB
            'data-sources.json' => 100,    // At least 100 bytes
            'factions.json' => 500,         // At least 500 bytes
            'macro-index.json' => 1000,     // At least 1KB
            'modules.json' => 1000,         // At least 1KB
            'ships.json' => 1000,           // At least 1KB
            'wares.json' => 1000            // At least 1KB
        ];

        foreach ($fileSizeExpectations as $file => $minSize) {
            $filePath = $dataFolder.'/'.$file;
            
            if (!file_exists($filePath)) {
                $this->markTestSkipped(sprintf('Data file [%s] not found.', $file));
            }

            $size = filesize($filePath);
            
            $this->assertGreaterThan(
                $minSize,
                $size,
                sprintf('File [%s] is too small (%d bytes). Expected at least %d bytes. May be corrupted or truncated.', $file, $size, $minSize)
            );
        }
    }

    public function test_buildProcess() : void
    {
        $start = microtime(true);
        
        DatabaseBuilder::build();
        
        $duration = microtime(true) - $start;
        
        $this->assertLessThan(120, $duration, 'Build process took too long (> 120s).');
    }

    public function test_rebuildIdempotent() : void
    {
        // Run build once (or assume previous test ran it, but good to ensure state)
        DatabaseBuilder::build();
        $hashes1 = $this->hashDataFiles();
        
        // Run again
        DatabaseBuilder::build();
        $hashes2 = $this->hashDataFiles();
        
        $this->assertEquals($hashes1, $hashes2, 'Rebuild produced different files.');
    }

    private function hashDataFiles() : array
    {
        $dataFolder = __DIR__.'/../../../../data';
        $files = glob($dataFolder.'/*.json');
        $hashes = [];
        foreach($files as $file) {
            $hashes[basename($file)] = md5_file($file);
        }
        return $hashes;
    }
}

