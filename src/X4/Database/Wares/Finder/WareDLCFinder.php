<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares\Finder;

use Mistralys\X4\Database\DataSources\DLCs;

/**
 * Utility to select a specific DLC as search criteria
 * in the ware finder ({@see WareFinder::selectDLC()}).
 *
 * @package X4 Database
 * @subpackage Wares
 */
class WareDLCFinder
{
    private WareFinder $defs;

    public function __construct(WareFinder $finder)
    {
        $this->defs = $finder;
    }

    public function boron(): WareFinder
    {
        return $this->id(DLCs::DLC_BORON);
    }

    public function hyperion(): WareFinder
    {
        return $this->id(DLCs::DLC_HYPERION);
    }

    public function pirate(): WareFinder
    {
        return $this->id(DLCs::DLC_PIRATE);
    }

    public function split(): WareFinder
    {
        return $this->id(DLCs::DLC_SPLIT);
    }

    public function terran(): WareFinder
    {
        return $this->id(DLCs::DLC_TERRAN);
    }

    public function timelines(): WareFinder
    {
        return $this->id(DLCs::DLC_TIMELINES);
    }

    /**
     * @param string $dlcID
     * @return WareFinder
     */
    public function id(string $dlcID): WareFinder
    {
        return $this->defs->selectDLCID($dlcID);
    }
}