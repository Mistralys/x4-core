<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategoryInterface;
use Mistralys\X4\X4Application;
use function AppUtils\t;

/**
 * @method BlueprintDef getByID(string $id)
 * @method BlueprintDef[] getAll()
 * @method BlueprintDef getDefault()
 */
class BlueprintDefs extends BaseStringPrimaryCollection implements BlueprintCategoryInterface
{
    private static ?BlueprintDefs $instance = null;
    private JSONFile $dataFile;

    private function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() .'/blueprints.json')
            ->setPrettyPrint(true)
            ->setTrailingNewline(true);
    }

    public function getDataFile() : JSONFile
    {
        return $this->dataFile;
    }

    public static function reset() : void
    {
        self::$instance = null;
    }

    public function getID() : string
    {
        return 'all-blueprints';
    }

    public function getBlueprintClass() : string
    {
        return BlueprintDef::class;
    }

    public static function getInstance() : BlueprintDefs
    {
        if(!isset(self::$instance)) {
            self::$instance = new BlueprintDefs();
        }

        return self::$instance;
    }

    public function getLabel() : string
    {
        return t('All blueprints');
    }

    public function createSelection(): BlueprintSelection
    {
        return BlueprintSelection::create($this->getAll());
    }

    public function getBlueprints(): array
    {
        return $this->getAll();
    }

    public function countBlueprints(): int
    {
        return $this->countRecords();
    }

    public function getBlueprintByID(string $blueprintID): BlueprintDef
    {
        return $this->getByID($blueprintID);
    }

    public function blueprintIDExists(string $blueprintID): bool
    {
        return $this->idExists($blueprintID);
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach($this->getDataFile()->getData() as $def) {
            if(is_array($def)) {
                $this->registerItem(BlueprintDef::fromArray($def));
            }
        }
    }
}
