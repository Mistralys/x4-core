<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\Wares\WareTransportTypes;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class WareTransportTypesTests extends TestCase
{
    public function test_tenConstants() : void
    {
        $constants = (new ReflectionClass(WareTransportTypes::class))->getConstants();

        $this->assertCount(10, $constants);
        $this->assertCount(10, WareTransportTypes::getIDs());
        $this->assertEqualsCanonicalizing(array_values($constants), WareTransportTypes::getIDs());
    }

    public function test_cargoIDs() : void
    {
        $this->assertSame(
            array('container', 'solid', 'liquid', 'condensate'),
            WareTransportTypes::getCargoIDs()
        );
    }

    public function test_idExists() : void
    {
        $this->assertTrue(WareTransportTypes::idExists('passenger'));
        $this->assertTrue(WareTransportTypes::idExists('container'));
        $this->assertFalse(WareTransportTypes::idExists('unknown'));
    }

    public function test_isCargoTypeID() : void
    {
        $this->assertTrue(WareTransportTypes::isCargoTypeID('liquid'));
        $this->assertFalse(WareTransportTypes::isCargoTypeID('inventory'));
        $this->assertFalse(WareTransportTypes::isCargoTypeID('unknown'));
    }
}
