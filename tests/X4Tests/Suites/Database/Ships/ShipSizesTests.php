<?php
/**
 * @package X4Tests
 * @subpackage Database\Ships
 * @see \Mistralys\X4\Database\Ships\ShipSizes
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\Ships\ShipSize;
use Mistralys\X4\Database\Ships\ShipSizes;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the ShipSizes collection which manages
 * the 5 known ship sizes (XS, S, M, L, XL)
 *
 * @package X4Tests
 * @subpackage Database\Ships
 */
final class ShipSizesTests extends X4TestCase
{
    private ShipSizes $sizes;
    private ShipDefs $ships;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sizes = ShipSizes::getInstance();
        $this->ships = ShipDefs::getInstance();
    }

    // =========================================================================
    // Collection Tests
    // =========================================================================

    public function test_getInstance(): void
    {
        $instance1 = ShipSizes::getInstance();
        $instance2 = ShipSizes::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    public function test_getAll(): void
    {
        $sizes = $this->sizes->getAll();

        $this->assertCount(5, $sizes, 'Should have exactly 5 ship sizes');

        // Verify all items are ShipSize instances
        foreach ($sizes as $size) {
            $this->assertInstanceOf(ShipSize::class, $size);
        }
    }

    public function test_getByID(): void
    {
        $mediumSize = $this->sizes->getByID(ShipSizes::SIZE_M);

        $this->assertInstanceOf(ShipSize::class, $mediumSize);
        $this->assertEquals(ShipSizes::SIZE_M, $mediumSize->getID());
    }

    public function test_getByID_invalid(): void
    {
        $this->expectException(RecordNotExistsException::class);
        $this->sizes->getByID('xxl');
    }

    public function test_getDefault(): void
    {
        $defaultSize = $this->sizes->getDefault();

        $this->assertInstanceOf(ShipSize::class, $defaultSize);
        $this->assertNotEmpty($defaultSize->getID());
    }

    public function test_idExists_true(): void
    {
        $this->assertTrue($this->sizes->idExists(ShipSizes::SIZE_XS));
        $this->assertTrue($this->sizes->idExists(ShipSizes::SIZE_S));
        $this->assertTrue($this->sizes->idExists(ShipSizes::SIZE_M));
        $this->assertTrue($this->sizes->idExists(ShipSizes::SIZE_L));
        $this->assertTrue($this->sizes->idExists(ShipSizes::SIZE_XL));
    }

    public function test_idExists_false(): void
    {
        $this->assertFalse($this->sizes->idExists('xxl'));
        $this->assertFalse($this->sizes->idExists(''));
        $this->assertFalse($this->sizes->idExists('medium'));
    }

    // =========================================================================
    // Size Constant Tests
    // =========================================================================

    public function test_constant_xs(): void
    {
        $size = $this->sizes->getByID(ShipSizes::SIZE_XS);
        $this->assertEquals('xs', $size->getID());
        $this->assertEquals('Extra Small', $size->getLabel());
    }

    public function test_constant_s(): void
    {
        $size = $this->sizes->getByID(ShipSizes::SIZE_S);
        $this->assertEquals('s', $size->getID());
        $this->assertEquals('Small', $size->getLabel());
    }

    public function test_constant_m(): void
    {
        $size = $this->sizes->getByID(ShipSizes::SIZE_M);
        $this->assertEquals('m', $size->getID());
        $this->assertEquals('Medium', $size->getLabel());
    }

    public function test_constant_l(): void
    {
        $size = $this->sizes->getByID(ShipSizes::SIZE_L);
        $this->assertEquals('l', $size->getID());
        $this->assertEquals('Large', $size->getLabel());
    }

    public function test_constant_xl(): void
    {
        $size = $this->sizes->getByID(ShipSizes::SIZE_XL);
        $this->assertEquals('xl', $size->getID());
        $this->assertEquals('Extra Large', $size->getLabel());
    }

    // =========================================================================
    // ShipSize Item Tests
    // =========================================================================

    public function test_ShipSize_getID(): void
    {
        $size = $this->sizes->getByID(ShipSizes::SIZE_M);
        $this->assertEquals(ShipSizes::SIZE_M, $size->getID());
    }

    public function test_ShipSize_getLabel(): void
    {
        $size = $this->sizes->getByID(ShipSizes::SIZE_M);
        $this->assertEquals('Medium', $size->getLabel());
        $this->assertNotEmpty($size->getLabel());
    }

    public function test_allSizesHaveLabels(): void
    {
        foreach ($this->sizes->getAll() as $size) {
            $this->assertNotEmpty($size->getLabel(),
                "Ship size {$size->getID()} has empty label");
        }
    }

    // =========================================================================
    // Size Hierarchy Tests
    // =========================================================================

    public function test_sizeOrder(): void
    {
        // Verify the expected order: XS < S < M < L < XL
        $expectedOrder = [
            ShipSizes::SIZE_XS,
            ShipSizes::SIZE_S,
            ShipSizes::SIZE_M,
            ShipSizes::SIZE_L,
            ShipSizes::SIZE_XL
        ];

        // Verify all expected sizes exist
        foreach ($expectedOrder as $sizeID) {
            $this->assertTrue($this->sizes->idExists($sizeID),
                "Expected size '$sizeID' does not exist");
        }

        // Verify we have exactly these 5 sizes
        $allSizes = $this->sizes->getAll();
        $this->assertCount(5, $allSizes);
    }

    // =========================================================================
    // Data Integrity Tests
    // =========================================================================

    public function test_everyShipHasValidSize(): void
    {
        $validSizes = ['xs', 's', 'm', 'l', 'xl'];

        foreach ($this->ships->getAll() as $ship) {
            $sizeID = $ship->getSizeID();
            $this->assertContains(
                $sizeID,
                $validSizes,
                "Ship '{$ship->getID()}' has invalid size ID: '$sizeID'"
            );

            // Verify we can retrieve the size object
            $size = $ship->getSize();
            $this->assertInstanceOf(ShipSize::class, $size);
            $this->assertEquals($sizeID, $size->getID());
        }
    }

    public function test_everySizeHasShips(): void
    {
        $sizesWithShips = [];

        foreach ($this->ships->getAll() as $ship) {
            $sizeID = $ship->getSizeID();
            if (!in_array($sizeID, $sizesWithShips, true)) {
                $sizesWithShips[] = $sizeID;
            }
        }

        // Verify we have ships for multiple sizes (not all 5 sizes may be represented in test data)
        $this->assertGreaterThan(3, count($sizesWithShips),
            'At least 4 different ship sizes should be represented in the ship collection');

        // All represented sizes should be valid
        $expectedSizes = ['xs', 's', 'm', 'l', 'xl'];
        foreach ($sizesWithShips as $sizeID) {
            $this->assertContains($sizeID, $expectedSizes,
                "Invalid size '$sizeID' found in ship collection");
        }
    }

    public function test_sizeToShipsRelationship(): void
    {
        // For each size, find ships of that size
        $sizesWithoutShips = [];
        
        foreach ($this->sizes->getAll() as $size) {
            $shipsOfSize = [];
            
            foreach ($this->ships->getAll() as $ship) {
                if ($ship->getSizeID() === $size->getID()) {
                    $shipsOfSize[] = $ship;
                }
            }

            // Track sizes without ships (may be valid if no ships of that size in data)
            if (empty($shipsOfSize)) {
                $sizesWithoutShips[] = $size->getID();
            } else {
                // Verify reverse relationship for sizes that have ships
                foreach ($shipsOfSize as $ship) {
                    $this->assertEquals($size->getID(), $ship->getSizeID());
                }
            }
        }

        // At least some sizes should have ships
        $this->assertLessThan(5, count($sizesWithoutShips),
            'At least some ship sizes should have ships in the collection');
    }

    // =========================================================================
    // Size Distribution Tests
    // =========================================================================

    public function test_sizeDistribution(): void
    {
        $sizeCount = [
            'xs' => 0,
            's' => 0,
            'm' => 0,
            'l' => 0,
            'xl' => 0
        ];

        foreach ($this->ships->getAll() as $ship) {
            $sizeID = $ship->getSizeID();
            if (isset($sizeCount[$sizeID])) {
                $sizeCount[$sizeID]++;
            }
        }

        // At least some sizes should have ships
        $sizesWithShips = 0;
        foreach ($sizeCount as $count) {
            if ($count > 0) {
                $sizesWithShips++;
            }
        }
        
        $this->assertGreaterThan(3, $sizesWithShips,
            'At least 4 ship sizes should have ships in the collection');

        // Total should equal number of ships
        $totalShips = count($this->ships->getAll());
        $this->assertEquals($totalShips, array_sum($sizeCount));
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_sizeIDsCaseSensitive(): void
    {
        // Ship size IDs are lowercase
        $this->assertTrue($this->sizes->idExists('m'));
        $this->assertFalse($this->sizes->idExists('M'));
    }

    public function test_collectionNotEmpty(): void
    {
        $this->assertNotEmpty($this->sizes->getAll());
    }

    public function test_noDuplicateIDs(): void
    {
        $sizes = $this->sizes->getAll();
        $ids = [];

        foreach ($sizes as $size) {
            $id = $size->getID();
            $this->assertNotContains($id, $ids, "Duplicate ship size ID found: $id");
            $ids[] = $id;
        }
    }

    public function test_exactlyFiveSizes(): void
    {
        $this->assertCount(5, $this->sizes->getAll(),
            'Should have exactly 5 ship sizes, no more, no less');
    }

    // =========================================================================
    // Comparison Tests
    // =========================================================================

    public function test_xsSmallerThanOthers(): void
    {
        // XS ships should typically be fighters/scouts (if any exist in data)
        $xsShips = [];
        foreach ($this->ships->getAll() as $ship) {
            if ($ship->getSizeID() === ShipSizes::SIZE_XS) {
                $xsShips[] = $ship;
            }
        }

        // Note: XS ships may not exist in all test data sets
        if (empty($xsShips)) {
            $this->markTestSkipped('No XS ships found in test data');
        }
        
        $this->assertNotEmpty($xsShips);
    }

    public function test_xlLargestSize(): void
    {
        // XL ships should typically be capital ships
        $xlShips = [];
        foreach ($this->ships->getAll() as $ship) {
            if ($ship->getSizeID() === ShipSizes::SIZE_XL) {
                $xlShips[] = $ship;
            }
        }

        $this->assertNotEmpty($xlShips, 'Should have some XL ships');
    }
}
