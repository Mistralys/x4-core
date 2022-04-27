<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

trait ValueFetcherTrait
{
    /**
     * @param callable $callback
     * @param object $subject
     * @return mixed|NULL
     */
    protected function getValueFromCallback(callable $callback, object $subject)
    {
        return $callback($subject, $this);
    }

    /**
     * @param string $methodName
     * @param object $subject
     * @return mixed|NULL
     * @throws DataGridException
     */
    protected function getValueFromMethod(string $methodName, object $subject)
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
            ValueFetcherInterface::ERROR_METHOD_DOES_NOT_EXIST
        );
    }

    /**
     * @param string $propertyName
     * @param object $subject
     * @return mixed|NULL
     * @throws DataGridException
     */
    protected function getValueFromProperty(string $propertyName, object $subject)
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
            ValueFetcherInterface::ERROR_PROPERTY_DOES_NOT_EXIST
        );
    }
}
