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
    public const ERROR_MACRO_FILE_NOT_FOUND = 138301;

    private FolderInfo $folder;

    /**
     * @var array<string,string>
     */
    private array $searchIn;

    /**
     * @var array<int,array{name:string,category:string}>
     */
    private array $modules = array();
    private TranslationDefs $translation;

    public function __construct(FolderInfo $structuresFolder)
    {
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
        echo 'Extracting modules...'.PHP_EOL;

        foreach($this->searchIn as $folderName)
        {
            echo '- '.$folderName.'...';
            $this->extractModules($folderName);
            echo 'OK'.PHP_EOL;
        }

        echo '- Saving to disk...';

        JSONFile::factory(__DIR__.'/../../../../data/modules-list.json')
           ->putData($this->modules, true);

        echo 'OK'.PHP_EOL;
        echo PHP_EOL;
        echo 'Done.'.PHP_EOL;
    }

    private function extractModules(string $folderName) : void
    {
        $xmlFiles = FileHelper::createFileFinder($this->folder.'/'.$folderName)
            ->includeExtension('xml')
            ->setPathmodeAbsolute()
            ->getAll();

        foreach($xmlFiles as $file)
        {
            $this->parseFile(FileInfo::factory($file));
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

    public const MODULE_TO_MACRO = array(
        'prod_tel_swampplants' => 'prod_tel_swampplant',

        // The gen medical supplies is used by several races. For the
        // label, we simply use the Argon one, which is available in
        // all installs.
        'prod_gen_medicalsupplies' => 'prod_arg_medicalsupplies',

        // No idea why these exist and have no macros at all.
        'hab_par_m_02' => 'hab_par_m_01',
        'hab_par_s_02' => 'hab_par_s_01',
        'hab_par_s_03' => 'hab_par_s_01',

        'dockarea_arg_xl_builder_01_a' => 'dockarea_arg_xl_builder_01',
        'buildmodule_arg_ships_xl' => 'buildmodule_gen_ships_xl',
        'buildmodule_arg_ships_s' => 'buildmodule_gen_ships_s',
        'buildmodule_arg_ships_m' => 'buildmodule_gen_ships_m',
        'buildmodule_arg_ships_l' => 'buildmodule_gen_ships_l',
        'buildmodule_arg_equip_xl' => 'buildmodule_gen_equip_xl',
        'buildmodule_arg_equip_l' => 'buildmodule_gen_equip_l'
    );

    public const IGNORE_MODULES = array(
        'prod_gen_countermeasures',
        'dockarea_arg_s_01'
    );

    private function add(string $moduleName, FileInfo $file) : void
    {
        if(in_array($moduleName, self::IGNORE_MODULES, true)) {
            return;
        }

        $parts = explode('_', $moduleName);
        $categoryID = (string)array_shift($parts);

        $categories = ModuleCategories::getInstance();
        if(!$categories->idExists($categoryID)) {
            return;
        }

        if(!isset($this->modules[$categoryID])) {
            $this->modules[$categoryID] = array();
        }

        $this->modules[$categoryID][$moduleName] = $this->findLabel($moduleName, $file);
   }

   private function findLabel(string $moduleName, FileInfo $file) : string
   {
       $baseName = $file->getBaseName();
       if(isset(self::MODULE_TO_MACRO[$baseName])) {
           $baseName = self::MODULE_TO_MACRO[$baseName];
       }

       $macroFile = FileInfo::factory($file->getFolderPath().'/macros/'.$baseName.'_macro.xml');
       if(!$macroFile->exists()) {
           throw new ModuleException(
               sprintf('Module [%s] has no macro file.', $moduleName),
               sprintf(
                   'Looking in: [%s].',
                   $macroFile->getPath()
               ),
               self::ERROR_MACRO_FILE_NOT_FOUND
           );
       }

       $dom = new DOMDocument();
       $dom->loadXML($macroFile->getContents());

       $nodes = $dom->getElementsByTagName('identification');

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
