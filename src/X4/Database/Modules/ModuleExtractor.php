<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper\JSONFile;
use DOMDocument;
use DOMElement;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Translations\TranslationDefs;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;

class ModuleExtractor
{
    public const ERROR_STRUCTURES_FOLDER_NOT_FOUND = 138302;
    public const ERROR_DUPLICATE_MODULE_NAME = 138303;
    public const ERROR_INVALID_MODULES = 138304;
    public const KEY_MACROS = 'macros';
    public const KEY_LABEL = 'label';
    public const KEY_CATEGORY = 'category';

    /**
     * @var StructuresFolder[]
     */
    private array $folders;

    /**
     * @var array<string,string>
     */
    private array $searchIn;

    /**
     * @var array<string,array{category:string,label:string,macros:string[]}>
     */
    private array $modules = array();
    private TranslationDefs $translation;

    public function __construct(DataFolders $dataFolders)
    {
        $this->loadStructureFolders($dataFolders);

        $this->searchIn = $this->getSearchFolders();
        $this->translation = new TranslationDefs(Languages::LANGUAGE_ENGLISH);
    }

    private function loadStructureFolders(DataFolders $dataFolders) : void
    {
        Console::header('Loading structure folders');

        $locations = array();
        foreach($dataFolders->getAll() as $dataFolder)
        {
            $structuresFolder = FolderInfo::factory($dataFolder->getFolder().'/assets/structures');
            $locations[] = (string)$structuresFolder;

            if($structuresFolder->exists()) {
                Console::line1('[%s] | OK | Structures found.', $dataFolder->getLabel(), $dataFolder->getLabel());
                $this->folders[] = new StructuresFolder($dataFolder, $structuresFolder);
            } else {
                Console::line1('[%s] | SKIP | No structures found.', $dataFolder->getLabel());
            }
        }

        Console::nl();

        if(empty($this->folders)) {
            throw new ModuleException(
                'No structure folders found in any of the available extracted data folders.',
                sprintf(
                    'Looking in: '.PHP_EOL.
                    '- %s',
                    implode(PHP_EOL.'- ', $locations)
                ),
                self::ERROR_STRUCTURES_FOLDER_NOT_FOUND
            );
        }
    }

    /**
     * @return array<string,string>
     */
    public function getSearchFolders() : array
    {
        return array(
            'buildmodule',
            'connectionmodules',
            'defence',
            'dock',
            'habitat',
            'processingmodule',
            'production',
            'storage',
            'venture',
            'welfare'
        );
    }

    public function extract() : void
    {
        $this->extractModules();

        Console::nl();

        $this->crossReferenceMacros();

        Console::nl();

        $this->verifyModules();

        Console::line1('Saving to disk...');

        ksort($this->modules);

        JSONFile::factory(__DIR__.'/../../../../data/modules-list.json')
           ->putData($this->modules, true);

        Console::line1('All Done.');
    }

    private function extractModules() : void
    {
        Console::header('Extracting modules');

        foreach($this->folders as $structuresFolder)
        {
            Console::line1('Processing data folder [%s].', $structuresFolder->getDataFolder()->getLabel());

            foreach ($this->searchIn as $folderName)
            {
                $folder = FolderInfo::factory($structuresFolder->getFolder() . '/' . $folderName);

                if (!$folder->exists()) {
                    Console::line2('[%s] | SKIP | Folder not found.', $folderName);
                    continue;
                }

                $this->extractModulesInFolder($folder, $folderName);
            }

            Console::nl();
        }
    }

    private function crossReferenceMacros() : void
    {
        Console::header('Cross-referencing macros');

        foreach($this->folders as $structuresFolder)
        {
            Console::line1('Processing data folder [%s].', $structuresFolder->getDataFolder()->getLabel());

            foreach ($this->searchIn as $folderName)
            {
                $folder = FolderInfo::factory($structuresFolder->getFolder() . '/' . $folderName . '/macros');

                if (!$folder->exists()) {
                    Console::line2('[%s] | SKIP | Folder not found.', $folderName);
                    continue;
                }

                $this->detectMacros($folder, $folderName);
            }

            Console::nl();
        }
    }

