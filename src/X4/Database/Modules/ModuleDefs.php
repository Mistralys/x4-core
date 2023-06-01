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

        foreach($list as $categoryID => $moduleIDs)
        {
            if(!$categories->idExists($categoryID)) {
                continue;
            }

            $category = $categories->getByID($categoryID);

            foreach($moduleIDs as $moduleID) {
                $this->defs[$moduleID] = new ModuleDef($moduleID, '', $category);
            }
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
        $parts = explode('_', $macro);
        $last = array_pop($parts);

        if($last !== 'macro') {
            return null;
        }

        $id = implode('_', $parts);
        if($this->idExists($id)) {
            return $this->getByID($id);
        }

        return null;
    }
}
