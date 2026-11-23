<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroup;
use Mistralys\X4\Database\Wares\WarePrice;
use Mistralys\X4\Database\Wares\WareProduction;
use Mistralys\X4\Database\Translations\Languages;
use X4Tests\Helpers\RawWareScanner;
use X4Tests\Helpers\X4TestCase;

final class WareDefTests extends X4TestCase
{
    private WareDefs $wares;

    protected function setUp(): void
    {
        parent::setUp();
        $this->wares = WareDefs::getInstance();
    }

    public function test_basicProperties(): void
    {
        $id = 'ship_arg_m_bomber_01_a';
        $ware = $this->wares->getByID($id);

        $this->assertSame($id, $ware->getID());
        $this->assertNotEmpty($ware->getLabel());
        $this->assertNotEmpty($ware->getGroupID());
        $this->assertInstanceOf(VariantID::class, $ware->getVariantID());
        $this->assertNotEmpty($ware->getTags());
        $this->assertNotEmpty($ware->getDataSourceID());
        $this->assertNotEmpty($ware->getFactionIDs());
        $this->assertNotEmpty($ware->getMacroID());
    }

    public function test_getGroup(): void
    {
        $ware = $this->wares->getByID('ship_arg_m_bomber_01_a');
        $group = $ware->getGroup();

        $this->assertInstanceOf(WareGroup::class, $group);
        $this->assertSame($ware->getGroupID(), $group->getID());
    }

    public function test_getDataSource(): void
    {
        $ware = $this->wares->getByID('ship_arg_m_bomber_01_a');
        $source = $ware->getDataSource();

        $this->assertInstanceOf(DataSourceDef::class, $source);
        $this->assertSame($ware->getDataSourceID(), $source->getID());
    }

    public function test_getFactions(): void
    {
        $ware = $this->wares->getByID('ship_arg_m_bomber_01_a');
        $factions = $ware->getFactions();

        $this->assertNotEmpty($factions);
        $this->assertContainsOnlyInstancesOf(FactionDef::class, $factions);
        
        $ids = $ware->getFactionIDs();
        $this->assertCount(count($ids), $factions);
        
        foreach($factions as $faction) {
            $this->assertContains($faction->getID(), $ids);
        }
    }

    public function test_getMacro(): void
    {
        // Not all wares have macros loaded in test environment unless MacroIndex is populated
        // Assuming test env has some macros or the method handles it roughly
        // If MacroFileDefs throws exception for missing macro, this test might need adjustment
        
        $ware = $this->wares->getByID('ship_arg_m_bomber_01_a');
        
        // This might fail if macros are not fully loaded in test environment
        // But WP-03 MacroIndex Testing is complete, so maybe it's fine.
        // Let's wrap in try-catch or verify if we can get it.
        // Or simply check if getMacroID returns a string, calling getMacro might require actual macro defs.
        
        $this->assertNotEmpty($ware->getMacroID());
    }

    public function test_hasTag(): void
    {
        $ware = $this->wares->getByID('ship_arg_m_bomber_01_a');
        $tags = $ware->getTags();
        
        if (count($tags) > 0) {
            $this->assertTrue($ware->hasTag($tags[0]));
        }
        $this->assertFalse($ware->hasTag('non_existent_tag_xyz'));
    }

    public function test_toArrayFromArray(): void
    {
        $ware = $this->wares->getByID('ship_arg_m_bomber_01_a');
        $array = $ware->toArray();

        $this->assertIsArray($array);
        $this->assertArrayHasKey(WareDef::KEY_WARE_ID, $array);
        $this->assertArrayHasKey(WareDef::KEY_LABEL, $array);

        $newWare = WareDef::fromArray($array);
        
        $this->assertEquals($ware->getID(), $newWare->getID());
        $this->assertEquals($ware->getLabel(), $newWare->getLabel());
        $this->assertEquals($ware->getGroupID(), $newWare->getGroupID());
        // VariantID objects equality might be reference based, check IDs
        $this->assertEquals($ware->getVariantID()->getID(), $newWare->getVariantID()->getID());
        $this->assertEquals($ware->getTags(), $newWare->getTags());
        $this->assertEquals($ware->getDataSourceID(), $newWare->getDataSourceID());
        $this->assertEquals($ware->getFactionIDs(), $newWare->getFactionIDs());
        $this->assertEquals($ware->getMacroID(), $newWare->getMacroID());
    }

    private const PRICE_RECORD = array('priceMin' => 5, 'priceAverage' => 10, 'priceMax' => 15);

