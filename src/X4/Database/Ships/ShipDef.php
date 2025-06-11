<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\ArrayDataCollection;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;

class ShipDef implements StringPrimaryRecordInterface
{
    public const KEY_ID = 'id';
    public const KEY_SIZE = 'size';
    public const KEY_BUILDER_FACTION_ID = 'builderFactionID';
    public const KEY_CLASS_ID = 'classID';
    public const KEY_USED_BY = 'usedBy';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';

    private string $id;
    private string $classID;
    private string $size;
    private string $builderFactionID;

    /**
     * @var string[]
     */
    private array $usedBy;
    private string $dataSourceID;

    public function __construct(string $id, string $size, string $builderFactionID, string $classID, array $usedBy, string $dataSourceID)
    {
        $this->id = $id;
        $this->size = $size;
        $this->builderFactionID = $builderFactionID;
        $this->classID = $classID;
        $this->usedBy = $usedBy;
        $this->dataSourceID = $dataSourceID;
    }

    public static function fromArray(array $def) : ShipDef
    {
        $data = ArrayDataCollection::create($def);

        return new self(
            $data->getString(self::KEY_ID),
            $data->getString(self::KEY_SIZE),
            $data->getString(self::KEY_BUILDER_FACTION_ID),
            $data->getString(self::KEY_CLASS_ID),
            $data->getArray(self::KEY_USED_BY),
            $data->getString(self::KEY_DATA_SOURCE_ID)
        );
    }

    public function getID(): string
    {
        return $this->id;
    }

    public function getSize(): string
    {
        return $this->size;
    }

    public function getBuilderFactionID(): string
    {
        return $this->builderFactionID;
    }

    public function getBuilderFaction() : FactionDef
    {
        return FactionDefs::getInstance()->getByID($this->getBuilderFactionID());
    }

    public function getClassID(): string
    {
        return $this->classID;
    }

    public function getClass(): ShipClass
    {
        return ShipClasses::getInstance()->getByID($this->getClassID());
    }

    public function getDataSourceID(): string
    {
        return $this->dataSourceID;
    }

    public function getDataSource() : DataSourceDef
    {
        return DataSourceDefs::getInstance()->getByID($this->getDataSourceID());
    }

    /**
     * @return FactionDef[]
     */
    public function getUsedBy() : array
    {
        $result = array();

        foreach($this->usedBy as $factionID) {
            $result[] = FactionDefs::getInstance()->getByID($factionID);
        }

        return $result;
    }

    public function toArray(): array
    {
        return array(
            self::KEY_ID => $this->id,
            self::KEY_SIZE => $this->size,
            self::KEY_BUILDER_FACTION_ID => $this->builderFactionID,
            self::KEY_CLASS_ID => $this->classID,
            self::KEY_USED_BY => $this->usedBy,
            self::KEY_DATA_SOURCE_ID => $this->dataSourceID
        );
    }
}
