<?php
/**
 * @package X4 Database
 * @subpackage Macro Index
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\MacroIndex;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\X4Application;

/**
 * Macro file definitions: Based on the `macros.xml` files found in
 * the game's data folders, which map the macro IDs to their respective
 * macro XML file paths.
 *
 * This is very useful as the paths to the macro files are all over
 * the place. This makes it possible to find and load the macro files
 * easily. Methods like {@see MacroFileDef::getFile()} and {@see MacroFileDef::getDOM()}
 * allow easily accessing and loading the macro files.
 *
 * **NOTE**: This class only makes sense to use when the game data has
 * been extracted.
 *
 * @package X4 Database
 * @subpackage Macro Index
 *
 * @method MacroFileDef getByID(string $id)
 * @method MacroFileDef[] getAll()
 * @method MacroFileDef getDefault()
 */
class MacroFileDefs extends BaseStringPrimaryCollection
{
    private JSONFile $dataFile;
    private static ?MacroFileDefs $instance = null;

    public static function getInstance(): MacroFileDefs
    {
        if (!isset(self::$instance)) {
            self::$instance = new MacroFileDefs();
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() . '/macro-index.json')
            ->setPrettyPrint(true)
            ->setTrailingNewline(true)
            ->setEscapeSlashes(false);
    }

    public function getDataFile(): JSONFile
    {
        return $this->dataFile;
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach($this->dataFile->getData() as $macro) {
            $this->registerItem(MacroFileDef::fromArray($macro));
        }
    }
}
