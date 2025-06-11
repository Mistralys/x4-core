<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\Interfaces\StringPrimaryRecordInterface;

class ModuleCategory implements StringPrimaryRecordInterface
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

    public function isProduction() : bool
    {
        return $this->getID() === ModuleCategories::CATEGORY_PRODUCTION;
    }

    public function isDockingModule() : bool
    {
        return
            $this->getID() === ModuleCategories::CATEGORY_DOCKING_AREA
            ||
            $this->getID() === ModuleCategories::CATEGORY_DOCKING_PIER;
    }
}
