<?php

declare(strict_types=1);

namespace Mistralys\X4\UserInterface\DataGrid\Decorations;

use AppUtils\AttributeCollection;
use Mistralys\X4\UI\DataGrid\GridCell;

class LinkByCallback extends BaseLinkDecoration
{
    /**
     * @var callable
     */
    private $linkCallback;
    private bool $newTab;
    private ?AttributeCollection $attributes = null;

    public function __construct(callable $callback, bool $newTab=false, ?AttributeCollection $attributes=null)
    {
        $this->linkCallback = $callback;
        $this->newTab = $newTab;
        $this->attributes = $attributes;
    }

    public function decorate(GridCell $cell, string $value) : string
    {
        $link = (string)call_user_func(
            $this->linkCallback,
            $cell->getValue(),
            $cell->getObject()
        );

        if(!empty($link))
        {
            return $this->renderLink($value, $link, $this->newTab, $this->attributes);
        }

        return $value;
    }
}
