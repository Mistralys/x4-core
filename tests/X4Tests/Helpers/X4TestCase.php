<?php

declare(strict_types=1);

namespace X4Tests\Helpers;

use Mistralys\X4\UI\UserInterface;
use PHPUnit\Framework\TestCase;

class X4TestCase extends TestCase
{
    protected UserInterface $ui;
    protected TestApplication $application;

    protected function setUp() : void
    {
        if(!isset($this->ui))
        {
            $this->application = new TestApplication();
            $this->ui = new UserInterface($this->application);
        }
    }
}
