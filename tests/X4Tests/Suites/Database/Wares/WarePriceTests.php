<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WarePrice;
use PHPUnit\Framework\TestCase;

final class WarePriceTests extends TestCase
{
    public function test_roundTrip() : void
    {
        $record = array(
            WareDef::KEY_PRICE_MIN => 10,
            WareDef::KEY_PRICE_AVERAGE => 15,
            WareDef::KEY_PRICE_MAX => 20,
        );

        $price = WarePrice::fromArray($record);

        $this->assertSame(10, $price->min);
        $this->assertSame(15, $price->average);
        $this->assertSame(20, $price->max);
        $this->assertSame($record, $price->toArray());
    }

    public function test_flatKeyNames() : void
    {
        $this->assertSame(
            array('priceMin', 'priceAverage', 'priceMax'),
            array_keys((new WarePrice(1, 2, 3))->toArray())
        );
    }
}
