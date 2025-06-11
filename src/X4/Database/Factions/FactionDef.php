<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Factions;

use AppUtils\ArrayDataCollection;
use AppUtils\Interfaces\StringPrimaryRecordInterface;

class FactionDef implements StringPrimaryRecordInterface
{
    public const KEY_ID = 'id';
    public const KEY_NAME = 'name';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';

    private string $id;
    private string $label;
    private string $dataSourceID;

    public function __construct(string $raceID, string $label, string $dataSourceID)
    {
        $this->id = $raceID;
        $this->label = $label;
        $this->dataSourceID = $dataSourceID;
    }

    public static function fromArray(array $raceDef) : self
    {
        $data = ArrayDataCollection::create($raceDef);

        return new self(
            $data->getString(self::KEY_ID),
            $data->getString(self::KEY_NAME),
            $data->getString(self::KEY_DATA_SOURCE_ID)
        );
    }

    /**
     * @return string Lowercase identifier, e.g. "arg" or "par"
     */
    public function getID() : string
    {
        return $this->id;
    }

    public function getShortIDs() : array
    {
        $id = $this->getID();
        $ids = array(substr($id, 0, 3));

        foreach(FactionDefs::SHORT_ID_MAPPINGS as $shortID => $longID) {
            if($longID === $id) {
                $ids[] = $shortID;
            }
        }

        return $ids;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getDataSourceID() : string
    {
        return $this->dataSourceID;
    }

    public function isGeneric() : bool
    {
        return $this->getID() === KnownFactions::FACTION_GENERIC;
    }
}
