<?php
/**
 * @package X4Tests
 * @subpackage Wares
 * @see \Mistralys\X4\Database\Wares\WaresPatchReader
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Wares;

use AppUtils\FileHelper\FileInfo;
use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Wares\WarePatch;
use Mistralys\X4\Database\Wares\WaresPatchReader;
use X4Tests\Helpers\X4TestCase;

final class WaresPatchReaderTests extends X4TestCase
{
    private const FIXTURE_XML = <<<'XML'
<?xml version="1.0" encoding="utf-8"?>
<diff>
  <add sel="/wares/production">
    <method id="boron" name="{20206,801}"/>
  </add>
  <add sel="/wares">
    <ware id="bofu"/>
  </add>
  <add sel="/wares/ware[@id='energycells']">
    <owner faction="argon"/>
  </add>
  <add sel='/wares/ware[@id="graphene"]'>
    <illegal/>
  </add>
  <replace sel="/wares/ware[@id='water']/price/@min">5</replace>
  <remove sel="/wares/ware[@id='ore']"/>
  <add sel="//ware[@id='silicon']"/>
</diff>
XML;

    /**
     * @var string[]
     */
    private array $tempFiles = array();

    protected function tearDown(): void
    {
        foreach($this->tempFiles as $file) {
            if(file_exists($file)) {
                unlink($file);
            }
        }

        parent::tearDown();
    }

    public function test_fixtureSupportedPatches() : void
    {
        $reader = $this->createReader();
        $patches = $reader->readFile($this->createFixtureFile(), 'fixture_dlc');

        $this->assertCount(4, $patches);

        $this->assertSame('/wares/production', $patches[0]->selector);
        $this->assertNull($patches[0]->targetWareID);

        $this->assertSame('/wares', $patches[1]->selector);
        $this->assertNull($patches[1]->targetWareID);
        $this->assertFalse($patches[1]->isWareTargeted());

        $this->assertSame('energycells', $patches[2]->targetWareID);
        $this->assertTrue($patches[2]->isWareTargeted());
        $this->assertSame('graphene', $patches[3]->targetWareID, 'Double quoted IDs are supported');

        foreach($patches as $patch) {
            $this->assertSame('fixture_dlc', $patch->dataFolderID);
            $this->assertSame('add', $patch->element->getDOMElement()->tagName);
        }
    }

    public function test_fixtureUnsupportedOperationsWarn() : void
    {
        $reader = $this->createReader();
        $reader->readFile($this->createFixtureFile(), 'fixture_dlc');

        $warnings = $reader->getWarnings();

        $this->assertCount(3, $warnings);
        $this->assertStringContainsString('WARNING', $warnings[0]);
        $this->assertStringContainsString('[replace]', $warnings[0]);
        $this->assertStringContainsString('[remove]', $warnings[1]);
        $this->assertStringContainsString("//ware[@id='silicon']", $warnings[2]);
    }

    public function test_realData_selectorFilters() : void
    {
        $reader = $this->createReader();

        $warePatches = $reader->getWarePatches();
        $this->assertNotEmpty($warePatches);

        foreach($warePatches as $patch) {
            $this->assertNotNull($patch->targetWareID);
        }

        $production = $reader->getPatchesForSelector(WaresPatchReader::SELECTOR_PRODUCTION);
        $this->assertNotEmpty($production);

        foreach($production as $patch) {
            $this->assertNull($patch->targetWareID);
            $this->assertSame('/wares/production', $patch->selector);
        }

        $this->assertSame(
            count($reader->getAllPatches()),
            count($warePatches)
            + count($production)
            + count($reader->getPatchesForSelector(WaresPatchReader::SELECTOR_WARES))
        );
    }

    public function test_realData_noWarnings() : void
    {
        $reader = $this->createReader();
        $reader->getAllPatches();

        $this->assertSame(array(), $reader->getWarnings());
    }

    public function test_realData_dataFolderOrder() : void
    {
        $folderOrder = array();
        foreach(DatabaseBuilder::getDataFolders()->getAll() as $folder) {
            $folderOrder[] = $folder->getID();
        }

        $seen = array();
        foreach($this->createReader()->getAllPatches() as $patch) {
            if(!in_array($patch->dataFolderID, $seen, true)) {
                $seen[] = $patch->dataFolderID;
            }
        }

        $this->assertNotContains('vanilla', $seen, 'Only DLC folders are read.');
        $this->assertSame(
            array_values(array_intersect($folderOrder, $seen)),
            $seen
        );
    }

    public function test_realData_tugboatPatches() : void
    {
        $patches = array_filter(
            $this->createReader()->getWarePatches(),
            static fn(WarePatch $patch) : bool => $patch->targetWareID === 'ship_gen_m_tugboat_01_a'
        );

        $this->assertNotEmpty($patches);

        $folders = array_unique(array_map(
            static fn(WarePatch $patch) : string => $patch->dataFolderID,
            $patches
        ));

        $this->assertContains('ego_dlc_split', $folders);
        $this->assertContains('ego_dlc_terran', $folders);
    }

    private function createReader() : WaresPatchReader
    {
        return new WaresPatchReader(DatabaseBuilder::getDataFolders());
    }

    private function createFixtureFile() : FileInfo
    {
        $path = sys_get_temp_dir().'/wares-patch-fixture-'.uniqid('', true).'.xml';
        file_put_contents($path, self::FIXTURE_XML);
        $this->tempFiles[] = $path;

        return FileInfo::factory($path);
    }
}
