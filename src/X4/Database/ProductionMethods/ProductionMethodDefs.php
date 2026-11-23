<?php
/**
 * @package X4 Database
 * @subpackage Production Methods
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\ProductionMethods;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\X4Application;

/**
 * Collection of all ware production methods, read from
 * the data file `production-methods.json`.
 *
 * @package X4 Database
 * @subpackage Production Methods
 *
 * @method ProductionMethodDef getByID(string $id)
 * @method ProductionMethodDef[] getAll()
 * @method ProductionMethodDef getDefault()
 */
class ProductionMethodDefs extends BaseStringPrimaryCollection
{
    public const DATA_FILE_NAME = 'production-methods.json';

    private static ?ProductionMethodDefs $instance = null;

    private JSONFile $dataFile;

    protected function __construct()
    {
        $this->dataFile = self::getStorageFile();
    }

    public static function getStorageFile() : JSONFile
    {
        return JSONFile::factory(X4Application::getDataFolder() . '/' . self::DATA_FILE_NAME)
            ->setPrettyPrint(true)
            ->setTrailingNewline(true)
            ->setEscapeSlashes(false);
    }

    public static function getInstance() : ProductionMethodDefs
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getDataFile() : JSONFile
    {
        return $this->dataFile;
    }

    public function getDefaultID() : string
    {
        return $this->getAutoDefault();
    }

    /**
     * Provides the raw method records. Separated from the registration
     * so that the collection logic can be exercised without a data file.
     *
     * @return array<int,array<string,mixed>>
     */
    protected function loadRecords() : array
    {
        /** @var array<int,array<string,mixed>> $records */
        $records = $this->getDataFile()->getData();

        return $records;
    }

    protected function registerItems() : void
    {
        foreach($this->loadRecords() as $record) {
            $this->registerItem(ProductionMethodDef::fromArray($record));
        }
    }
}
