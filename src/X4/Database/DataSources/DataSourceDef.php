<?php
/**
 * @package X4 Core
 * @subpackage Data Sources
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\DataSources;

use AppUtils\ArrayDataCollection;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\ExtractedData\DataFolder;

/**
 * This identifies a source data folder in the game, from the
 * base game data to its DLCs.
 *
 * {@see self::getID()} returns the name of the data folder.
 * In the case of extensions, this is the name of the DLC.
 * For example, `ego_dlc_split` for the Split Vendetta DLC.
 *
 * @package X4 Core
 * @subpackage Data Sources
 */
class DataSourceDef implements StringPrimaryRecordInterface
{
    public const KEY_ID = 'id';
    public const KEY_LABEL = 'label';
    public const KEY_IS_EXTENSION = 'isExtension';

    private string $id;
    private string $label;
    private bool $isExtension;

    public function __construct(string $id, string $label, bool $isExtension)
    {
        $this->id = $id;
        $this->label = $label;
        $this->isExtension = $isExtension;
    }

    public static function toArray(DataFolder $dataFolder) : array
    {
        return array(
            self::KEY_ID => $dataFolder->getID(),
            self::KEY_LABEL => $dataFolder->getLabel(),
            self::KEY_IS_EXTENSION => $dataFolder->isExtension(),
        );
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function isExtension() : bool
    {
        return $this->isExtension;
    }

    public static function fromArray(array $data) : self
    {
        $data = ArrayDataCollection::create($data);

        return new self(
            $data->getString(self::KEY_ID),
            $data->getString(self::KEY_LABEL),
            $data->getBool(self::KEY_IS_EXTENSION)
        );
    }
}
