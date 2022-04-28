<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\Interface_Classable;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\RenderableBufferedTrait;
use AppUtils\Traits_Classable;
use Mistralys\X4\UI\DataGrid\GridCell;

class GridRow implements Interface_Classable, RenderableInterface
{
    use Traits_Classable;
    use RenderableBufferedTrait;

    private array $data;
    private ?object $object = null;
    private DataGrid $grid;

    /**
     * @var array<string,GridCell>
     */
    private array $cells = array();

    public function __construct(DataGrid $grid, array $data=array())
    {
        $this->grid = $grid;
        $this->data = $data;
    }

    public function getGrid() : DataGrid
    {
        return $this->grid;
    }

    public function getValue(GridCell $cell) : string
    {
        $column = $cell->getColumn();

        if(isset($this->object))
        {
            return $column->getValueFromObject($this->object);
        }

        return $this->data[$column->getKeyName()] ?? '';
    }

    /**
     * @return object|null
     */
    public function getObject() : ?object
    {
        return $this->object;
    }

    public function setValue(GridColumn $column, string $value) : self
    {
        $this->data[$column->getKeyName()] = $value;
        return $this;
    }

    public function getCell(GridColumn $column) : GridCell
    {
        $key = $column->getKeyName();

        if(isset($this->cells[$key]))
        {
            return $this->cells[$key];
        }

        $cell = new GridCell($column, $this);

        $this->cells[$key] = $cell;

        return $cell;
    }

    public function setObject(object $subject) : self
    {
        $this->object = $subject;
        return $this;
    }

    public function setValues(array $values) : self
    {
        $this->data = $values;
        return $this;
    }

    protected function generateOutput() : void
    {
        $columns = $this->grid->getColumns();

        foreach($columns as $column)
        {
            $cell = $this->getCell($column);

            ?>
            <td <?php echo $column->classesToAttribute() ?>>
                <?php $cell->display() ?>
            </td>
            <?php
        }
    }
}
