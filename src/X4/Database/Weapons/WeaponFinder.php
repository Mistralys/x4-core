<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Weapons\WeaponFinder
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Weapons;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\Finder\BaseFinder;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionInterface;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionTrait;
use Mistralys\X4\Database\Core\ItemCollectionInterface;

/**
 * Finder for filtering weapon collections.
 * 
 * Provides fluent interface for filtering weapons by:
 * - Physical properties (size, maker race)
 * - Data source (vanilla, DLCs)
 * - Weapon type (weapon system, category)
 * - Performance characteristics (damage, DPS, range, reload)
 * - Quality (mark level)
 * 
 * Usage:
 *   $weapons = WeaponDefs::getInstance()->findWeapons()
 *       ->selectSize('s')
 *       ->selectWeaponSystem('weapon_standard')
 *       ->selectMinDamage(30)
 *       ->getAll();
 *
 * @package X4Core
 * @subpackage Database
 */
class WeaponFinder extends BaseFinder implements DataSourceSelectionInterface
{
    use DataSourceSelectionTrait;

    /**
     * @var string[]
     */
    private array $sizes = array();

    /**
     * @var string[]
     */
    private array $makerRaces = array();

    /**
     * @var string[]
     */
    private array $weaponSystems = array();

    /**
     * @var string[]
     */
    private array $weaponCategories = array();

    /**
     * @var int[]
     */
    private array $mks = array();

    private ?float $minDamage = null;
    private ?float $maxDamage = null;
    private ?float $minDPS = null;
    private ?float $maxDPS = null;
    private ?float $minRange = null;
    private ?float $maxRange = null;
    private ?float $minReloadRate = null;
    private ?float $maxReloadRate = null;
    private ?float $minBulletSpeed = null;
    private ?float $minRotationSpeed = null;
    private ?int $minMk = null;
    private ?bool $isTurret = null;
    private ?bool $isBeam = null;
    private ?bool $isMissile = null;
    private ?bool $isMining = null;
    private ?bool $isRepair = null;

    public function getCollection(): ItemCollectionInterface
    {
        return WeaponDefs::getInstance();
    }

    /**
     * Filter by weapon size.
     * 
     * @param string $size Weapon size: 's', 'm', 'l', 'xl'
     * @return self
     */
    public function selectSize(string $size): self
    {
        if (!in_array($size, $this->sizes, true)) {
            $this->sizes[] = $size;
        }
        return $this;
    }

    /**
     * Filter by multiple sizes.
     * 
     * @param string[] $sizes Array of sizes
     * @return self
     */
    public function selectSizes(array $sizes): self
    {
        foreach ($sizes as $size) {
            $this->selectSize($size);
        }
        return $this;
    }

    /**
     * Filter by maker race.
     * 
     * @param string $race Race: 'argon', 'paranid', 'teladi', 'split', 'boron', 'terran', etc.
     * @return self
     */
    public function selectMakerRace(string $race): self
    {
        if (!in_array($race, $this->makerRaces, true)) {
            $this->makerRaces[] = $race;
        }
        return $this;
    }

    /**
     * Filter by weapon system type.
     * 
     * @param string $system Weapon system: 'weapon_standard', 'weapon_beam', 'weapon_missile', etc.
     * @return self
     */
    public function selectWeaponSystem(string $system): self
    {
        if (!in_array($system, $this->weaponSystems, true)) {
            $this->weaponSystems[] = $system;
        }
        return $this;
    }

    /**
     * Filter by weapon category.
     * 
     * @param string $category Category: 'standard', 'energy', 'heavy', 'mining', 'missile', etc.
     * @return self
     */
    public function selectWeaponCategory(string $category): self
    {
        if (!in_array($category, $this->weaponCategories, true)) {
            $this->weaponCategories[] = $category;
        }
        return $this;
    }

