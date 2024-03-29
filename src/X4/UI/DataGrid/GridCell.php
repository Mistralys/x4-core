<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\DataGrid;

use AppUtils\AttributeCollection;
use AppUtils\Interfaces\AttributableInterface;
use AppUtils\Interfaces\ClassableAttributeInterface;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\AttributableTrait;
use AppUtils\Traits\ClassableAttributeTrait;
use AppUtils\Traits\RenderableTrait;
use Mistralys\X4\UserInterface\DataGrid\GridColumn;
use Mistralys\X4\UserInterface\DataGrid\GridRow;

class GridCell implements ClassableAttributeInterface, AttributableInterface, RenderableInterface
{
    use ClassableAttributeTrait;
    use AttributableTrait;
    use RenderableTrait;

    private GridColumn $column;
    private GridRow $row;
    private AttributeCollection $attributes;

    public function __construct(GridColumn $column, GridRow $row)
    {
        $this->column = $column;
        $this->row = $row;
        $this->attributes = AttributeCollection::create();
    }

    public function getAttributes() : AttributeCollection
    {
        return $this->attributes;
    }

    public function getColumn() : GridColumn
    {
        return $this->column;
    }

    public function getRow() : GridRow
    {
        return $this->row;
    }

    /**
     * @return mixed|NULL
     */
    public function getValue()
    {
        return $this->row->getValue($this);
    }

    public function getObject() : ?object
    {
        return $this->row->getObject();
    }

    public function render() : string
    {
        $value = $this->column->applyFormattings($this->getValue());

        return $this->column->applyDecorations($this, $value);
    }
}
