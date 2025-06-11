<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Factions;

use AppUtils\ConvertHelper;
use AppUtils\FileHelper\FileInfo;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\DataExtractor\CatFileFinder;
use Mistralys\X4\ExtractedData\DataFolder;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

class FactionsExtractor
{
    private DataFolders $dataFolders;
    private Language $language;

    public function __construct(DataFolders $dataFolders)
    {
        $this->dataFolders = $dataFolders;
        $this->language = Languages::getInstance()->getEnglish();
    }

    public function extract() : void
    {
        $this->extractFactions();
        $this->validateFactions();
        $this->generateKnownFactions();
    }

    private function extractFactions() : void
    {
        foreach($this->dataFolders->getAll() as $dataFolder) {
            $this->extractDataFolder($dataFolder);
        }

        // Not using the known faction constant here because the known
        // factions file has not been generated yet.
        $this->factions['generic'] = array(
            FactionDef::KEY_ID => 'generic',
            FactionDef::KEY_NAME => 'Generic',
            FactionDef::KEY_DATA_SOURCE_ID => CatFileFinder::SOURCE_VANILLA
        );

        ksort($this->factions);

        Console::line1('Found [%d] factions in total.', count($this->factions));
        Console::nl();

        FactionDefs::getInstance()
            ->getDataFile()
            ->putData(array_values($this->factions));
    }

    private function extractDataFolder(DataFolder $dataFolder) : void
    {
        Console::header('Processing data folder [%s]', $dataFolder->getLabel());

        $waresFile = FileInfo::factory($dataFolder->getFolder().'/libraries/factions.xml');

        if(!$waresFile->exists()) {
            Console::line1('SKIP | No factions file found.');
            Console::nl();
            return;
        }

        Console::line1('Processing factions file...');

        $found = 0;
        foreach(DOMExtended::createFromFile($waresFile)->byTagName('faction')->getAll() as $el) {
            $this->processFaction($el, $dataFolder);
            $found++;
        }

        Console::line1('Found [%d] factions.', $found);
        Console::nl();
    }

    private function processFaction(ElementExtended $factionElement, DataFolder $dataFolder) : void
    {
        $id = $factionElement->getAttribute('id');

        if(empty($id)) {
            Console::line1('ERROR | Faction element has no ID.');
            echo $factionElement->getXML();
            return;
        }

        $translated = $this->language->ts($factionElement->getAttribute('name'));
        if(empty($translated)) {
            $translated = ucfirst($id);
        }

        $this->factions[$id] = array(
            FactionDef::KEY_ID => $id,
            FactionDef::KEY_NAME => $translated,
            FactionDef::KEY_DATA_SOURCE_ID => $dataFolder->getID()
        );
    }

    private array $factions = array();

    private function validateFactions() : void
    {
        Console::header('Validating factions');

        $used = array();
        foreach($this->factions as $faction) {
            $used[] = $faction[FactionDef::KEY_ID];
        }

        foreach(FactionDefs::getInstance()->getIDs() as $id) {
            if(!in_array($id, $used)) {
                Console::line1('ERROR | The faction [%s] is unused.', $id);
                exit;
            }
        }

        Console::line1('Done.');
        Console::nl();
    }

    private const KNOWN_FACTIONS_TEMPLATE = <<<'PHP'
<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Races;

class KnownFactions
{
%1$s
    
    public const FACTIONS = array(
%2$s
    );

    private FactionDefs $defs;

    private function __construct()
    {
        $this->defs = FactionDefs::getInstance();
    }

    private static ?KnownFactions $instance = null;

    public static function getInstance() : KnownFactions
    {
        if (!isset(self::$instance)) {
            self::$instance = new KnownFactions();
        }

        return self::$instance;
    }

%3$s
}

PHP;

    private const KNOWN_FACTIONS_METHODS_TEMPLATE = <<<'PHP'
    public function %1$s() : FactionDef
    {
        return $this->defs->getByID(self::%2$s);
    }
PHP;


    private function generateKnownFactions() : void
    {
        Console::header('Generating known factions');

        $constants = array();
        $enums = array();
        $methods = array();

        foreach($this->compileKnownFactions() as $entry)
        {
            $constants[] = sprintf(
                '    public const %s = \'%s\';',
                $entry['constant'],
                $entry['id']
            );

            $enums[] = sprintf(
                '        self::%s',
                $entry['constant']
            );

            $methods[] = sprintf(
                self::KNOWN_FACTIONS_METHODS_TEMPLATE,
                $entry['method'],
                $entry['constant']
            );
        }

        sort($constants);
        sort($enums);
        sort($methods);

        $php = sprintf(
            self::KNOWN_FACTIONS_TEMPLATE,
            implode("\n", $constants),
            implode(",\n", $enums),
            implode("\n\n", $methods)
        );

        FileInfo::factory(__DIR__.'/KnownFactions.php')
            ->putContents($php);

        Console::line1('Done.');
    }

    /**
     * @return array<int,array{constant:string, method:string}>
     */
    private function compileKnownFactions() : array
    {
        $known = array();

        foreach($this->factions as $factionDef)
        {
            $label = $factionDef[FactionDef::KEY_NAME];
            if(empty($label)) {
                Console::line1('SKIP | Faction [%s] has no label.', $factionDef[FactionDef::KEY_ID]);
                continue;
            }

            $id = $factionDef[FactionDef::KEY_ID];

            // Special case for the smuggler faction, to avoid
            // a duplicate "Criminal" constant.
            if($id === 'smuggler') {
                $label = 'Smuggler';
            }

            $known[] = array(
                'id' => $id,
                'constant' => $this->resolveConstantName($label),
                'method' => $this->resolveMethodName($label),
            );
        }

        return $known;
    }

    private function resolveConstantName(string $label) : string
    {
        $label = ConvertHelper::transliterate($label);
        $parts = ConvertHelper::explodeTrim('-', $label);
        $parts = ConvertHelper::arrayRemoveValues($parts, array('of', 'the'));

        return 'FACTION_'.strtoupper(implode('_', $parts));
    }

    private function resolveMethodName(string $label) : string
    {
        $label = ConvertHelper::transliterate($label);
        $parts = ConvertHelper::explodeTrim('-', $label);

        return 'get'.implode('', array_map('ucfirst', $parts));
    }
}
