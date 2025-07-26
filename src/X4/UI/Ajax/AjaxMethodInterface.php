<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Ajax;

use AppUtils\Interfaces\StringPrimaryRecordInterface;

interface AjaxMethodInterface extends StringPrimaryRecordInterface
{
    public const ERROR_UNKNOWN_METHOD = 179101;

    public function process() : never;
}
