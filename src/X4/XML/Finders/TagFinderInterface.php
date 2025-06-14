<?php

declare(strict_types=1);

namespace Mistralys\X4\XML;

use DOMDocument;
use DOMNode;

interface TagFinderInterface
{
    public function getFirst() : ?ElementExtended;
    public function requireFirst() : ElementExtended;

    /**
     * Returns all elements found by this finder.
     * @return ElementExtended[]
     */
    public function getAll() : array;
    public function getDOMSubject() : DOMNode|DOMDocument;
}
