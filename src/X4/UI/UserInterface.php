<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UI\UserInterface
 */

declare(strict_types=1);

namespace Mistralys\X4\UI;

use AppUtils\Interfaces\RenderableInterface;
use AppUtils\Request;
use AppUtils\Traits\RenderableBufferedTrait;
use Mistralys\X4\UI\Page\BasePage;
use Mistralys\X4\UserInterface\DataGrid\DataGrid;
use Mistralys\X4\UserInterface\UIException;
use Mistralys\X4\X4Application;
use function AppLocalize\pt;

/**
 * User interface handler: renders the UI.
 *
 * @package X4Core
 * @subpackage UserInterface
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */
class UserInterface implements RenderableInterface
{
    use RenderableBufferedTrait;

    public const ERROR_NO_PAGES_REGISTERED = 105801;
    public const ERROR_PAGE_CLASS_NOT_FOUND = 105802;
    public const ERROR_INVALID_PAGE_CLASS = 105803;
    public const ERROR_UNKNOWN_PAGE_ID = 105804;

    public const THEME_SUPERHERO = 'superhero';

    private BasePage $activePage;
    private X4Application $application;
    private Request $request;
    private string $webrootURL;
    private string $vendorURL;
    private string $unitTestingURL;
    private string $theme = self::THEME_SUPERHERO;

    /**
     * @var array<string,string>
     */
    private array $pages = array();

    /**
     * @var array<string,BasePage>
     */
    private array $pageInstances = array();

    /**
     * @var string[]
     */
    private array $styleSheets = array();

    /**
     * @var string[]
     */
    private array $javaScripts = array();

    /**
     * @param X4Application $application
     * @param string $webrootURL
     * @param string $vendorURL Optional: Custom URL to access the composer vendor folder.
     * @throws UIException
     */
    public function __construct(X4Application $application, string $webrootURL, string $vendorURL='')
    {
        $this->application = $application;
        $this->webrootURL = $webrootURL;
        $this->vendorURL = $vendorURL;
        $this->request = new Request();

        $this->application->registerPages($this);

        $this->activePage = $this->createPage($this->getActivePageID());
    }

    public function getTitle() : string
    {
        return $this->application->getTitle();
    }

    public function getRequest() : Request
    {
        return $this->request;
    }

    public function getWebrootURL() : string
    {
        return $this->webrootURL;
    }

    public function setUnitTestingURL(string $unitTestingURL) : self
    {
        $this->unitTestingURL = $unitTestingURL;
        return $this;
    }

    public function createDataGrid() : DataGrid
    {
        return new DataGrid();
    }

    public function registerPage(string $urlName, string $className) : void
    {
        $this->pages[$urlName] = $className;
    }

    /**
     * Retrieves the class name of the target page, by its ID (url name).
     *
     * @param string $id
     * @return string
     *
     * @throws UIException
     * @see UserInterface::ERROR_UNKNOWN_PAGE_ID
     */
    public function getPageClass(string $id) : string
    {
        if(isset($this->pages[$id]))
        {
            return $this->pages[$id];
        }

        throw new UIException(
            'Unknown page',
            sprintf(
                'No page registered with ID [%s]. Registered pages are: [%s].',
                $id,
                implode(', ', array_keys($this->pages))
            ),
            self::ERROR_UNKNOWN_PAGE_ID
        );
    }

    /**
     * @param string $id
     * @return BasePage
     *
     * @throws UIException
     * @see UserInterface::ERROR_PAGE_CLASS_NOT_FOUND
     * @see UserInterface::ERROR_INVALID_PAGE_CLASS
     */
    public function createPage(string $id) : BasePage
    {
        if(isset($this->pageInstances[$id]))
        {
            return $this->pageInstances[$id];
        }

        $class = $this->getPageClass($id);

        if(!class_exists($class))
        {
            throw new UIException(
                'Unknown page',
                sprintf('The page class [%s] does not exist.', $class),
                self::ERROR_PAGE_CLASS_NOT_FOUND
            );
        }

        $page = new $class($this);

        if($page instanceof BasePage)
        {
            $this->pageInstances[$id] = $page;
            return $page;
        }

        throw new UIException(
            'Invalid page',
            sprintf(
                'The page class [%s] does not extend the base class [%s].',
                $class,
                BasePage::class
            ),
            self::ERROR_INVALID_PAGE_CLASS
        );
    }

    public function getApplication() : X4Application
    {
        return $this->application;
    }

    /**
     * @return string
     *
     * @throws UIException
     * @see UserInterface::ERROR_NO_PAGES_REGISTERED
     */
    public function getActivePageID() : string
    {
        if(empty($this->pages))
        {
            throw new UIException(
                'No pages have been registered.',
                '',
                self::ERROR_NO_PAGES_REGISTERED
            );
        }

        $pageID = (string)$this->request->getParam('page');

        if(isset($this->pages[$pageID]))
        {
            return $pageID;
        }

        $default = $this->application->getDefaultPageID();

        if($default !== null && isset($this->pages[$default]))
        {
            return $default;
        }

        reset($this->pages);

        return key($this->pages);
    }

