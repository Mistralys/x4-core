<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\Interfaces\StringPrimaryRecordInterface;

/**
 * @package X4 Database
 * @subpackage Wares
 */
class WareGroup implements StringPrimaryRecordInterface
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

    /**
     * Returns the wares that belong to this group.
     * @return WareDef[]
     */
    public function getWares() : array
    {
        return WareDefs::getInstance()
            ->findWares()
            ->selectGroup($this)
            ->getAll();
    }
}
