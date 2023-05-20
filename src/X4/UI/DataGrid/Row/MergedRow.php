<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\DataGrid\Row;

use AppUtils\Interface_Stringable;
use Mistralys\X4\UI\DataGrid\Cell\MergedCell;
use Mistralys\X4\UserInterface\DataGrid\Column\MergedColumn;
use Mistralys\X4\UserInterface\DataGrid\GridRow;

class MergedRow extends GridRow
{
    private MergedColumn $column;
    private string $content = '';

    protected function init() : void
    {
        $this->addClass('datagrid-row-merged');
        $this->column = new MergedColumn($this);
    }

    public function getCell() : MergedCell
    {
        return $this->column->getCell();
    }

    /**
     * @param string|number|Interface_Stringable|NULL $content
     * @return $this
     */
    public function setContent($content) : self
    {
        $this->content = '';
        return $this->appendContent($content);
    }

    /**
     * @param string|number|Interface_Stringable|NULL $content
     * @return $this
     */
    public function appendContent($content) : self
    {
        $this->content .= $content;
        return $this;
    }

    public function getContent() : string
    {
        return $this->content;
    }

    protected function displayCells() : void
    {
        $this->column->display();
    }
}
