<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\Finder\BaseFinder;
use Mistralys\X4\Database\Core\ItemCollectionInterface;

class ModuleFinder extends BaseFinder
{
    /**
     * @var string[]
     */
    private array $categories = array();

    public function selectCategory(string|ModuleCategory $category) : self
    {
        if(!$category instanceof ModuleCategory) {
            $category = ModuleCategories::getInstance()->getByID($category);
        }

        $categoryID = $category->getID();

        if(!in_array($categoryID, $this->categories, true)) {
            $this->categories[] = $categoryID;
        }

        return $this;
    }

    /**
     * @param ModuleDef $item
     * @return bool
     */
    protected function isMatch(CollectionItemInterface $item): bool
    {
        if(!empty($this->categories) && !in_array($item->getCategoryID(), $this->categories, true)) {
            return false;
        }

        if(!$this->isLabelMatch($item->getLabel())) {
            return false;
        }

        return true;
    }

    public function getCollection(): ItemCollectionInterface
    {
        return ModuleDefs::getInstance();
    }
}
