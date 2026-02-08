<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\SlotTypes;

use AppUtils\Collections\BaseStringPrimaryCollection;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\ItemCollectionInterface;

/**
 * @extends BaseStringPrimaryCollection<SlotType>
 */
class SlotTypes extends BaseStringPrimaryCollection implements ItemCollectionInterface
{
    /**
     * @var SlotTypes|null
     */
    private static ?SlotTypes $instance = null;


    public static function getInstance() : SlotTypes
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    public function getCollectionName(): string
    {
        return 'Slot Types';
    }

    public function getDefaultID(): string
    {
        return KnownSlotTypes::WEAPON;
    }

    protected function registerItems(): void
    {
        $data = $this->loadInitialData();
        foreach($data as $itemData) {
            $this->registerItem($this->createItem($itemData));
        }
    }

    public function getCollectionDescription(): string
    {
        return 'Standard equipment slot types found in ship components.';
    }

    protected function createItem(array $data): CollectionItemInterface
    {
        return new SlotType($this, $data);
    }
    
    protected function loadInitialData(): array
    {
        return [
            [
                'id' => KnownSlotTypes::WEAPON,
                SlotType::KEY_LABEL => 'Weapon',
                SlotType::KEY_TAGS => 'weapon'
            ],
            [
                'id' => KnownSlotTypes::SHIELD,
                SlotType::KEY_LABEL => 'Shield',
                SlotType::KEY_TAGS => 'shield'
            ],
            [
                'id' => KnownSlotTypes::TURRET,
                SlotType::KEY_LABEL => 'Turret',
                SlotType::KEY_TAGS => 'turret'
            ],
            [
                'id' => KnownSlotTypes::ENGINE,
                SlotType::KEY_LABEL => 'Engine',
                SlotType::KEY_TAGS => 'engine' // Often "part", but we need logic to find it
            ],
            [
                'id' => KnownSlotTypes::DOCKING_BAY,
                SlotType::KEY_LABEL => 'Docking Bay',
                SlotType::KEY_TAGS => 'dockingbay'
            ],
            [
                'id' => KnownSlotTypes::COUNTERMEASURES,
                SlotType::KEY_LABEL => 'Countermeasures',
                SlotType::KEY_TAGS => 'countermeasures'
            ]
        ];
    }
}