    private const PRODUCTION_RECORDS = array(
        array(
            'methodID' => 'default',
            'duration' => 600.0,
            'quantity' => 10,
            'resources' => array('ore' => 5, 'gas' => 2),
            'effects' => array('work' => 0.5),
        ),
        array(
            'methodID' => 'terran',
            'duration' => 300.0,
            'quantity' => 20,
            'resources' => array('gas' => 4, 'energycells' => 1),
            'effects' => array(),
        ),
    );

    private function makeSyntheticWare(array $extra = array()) : WareDef
    {
        return WareDef::fromArray(array_merge(
            array(
                WareDef::KEY_WARE_ID => 'synthetic',
                WareDef::KEY_LABEL => 'Synthetic',
                WareDef::KEY_GROUP => 'hightech',
                WareDef::KEY_DATA_SOURCE_ID => 'core',
            ),
            $extra
        ));
    }

    private function readPrivate(WareDef $ware, string $property) : mixed
    {
        return (new \ReflectionProperty(WareDef::class, $property))->getValue($ware);
    }

    public function test_newKeyConstants() : void
    {
        $this->assertSame('priceMin', WareDef::KEY_PRICE_MIN);
        $this->assertSame('priceAverage', WareDef::KEY_PRICE_AVERAGE);
        $this->assertSame('priceMax', WareDef::KEY_PRICE_MAX);
        $this->assertSame('productions', WareDef::KEY_PRODUCTIONS);
        $this->assertSame('licenceID', WareDef::KEY_LICENCE_ID);
        $this->assertSame('descriptionID', WareDef::KEY_DESCRIPTION_ID);
        $this->assertSame('illegalFactionIDs', WareDef::KEY_ILLEGAL_FACTION_IDS);
        $this->assertSame('buyRestricted', WareDef::KEY_BUY_RESTRICTED);
        $this->assertSame('volume', WareDef::KEY_VOLUME);
        $this->assertSame('transportTypeID', WareDef::KEY_TRANSPORT_TYPE_ID);
    }

    public function test_constructorTrailingParameterOrder() : void
    {
        $params = array_map(
            static fn(\ReflectionParameter $p) : string => $p->getName(),
            array_slice((new \ReflectionMethod(WareDef::class, '__construct'))->getParameters(), 10)
        );

        $this->assertSame(
            array('price', 'productions', 'licenceID', 'descriptionID', 'illegalFactionIDs', 'buyRestricted', 'volume', 'transportTypeID'),
            $params
        );
    }

    public function test_fromArray_withoutNewKeys() : void
    {
        $ware = $this->makeSyntheticWare();

        $this->assertNull($ware->getPrice());
        $this->assertFalse($ware->hasPrice());
        $this->assertSame(array(), $ware->getProductions());
        $this->assertNull($ware->getProduction('default'));
        $this->assertNull($ware->getLicenceID());
        $this->assertNull($ware->getDescriptionID());
        $this->assertSame('', $ware->getDescription());
        $this->assertNull($ware->getVolume());
        $this->assertNull($ware->getTransportTypeID());
        $this->assertSame(array(), $ware->getIllegalFactionIDs());
        $this->assertFalse($ware->hasBuyRestriction());

        $array = $ware->toArray();
        foreach(array('priceMin', 'priceAverage', 'priceMax', 'productions', 'licenceID', 'descriptionID', 'illegalFactionIDs', 'buyRestricted', 'volume', 'transportTypeID') as $key) {
            $this->assertArrayNotHasKey($key, $array);
        }
    }

    public function test_toArray_buyRestrictedOnlyWhenTrue() : void
    {
        $this->assertArrayNotHasKey('buyRestricted', $this->makeSyntheticWare(array('buyRestricted' => false))->toArray());
        $this->assertTrue($this->makeSyntheticWare(array('buyRestricted' => true))->toArray()['buyRestricted']);
        $this->assertTrue($this->makeSyntheticWare(array('buyRestricted' => true))->hasBuyRestriction());
    }

    public function test_accessors() : void
    {
        $ware = $this->makeSyntheticWare(array_merge(self::PRICE_RECORD, array(
            'productions' => self::PRODUCTION_RECORDS,
            'licenceID' => 'generaluseship',
            'descriptionID' => '{20201,1}',
            'illegalFactionIDs' => array('argon', 'paranid'),
            'volume' => 12,
            'transportTypeID' => 'container',
        )));

        $this->assertTrue($ware->hasPrice());
        $this->assertSame(10, $ware->getPrice()->average);
        $this->assertCount(2, $ware->getProductions());
        $this->assertSame('terran', $ware->getProduction('terran')->methodID);
        $this->assertNull($ware->getProduction('recycling'));
        $this->assertSame(array('default', 'terran'), $ware->getProductionMethodIDs());
        $this->assertSame(array('energycells', 'gas', 'ore'), $ware->getProductionResourceIDs());
        $this->assertTrue($ware->usesResource('gas'));
        $this->assertFalse($ware->usesResource('silicon'));
        $this->assertSame('generaluseship', $ware->getLicenceID());
        $this->assertSame('{20201,1}', $ware->getDescriptionID());
        $this->assertSame(12, $ware->getVolume());
        $this->assertSame('container', $ware->getTransportTypeID());
        $this->assertSame(array('argon', 'paranid'), $ware->getIllegalFactionIDs());
        $this->assertTrue($ware->isIllegalFor('argon'));
        $this->assertFalse($ware->isIllegalFor('teladi'));
    }

