<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\Interfaces\StringPrimaryRecordInterface;

/**
 * @package X4 Database
 * @subpackage Ships
 */
class ShipSize implements StringPrimaryRecordInterface
{
    private string $id;
    private string $label;

    public function __construct(string $id, string $label)
    {
        $this->id = $id;
        $this->label = $label;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }
}
