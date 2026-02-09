<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Shields\ShieldFinder
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Shields;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\Finder\BaseFinder;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionInterface;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionTrait;
use Mistralys\X4\Database\Core\ItemCollectionInterface;

/**
 * Finder for filtering shield collections.
 * 
 * Provides fluent interface for filtering shields by:
 * - Physical properties (size, maker race, type)
 * - Data source (vanilla, DLCs)
 * - Performance characteristics (capacity, recharge, hull)
 * - Quality (mark level)
 * 
 * Usage:
 *   $shields = ShieldDefs::getInstance()->findShields()
 *       ->selectSize('l')
 *       ->selectMakerRace('argon')
 *       ->selectType('standard')
 *       ->selectMinCapacity(30000)
 *       ->getAll();
 *
 * @package X4Core
 * @subpackage Database
 */
class ShieldFinder extends BaseFinder implements DataSourceSelectionInterface
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
     * @var int[]
     */
    private array $mks = array();

    /**
     * @var string[]
     */
    private array $types = array();

    private ?float $minCapacity = null;
    private ?float $maxCapacity = null;
    private ?float $minRechargeRate = null;
    private ?float $maxRechargeDelay = null;
    private ?float $minHull = null;
    private ?int $minMk = null;
    private ?bool $integratedOnly = null;
    private ?bool $withHullOnly = null;

    public function getCollection(): ItemCollectionInterface
    {
        return ShieldDefs::getInstance();
    }

    /**
     * Filter by shield size.
     * 
     * @param string $size Shield size: 's', 'm', 'l', 'xl'
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
     * Filter by shield type.
     * 
     * @param string $type Type: 'standard', 'racer', 'corvette', 'mothership', 'yacht', 'experimental', 'virtual'
     * @return self
     */
    public function selectType(string $type): self
    {
        if (!in_array($type, $this->types, true)) {
            $this->types[] = $type;
        }
        return $this;
    }

    /**
     * Filter by multiple types.
     * 
     * @param string[] $types Array of shield types
     * @return self
     */
    public function selectTypes(array $types): self
    {
        foreach ($types as $type) {
            $this->selectType($type);
        }
        return $this;
    }

    /**
     * Filter by minimum shield capacity.
     * 
     * @param float $minCapacity Minimum recharge max value
     * @return self
     */
    public function selectMinCapacity(float $minCapacity): self
    {
        $this->minCapacity = $minCapacity;
        return $this;
    }

    /**
     * Filter by maximum shield capacity.
     * 
     * @param float $maxCapacity Maximum recharge max value
     * @return self
     */
    public function selectMaxCapacity(float $maxCapacity): self
    {
        $this->maxCapacity = $maxCapacity;
        return $this;
    }

    /**
     * Filter by minimum recharge rate (faster recharge).
     * 
     * @param float $minRate Minimum recharge rate per second
     * @return self
     */
    public function selectMinRechargeRate(float $minRate): self
    {
        $this->minRechargeRate = $minRate;
        return $this;
    }

    /**
     * Filter by maximum recharge delay (faster recovery).
     * 
     * @param float $maxDelay Maximum delay in seconds
     * @return self
     */
    public function selectMaxRechargeDelay(float $maxDelay): self
    {
        $this->maxRechargeDelay = $maxDelay;
        return $this;
    }

    /**
     * Filter by minimum hull durability.
     * 
     * @param float $minHull Minimum hull max value
     * @return self
     */
    public function selectMinHull(float $minHull): self
    {
        $this->minHull = $minHull;
        return $this;
    }

    /**
     * Filter shields with hull protection only.
     * 
     * @return self
     */
    public function selectWithHull(): self
    {
        $this->withHullOnly = true;
        return $this;
    }

    /**
     * Filter integrated shields only.
     * 
     * @return self
     */
    public function selectIntegrated(): self
    {
        $this->integratedOnly = true;
        return $this;
    }

    /**
     * Filter non-integrated shields only.
     * 
     * @return self
     */
    public function selectNonIntegrated(): self
    {
        $this->integratedOnly = false;
        return $this;
    }

    /**
     * @param ShieldDef $item
     * @return bool
     */
    protected function isMatch(CollectionItemInterface $item): bool
    {
        if (!empty($this->sizes) && !in_array($item->getSize(), $this->sizes, true)) {
            return false;
        }

        if (!empty($this->makerRaces) && !in_array($item->getMakerRace(), $this->makerRaces, true)) {
            return false;
        }

        if (!empty($this->mks) && !in_array($item->getMk(), $this->mks, true)) {
            return false;
        }

        if (!empty($this->types) && !in_array($item->getShieldType(), $this->types, true)) {
            return false;
        }

        if ($this->minMk !== null && $item->getMk() < $this->minMk) {
            return false;
        }

        if ($this->minCapacity !== null && $item->getCapacity() < $this->minCapacity) {
            return false;
        }

        if ($this->maxCapacity !== null && $item->getCapacity() > $this->maxCapacity) {
            return false;
        }

        if ($this->minRechargeRate !== null && $item->getRechargeRate() < $this->minRechargeRate) {
            return false;
        }

        if ($this->maxRechargeDelay !== null && $item->getRechargeDelay() > $this->maxRechargeDelay) {
            return false;
        }

        if ($this->minHull !== null && $item->getHullMax() < $this->minHull) {
            return false;
        }

        if ($this->withHullOnly === true && !$item->hasHull()) {
            return false;
        }

        if ($this->integratedOnly !== null && $item->isHullIntegrated() !== $this->integratedOnly) {
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
