<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\SlotTypes;

use Mistralys\X4\Database\SlotTypes\KnownSlotTypes;
use Mistralys\X4\Database\SlotTypes\SlotTypes;
use X4Tests\Helpers\X4TestCase;

class SlotTypesTest extends X4TestCase
{
    public function test_singleton() : void
    {
        $this->assertSame(SlotTypes::getInstance(), SlotTypes::getInstance());
    }

    public function test_hasStandardTypes() : void
    {
        $collection = SlotTypes::getInstance();
        
        $this->assertTrue($collection->idExists(KnownSlotTypes::WEAPON));
        $this->assertTrue($collection->idExists(KnownSlotTypes::SHIELD));
        $this->assertTrue($collection->idExists(KnownSlotTypes::TURRET));
    }

    public function test_getProperties() : void
    {
        $weapon = SlotTypes::getInstance()->getByID(KnownSlotTypes::WEAPON);
        
        $this->assertEquals('Weapon', $weapon->getLabel());
        $this->assertEquals('weapon', $weapon->getPrimaryTag());
    }
}
