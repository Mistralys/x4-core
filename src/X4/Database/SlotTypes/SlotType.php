<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\SlotTypes;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;

class SlotType implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_LABEL = 'label';
    public const KEY_TAGS = 'tags';

    private string $id;
    private string $label;
    private string $primaryTag;
    
    public function __construct(SlotTypes $collection, array $data)
    {
        $this->id = $data['id'];
        $this->label = $data[self::KEY_LABEL];
        $this->primaryTag = $data[self::KEY_TAGS];
    }
    
    public function getID() : string
    {
        return $this->id;
    }
    
    public function getVariantID() : \Mistralys\X4\Database\Core\VariantID
    {
        return \Mistralys\X4\Database\Core\VariantID::fromID($this->id);
    }
    
    public function getLabel() : string
    {
        return $this->label;
    }
    
    public function getPrimaryTag() : string
    {
        return $this->primaryTag;
    }
}
