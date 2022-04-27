<?php

declare(strict_types=1);

namespace X4Tests\Helpers\UI\DataGrid;

class TestObject
{
    public string $publicProperty = 'Property';

    public function getLabel() : string
    {
        return 'Test object';
    }

    public function getTheAnswer() : int
    {
        return 42;
    }
}
