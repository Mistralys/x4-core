<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipLoadout
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Ships\MacroProperties\ShipLoadout;
use Mistralys\X4\Database\Ships\MacroProperties\ShipLoadoutItem;
use X4Tests\Helpers\X4TestCase;

final class ShipLoadoutTests extends X4TestCase
{
    private const ITEMS = array(
        array('type' => 'engine', 'macroID' => 'engine_arg_s_allround_01_mk1_macro', 'count' => 2, 'optional' => false),
        array('type' => 'shield', 'macroID' => 'shield_arg_s_standard_01_mk1_macro', 'count' => 1, 'optional' => false),
        array('type' => 'engine', 'macroID' => 'engine_arg_s_allround_01_mk1_macro', 'count' => 1, 'optional' => true),
        array('type' => 'software', 'macroID' => 'software_dockmk2', 'count' => 1, 'optional' => false),
        array('type' => 'weapon', 'macroID' => 'weapon_unknown_macro', 'count' => 3, 'optional' => false),
    );

    public function test_roundTrip() : void
    {
        $this->assertSame(self::ITEMS, ShipLoadout::fromArray(self::ITEMS)->toArray());
        $this->assertTrue(ShipLoadout::fromArray(array())->isEmpty());
    }

    public function test_itemTypesAndNoWareIDKey() : void
    {
        $item = ShipLoadoutItem::fromArray(self::ITEMS[0]);

        $this->assertSame('engine', $item->type);
        $this->assertSame('engine_arg_s_allround_01_mk1_macro', $item->macroID);
        $this->assertSame(2, $item->count);
        $this->assertFalse($item->optional);
        $this->assertArrayNotHasKey('wareID', $item->toArray());

        $this->assertSame(
            array('engine', 'shield', 'weapon', 'turret', 'thruster', 'software', 'ammunition'),
            ShipLoadoutItem::getTypes()
        );
    }

    public function test_itemWareResolution() : void
    {
        $engine = ShipLoadoutItem::fromArray(self::ITEMS[0])->getWare();
        $this->assertNotNull($engine);
        $this->assertSame('engine_arg_s_allround_01_mk1', $engine->getID());

        $software = ShipLoadoutItem::fromArray(self::ITEMS[3])->getWare();
        $this->assertNotNull($software, 'Software resolves through its ware ID.');
        $this->assertSame('software_dockmk2', $software->getID());

        $this->assertNull(ShipLoadoutItem::fromArray(self::ITEMS[4])->getWare());
    }

    public function test_itemsByTypeAndCounts() : void
    {
        $loadout = ShipLoadout::fromArray(self::ITEMS);

        $this->assertCount(5, $loadout->getItems());
        $this->assertCount(2, $loadout->getItemsByType(ShipLoadoutItem::TYPE_ENGINE));
        $this->assertSame(3, $loadout->countByType(ShipLoadoutItem::TYPE_ENGINE));
        $this->assertSame(3, $loadout->countByType(ShipLoadoutItem::TYPE_WEAPON));
        $this->assertSame(0, $loadout->countByType(ShipLoadoutItem::TYPE_TURRET));
        $this->assertSame(array(), $loadout->getItemsByType(ShipLoadoutItem::TYPE_TURRET));
    }

    public function test_waresContainOnlyResolvableItems() : void
    {
        $ids = array_map(
            static fn($ware) : string => $ware->getID(),
            ShipLoadout::fromArray(self::ITEMS)->getWares()
        );

        $this->assertSame(
            array(
                'engine_arg_s_allround_01_mk1',
                'shield_arg_s_standard_01_mk1',
                'engine_arg_s_allround_01_mk1',
                'software_dockmk2'
            ),
            $ids
        );
    }
}
