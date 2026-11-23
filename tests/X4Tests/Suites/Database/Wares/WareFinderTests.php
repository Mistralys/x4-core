<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareException;
use Mistralys\X4\Database\Wares\WareTransportTypes;
use X4Tests\Helpers\RawWareScanner;
use Mistralys\X4\Database\Wares\WareGroups;
use X4Tests\Helpers\X4TestCase;

final class WareFinderTests extends X4TestCase
{
    public function test_selectTag(): void
    {
        $tag = 'economy';
        $results = WareDefs::getInstance()
            ->findWares()
            ->selectTag($tag)
            ->getAll();

        $this->assertNotEmpty($results);
        foreach ($results as $item) {
            $this->assertTrue($item->hasTag($tag), 'Item '.$item->getID().' does not have tag '.$tag);
        }
    }

    public function test_selectGroup(): void
    {
        $groupID = WareGroups::GROUP_SHIPS;
        $results = WareDefs::getInstance()
            ->findWares()
            ->selectGroup($groupID)
            ->getAll();

        $this->assertNotEmpty($results);
        foreach ($results as $item) {
            $this->assertSame($groupID, $item->getGroupID());
        }
    }
    
    /**
     * Test verifying that selecting multiple groups acts as an OR filter.
     */
    public function test_selectMultipleGroups(): void
    {
        $groupA = WareGroups::GROUP_SHIPS;
        $groupB = WareGroups::GROUP_WEAPONS;
        
        $results = WareDefs::getInstance()
            ->findWares()
            ->selectGroup($groupA)
            ->selectGroup($groupB)
            ->getAll();

        $this->assertNotEmpty($results, 'Selecting multiple groups should return combined results.');
        
        $foundA = false;
        $foundB = false;
        
        foreach ($results as $item) {
            $groupID = $item->getGroupID();
            $this->assertTrue(
                $groupID === $groupA || $groupID === $groupB, 
                "Found item with group '$groupID' which is neither '$groupA' nor '$groupB'."
            );
            
            if ($groupID === $groupA) $foundA = true;
            if ($groupID === $groupB) $foundB = true;
        }
        
        $this->assertTrue($foundA, "Did not find any items from group A ($groupA)");
        $this->assertTrue($foundB, "Did not find any items from group B ($groupB)");
    }

