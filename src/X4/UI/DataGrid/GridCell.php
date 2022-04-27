<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\DataGrid;

use Mistralys\X4\UserInterface\DataGrid\GridColumn;
use Mistralys\X4\UserInterface\DataGrid\GridRow;

class GridCell
{
    private GridColumn $column;
    private GridRow $row;

    public function __construct(GridColumn $column, GridRow $row)
    {
        $this->column = $column;
        $this->row = $row;
    }

    public function getColumn() : GridColumn
    {
        return $this->column;
    }

    public function getRow() : GridRow
    {
        return $this->row;
    }

    public function getValue() : string
    {
        return $this->row->getValue($this);
    }

    public function getObject() : ?object
    {
        return $this->row->getObject();
    }

    public function display() : void
    {
        $value = $this->column->applyFormattings($this->getValue());

        $this->column->applyDecorations($this, $value);
    }
}
