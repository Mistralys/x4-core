<?php
/**
 * @package X4Core
 * @subpackage UserInterface
 * @see \Mistralys\X4\UI\UserInterface
 */

declare(strict_types=1);

namespace Mistralys\X4\UI;

use AppUtils\Request;
use Mistralys\X4\UserInterface\DataGrid\DataGrid;use Mistralys\X4\UserInterface\UIException;
use Mistralys\X4\X4Application;

/**
 * User interface handler: renders the UI.
 *
 * @package X4Core
 * @subpackage UserInterface
 * @author Sebastian Mordziol <s.mordziol@mistralys.eu>
 */
class UserInterface
{
    public const ERROR_NO_PAGES_REGISTERED = 105801;
    public const ERROR_PAGE_CLASS_NOT_FOUND = 105802;
    public const ERROR_INVALID_PAGE_CLASS = 105803;
    public const ERROR_UNKNOWN_PAGE_ID = 105804;

    private BasePage $activePage;
    private X4Application $application;
    private Request $request;

    /**
     * @var array<string,string>
     */
    private array $pages = array();

    /**
     * @var array<string,BasePage>
     */
    private array $pageInstances = array();

    public function __construct(X4Application $application)
    {
        $this->application = $application;
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

    public function createDataGrid() : DataGrid
    {
        return new DataGrid();
    }

    public function registerPage(string $urlName, string $className) : void
    {
        $this->pages[$urlName] = $className;
    }

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

    public function display() : void
    {
        $content = $this->activePage->render();

        ?><!doctype html>
        <html lang="en">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <title><?php echo $this->getTitle() ?></title>

                <!-- Bootstrap core CSS -->
                <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.css">
                <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/fontawesome.css">
                <link rel="stylesheet" href="vendor/fortawesome/font-awesome/css/solid.css">
                <link rel="stylesheet" href="vendor/mistralys/x4-core/css/ui.css">
            </head>
            <body>
                <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
                    <a class="navbar-brand col-md-3 col-lg-2 mr-0 px-3" href="?"><?php echo $this->getTitle() ?></a>
                    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </nav>

                <div class="container-fluid">
                    <div class="row">

                        <?php
                            $items = $this->activePage->getNavItems();

                            if(!empty($items))
                            {
                                $this->displayNavigation($items);
                            }
                        ?>

                        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                                <h1 class="h2"><?php echo $this->activePage->getTitle() ?></h1>
                            </div>

                            <?php echo $content; ?>
                        </main>

                        <footer>
                            <?php echo $this->getTitle() ?> v<?php echo $this->application->getVersion() ?><br>
                        </footer>
                    </div>
                </div>

                <script src="vendor/components/jquery/jquery.slim.js"></script>
                <script src="vendor/twbs/bootstrap/dist/js/bootstrap.js"></script>
            </body>
        </html><?php
    }

    /**
     * @param NavItem[] $items
     * @return void
     */
    private function displayNavigation(array $items) : void
    {
        ?>
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="sidebar-sticky pt-3">
                <ul class="nav flex-column">
                    <?php
                        foreach($items as $item)
                        {
                            ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="<?php echo $item->getUrl() ?>">
                                    <?php echo $item->getLabel() ?>
                                </a>
                            </li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
        </nav>

        <?php
    }
}
