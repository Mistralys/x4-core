<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\WeaponSystems\WeaponSystems
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\WeaponSystems;

use AppUtils\Collections\BaseStringPrimaryCollection;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\ItemCollectionInterface;

/**
 * Collection of all weapon system types.
 * 
 * Provides centralized access to weapon system metadata including
 * human-readable labels and descriptions. Weapon systems classify
 * weapons by their intended combat role (turrets, missiles, etc.).
 * 
 * Usage:
 * ```php
 * $systems = WeaponSystems::getInstance();
 * $shortRange = $systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
 * echo $shortRange->getLabel(); // "Short-Range Turret"
 * ```
 * 
 * @package X4Core
 * @subpackage Database
 * @extends BaseStringPrimaryCollection<WeaponSystem>
 */
class WeaponSystems extends BaseStringPrimaryCollection implements ItemCollectionInterface
{
    private static ?WeaponSystems $instance = null;

    /**
     * Get the singleton instance of the weapon systems collection.
     * 
     * @return WeaponSystems
     */
    public static function getInstance(): WeaponSystems
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    
    /**
     * Get the collection name for display purposes.
     * 
     * @return string
     */
    public function getCollectionName(): string
    {
        return 'Weapon Systems';
    }

    /**
     * Get the default weapon system ID.
     * 
     * @return string
     */
    public function getDefaultID(): string
    {
        return KnownWeaponSystems::WEAPON_STANDARD;
    }

    /**
     * Register all weapon system items.
     * 
     * @return void
     */
    protected function registerItems(): void
    {
        $data = $this->loadInitialData();
        foreach ($data as $itemData) {
            $this->registerItem($this->createItem($itemData));
        }
    }

    /**
     * Get a description of this collection.
     * 
     * @return string
     */
    public function getCollectionDescription(): string
    {
        return 'Weapon system types used to classify weapons by combat role.';
    }

    /**
     * Create a weapon system item from data.
     * 
     * @param array{id: string, label: string, description: string} $data
     * @return CollectionItemInterface
     */
    protected function createItem(array $data): CollectionItemInterface
    {
        return new WeaponSystem($this, $data);
    }
    
    /**
     * Load hardcoded weapon system data.
     * 
     * @return array<int, array{id: string, label: string, description: string}>
     */
    protected function loadInitialData(): array
    {
        return [
            [
                'id' => KnownWeaponSystems::TURRET_SHORTRANGE,
                WeaponSystem::KEY_LABEL => 'Short-Range Turret',
                WeaponSystem::KEY_DESCRIPTION => 'Automated turrets optimized for close combat: beam weapons, gatling guns, and short-range plasma.'
            ],
            [
                'id' => KnownWeaponSystems::TURRET_MIDRANGE,
                WeaponSystem::KEY_LABEL => 'Mid-Range Turret',
                WeaponSystem::KEY_DESCRIPTION => 'Automated turrets for balanced engagement: pulse lasers, plasma cannons, and mass drivers.'
            ],
            [
                'id' => KnownWeaponSystems::TURRET_LONGRANGE,
                WeaponSystem::KEY_LABEL => 'Long-Range Turret',
                WeaponSystem::KEY_DESCRIPTION => 'Automated turrets for distance fighting: bolt repeaters, flak arrays, and ion weaponry.'
            ],
            [
                'id' => KnownWeaponSystems::WEAPON_STANDARD,
                WeaponSystem::KEY_LABEL => 'Standard Weapon',
                WeaponSystem::KEY_DESCRIPTION => 'Fixed ship-mounted weapons for direct fire combat across all range bands.'
            ],
            [
                'id' => KnownWeaponSystems::WEAPON_MINING,
                WeaponSystem::KEY_LABEL => 'Mining Laser',
                WeaponSystem::KEY_DESCRIPTION => 'Specialized lasers for extracting resources from asteroids and mineral deposits.'
            ],
            [
                'id' => KnownWeaponSystems::WEAPON_REPAIR,
                WeaponSystem::KEY_LABEL => 'Repair Laser',
                WeaponSystem::KEY_DESCRIPTION => 'Specialized tools for repairing ship hull damage and station components.'
            ],
            [
                'id' => KnownWeaponSystems::MISSILE_DUMBFIRE,
                WeaponSystem::KEY_LABEL => 'Dumbfire Missile',
                WeaponSystem::KEY_DESCRIPTION => 'Unguided missiles that travel in a straight line at high speed.'
            ],
            [
                'id' => KnownWeaponSystems::MISSILE_GUIDED,
                WeaponSystem::KEY_LABEL => 'Guided Missile',
                WeaponSystem::KEY_DESCRIPTION => 'Tracking missiles that pursue and lock onto targets with varying degrees of agility.'
            ],
            [
                'id' => KnownWeaponSystems::TORPEDO,
                WeaponSystem::KEY_LABEL => 'Torpedo',
                WeaponSystem::KEY_DESCRIPTION => 'Heavy capital ship weapons with devastating damage at the cost of slow speed and maneuverability.'
            ],
            [
                'id' => KnownWeaponSystems::BOMB,
                WeaponSystem::KEY_LABEL => 'Bomb',
                WeaponSystem::KEY_DESCRIPTION => 'Explosive devices deployed by spacesuit personnel for demolition purposes.'
            ]
        ];
    }
    
    /**
     * Get all turret weapon systems.
     * 
     * @return array<WeaponSystem>
     */
    public function getTurretSystems(): array
    {
        return array_filter($this->getAll(), fn(WeaponSystem $system) => $system->isTurret());
    }
    
    /**
     * Get all missile weapon systems.
     * 
     * @return array<WeaponSystem>
     */
    public function getMissileSystems(): array
    {
        return array_filter($this->getAll(), fn(WeaponSystem $system) => $system->isMissile());
    }
    
    /**
     * Get all standard weapon systems.
     * 
     * @return array<WeaponSystem>
     */
    public function getStandardWeaponSystems(): array
    {
        return array_filter($this->getAll(), fn(WeaponSystem $system) => $system->isStandardWeapon());
    }
    
    /**
     * Check if a weapon system ID is known.
     * 
     * @param string $systemID
     * @return bool
     */
    public function isKnownSystem(string $systemID): bool
    {
        return $this->idExists($systemID);
    }
    
    /**
     * Validate that a weapon system exists, throw exception if not.
     * 
     * This is used during weapon extraction to ensure game data changes
     * that introduce new weapon systems are caught early.
     * 
     * @param string $systemID
     * @throws \Mistralys\X4\Database\Weapons\WeaponException
     * @return void
     */
    public function requireKnownSystem(string $systemID): void
    {
        if (!$this->isKnownSystem($systemID)) {
            throw new \Mistralys\X4\Database\Weapons\WeaponException(
                sprintf(
                    'Unknown weapon system type: "%s". Known systems: %s',
                    $systemID,
                    implode(', ', $this->getIDs())
                ),
                null,
                \Mistralys\X4\Database\Weapons\WeaponException::ERROR_UNKNOWN_WEAPON_SYSTEM
            );
        }
    }
}
