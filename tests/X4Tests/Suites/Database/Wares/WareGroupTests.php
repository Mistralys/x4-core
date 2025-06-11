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

        $this->assertSame(
            $usedGroups,
            WareGroups::getInstance()->getIDs(),
            'The list of groups in use by the wares does not match the ware groups list.'
        );
    }
}
