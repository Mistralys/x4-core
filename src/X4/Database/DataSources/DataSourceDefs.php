<?php
/**
 * @package X4 Core
 * @subpackage Data Sources
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\DataSources;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\X4Application;

/**
 * Keeps track of the sources from which the data was originally
 * extracted in the game files. This is used to determine, among
 * other things, if an item is part of the base game or a DLC.
 *
 * This is a direct mapping to the {@see DataFolders}, which is used
 * when the game files have been extracted locally.
 *
 * @package X4 Core
 * @subpackage Data Sources
 *
 * @method DataSourceDef getByID(string $id)
 * @method DataSourceDef getDefault()
 * @method DataSourceDef[] getAll()
 */
class DataSourceDefs extends BaseStringPrimaryCollection
{
    private JSONFile $dataFile;

    public function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() .'/data-sources.json')
            ->setPrettyPrint(true)
            ->setTrailingNewline(true)
            ->setEscapeSlashes(false);
    }

    private static ?DataSourceDefs $instance = null;

    public static function getInstance() : DataSourceDefs
    {
        if(!isset(self::$instance)) {
            self::$instance = new DataSourceDefs();
        }

        return self::$instance;
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach($this->getDataFile()->getData() as $dataSourceDef) {
            $this->registerItem(DataSourceDef::fromArray($dataSourceDef));
        }
    }

    /**
     * The JSON file that contains the data source definitions.
     * @return JSONFile
     */
    public function getDataFile() : JSONFile
    {
        return $this->dataFile;
    }
}
