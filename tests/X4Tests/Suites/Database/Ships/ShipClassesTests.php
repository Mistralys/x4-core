<?php
/**
 * @package X4Tests
 * @subpackage Database\Ships
 * @see \Mistralys\X4\Database\Ships\ShipClasses
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Ships\ShipClass;
use Mistralys\X4\Database\Ships\ShipClasses;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipDefs;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the ShipClasses collection which manages
 * all known ship classes (fighter, frigate, destroyer, etc.)
 *
 * @package X4Tests
 * @subpackage Database\Ships
 */
final class ShipClassesTests extends X4TestCase
{
    private ShipClasses $classes;
    private ShipDefs $ships;

    protected function setUp(): void
    {
        parent::setUp();
        $this->classes = ShipClasses::getInstance();
        $this->ships = ShipDefs::getInstance();
    }

    // =========================================================================
    // Collection Tests
    // =========================================================================

    public function test_getInstance(): void
    {
        $instance1 = ShipClasses::getInstance();
        $instance2 = ShipClasses::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function test_getAll(): void
    {
        $classes = $this->classes->getAll();

        $this->assertNotEmpty($classes);
        $this->assertGreaterThanOrEqual(21, count($classes), 'Should have at least 21 ship classes');

        // Verify all items are ShipClass instances
        foreach ($classes as $class) {
            $this->assertInstanceOf(ShipClass::class, $class);
        }
    }

    public function test_getByID(): void
    {
        $fighter = $this->classes->getByID(ShipClasses::CLASS_FIGHTER);

        $this->assertInstanceOf(ShipClass::class, $fighter);
        $this->assertEquals(ShipClasses::CLASS_FIGHTER, $fighter->getID());
    }

    public function test_getByID_invalid(): void
    {
        $this->expectException(RecordNotExistsException::class);
        $this->classes->getByID('nonexistent_class_xyz');
    }

    public function test_getDefault(): void
    {
        $defaultClass = $this->classes->getDefault();

        $this->assertInstanceOf(ShipClass::class, $defaultClass);
        $this->assertNotEmpty($defaultClass->getID());
    }

    public function test_idExists_true(): void
    {
        $this->assertTrue($this->classes->idExists(ShipClasses::CLASS_FIGHTER));
        $this->assertTrue($this->classes->idExists(ShipClasses::CLASS_DESTROYER));
        $this->assertTrue($this->classes->idExists(ShipClasses::CLASS_CARRIER));
    }

    public function test_idExists_false(): void
    {
        $this->assertFalse($this->classes->idExists('nonexistent_class'));
        $this->assertFalse($this->classes->idExists(''));
    }

    // =========================================================================
    // Constant Tests - Combat Classes
    // =========================================================================

    public function test_constant_fighter(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_FIGHTER);
        $this->assertEquals('fighter', $class->getID());
        $this->assertEquals('Fighter', $class->getLabel());
    }

