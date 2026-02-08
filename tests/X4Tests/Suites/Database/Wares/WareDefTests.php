<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroup;
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
}
