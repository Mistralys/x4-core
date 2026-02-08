<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

class ShipSlotAggregator
{
    private array $connections = [];

    public function addStructureConnection(array $connectionData) : void
    {
        $this->connections[] = $connectionData;
    }

    public function getAggregatedData() : array
    {
        $slots = [
            'engines' => [],
            'shields' => [],
            'turrets' => [],
            'weapons' => [],
            'docks' => [],
            'countermeasures' => []
        ];
        
        foreach($this->connections as $conn) {
            $tags = $conn['tags'] ?? [];
            $size = $this->resolveSize($tags);
            $type = $this->resolveType($tags);
            
            if(!$type) continue;
            
            // Engines and Main Weapons usually handled as simple counts per size/group
            if($type === 'engine') {
                $this->addCount($slots['engines'], $size, $tags);
            }
            elseif($type === 'weapon') {
                 // Check if it is a turret or main weapon
                 if(in_array('turret', $tags)) {
                     // Handled in turret logic? No, type resolution separates them.
                 } else {
                     $this->addCount($slots['weapons'], $size, $tags);
                 }
            }
            elseif($type === 'shield') {
                $this->addCount($slots['shields'], $size, $tags);
            }
            elseif($type === 'turret') {
                $this->addCount($slots['turrets'], $size, $tags);
            }
            elseif($type === 'dockingbay') {
                 $this->addCount($slots['docks'], $size, $tags);
            }
            elseif($type === 'countermeasures') {
                 $this->addCount($slots['countermeasures'], $size, $tags);
            }
        }
        
        return $this->cleanOutput($slots);
    }
    
    private function resolveSize(array $tags) : string
    {
        if(in_array('small', $tags) || in_array('size_s', $tags)) return 's';
        if(in_array('medium', $tags) || in_array('size_m', $tags)) return 'm';
        if(in_array('large', $tags) || in_array('size_l', $tags)) return 'l';
        if(in_array('extralarge', $tags) || in_array('size_xl', $tags)) return 'xl';
        return '';
    }
    
    private function resolveType(array $tags) : ?string
    {
        if(in_array('engine', $tags)) return 'engine';
        if(in_array('dockingbay', $tags) || in_array('dock_xs', $tags)) return 'dockingbay';
        if(in_array('weapon', $tags) && !in_array('turret', $tags)) return 'weapon';
        if(in_array('turret', $tags)) return 'turret';
        if(in_array('shield', $tags)) return 'shield';
        if(in_array('countermeasures', $tags)) return 'countermeasures';
        return null;
    }

    private function addCount(array &$collection, string $size, array $tags) : void
    {
        $tags = array_filter($tags); // Remove empty entries
        sort($tags);
        
        // Generate a signature for aggregation
        $sig = md5($size . '|' . implode(',', $tags));
        
        if(!isset($collection[$sig])) {
            $collection[$sig] = [
                'size' => $size,
                'count' => 0,
                'tags' => array_values($tags)
            ];
        }
        
        $collection[$sig]['count']++;
    }
    
    private function cleanOutput(array $slots) : array
    {
        $final = [];
        foreach($slots as $type => $groups) {
            $values = array_values($groups);
            
            // Clean up: if empty, omit (or keep empty array? Plan implied partial objects)
            if(empty($values)) continue;
            
            // If explicit single item structure was requested, we might merge,
            // but the plan shows Arrays for some (shields) and Objects for others (engines).
            // "If a type has only 1 group (e.g. Engines), output as an Object."
            
            if(count($values) === 1) {
                $final[$type] = $values[0];
            } else {
                $final[$type] = $values;
            }
        }
        return $final;
    }
}
