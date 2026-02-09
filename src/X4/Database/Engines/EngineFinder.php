<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Engines\EngineFinder
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Engines;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\Finder\BaseFinder;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionInterface;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionTrait;
use Mistralys\X4\Database\Core\ItemCollectionInterface;

/**
 * Finder for filtering engine collections.
 * 
 * Provides fluent interface for filtering engines by:
 * - Physical properties (size, maker race)
 * - Data source (vanilla, DLCs)
 * - Performance characteristics (thrust, boost, travel)
 * - Quality (mark level)
 * 
 * Usage:
 *   $engines = EngineDefs::getInstance()->findEngines()
 *       ->selectSize('l')
 *       ->selectMakerRace('argon')
 *       ->selectMinThrust(3000)
 *       ->getAll();
 *
 * @package X4Core
 * @subpackage Database
 */
class EngineFinder extends BaseFinder implements DataSourceSelectionInterface
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

    private ?float $minThrust = null;
    private ?float $maxThrust = null;
    private ?float $minBoostDuration = null;
    private ?float $maxBoostRecharge = null;
    private ?float $minBoostThrust = null;
    private ?float $minTravelThrust = null;
    private ?float $maxTravelCharge = null;
    private ?float $minHull = null;
    private ?int $minMk = null;
    private bool $requireDecelerationCurve = false;

    public function getCollection(): ItemCollectionInterface
    {
        return EngineDefs::getInstance();
    }

    /**
     * Filter by engine size.
     * 
     * @param string $size Engine size: 's', 'm', 'l', 'xl'
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
     * Filter by minimum forward thrust.
     * 
     * @param float $minThrust Minimum forward thrust value
     * @return self
     */
    public function selectMinThrust(float $minThrust): self
    {
        $this->minThrust = $minThrust;
        return $this;
    }

    /**
     * Filter by maximum forward thrust.
     * 
     * @param float $maxThrust Maximum forward thrust value
     * @return self
     */
    public function selectMaxThrust(float $maxThrust): self
    {
        $this->maxThrust = $maxThrust;
        return $this;
    }

    /**
     * Filter by minimum boost duration.
     * 
     * @param float $minDuration Minimum boost duration in seconds
     * @return self
     */
    public function selectMinBoostDuration(float $minDuration): self
    {
        $this->minBoostDuration = $minDuration;
        return $this;
    }

    /**
     * Filter by maximum boost recharge time (faster recharge).
     * 
     * @param float $maxRecharge Maximum recharge time in seconds
     * @return self
     */
    public function selectMaxBoostRecharge(float $maxRecharge): self
    {
        $this->maxBoostRecharge = $maxRecharge;
        return $this;
    }

    /**
     * Filter by minimum boost thrust multiplier.
     * 
     * @param float $minMultiplier Minimum thrust multiplier
     * @return self
     */
    public function selectMinBoostThrust(float $minMultiplier): self
    {
        $this->minBoostThrust = $minMultiplier;
        return $this;
    }

    /**
     * Filter by minimum travel thrust.
     * 
     * @param float $minTravel Minimum travel thrust value
     * @return self
     */
    public function selectMinTravelThrust(float $minTravel): self
    {
        $this->minTravelThrust = $minTravel;
        return $this;
    }

    /**
     * Filter by maximum travel charge time (faster charge).
     * 
     * @param float $maxCharge Maximum charge time in seconds
     * @return self
     */
    public function selectMaxTravelCharge(float $maxCharge): self
    {
        $this->maxTravelCharge = $maxCharge;
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
     * Filter engines with deceleration curves.
     * 
     * @return self
     */
    public function selectWithDecelerationCurve(): self
    {
        $this->requireDecelerationCurve = true;
        return $this;
    }

    /**
     * @param EngineDef $item
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

        if ($this->minMk !== null && $item->getMk() < $this->minMk) {
            return false;
        }

        if ($this->minThrust !== null && $item->getThrustForward() < $this->minThrust) {
            return false;
        }

        if ($this->maxThrust !== null && $item->getThrustForward() > $this->maxThrust) {
            return false;
        }

        if ($this->minBoostDuration !== null && $item->getBoostDuration() < $this->minBoostDuration) {
            return false;
        }

        if ($this->maxBoostRecharge !== null && $item->getBoostRecharge() > $this->maxBoostRecharge) {
            return false;
        }

        if ($this->minBoostThrust !== null && $item->getBoostThrust() < $this->minBoostThrust) {
            return false;
        }

        if ($this->minTravelThrust !== null && $item->getTravelThrust() < $this->minTravelThrust) {
            return false;
        }

        if ($this->maxTravelCharge !== null && $item->getTravelCharge() > $this->maxTravelCharge) {
            return false;
        }

        if ($this->minHull !== null && $item->getHullMax() < $this->minHull) {
            return false;
        }

        if ($this->requireDecelerationCurve && !$item->hasDecelerationCurve()) {
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
