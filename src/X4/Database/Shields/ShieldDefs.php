<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Shields\ShieldDefs
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Shields;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
use Mistralys\X4\X4Application;

/**
 * Collection of all shield definitions.
 * 
 * Singleton accessor for shield performance data loaded from shields.json.
 * Shields link to WareDefs via wareID.
 * 
 * Usage:
 *   $shield = ShieldDefs::getInstance()->getByID('shield_arg_l_standard_01_mk1');
 *   $shields = ShieldDefs::getInstance()->getAll();
 *   $finder = ShieldDefs::getInstance()->findShields();
 *
 * @package X4Core
 * @subpackage Database
 * @method ShieldDef getByID(string $id)
 * @method ShieldDef[] getAll()
 * @method ShieldDef getDefault()
 */
class ShieldDefs extends BaseStringPrimaryCollection implements ItemCollectionInterface
{
    public const DATA_FILE = 'shields.json';
    public const ERROR_SHIELD_NOT_FOUND = ShieldException::ERROR_SHIELD_NOT_FOUND;

    private static ?ShieldDefs $instance = null;
    private JSONFile $dataFile;

    private function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() . '/' . self::DATA_FILE)
            ->setPrettyPrint(true)
            ->setTrailingNewline(true);
    }

    public static function getInstance(): ShieldDefs
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShieldDefs();
        }

        return self::$instance;
    }

    public function getDataFile(): JSONFile
    {
        return $this->dataFile;
    }

    /**
     * Find a shield by either its ware ID or macro ID.
     *
     * @param string $idOrMacro
     * @return ShieldDef|null
     */
    public function find(string $idOrMacro): ?ShieldDef
    {
        if ($this->idExists($idOrMacro)) {
            return $this->getByID($idOrMacro);
        }

        return $this->findByMacro($idOrMacro);
    }

    /**
     * Find shield by macro ID.
     *
     * @param string $macro
     * @return ShieldDef|null
     */
    public function findByMacro(string $macro): ?ShieldDef
    {
        return array_find($this->getAll(), fn($shield) => $shield->getMacroID() === $macro);
    }

    /**
     * Get shields by type.
     *
     * @param string $type Shield type (standard/racer/corvette/mothership/yacht/experimental/virtual)
     * @return ShieldDef[]
     */
    public function getByType(string $type): array
    {
        return array_filter(
            $this->getAll(),
            fn(ShieldDef $shield) => $shield->getShieldType() === $type
        );
    }

    /**
     * Get default shield ID (first in collection).
     */
    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    /**
     * Create finder for filtering shields.
     * 
     * @return ShieldFinder
     */
    public function findShields(): ShieldFinder
    {
        return new ShieldFinder();
    }

    /**
     * Load shields from JSON file.
     * Called automatically by parent constructor.
     */
    protected function registerItems(): void
    {
        foreach ($this->getDataFile()->getData() as $shieldData) {
            $this->registerItem(ShieldDef::fromArray($shieldData));
        }
    }
}
