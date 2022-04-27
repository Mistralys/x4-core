<?php

declare(strict_types=1);

namespace X4Tests\Suites\UI\DataGrid;

use AppUtils\ConvertHelper;
use DateTime;
use X4Tests\Helpers\X4TestCase;

class FormattingTests extends X4TestCase
{
    public function test_formatBoolean() : void
    {
        $col = $this->ui
            ->createDataGrid()
            ->addColumn('default', 'Default')
            ->chooseFormat()->boolean()->done();

        $this->assertSame('true', $col->formatValue(true));
        $this->assertSame('false', $col->formatValue(false));
        $this->assertSame('true', $col->formatValue('yes'));
        $this->assertSame('false', $col->formatValue('no'));
    }

    public function test_dateFormat() : void
    {
        $col = $this->ui
            ->createDataGrid()
            ->addColumn('default', 'Default')
            ->chooseFormat()->dateFormat('Y-m-d')->done();

        $this->assertSame('2020-05-03', $col->formatValue(new DateTime('2020-05-03')));
    }

    public function test_dateAuto() : void
    {
        $col = $this->ui
            ->createDataGrid()
            ->addColumn('default', 'Default')
            ->chooseFormat()->dateAuto(true, true)->done();

        $date = new DateTime('2020-05-03 12:12:30');

        $this->assertSame(ConvertHelper::date2listLabel($date, true, true), $col->formatValue($date));
    }
}
