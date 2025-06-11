<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\Collections\BaseStringPrimaryCollection;
use function AppLocalize\t;

/**
 * Collection of all ware groups available in the game.
 * These can be used to identify the type of wares.
 *
 * @package X4 Database
 * @subpackage Wares
 *
 * @method WareGroup getByID(string $id)
 * @method WareGroup[] getAll()
 * @method WareGroup getDefault()
 */
class WareGroups extends BaseStringPrimaryCollection
{
    public const GROUP_AGRICULTURAL = 'agricultural';
    public const GROUP_COUNTERMEASURES = 'countermeasures';
    public const GROUP_CRAFTING = 'crafting';
    public const GROUP_DRONES = 'drones';
    public const GROUP_ENERGY = 'energy';
    public const GROUP_ENGINES = 'engines';
    public const GROUP_EQUIPMENT = 'equipment';
    public const GROUP_EQUIPMENT_MODS = 'equipmentmods';
    public const GROUP_EQUIPMENT_MOD_PARTS = 'equipmentmodparts';
    public const GROUP_FOOD = 'food';
    public const GROUP_GASES = 'gases';
    public const GROUP_HIGH_TECH = 'hightech';
    public const GROUP_ICE = 'ice';
    public const GROUP_INVENTORY_ITEMS = 'inventoryitems';
    public const GROUP_MINERALS = 'minerals';
    public const GROUP_MISSILES = 'missiles';
    public const GROUP_MISSION_ITEMS = 'missionitems';
    public const GROUP_MODULES = 'modules';
    public const GROUP_OTHER = 'other';
    public const GROUP_PAINT_MODS = 'paintmods';
    public const GROUP_PHARMACEUTICAL = 'pharmaceutical';
    public const GROUP_REFINED = 'refined';
    public const GROUP_RESEARCH = 'research';
    public const GROUP_SEMINARS = 'seminars';
    public const GROUP_SHIELDS = 'shields';
    public const GROUP_SHIPS = 'ships';
    public const GROUP_SHIPTECH = 'shiptech';
    public const GROUP_SOFTWARE = 'software';
    public const GROUP_THRUSTERS = 'thrusters';
    public const GROUP_TURRETS = 'turrets';
    public const GROUP_WATER = 'water';
    public const GROUP_WEAPONS = 'weapons';

    private function getGroupsList() : array
    {
        return array(
            self::GROUP_AGRICULTURAL => t('Agricultural'),
            self::GROUP_COUNTERMEASURES => t('Countermeasures'),
            self::GROUP_CRAFTING => t('Crafting'),
            self::GROUP_DRONES => t('Drones'),
            self::GROUP_ENERGY => t('Energy'),
            self::GROUP_ENGINES => t('Engines'),
            self::GROUP_EQUIPMENT => t('Equipment'),
            self::GROUP_EQUIPMENT_MODS => t('Equipment Mods'),
            self::GROUP_EQUIPMENT_MOD_PARTS => t('Equipment Mod Parts'),
            self::GROUP_FOOD => t('Food'),
            self::GROUP_GASES => t('Gases'),
            self::GROUP_HIGH_TECH => t('High Tech'),
            self::GROUP_ICE => t('Ice'),
            self::GROUP_INVENTORY_ITEMS => t('Inventory Items'),
            self::GROUP_MINERALS => t('Minerals'),
            self::GROUP_MISSILES => t('Missiles'),
            self::GROUP_MISSION_ITEMS => t('Mission Items'),
            self::GROUP_MODULES => t('Modules'),
            self::GROUP_OTHER => t('Other'),
            self::GROUP_PAINT_MODS => t('Paint Mods'),
            self::GROUP_PHARMACEUTICAL => t('Pharmaceutical'),
            self::GROUP_REFINED => t('Refined'),
            self::GROUP_RESEARCH => t('Research'),
            self::GROUP_SEMINARS => t('Seminars'),
            self::GROUP_SHIELDS => t('Shields'),
            self::GROUP_SHIPS => t('Ships'),
            self::GROUP_SHIPTECH => t('Ship Technology'),
            self::GROUP_SOFTWARE => t('Software'),
            self::GROUP_THRUSTERS => t('Thrusters'),
            self::GROUP_TURRETS => t('Turrets'),
            self::GROUP_WATER => t('Water'),
            self::GROUP_WEAPONS => t('Weapons'),
        );
    }
    private static ?WareGroups $instance = null;

    public static function getInstance(): WareGroups
    {
        if (self::$instance === null) {
            self::$instance = new WareGroups();
        }

        return self::$instance;
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach($this->getGroupsList() as $id => $label) {
            $this->registerItem(new WareGroup($id, $label));
        }
    }
}
