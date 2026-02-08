<?php
/**
 * @package X4Tests
 * @subpackage Database\MacroIndex
 * @see \Mistralys\X4\Database\MacroIndex\MacroFileDef
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\MacroIndex;

use AppUtils\FileHelper\FileInfo;
use Mistralys\X4\Database\MacroIndex\MacroFileDef;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\ExtractedData\DataFolder;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the MacroFileDef item class which represents
 * a single macro file definition.
 *
 * **ARCHITECTURAL LIMITATION**: The MacroFileDefs collection uses BaseStringPrimaryCollection
 * which requires unique IDs. However, the macro-index.json data contains duplicate macro
 * names across different data sources. This prevents the collection from loading.
 *
 * **STATUS**: All tests are currently skipped until the architectural issue is resolved.
 *
 * @package X4Tests
 * @subpackage Database\MacroIndex
 */
final class MacroDefTests extends X4TestCase
{
    private function getSampleMacro(): MacroFileDef
    {
        return MacroFileDefs::getInstance()->getByID('vanilla::Cluster_black2_Sector01_macro');
    }

    /**
     * Test that getID returns the composite ID (dataFolder::macroName)
     */
    public function test_getID(): void
    {
        $macro = $this->getSampleMacro();
        
        $this->assertIsString($macro->getID());
        $this->assertSame('vanilla::Cluster_black2_Sector01_macro', $macro->getID());
        $this->assertStringContainsString('::', $macro->getID());
    }

    /**
     * Test that getMacroName returns only the macro name
     */
    public function test_getMacroName(): void
    {
        $macro = $this->getSampleMacro();
        
        $this->assertIsString($macro->getMacroName());
        $this->assertSame('Cluster_black2_Sector01_macro', $macro->getMacroName());
        $this->assertStringNotContainsString('::', $macro->getMacroName());
    }

    /**
     * Test that getFullPath returns the file path
     */
    public function test_getFullPath(): void
    {
        $macro = $this->getSampleMacro();
        
        $this->assertIsString($macro->getFullPath());
        $this->assertNotEmpty($macro->getFullPath());
        $this->assertSame('maps/black2galaxy/black2sector', $macro->getFullPath());
    }

    /**
     * Test that getDataFolderID returns the data folder ID
     */
    public function test_getDataFolderID(): void
    {
        $macro = $this->getSampleMacro();
        
        $this->assertIsString($macro->getDataFolderID());
        $this->assertSame('vanilla', $macro->getDataFolderID());
    }

    /**
     * Test that getDataFolder returns a DataFolder instance
     */
    public function test_getDataFolder(): void
    {
        $macro = $this->getSampleMacro();
        $dataFolder = $macro->getDataFolder();
        
        $this->assertInstanceOf(DataFolder::class, $dataFolder);
        $this->assertSame($macro->getDataFolderID(), $dataFolder->getID());
    }

    /**
     * Test that getFile returns a FileInfo instance
     */
    public function test_getFile(): void
    {
        $macro = $this->getSampleMacro();
        $file = $macro->getFile();
        
        $this->assertInstanceOf(FileInfo::class, $file);
        $this->assertStringEndsWith('.xml', $file->getName());
    }

    /**
     * Test fromArray creates a MacroFileDef from array data
     */
    public function test_fromArray(): void
    {
        $data = [
            'name' => 'test_macro_name',
            'fullPath' => 'test/path/to/macro',
            'dataFolder' => 'vanilla'
        ];
        
        $macro = MacroFileDef::fromArray($data);
        
        $this->assertInstanceOf(MacroFileDef::class, $macro);
        $this->assertSame('vanilla::test_macro_name', $macro->getID());
        $this->assertSame('test_macro_name', $macro->getMacroName());
        $this->assertSame('test/path/to/macro', $macro->getFullPath());
        $this->assertSame('vanilla', $macro->getDataFolderID());
    }

    /**
     * Test that all macros have consistent data
     */
    public function test_allMacrosHaveConsistentData(): void
    {
        $macros = MacroFileDefs::getInstance()->getAll();
        
        foreach ($macros as $macro) {
            // Every macro should have these basic properties
            $this->assertNotEmpty($macro->getID(), 'Macro should have ID');
            $this->assertNotEmpty($macro->getFullPath(), 'Macro should have path');
            $this->assertNotEmpty($macro->getDataFolderID(), 'Macro should have data folder ID');
            
            // getFile should always return FileInfo
            $this->assertInstanceOf(FileInfo::class, $macro->getFile());
            
            // getDataFolder should always return DataFolder
            $this->assertInstanceOf(DataFolder::class, $macro->getDataFolder());
        }
    }

