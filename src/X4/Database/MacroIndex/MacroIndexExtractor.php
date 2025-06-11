<?php
/**
 * @package X4 Database
 * @subpackage Macro Index
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\MacroIndex;

use AppUtils\FileHelper\FileInfo;
use DOMDocument;
use DOMElement;
use Mistralys\X4\ExtractedData\Console;
use Mistralys\X4\ExtractedData\DataFolder;
use Mistralys\X4\ExtractedData\DataFolders;

/**
 * Goes through all data folders and extracts the macro indexes.
 *
 * More info here: {@see MacroFileDefs}.
 *
 * @package X4 Database
 * @subpackage Macro Index
 */
class MacroIndexExtractor
{
    private DataFolders $dataFolders;
    private array $macros = array();

    public function __construct(DataFolders $dataFolders)
    {
        $this->dataFolders = $dataFolders;
    }

    public function extract() : void
    {
        foreach($this->dataFolders->getAll() as $dataFolder) {
            $this->processDataFolder($dataFolder);
        }

        ksort($this->macros);

        Console::line1('Writing macro index file.');

        MacroFileDefs::getInstance()
            ->getDataFile()
            ->putData(array_values($this->macros));
    }

    private function processDataFolder(DataFolder $dataFolder) : void
    {
        Console::header('Processing Data Folder [%s]', $dataFolder->getLabel());

        $macroFile = FileInfo::factory($dataFolder->getFolder().'/index/macros.xml');
        if(!$macroFile->exists()) {
            Console::line1('SKIP | No macro file found.');
            return;
        }

        Console::line1('Macro file found, processing...');

        $dom = new DOMDocument();
        $dom->loadXML($macroFile->getContents());

        $total = 0;
        foreach($dom->getElementsByTagName('entry') as $entry) {
            $this->processEntry($entry, $dataFolder);
            $total++;
        }

        Console::line1('[%d] macros found.', $total);
        Console::nl();
    }

    private function processEntry(DOMElement $entry, DataFolder $dataFolder) : void
    {
        $name = $entry->getAttribute('name');
        $path = str_replace('\\', '/', $entry->getAttribute('value'));

        if($dataFolder->isExtension()) {
            $parts = explode($dataFolder->getID(), $path);
            $path = ltrim(array_pop($parts), '/');
        }

        $this->macros[$name] = array(
            MacroFileDef::KEY_NAME => $name,
            MacroFileDef::KEY_DATA_FOLDER => $dataFolder->getID(),
            MacroFileDef::KEY_FULL_PATH => $path
        );
    }
}
