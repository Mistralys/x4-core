<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Shields\ShieldsExtractor
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Shields;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;

/**
 * Extracts shield performance data from X4 macro XML files.
 * 
 * Process:
 * 1. Filter WareDefs to get only shields (tag = "shield")
 * 2. For each shield wareID:
 *    - Access macro XML via WareDef
 *    - Parse performance properties from XML
 *    - Build ShieldDef data array
 * 3. Write to data/shields.json
 *
 * @package X4Core
 * @subpackage Database
 */
class ShieldsExtractor
{
    /**
     * @var array<int,array<string,mixed>>
     */
    private array $shields = array();

    public function __construct(DataFolders $dataFolders)
    {
        // DataFolders passed for consistency with other extractors
    }

    /**
     * Main extraction method.
     * Generates data/shields.json.
     */
    public function extract(): void
    {
        $this->extractShields();
    }

    private function extractShields(): void
    {
        Console::header('Extracting shields...');

        // Filter wares to get shields only (tag contains "shield")
        foreach (WareDefs::getInstance()->getAll() as $ware) {
            if (in_array('shield', $ware->getTags(), true)) {
                $this->processWare($ware);
            }
        }

        Console::line1('Found [%d] shields.', count($this->shields));
        Console::line1('Saving to disk...');
        Console::nl();

        ksort($this->shields);

        ShieldDefs::getInstance()
            ->getDataFile()
            ->putData($this->shields);
    }

    private function processWare(WareDef $ware): void
    {
        $this->shields[] = (new ShieldMacroExtractor($ware))->extract();
    }
}
