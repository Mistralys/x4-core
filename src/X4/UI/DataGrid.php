<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\OutputBuffering;

class DataGrid
{
    /**
     * @var array<string,GridColumn>
     */
    private array $columns = array();

    /**
     * @var GridEntry[]
     */
    private array $entries = array();

    public function render() : string
    {
        OutputBuffering::start();

        ?>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
            <?php
            $this->renderHeader();
            $this->renderEntries();
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

    public function appendEntry(GridEntry $entry) : self
    {
        $this->entries[] = $entry;
        return $this;
    }

    public function createEntry(array $data) : GridEntry
    {
        return new GridEntry($data);
    }

    public function appendFromArray(array $data) : self
    {
        return $this->appendEntry($this->createEntry($data));
    }

    /**
     * @return GridColumn[]
     */
    public function getColumns() : array
    {
        return array_values($this->columns);
    }

    /**
     * @return GridEntry[]
     */
    public function getEntries() : array
    {
        return $this->entries;
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

    private function renderEntries() : void
    {
        ?>
        <tbody>
        <?php
        $entries = $this->getEntries();
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
