<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \X4\DataGrid\ObjectHandler
 */

declare(strict_types=1);

namespace X4\DataGrid;

use Mistralys\X4\UserInterface\DataGrid\BaseHandler;
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
    public const ERROR_METHOD_DOES_NOT_EXIST = 105901;
    public const ERROR_PROPERTY_DOES_NOT_EXIST = 105902;

    private string $methodName = '';
    private string $propertyName = '';

    /**
     * @var callable|null
     */
    private $accessor;

    /**
     * @param string $methodName
     * @return ObjectHandler
     */
    public function setMethodName(string $methodName) : self
    {
        $this->methodName = $methodName;
        return $this;
    }

    /**
     * @param string $propertyName
     * @return ObjectHandler
     */
    public function setPropertyName(string $propertyName) : self
    {
        $this->propertyName = $propertyName;
        return $this;
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
     * @param callable $accessor
     * @return ObjectHandler
     */
    public function setAccessor(callable $accessor) : self
    {
        $this->accessor = $accessor;
        return $this;
    }

    /**
     * @param object $subject
     * @return mixed|null
     * @throws DataGridException
     */
    public function getValue(object $subject)
    {
        if(isset($this->accessor))
        {
            return $this->getValueFromAccessor($this->accessor, $subject);
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

    /**
     * @param callable $accessor
     * @param object $subject
     * @return mixed|NULL
     */
    private function getValueFromAccessor(callable $accessor, object $subject)
    {
        return $accessor($subject, $this);
    }

    /**
     * @param string $methodName
     * @param object $subject
     * @return mixed|NULL
     * @throws DataGridException
     */
    private function getValueFromMethod(string $methodName, object $subject)
    {
        if(method_exists($subject, $methodName))
        {
            return $subject->$methodName();
        }

        throw new DataGridException(
            'Method does not exist in object',
            sprintf(
                'The method [%s] for column [%s] does not exist in object of class [%s].',
                $methodName,
                $this->column->getKeyName(),
                get_class($subject)
            ),
            self::ERROR_METHOD_DOES_NOT_EXIST
        );
    }

    /**
     * @param string $propertyName
     * @param object $subject
     * @return mixed|NULL
     * @throws DataGridException
     */
    private function getValueFromProperty(string $propertyName, object $subject)
    {
        if(isset($subject->$propertyName))
        {
            return $subject->$propertyName;
        }

        throw new DataGridException(
            'Property does not exist in object',
            sprintf(
                'The property [$%s] for column [%s] does not exist in object of class [%s].',
                $propertyName,
                $this->column->getKeyName(),
                get_class($subject)
            ),
            self::ERROR_PROPERTY_DOES_NOT_EXIST
        );
    }
}
