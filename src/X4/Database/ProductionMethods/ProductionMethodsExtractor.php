<?php
/**
 * @package X4 Database
 * @subpackage Production Methods
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\ProductionMethods;

use AppUtils\ConvertHelper;
use AppUtils\FileHelper\FileInfo;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Wares\WaresPatchReader;
use Mistralys\X4\ExtractedData\DataFolder;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

/**
 * Extracts the ware production methods from the `libraries/wares.xml`
 * files into the data file `production-methods.json`.
 *
 * The vanilla methods are the `<method>` children of the root-level
 * `<production>` element. The DLCs add their methods with
 * `<add sel="/wares/production">` patches, which are read via the
 * {@see WaresPatchReader}.
 *
 * A method ID that is defined more than once keeps the definition of
 * the later data folder, and a notice is logged.
 *
 * @package X4 Database
 * @subpackage Production Methods
 */
class ProductionMethodsExtractor
{
    private DataFolders $dataFolders;
    private Language $language;
    private WaresPatchReader $patchReader;

    /**
     * @var array<string,ProductionMethodDef>
     */
    private array $methods = array();

    public function __construct(DataFolders $dataFolders)
    {
        $this->dataFolders = $dataFolders;
        $this->language = Languages::getInstance()->getEnglish();
        $this->patchReader = new WaresPatchReader($dataFolders);
    }

    public function extract() : void
    {
        $methods = $this->collect();

        Console::line1('Found [%d] production methods in total.', count($methods));
        Console::nl();

        $records = array();
        foreach($methods as $method) {
            $records[] = $method->toArray();
        }

        ProductionMethodDefs::getStorageFile()->putData($records);
    }

    /**
     * Collects the methods of all data folders, sorted by ID.
     *
     * @return array<string,ProductionMethodDef>
     */
    public function collect() : array
    {
        $this->methods = array();

        foreach($this->dataFolders->getAll() as $dataFolder) {
            $this->processDataFolder($dataFolder);
        }

        ksort($this->methods);

        return $this->methods;
    }

    private function processDataFolder(DataFolder $dataFolder) : void
    {
        Console::header('Processing data folder [%s]', $dataFolder->getLabel());

        if($dataFolder->isExtension()) {
            foreach($this->patchReader->getPatchesForSelector(WaresPatchReader::SELECTOR_PRODUCTION) as $patch) {
                if($patch->dataFolderID === $dataFolder->getID()) {
                    $this->processElements($patch->element->getChildren(), $dataFolder);
                }
            }

            return;
        }

        $file = FileInfo::factory($dataFolder->getPath().'/libraries/wares.xml');

        if(!$file->exists()) {
            Console::line1('SKIP | No wares file found.');
            return;
        }

        $dom = DOMExtended::createFromFile($file);
        $root = $dom->getDOM()->documentElement;

        if($root === null) {
            return;
        }

        foreach((new ElementExtended($dom, $root))->getChildren() as $child) {
            if($child->getDOMElement()->tagName === 'production') {
                $this->processElements($child->getChildren(), $dataFolder);
            }
        }
    }

    /**
     * @param ElementExtended[] $elements
     */
    private function processElements(array $elements, DataFolder $dataFolder) : void
    {
        foreach($elements as $element) {
            if($element->getDOMElement()->tagName === 'method') {
                $this->processMethod($element, $dataFolder);
            }
        }
    }

    private function processMethod(ElementExtended $element, DataFolder $dataFolder) : void
    {
        $id = (string)$element->getAttribute('id');

        if($id === '') {
            Console::line1('Data folder [%s] | ERROR | Production method element has no ID.', $dataFolder->getID());
            return;
        }

        $label = $this->language->ts((string)$element->getAttribute('name'));
        if($label === '') {
            $label = ucfirst($id);
        }

        $tags = ConvertHelper::explodeTrim(' ', (string)$element->getAttribute('tags'));
        sort($tags);

        $raceIDs = array();
        foreach($element->getChildren() as $child) {
            if($child->getDOMElement()->tagName !== 'default') {
                continue;
            }

            $raceID = (string)$child->getAttribute('race');
            if($raceID !== '') {
                $raceIDs[] = $raceID;
            }
        }

        $raceIDs = array_values(array_unique($raceIDs));
        sort($raceIDs);

        if(isset($this->methods[$id])) {
            Console::line1('Data folder [%s] | NOTICE | Production method [%s] is defined more than once, keeping the later definition.', $dataFolder->getID(), $id);
        }

        $this->methods[$id] = new ProductionMethodDef($id, $label, $tags, $raceIDs);
    }
}