    private function verifyModules() : void
    {
        Console::header('Finding orphaned modules');

        foreach($this->modules as $moduleName => $data)
        {
            if(!isset($data[self::KEY_MACROS])) {
                Console::line1('Module [%s] is not used anywhere, removing.', $moduleName);
                unset($this->modules[$moduleName]);
            }
        }

        Console::line1('Done.');
    }

    private function detectMacros(FolderInfo $folder, string $folderName) : void
    {
        $xmlFiles = $folder->createFileFinder()
            ->includeExtension('xml')
            ->getFiles()
            ->typeANY();

        Console::line3('[%s] | Found [%d] XML files.', $folderName, count($xmlFiles));

        foreach($xmlFiles as $file) {
            $this->parseMacroFile($file);
        }
    }

    private function parseMacroFile(FileInfo $file) : void
    {
        $dom = new DOMDocument();
        $dom->loadXML($file->getContents());

        $nodes = $dom->getElementsByTagName('macro');

        foreach($nodes as $node) {
            if($node instanceof DOMElement) {
                $this->parseMacroNode($node);
                break;
            }
        }
    }

    private function parseMacroNode(DOMElement $macroNode) : void
    {
        $nodes = $macroNode->getElementsByTagName('component');

        foreach($nodes as $node) {
            if($node instanceof DOMElement) {
                $this->registerMacro(
                    $macroNode->getAttribute('name'),
                    $node->getAttribute('ref'),
                    $this->findLabel($macroNode)
                );
                break;
            }
        }
    }

    private function registerMacro(string $macroName, string $moduleName, string $label) : void
    {
        if(!isset($this->modules[$moduleName])) {
            Console::line3('Macro [%s] | SKIP | No matching module exists.', $moduleName);
            return;
        }

        if(!in_array($macroName, $this->modules[$moduleName][self::KEY_MACROS], true)) {
            $this->modules[$moduleName][self::KEY_MACROS][] = $macroName;
        }

        if(!empty($label)) {
            $this->modules[$moduleName][self::KEY_LABEL] = $label;
        }
    }

    private function extractModulesInFolder(FolderInfo $folder, string $folderName) : void
    {
        $xmlFiles = $folder->createFileFinder()
            ->includeExtension('xml')
            ->getFiles()
            ->typeANY();

        Console::line2('[%s] | Found [%s] XML files.', $folderName, count($xmlFiles));

        foreach($xmlFiles as $file) {
            $this->parseFile($file);
        }
    }

    private function parseFile(FileInfo $file) : void
    {
        $dom = new DOMDocument();
        $dom->loadXML($file->getContents());

        $nodes = $dom->getElementsByTagName('component');

        foreach($nodes as $node) {
            if($node instanceof DOMElement) {
                $this->add($node->getAttribute('name'), $file);
                break;
            }
        }
    }

    private function add(string $moduleName) : void
    {
        $parts = explode('_', $moduleName);
        $categoryID = (string)array_shift($parts);

        $categories = ModuleCategories::getInstance();
        if(!$categories->idExists($categoryID)) {
            return;
        }

        if(isset($this->modules[$moduleName])) {
            throw new ModuleException(
                'Duplicate module name found.',
                sprintf(
                    'Module [%s] already exists.',
                    $moduleName
                ),
                self::ERROR_DUPLICATE_MODULE_NAME
            );
        }

        $this->modules[$moduleName] = array(
            self::KEY_CATEGORY => $categoryID,
            self::KEY_LABEL => '',
            self::KEY_MACROS => array()
        );
   }

   private function findLabel(DOMElement $macroNode) : string
   {
       $nodes = $macroNode->getElementsByTagName('identification');

       foreach($nodes as $node) {
           if($node instanceof DOMElement) {
               return $this->getTranslation($node->getAttribute('name'));
           }
       }

       return '';
   }

   private function getTranslation(string $id) : string
   {
       $translation = $this->translation->ts($id);

       if(strlen($translation) > 1 && $translation[0] === '(') {
            $parts = explode(')', ltrim($translation, '('));
            $translation = $parts[0];
       }

       return $translation;
   }
}
