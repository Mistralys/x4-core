<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

class ShipSlotAggregator
{
    private array $connections = [];
    private array $dockSizeCounts = [];
    private int $countermeasuresCount = 0;

    public function addStructureConnection(array $connectionData) : void
    {
        $this->connections[] = $connectionData;
        
        $tags = $connectionData['tags'] ?? [];
        $type = $this->resolveType($tags);
        
        // Track docks with sizes separately
        if ($type === 'dockingbay') {
            $dockSize = $connectionData['dockSize'] ?? null;
            if ($dockSize !== null && $dockSize !== 'xs') {
                // Filter out xs docks (spacesuit docks)
                if (!isset($this->dockSizeCounts[$dockSize])) {
                    $this->dockSizeCounts[$dockSize] = 0;
                }
                $this->dockSizeCounts[$dockSize]++;
            }
        }
        
        // Track countermeasures as simple count
        if ($type === 'countermeasures') {
            $this->countermeasuresCount++;
        }
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
                 // Skip - we use dockSizeCounts
            }
            elseif($type === 'countermeasures') {
                 // Skip - tracked separately as simple count
            }
        }
        
        // Use the new dock size format
        $slots['docks'] = $this->dockSizeCounts;
        
        // Set countermeasures as simple count
        $slots['countermeasures'] = $this->countermeasuresCount;
        
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
            // Special handling for docks - they're now stored as object/associative array
            if ($type === 'docks') {
                if (!empty($groups)) {
                    // Sort dock sizes alphabetically
                    ksort($groups);
                    $final[$type] = $groups;
                }
                // Skip empty docks entirely
                continue;
            }
            
            // Special handling for countermeasures - output as simple number
            if ($type === 'countermeasures') {
                if ($groups > 0) {
                    $final[$type] = $groups;
                }
                continue;
            }
            
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