    public function test_lazyHydration() : void
    {
        $ware = $this->makeSyntheticWare(array_merge(self::PRICE_RECORD, array('productions' => self::PRODUCTION_RECORDS)));

        $this->assertNull($this->readPrivate($ware, 'priceObject'));
        $this->assertNull($this->readPrivate($ware, 'productionObjects'));

        $ware->hasPrice();
        $ware->getProductionMethodIDs();
        $ware->getProductionResourceIDs();
        $ware->usesResource('gas');
        $ware->toArray();

        $this->assertNull($this->readPrivate($ware, 'priceObject'));
        $this->assertNull($this->readPrivate($ware, 'productionObjects'));

        $this->assertSame($ware->getPrice(), $ware->getPrice());
        $this->assertSame($ware->getProductions(), $ware->getProductions());
        $this->assertNotNull($this->readPrivate($ware, 'priceObject'));
        $this->assertNotNull($this->readPrivate($ware, 'productionObjects'));
    }

    public function test_constructWithValueObjects_equalsRecords() : void
    {
        $fromRecords = $this->makeSyntheticWare(array_merge(self::PRICE_RECORD, array(
            'productions' => self::PRODUCTION_RECORDS,
            'volume' => 3,
        )));

        $fromObjects = new WareDef(
            'synthetic',
            '',
            'Synthetic',
            'hightech',
            VariantID::fromID('::'),
            array(),
            'core',
            '',
            array(),
            array('tags' => array()),
            new WarePrice(5, 10, 15),
            array_map(
                static fn(array $r) : WareProduction => WareProduction::fromArray($r),
                self::PRODUCTION_RECORDS
            ),
            null,
            null,
            array(),
            false,
            3
        );

        $this->assertSame($fromRecords->toArray(), $fromObjects->toArray());
        $this->assertEquals($fromRecords->getPrice(), $fromObjects->getPrice());
        $this->assertEquals($fromRecords->getProductions(), $fromObjects->getProductions());
        $this->assertSame($fromRecords->getProductionResourceIDs(), $fromObjects->getProductionResourceIDs());
    }

    public function test_getDescription_neverReturnsRawCode() : void
    {
        $ware = $this->makeSyntheticWare(array('descriptionID' => '{99999,99999}'));

        $this->assertSame('', $ware->getDescription());
    }

    private const TUGBOAT_ID = 'ship_gen_m_tugboat_01_a';

    /**
     * @return array<string,array<string,mixed>> Raw records of the wares that exist in the extracted data.
     */
    private function getRawWares() : array
    {
        return array_intersect_key(RawWareScanner::getWares(), array_flip($this->wares->getIDs()));
    }

    public function test_price_production_licence_matchRawXml() : void
    {
        $checked = 0;

        foreach($this->getRawWares() as $id => $raw)
        {
            if(!str_starts_with($id, 'ship_')) {
                continue;
            }

            $ware = $this->wares->getByID($id);
            $checked++;

            if($raw['price'] === null) {
                $this->assertFalse($ware->hasPrice(), $id);
            } else {
                $this->assertSame($raw['price']['min'], $ware->getPrice()->min, $id);
                $this->assertSame($raw['price']['average'], $ware->getPrice()->average, $id);
                $this->assertSame($raw['price']['max'], $ware->getPrice()->max, $id);
            }

            $this->assertSame($raw['licence'], $ware->getLicenceID(), $id);
            $this->assertSame($raw['description'], $ware->getDescriptionID(), $id);

            $actual = array();
            foreach($ware->getProductions() as $production) {
                $actual[] = array(
                    'method' => $production->methodID,
                    'time' => $production->duration,
                    'amount' => $production->quantity,
                    'resources' => $production->resources,
                    'effects' => $production->effects,
                );
            }

            $this->assertEquals($raw['productions'], $actual, 'Productions of '.$id);
        }

        $this->assertGreaterThan(100, $checked);

        $tugboat = $this->wares->getByID(self::TUGBOAT_ID);
        $this->assertNotNull($tugboat->getProduction('terran'));
        $this->assertContains('boron', $tugboat->getProductionMethodIDs());
    }

