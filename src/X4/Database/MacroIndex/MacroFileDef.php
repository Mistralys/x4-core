<?php
/**
 * @package X4 Database
 * @subpackage Macro Index
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\MacroIndex;

use AppUtils\ArrayDataCollection;
use AppUtils\FileHelper\FileInfo;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\ExtractedData\DataFolder;
use Mistralys\X4\XML\DOMExtended;

/**
 * Information about a macro file from a game data folder.
 *
 * @package X4 Database
 * @subpackage Macro Index
 */
class MacroFileDef implements StringPrimaryRecordInterface
{
    public const KEY_DATA_FOLDER = 'dataFolder';
    public const KEY_FULL_PATH = 'fullPath';
    public const KEY_NAME = 'name';

    private string $name;
    private string $fullPath;
    private string $dataFolderID;

    public function __construct(string $name, string $fullPath, string $dataFolderID)
    {
        $this->name = $name;
        $this->fullPath = $fullPath;
        $this->dataFolderID = $dataFolderID;
    }

    public function getID() : string
    {
        return $this->name;
    }

    public function getFile() : FileInfo
    {
        return FileInfo::factory($this->getDataFolder()->getFolder().'/'.$this->getFullPath().'.xml');
    }

    public function getFullPath() : string
    {
        return $this->fullPath;
    }

    public function getDataFolderID() : string
    {
        return $this->dataFolderID;
    }

    public function getDataFolder() : DataFolder
    {
        return DatabaseBuilder::getDataFolders()->getByID($this->getDataFolderID());
    }

    public function getDOM() : DOMExtended
    {
        return DOMExtended::createFromFile($this->getFile());
    }

    public static function fromArray(array $def) : MacroFileDef
    {
        $data = ArrayDataCollection::create($def);

        return new MacroFileDef(
            $data->getString(self::KEY_NAME),
            $data->getString(self::KEY_FULL_PATH),
            $data->getString(self::KEY_DATA_FOLDER)
        );
    }
}
