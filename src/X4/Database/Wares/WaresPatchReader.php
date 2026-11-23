<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\FileHelper\FileInfo;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Reads the DLC `libraries/wares.xml` files, which are `<diff>` documents,
 * and returns their `<add>` operations as {@see WarePatch} objects.
 *
 * Only `<add>` operations with the selectors `/wares`, `/wares/production`
 * and `/wares/ware[@id='X']` are supported, as these are the only ones present
 * in the game data. Anything else is skipped with a warning, so that a change
 * in the game data does not go unnoticed.
 *
 * @package X4 Database
 * @subpackage Wares
 */
class WaresPatchReader
{
    public const SELECTOR_WARES = '/wares';
    public const SELECTOR_PRODUCTION = '/wares/production';

    private const REGEX_WARE_SELECTOR = '/\A\/wares\/ware\[@id=(?:\'([^\']+)\'|"([^"]+)")]\z/';

    private DataFolders $dataFolders;

    /**
     * @var WarePatch[]|null
     */
    private ?array $patches = null;

    /**
     * @var string[]
     */
    private array $warnings = array();

    public function __construct(DataFolders $dataFolders)
    {
        $this->dataFolders = $dataFolders;
    }

    /**
     * Gets all patches that target a single ware
     * (`/wares/ware[@id='X']` selectors).
     *
     * @return WarePatch[]
     */
    public function getWarePatches() : array
    {
        return array_values(array_filter(
            $this->getAllPatches(),
            static fn(WarePatch $patch) : bool => $patch->isWareTargeted()
        ));
    }

    /**
     * Gets all patches with the exact selector, e.g. `/wares/production`.
     *
     * @param string $selector
     * @return WarePatch[]
     */
    public function getPatchesForSelector(string $selector) : array
    {
        return array_values(array_filter(
            $this->getAllPatches(),
            static fn(WarePatch $patch) : bool => $patch->selector === $selector
        ));
    }

    /**
     * Gets all supported patches of all DLC data folders,
     * in the order of the data folders.
     *
     * @return WarePatch[]
     */
    public function getAllPatches() : array
    {
        if($this->patches !== null) {
            return $this->patches;
        }

        $this->patches = array();

        foreach($this->dataFolders->getAll() as $dataFolder)
        {
            if(!$dataFolder->isExtension()) {
                continue;
            }

            $file = FileInfo::factory($dataFolder->getPath().'/libraries/wares.xml');

            if($file->exists()) {
                array_push($this->patches, ...$this->readFile($file, $dataFolder->getID()));
            }
        }

        return $this->patches;
    }

    /**
     * Reads the patches of a single diff file.
     *
     * @param FileInfo $file
     * @param string $dataFolderID
     * @return WarePatch[]
     */
    public function readFile(FileInfo $file, string $dataFolderID) : array
    {
        $patches = array();
        $dom = DOMExtended::createFromFile($file);
        $root = $dom->getDOM()->documentElement;

        if($root === null) {
            return $patches;
        }

        foreach((new ElementExtended($dom, $root))->getChildren() as $operation)
        {
            $name = $operation->getDOMElement()->tagName;
            $selector = (string)$operation->getAttribute('sel');

            if($name !== 'add') {
                $this->warn('Data folder [%s] | WARNING | Unsupported diff operation [%s] on [%s] skipped.', $dataFolderID, $name, $selector);
                continue;
            }

            if($selector === self::SELECTOR_WARES || $selector === self::SELECTOR_PRODUCTION) {
                $patches[] = new WarePatch($dataFolderID, $selector, null, $operation);
                continue;
            }

            $wareID = $this->parseWareSelector($selector);

            if($wareID !== null) {
                $patches[] = new WarePatch($dataFolderID, $selector, $wareID, $operation);
                continue;
            }

            $this->warn('Data folder [%s] | WARNING | Unsupported selector [%s] skipped.', $dataFolderID, $selector);
        }

        return $patches;
    }

    /**
     * @return string[] Warnings collected while reading, for diagnostics and tests.
     */
    public function getWarnings() : array
    {
        return $this->warnings;
    }

    private function parseWareSelector(string $selector) : ?string
    {
        if(preg_match(self::REGEX_WARE_SELECTOR, $selector, $matches) === 1) {
            return $matches[1] !== '' ? $matches[1] : $matches[2];
        }

        return null;
    }

    private function warn(string $message, string ...$args) : void
    {
        $this->warnings[] = vsprintf($message, $args);

        Console::line1($message, ...$args);
    }
}
