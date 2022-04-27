<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\Column\BaseHandler
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid\Column;

use Mistralys\X4\UserInterface\DataGrid\GridColumn;
use Mistralys\X4\UserInterface\DataGrid\ValueFetcherInterface;
use Mistralys\X4\UserInterface\DataGrid\ValueFetcherTrait;

/**
 * Base abstract class for column configuration
 * handler classes.
 *
 * @package X4Core
 * @subpackage UserInterface
 */
abstract class BaseHandler implements ValueFetcherInterface
{
    use ValueFetcherTrait;

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
}
