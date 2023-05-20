<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\DataGrid\Cell;

use Mistralys\X4\UI\DataGrid\GridCell;
use Mistralys\X4\UI\DataGrid\Row\MergedRow;
use Mistralys\X4\UserInterface\DataGrid\Column\MergedColumn;

/**
 * @method MergedRow getRow()
 * @method MergedColumn getColumn()
 */
class MergedCell extends GridCell
{
    public function __construct(MergedColumn $column)
    {
        parent::__construct($column, $column->getRow());

        $this->addClass('datagrid-merged-cell');
    }

    public function getValue() : string
    {
        $this->attr('colspan', $this->getRow()->getGrid()->countColumns());

        return $this->getRow()->getContent();
    }
}
