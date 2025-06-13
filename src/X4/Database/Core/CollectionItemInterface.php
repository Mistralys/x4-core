<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Core;

use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\Wares\WareDef;

interface CollectionItemInterface extends StringPrimaryRecordInterface
{
    /**
     * Returns the item's ware ID. This is an alias for {@see self::getID()}.
     *
     * > Note: {@see self::getID()} also returns the ware ID, but its
     * > naming can be unclear. It is imposed by the underlying
     * > interface, so the alias is provided to avoid guesswork.
     *
     * @return string
     */
    public function getWareID() : string;

    /**
     * Returns the human-readable label of the item.
     *
     * @return string
     */
    public function getLabel() : string;

    /**
     * Returns the ware definition of the item.
     *
     * @return WareDef
     */
    public function getWare() : WareDef;
}
