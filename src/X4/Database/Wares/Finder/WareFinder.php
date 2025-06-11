<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares\Finder;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroup;

/**
 * Specialized filtering utility to find wares based on
 * various criteria. Criteria can be combined to refine
 * your search.
 *
 * For example, you can search for all ships for a specific
 * DLC by combining {@see self::selectGroup()} and
 * {@see self::selectDLC()}.
 *
 * @package X4 Database
 * @subpackage Wares
 */
class WareFinder
{
    private WareDefs $defs;

    /**
     * @var string[]
     */
    private array $tags = array();

    /**
     * @var string[]
     */
    private array $dlcs = array();

    public function __construct(WareDefs $defs)
    {
        $this->defs = $defs;
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
     * Returns a finder for Wares that belong to a specific DLC.
     * @return WareDLCFinder
     */
    public function selectDLC(): WareDLCFinder
    {
        return new WareDLCFinder($this);
    }

    public function selectDLCID(string $dlcID): self
    {
        if (!in_array($dlcID, $this->dlcs, true)) {
            $this->dlcs[] = $dlcID;
        }

        return $this;
    }

    public function getAll(): array
    {
        $results = array();

        foreach ($this->defs->getAll() as $ware) {
            if ($this->isMatch($ware)) {
                $results[] = $ware;
            }
        }

        return $results;
    }

    private function isMatch(WareDef $ware): bool
    {
        foreach ($this->tags as $tag) {
            if (!in_array($tag, $ware->getTags(), true)) {
                return false;
            }
        }

        foreach ($this->dlcs as $dlcID) {
            if ($ware->getDataSourceID() !== $dlcID) {
                return false;
            }
        }

        foreach ($this->groups as $group) {
            if ($ware->getGroupID() !== $group) {
                return false;
            }
        }

        return true;
    }
}
