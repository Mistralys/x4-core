<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\MacroProperties;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;

/**
 * The software a ship comes with and the optional software it can install.
 *
 * The game calls the optional entries "compatible"; they are the installable
 * extras and never overlap the default software.
 *
 * @package X4 Database
 * @subpackage Ships
 */
final class ShipSoftware
{
    public const KEY_DEFAULT_WARE_IDS = 'defaultWareIDs';
    public const KEY_OPTIONAL_WARE_IDS = 'optionalWareIDs';

    /**
     * @var string[]
     */
    public readonly array $defaultWareIDs;

    /**
     * @var string[]
     */
    public readonly array $optionalWareIDs;

    /**
     * @param string[] $defaultWareIDs Software ware IDs installed by default.
     * @param string[] $optionalWareIDs Software ware IDs that can be installed additionally.
     */
    public function __construct(array $defaultWareIDs = array(), array $optionalWareIDs = array())
    {
        $this->defaultWareIDs = array_values($defaultWareIDs);
        $this->optionalWareIDs = array_values($optionalWareIDs);
    }

    /**
     * @param array<string,mixed> $data
     * @return self
     */
    public static function fromArray(array $data) : self
    {
        return new self(
            (array)($data[self::KEY_DEFAULT_WARE_IDS] ?? array()),
            (array)($data[self::KEY_OPTIONAL_WARE_IDS] ?? array())
        );
    }

    /**
     * @return array{defaultWareIDs:string[],optionalWareIDs:string[]}
     */
    public function toArray() : array
    {
        return array(
            self::KEY_DEFAULT_WARE_IDS => $this->defaultWareIDs,
            self::KEY_OPTIONAL_WARE_IDS => $this->optionalWareIDs
        );
    }

    /**
     * @return WareDef[] The default software wares. IDs that do not resolve to a ware are skipped.
     */
    public function getDefaultSoftware() : array
    {
        return $this->resolveWares($this->defaultWareIDs);
    }

    /**
     * @return WareDef[] The optional software wares. IDs that do not resolve to a ware are skipped.
     */
    public function getOptionalSoftware() : array
    {
        return $this->resolveWares($this->optionalWareIDs);
    }

    public function isDefault(string $wareID) : bool
    {
        return in_array($wareID, $this->defaultWareIDs, true);
    }

    public function isOptional(string $wareID) : bool
    {
        return in_array($wareID, $this->optionalWareIDs, true);
    }

    /**
     * Whether the ship can have the software installed, be it as
     * default software or as an optional one.
     *
     * @param string $wareID
     * @return bool
     */
    public function isInstallable(string $wareID) : bool
    {
        return $this->isDefault($wareID) || $this->isOptional($wareID);
    }

    public function isEmpty() : bool
    {
        return empty($this->defaultWareIDs) && empty($this->optionalWareIDs);
    }

    /**
     * @param string[] $wareIDs
     * @return WareDef[]
     */
    private function resolveWares(array $wareIDs) : array
    {
        $wares = WareDefs::getInstance();
        $result = array();

        foreach($wareIDs as $wareID) {
            $ware = $wares->findByMacro($wareID);

            if($ware !== null) {
                $result[] = $ware;
            }
        }

        return $result;
    }
}
