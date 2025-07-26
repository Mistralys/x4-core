<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Ajax;

use Mistralys\X4\UI\UserInterface;
use Mistralys\X4\X4Application;

abstract class BaseAjaxMethod implements AjaxMethodInterface
{
    protected AjaxMethods $methods;
    protected UserInterface $ui;
    protected X4Application $application;

    public function __construct(AjaxMethods $methods)
    {
        $this->methods = $methods;
        $this->ui = $methods->getUI();
        $this->application = $this->ui->getApplication();

        $this->init();
    }

    abstract protected function init() : void;

    public function process() : never
    {
        $this->preProcess();
        $this->_process();
    }

    abstract protected function _process() : never;
    abstract protected function preProcess() : void;

    protected function sendError(string $message, int $code, array $payload=array()) : never
    {
        $this->methods->sendError($message, $code, $payload);
    }

    protected function sendSuccess(string $message='', array $payload=array()) : never
    {
        $this->methods->sendSuccess($message, $payload);
    }
}
