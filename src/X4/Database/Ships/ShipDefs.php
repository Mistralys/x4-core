<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\X4Application;

/**
 * @method ShipDef getByID(string $id)
 * @method ShipDef[] getAll()
 * @method ShipDef getDefault()
 */
class ShipDefs extends BaseStringPrimaryCollection implements ItemCollectionInterface
{
    private static ?ShipDefs $instance = null;
    private JSONFile $dataFile;

    public static function getInstance(): ShipDefs
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShipDefs();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() . '/ships.json')
            ->setPrettyPrint(true)
            ->setTrailingNewline(true);
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    public function getDataFile() : JSONFile
    {
        return $this->dataFile;
    }

    /**
     * Finds a ship by its ID.
     * Returns null if the ship does not exist.
     *
     * @param string $id The ship ware ID (e.g., 'ship_gen_m_corvette_01')
     * @return ShipDef|null
     */
    public function find(string $id): ?ShipDef
    {
        if ($this->idExists($id)) {
            return $this->getByID($id);
        }

        return null;
    }

    public function getFactions() : array
    {
        $result = array();

        foreach($this->getAll() as $ship) {
            foreach ($ship->getBuilderFactionIDs() as $factionID) {
                if (!isset($result[$factionID])) {
                    $result[$factionID] = FactionDefs::getInstance()->getByID($factionID);
                }
            }
        }

        usort($result, function(FactionDef $a, FactionDef $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });

        return array_values($result);
    }

    protected function registerItems(): void
    {
        foreach($this->dataFile->getData() as $data) {
            $this->registerItem(ShipDef::fromArray($data));
        }
    }

    public function findShips() : ShipFinder
    {
        return new ShipFinder();
    }

    /**
     * Returns a list of all data sources used by the ships in this collection.
     * @return DataSourceDef[]
     */
    public function getDataSources() : array
    {
        $result = array();

        foreach ($this->getAll() as $ship) {
            $dataSourceID = $ship->getDataSourceID();
            if (!isset($result[$dataSourceID])) {
                $result[$dataSourceID] = $ship->getDataSource();
            }
        }

        usort($result, function(DataSourceDef $a, DataSourceDef $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });

        return $result;

    }
}
