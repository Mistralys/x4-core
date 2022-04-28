<?php

declare(strict_types=1);

namespace X4Tests\Helpers\TestApplication\Pages;

use Mistralys\X4\UI\BasePage;

class TestPageMain extends BasePage
{
    public const URL_NAME = 'TestPageMain';

    public function getTitle() : string
    {
        return 'Main page';
    }

    protected function preRender() : void
    {
    }

    protected function _render() : void
    {

    }

    public function getNavItems() : array
    {
        return array();
    }

    protected function getURLParams() : array
    {
        return array();
    }
}
