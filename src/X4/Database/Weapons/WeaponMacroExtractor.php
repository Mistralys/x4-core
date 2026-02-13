<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Weapons\WeaponMacroExtractor
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Weapons;

use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Extracts weapon performance data from a weapon macro XML file.
 * 
 * This extractor reads the weapon macro and also loads the associated
 * bullet macro to get complete weapon statistics.
 * 
 * Weapon macros contain:
 * - Heat properties (overheat, cooldown, coolrate, reenable)
 * - Rotation properties (speed, acceleration)
 * - Hull properties (max, hittable)
 * - Bullet class reference (links to bullet macro)
 *
 * @package X4Core
 * @subpackage Database
 */
class WeaponMacroExtractor
{
    private WareDef $ware;
    private DOMExtended $dom;

    public function __construct(WareDef $ware)
    {
        $this->ware = $ware;
        $this->dom = $ware->getMacro()->getDOM();
    }

    /**
     * Extracts the complete weapon data from weapon and bullet macros.
     * 
     * @return array<string,mixed>
     */
    public function extract(): array
    {
        $bulletClass = $this->resolveBulletClass();
        $bulletData = $this->extractBulletData($bulletClass);
        
        // Calculate bullet range: speed × lifetime
        $bulletSpeed = $bulletData['bulletSpeed'] ?? 0.0;
        $bulletLifetime = $bulletData['bulletLifetime'] ?? 0.0;
        $bulletRange = $bulletSpeed * $bulletLifetime;
        
        // Weapon category from file path (e.g., "standard", "energy", "mining")
        $weaponCategory = $this->resolveWeaponCategory();
        
        return array_merge(
            array(
                WeaponDef::KEY_WARE_ID => $this->ware->getID(),
                WeaponDef::KEY_MACRO_ID => $this->dom->byTagName('macro')->requireFirst()->getAttribute('name'),
                WeaponDef::KEY_BULLET_CLASS => $bulletClass,
                WeaponDef::KEY_LABEL => $this->ware->getLabel(),
                WeaponDef::KEY_SIZE => $this->ware->getSize(),
                WeaponDef::KEY_DATA_SOURCE_ID => $this->ware->getDataSourceID(),
                WeaponDef::KEY_MAKER_RACE => $this->resolveMakerRace(),
                WeaponDef::KEY_MK => $this->resolveMk(),
                WeaponDef::KEY_VARIANT_ID => (string)$this->ware->getVariantID(),
                WeaponDef::KEY_WEAPON_CATEGORY => $weaponCategory,
                
                // Heat properties (from weapon macro)
                WeaponDef::KEY_HEAT_OVERHEAT => $this->resolveFloat('heat', 'overheat'),
                WeaponDef::KEY_HEAT_COOLDELAY => $this->resolveFloat('heat', 'cooldelay'),
                WeaponDef::KEY_HEAT_COOLRATE => $this->resolveFloat('heat', 'coolrate'),
                WeaponDef::KEY_HEAT_REENABLE => $this->resolveFloat('heat', 'reenable'),
                
                // Rotation properties (from weapon macro)
                WeaponDef::KEY_ROTATION_SPEED => $this->resolveRotationSpeed(),
                WeaponDef::KEY_ROTATION_ACCELERATION => $this->resolveRotationAcceleration(),
                
                // Hull properties (from weapon macro)
                WeaponDef::KEY_HULL_MAX => $this->resolveFloat('hull', 'max'),
                WeaponDef::KEY_HULL_HITTABLE => $this->resolveHullHittable(),
                
                // Calculated range
                WeaponDef::KEY_BULLET_RANGE => $bulletRange,
            ),
            $bulletData // Merge bullet data (ammo, bullet props, damage, reload, etc.)
        );
    }

    /**
     * Resolve weapon category from macro file path.
     * 
     * Examples:
     *   assets/props/WeaponSystems/standard/macros/... -> standard
     *   assets/props/WeaponSystems/energy/macros/... -> energy
     *   assets/props/WeaponSystems/mining/macros/... -> mining
     */
    private function resolveWeaponCategory(): string
    {
        $filePath = $this->ware->getMacro()->getFullPath();
        
        // Known weapon categories in X4
        $categories = [
            'capital',
            'dumbfire',
            'energy',
            'guided',
            'heavy',
            'mines',
            'mining',
            'missile',
            'spacesuit',
            'standard',
            'torpedo'
        ];
        
        foreach ($categories as $category) {
            if (str_contains($filePath, '/WeaponSystems/' . $category . '/')) {
                return $category;
            }
        }
        
        return 'standard'; // Default fallback
    }

