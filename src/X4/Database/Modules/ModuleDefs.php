<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\FileHelper\JSONFile;

class ModuleDefs
{
    public const ERROR_UNKNOWN_MODULE_ID = 137601;

    private static ?ModuleDefs $instance = null;

    /**
     * @var array<string,ModuleDef>
     */
    private array $defs = array();

    private function __construct()
    {
        $list = JSONFile::factory(__DIR__.'/../../../../data/modules-list.json')
            ->parse();

        $categories = ModuleCategories::getInstance();

        foreach($list as $moduleName => $moduleData)
        {
            $categoryID = $moduleData[ModuleExtractor::KEY_CATEGORY];

            if(!$categories->idExists($categoryID)) {
                continue;
            }

            $this->defs[$moduleName] = new ModuleDef(
                $moduleName,
                $categories->getByID($categoryID),
                $moduleData
            );
        }

        uasort($this->defs, static function(ModuleDef $a, ModuleDef $b) {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });
    }

    public static function getInstance() : ModuleDefs
    {
        if(isset(self::$instance))
        {
            return self::$instance;
        }

        $defs = new ModuleDefs();
        self::$instance = $defs;
        return $defs;
    }

    /**
     * @return ModuleDef[]
     */
    public function getAll() : array
    {
        return array_values($this->defs);
    }

    public function idExists(string $moduleID) : bool
    {
        return isset($this->defs[$moduleID]);
    }

    /**
     * @param string $moduleID
     * @return ModuleDef
     * @throws ModuleException {@see self::ERROR_UNKNOWN_MODULE_ID}
     */
    public function getByID(string $moduleID) : ModuleDef
    {
        if(isset($this->defs[$moduleID]))
        {
            return $this->defs[$moduleID];
        }

        throw new ModuleException(
            'Unknown module.',
            sprintf(
                'Tried to fetch module by ID [%s].',
                $moduleID
            ),
            self::ERROR_UNKNOWN_MODULE_ID
        );
    }

    public function findByMacro(string $macro) : ?ModuleDef
    {
        $modules = $this->getAll();

        foreach($modules as $module) {
            if($module->getMacro() === $macro) {
                return $module;
            }
        }

        return null;
    }
}
