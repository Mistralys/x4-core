<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;


use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\X4Application;

class ShipDefs extends BaseStringPrimaryCollection
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

    protected function registerItems(): void
    {
        foreach($this->dataFile->getData() as $data) {
            $this->registerItem(ShipDef::fromArray($data));
        }
    }
}
