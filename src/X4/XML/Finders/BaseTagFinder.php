<?php

declare(strict_types=1);

namespace Mistralys\X4\XML\Finders;

use DOMDocument;
use DOMNode;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;
use Mistralys\X4\XML\TagFinderInterface;
use Mistralys\X4\XML\XMLException;

abstract class BaseTagFinder implements TagFinderInterface
{
    protected DOMExtended $domEX;
    protected DOMDocument $dom;
    protected ?ElementExtended $subject;

    public function __construct(DOMExtended $dom, ?ElementExtended $subject=null)
    {
        $this->domEX = $dom;
        $this->dom = $dom->getDOM();
        $this->subject = $subject;
    }

    public function getFirst() : ?ElementExtended
    {
        $elements = $this->getAll();

        if(!empty($elements)) {
            return $elements[0];
        }

        return null;
    }

    public function requireFirst(string $errorDetails=''): ElementExtended
    {
        $first = $this->getFirst();
        if ($first !== null) {
            return $first;
        }

        throw new XMLException(
            'No first element found.',
            $errorDetails,
            XMLException::ERROR_NO_FIRST_ELEMENT_FOUND
        );
    }

    public function getDOMSubject() : DOMNode|DOMDocument
    {
        if(isset($this->subject)) {
            return $this->subject->getDOMElement();
        }

        return $this->dom;
    }
}
