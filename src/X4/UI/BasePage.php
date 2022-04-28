<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Request;
use AppUtils\Traits\RenderableBufferedTrait;
use Mistralys\X4\X4Application;

abstract class BasePage implements RenderableInterface
{
    use RenderableBufferedTrait;

    public const REQUEST_PARAM_PAGE = 'page';

    protected Request $request;
    protected UserInterface $ui;
    private X4Application $application;

    public function __construct(UserInterface $ui)
    {
        $this->ui = $ui;
        $this->request = $ui->getRequest();
        $this->application = $ui->getApplication();

        $this->init();
    }

    protected function init() : void
    {

    }

    public function getApplication() : X4Application
    {
        return $this->application;
    }

    public function getUI() : UserInterface
    {
        return $this->ui;
    }

    public function getID() : string
    {
        $parts = explode('\\', get_class($this));
        return array_pop($parts);
    }

    abstract public function getTitle() : string;

    protected function generateOutput() : void
    {
        $this->_render();
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
        $params[self::REQUEST_PARAM_PAGE] = $this->getID();

        $params = array_merge($params, $this->getURLParams());

        return '?'.http_build_query($params);
    }

    abstract protected function getURLParams() : array;
}