    public function addInternalStylesheet(string $file) : self
    {
        return $this->addToCollection($this->styleSheets, sprintf(
            '%s/css/%s',
            $this->webrootURL,
            $file
        ));
    }

    public function addExternalStylesheet(string $url) : self
    {
        return $this->addToCollection($this->styleSheets, $url);
    }

    public function addVendorStylesheet(string $packageName, string $file) : self
    {
        return $this->addVendorInclude($packageName, $file, $this->styleSheets);
    }

    public function addVendorJS(string $packageName, string $file) : self
    {
        return $this->addVendorInclude($packageName, $file, $this->javaScripts);
    }

    private function addToCollection(array &$collection, $url) : self
    {
        if(!in_array($url, $collection, true))
        {
            $collection[] = $url;
        }

        return $this;
    }

    protected function addVendorInclude(string $packageName, string $file, &$collection) : self
    {
        if($packageName === 'mistralys/x4-core' && !empty($this->unitTestingURL))
        {
            return $this->addToCollection($collection, sprintf(
                '%s/%s',
                $this->unitTestingURL,
                $file
            ));
        }

        $baseURL = $this->webrootURL.'/vendor';

        if(!empty($this->vendorURL))
        {
            $baseURL = $this->vendorURL;
        }

        return $this->addToCollection($collection, sprintf(
            '%s/%s/%s',
            $baseURL,
            $packageName,
            $file
        ));
    }

    private function initIncludes() : void
    {
        $this->addVendorStylesheet('thomaspark/bootswatch', 'dist/'.$this->theme.'/bootstrap.min.css');        $this->addVendorStylesheet('fortawesome/font-awesome', 'css/fontawesome.css');
        $this->addVendorStylesheet('fortawesome/font-awesome', 'css/solid.css');
        $this->addVendorStylesheet('mistralys/x4-core', 'css/ui.css');

        $this->addVendorJS('components/jquery', 'jquery.slim.js');
        $this->addVendorJS('twbs/bootstrap', 'dist/js/bootstrap.js');
    }

    protected function generateOutput() : void
    {
        $this->initIncludes();

        $content = $this->activePage->render();

        ?><!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <title><?php echo $this->getTitle() ?></title>
                <?php
                foreach($this->styleSheets as $url)
                {
                    ?>
                    <link rel="stylesheet" href="<?php echo $url ?>">
                    <?php
                }
                ?>
            </head>
            <body>
                <div class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
                    <div class="container">
                        <a class="navbar-brand" href="?"><?php echo $this->getTitle() ?></a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarReponsive" aria-controls="navbarReponsive" aria-expanded="false" aria-label="<?php pt('Toggle navigation') ?>">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarResponsive">
                            <?php
                            $this->displayNavigation();
                            $this->displayMetaNav();
                            ?>
                        </div>
                    </div>
                </div>
                <div class="container" id="main-content">
                    <div class="page-header">
                        <h1 class="page-title"><?php echo $this->activePage->getTitle() ?></h1>
                    </div>
                    <div class="content-container">
                        <?php echo $content; ?>
                    </div>
                    <footer>
                        <?php echo $this->getTitle() ?> v<?php echo $this->application->getVersion() ?><br>
                    </footer>
                </div>
                <?php
                foreach($this->javaScripts as $url)
                {
                    ?>
                    <script src="<?php echo $url ?>"></script>
                    <?php
                }
                ?>
            </body>
        </html><?php
    }

    private function displayMetaNav() : void
    {
        return;

        ?>
        <ul class="navbar-nav ms-md-auto">
            <li class="nav-item">
                <a target="_blank" rel="noopener" class="nav-link" href="https://github.com/thomaspark/bootswatch/"><i class="fa fa-github"></i> GitHub</a>
            </li>
            <li class="nav-item">
                <a target="_blank" rel="noopener" class="nav-link" href="https://twitter.com/bootswatch"><i class="fa fa-twitter"></i> Twitter</a>
            </li>
        </ul>
        <?php
    }

    /**
     * @return void
     */
    private function displayNavigation() : void
    {
        $items = $this->activePage->getNavItems();

        if(empty($items))
        {
            return;
        }

        ?>
        <ul class="navbar-nav">
            <?php
            foreach($items as $item)
            {
                ?>
                <li class="nav-item">
                    <a class="nav-link <?php if($item->isActive()) { echo 'active'; } ?>" href="<?php echo $item->getUrl() ?>">
                        <?php echo $item->getLabel() ?>
                    </a>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }
}
