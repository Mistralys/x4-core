<?php
/**
 * @package X4Tests
 * @subpackage Production Methods
 * @see \Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\ProductionMethods;

use Mistralys\X4\Database\ProductionMethods\ProductionMethodDef;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\X4TestCase;

final class ProductionMethodDefsTests extends X4TestCase
{
    public const FIXTURE_RECORDS = array(
        array(
            'id' => 'terran',
            'label' => 'Terran',
            'tags' => array(),
            'defaultRaceIDs' => array('terran')
        ),
        array(
            'id' => 'recycling',
            'label' => 'Recycling',
            'tags' => array('noplayerbuild', 'recycling'),
            'defaultRaceIDs' => array()
        ),
    );

    public function test_roundTrip() : void
    {
        foreach(self::FIXTURE_RECORDS as $record) {
            $this->assertSame($record, ProductionMethodDef::fromArray($record)->toArray());
        }
    }

    public function test_fromArray_missingOptionalKeys() : void
    {
        $method = ProductionMethodDef::fromArray(array('id' => 'x', 'label' => 'X'));

        $this->assertSame(array(), $method->getTags());
        $this->assertSame(array(), $method->getDefaultRaceIDs());
        $this->assertTrue($method->isPlayerBuildable());
    }

    public function test_tagsAndRaces() : void
    {
        $terran = ProductionMethodDef::fromArray(self::FIXTURE_RECORDS[0]);
        $recycling = ProductionMethodDef::fromArray(self::FIXTURE_RECORDS[1]);

        $this->assertSame('terran', $terran->getID());
        $this->assertSame('Terran', $terran->getLabel());
        $this->assertSame(array('terran'), $terran->getDefaultRaceIDs());
        $this->assertTrue($terran->isPlayerBuildable());
        $this->assertFalse($terran->hasTag('recycling'));

        $this->assertSame(array('noplayerbuild', 'recycling'), $recycling->getTags());
        $this->assertTrue($recycling->hasTag('recycling'));
        $this->assertFalse($recycling->isPlayerBuildable());
    }

    public function test_dataFilePath() : void
    {
        $file = ProductionMethodDefs::getInstance()->getDataFile();

        $this->assertSame('production-methods.json', $file->getName());
        $this->assertSame(
            X4Application::getDataFolder()->getPath(),
            $file->getFolder()->getPath()
        );
    }

    public function test_collectionLoadsFixtureRecords() : void
    {
        $collection = $this->createFixtureCollection();

        $this->assertCount(2, $collection->getAll());
        $this->assertTrue($collection->idExists('terran'));
        $this->assertFalse($collection->idExists('unknown'));
        $this->assertSame('Recycling', $collection->getByID('recycling')->getLabel());
        $this->assertTrue($collection->idExists($collection->getDefaultID()));
    }

    public function test_dataFileContents() : void
    {
        $methods = ProductionMethodDefs::getInstance();
        $expected = array('argon', 'boron', 'closedloop', 'default', 'paranid', 'processing', 'recycling', 'research', 'split', 'teladi', 'terran', 'xenon');

        $this->assertSame($expected, $methods->getIDs());

        foreach(array('research', 'processing', 'recycling', 'xenon') as $id) {
            $this->assertFalse($methods->getByID($id)->isPlayerBuildable(), $id);
        }

        $this->assertTrue($methods->getByID('default')->isPlayerBuildable());

        $terran = $methods->getByID('terran');
        $this->assertNotSame('', $terran->getLabel());
        $this->assertSame(array('terran'), $terran->getDefaultRaceIDs());
    }

    private function createFixtureCollection() : ProductionMethodDefs
    {
        return new class extends ProductionMethodDefs {
            public function __construct()
            {
                parent::__construct();
            }

            protected function loadRecords() : array
            {
                return ProductionMethodDefsTests::FIXTURE_RECORDS;
            }
        };
    }
}
