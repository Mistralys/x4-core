<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Engines\EnginesExtractor
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Engines;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;

/**
 * Extracts engine performance data from X4 macro XML files.
 * 
 * Process:
 * 1. Filter WareDefs to get only engines (group = "engines")
 * 2. For each engine wareID:
 *    - Access macro XML via WareDef
 *    - Parse performance properties from XML
 *    - Build EngineDef data array
 * 3. Write to data/engines.json
 *
 * @package X4Core
 * @subpackage Database
 */
class EnginesExtractor
{
    /**
     * @var array<int,array<string,mixed>>
     */
    private array $engines = array();

    public function __construct(DataFolders $dataFolders)
    {
        // DataFolders passed for future use or consistency with other extractors
    }

    /**
     * Main extraction method.
     * Generates data/engines.json.
     */
    public function extract(): void
    {
        $this->extractEngines();
    }

    private function extractEngines(): void
    {
        Console::header('Extracting engines...');

        foreach (WareDefs::getInstance()->findWares()->selectGroup(WareGroups::GROUP_ENGINES)->getAll() as $ware) {
            $this->processWare($ware);
        }

        Console::line1('Found [%d] engines.', count($this->engines));
        Console::line1('Saving to disk...');
        Console::nl();

        ksort($this->engines);

        EngineDefs::getInstance()
            ->getDataFile()
            ->putData($this->engines);
    }

    private function processWare(WareDef $ware): void
    {
        $this->engines[] = (new EngineMacroExtractor($ware))->extract();
    }
}
