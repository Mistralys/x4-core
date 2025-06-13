<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Core;

use AppUtils\ConvertHelper;
use AppUtils\Interfaces\StringableInterface;

class VariantID implements StringableInterface
{
    private int $number;
    private ?string $qualifier;
    private ?string $mark;

    public function __construct(int $number=0, ?string $qualifier=null, ?string $mark=null)
    {
        $this->number = $number;
        $this->qualifier = $qualifier;
        $this->mark = $mark;
    }

    public static function fromID(string $variantID) : self
    {
        $parts = explode(':', $variantID);

        if(count($parts) !== 3) {
            return new VariantID();
        }

        $number = (int)$parts[0];
        $qualifier = null;
        $mark = null;

        if(!empty($parts[1])) {
            $qualifier = $parts[1];
        }

        if(!empty($parts[2])) {
            $mark = $parts[2];
        }

        return new self($number, $qualifier, $mark);
    }

    public function getNumber() : int
    {
        return $this->number;
    }

    public function getNumberString() : string
    {
        return sprintf('%02d', $this->number);
    }

    public function getQualifier() : ?string
    {
        return $this->qualifier;
    }

    public function getMark(): ?string
    {
        return $this->mark;
    }

    public function getID() : string
    {
        $result = array();

        if($this->number > 0) {
            $result[] = $this->getNumberString();
        } else {
            $result[] = '';
        }

        if(isset($this->qualifier)) {
            $result[] = $this->qualifier;
        } else {
            $result[] = '';
        }

        if(isset($this->mark)) {
            $result[] = $this->mark;
        } else {
            $result[] = '';
        }

        return implode(':', $result);
    }

    public const MARKS = array(
        'mk1',
        'mk2',
        'mk3'
    );

    public static function resolveWareVariantID($wareID): VariantID
    {
        $parts = explode('_', $wareID);

        $found = null;
        foreach (self::MARKS as $mark) {
            if (in_array($mark, $parts)) {
                $found = $mark;
            }
        }

        if ($found !== null) {
            $parts = ConvertHelper::arrayRemoveValues($parts, array($found));
        }

        foreach ($parts as $idx => $part) {
            if ($part === '01' || $part === '02' || $part === '03' || $part === '04' || $part === '05') {
                return new VariantID(
                    (int)$part,
                    implode('-', array_slice($parts, $idx + 1)),
                    $found
                );
            }
        }

        return new VariantID(0, '', $found);
    }

    private function listParts() : array
    {
        $parts = array();

        if($this->number > 0) {
            $parts[] = $this->getNumberString();
        }

        if(isset($this->mark)) {
            $parts[] = $this->mark;
        }

        if(isset($this->qualifier)) {
            $parts[] = $this->qualifier;
        }

        return $parts;
    }

    public function appendConstantSuffix(string $constant, ?string $exceptionSuffix=null) : string
    {
        $variantParts = $this->listParts();

        if(!empty($exceptionSuffix)) {
            $variantParts[] = $exceptionSuffix;
        }

        if(empty($variantParts)) {
            return $constant;
        }

        return $constant.'_'.strtoupper(implode('_', $variantParts));
    }

    public function appendMethodSuffix(string $method, ?string $exceptionSuffix=null) : string
    {
        $variantParts = $this->listParts();

        if(!empty($exceptionSuffix)) {
            $variantParts[] = $exceptionSuffix;
        }

        if(empty($variantParts)) {
            return $method;
        }

        return $method.'_'.ucfirst(implode('', array_map('ucfirst', $variantParts)));
    }

    public function __toString(): string
    {
        return $this->getID();
    }
}
