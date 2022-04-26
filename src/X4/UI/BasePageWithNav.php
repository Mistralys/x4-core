<?php

declare(strict_types=1);

namespace Mistralys\X4\UI;

abstract class BasePageWithNav extends BasePage
{
    public const ERROR_INVALID_SUBPAGE_ID = 89401;

    /**
     * @var BaseSubPage[]
     */
    protected array $subPages = array();

    public function __construct()
    {
        parent::__construct();

        $this->initSubPages();
    }

    abstract public function getDefaultSubPageID() : string;

    public function getNavItems() : array
    {
        $result = array();

        foreach($this->subPages as $subPage)
        {
            $result[] = array(
                'label' => $subPage->getTitle(),
                'url' => $subPage->getURL()
            );
        }

        return $result;
    }

    abstract protected function initSubPages() : void;

    /**
     * @return BaseSubPage
     * @throws X4Exception
     */
    protected function getSubPage() : BaseSubPage
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
