<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UserInterface\DataGrid\Column\ObjectHandler
 */

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid\Column;

use Mistralys\X4\UserInterface\DataGrid\DataGridException;
use Mistralys\X4\UserInterface\DataGrid\GridColumn;

/**
 * Utility used to fetch a column cell value from an
 * object, when the data grid entry is an object.
 *
 * @package X4Core
 * @subpackage UserInterface
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */
class ObjectHandler extends BaseHandler
{
    private string $methodName = '';
    private string $propertyName = '';

    /**
     * @var callable|null
     */
    private $valueCallback;

    /**
     * Uses the second value in a callable array as
     * the method name to call. Use this to specify a
     * method name, while retaining the refactoring
     * capabilities of your IDE.
     *
     * Example:
     *
     * <pre>
     * setMethod(array(TestObject::class, 'methodName'));
     * </pre>
     *
     * @param array{0:string,1:string} $callback
     * @return GridColumn
     */
    public function fetchByMethod(array $callback) : GridColumn
    {
        $this->methodName = $callback[1];
        return $this->getColumn();
    }

    /**
     * @param string $propertyName
     * @return GridColumn
     */
    public function fetchByProperty(string $propertyName) : GridColumn
    {
        $this->propertyName = $propertyName;
        return $this->getColumn();
    }

    /**
     * Sets a callback which will retrieve the target cell value
     * from the object. It is called for each row in the data grid
     * entries, for the cell matching the column.
     *
     * The callback gets the following parameters:
     *
     * 1) The object instance
     * 2) The column
     *
     * It must return the according value, or NULL if not found/relevant.
     *
     * @param callable $callback
     * @return GridColumn
     */
    public function fetchByCallback(callable $callback) : GridColumn
    {
        $this->valueCallback = $callback;
        return $this->getColumn();
    }

    /**
     * @param object $subject
     * @return mixed|null
     * @throws DataGridException
     */
    public function getValue(object $subject)
    {
        if(isset($this->valueCallback))
        {
            return $this->getValueFromCallback($this->valueCallback, $subject);
        }

        if(!empty($this->methodName))
        {
            return $this->getValueFromMethod($this->methodName, $subject);
        }

        if(!empty($this->propertyName))
        {
            return $this->getValueFromProperty($this->propertyName, $subject);
        }

        return null;
    }
}
