<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Page;

use Mistralys\X4\UI\BaseSubPage;
use Mistralys\X4\UI\UserInterface;
use Mistralys\X4\X4Exception;

abstract class BasePageWithNav extends BasePage
{
    public const ERROR_INVALID_SUBPAGE_ID = 89401;

    /**
     * @var BaseSubPage[]
     */
    protected array $subPages = array();

    public function __construct(UserInterface $ui)
    {
        parent::__construct($ui);

        $this->initSubPages();
    }

    abstract public function getDefaultSubPageID() : string;

    public function getNavItems() : array
    {
        $result = array();

        foreach($this->subPages as $subPage)
        {
            $result[] = new NavItem(
                $subPage->getTitle(),
                $subPage->getURL()
            );
        }

        return $result;
    }

    abstract protected function initSubPages() : void;

    /**
     * @return BaseSubPage
     * @throws X4Exception
     */
    public function getSubPage() : BaseSubPage
    {
        $view = $this->request->getParam('view');

        foreach ($this->subPages as $subPage)
        {
            if($subPage->getURLName() === $view) {
                return $subPage;
            }
        }

        return $this->getSubPageByID($this->getDefaultSubPageID());
    }

    /**
     * @param string $id
     * @return BaseSubPage
     * @throws X4Exception
     */
    private function getSubPageByID(string $id) : BaseSubPage
    {
        foreach ($this->subPages as $subPage)
        {
            if($subPage->getURLName() === $id) {
                return $subPage;
            }
        }

        throw new X4Exception(
            'Invalid page',
            sprintf(
                'The page [%s] does not exist.',
                $id
            ),
            self::ERROR_INVALID_SUBPAGE_ID
        );
    }

    protected function _render(): void
    {
        $this->getSubPage()->renderContent();
    }
}
