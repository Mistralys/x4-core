<?php

declare(strict_types=1);

namespace Mistralys\X4\XML\Finders;

use DOMElement;
use DOMNodeList;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

class TagSelectorFinder extends BaseTagFinder
{
    private string $selector;

    public function __construct(DOMExtended $dom, string $selector, ?ElementExtended $subject=null)
    {
        parent::__construct($dom, $subject);

        $this->selector = $selector;
    }

    public function getAll(): array
    {
        $elements = array();
        foreach($this->executeXpath() as $node) {
            if($node instanceof DOMElement) {
                $elements[] = $node;
            }
        }

        return $elements;
    }

    private function executeXpath() : DOMNodeList
    {
        return $this->domEX
            ->getXPath()
            ->query(
                $this->domEX->getSelectorConverter()->toXPath($this->selector),
                $this->getDOMSubject()
            );
    }
}
