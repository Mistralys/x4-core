<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid\Decorations;

use AppUtils\AttributeCollection;
use Mistralys\X4\UI\DataGrid\GridCell;

class LinkByMethod extends BaseLinkDecoration
{
    private string $methodName;
    private bool $newTab;
    private ?AttributeCollection $attributes;

    public function __construct(string $methodName, bool $newTab=false, ?AttributeCollection $attributes=null)
    {
        $this->methodName = $methodName;
        $this->newTab = $newTab;
        $this->attributes = $attributes;
    }

    public function decorate(GridCell $cell, string $value) : string
    {
        $object = $cell->getObject();

        if($object === null)
        {
            return $value;
        }

        $link = (string)$this->getValueFromMethod($this->methodName, $object);

        if(!empty($link))
        {
            return $this->renderLink($value, $link, $this->newTab, $this->attributes);
        }

        return $value;
    }
}
