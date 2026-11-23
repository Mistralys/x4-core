<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipSoftware
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Ships\MacroProperties\ShipSoftware;
use X4Tests\Helpers\X4TestCase;

final class ShipSoftwareTests extends X4TestCase
{
    private const DATA = array(
        'defaultWareIDs' => array('software_dockmk2'),
        'optionalWareIDs' => array('software_scannerlongrangemk1', 'software_not_a_real_ware')
    );

    public function test_roundTrip() : void
    {
        $this->assertSame(self::DATA, ShipSoftware::fromArray(self::DATA)->toArray());
        $this->assertSame(
            array('defaultWareIDs' => array(), 'optionalWareIDs' => array()),
            ShipSoftware::fromArray(array())->toArray()
        );
    }

    public function test_membershipChecks() : void
    {
        $software = ShipSoftware::fromArray(self::DATA);

        $this->assertTrue($software->isDefault('software_dockmk2'));
        $this->assertFalse($software->isDefault('software_scannerlongrangemk1'));

        $this->assertTrue($software->isOptional('software_scannerlongrangemk1'));
        $this->assertFalse($software->isOptional('software_dockmk2'));

        $this->assertTrue($software->isInstallable('software_dockmk2'));
        $this->assertTrue($software->isInstallable('software_scannerlongrangemk1'));
        $this->assertFalse($software->isInstallable('software_other'));
    }

    public function test_unresolvedWareIDsAreSkipped() : void
    {
        $software = ShipSoftware::fromArray(self::DATA);

        $default = $software->getDefaultSoftware();
        $this->assertCount(1, $default);
        $this->assertSame('software_dockmk2', $default[0]->getID());

        $optional = $software->getOptionalSoftware();
        $this->assertCount(1, $optional, 'The unknown ware ID is skipped.');
        $this->assertSame('software_scannerlongrangemk1', $optional[0]->getID());

        $this->assertTrue($software->isOptional('software_not_a_real_ware'), 'The raw ID is still kept.');
    }

    public function test_empty() : void
    {
        $this->assertTrue((new ShipSoftware())->isEmpty());
        $this->assertSame(array(), (new ShipSoftware())->getDefaultSoftware());
        $this->assertFalse(ShipSoftware::fromArray(self::DATA)->isEmpty());
    }
}
