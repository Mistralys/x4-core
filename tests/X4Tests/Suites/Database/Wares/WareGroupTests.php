<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use X4Tests\Helpers\X4TestCase;

final class WareGroupTests extends X4TestCase
{
    public function test_allWaresHaveAValidGroupSet() : void
    {
        foreach(WareDefs::getInstance()->getAll() as $ware) {
            $this->assertSame($ware->getGroupID(), $ware->getGroup()->getID());
        }
    }

    public function test_allGroupsAreAccountedFor() : void
    {
        $usedGroups = array();

        foreach(WareDefs::getInstance()->getAll() as $ware) {
            $groupID = $ware->getGroupID();
            if(!in_array($groupID, $usedGroups)) {
                $usedGroups[] = $groupID;
            }
        }

        sort($usedGroups);
        
        $definedGroups = WareGroups::getInstance()->getIDs();
        sort($definedGroups);

        // Check that all groups used by wares are actually defined in the groups collection
        $diff = array_diff($usedGroups, $definedGroups);
        
        $this->assertEmpty($diff, 'Found ware groups that are not defined in WareGroups: '.implode(', ', $diff));
        
        // We do not enforce the reverse (that all defined groups are used), 
        // because the wares.json might not contain all items from the game yet.
    }
}
