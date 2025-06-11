<?php

declare(strict_types=1);

namespace Mistralys\X4\XML;

use DOMElement;
use Mistralys\X4\XML\Finders\TagSelectorFinder;

class ElementExtended
{
    private DOMExtended $dom;
    private DOMElement $element;

    public function __construct(DOMExtended $dom, DOMElement $element)
    {
        $this->dom = $dom;
        $this->element = $element;
    }

    public function hasAttribute(string $name) : bool
    {
        return $this->element->hasAttribute($name);
    }

    public function getAttribute(string $name) : ?string
    {
        return $this->element->getAttribute($name);
    }

    public function hasChildren() : bool
    {
        foreach($this->element->childNodes as $node) {
            if($node instanceof DOMElement) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return ElementExtended[]
     */
    public function getChildren() : array
    {
        $children = [];
        foreach($this->element->childNodes as $node) {
            if($node instanceof DOMElement) {
                $children[] = new self($this->dom, $node);
            }
        }
        return $children;
    }

    public function getXML() : string
    {
        return $this->element->ownerDocument->saveXML($this->element);
    }

    public function findChildren() : TagSelection
    {
        return new TagSelection($this->dom, $this);
    }

    public function getDOMElement() : DOMElement
    {
        return $this->element;
    }
}
