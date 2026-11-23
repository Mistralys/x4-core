<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\ShipPurposes
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use Mistralys\X4\Database\Ships\ShipPurposes;
use PHPUnit\Framework\TestCase;

final class ShipPurposesTests extends TestCase
{
    public function test_idsAreTheEightPurposesInTheData() : void
    {
        $ids = ShipPurposes::getIDs();
        sort($ids);

        $this->assertSame(
            array('auxiliary', 'build', 'dismantling', 'fight', 'mine', 'racing', 'salvage', 'trade'),
            $ids
        );
    }

    public function test_idExists() : void
    {
        foreach(ShipPurposes::getIDs() as $id) {
            $this->assertTrue(ShipPurposes::idExists($id));
        }

        $this->assertFalse(ShipPurposes::idExists('unknown'));
        $this->assertFalse(ShipPurposes::idExists(''));
        $this->assertFalse(ShipPurposes::idExists('FIGHT'));
    }

    public function test_constantsMatchIDs() : void
    {
        $this->assertSame('fight', ShipPurposes::PURPOSE_FIGHT);
        $this->assertContains(ShipPurposes::PURPOSE_DISMANTLING, ShipPurposes::getIDs());
    }
}
