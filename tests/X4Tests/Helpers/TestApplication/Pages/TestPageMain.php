<?php

declare(strict_types=1);

namespace X4Tests\Helpers\TestApplication\Pages;

use Mistralys\X4\UI\Page\BasePage;
use Mistralys\X4\UI\Page\NavItem;
use function AppLocalize\pt;
use function AppUtils\t;

class TestPageMain extends BasePage
{
    public const URL_NAME = 'TestPageMain';

    public function getTitle() : string
    {
        return t('Test page');
    }

    public function getNavTitle() : string
    {
        return t('Test');
    }

    protected function preRender() : void
    {
    }

    protected function _render() : void
    {
        ?>
        <p>
            <?php pt('This is an X4 application test page.') ?>
        </p>
        <?php
    }

    public function getNavItems() : array
    {
        return array(
            new NavItem(t('Main'), $this->getURL())
        );
    }

    protected function getURLParams() : array
    {
        return array();
    }
}
