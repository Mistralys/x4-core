<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Blueprints;

use Mistralys\X4\Database\Blueprints\BlueprintDef;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\BlueprintSelection;
use X4Tests\Helpers\X4TestCase;

final class BlueprintCollectionTests extends X4TestCase
{
    private BlueprintDefs $blueprints;

    protected function setUp(): void
    {
        parent::setUp();
        $this->blueprints = BlueprintDefs::getInstance();
    }

    public function test_getInstance(): void
    {
        $this->assertInstanceOf(BlueprintDefs::class, $this->blueprints);
        $this->assertSame($this->blueprints, BlueprintDefs::getInstance());
    }

    public function test_getAll(): void
    {
        $all = $this->blueprints->getAll();
        $this->assertNotEmpty($all);
        $this->assertContainsOnlyInstancesOf(BlueprintDef::class, $all);
    }
    
    public function test_getByID(): void
    {
        $all = $this->blueprints->getAll();
        $first = reset($all);
        
        $retrieved = $this->blueprints->getByID($first->getID());
        $this->assertSame($first, $retrieved);
    }

    public function test_createSelection(): void
    {
        $selection = $this->blueprints->createSelection();
        $this->assertInstanceOf(BlueprintSelection::class, $selection);
        $this->assertCount(count($this->blueprints->getAll()), $selection->getBlueprints());
    }
    
    public function test_blueprintIDExists(): void
    {
        $all = $this->blueprints->getAll();
        $first = reset($all);
        
        $this->assertTrue($this->blueprints->blueprintIDExists($first->getID()));
        $this->assertFalse($this->blueprints->blueprintIDExists('non_existent_blueprint_999'));
    }

    public function test_getBlueprints(): void
    {
        $this->assertSame($this->blueprints->getAll(), $this->blueprints->getBlueprints());
    }

    public function test_countBlueprints(): void
    {
        $this->assertSame(count($this->blueprints->getAll()), $this->blueprints->countBlueprints());
    }
    
    public function test_getDefault(): void
    {
        $default = $this->blueprints->getDefault();
        $this->assertInstanceOf(BlueprintDef::class, $default);
    }
}
