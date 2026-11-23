<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

use Mistralys\X4\Database\MacroIndex\MacroFileDef;

/**
 * A ship macro `<connection>` together with the resolved macro
 * of its child `<macro ref>` element.
 *
 * @package X4 Database
 * @subpackage Ships
 *
 * @see ShipMacroConnectionResolver
 */
final class ShipMacroConnection
{
    /**
     * @param string $connectionRef The `ref` attribute of the connection, e.g. `con_storage01`.
     * @param string $macroID The macro name as referenced in the XML. May differ in case from {@see MacroFileDef::getMacroName()}.
     * @param MacroFileDef $macroDef The resolved macro file.
     */
    public function __construct(
        public readonly string $connectionRef,
        public readonly string $macroID,
        public readonly MacroFileDef $macroDef
    )
    {
    }
}
