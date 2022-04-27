<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\BaseHandler
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

/**
 * Base abstract class for column configuration
 * handler classes.
 *
 * @package X4Core
 * @subpackage UserInterface
 */
abstract class BaseHandler
{
    protected GridColumn $column;

    public function __construct(GridColumn $column)
    {
        $this->column = $column;
    }

    /**
     * @return GridColumn
     */
    public function getColumn() : GridColumn
    {
        return $this->column;
    }

    /**
     * Utility method for chaining: When using the
     * column's {@see GridColumn::object()} method,
     * use this to go back to the column instance once
     * you have configured the object handler.
     *
     * @return GridColumn
     */
    public function done() : GridColumn
    {
        return $this->column;
    }
}
