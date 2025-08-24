<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Page;

use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Interfaces\StringableInterface;
use AppUtils\Request;
use AppUtils\Traits\RenderableBufferedTrait;
use Mistralys\X4\UI\Ajax\AjaxMethodInterface;
use Mistralys\X4\UI\Ajax\AjaxMethods;
use Mistralys\X4\UI\Page\NavItem;
use Mistralys\X4\UI\UserInterface;
use Mistralys\X4\X4Application;
use function AppLocalize\t;

abstract class BasePage implements RenderableInterface
{
    use RenderableBufferedTrait;

    public const string REQUEST_PARAM_PAGE = 'page';
    public const string REQUEST_PARAM_VIEW = 'view';

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

    public function getRequest() : Request
    {
        return $this->request;
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
    abstract public function getSubtitle() : string;
    abstract public function getAbstract() : string;

    abstract public function getNavTitle() : string;

    protected function generateOutput() : void
    {
        $ajax = $this->request->registerParam(AjaxMethods::REQUEST_PARAM_AJAX)->setRegex('/^[a-zA-Z0-9.]+$/')->getString();

        if(!empty($ajax)) {
            $this->handleAjaxRequest($ajax);
        }

        $this->preRender();

        $this->_render();
    }

    protected function handleAjaxRequest(string $action) : never
    {
        $methods = $this->ui->getAjaxMethods();

        if(!$methods->idExists($action)) {
            $methods->sendError(
                'Unknown AJAX method',
                AjaxMethodInterface::ERROR_UNKNOWN_METHOD
            );
        }

        $methods->getByID($action)->process();
    }

    abstract protected function preRender() : void;

    abstract protected function _render() : void;

    /**
     * @return NavItem[]
     */
    abstract public function getNavItems() : array;

    public function redirectWithSuccessMessage(string $url, string|StringableInterface|null $message, int $code) : never
    {
        $this->ui->getMessages()->addSuccess($message, $code);

        $this->redirect($url);
    }

    public function redirectWithErrorMessage(string $url, string|StringableInterface|null $message, int $code) : never
    {
        $this->ui->getMessages()->addError($message, $code);

        $this->redirect($url);
    }

    public function redirectWithInfoMessage(string $url, string|StringableInterface|null $message, int $code) : never
    {
        $this->ui->getMessages()->addInfo($message, $code);

        $this->redirect($url);
    }

    public function redirectWithWarningMessage(string $url, string|StringableInterface|null $message, int $code) : never
    {
        $this->ui->getMessages()->addWarning($message, $code);

        $this->redirect($url);
    }

    public function redirect(string $url) : never
    {
        header('Location:'.$url);

        $this->application->exit();
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

        foreach($this->getURLParams() as $param => $value) {
            if(!isset($params[$param])) {
                $params[$param] = $value;
            }
        }

        return $this->ui->getWebrootURL().'?'.http_build_query($params);
    }

    abstract protected function getURLParams() : array;
}