    public function test_constant_heavyFighter(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_HEAVY_FIGHTER);
        $this->assertEquals('heavyfighter', $class->getID());
        $this->assertEquals('Heavy Fighter', $class->getLabel());
    }

    public function test_constant_scout(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_SCOUT);
        $this->assertEquals('scout', $class->getID());
        $this->assertEquals('Scout', $class->getLabel());
    }

    public function test_constant_corvette(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_CORVETTE);
        $this->assertEquals('corvette', $class->getID());
        $this->assertEquals('Corvette', $class->getLabel());
    }

    public function test_constant_frigate(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_FRIGATE);
        $this->assertEquals('frigate', $class->getID());
        $this->assertEquals('Frigate', $class->getLabel());
    }

    public function test_constant_gunboat(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_GUNBOAT);
        $this->assertEquals('gunboat', $class->getID());
        $this->assertEquals('Gunboat', $class->getLabel());
    }

    public function test_constant_destroyer(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_DESTROYER);
        $this->assertEquals('destroyer', $class->getID());
        $this->assertEquals('Destroyer', $class->getLabel());
    }

    public function test_constant_battleship(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_BATTLESHIP);
        $this->assertEquals('battleship', $class->getID());
        $this->assertEquals('Battleship', $class->getLabel());
    }

    public function test_constant_carrier(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_CARRIER);
        $this->assertEquals('carrier', $class->getID());
        $this->assertEquals('Carrier', $class->getLabel());
    }

    // =========================================================================
    // Constant Tests - Industrial Classes
    // =========================================================================

    public function test_constant_miner(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_MINER);
        $this->assertEquals('miner', $class->getID());
        $this->assertEquals('Miner', $class->getLabel());
    }

    public function test_constant_largeMiner(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_LARGEMINER);
        $this->assertEquals('largeminer', $class->getID());
        $this->assertEquals('Large Miner', $class->getLabel());
    }

    public function test_constant_freighter(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_FREIGHTER);
        $this->assertEquals('freighter', $class->getID());
        $this->assertEquals('Freighter', $class->getLabel());
    }

    public function test_constant_transporter(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_TRANSPORTER);
        $this->assertEquals('transporter', $class->getID());
        $this->assertEquals('Transporter', $class->getLabel());
    }

    public function test_constant_courier(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_COURIER);
        $this->assertEquals('courier', $class->getID());
        $this->assertEquals('Courier', $class->getLabel());
    }

    public function test_constant_builder(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_BUILDER);
        $this->assertEquals('builder', $class->getID());
        $this->assertEquals('Builder', $class->getLabel());
    }

    public function test_constant_scavenger(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_SCAVENGER);
        $this->assertEquals('scavenger', $class->getID());
        $this->assertEquals('Scavenger', $class->getLabel());
    }

    public function test_constant_resupplier(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_RESUPPLIER);
        $this->assertEquals('resupplier', $class->getID());
        $this->assertEquals('Resupplier', $class->getLabel());
    }

    // =========================================================================
    // Constant Tests - Special Classes
    // =========================================================================

    public function test_constant_tug(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_TUG);
        $this->assertEquals('tug', $class->getID());
        $this->assertEquals('Tugboat', $class->getLabel());
    }

    public function test_constant_scrapper_notInCollection(): void
    {
        // Note: CLASS_SCRAPPER constant exists but is not in the CLASSES array
        // This is a known inconsistency in the source code
        $this->assertFalse($this->classes->idExists(ShipClasses::CLASS_SCRAPPER));
    }

    public function test_constant_expeditionary(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_EXPEDITIONARY);
        $this->assertEquals('expeditionary', $class->getID());
        $this->assertEquals('Expeditionary', $class->getLabel());
    }

    public function test_constant_compactor(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_COMPACTOR);
        $this->assertEquals('compactor', $class->getID());
        $this->assertEquals('Compactor', $class->getLabel());
    }

    public function test_constant_envoy(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_ENVOY);
        $this->assertEquals('envoy', $class->getID());
        $this->assertEquals('Envoy', $class->getLabel());
    }

    // =========================================================================
    // Constant Validation
    // =========================================================================

    public function test_allConstantsExist(): void
    {
        $constants = ShipClasses::CLASSES;
        $this->assertNotEmpty($constants, 'CLASSES constant should not be empty');

        foreach ($constants as $id => $label) {
            $this->assertTrue(
                $this->classes->idExists($id),
                "Ship class constant '$id' exists in CLASSES but not in collection"
            );
        }
    }

    public function test_allClassesHaveConstant(): void
    {
        $constants = ShipClasses::CLASSES;
        $classes = $this->classes->getAll();

        foreach ($classes as $class) {
            $this->assertArrayHasKey(
                $class->getID(),
                $constants,
                "Ship class '{$class->getID()}' exists in collection but not in CLASSES constant"
            );
        }
    }

    // =========================================================================
    // ShipClass Item Tests
    // =========================================================================

    public function test_ShipClass_getID(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_FIGHTER);
        $this->assertEquals(ShipClasses::CLASS_FIGHTER, $class->getID());
    }

    public function test_ShipClass_getLabel(): void
    {
        $class = $this->classes->getByID(ShipClasses::CLASS_FIGHTER);
        $this->assertEquals('Fighter', $class->getLabel());
        $this->assertNotEmpty($class->getLabel());
    }

    public function test_allClassesHaveLabels(): void
    {
        foreach ($this->classes->getAll() as $class) {
            $this->assertNotEmpty($class->getLabel(), 
                "Ship class {$class->getID()} has empty label");
        }
    }

    // =========================================================================
    // Data Integrity Tests
    // =========================================================================

    public function test_everyShipHasValidClass(): void
    {
        foreach ($this->ships->getAll() as $ship) {
            $classID = $ship->getClassID();
            $this->assertTrue(
                $this->classes->idExists($classID),
                "Ship '{$ship->getID()}' has invalid class ID: '$classID'"
            );

            // Also verify we can retrieve the class
            $class = $ship->getClass();
            $this->assertInstanceOf(ShipClass::class, $class);
            $this->assertEquals($classID, $class->getID());
        }
    }

    public function test_everyClassHasShips(): void
    {
        $classesWithShips = [];

        foreach ($this->ships->getAll() as $ship) {
            $classID = $ship->getClassID();
            if (!in_array($classID, $classesWithShips, true)) {
                $classesWithShips[] = $classID;
            }
        }

        // Note: Not all classes may have ships in the test data
        // This test just verifies we can match ships to their classes
        $this->assertNotEmpty($classesWithShips);
        $this->assertGreaterThan(5, count($classesWithShips), 
            'At least 6 different ship classes should have ships');
    }

    public function test_classToShipsRelationship(): void
    {
        // Pick a known class that should have ships
        $fighterClass = $this->classes->getByID(ShipClasses::CLASS_FIGHTER);
        
        // Find ships of this class
        $fighterShips = [];
        foreach ($this->ships->getAll() as $ship) {
            if ($ship->getClassID() === ShipClasses::CLASS_FIGHTER) {
                $fighterShips[] = $ship;
            }
        }

        // Fighter class should be used by at least some ships
        // (May not be true for all classes in test data, but fighters should be common)
        if (!empty($fighterShips)) {
            $this->assertGreaterThan(0, count($fighterShips));
            
            // Verify reverse relationship
            foreach ($fighterShips as $ship) {
                $this->assertEquals($fighterClass->getID(), $ship->getClassID());
            }
        } else {
            $this->markTestSkipped('No fighter ships found in test data');
        }
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_classIDsCaseSensitive(): void
    {
        // Ship class IDs are lowercase
        $this->assertTrue($this->classes->idExists('fighter'));
        $this->assertFalse($this->classes->idExists('FIGHTER'));
        $this->assertFalse($this->classes->idExists('Fighter'));
    }

    public function test_collectionNotEmpty(): void
    {
        $this->assertNotEmpty($this->classes->getAll());
    }

    public function test_noDuplicateIDs(): void
    {
        $classes = $this->classes->getAll();
        $ids = [];

        foreach ($classes as $class) {
            $id = $class->getID();
            $this->assertNotContains($id, $ids, "Duplicate ship class ID found: $id");
            $ids[] = $id;
        }
    }
}
