<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Wares\WareDef;

/**
 * The built-in default loadout of a ship, as a flat list of items.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipLoadout
{
    /**
     * @var ShipLoadoutItem[]
     */
    public readonly array $items;

    /**
     * @param ShipLoadoutItem[] $items
     */
    public function __construct(array $items = array())
    {
        $this->items = array_values($items);
    }

    /**
     * @param array<int,array<string,mixed>> $items
     * @return self
     */
    public static function fromArray(array $items) : self
    {
        $result = array();

        foreach($items as $item) {
            $result[] = ShipLoadoutItem::fromArray($item);
        }

        return new self($result);
    }

    /**
     * @return array<int,array{type:string,macroID:string,count:int,optional:bool}>
     */
    public function toArray() : array
    {
        $result = array();

        foreach($this->items as $item) {
            $result[] = $item->toArray();
        }

        return $result;
    }

    /**
     * @return ShipLoadoutItem[]
     */
    public function getItems() : array
    {
        return $this->items;
    }

    /**
     * @param string $type One of the {@see ShipLoadoutItem} `TYPE_*` constants.
     * @return ShipLoadoutItem[]
     */
    public function getItemsByType(string $type) : array
    {
        return array_values(array_filter(
            $this->items,
            static fn(ShipLoadoutItem $item) : bool => $item->type === $type
        ));
    }

    /**
     * The total count of the items of a type, i.e. the sum of their counts.
     *
     * @param string $type One of the {@see ShipLoadoutItem} `TYPE_*` constants.
     * @return int
     */
    public function countByType(string $type) : int
    {
        $total = 0;

        foreach($this->getItemsByType($type) as $item) {
            $total += $item->count;
        }

        return $total;
    }

    /**
     * @return WareDef[] The wares of all items that resolve to a ware, in item order.
     */
    public function getWares() : array
    {
        $wares = array();

        foreach($this->items as $item) {
            $ware = $item->getWare();

            if($ware !== null) {
                $wares[] = $ware;
            }
        }

        return $wares;
    }

    public function isEmpty() : bool
    {
        return empty($this->items);
    }
}
