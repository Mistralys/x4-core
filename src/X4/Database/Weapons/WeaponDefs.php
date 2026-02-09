<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Weapons\WeaponDefs
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Weapons;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
use Mistralys\X4\X4Application;

/**
 * Collection of all weapon definitions.
 * 
 * Singleton accessor for weapon performance data loaded from weapons.json.
 * Weapons link to WareDefs via wareID.
 * 
 * Usage:
 *   $weapon = WeaponDefs::getInstance()->getByID('weapon_gen_s_laser_01_mk1');
 *   $weapons = WeaponDefs::getInstance()->getAll();
 *   $finder = WeaponDefs::getInstance()->findWeapons();
 *
 * @package X4Core
 * @subpackage Database
 * @method WeaponDef getByID(string $id)
 * @method WeaponDef[] getAll()
 * @method WeaponDef getDefault()
 */
class WeaponDefs extends BaseStringPrimaryCollection implements ItemCollectionInterface
{
    public const DATA_FILE = 'weapons.json';
    public const ERROR_WEAPON_NOT_FOUND = WeaponException::ERROR_WEAPON_NOT_FOUND;

    private static ?WeaponDefs $instance = null;
    private JSONFile $dataFile;

    private function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() . '/' . self::DATA_FILE)
            ->setPrettyPrint(true)
            ->setTrailingNewline(true);
    }

    public static function getInstance(): WeaponDefs
    {
        if (!isset(self::$instance)) {
            self::$instance = new WeaponDefs();
        }

        return self::$instance;
    }

    public function getDataFile(): JSONFile
    {
        return $this->dataFile;
    }

    /**
     * Find a weapon by either its ware ID or macro ID.
     *
     * @param string $idOrMacro
     * @return WeaponDef|null
     */
    public function find(string $idOrMacro): ?WeaponDef
    {
        if ($this->idExists($idOrMacro)) {
            return $this->getByID($idOrMacro);
        }

        return $this->findByMacro($idOrMacro);
    }

    /**
     * Find weapon by macro ID.
     *
     * @param string $macro
     * @return WeaponDef|null
     */
    public function findByMacro(string $macro): ?WeaponDef
    {
        return array_find($this->getAll(), fn($weapon) => $weapon->getMacroID() === $macro);
    }

    /**
     * Find weapon by bullet class.
     *
     * @param string $bulletClass
     * @return WeaponDef|null
     */
    public function findByBulletClass(string $bulletClass): ?WeaponDef
    {
        return array_find($this->getAll(), fn($weapon) => $weapon->getBulletClass() === $bulletClass);
    }

    /**
     * Get all weapons of a specific weapon system type.
     *
     * @param string $weaponSystem Weapon system type (e.g., 'weapon_standard', 'weapon_beam')
     * @return WeaponDef[]
     */
    public function getByWeaponSystem(string $weaponSystem): array
    {
        return array_filter($this->getAll(), fn($weapon) => $weapon->getWeaponSystem() === $weaponSystem);
    }

    /**
     * Get all weapons of a specific category.
     *
     * @param string $category Category (e.g., 'standard', 'energy', 'mining')
     * @return WeaponDef[]
     */
    public function getByCategory(string $category): array
    {
        return array_filter($this->getAll(), fn($weapon) => $weapon->getWeaponCategory() === $category);
    }

    /**
     * Get default weapon ID (first in collection).
     */
    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    /**
     * Create finder for filtering weapons.
     * 
     * @return WeaponFinder
     */
    public function findWeapons(): WeaponFinder
    {
        return new WeaponFinder();
    }

    /**
     * Load weapons from JSON file.
     * Called automatically by parent constructor.
     */
    protected function registerItems(): void
    {
        if (!$this->dataFile->exists()) {
            return;
        }

        foreach ($this->getDataFile()->getData() as $weaponData) {
            $this->registerItem(WeaponDef::fromArray($weaponData));
        }
    }
}
