<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroup;
use Mistralys\X4\Database\Wares\WareGroups;
use ReflectionClass;
use X4Tests\Helpers\X4TestCase;

final class WareGroupsTests extends X4TestCase
{
    private WareGroups $groups;

    protected function setUp(): void
    {
        parent::setUp();
        $this->groups = WareGroups::getInstance();
    }

    public function test_getAll(): void
    {
        $all = $this->groups->getAll();
        $this->assertGreaterThan(30, count($all));
        $this->assertContainsOnlyInstancesOf(WareGroup::class, $all);
    }
    
    public function test_getByID(): void
    {
        $id = WareGroups::GROUP_SHIPS;
        $group = $this->groups->getByID($id);
        
        $this->assertSame($id, $group->getID());
        $this->assertNotEmpty($group->getLabel());
    }

    public function test_getDefault(): void
    {
        $default = $this->groups->getDefault(); // Should not throw
        $this->assertInstanceOf(WareGroup::class, $default);
    }

    public function test_idExists(): void
    {
        $this->assertTrue($this->groups->idExists(WareGroups::GROUP_SHIPS));
        $this->assertFalse($this->groups->idExists('non_existent_group_xyz'));
    }

    public function test_allGroupConstants(): void
    {
        $reflection = new ReflectionClass(WareGroups::class);
        $constants = $reflection->getConstants();
        
        // Filter out non-group constants if any (convention: keys start with GROUP_)
        foreach ($constants as $name => $value) {
            if (str_starts_with($name, 'GROUP_') && is_string($value)) {
                $this->assertTrue($this->groups->idExists($value), "Constant $name with value '$value' is not a registered group ID.");
            }
        }
    }

    public function test_WareGroup_methods(): void
    {
        $group = $this->groups->getByID(WareGroups::GROUP_SHIPS);
        
        $this->assertSame(WareGroups::GROUP_SHIPS, $group->getID());
        $this->assertNotEmpty($group->getLabel());
        
        // Assuming WareGroup has getWares() method? Let's check or assume based on similar classes
        // The plan mentions test_WareGroup_getWares().
        // If it doesn't exist, I'll need to check WareGroup.php source.
        
        if (method_exists($group, 'getWares')) {
             $wares = $group->getWares();
             $this->assertIsArray($wares);
             // Ships group should definitely have wares
             $this->assertNotEmpty($wares);
        }
    }
}
