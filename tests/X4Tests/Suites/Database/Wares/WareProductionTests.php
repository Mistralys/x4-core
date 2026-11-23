<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\ProductionMethods\ProductionMethodDef;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareProduction;
use ReflectionProperty;
use X4Tests\Helpers\RawWareScanner;
use X4Tests\Helpers\X4TestCase;

final class WareProductionTests extends X4TestCase
{
    protected function tearDown() : void
    {
        $this->setMacroIndex(null);
        parent::tearDown();
    }

    /**
     * @param array<string,WareDef>|null $index
     */
    private function setMacroIndex(?array $index) : void
    {
        $prop = new ReflectionProperty(WareDefs::class, 'macroIndex');
        $prop->setValue(WareDefs::getInstance(), $index);
    }

    private function makeWare(string $id, ?array $price) : WareDef
    {
        return new WareDef($id, $id . '_macro', $id, 'group', \Mistralys\X4\Database\Core\VariantID::fromID('::'), array(), 'core', '', array(), array('tags' => array()), $price);
    }

    private function registerWares() : void
    {
        $this->setMacroIndex(array(
            'ore' => $this->makeWare('ore', array('priceMin' => 1, 'priceAverage' => 10, 'priceMax' => 20)),
            'gas' => $this->makeWare('gas', array('priceMin' => 1, 'priceAverage' => 3, 'priceMax' => 5)),
            'unpriced' => $this->makeWare('unpriced', null),
        ));
    }

    public function test_roundTrip() : void
    {
        $record = array(
            WareProduction::KEY_METHOD_ID => 'default',
            WareProduction::KEY_DURATION => 600.0,
            WareProduction::KEY_QUANTITY => 30,
            WareProduction::KEY_RESOURCES => array('ore' => 5, 'gas' => 2),
            WareProduction::KEY_EFFECTS => array('work' => 0.5),
        );

        $production = WareProduction::fromArray($record);

        $this->assertSame('default', $production->methodID);
        $this->assertSame(600.0, $production->duration);
        $this->assertSame(30, $production->quantity);
        $this->assertSame(array('ore' => 5, 'gas' => 2), $production->resources);
        $this->assertSame(array('work' => 0.5), $production->effects);
        $this->assertSame($record, $production->toArray());
    }

    public function test_getResourceCost() : void
    {
        $this->registerWares();

        $production = new WareProduction('default', 10.0, 4, array('ore' => 5, 'gas' => 2));

        $this->assertSame(5 * 10 + 2 * 3, $production->getResourceCost());
        $this->assertSame(14.0, $production->getResourceCostPerUnit());
    }

    public function test_getResourceCost_nullWhenUnknownOrUnpriced() : void
    {
        $this->registerWares();

        $unknown = new WareProduction('default', 10.0, 4, array('ore' => 5, 'nonexistent' => 1));
        $unpriced = new WareProduction('default', 10.0, 4, array('unpriced' => 1));

        $this->assertNull($unknown->getResourceCost());
        $this->assertNull($unknown->getResourceCostPerUnit());
        $this->assertNull($unpriced->getResourceCost());
    }

    public function test_getResourceCost_noResources() : void
    {
        $this->registerWares();

        $production = new WareProduction('default', 10.0, 4);

        $this->assertSame(0, $production->getResourceCost());
        $this->assertSame(0.0, $production->getResourceCostPerUnit());
    }

    public function test_getResourceCostPerUnit_zeroQuantity() : void
    {
        $this->registerWares();

        $production = new WareProduction('default', 10.0, 0, array('ore' => 1));

        $this->assertSame(10, $production->getResourceCost());
        $this->assertNull($production->getResourceCostPerUnit());
    }

    public function test_getResourceWares_skipsUnknown() : void
    {
        $this->registerWares();

        $production = new WareProduction('default', 10.0, 4, array('ore' => 5, 'nonexistent' => 1, 'gas' => 2));
        $wares = $production->getResourceWares();

        $this->assertSame(array('ore', 'gas'), array_map(static fn(WareDef $w) : string => $w->getID(), $wares));
    }

    public function test_getMethod() : void
    {
        $instance = new class extends ProductionMethodDefs {
            public function __construct() {}

            protected function loadRecords() : array
            {
                return array(array('id' => 'default', 'label' => 'Default'));
            }
        };

        $prop = new ReflectionProperty(ProductionMethodDefs::class, 'instance');
        $original = $prop->getValue();
        $prop->setValue(null, $instance);

        try {
            $known = new WareProduction('default', 10.0, 1);
            $unknown = new WareProduction('no_such_method_xyz', 10.0, 1);

            $this->assertInstanceOf(ProductionMethodDef::class, $known->getMethod());
            $this->assertSame('default', $known->getMethod()->getID());
            $this->assertNull($unknown->getMethod());
        } finally {
            $prop->setValue(null, $original);
        }
    }

    public function test_getResourceCost_matchesRawPrices() : void
    {
        $this->setMacroIndex(null);

        $wares = WareDefs::getInstance();
        $raw = RawWareScanner::getWares();
        $total = 0;

        foreach($wares->getAll() as $ware)
        {
            $productions = $ware->getProductions();

            foreach($productions as $idx => $production)
            {
                $total++;
                $expected = 0;

                foreach($raw[$ware->getID()]['productions'][$idx]['resources'] as $resourceID => $amount) {
                    $price = $raw[(string)$resourceID]['price'] ?? null;
                    if($price === null) {
                        $expected = null;
                        break;
                    }

                    $expected += $amount * $price['average'];
                }

                $this->assertSame($expected, $production->getResourceCost(), $ware->getID().' production '.$idx);
            }
        }

        $this->assertSame(1695, $total);
    }
}
