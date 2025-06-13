<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Core;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;

trait CollectionItemTrait
{
    public function getWareID() : string
    {
        return $this->getID();
    }

    public function getWare() : WareDef
    {
        return WareDefs::getInstance()->getByID($this->getWareID());
    }
}
