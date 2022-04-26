<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid;

use AppUtils\Interface_Classable;
use AppUtils\Traits_Classable;

class GridColumn implements Interface_Classable
{
    use Traits_Classable;

    public const ALIGN_RIGHT = 'align-right';
    public const ALIGN_CENTER = 'align-center';
    public const ALIGN_LEFT = 'align-left';

    private string $label;
    private string $keyName;

    public function __construct(string $keyName, string $label)
    {
        $this->label = $label;
        $this->keyName = $keyName;
    }

    public function getKeyName() : string
    {
        return $this->keyName;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function alignRight() : self
    {
        return $this->setAlign(self::ALIGN_RIGHT);
    }

    public function alignCenter() : self
    {
        return $this->setAlign(self::ALIGN_CENTER);
    }

    public function alignLeft() : self
    {
        return $this->setAlign(self::ALIGN_LEFT);
    }

    public function setAlign(string $align) : self
    {
        $this->removeClass(self::ALIGN_RIGHT);
        $this->removeClass(self::ALIGN_CENTER);
        $this->removeClass(self::ALIGN_LEFT);

        $this->addClass($align);
        return $this;
    }
}
