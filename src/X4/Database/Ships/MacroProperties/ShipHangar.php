<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

/**
 * The internal ship storage (hangar) capacity per dock size, e.g.
 * <code>array('s' => 12, 'm' => 4)</code>. Same shape as the existing
 * `equipment.docks` map. This is not the docking points of the ship.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipHangar
{
    /**
     * @var array<string,int>
     */
    public readonly array $capacities;

    /**
     * @param array<string,int|string> $capacities Capacity per {@see \Mistralys\X4\Database\Ships\ShipSizes} ID.
     */
    public function __construct(array $capacities = array())
    {
        $clean = array();

        foreach($capacities as $size => $capacity) {
            $clean[(string)$size] = (int)$capacity;
        }

        $this->capacities = $clean;
    }

    /**
     * @param array<string,int|string> $data
     * @return self
     */
    public static function fromArray(array $data) : self
    {
        return new self($data);
    }

    /**
     * @return array<string,int>
     */
    public function toArray() : array
    {
        return $this->capacities;
    }

    /**
     * @param string $size A ship size ID, e.g. `s`.
     * @return int 0 if the hangar has no capacity for the size.
     */
    public function getCapacity(string $size) : int
    {
        return $this->capacities[$size] ?? 0;
    }

    /**
     * The plain sum of the per-size capacities.
     *
     * Note: A bay that is usable for several sizes is counted once for each
     * size, so the total would be over-counted for such a ship.
     * No ship in the game data has such a bay.
     *
     * @return int
     */
    public function getTotalCapacity() : int
    {
        return array_sum($this->capacities);
    }

    /**
     * @return string[] The sizes that have a capacity entry.
     */
    public function getSizes() : array
    {
        return array_keys($this->capacities);
    }

    public function isEmpty() : bool
    {
        return empty($this->capacities);
    }
}
