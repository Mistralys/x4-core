<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;

class ModuleExtractor
{
    /**
     * @var array<int,array<string,string|int|null>>
     */
    private array $modules = array();

    public function __construct(DataFolders $dataFolders)
    {
    }

    public function extract() : void
    {
        $this->extractModules();
        $this->validateCategories();
    }

    private function extractModules() : void
    {
        Console::header('Extracting modules...');

        foreach(WareDefs::getInstance()->findWares()->selectGroup(WareGroups::GROUP_MODULES)->getAll() as $ware) {
            $this->processWare($ware);
        }

        Console::line1('Found [%d] modules.', count($this->modules));
        Console::line1('Saving to disk...');
        Console::nl();

        ksort($this->modules);

        ModuleDefs::getInstance()
            ->getDataFile()
            ->putData($this->modules);
    }

    private function processWare(WareDef $ware) : void
    {
        $this->modules[] = (new ModuleMacroExtractor($ware))->extract();
    }

    private function validateCategories() : void
    {
        Console::header('Validating module categories...');

        $collection = ModuleCategories::getInstance();
        $used = $this->getUsedCategories();
        $available = $collection->getIDs();

        foreach($used as $usedCategory) {
            if(!in_array($usedCategory, $available, true)) {
                Console::line1('Unknown module category [%s].', $usedCategory);
            }
        }

        foreach($available as $availableCategory) {
            if(!in_array($availableCategory, $used, true)) {
                Console::line1('Unused module category [%s].', $availableCategory);
            }
        }

        Console::line1('Validation complete.');
        Console::nl();
    }

    /**
     * @return string[]
     */
    private function getUsedCategories() : array
    {
        $result = array();

        foreach($this->modules as $module) {
            $category = $module[ModuleDef::KEY_CATEGORY];
            if(!in_array($category, $result, true)) {
                $result[] = $category;
            }
        }

        sort($result);

        return $result;
    }
}
