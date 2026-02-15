<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Shields\ShieldMacroExtractor
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Shields;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Extracts shield performance data from a single shield macro XML file.
 *
 * @package X4Core
 * @subpackage Database
 */
class ShieldMacroExtractor
{
    private WareDef $ware;
    private DOMExtended $dom;

    public function __construct(WareDef $ware)
    {
        $this->ware = $ware;
        $this->dom = $ware->getMacro()->getDOM();
    }

    /**
     * Extracts the shield data from the macro XML.
     *
     * @return array<string,mixed>
     */
    public function extract(): array
    {
        return array(
            ShieldDef::KEY_WARE_ID => $this->ware->getID(),
            ShieldDef::KEY_MACRO_ID => $this->dom->byTagName('macro')->requireFirst()->getAttribute('name'),
            ShieldDef::KEY_LABEL => $this->ware->getLabel(),
            ShieldDef::KEY_SIZE => $this->ware->getSize(),
            ShieldDef::KEY_DATA_SOURCE_ID => $this->ware->getDataSourceID(),
            ShieldDef::KEY_MAKER_RACES => $this->resolveMakerRace(),
            ShieldDef::KEY_MK => $this->resolveMk(),
            ShieldDef::KEY_VARIANT_ID => (string)$this->ware->getVariantID(),
            ShieldDef::KEY_SHIELD_TYPE => $this->resolveShieldType(),
            
            // Recharge properties
            ShieldDef::KEY_RECHARGE_MAX => $this->resolveFloat('recharge', 'max'),
            ShieldDef::KEY_RECHARGE_RATE => $this->resolveFloat('recharge', 'rate'),
            ShieldDef::KEY_RECHARGE_DELAY => $this->resolveFloat('recharge', 'delay'),
            
            // Hull properties
            ShieldDef::KEY_HULL_MAX => $this->resolveFloat('hull', 'max'),
            ShieldDef::KEY_HULL_THRESHOLD => $this->resolveFloat('hull', 'threshold'),
            ShieldDef::KEY_HULL_INTEGRATED => $this->resolveIntegrated(),
        );
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
     * Resolve shield type from ware ID pattern.
     * Examples:
     *   shield_arg_l_standard_01_mk1 -> standard
     *   shield_par_s_racer_01_mk1 -> racer
     *   shield_gen_xl_corvette_01_mk1 -> corvette
     */
    private function resolveShieldType(): string
    {
        $wareID = $this->ware->getID();
        
        $types = [
            'standard',
            'racer',
            'corvette',
            'mothership',
            'yacht',
            'experimental',
            'virtual'
        ];
        
        foreach ($types as $type) {
            if (strpos($wareID, '_' . $type . '_') !== false) {
                return $type;
            }
        }
        
        return 'standard'; // Default fallback
    }

    /**
     * Resolve integrated flag from hull element.
     * integrated="1" means shield is integrated into hull.
     */
    private function resolveIntegrated(): bool
    {
        $el = $this->dom->byTagName('hull')->getFirst();
        if ($el !== null) {
            $integrated = $el->getAttribute('integrated');
            return $integrated === '1';
        }
        return false;
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
