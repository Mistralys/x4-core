<?php

declare(strict_types=1);

namespace X4Tests\Helpers;

use Mistralys\X4\UI\UserInterface;
use Mistralys\X4\X4Application;
use X4Tests\Helpers\TestApplication\Pages\TestPageMain;

class TestApplication extends X4Application
{
    public function getTitle() : string
    {
        return 'Test application';
    }

    public function registerPages(UserInterface $ui) : void
    {
        $ui->registerPage(TestPageMain::URL_NAME, TestPageMain::class);
    }

    public function getDefaultPageID() : ?string
    {
        return TestPageMain::URL_NAME;
    }

    public function getVersion() : string
    {
        return '0.1';
    }
}
