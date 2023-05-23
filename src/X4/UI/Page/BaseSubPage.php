<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

use AppUtils\Request;
use Mistralys\X4\UI\Page\BasePage;

abstract class BaseSubPage
{
    protected BasePage $page;
    protected Request $request;

    public function __construct(BasePage $page)
    {
        $this->page = $page;
        $this->request = $page->getRequest();

        $this->init();
    }

    protected function init() : void
    {

    }

    public function getURL() : string
    {
        $params[BasePage::REQUEST_PARAM_VIEW] = $this->getURLName();

        return $this->page->getURL($params);
    }

    public function generateOutput() : void
    {
        $this->preRender();
        $this->renderContent();
    }

    protected function preRender() : void
    {

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
    abstract public function getSubtitle() : string;
    abstract public function getAbstract() : string;
}
