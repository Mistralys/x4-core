<?php
/**
 * @package X4Tests
 * @subpackage Production Methods
 * @see \Mistralys\X4\Database\ProductionMethods\ProductionMethodsExtractor
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\ProductionMethods;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDef;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodsExtractor;
use X4Tests\Helpers\X4TestCase;

final class ProductionMethodsExtractorTests extends X4TestCase
{
    public function test_collectReadsVanillaAndDlcMethods() : void
    {
        $methods = (new ProductionMethodsExtractor(DatabaseBuilder::getDataFolders()))->collect();

        $ids = array_keys($methods);
        $sorted = $ids;
        sort($sorted);

        $this->assertSame($sorted, $ids, 'Methods must be sorted by ID.');
        $this->assertCount(12, $methods);

        // Vanilla
        $this->assertFalse($methods['recycling']->isPlayerBuildable());
        $this->assertTrue($methods['recycling']->hasTag('recycling'));
        $this->assertSame(array('argon'), $methods['argon']->getDefaultRaceIDs());

        // DLC patches
        $this->assertSame('terran', $methods['terran']->getID());
        $this->assertSame(array('terran'), $methods['terran']->getDefaultRaceIDs());
        $this->assertArrayHasKey('closedloop', $methods);
        $this->assertArrayHasKey('boron', $methods);
        $this->assertArrayHasKey('split', $methods);
    }

    public function test_labelsAreTranslated() : void
    {
        $methods = (new ProductionMethodsExtractor(DatabaseBuilder::getDataFolders()))->collect();

        foreach($methods as $method) {
            $this->assertNotSame('', $method->getLabel());
            $this->assertStringStartsNotWith('{', $method->getLabel(), 'Label of ['.$method->getID().'] is untranslated.');
        }
    }

    public function test_dataFileMatchesExtraction() : void
    {
        $methods = (new ProductionMethodsExtractor(DatabaseBuilder::getDataFolders()))->collect();

        $expected = array();
        foreach($methods as $method) {
            $expected[] = $method->toArray();
        }

        $this->assertSame($expected, ProductionMethodDefs::getStorageFile()->getData());
        $this->assertContainsOnlyInstancesOf(ProductionMethodDef::class, ProductionMethodDefs::getInstance()->getAll());
    }
}
