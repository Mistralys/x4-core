<?php

declare(strict_types=1);

namespace Mistralys\X4\XML;

use AppUtils\FileHelper\FileInfo;
use DOMDocument;
use DOMXPath;
use Mistralys\X4\XML\Finders\TagNameFinder;
use Mistralys\X4\XML\Finders\TagSelectorFinder;
use SplFileInfo;
use Symfony\Component\CssSelector\CssSelectorConverter;

class DOMExtended
{
    private DOMDocument $dom;

    public function __construct(DOMDocument $document)
    {
        $this->dom = $document;
    }

    public function getDOM() : DOMDocument
    {
        return $this->dom;
    }

    public static function createFromFile(string|FileInfo|SplFileInfo $file) : self
    {
        return self::createFromString(FileInfo::factory($file)->getContents());
    }

    public static function createFromString(string $xml) : self
    {
        $dom = new DOMDocument();
        $dom->loadXML($xml);
        return new self($dom);
    }

    public function byTagName(string $tagName) : TagNameFinder
    {
        return new TagNameFinder($this, $tagName);
    }

    public function bySelector(string $selector) : TagSelectorFinder
    {
        return new TagSelectorFinder($this, $selector);
    }

    private ?DOMXPath $xpath = null;

    public function getXPath() : DOMXPath
    {
        if($this->xpath === null) {
            $this->xpath = new DOMXPath($this->dom);
        }

        return $this->xpath;
    }

    private ?CssSelectorConverter $selectorConverter = null;

    public function getSelectorConverter() : CssSelectorConverter
    {
        if(!isset($this->selectorConverter)) {
            $this->selectorConverter = new CssSelectorConverter();
        }

        return $this->selectorConverter;
    }

    public function getXML() : string
    {
        return $this->dom->saveXML();
    }
}