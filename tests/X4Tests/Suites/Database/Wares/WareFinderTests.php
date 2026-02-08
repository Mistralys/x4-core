<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\Wares\WareDefs;
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
}