    /**
     * Filter by mark level.
     * 
     * @param int $mk Mark level (1, 2, 3)
     * @return self
     */
    public function selectMk(int $mk): self
    {
        if (!in_array($mk, $this->mks, true)) {
            $this->mks[] = $mk;
        }
        return $this;
    }

    /**
     * Filter by minimum mark level.
     * 
     * @param int $minMk Minimum mark level
     * @return self
     */
    public function selectMinMk(int $minMk): self
    {
        $this->minMk = $minMk;
        return $this;
    }

    /**
     * Filter by minimum damage per hit.
     * 
     * @param float $minDamage Minimum damage value
     * @return self
     */
    public function selectMinDamage(float $minDamage): self
    {
        $this->minDamage = $minDamage;
        return $this;
    }

    /**
     * Filter by maximum damage per hit.
     * 
     * @param float $maxDamage Maximum damage value
     * @return self
     */
    public function selectMaxDamage(float $maxDamage): self
    {
        $this->maxDamage = $maxDamage;
        return $this;
    }

    /**
     * Filter by minimum DPS (damage per second).
     * 
     * @param float $minDPS Minimum DPS value
     * @return self
     */
    public function selectMinDPS(float $minDPS): self
    {
        $this->minDPS = $minDPS;
        return $this;
    }

    /**
     * Filter by maximum DPS (damage per second).
     * 
     * @param float $maxDPS Maximum DPS value
     * @return self
     */
    public function selectMaxDPS(float $maxDPS): self
    {
        $this->maxDPS = $maxDPS;
        return $this;
    }

    /**
     * Filter by minimum weapon range.
     * 
     * @param float $minRange Minimum range in meters
     * @return self
     */
    public function selectMinRange(float $minRange): self
    {
        $this->minRange = $minRange;
        return $this;
    }

    /**
     * Filter by maximum weapon range.
     * 
     * @param float $maxRange Maximum range in meters
     * @return self
     */
    public function selectMaxRange(float $maxRange): self
    {
        $this->maxRange = $maxRange;
        return $this;
    }

    /**
     * Filter by minimum reload rate.
     * 
     * @param float $minReloadRate Minimum reload rate
     * @return self
     */
    public function selectMinReloadRate(float $minReloadRate): self
    {
        $this->minReloadRate = $minReloadRate;
        return $this;
    }

    /**
     * Filter by maximum reload rate.
     * 
     * @param float $maxReloadRate Maximum reload rate
     * @return self
     */
    public function selectMaxReloadRate(float $maxReloadRate): self
    {
        $this->maxReloadRate = $maxReloadRate;
        return $this;
    }

    /**
     * Filter by minimum bullet speed.
     * 
     * @param float $minBulletSpeed Minimum bullet speed in m/s
     * @return self
     */
    public function selectMinBulletSpeed(float $minBulletSpeed): self
    {
        $this->minBulletSpeed = $minBulletSpeed;
        return $this;
    }

    /**
     * Filter by minimum rotation speed (for turrets).
     * 
     * @param float $minRotationSpeed Minimum rotation speed
     * @return self
     */
    public function selectMinRotationSpeed(float $minRotationSpeed): self
    {
        $this->minRotationSpeed = $minRotationSpeed;
        return $this;
    }

    /**
     * Filter only turret weapons (have rotation).
     * 
     * @param bool $isTurret True to select only turrets, false to exclude turrets
     * @return self
     */
    public function selectTurret(bool $isTurret = true): self
    {
        $this->isTurret = $isTurret;
        return $this;
    }

    /**
     * Filter only beam weapons.
     * 
     * @param bool $isBeam True to select only beam weapons
     * @return self
     */
    public function selectBeamWeapons(bool $isBeam = true): self
    {
        $this->isBeam = $isBeam;
        return $this;
    }

    /**
     * Filter only missile weapons.
     * 
     * @param bool $isMissile True to select only missile weapons
     * @return self
     */
    public function selectMissileWeapons(bool $isMissile = true): self
    {
        $this->isMissile = $isMissile;
        return $this;
    }

