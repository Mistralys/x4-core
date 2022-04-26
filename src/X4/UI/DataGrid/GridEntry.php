<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\Interface_Classable;
use AppUtils\Traits_Classable;

class GridEntry implements Interface_Classable
{
    use Traits_Classable;

    private array $data;

    public function __construct(array $data=array())
    {
        $this->data = $data;
    }

    public function getColumnValue(GridColumn $column) : string
    {
        return $this->data[$column->getKeyName()] ?? '';
    }

    /**
     * @param GridColumn[] $columns
     * @return void
     */
    public function displayColumns(array $columns) : void
    {
        foreach($columns as $column)
        {
            ?>
                <td <?php echo $column->classesToAttribute() ?>>
                    <?php echo $this->getColumnValue($column) ?>
                </td>
            <?php
        }
    }
}
