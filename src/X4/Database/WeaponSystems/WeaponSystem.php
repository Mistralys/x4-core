<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\WeaponSystems\WeaponSystem
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\WeaponSystems;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;

/**
 * Represents a single weapon system type with metadata.
 * 
 * Weapon systems classify weapons by their intended role:
 * - Turret types (shortrange, midrange, longrange)
 * - Weapon types (standard, mining)
 * - Missile types (dumbfire, guided)
 * - Torpedoes
 * 
 * @package X4Core
 * @subpackage Database
 */
class WeaponSystem implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_LABEL = 'label';
    public const KEY_DESCRIPTION = 'description';

    private string $id;
    private string $label;
    private string $description;
    
    /**
     * @param WeaponSystems $collection
     * @param array{id: string, label: string, description: string} $data
     */
    public function __construct(WeaponSystems $collection, array $data)
    {
        $this->id = $data['id'];
        $this->label = $data[self::KEY_LABEL];
        $this->description = $data[self::KEY_DESCRIPTION];
    }
    
    /**
     * Get the weapon system ID (e.g., 'turret_shortrange').
     * 
     * @return string
     */
    public function getID(): string
    {
        return $this->id;
    }
    
    /**
     * Get the variant ID wrapper.
     * 
     * @return VariantID
     */
    public function getVariantID(): VariantID
    {
        return VariantID::fromID($this->id);
    }
    
    /**
     * Get the human-readable label (e.g., 'Short-Range Turret').
     * 
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
    
    /**
     * Get the description of this weapon system type.
     * 
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * Check if this is a turret weapon system.
     * 
     * @return bool
     */
    public function isTurret(): bool
    {
        return str_starts_with($this->id, 'turret_');
    }
    
    /**
     * Check if this is a missile weapon system.
     * 
     * @return bool
     */
    public function isMissile(): bool
    {
        return str_starts_with($this->id, 'missile_') 
            || $this->id === 'torpedo' 
            || $this->id === 'bomb';
    }
    
    /**
     * Check if this is a standard ship weapon system.
     * 
     * @return bool
     */
    public function isStandardWeapon(): bool
    {
        return str_starts_with($this->id, 'weapon_');
    }
}
