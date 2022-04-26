<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

use AppUtils\Request;

abstract class BasePage
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();

        $this->init();
    }

    protected function init() : void
    {

    }

    public function getID() : string
    {
        $parts = explode('\\', get_class($this));
        return array_pop($parts);
    }

    abstract public function getTitle() : string;

    public function render() : string
    {
        ob_start();
        $this->_render();
        return ob_get_clean();
    }

    abstract protected function _render() : void;

    /**
     * @return NavItem[]
     */
    abstract public function getNavItems() : array;

    protected function redirect(string $url) : void
    {
        header('Location:'.$url);
        exit;
    }

    protected function renderBool(bool $boolean) : string
    {
        if($boolean === true) {
            return '<i class="fa fa-check"></i>';
        }

        return '<i class="fa fa-times"></i>';
    }

    public function getURL(array $params=array()) : string
    {
        $params['page'] = $this->getID();

        $params = array_merge($params, $this->getURLParams());

        return '?'.http_build_query($params);
    }

    abstract protected function getURLParams() : array;
}
