<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\Finder\BaseFinder;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionInterface;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionTrait;
use Mistralys\X4\Database\Core\ItemCollectionInterface;

/**
 * Specialized filtering utility to find wares based on
 * various criteria. Criteria can be combined to refine
 * your search.
 *
 * For example, you can search for all ships for a specific
 * DLC by combining {@see self::selectGroup()} and
 * {@see self::selectDataSource()}.
 *
 * @package X4 Database
 * @subpackage Wares
 */
class WareFinder extends BaseFinder implements DataSourceSelectionInterface
{
    use DataSourceSelectionTrait;

    /**
     * @var string[]
     */
    private array $tags = array();

    public function getCollection(): ItemCollectionInterface
    {
        return WareDefs::getInstance();
    }

    /**
     * Returns all wares that have the given tag.
     * @param string $tagName
     * @return $this
     */
    public function selectTag(string $tagName): self
    {
        if (!in_array($tagName, $this->tags, true)) {
            $this->tags[] = $tagName;
        }

        return $this;
    }

    /**
     * @var string[]
     */
    private array $groups = array();

    public function selectGroup(string|WareGroup $group): self
    {
        if ($group instanceof WareGroup) {
            $group = $group->getID();
        }

        if (!in_array($group, $this->groups, true)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * @param WareDef $item
     * @return bool
     */
    protected function isMatch(CollectionItemInterface $item): bool
    {
        foreach ($this->tags as $tag) {
            if (!in_array($tag, $item->getTags(), true)) {
                return false;
            }
        }

        if(!$this->isDataSourceMatch($item->getDataSourceID())) {
            return false;
        }

        foreach ($this->groups as $group) {
            if ($item->getGroupID() !== $group) {
                return false;
            }
        }

        return true;
    }
}