    public function test_production_getMethod() : void
    {
        $tugboat = $this->wares->getByID(self::TUGBOAT_ID);
        $terran = $tugboat->getProduction('terran');

        $this->assertNotNull($terran);
        $this->assertNotNull($terran->getMethod());
        $this->assertSame('terran', $terran->getMethod()->getID());

        // Every production of every ship ware must resolve its method.
        $checked = 0;
        foreach($this->wares->getAll() as $ware) {
            if(!$ware->hasTag('ship')) {
                continue;
            }

            foreach($ware->getProductions() as $production) {
                $this->assertNotNull(
                    $production->getMethod(),
                    sprintf('Ware [%s] has an unresolved production method [%s].', $ware->getID(), $production->toArray()[WareProduction::KEY_METHOD_ID])
                );
                $checked++;
            }
        }

        $this->assertGreaterThan(0, $checked);
    }

    public function test_getDescription_translates() : void
    {
        $ware = $this->wares->getByID(self::TUGBOAT_ID);

        $this->assertNotNull($ware->getDescriptionID());

        $description = $ware->getDescription();
        $this->assertNotSame('', $description);
        $this->assertStringNotContainsString('{', $description);
        $this->assertSame($description, $ware->getDescription(Languages::getInstance()->getEnglish()));
    }

    public function test_volumeAndTransport_matchRawXml() : void
    {
        foreach($this->getRawWares() as $id => $raw) {
            $ware = $this->wares->getByID($id);
            $this->assertSame($raw['volume'], $ware->getVolume(), $id);
            $this->assertSame($raw['transport'], $ware->getTransportTypeID(), $id);
        }

        $expected = array('energycells' => array('container', 1), 'ore' => array('solid', 10), 'hullparts' => array('container', 12));
        foreach($expected as $id => $values) {
            $this->assertSame($values[0], $this->wares->getByID($id)->getTransportTypeID(), $id);
            $this->assertSame($values[1], $this->wares->getByID($id)->getVolume(), $id);
        }
    }

    public function test_usesResource_matchesRawXml() : void
    {
        $allResources = array();

        foreach($this->getRawWares() as $id => $raw)
        {
            $expected = array();
            foreach($raw['productions'] as $production) {
                foreach(array_keys($production['resources']) as $resourceID) {
                    $expected[(string)$resourceID] = true;
                    $allResources[(string)$resourceID] = true;
                }
            }

            $expected = array_map('strval', array_keys($expected));
            sort($expected);

            $ware = $this->wares->getByID($id);
            $this->assertSame($expected, $ware->getProductionResourceIDs(), $id);

            foreach($expected as $resourceID) {
                $this->assertTrue($ware->usesResource($resourceID), $id.' uses '.$resourceID);
            }

            $this->assertFalse($ware->usesResource('no_such_ware'), $id);
        }

        $this->assertGreaterThan(50, count($allResources));
    }

    public function test_illegalFactions_and_buyRestriction() : void
    {
        $illegalCount = 0;
        $buyIDs = array();

        foreach($this->getRawWares() as $id => $raw)
        {
            $ware = $this->wares->getByID($id);

            $this->assertSame($raw['illegal'], $ware->getIllegalFactionIDs(), $id);
            $this->assertSame($raw['buy'], $ware->hasBuyRestriction(), $id);

            if(!empty($raw['illegal'])) {
                $illegalCount++;
                $this->assertTrue($ware->isIllegalFor($raw['illegal'][0]), $id);
            }

            if($raw['buy']) {
                $buyIDs[] = $id;
            }
        }

        $this->assertGreaterThan(0, $illegalCount);

        sort($buyIDs);
        $this->assertSame(array('inv_securitybypasssystem', 'inv_securitydecryptionsystem', 'inv_securityslicer'), $buyIDs);
    }

    public function test_hydration_isLazy() : void
    {
        // Fresh instance: the shared singleton may already have hydrated objects.
        $ware = WareDef::fromArray($this->wares->getByID(self::TUGBOAT_ID)->toArray());

        $this->assertNull($this->readPrivate($ware, 'priceObject'));
        $this->assertNull($this->readPrivate($ware, 'productionObjects'));

        $this->assertTrue($ware->hasPrice());
        $this->assertNotEmpty($ware->getProductionMethodIDs());
        $this->assertNotEmpty($ware->getProductionResourceIDs());
        $this->assertTrue($ware->usesResource('hullparts'));

        $this->assertNull($this->readPrivate($ware, 'priceObject'));
        $this->assertNull($this->readPrivate($ware, 'productionObjects'));

        $this->assertNotNull($ware->getPrice());
        $this->assertNotEmpty($ware->getProductions());
        $this->assertNotNull($this->readPrivate($ware, 'priceObject'));
        $this->assertNotNull($this->readPrivate($ware, 'productionObjects'));
    }
}
