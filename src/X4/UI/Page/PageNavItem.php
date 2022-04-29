<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Page;

class PageNavItem extends NavItem
{
    public function __construct(BasePage $page)
    {
        parent::__construct($page->getNavTitle(), $page->getURL());
    }
}
