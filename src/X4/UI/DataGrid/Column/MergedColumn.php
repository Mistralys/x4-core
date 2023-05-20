<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid\Column;

use Mistralys\X4\UI\DataGrid\Cell\MergedCell;
use Mistralys\X4\UI\DataGrid\Row\MergedRow;
use Mistralys\X4\UserInterface\DataGrid\GridColumn;

class MergedColumn extends GridColumn
{
    private MergedRow $row;
    private MergedCell $cell;

    public function __construct(MergedRow $row)
    {
        parent::__construct('merged-column', 'merged-label');

        $this->row = $row;
        $this->cell = new MergedCell($this, $row);
    }

    public function getCell() : MergedCell
    {
        return $this->cell;
    }

    public function getRow() : MergedRow
    {
        return $this->row;
    }

    public function display() : void
    {
        $this->displayCell($this->cell);
    }
}
