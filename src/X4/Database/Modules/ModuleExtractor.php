<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper\JSONFile;
use DOMDocument;
use DOMElement;

class ModuleExtractor
{
    private FolderInfo $folder;

    /**
     * @var array<string,string>
     */
    private array $searchIn;

    /**
     * @var array<int,array{name:string,category:string}>
     */
    private array $modules = array();

    public function __construct(FolderInfo $structuresFolder)
    {
        $this->folder = $structuresFolder;
        $this->searchIn = $this->getSearchFolders();
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
        foreach($this->searchIn as $folderName)
        {
            $this->extractModules($folderName);
        }

        echo JSONFile::factory(__DIR__.'/../../../../data/modules-list.json')
           ->putData($this->modules, true)
            ->getContents();
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
                $this->add($node->getAttribute('name'));
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

        if(!isset($this->modules[$categoryID])) {
            $this->modules[$categoryID] = array();
        }

        $this->modules[$categoryID][] = $moduleName;
    }
}
