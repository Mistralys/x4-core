<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\AttributeCollection;
use AppUtils\Interfaces\AttributableInterface;
use AppUtils\Interfaces\ClassableAttributeInterface;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\AttributableTrait;
use AppUtils\Traits\ClassableAttributeTrait;
use AppUtils\Traits\RenderableBufferedTrait;

abstract class GridRow implements ClassableAttributeInterface, RenderableInterface, AttributableInterface
{
    use ClassableAttributeTrait;
    use RenderableBufferedTrait;
    use AttributableTrait;

    protected DataGrid $grid;
    protected AttributeCollection $attributes;

    /**
     * @var array<string,string>
     */
    protected array $styles = array();

    public function __construct(DataGrid $grid)
    {
        $this->grid = $grid;
        $this->attributes = AttributeCollection::create();

        $this->init();
    }

    public function getAttributes() : AttributeCollection
    {
        return $this->attributes;
    }

    protected function init() : void
    {

    }

    public function getGrid() : DataGrid
    {
        return $this->grid;
    }

    public function generateOutput() : void
    {
        $this->preRender();

        ?>
        <tr <?php echo $this->attributes->render() ?>>
            <?php $this->displayCells() ?>
        </tr>
        <?php
    }

    protected function preRender() : void
    {

    }

    abstract protected function displayCells() : void;
}