    /**
     * Resolve bullet class name from <bullet class="xxx"/> element.
     */
    private function resolveBulletClass(): string
    {
        $el = $this->dom->byTagName('bullet')->getFirst();
        if ($el !== null) {
            $bulletClass = $el->getAttribute('class');
            if (!empty($bulletClass)) {
                return $bulletClass;
            }
        }
        return '';
    }

    /**
     * Extract bullet data from the bullet macro.
     * 
     * @param string $bulletClass Bullet macro class name
     * @return array<string,mixed>
     */
    private function extractBulletData(string $bulletClass): array
    {
        if (empty($bulletClass)) {
            return $this->getEmptyBulletData();
        }
        
        try {
            $bulletMacro = MacroFileDefs::getInstance()->getByMacroName(
                $bulletClass,
                $this->ware->getDataSourceID()
            );
            
            $extractor = new BulletMacroExtractor($bulletMacro);
            return $extractor->extract();
        } catch (\Exception $e) {
            // Bullet macro not found - return empty data
            return $this->getEmptyBulletData();
        }
    }

    /**
     * Get empty bullet data structure with default values.
     * 
     * @return array<string,mixed>
     */
    private function getEmptyBulletData(): array
    {
        return array(
            'ammoValue' => 0.0,
            'ammoReload' => 0.0,
            'bulletSpeed' => 0.0,
            'bulletLifetime' => 0.0,
            'bulletAmount' => 1,
            'bulletBarrelamount' => 1,
            'bulletIcon' => '',
            'bulletTimediff' => 0.0,
            'bulletAngle' => 0.0,
            'bulletMaxhits' => 1,
            'bulletRicochet' => 0,
            'bulletAttach' => 0,
            'heatPerShot' => 0.0,
            'reloadRate' => 0.0,
            'damageValue' => 0.0,
            'repairValue' => 0.0,
            'weaponSystem' => 'weapon_standard',
        );
    }

    /**
     * Resolve rotation speed from <rotationspeed max="xxx"/> element.
     */
    private function resolveRotationSpeed(): float
    {
        $el = $this->dom->byTagName('rotationspeed')->getFirst();
        if ($el !== null) {
            $value = $el->getAttribute('max');
            if (!empty($value)) {
                return (float)$value;
            }
        }
        return 0.0;
    }

    /**
     * Resolve rotation acceleration from <rotationacceleration max="xxx"/> element.
     */
    private function resolveRotationAcceleration(): float
    {
        $el = $this->dom->byTagName('rotationacceleration')->getFirst();
        if ($el !== null) {
            $value = $el->getAttribute('max');
            if (!empty($value)) {
                return (float)$value;
            }
        }
        return 0.0;
    }

    /**
     * Resolve hull hittable flag from <hull hittable="xxx"/> element.
     */
    private function resolveHullHittable(): int
    {
        $el = $this->dom->byTagName('hull')->getFirst();
        if ($el !== null) {
            $hittable = $el->getAttribute('hittable');
            if ($hittable === '1') {
                return 1;
            }
        }
        return 0;
    }

    /**
     * @return string[]
     */
    private function resolveMakerRace(): array
    {
        $el = $this->tagIdentification();
        if ($el) {
            $raceString = $el->getAttribute('makerrace');
            if (!empty($raceString)) {
                $races = array_values(array_filter(explode(' ', $raceString)));
                if (!empty($races)) {
                    return $races;
                }
            }
        }
        return ['unknown'];
    }

    private function resolveMk(): int
    {
        $el = $this->tagIdentification();
        if ($el) {
            $mk = $el->getAttribute('mk');
            if (!empty($mk)) {
                return (int)$mk;
            }
        }
        return 1;
    }

    /**
     * Resolve a float attribute from an XML element.
     *
     * @param string $tagName Tag name to search for
     * @param string $attribute Attribute name to extract
     * @param float $default Default value if element or attribute not found
     * @return float
     */
    private function resolveFloat(string $tagName, string $attribute, float $default = 0.0): float
    {
        $el = $this->dom->byTagName($tagName)->getFirst();
        if ($el !== null) {
            $value = $el->getAttribute($attribute);
            if (!empty($value)) {
                return (float)$value;
            }
        }
        return $default;
    }

    private function tagIdentification(): ?ElementExtended
    {
        return $this->dom->byTagName('identification')->getFirst();
    }
}
