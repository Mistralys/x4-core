<?php

declare(strict_types=1);

namespace Mistralys\X4\XML;

use Mistralys\X4\XML\Finders\TagNameFinder;
use Mistralys\X4\XML\Finders\TagSelectorFinder;

class TagSelection
{
    private DOMExtended $domEX;
    private ?ElementExtended $subject;

    public function __construct(DOMExtended $domEX, ?ElementExtended $subject = null)
    {
        $this->domEX = $domEX;
        $this->subject = $subject;
    }

    public function byTagName(string $tagName) : TagNameFinder
    {
        return new TagNameFinder($this->domEX, $tagName, $this->subject);
    }

    public function bySelector(string $selector) : TagSelectorFinder
    {
        return new TagSelectorFinder($this->domEX, $selector, $this->subject);
    }
}
