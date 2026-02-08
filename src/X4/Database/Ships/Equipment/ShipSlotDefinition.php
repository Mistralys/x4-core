<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\Equipment;

use Mistralys\X4\Database\Wares\WareDef;

class ShipSlotDefinition
{
    private int $count;
    private string $size;
    /**
     * @var string[]
     */
    private array $tags;

    /**
     * @param int $count
     * @param string $size
     * @param string[] $tags
     */
    public function __construct(int $count, string $size, array $tags)
    {
        $this->count = $count;
        $this->size = $size;
        $this->tags = $tags;
    }

    public static function fromArray(array $data) : ShipSlotDefinition
    {
        return new self(
            (int)($data['count'] ?? 0),
            (string)($data['size'] ?? ''),
            (array)($data['tags'] ?? [])
        );
    }

    public function getCount() : int
    {
        return $this->count;
    }

    public function getSize() : string
    {
        return $this->size;
    }

    /**
     * @return string[]
     */
    public function getTags() : array
    {
        return $this->tags;
    }
    
    public function hasTag(string $tag) : bool
    {
        return in_array($tag, $this->tags, true);
    }

    public function canEquip(WareDef $ware) : bool
    {
        // 1. Size Check
        // If the slot has a size, the ware must match it.
        // Some slots might be sizeless? (e.g. some docks/countermeasures are implicit size)
        if ($this->size !== '' && $ware->getSize() !== $this->size) {
            return false;
        }

        $wareTags = $ware->getCompatibilityTags();

        // 2. Primary Type Check
        // Identify the primary type of the slot
        $types = ['engine', 'shield', 'turret', 'weapon', 'dockingbay', 'countermeasures'];
        $slotType = null;
        foreach ($types as $type) {
            if ($this->hasTag($type)) {
                $slotType = $type;
                break;
            }
        }

        // If we extracted a type, ensure the ware has it
        if ($slotType && !in_array($slotType, $wareTags, true)) {
            return false;
        }
        
        // 3. Special Purpose Check (Simple implementation)
        // If slot has 'mining', ware should probably have 'mining'
        // If slot has 'combat', ware should probably have 'combat'
        // This is heuristic and might need refinement.
        
        // For now, let's strictly check that if 'turret' is the type, 
        // we don't accidentally allow 'weapon' (main guns).
        // The type check above handles that (since weapon != turret).

        return true;
    }
}
