<?php

declare(strict_types=1);

namespace X4Tests\Helpers\TestApplication\Pages;

use Mistralys\X4\UI\Page\BasePage;
use Mistralys\X4\UI\Page\PageNavItem;
use function AppLocalize\pt;
use function AppUtils\t;

class TestPageMain extends BasePage
{
    public const URL_NAME = 'TestPageMain';

    public function getTitle() : string
    {
        return t('Main test page');
    }

    public function getNavTitle() : string
    {
        return t('Main');
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
            new PageNavItem($this)
        );
    }

    protected function getURLParams() : array
    {
        return array();
    }
}
