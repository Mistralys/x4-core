<?php

declare(strict_types=1);

namespace X4Tests\Suites\UI\DataGrid;

use X4Tests\Helpers\UI\DataGrid\TestObject;
use X4Tests\Helpers\X4TestCase;

final class ObjectValueTests extends X4TestCase
{
    public function test_methodName() : void
    {
        $object = new TestObject();

        $col = $this->ui
            ->createDataGrid()
            ->addColumn('default', 'Default')
            ->useObjectValues()->fetchByMethod(array(TestObject::class, 'getLabel'));

        $this->assertSame($object->getLabel(), $col->getValueFromObject($object));
    }

    public function test_propertyName() : void
    {
        $object = new TestObject();

        $col = $this->ui
            ->createDataGrid()
            ->addColumn('default', 'Default')
            ->useObjectValues()->fetchByProperty('publicProperty');

        $this->assertSame($object->publicProperty, $col->getValueFromObject($object));
    }

    public function test_accessor() : void
    {
        $object = new TestObject();

        $col = $this->ui
            ->createDataGrid()
            ->addColumn('default', 'Default')
            ->useObjectValues()->fetchByCallback(static function(TestObject $object) : int {
                return $object->getTheAnswer();
            });

        $this->assertSame($object->getTheAnswer(), $col->getValueFromObject($object));
    }
}
