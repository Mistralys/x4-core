<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistral\X4\Database\Wares\WaresExtractor;
use Mistralys\X4\Database\Wares\Finder\WareFinder;
use Mistralys\X4\X4Application;

/**
 * Collection of all wares available in the game. These include
 * virtually all items that can be bought or built in the game,
 * from basic resources to ships.
 *
 * Basic information is available for each, and they are grouped
 * and tagged to allow for easy searching.
 *
 * ## Consistency fixes
 *
 * A few fixes and tweaks are applied to the data to ensure
 * consistency. For example, some ships filed in the
 * {@see WareGroups::GROUP_SHIPS} group do not have the
 * {@see WaresExtractor::TAG_SHIP} tag set on them.
 * This is fixed automatically during extraction.
 *
 * ## Finding wares
 *
 * Use the finder utility to search for wares based on various
 * criteria: {@see self::findWares()}. The finder allows you to
 * select several criteria to refine the wares you are looking for.
 * See the {@see WareFinder} class for more details.
 *
 * @package X4 Database
 * @subpackage Wares
 *
 * @method WareDef[] getAll()
 * @method WareDef getByID(string $id)
 * @method WareDef getDefault()
 */
class WareDefs extends BaseStringPrimaryCollection
{
    private JSONFile $dataFile;

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    public function getByTag(string $tagName) : array
    {
        $results = array();
        foreach($this->getAll() as $ware) {
            if(in_array($tagName, $ware->getTags(), true)) {
                $results[] = $ware;
            }
        }

        return $results;
    }

    public function findWares() : WareFinder
    {
        return new WareFinder($this);
    }

    protected function registerItems(): void
    {
        foreach($this->getDataFile()->getData() as $wareDef) {
            $this->registerItem(WareDef::fromArray($wareDef));
        }
    }

    private static ?WareDefs $instance = null;

    public static function getInstance() : WareDefs
    {
        if(!isset(self::$instance)) {
            self::$instance = new WareDefs();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() .'/wares.json')
            ->setPrettyPrint(true)
            ->setTrailingNewline(true)
            ->setEscapeSlashes(false);
    }

    public function getDataFile() : JSONFile
    {
        return $this->dataFile;
    }
}
