<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Core\Finder;

use Mistralys\X4\Database\Core\ItemCollectionInterface;

interface FinderInterface
{
    /**
     * Adds a term to search for in item labels. When multiple terms
     *  are added, items that match any of the terms will be returned.
     *
     * @param string $searchTerm
     * @return $this
     */
    public function selectLabelSearch(string $searchTerm) : self;

    public function getCollection() : ItemCollectionInterface;
}
