<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use Mistralys\X4\XML\ElementExtended;

/**
 * A single `<add>` operation of a DLC `libraries/wares.xml` diff file.
 *
 * @package X4 Database
 * @subpackage Wares
 *
 * @see WaresPatchReader
 */
final class WarePatch
{
    /**
     * @param string $dataFolderID ID of the data folder the patch comes from, e.g. `ego_dlc_split`.
     * @param string $selector The `sel` attribute of the `<add>` element, e.g. `/wares/ware[@id='energycells']`.
     * @param string|null $targetWareID The targeted ware ID, or NULL if the selector does not target a single ware.
     * @param ElementExtended $element The `<add>` element. Its child elements are the content to add.
     */
    public function __construct(
        public readonly string $dataFolderID,
        public readonly string $selector,
        public readonly ?string $targetWareID,
        public readonly ElementExtended $element
    )
    {
    }

    public function isWareTargeted() : bool
    {
        return $this->targetWareID !== null;
    }
}
