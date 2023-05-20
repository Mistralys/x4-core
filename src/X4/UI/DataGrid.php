<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\DataGrid
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\Interface_Classable;
use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Traits\RenderableBufferedTrait;
use AppUtils\Traits_Classable;
use Mistralys\X4\UI\DataGrid\Row\MergedRow;
use Mistralys\X4\UI\DataGrid\Row\RegularRow;

/**
 * Utility class used to generate the HTML code for
 * data grids, using a friendly object-oriented interface.
 *
 * @package X4Core
 * @subpackage UserInterface
 */
class DataGrid implements RenderableInterface, Interface_Classable
{
    use RenderableBufferedTrait;
    use Traits_Classable;

    /**
     * @var array<string,GridColumn>
     */
    private array $columns = array();

    /**
     * @var GridRow[]
     */
    private array $rows = array();
    private bool $optionStriped = false;
    private bool $optionBordered = true;

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

    public function createRow() : RegularRow
    {
        return new RegularRow($this);
    }

    public function countColumns() : int
    {
        return count($this->columns);
    }

    public function createMergedRow() : MergedRow
    {
        return new MergedRow($this);
    }

    /**
     * @param array<string,mixed> $values
     * @return $this
     */
    public function addRowFromArray(array $values) : self
    {
        return $this->addRow($this->createRow()->setValues($values));
    }

    /**
     * Appends an entry using the specified object as source
     * for the cell values.
     *
     * @param object $object
     * @return $this
     */
    public function addRowFromObject(object $object) : self
    {
        return $this->addRow($this->createRow()->setObject($object));
    }

    /**
     * @param object[] $objects
     * @return $this
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

    public function optionStriped(bool $enable) : self
    {
        $this->optionStriped = $enable;
        return $this;
    }

    public function optionBordered(bool $enable) : self
    {
        $this->optionBordered = $enable;
        return $this;
    }

    // region: Rendering

    protected function generateOutput() : void
    {
        $classes = $this->getClasses();

        if($this->optionStriped) {
            $classes[] = 'table-striped';
        }

        if($this->optionBordered) {
            $classes[] = 'table-bordered';
        }

        ?>
        <div class="table-responsive">
            <table class="table table-sm <?php echo implode(' ', $classes) ?>">
                <?php
                $this->generateHeader();
                $this->generateRows();
                $this->generateFooter();
                ?>
            </table>
        </div>
        <?php
    }

    private function generateHeader() : void
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

    private function generateRows() : void
    {
        ?>
        <tbody>
        <?php
        $entries = $this->getRows();
        foreach($entries as $entry)
        {
            $entry->display();
        }
        ?>
        </tbody>
        <?php
    }

    private function generateFooter() : void
    {

    }

    // endregion
}
