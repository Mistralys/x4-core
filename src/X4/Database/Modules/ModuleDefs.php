<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\X4Application;

/**
 * @method ModuleDef getByID(string $id)
 * @method ModuleDef[] getAll()
 * @method ModuleDef getDefault()
 */
class ModuleDefs extends BaseStringPrimaryCollection
{
    public const ERROR_UNKNOWN_MODULE_ID = 137601;

    private static ?ModuleDefs $instance = null;
    private JSONFile $dataFile;

    private function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() .'/modules.json')
            ->setPrettyPrint(true)
            ->setTrailingNewline(true);
    }

    public static function getInstance() : ModuleDefs
    {
        if(!isset(self::$instance)) {
            self::$instance = new ModuleDefs();
        }

        return self::$instance;
    }

    public function getDataFile(): JSONFile
    {
        return $this->dataFile;
    }

    public function findByMacro(string $macro) : ?ModuleDef
    {
        foreach($this->getAll() as $module) {
            if($module->getMacroID() === $macro) {
                return $module;
            }
        }

        return null;
    }

    /**
     * Finds a module by either its ware ID or macro ID.
     *
     * @param string $idOrMacro
     * @return ModuleDef|null
     */
    public function find(string $idOrMacro) : ?ModuleDef
    {
        if($this->idExists($idOrMacro)) {
            return $this->getByID($idOrMacro);
        }

        return $this->findByMacro($idOrMacro);
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach($this->getDataFile()->getData() as $moduleDef) {
            $this->registerItem(ModuleDef::fromArray($moduleDef));
        }
    }
}
