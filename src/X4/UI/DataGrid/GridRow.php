<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\AttributeCollection;
use AppUtils\Interfaces\AttributableInterface;
use AppUtils\Interfaces\ClassableAttributeInterface;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\JSHelper;
use AppUtils\Traits\AttributableTrait;
use AppUtils\Traits\ClassableAttributeTrait;
use AppUtils\Traits\RenderableBufferedTrait;
use Mistralys\X4\X4Application;

abstract class GridRow implements ClassableAttributeInterface, RenderableInterface, AttributableInterface
{
    use ClassableAttributeTrait;
    use RenderableBufferedTrait;
    use AttributableTrait;

    protected DataGrid $grid;
    protected AttributeCollection $attributes;
    private bool $collapsible = false;
    private bool $collapsed = false;
    private bool $jsInitialized = false;

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

    public function getID(bool $autoGenerate=false) : string
    {
        $id = $this->attributes->getAttribute('id');

        if($autoGenerate && empty($id)) {
            $id = JSHelper::nextElementID();
            $this->attributes->attr('id', $id);
        }

        return $id;
    }

    public function setID(string $id) : self
    {
        $this->attributes->id($id);
        return $this;
    }

    protected function init() : void
    {

    }

    public function getGrid() : DataGrid
    {
        return $this->grid;
    }

    // region: Collapsing

    public function collapse() : self
    {
        return $this->setCollapsed(true);
    }

    public function expand() : self
    {
        return $this->setCollapsed(false);
    }

    public function setCollapsed(bool $collapsed) : self
    {
        $this->makeCollapsible();
        $this->collapsed = $collapsed;
        return $this;
    }

    public function makeCollapsible(bool $enabled=true) : self
    {
        $this->collapsible = $enabled;

        if(empty($this->getID())) {
            $this->setID(JSHelper::nextElementID());
        }

        return $this;
    }

    private function initCollapsing() : void
    {
        if(!$this->collapsible)
        {
            return;
        }

        $this->initJS();

        $display = '';
        $class = 'expanded';
        if($this->collapsed) {
            $display = 'none';
            $class = 'collapsed';
        }

        $this->attributes->style('display', $display, false);
        $this->attributes->addClass('collapsible');
        $this->attributes->addClass($class);
    }

    /**
     * Gets the JavaScript statement that can be used to
     * toggle the row's collapsing (if enabled).
     *
     * @return string
     */
    public function getJSToggle() : string
    {
        if(!$this->collapsible) {
            return '';
        }

        return sprintf(
            '%s.Toggle()',
            $this->getJSObjectName()
        );
    }

    public function getJSObjectName() : string
    {
        $this->initJS();

        return $this->attributes->getAttribute('js-object');
    }

    private function initJS() : void
    {
        if($this->jsInitialized)
        {
            return;
        }

        $this->jsInitialized = true;

        $id = $this->getID(true);
        $objectName = 'gridRow'.$id;

        $this->attributes->attr('js-object', $objectName);

        $ui = $this->grid->getUI();
        $ui->addVendorJS(X4Application::PACKAGE_NAME, 'js/datagrid/row.js');
        $ui->addVendorStylesheet(X4Application::PACKAGE_NAME, 'css/datagrid/row.css');
        $ui->addJSHead(sprintf(
            "const %s = new GridRow('%s')",
            $objectName,
            $id
        ));
    }

    // endregion

    public function generateOutput() : void
    {
        $this->preRender();
        $this->initCollapsing();

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