    public function test_selectLabelSearch(): void
    {
        // Using "Energy" which should match "Energy Cells"
        $term = 'Energy';
        $results = WareDefs::getInstance()
            ->findWares()
            ->selectLabelSearch($term)
            ->getAll();

        $this->assertNotEmpty($results);
        $found = false;
        foreach ($results as $item) {
            if (stripos($item->getLabel(), $term) !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'No item found with label containing '.$term);
    }

    // region: Production, resource, licence, illegal and transport filters

    /**
     * @return array<string,array<string,mixed>> Independently scanned raw wares that exist in the extracted data.
     */
    private function getRawWares() : array
    {
        return array_intersect_key(RawWareScanner::getWares(), array_flip(WareDefs::getInstance()->getIDs()));
    }

    /**
     * @param WareDef[] $results
     * @return string[]
     */
    private function sortedIDs(array $results) : array
    {
        $ids = array_map(static fn(WareDef $ware) : string => $ware->getID(), $results);
        sort($ids);

        return $ids;
    }

    public function test_selectProducedBy() : void
    {
        $scan = function(array $methodIDs) : array {
            $ids = array();
            foreach($this->getRawWares() as $id => $raw) {
                foreach($raw['productions'] as $production) {
                    if(in_array($production['method'], $methodIDs, true)) {
                        $ids[] = $id;
                        break;
                    }
                }
            }
            sort($ids);

            return $ids;
        };

        $results = WareDefs::getInstance()->findWares()->selectProducedBy('terran')->getAll();

        $this->assertNotEmpty($scan(array('terran')));
        $this->assertSame($scan(array('terran')), $this->sortedIDs($results));
        $this->assertContains('ship_gen_m_tugboat_01_a', $this->sortedIDs($results), 'Patch-added productions must be found.');

        // Object and string are equivalent.
        $byObject = WareDefs::getInstance()->findWares()
            ->selectProducedBy(ProductionMethodDefs::getInstance()->getByID('terran'))
            ->getAll();
        $this->assertSame($scan(array('terran')), $this->sortedIDs($byObject));

        // ANY: two methods give the union.
        $union = WareDefs::getInstance()->findWares()->selectProducedBy('terran')->selectProducedBy('recycling')->getAll();
        $this->assertSame($scan(array('terran', 'recycling')), $this->sortedIDs($union));

        $this->expectException(RecordNotExistsException::class);
        WareDefs::getInstance()->findWares()->selectProducedBy('no_such_method');
    }

    public function test_selectUsingResource() : void
    {
        $scan = function(array $resourceIDs) : array {
            $ids = array();
            foreach($this->getRawWares() as $id => $raw) {
                $used = array();
                foreach($raw['productions'] as $production) {
                    foreach(array_keys($production['resources']) as $resourceID) {
                        $used[(string)$resourceID] = true;
                    }
                }

                if(count(array_intersect_key(array_flip($resourceIDs), $used)) === count($resourceIDs)) {
                    $ids[] = $id;
                }
            }
            sort($ids);

            return $ids;
        };

        $wares = WareDefs::getInstance();
        $reflection = new \ReflectionProperty(WareDef::class, 'productionObjects');

        // Start from a clean state: other tests may have built production objects already.
        foreach($wares->getAll() as $ware) {
            $reflection->setValue($ware, null);
        }

        $energy = $wares->findWares()->selectUsingResource('energycells')->getAll();

        $this->assertNotEmpty($energy);
        $this->assertSame($scan(array('energycells')), $this->sortedIDs($energy));

        // ALL: two resources give the intersection.
        $both = $wares->findWares()
            ->selectUsingResource('energycells')
            ->selectUsingResource($wares->getByID('hullparts'))
            ->getAll();
        $this->assertSame($scan(array('energycells', 'hullparts')), $this->sortedIDs($both));
        $this->assertLessThan(count($energy), count($both));

        // The filters read raw records only: no WareProduction objects are built.
        foreach($wares->getAll() as $ware) {
            $this->assertNull($reflection->getValue($ware), $ware->getID().' has built production objects.');
        }

        $this->expectException(RecordNotExistsException::class);
        $wares->findWares()->selectUsingResource('no_such_ware');
    }

    public function test_selectLicence() : void
    {
        $expected = array();
        foreach($this->getRawWares() as $id => $raw) {
            if($raw['licence'] === 'capitalship') {
                $expected[] = $id;
            }
        }
        sort($expected);

        $results = WareDefs::getInstance()->findWares()->selectLicence('capitalship')->getAll();

        $this->assertCount(39, $expected);
        $this->assertSame($expected, $this->sortedIDs($results));

        $this->assertSame(array(), WareDefs::getInstance()->findWares()->selectLicence('no_such_licence')->getAll());
    }

    public function test_selectIllegalFor() : void
    {
        $factionIDs = array('boron', 'loanshark', 'pioneers', 'terran');
        $union = array();

        foreach($factionIDs as $factionID)
        {
            $expected = array();
            foreach($this->getRawWares() as $id => $raw) {
                if(in_array($factionID, $raw['illegal'], true)) {
                    $expected[] = $id;
                }
            }
            sort($expected);

            $results = WareDefs::getInstance()->findWares()->selectIllegalFor($factionID)->getAll();

            $this->assertNotEmpty($expected, $factionID);
            $this->assertSame($expected, $this->sortedIDs($results), $factionID);

            $union = array_merge($union, $expected);
        }

        $union = array_values(array_unique($union));
        sort($union);
        $this->assertCount(18, $union);

        $finder = WareDefs::getInstance()->findWares();
        foreach($factionIDs as $factionID) {
            $finder->selectIllegalFor(FactionDefs::getInstance()->getByID($factionID));
        }
        $this->assertSame($union, $this->sortedIDs($finder->getAll()), 'Several factions combine as ANY.');

        $this->expectException(RecordNotExistsException::class);
        WareDefs::getInstance()->findWares()->selectIllegalFor('no_such_faction');
    }

    public function test_selectTransportType() : void
    {
        $solid = WareDefs::getInstance()->findWares()->selectTransportType(WareTransportTypes::TRANSPORT_SOLID)->getAll();

        $this->assertCount(8, $solid);
        foreach($solid as $ware) {
            $this->assertSame(WareTransportTypes::TRANSPORT_SOLID, $ware->getTransportTypeID());
        }

        // Different kinds of filters act as AND.
        $combined = WareDefs::getInstance()->findWares()
            ->selectTransportType(WareTransportTypes::TRANSPORT_SHIP)
            ->selectGroup(WareGroups::GROUP_SHIPS)
            ->getAll();

        $this->assertNotEmpty($combined);
        foreach($combined as $ware) {
            $this->assertSame(WareGroups::GROUP_SHIPS, $ware->getGroupID());
            $this->assertSame(WareTransportTypes::TRANSPORT_SHIP, $ware->getTransportTypeID());
        }

        // Several types combine as ANY.
        $either = WareDefs::getInstance()->findWares()
            ->selectTransportType(WareTransportTypes::TRANSPORT_SOLID)
            ->selectTransportType(WareTransportTypes::TRANSPORT_LIQUID)
            ->getAll();
        $this->assertCount(11, $either);

        try {
            WareDefs::getInstance()->findWares()->selectTransportType('no_such_type');
            $this->fail('An exception should have been thrown.');
        } catch (WareException $e) {
            $this->assertSame(WareException::ERROR_UNKNOWN_TRANSPORT_TYPE, $e->getCode());
        }
    }

    // endregion
}
