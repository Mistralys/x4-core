<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\ConvertHelper;
use AppUtils\Interface_Classable;
use AppUtils\Interface_Stringable;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\RenderableBufferedTrait;
use AppUtils\Traits_Classable;
use DateTime;
use Mistralys\X4\UI\DataGrid\GridCell;
use Mistralys\X4\UI\Icon;

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

    /**
     * @param GridCell $cell
     * @return mixed|NULL
     * @throws DataGridException
     */
    public function getValue(GridCell $cell)
    {
        $column = $cell->getColumn();

        if(isset($this->object))
        {
            return $column->getValueFromObject($this->object);
        }

        return $this->data[$column->getKeyName()] ?? null;
    }

    /**
     * @return object|null
     */
    public function getObject() : ?object
    {
        return $this->object;
    }

    /**
     * @param GridColumn $column
     * @param string|number|Interface_Stringable|DateTime|bool|NULL $value
     * @return $this
     */
    public function setValue(GridColumn $column, $value) : self
    {
        if($value instanceof DateTime) {
            return $this->setDate($column, $value);
        }

        if(is_bool($value)) {
            return $this->setBool($column, $value);
        }

        $this->data[$column->getKeyName()] = (string)$value;
        return $this;
    }

    /**
     * @param GridColumn $column
     * @param bool|string|int $value
     * @return $this
     */
    public function setBool(GridColumn $column, $value, ?Icon $trueIcon=null, ?Icon $falseIcon=null) : self
    {
        if($trueIcon === null) {
            $trueIcon = Icon::yes()->colorSuccess();
        }

        if($falseIcon === null) {
            $falseIcon = Icon::no()->colorMuted();
        }

        $converted = $falseIcon;
        if(ConvertHelper::string2bool($value) === true) {
            $converted = $trueIcon;
        }

        return $this->setValue($column, $converted);
    }

    public function setDate(GridColumn $column, DateTime $date, bool $includeTime=true, bool $shortMonth=false) : self
    {
        return $this->setValue(
            $column,
            ConvertHelper::date2listLabel($date, $includeTime, $shortMonth)
        );
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
