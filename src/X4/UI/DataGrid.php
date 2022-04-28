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

    public function createRow() : GridRow
    {
        return new GridRow($this);
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

    // region: Rendering

    protected function generateOutput() : void
    {
        ?>
        <div class="table-responsive">
            <table class="table table-striped table-sm <?php echo $this->classesToString() ?>">
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
            ?>
            <tr <?php echo $entry->classesToAttribute() ?>>
                <?php $entry->display() ?>
            </tr>
            <?php
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
