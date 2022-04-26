<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

abstract class BaseSubPage
{
    protected BasePage $page;

    public function __construct(BasePage $page)
    {
        $this->page = $page;

        $this->init();
    }

    protected function init() : void
    {

    }

    public function getURL() : string
    {
        $params['view'] = $this->getURLName();

        return $this->page->getURL($params);
    }

    abstract protected function getURLParams() : array;

    protected function renderBool(bool $boolean) : string
    {
        if($boolean === true) {
            return '<i class="fa fa-check"></i>';
        }

        return '<i class="fa fa-times"></i>';
    }

    abstract public function isInSubnav() : bool;

    abstract public function getURLName() : string;

    abstract public function renderContent() : void;

    abstract public function getTitle() : string;
}
