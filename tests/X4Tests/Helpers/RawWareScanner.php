<?php
/**
 * @package X4Tests
 * @subpackage Helpers
 * @see \X4Tests\Helpers\RawWareScanner
 */

declare(strict_types=1);

namespace X4Tests\Helpers;

use Mistralys\X4\Database\DatabaseBuilder;
use SimpleXMLElement;

/**
 * Independent reader of the raw `libraries/wares.xml` files of all
 * data folders, used by the real-data tests to verify the extracted
 * ware data. It deliberately shares no code with the extractor.
 *
 * Wares tagged `workunit` or `deprecated` are not scanned, as the
 * extractor excludes them. A ware that the extractor dropped for an
 * unknown owner is scanned, so look up only the IDs present in the
 * extracted data.
 *
 * The record of a ware is:
 *
 * - `price`: `array{min:int,average:int,max:int}|null`
 * - `licence`: string|null
 * - `description`: string|null
 * - `volume`: int|null
 * - `transport`: string|null
 * - `buy`: bool
 * - `owners`: string[] (definition plus patches, sorted, unique)
 * - `illegal`: string[] (definition plus patches, sorted, unique)
 * - `productions`: list of `array{method:string,time:float,amount:int,resources:array<string,int>,effects:array<string,float>}`
 *   (the definition's productions first, then the patched ones in data folder order)
 *
 * @package X4Tests
 * @subpackage Helpers
 */
class RawWareScanner
{
    private const EXCLUDED_TAGS = array('workunit', 'deprecated');

    /**
     * @var array<string,array<string,mixed>>|null
     */
    private static ?array $wares = null;

    /**
     * @return array<string,array<string,mixed>> Records by ware ID.
     */
    public static function getWares() : array
    {
        if(self::$wares !== null) {
            return self::$wares;
        }

        $files = array();
        foreach(DatabaseBuilder::getDataFolders()->getAll() as $folder) {
            $path = $folder->getPath().'/libraries/wares.xml';
            if(file_exists($path)) {
                $files[] = $path;
            }
        }

        $wares = array();

        // Definitions: every <ware id> anywhere in every file (the DLC files add whole wares).
        foreach($files as $path) {
            $xml = simplexml_load_file($path);
            if($xml === false) {
                continue;
            }

            foreach($xml->xpath('//ware[@id]') ?: array() as $el) {
                $tags = preg_split('/\s+/', trim((string)$el['tags'])) ?: array();
                if(array_intersect($tags, self::EXCLUDED_TAGS)) {
                    continue;
                }

                $id = (string)$el['id'];
                $wares[$id] = self::readDefinition($el);
            }
        }

        // Patches, in data folder order.
        foreach($files as $path) {
            $xml = simplexml_load_file($path);
            if($xml === false) {
                continue;
            }

            foreach($xml->xpath('/diff/add') ?: array() as $add) {
                if(preg_match('/\A\/wares\/ware\[@id=[\'"]([^\'"]+)[\'"]]\z/', (string)$add['sel'], $m) !== 1) {
                    continue;
                }

                if(!isset($wares[$m[1]])) {
                    continue;
                }

                $wares[$m[1]]['owners'] = array_merge($wares[$m[1]]['owners'], self::readOwners($add));
                $wares[$m[1]]['illegal'] = array_merge($wares[$m[1]]['illegal'], self::readIllegal($add));
                $wares[$m[1]]['productions'] = array_merge($wares[$m[1]]['productions'], self::readProductions($add));
            }
        }

        foreach($wares as $id => $ware) {
            $owners = array_values(array_unique($ware['owners']));
            sort($owners);
            $wares[$id]['owners'] = $owners;

            $illegal = array_values(array_unique($ware['illegal']));
            sort($illegal);
            $wares[$id]['illegal'] = $illegal;
        }

        self::$wares = $wares;

        return $wares;
    }

    /**
     * @return array<string,mixed>
     */
    private static function readDefinition(SimpleXMLElement $el) : array
    {
        $price = null;
        if(isset($el->price)) {
            $price = array(
                'min' => (int)$el->price['min'],
                'average' => (int)$el->price['average'],
                'max' => (int)$el->price['max'],
            );
        }

        return array(
            'price' => $price,
            'licence' => isset($el->restriction['licence']) ? (string)$el->restriction['licence'] : null,
            'description' => isset($el['description']) ? (string)$el['description'] : null,
            'volume' => isset($el['volume']) ? (int)$el['volume'] : null,
            'transport' => isset($el['transport']) ? (string)$el['transport'] : null,
            'buy' => isset($el->restriction['buy']) && (string)$el->restriction['buy'] === '1',
            'owners' => self::readOwners($el),
            'illegal' => self::readIllegal($el),
            'productions' => self::readProductions($el),
        );
    }

    /**
     * @return string[]
     */
    private static function readOwners(SimpleXMLElement $el) : array
    {
        $result = array();
        foreach($el->owner as $owner) {
            $result[] = (string)$owner['faction'];
        }

        return $result;
    }

    /**
     * @return string[]
     */
    private static function readIllegal(SimpleXMLElement $el) : array
    {
        $result = array();
        foreach($el->illegal as $illegal) {
            foreach(preg_split('/\s+/', trim((string)$illegal['factions'])) ?: array() as $factionID) {
                if($factionID !== '') {
                    $result[] = $factionID;
                }
            }
        }

        return $result;
    }

    /**
     * @return array<int,array<string,mixed>>
     */
    private static function readProductions(SimpleXMLElement $el) : array
    {
        $result = array();
        foreach($el->production as $production) {
            $resources = array();
            foreach($production->xpath('primary/ware') ?: array() as $resource) {
                $resources[(string)$resource['ware']] = (int)$resource['amount'];
            }

            $effects = array();
            foreach($production->xpath('effects/effect') ?: array() as $effect) {
                $effects[(string)$effect['type']] = (float)$effect['product'];
            }

            $result[] = array(
                'method' => (string)$production['method'],
                'time' => (float)$production['time'],
                'amount' => (int)$production['amount'],
                'resources' => $resources,
                'effects' => $effects,
            );
        }

        return $result;
    }
}
