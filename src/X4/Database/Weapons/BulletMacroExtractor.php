<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Weapons\BulletMacroExtractor
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Weapons;

use Mistralys\X4\Database\MacroIndex\MacroFileDef;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Extracts bullet performance data from a bullet macro XML file.
 * 
 * Bullet macros contain:
 * - Ammunition properties (value, reload)
 * - Bullet physics (speed, lifetime, amount, angle)
 * - Heat per shot
 * - Reload rate
 * - Damage and repair values
 * - Weapon system type
 *
 * @package X4Core
 * @subpackage Database
 */
class BulletMacroExtractor
{
    private MacroFileDef $macro;
    private DOMExtended $dom;

    public function __construct(MacroFileDef $macro)
    {
        $this->macro = $macro;
        $this->dom = $macro->getDOM();
    }

    /**
     * Extracts bullet data from the macro XML.
     * 
     * @return array<string,mixed>
     */
    public function extract(): array
    {
        return array(
            // Ammunition properties
            'ammoValue' => $this->resolveFloat('ammunition', 'value'),
            'ammoReload' => $this->resolveFloat('ammunition', 'reload'),
            
            // Bullet properties
            'bulletSpeed' => $this->resolveFloat('bullet', 'speed'),
            'bulletLifetime' => $this->resolveFloat('bullet', 'lifetime'),
            'bulletAmount' => $this->resolveInt('bullet', 'amount', 1),
            'bulletBarrelamount' => $this->resolveInt('bullet', 'barrelamount', 1),
            'bulletIcon' => $this->resolveString('bullet', 'icon'),
            'bulletTimediff' => $this->resolveFloat('bullet', 'timediff'),
            'bulletAngle' => $this->resolveFloat('bullet', 'angle'),
            'bulletMaxhits' => $this->resolveInt('bullet', 'maxhits', 1),
            'bulletRicochet' => $this->resolveInt('bullet', 'ricochet', 0),
            'bulletAttach' => $this->resolveInt('bullet', 'attach', 0),
            
            // Heat per shot
            'heatPerShot' => $this->resolveHeatValue(),
            
            // Reload rate
            'reloadRate' => $this->resolveFloat('reload', 'rate'),
            
            // Damage properties
            'damageValue' => $this->resolveFloat('damage', 'value'),
            'repairValue' => $this->resolveFloat('damage', 'repair'),
            
            // Weapon system type
            'weaponSystem' => $this->resolveWeaponSystem(),
        );
    }

    /**
     * Resolve weapon system type from <weapon system="xxx"/> element.
     * Returns "weapon_standard" or "weapon_beam", "weapon_missile", etc.
     */
    private function resolveWeaponSystem(): string
    {
        $el = $this->dom->byTagName('weapon')->getFirst();
        if ($el !== null) {
            $system = $el->getAttribute('system');
            if (!empty($system)) {
                return $system;
            }
        }
        return 'weapon_standard'; // Default fallback
    }

    /**
     * Resolve heat value from <heat value="xxx"/> element.
     * This is heat generated per shot.
     */
    private function resolveHeatValue(): float
    {
        $el = $this->dom->byTagName('heat')->getFirst();
        if ($el !== null) {
            $value = $el->getAttribute('value');
            if (!empty($value)) {
                return (float)$value;
            }
        }
        return 0.0;
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

    /**
     * Resolve an integer attribute from an XML element.
     *
     * @param string $tagName Tag name to search for
     * @param string $attribute Attribute name to extract
     * @param int $default Default value if element or attribute not found
     * @return int
     */
    private function resolveInt(string $tagName, string $attribute, int $default = 0): int
    {
        $el = $this->dom->byTagName($tagName)->getFirst();
        if ($el !== null) {
            $value = $el->getAttribute($attribute);
            if (!empty($value)) {
                return (int)$value;
            }
        }
        return $default;
    }

    /**
     * Resolve a string attribute from an XML element.
     *
     * @param string $tagName Tag name to search for
     * @param string $attribute Attribute name to extract
     * @param string $default Default value if element or attribute not found
     * @return string
     */
    private function resolveString(string $tagName, string $attribute, string $default = ''): string
    {
        $el = $this->dom->byTagName($tagName)->getFirst();
        if ($el !== null) {
            $value = $el->getAttribute($attribute);
            if (!empty($value)) {
                return $value;
            }
        }
        return $default;
    }
}
