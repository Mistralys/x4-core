<?php
/**
 * @package X4Tests
 * @subpackage Helpers
 * @see \X4Tests\Helpers\X4TestCase
 */

declare(strict_types=1);

namespace X4Tests\Helpers;

use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\UI\UserInterface;
use PHPUnit\Framework\TestCase;

/**
 * @package X4Tests
 * @subpackage Helpers
 */
class X4TestCase extends TestCase
{
    private static ?TestApplication $appInstance = null;
    protected UserInterface $ui;
    protected TestApplication $application;

    protected function setUp() : void
    {
        // Reset the collection to ensure it uses the vanilla list of blueprints every time.
        BlueprintDefs::reset();

        if(!isset($this->application))
        {
            $this->application = self::createTestApp();
            $this->ui = $this->application->getUI();
        }
    }

    public static function createTestApp() : TestApplication
    {
        if(isset(self::$appInstance))
        {
            return self::$appInstance;
        }

        $app = new TestApplication();

        $app->createUI(
            X4TESTS_INSTALL_URL.'/tests/X4Tests/ExampleUI',
            X4TESTS_INSTALL_URL.'/vendor'
        )
            ->setUnitTestingURL(X4TESTS_INSTALL_URL);

        self::$appInstance = $app;

        return $app;
    }
}
