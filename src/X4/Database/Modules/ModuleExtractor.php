<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper\JSONFile;
use DOMDocument;
use DOMElement;
use Mistralys\X4\Database\Translations\TranslationDefs;
use Mistralys\X4\Database\Translations\TranslationExtractor;

class ModuleExtractor
{
    public const ERROR_STRUCTURES_FOLDER_NOT_FOUND = 138302;
    public const ERROR_DUPLICATE_MODULE_NAME = 138303;
    public const ERROR_INVALID_MODULES = 138304;
    public const KEY_MACROS = 'macros';
    public const KEY_LABEL = 'label';
    public const KEY_CATEGORY = 'category';

    private FolderInfo $folder;

    /**
     * @var array<string,string>
     */
    private array $searchIn;

    /**
     * @var array<string,array{category:string,label:string,macros:string[]}>
     */
    private array $modules = array();
    private TranslationDefs $translation;

    public function __construct(FolderInfo $structuresFolder)
    {
        if(!$structuresFolder->exists()) {
            throw new ModuleException(
                'The structures folder does not exist.',
                sprintf(
                    'Looking in: [%s].',
                    $structuresFolder->getPath()
                ),
                self::ERROR_STRUCTURES_FOLDER_NOT_FOUND
            );
        }

        $this->folder = $structuresFolder;
        $this->searchIn = $this->getSearchFolders();
        $this->translation = new TranslationDefs(TranslationExtractor::LANGUAGE_ENGLISH);
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

        echo PHP_EOL;

        $this->crossReferenceMacros();

        echo PHP_EOL;

        $this->verifyModules();

        echo '- Saving to disk...';

        JSONFile::factory(__DIR__.'/../../../../data/modules-list.json')
           ->putData($this->modules, true);

        echo 'OK'.PHP_EOL;
        echo PHP_EOL;
        echo 'All Done.'.PHP_EOL;
    }

    private function extractModules() : void
    {
        echo 'Extracting modules...'.PHP_EOL;

        foreach($this->searchIn as $folderName)
        {
            echo '- '.$folderName.'...';

            $folder = FolderInfo::factory($this->folder.'/'.$folderName);

            if(!$folder->exists()) {
                echo '- NOT FOUND.'.PHP_EOL;
                return;
            }

            $this->extractModulesInFolder($folder);
        }

        echo 'Done.'.PHP_EOL;
    }

    private function crossReferenceMacros() : void
    {
        echo 'Cross-referencing macros...'.PHP_EOL;

        foreach($this->searchIn as $folderName)
        {
            echo '- '.$folderName.'...'.PHP_EOL;

            $folder = FolderInfo::factory($this->folder.'/'.$folderName.'/macros');

            if(!$folder->exists()) {
                echo '- NOT FOUND.'.PHP_EOL;
                return;
            }

            $this->detectMacros($folder);
        }

        echo 'Done'.PHP_EOL;
    }

    private function verifyModules() : void
    {
        echo 'Finding orphaned modules...'.PHP_EOL;

        foreach($this->modules as $moduleName => $data)
        {
            if(!isset($data[self::KEY_MACROS])) {
                echo '- Module `'.$moduleName.'` is not used anywhere, removing.'.PHP_EOL;
                unset($this->modules[$moduleName]);
            }
        }

        echo 'Done.'.PHP_EOL;
    }

    private function detectMacros(FolderInfo $folderName) : void
    {
        $xmlFiles = FileHelper::createFileFinder($folderName)
            ->includeExtension('xml')
            ->setPathmodeAbsolute()
            ->getAll();

        foreach($xmlFiles as $file)
        {
            $this->parseMacroFile(FileInfo::factory($file));
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
            echo '-- Macro `'.$moduleName.'` has no matching module, ignoring.'.PHP_EOL;
            return;
        }

        if(!in_array($macroName, $this->modules[$moduleName][self::KEY_MACROS], true)) {
            $this->modules[$moduleName][self::KEY_MACROS][] = $macroName;
        }

        if(!empty($label)) {
            $this->modules[$moduleName][self::KEY_LABEL] = $label;
        }
    }

    private function extractModulesInFolder(FolderInfo $folder) : void
    {
        $xmlFiles = FileHelper::createFileFinder($folder)
            ->includeExtension('xml')
            ->setPathmodeAbsolute()
            ->getAll();

        foreach($xmlFiles as $file)
        {
            $this->parseFile(FileInfo::factory($file));
        }

        echo 'OK'.PHP_EOL;
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
