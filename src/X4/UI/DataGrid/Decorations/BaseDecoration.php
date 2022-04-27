<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid\Decorations;

use Mistralys\X4\UI\DataGrid\GridCell;
use Mistralys\X4\UserInterface\DataGrid\ValueFetcherInterface;
use Mistralys\X4\UserInterface\DataGrid\ValueFetcherTrait;

abstract class BaseDecoration implements ValueFetcherInterface
{
    use ValueFetcherTrait;

    abstract public function decorate(GridCell $cell, string $value) : string;
}
