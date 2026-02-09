<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\WeaponSystems;

/**
 * Known weapon system type constants.
 * 
 * These represent the different weapon system categories
 * used to classify weapons in X4: Foundations.
 * 
 * @package X4Core
 * @subpackage Database
 */
class KnownWeaponSystems
{
    public const TURRET_SHORTRANGE = 'turret_shortrange';
    public const TURRET_MIDRANGE = 'turret_midrange';
    public const TURRET_LONGRANGE = 'turret_longrange';
    public const WEAPON_STANDARD = 'weapon_standard';
    public const WEAPON_MINING = 'weapon_mining';
    public const WEAPON_REPAIR = 'weapon_repair';
    public const MISSILE_DUMBFIRE = 'missile_dumbfire';
    public const MISSILE_GUIDED = 'missile_guided';
    public const TORPEDO = 'torpedo';
    public const BOMB = 'bomb';
}
