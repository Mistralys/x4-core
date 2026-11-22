<?php
/**
 * @package X4 Database
 * @subpackage Macro Index
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\MacroIndex;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\Collections\RecordNotExistsException;
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

    /**
     * Retrieves a macro by its name and data folder.
     * This is the recommended way to lookup macros when you have
     * a simple macro name and know which data source it belongs to.
     *
     * ## Resolution order
     *
     * The game does not scope macro references to the data folder of the
     * file that contains them: every `index/macros.xml` (base game and
     * extensions alike) feeds one global name-to-file map, and an extension
     * can both add macros and replace existing ones. A Split ship may thus
     * reference a vanilla storage macro, or a vanilla ship an extension's.
     * This method mirrors that behavior:
     *
     * 1. The macro as defined in the given data folder (an extension's own
     *    override takes precedence for its own content).
     * 2. Otherwise, the macro as defined in any other data folder. If several
     *    define it, the one from the data folder registered last wins, just as
     *    the last-loaded extension does in the game.
     *
     * Macro names are matched case-insensitively, like the game does.
     *
     * @param string $name The macro name (e.g., "ship_arg_s_fighter_01_a_macro")
     * @param string $dataFolderID The preferred data folder ID (defaults to "vanilla")
     * @return MacroFileDef
     * @throws RecordNotExistsException If no data folder defines the macro
     */
    public function getByMacroName(string $name, string $dataFolderID = 'vanilla'): MacroFileDef
    {
        $id = $dataFolderID . '::' . $name;

        if($this->idExists($id)) {
            return $this->getByID($id);
        }

        $match = null;
        $lowerName = strtolower($name);

        // Entries are registered in data folder order (base game first,
        // then the extensions), so the last match is the highest priority.
        foreach($this->getAll() as $macro) {
            if(strtolower($macro->getMacroName()) === $lowerName) {
                $match = $macro;
            }
        }

        if($match !== null) {
            return $match;
        }

        // Not defined anywhere: let the collection raise its standard exception.
        return $this->getByID($id);
    }

    /**
     * Finds all variants of a macro across all data folders.
     * Useful when you want to see all DLC overrides of a macro.
     *
     * @param string $name The macro name to search for
     * @return MacroFileDef[]
     */
    public function findAllByMacroName(string $name): array
    {
        $result = array();
        
        foreach ($this->getAll() as $macro) {
            if ($macro->getMacroName() === $name) {
                $result[] = $macro;
            }
        }
        
        return $result;
    }

    protected function registerItems(): void
    {
        foreach($this->dataFile->getData() as $macro) {
            $this->registerItem(MacroFileDef::fromArray($macro));
        }
    }
}