    /**
     * Filter only mining weapons.
     * 
     * @param bool $isMining True to select only mining weapons
     * @return self
     */
    public function selectMiningWeapons(bool $isMining = true): self
    {
        $this->isMining = $isMining;
        return $this;
    }

    /**
     * Filter only repair weapons.
     * 
     * @param bool $isRepair True to select only repair weapons
     * @return self
     */
    public function selectRepairWeapons(bool $isRepair = true): self
    {
        $this->isRepair = $isRepair;
        return $this;
    }

    /**
     * Apply filters to weapon item.
     * 
     * @param CollectionItemInterface $item
     * @return bool
     */
    protected function isMatch(CollectionItemInterface $item): bool
    {
        if (!$item instanceof WeaponDef) {
            return false;
        }

        // Size filter
        if (!empty($this->sizes) && !in_array($item->getSize(), $this->sizes, true)) {
            return false;
        }

        // Maker race filter
        if (!empty($this->makerRaces)) {
            $intersection = array_intersect($item->getMakerRaces(), $this->makerRaces);
            if (empty($intersection)) {
                return false;
            }
        }

        // Weapon system filter
        if (!empty($this->weaponSystems) && !in_array($item->getWeaponSystem(), $this->weaponSystems, true)) {
            return false;
        }

        // Weapon category filter
        if (!empty($this->weaponCategories) && !in_array($item->getWeaponCategory(), $this->weaponCategories, true)) {
            return false;
        }

        // Mark level filters
        if (!empty($this->mks) && !in_array($item->getMk(), $this->mks, true)) {
            return false;
        }

        if ($this->minMk !== null && $item->getMk() < $this->minMk) {
            return false;
        }

        // Damage filters
        if ($this->minDamage !== null && $item->getDamageValue() < $this->minDamage) {
            return false;
        }

        if ($this->maxDamage !== null && $item->getDamageValue() > $this->maxDamage) {
            return false;
        }

        // DPS filters
        if ($this->minDPS !== null && $item->getDPS() < $this->minDPS) {
            return false;
        }

        if ($this->maxDPS !== null && $item->getDPS() > $this->maxDPS) {
            return false;
        }

        // Range filters
        if ($this->minRange !== null && $item->getBulletRange() < $this->minRange) {
            return false;
        }

        if ($this->maxRange !== null && $item->getBulletRange() > $this->maxRange) {
            return false;
        }

        // Reload rate filters
        if ($this->minReloadRate !== null && $item->getReloadRate() < $this->minReloadRate) {
            return false;
        }

        if ($this->maxReloadRate !== null && $item->getReloadRate() > $this->maxReloadRate) {
            return false;
        }

        // Bullet speed filter
        if ($this->minBulletSpeed !== null && $item->getBulletSpeed() < $this->minBulletSpeed) {
            return false;
        }

        // Rotation speed filter
        if ($this->minRotationSpeed !== null && $item->getRotationSpeed() < $this->minRotationSpeed) {
            return false;
        }

        // Boolean filters
        if ($this->isTurret !== null && $item->isTurret() !== $this->isTurret) {
            return false;
        }

        if ($this->isBeam !== null && $item->isBeamWeapon() !== $this->isBeam) {
            return false;
        }

        if ($this->isMissile !== null && $item->isMissileWeapon() !== $this->isMissile) {
            return false;
        }

        if ($this->isMining !== null && $item->isMiningWeapon() !== $this->isMining) {
            return false;
        }

        if ($this->isRepair !== null && $item->isRepairWeapon() !== $this->isRepair) {
            return false;
        }

        if (!$this->isDataSourceMatch($item->getDataSourceID())) {
            return false;
        }

        if (!$this->isLabelMatch($item->getLabel())) {
            return false;
        }

        return true;
    }
}
