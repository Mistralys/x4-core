<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;

/**
 * Walks the `<connection>` elements of a ship macro and resolves the
 * macro referenced by each connection's child `<macro ref>` element.
 *
 * This is the single implementation of the "connection to child macro to
 * macro file" walk, shared by the storage, dock size and hangar lookups.
 *
 * @package X4 Database
 * @subpackage Ships
 */
class ShipMacroConnectionResolver
{
    private MacroFileDefs $macroDefs;

    public function __construct(?MacroFileDefs $macroDefs = null)
    {
        $this->macroDefs = $macroDefs ?? MacroFileDefs::getInstance();
    }

    /**
     * Resolves all connections that have a child macro reference, in document order.
     *
     * A connection can have several child macros, in which case each one
     * results in an entry. Macros that cannot be found in any data folder
     * are skipped, with a warning.
     *
     * @param DOMExtended $dom The macro DOM to search.
     * @param string $dataSourceID The preferred data folder for the macro lookup.
     * @param string $connectionRefPrefix Optional: only connections whose `ref` starts with this prefix, e.g. `con_storage`.
     * @return ShipMacroConnection[]
     */
    public function resolve(DOMExtended $dom, string $dataSourceID, string $connectionRefPrefix = '') : array
    {
        $result = array();

        foreach($dom->byTagName('connection')->getAll() as $connection)
        {
            $connectionRef = (string)$connection->getAttribute('ref');

            if($connectionRefPrefix !== '' && !str_starts_with($connectionRef, $connectionRefPrefix)) {
                continue;
            }

            foreach($connection->getChildren() as $child)
            {
                if($child->getDOMElement()->nodeName !== 'macro') {
                    continue;
                }

                $macroID = (string)$child->getAttribute('ref');

                if($macroID === '') {
                    continue;
                }

                try
                {
                    $macroDef = $this->macroDefs->getByMacroName($macroID, $dataSourceID);
                }
                catch (RecordNotExistsException)
                {
                    Console::line1(
                        'WARNING | Macro [%s] referenced by connection [%s] was not found in any data folder.',
                        $macroID,
                        $connectionRef
                    );
                    continue;
                }

                $result[] = new ShipMacroConnection($connectionRef, $macroID, $macroDef);
            }
        }

        return $result;
    }
}
