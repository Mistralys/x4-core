<?php

declare(strict_types=1);

namespace Mistralys\X4\XML\Finders;

use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

class TagNameFinder extends BaseTagFinder
{
    private string $tagName;

    public function __construct(DOMExtended $dom, string $tagName, ?ElementExtended $subject=null)
    {
        parent::__construct($dom, $subject);

        $this->tagName = $tagName;
    }

    /**
     * @return ElementExtended[]
     */
    public function getAll(): array
    {
        return array_map(
            fn($el) => new ElementExtended($this->domEX, $el),
            iterator_to_array($this->getDOMSubject()->getElementsByTagName($this->tagName))
        );
    }
}
