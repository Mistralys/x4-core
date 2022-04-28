<?php
/**
 * @package X4Core
 * @subpackage Application
 * @see \Mistralys\X4\X4Application
 */

declare(strict_types=1);

namespace Mistralys\X4;

use AppLocalize\Localization;
use Mistralys\X4\UI\UserInterface;

/**
 * Base class for an X4 application.
 *
 * @package X4Core
 * @subpackage Application
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */
abstract class X4Application
{
    private ?UserInterface $ui = null;

    abstract public function getTitle() : string;

    abstract public function registerPages(UserInterface $ui) : void;

    abstract public function getDefaultPageID() : ?string;

    abstract public function getVersion() : string;

    public function __construct()
    {
        $this->initLocalization();
    }

    private function initLocalization() : void
    {
        Localization::addAppLocale('de_DE');

        Localization::addSourceFolder(
            'x4-core',
            'X4 core libraries',
            'X4',
            __DIR__.'/../../localization',
            __DIR__.'/../'
        );

        Localization::configure(
            __DIR__.'/../../localization/cache.json'
        );

        Localization::setClientLibrariesCacheKey($this->getVersion());
    }

    public function createUI(string $webrootURL, string $vendorURL='') : UserInterface
    {
        if(isset($this->ui))
        {
            return $this->ui;
        }

        $ui = new UserInterface($this, $webrootURL, $vendorURL);

        $this->ui = $ui;

        return $ui;
    }
}
