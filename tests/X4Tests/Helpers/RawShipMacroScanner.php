<?php
/**
 * @package X4Tests
 * @subpackage Helpers
 * @see \X4Tests\Helpers\RawShipMacroScanner
 */

declare(strict_types=1);

namespace X4Tests\Helpers;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Ships\ShipDef;
use SimpleXMLElement;

/**
 * Independent reader of the raw ship macro files, used by the real-data
 * tests to verify the extracted ship properties. It deliberately shares no
 * code with the extractor; only the macro file lookup of the macro index
 * is used to find the files.
 *
 * Values are read from the ship's own macro first and from the macro its
 * `alias=` points to second, like the game does.
 *
 * @package X4Tests
 * @subpackage Helpers
 */
class RawShipMacroScanner
{
    private const PROPERTIES = '/macros/macro/properties/';

    /**
     * @var array<string,SimpleXMLElement|null>
     */
    private static array $cache = array();

    public static function loadMacro(string $macroName, string $dataSourceID) : ?SimpleXMLElement
    {
        $key = $dataSourceID.'::'.$macroName;

        if(array_key_exists($key, self::$cache)) {
            return self::$cache[$key];
        }

        try {
            $path = MacroFileDefs::getInstance()->getByMacroName($macroName, $dataSourceID)->getFile()->getPath();
        } catch (RecordNotExistsException) {
            return self::$cache[$key] = null;
        }

        $xml = simplexml_load_file($path);

        return self::$cache[$key] = ($xml === false ? null : $xml);
    }

    public static function getOwnMacro(ShipDef $ship) : SimpleXMLElement
    {
        $xml = self::loadMacro($ship->getWare()->getMacroID(), $ship->getDataSourceID());

        if($xml === null) {
            throw new \RuntimeException('Macro not found for ship '.$ship->getID());
        }

        return $xml;
    }

    public static function getAliasName(ShipDef $ship) : ?string
    {
        $alias = (string)(self::getOwnMacro($ship)->xpath('/macros/macro/@alias')[0] ?? '');

        return $alias !== '' ? $alias : null;
    }

    /**
     * @return SimpleXMLElement[] The own macro, then the alias macro if there is one.
     */
    public static function getMacros(ShipDef $ship) : array
    {
        $macros = array(self::getOwnMacro($ship));
        $alias = self::getAliasName($ship);

        if($alias !== null) {
            $aliasXML = self::loadMacro($alias, $ship->getDataSourceID());
            if($aliasXML !== null) {
                $macros[] = $aliasXML;
            }
        }

        return $macros;
    }

    /**
     * Elements below `properties` from the first macro that has any.
     *
     * @return SimpleXMLElement[]
     */
    public static function elements(ShipDef $ship, string $path) : array
    {
        foreach(self::getMacros($ship) as $xml) {
            $found = $xml->xpath(self::PROPERTIES.$path);
            if(!empty($found)) {
                return $found;
            }
        }

        return array();
    }

    /**
     * First non-empty attribute value below `properties`: own macro first, alias second.
     */
    public static function attr(ShipDef $ship, string $path, string $attribute) : ?string
    {
        foreach(self::getMacros($ship) as $xml)
        {
            $element = ($xml->xpath(self::PROPERTIES.$path) ?: array())[0] ?? null;
            $value = $element === null ? '' : (string)$element[$attribute];

            if($value !== '') {
                return $value;
            }
        }

        return null;
    }

    public static function intAttr(ShipDef $ship, string $path, string $attribute) : ?int
    {
        $value = self::attr($ship, $path, $attribute);

        return $value === null ? null : (int)$value;
    }

    public static function floatAttr(ShipDef $ship, string $path, string $attribute) : ?float
    {
        $value = self::attr($ship, $path, $attribute);

        return $value === null ? null : (float)$value;
    }

    /**
     * Capacity per dock size of the internal hangar bays: connected macros
     * with `<dock storage="1">`, not hidden. Own connections first, alias second.
     *
     * @return array<string,int>
     */
    public static function hangar(ShipDef $ship) : array
    {
        foreach(self::getMacros($ship) as $xml)
        {
            $capacities = array();

            foreach($xml->xpath('//connection/macro/@ref') ?: array() as $ref)
            {
                $bay = self::loadMacro((string)$ref, $ship->getDataSourceID());
                $dock = $bay === null ? null : ($bay->xpath('//dock[@storage="1"]') ?: array())[0] ?? null;

                if($dock === null || (string)$dock['hidden'] === '1') {
                    continue;
                }

                $tags = (string)(($bay->xpath('//docksize/@tags') ?: array())[0] ?? '');
                preg_match_all('/dock_(xs|s|m|l|xl)\b/', $tags, $matches);

                foreach(array_unique($matches[1]) as $size) {
                    $capacities[$size] = ($capacities[$size] ?? 0) + (int)$dock['capacity'];
                }
            }

            if(!empty($capacities)) {
                $sorted = array();
                foreach(array('xs', 's', 'm', 'l', 'xl') as $size) {
                    if(isset($capacities[$size])) {
                        $sorted[$size] = $capacities[$size];
                    }
                }

                return $sorted;
            }
        }

        return array();
    }
}
