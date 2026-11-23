<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\ArrayDataCollection;

/**
 * The reference price range of a ware (`<price min average max>`).
 *
 * In the ware records, the three values are stored as the flat keys
 * {@see WareDef::KEY_PRICE_MIN}, {@see WareDef::KEY_PRICE_AVERAGE}
 * and {@see WareDef::KEY_PRICE_MAX}.
 *
 * @package X4 Database
 * @subpackage Wares
 */
final class WarePrice
{
    public readonly int $min;
    public readonly int $average;
    public readonly int $max;

    public function __construct(int $min, int $average, int $max)
    {
        $this->min = $min;
        $this->average = $average;
        $this->max = $max;
    }

    /**
     * @param array<string,mixed> $record A ware record fragment with the flat `price*` keys.
     * @return WarePrice
     */
    public static function fromArray(array $record) : WarePrice
    {
        $data = ArrayDataCollection::create($record);

        return new WarePrice(
            $data->getInt(WareDef::KEY_PRICE_MIN),
            $data->getInt(WareDef::KEY_PRICE_AVERAGE),
            $data->getInt(WareDef::KEY_PRICE_MAX)
        );
    }

    /**
     * @return array{priceMin:int,priceAverage:int,priceMax:int}
     */
    public function toArray() : array
    {
        return array(
            WareDef::KEY_PRICE_MIN => $this->min,
            WareDef::KEY_PRICE_AVERAGE => $this->average,
            WareDef::KEY_PRICE_MAX => $this->max,
        );
    }
}
