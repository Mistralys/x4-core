<?php
/**
 * @package X4Tests
 * @subpackage Ships
 * @see \Mistralys\X4\Database\Ships\MacroProperties\ShipMacroConnectionResolver
 */

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships\MacroProperties;

use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Ships\MacroProperties\ShipCargoInfo;
use Mistralys\X4\Database\Ships\MacroProperties\ShipMacroConnectionResolver;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use X4Tests\Helpers\X4TestCase;

final class ShipMacroConnectionResolverTests extends X4TestCase
{
    public function test_realShipConnectionsInDocumentOrder() : void
    {
        $ware = WareDefs::getInstance()->getByID('ship_arg_xl_carrier_01_a');
        $macroDef = MacroFileDefs::getInstance()->getByMacroName($ware->getMacroID(), $ware->getDataSourceID());

        $connections = (new ShipMacroConnectionResolver())->resolve($macroDef->getDOM(), $ware->getDataSourceID());

        $this->assertNotEmpty($connections);

        // Document order: the order of the refs equals the order in the XML.
        $expected = array();
        foreach($macroDef->getDOM()->byTagName('connection')->getAll() as $connection) {
            foreach($connection->getChildren() as $child) {
                if($child->getDOMElement()->nodeName === 'macro') {
                    $expected[] = $connection->getAttribute('ref').'::'.$child->getAttribute('ref');
                }
            }
        }

        $actual = array();
        foreach($connections as $connection) {
            $actual[] = $connection->connectionRef.'::'.$connection->macroID;
            $this->assertSame(
                strtolower($connection->macroID),
                strtolower($connection->macroDef->getMacroName())
            );
        }

        $this->assertSame($expected, $actual);
    }

    public function test_prefixFilter() : void
    {
        $ware = WareDefs::getInstance()->getByID('ship_arg_xl_carrier_01_a');
        $macroDef = MacroFileDefs::getInstance()->getByMacroName($ware->getMacroID(), $ware->getDataSourceID());

        $connections = (new ShipMacroConnectionResolver())->resolve($macroDef->getDOM(), $ware->getDataSourceID(), 'con_storage');

        $this->assertCount(1, $connections);
        $this->assertStringStartsWith('con_storage', $connections[0]->connectionRef);
    }

    public function test_unresolvableReferenceLogsWarningAndIsSkipped() : void
    {
        $dom = DOMExtended::createFromString(<<<'XML'
<macros>
  <macro name="fixture_macro">
    <connections>
      <connection ref="con_missing">
        <macro ref="this_macro_does_not_exist_macro" connection="ShipConnection"/>
      </connection>
      <connection ref="con_storage01">
        <macro ref="storage_arg_xl_carrier_01_a_macro" connection="ShipConnection"/>
      </connection>
      <connection ref="con_empty">
        <macro ref="" connection="ShipConnection"/>
      </connection>
    </connections>
  </macro>
</macros>
XML
        );

        Console::setEnabled(true);
        ob_start();

        try {
            $connections = (new ShipMacroConnectionResolver())->resolve($dom, 'vanilla');
        } finally {
            $output = (string)ob_get_clean();
            Console::setEnabled(false);
        }

        $this->assertCount(1, $connections);
        $this->assertSame('con_storage01', $connections[0]->connectionRef);

        $this->assertSame(1, substr_count($output, 'WARNING'));
        $this->assertStringContainsString(
            'Macro [this_macro_does_not_exist_macro] referenced by connection [con_missing] was not found in any data folder.',
            $output
        );
    }

    public function test_crossFolderCarrierStorageMacro() : void
    {
        $ware = WareDefs::getInstance()->getByID('ship_spl_xl_carrier_01_a');
        $macroDef = MacroFileDefs::getInstance()->getByMacroName($ware->getMacroID(), $ware->getDataSourceID());

        $connections = (new ShipMacroConnectionResolver())->resolve($macroDef->getDOM(), $ware->getDataSourceID(), 'con_storage');

        $this->assertCount(1, $connections);
        $this->assertSame('storage_arg_xl_carrier_01_a_macro', $connections[0]->macroDef->getMacroName());
        $this->assertSame('vanilla', $connections[0]->macroDef->getDataFolderID());
    }

    public function test_cargoInfoDefaults() : void
    {
        $info = new ShipCargoInfo();

        $this->assertSame(0, $info->capacity);
        $this->assertSame('none', $info->type);
        $this->assertNull($info->macroID);
    }
}
