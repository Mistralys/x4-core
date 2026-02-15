<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Engines\EngineMacroExtractor
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Engines;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Extracts engine performance data from a single engine macro XML file.
 *
 * @package X4Core
 * @subpackage Database
 */
class EngineMacroExtractor
{
    private WareDef $ware;
    private DOMExtended $dom;

    public function __construct(WareDef $ware)
    {
        $this->ware = $ware;
        $this->dom = $ware->getMacro()->getDOM();
    }

    /**
     * Extracts the engine data from the macro XML.
     *
     * @return array<string,mixed>
     */
    public function extract(): array
    {
        return array(
            EngineDef::KEY_WARE_ID => $this->ware->getID(),
            EngineDef::KEY_MACRO_ID => $this->dom->byTagName('macro')->requireFirst()->getAttribute('name'),
            EngineDef::KEY_LABEL => $this->ware->getLabel(),
            EngineDef::KEY_SIZE => $this->ware->getSize(),
            EngineDef::KEY_DATA_SOURCE_ID => $this->ware->getDataSourceID(),
            EngineDef::KEY_MAKER_RACES => $this->resolveMakerRace(),
            EngineDef::KEY_MK => $this->resolveMk(),
            EngineDef::KEY_VARIANT_ID => (string)$this->ware->getVariantID(),
            
            // Boost properties
            EngineDef::KEY_BOOST_DURATION => $this->resolveFloat('boost', 'duration'),
            EngineDef::KEY_BOOST_RECHARGE => $this->resolveFloat('boost', 'recharge'),
            EngineDef::KEY_BOOST_THRUST => $this->resolveFloat('boost', 'thrust', 1.0),
            EngineDef::KEY_BOOST_ACCELERATION => $this->resolveFloat('boost', 'acceleration', 1.0),
            EngineDef::KEY_BOOST_ATTACK => $this->resolveFloat('boost', 'attack'),
            EngineDef::KEY_BOOST_RELEASE => $this->resolveFloat('boost', 'release'),
            EngineDef::KEY_BOOST_COAST => $this->resolveFloat('boost', 'coast', 1.0),
            
            // Travel properties
            EngineDef::KEY_TRAVEL_CHARGE => $this->resolveFloat('travel', 'charge'),
            EngineDef::KEY_TRAVEL_THRUST => $this->resolveFloat('travel', 'thrust'),
            EngineDef::KEY_TRAVEL_ATTACK => $this->resolveFloat('travel', 'attack'),
            EngineDef::KEY_TRAVEL_RELEASE => $this->resolveFloat('travel', 'release'),
            
            // Thrust properties
            EngineDef::KEY_THRUST_FORWARD => $this->resolveFloat('thrust', 'forward'),
            EngineDef::KEY_THRUST_REVERSE => $this->resolveFloat('thrust', 'reverse'),
            
            // Hull properties
            EngineDef::KEY_HULL_MAX => $this->resolveFloat('hull', 'max'),
            EngineDef::KEY_HULL_THRESHOLD => $this->resolveFloat('hull', 'threshold'),
            
            // Curves
            EngineDef::KEY_DECELERATION_CURVE => $this->resolveDecelerationCurve(),
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
     * Resolve deceleration curve points.
     *
     * @return array<int,array{position:float,value:float}>
     */
    private function resolveDecelerationCurve(): array
    {
        $curve = $this->dom->byTagName('decelerationcurve')->getFirst();
        if ($curve === null) {
            return array();
        }

        $points = array();
        foreach ($this->dom->byTagName('point')->getAll() as $pointNode) {
            // Check if this point is a child of the deceleration curve
            // by checking if it's within the curve's children
            $parent = $pointNode->getDOMElement()->parentNode;
            if ($parent && $parent->nodeName === 'decelerationcurve') {
                $points[] = array(
                    'position' => (float)$pointNode->getAttribute('position'),
                    'value' => (float)$pointNode->getAttribute('value'),
                );
            }
        }

        return $points;
    }

    private function tagIdentification(): ?ElementExtended
    {
        return $this->dom->byTagName('identification')->getFirst();
    }
}