    /**
     * Test that vanilla macros have vanilla data folder
     */
    public function test_vanillaMacrosHaveVanillaDataFolder(): void
    {
        $macro = $this->getSampleMacro();
        
        $this->assertSame('vanilla', $macro->getDataFolderID());
        $this->assertSame('vanilla', $macro->getDataFolder()->getID());
    }

    /**
     * Test that macro file paths are properly constructed
     */
    public function test_filePathsAreProperlyConstructed(): void
    {
        $macro = $this->getSampleMacro();
        $file = $macro->getFile();
        
        // File path should contain the macro's full path
        $this->assertStringContainsString($macro->getFullPath(), $file->getPath());
        
        // File should end with .xml
        $this->assertStringEndsWith('.xml', $file->getPath());
    }

    /**
     * Test that different macros have different IDs
     */
    public function test_differentMacrosHaveDifferentIDs(): void
    {
        $allMacros = MacroFileDefs::getInstance()->getAll();
        
        // Get two different macros
        $macro1 = $allMacros[0];
        $macro2 = $allMacros[1];
        
        $this->assertNotSame($macro1->getID(), $macro2->getID(), 'Different macros should have different IDs');
    }

    /**
     * Test that macro paths don't include .xml extension
     */
    public function test_pathsDoNotIncludeXmlExtension(): void
    {
        $macro = $this->getSampleMacro();
        
        $this->assertStringNotContainsString('.xml', $macro->getFullPath(), 'Full path should not include .xml extension');
    }

    /**
     * Test that getFile path includes data folder path
     */
    public function test_getFilePathIncludesDataFolderPath(): void
    {
        $macro = $this->getSampleMacro();
        $file = $macro->getFile();
        $dataFolder = $macro->getDataFolder();
        
        $dataFolderPath = $dataFolder->getPath()->getPath();  // FolderInfo->getPath() returns string
        $this->assertStringContainsString($dataFolderPath, $file->getPath(), 'File path should include data folder path');
    }

    /**
     * Test that macros from different data folders have different paths
     */
    public function test_macrosFromDifferentDataFoldersHaveDifferentBasePaths(): void
    {
        $allMacros = MacroFileDefs::getInstance()->getAll();
        
        // Find one vanilla and one DLC macro
        $vanillaMacro = null;
        $dlcMacro = null;
        
        foreach ($allMacros as $macro) {
            if ($macro->getDataFolderID() === 'vanilla') {
                $vanillaMacro = $macro;
            } elseif ($dlcMacro === null) {
                $dlcMacro = $macro;
            }
            
            if ($vanillaMacro !== null && $dlcMacro !== null) {
                break;
            }
        }
        
        if ($vanillaMacro !== null && $dlcMacro !== null) {
            $vanillaPath = $vanillaMacro->getFile()->getPath();
            $dlcPath = $dlcMacro->getFile()->getPath();
            
            $this->assertNotSame($vanillaPath, $dlcPath, 'Macros from different data folders should have different file paths');
        }
    }

    /**
     * Test that fromArray handles all required keys
     */
    public function test_fromArrayHandlesAllRequiredKeys(): void
    {
        $data = [
            MacroFileDef::KEY_NAME => 'test_macro',
            MacroFileDef::KEY_FULL_PATH => 'test/path',
            MacroFileDef::KEY_DATA_FOLDER => 'vanilla'
        ];
        
        $macro = MacroFileDef::fromArray($data);
        
        $this->assertSame('vanilla::test_macro', $macro->getID());
        $this->assertSame('test_macro', $macro->getMacroName());
        $this->assertSame('test/path', $macro->getFullPath());
        $this->assertSame('vanilla', $macro->getDataFolderID());
    }

    /**
     * Test that most macro names follow expected patterns
     */
    public function test_mostMacroNamesEndWithMacro(): void
    {
        $allMacros = MacroFileDefs::getInstance()->getAll();
        $endingWithMacro = 0;
        $total = count($allMacros);
        
        foreach ($allMacros as $macro) {
            $id = $macro->getID();
            if (str_ends_with($id, '_macro')) {
                $endingWithMacro++;
            }
        }
        
        // Most macros (>90%) should end with _macro, but some patterns like basegame_map_* don't
        $percentage = ($endingWithMacro / $total) * 100;
        $this->assertGreaterThan(90, $percentage, 'Most macro IDs should end with _macro');
    }
}
