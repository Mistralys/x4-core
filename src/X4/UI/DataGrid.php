<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\DataGrid
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\OutputBuffering;

/**
 * Utility class used to generate the HTML code for
 * data grids, using a friendly object-oriented interface.
 *
 * @package X4Core
 * @subpackage UserInterface
 */
class DataGrid
{
    /**
     * @var array<string,GridColumn>
     */
    private array $columns = array();

    /**
     * @var GridRow[]
     */
    private array $rows = array();

    public function render() : string
    {
        OutputBuffering::start();

        ?>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
            <?php
            $this->renderHeader();
            $this->renderRows();
            $this->renderFooter();
            ?>
            </table>
        </div>
        <?php

        return OutputBuffering::get();
    }

    public function addColumn(string $keyName, string $label) : GridColumn
    {
        $column = new GridColumn($keyName, $label);

        $this->columns[$keyName] = $column;

        return $column;
    }

    public function addRow(GridRow $entry) : self
    {
        $this->rows[] = $entry;
        return $this;
    }

    public function createRow(array $data) : GridRow
    {
        return new GridRow($data);
    }

    public function addRowFromArray(array $data) : self
    {
        return $this->addRow($this->createRow($data));
    }

    /**
     * Appends an entry using the specified object as source
     * for the cell values.
     *
     * @param object $object
     * @return $this
     * @throws DataGridException
     */
    public function addRowFromObject(object $object) : self
    {
        $data = array();
        $columns = $this->getColumns();

        foreach($columns as $column)
        {
            $data[$column->getKeyName()] = $column->getValueFromObject($object);
        }

        $this->addRowFromArray($data);

        return $this;
    }

    /**
     * @param object[] $objects
     * @return $this
     * @throws DataGridException
     */
    public function addRowsFromObjects(array $objects) : self
    {
        foreach($objects as $object)
        {
            $this->addRowFromObject($object);
        }

        return $this;
    }

    /**
     * @return GridColumn[]
     */
    public function getColumns() : array
    {
        return array_values($this->columns);
    }

    /**
     * @return GridRow[]
     */
    public function getRows() : array
    {
        return $this->rows;
    }

    private function renderHeader() : void
    {
        ?>
        <thead>
        <tr>
            <?php
            $columns = $this->getColumns();
            foreach($columns as $column)
            {
                ?>
                <th <?php echo $column->classesToAttribute() ?>>
                    <?php echo $column->getLabel() ?>
                </th>
                <?php
            }
            ?>
        </tr>
        </thead>
        <?php
    }

    private function renderRows() : void
    {
        ?>
        <tbody>
        <?php
        $entries = $this->getRows();
        $columns = $this->getColumns();
        foreach($entries as $entry)
        {
            ?>
            <tr <?php echo $entry->classesToAttribute() ?>>
                <?php $entry->displayColumns($columns) ?>
            </tr>
            <?php
        }
        ?>
        </tbody>
        <?php
    }

    private function renderFooter() : void
    {

    }
}
