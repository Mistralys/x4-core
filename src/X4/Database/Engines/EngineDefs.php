<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Engines\EngineDefs
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Engines;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
use Mistralys\X4\X4Application;

/**
 * Collection of all engine definitions.
 * 
 * Singleton accessor for engine performance data loaded from engines.json.
 * Engines link to WareDefs via wareID.
 * 
 * Usage:
 *   $engine = EngineDefs::getInstance()->getByID('engine_arg_l_allround_01_mk1');
 *   $engines = EngineDefs::getInstance()->getAll();
 *   $finder = EngineDefs::getInstance()->findEngines();
 *
 * @package X4Core
 * @subpackage Database
 * @method EngineDef getByID(string $id)
 * @method EngineDef[] getAll()
 * @method EngineDef getDefault()
 */
class EngineDefs extends BaseStringPrimaryCollection implements ItemCollectionInterface
{
    public const DATA_FILE = 'engines.json';
    public const ERROR_ENGINE_NOT_FOUND = EngineException::ERROR_ENGINE_NOT_FOUND;

    private static ?EngineDefs $instance = null;
    private JSONFile $dataFile;

    private function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() . '/' . self::DATA_FILE)
            ->setPrettyPrint(true)
            ->setTrailingNewline(true);
    }

    public static function getInstance(): EngineDefs
    {
        if (!isset(self::$instance)) {
            self::$instance = new EngineDefs();
        }

        return self::$instance;
    }

    public function getDataFile(): JSONFile
    {
        return $this->dataFile;
    }

    /**
     * Check if engine exists.
     * Inherits from BaseStringPrimaryCollection
     */

    /**
     * Find an engine by either its ware ID or macro ID.
     *
     * @param string $idOrMacro
     * @return EngineDef|null
     */
    public function find(string $idOrMacro): ?EngineDef
    {
        if ($this->idExists($idOrMacro)) {
            return $this->getByID($idOrMacro);
        }

        return $this->findByMacro($idOrMacro);
    }

    /**
     * Find engine by macro ID.
     *
     * @param string $macro
     * @return EngineDef|null
     */
    public function findByMacro(string $macro): ?EngineDef
    {
        return array_find($this->getAll(), fn($engine) => $engine->getMacroID() === $macro);
    }

    /**
     * Get default engine ID (first in collection).
     */
    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    /**
     * Create finder for filtering engines.
     * 
     * @return EngineFinder
     */
    public function findEngines(): EngineFinder
    {
        return new EngineFinder();
    }

    /**
     * Load engines from JSON file.
     * Called automatically by parent constructor.
     */
    protected function registerItems(): void
    {
        foreach ($this->getDataFile()->getData() as $engineData) {
            $this->registerItem(EngineDef::fromArray($engineData));
        }
    }
}
