<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Core\Finder;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Ships\ShipDef;

abstract class BaseFinder implements FinderInterface
{
    /**
     * Returns all matching ships.
     * @return ShipDef[]
     */
    public function getAll() : array
    {
        $results = array();

        foreach($this->getCollection()->getAll() as $item) {
            if($this->isMatch($item)) {
                $results[] = $item;
            }
        }

        return $results;
    }

    abstract protected function isMatch(CollectionItemInterface $item) : bool;

    /**
     * @var string[]
     */
    private array $labelSearches = array();

    public function selectLabelSearch(string $searchTerm) : self
    {
        $searchTerm = trim($searchTerm);
        if(strlen($searchTerm) < 2) {
            return $this;
        }

        if(!in_array($searchTerm, $this->labelSearches)) {
            $this->labelSearches[] = $searchTerm;
        }

        return $this;
    }

    protected function isLabelMatch(string $label) : bool
    {
        if(empty($this->labelSearches)) {
            return true;
        }

        $found = false;
        foreach($this->labelSearches as $searchTerm) {
            if(stripos($label, $searchTerm) !== false) {
                $found = true;
                break;
            }
        }

        return $found;
    }
}
