<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper;
use AppUtils\FileHelper\JSONFile;
use Mistral\X4\Database\Wares\WaresExtractor;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
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
class WareDefs extends BaseStringPrimaryCollection implements ItemCollectionInterface
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

    /**
     * Attempts to find a ware definition by its ware ID, macro ID or macro file name.
     * @param string $macroNameOrFile E.g. `ship_arg_l_trans_container_04_a` (ware ID), `ship_arg_l_trans_container_04_a_macro` (macro ID) or `ship_arg_l_trans_container_04_a_macro.xml`.
     * @return WareDef|null
     */
    public function findByMacro(string $macroNameOrFile) : ?WareDef
    {
        if(str_contains($macroNameOrFile, '.')) {
            $macroName = FileHelper::removeExtension($macroNameOrFile);
        } else {
            $macroName = $macroNameOrFile;
        }

        $index = $this->getMacroIndex();

        return $index[$macroName] ?? null;
    }

    /**
     * @return array<string, WareDef>
     */
    private ?array $macroIndex = null;

    /**
     * Returns an index of all wares by their ware ID and macro ID.
     * This allows for quick lookups by either ID. Also see {@see self::findByMacro()},
     * which uses this index.
     *
     * @return array<string, WareDef>
     */
    public function getMacroIndex() : array
    {
        if(isset($this->macroIndex)) {
            return $this->macroIndex;
        }

        $this->macroIndex = array();

        foreach($this->getAll() as $ware) {
            $this->macroIndex[$ware->getWareID()] = $ware;
            $this->macroIndex[$ware->getMacroID()] = $ware;
        }

        return $this->macroIndex;
    }
}
