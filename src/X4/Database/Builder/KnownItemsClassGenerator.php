<?php
/**
 * @package X4 Database
 * @subpackage Build Tools
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Builder;

use AppUtils\ClassHelper;
use AppUtils\ConvertHelper;
use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\Interfaces\StringPrimaryCollectionInterface;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use AppUtils\StringBuilder;
use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\UI\Console;
use function AppUtils\sb;

/**
 * Build utility: Generates a PHP class that contains constants and methods
 * for all known items of a given collection.
 *
 * @package X4 Database
 * @subpackage Build Tools
 */
class KnownItemsClassGenerator
{
    const PARA_PLACEHOLDER = '__PARA__';
    private string $typeName;

    /**
     * @var class-string<StringPrimaryCollectionInterface>
     */
    private string $collectionClass;

    /**
     * @var class-string<StringPrimaryRecordInterface>
     */
    private string $itemClass;
    private string $namespace;
    private FolderInfo $outputFolder;
    private string $subpackage;
    private string $description = '';

    /**
     * @param class-string<StringPrimaryCollectionInterface> $collectionClass
     * @param class-string<StringPrimaryRecordInterface> $itemClass
     */
    public function __construct(string $collectionClass, string $itemClass, FolderInfo $outputFolder, string $subpackage='')
    {
        $this->namespace = ClassHelper::getClassNamespace($collectionClass);
        $this->collectionClass = $collectionClass;
        $this->itemClass = $itemClass;
        $this->outputFolder = $outputFolder;
        $this->subpackage = $subpackage;

        $parts = explode('\\', $this->namespace);
        $this->typeName = array_pop($parts);
    }

    /**
     * Sets the description that will be used as documentation
     * in the PHPDoc header of the generated class.
     *
     * @param string|StringBuilder $description
     * @return $this
     */
    public function setDescription(string|StringBuilder $description) : self
    {
        $this->description = (string)$description;
        return $this;
    }

    private const CLASS_TEMPLATE = <<<'PHP'
<?php
/**
 * @package X4 Database
 * @subpackage %8$s 
 */

declare(strict_types=1);

namespace %7$s;

/**
 * %9$s
 * 
 * ----
 * 
 * > NOTE: This is an auto-generated class.
 * 
 * @package X4 Database
 * @subpackage %8$s 
 */
class Known%4$s
{
%1$s
    
    public const %5$s = array(
%2$s
    );

    private %6$s $defs;

    private function __construct()
    {
        $this->defs = %6$s::getInstance();
    }

    private static ?Known%4$s $instance = null;

    public static function getInstance() : Known%4$s
    {
        if (!isset(self::$instance)) {
            self::$instance = new Known%4$s();
        }

        return self::$instance;
    }

%3$s
}

PHP;

    private const METHOD_TEMPLATE = <<<'PHP'
    public function %1$s() : %3$s
    {
        return $this->defs->getByID(self::%2$s);
    }
PHP;


    public function generate() : void
    {
        Console::header('Generating known items class for %s', $this->typeName);

        $itemClass = ClassHelper::getClassTypeName($this->itemClass);

        $constants = array();
        $enums = array();
        $methods = array();

        foreach($this->items as $item)
        {
            $constants[] = sprintf(
                '    public const %s = \'%s\';',
                $item['constant'],
                $item['id']
            );

            $enums[] = sprintf(
                '        self::%s',
                $item['constant']
            );

            $methods[] = sprintf(
                self::METHOD_TEMPLATE,
                $item['method'],
                $item['constant'],
                $itemClass
            );
        }

        sort($constants);
        sort($enums);
        sort($methods);

        $php = sprintf(
            self::CLASS_TEMPLATE,
            implode("\n", $constants),
            implode(",\n", $enums),
            implode("\n\n", $methods),
            $this->typeName,
            $this->getListConstantName(),
            ClassHelper::getClassTypeName($this->collectionClass),
            $this->namespace,
            $this->getSubpackage(),
            $this->renderDescription()
        );

        FileInfo::factory(sprintf(
            '%s/Known%s.php',
            $this->outputFolder,
            $this->typeName
        ))
            ->putContents($php);

        Console::line1('Done.');
    }

    private function resolveDescription() : string
    {
        $descr = sb();

        if(!empty($this->description)) {
            $descr->add($this->description);
        }

        $classRef = sprintf('{@see %s}', ClassHelper::getClassTypeName($this->collectionClass));

        return (string)$descr
            ->add(self::PARA_PLACEHOLDER)
            ->t(
                'This utility is meant to be used in tandem with the main collection class %1$s.',
                $this->addDescriptionPlaceholder($classRef)
            )
            ->t('When you want to access items manually in your code, the getter methods are a great help to find what you need.')
            ->add(self::PARA_PLACEHOLDER)
            ->t('## Usage')
            ->add(self::PARA_PLACEHOLDER)
            ->t(
                'Use the method %1$s to get the instance of this class, then call the getter methods to retrieve the items you need.',
                $this->addDescriptionPlaceholder('{@see self::getInstance()}')
            );
    }

    private int $placeholderCounter = 0;

    /**
     * @var array<string,string>
     */
    private array $placeholders = array();

    /**
     * Adds a placeholder for the given text, which will be replaced later
     * in the generated PHPDoc. This is used to avoid the word wrapping
     * to break the given text. A number as long as the text is used to
     * avoid the word wrapping from splitting it.
     *
     * @param string $text The text to be used as a placeholder.
     * @return string The placeholder that will be used in the PHPDoc.
     */
    private function addDescriptionPlaceholder(string $text) : string
    {
        $this->placeholderCounter++;

        // To avoid the reference being broken by the word wrapping,
        // we use a placeholder that will be replaced later.
        $placeholder = sprintf(
            '%02d%s',
            $this->placeholderCounter,
            str_repeat('9', strlen($text)-2)
        );

        $this->placeholders[$placeholder] = $text;

        return $placeholder;
    }

    private function replacePlaceholders(string $text) : string
    {
        return str_replace(
            array_keys($this->placeholders),
            array_values($this->placeholders),
            $text
        );
    }

    private function renderDescription() : string
    {
        // Normalize newlines
        $descr = str_replace("\r\n", "\n", $this->resolveDescription());

        // Mark paragraphs
        $descr = str_replace("\n\n", self::PARA_PLACEHOLDER, $descr);

        // Remove any remaining newlines to let the word wrapping handle them
        $descr = str_replace("\n", ' ', $descr);

        $lines = array();
        $paras = explode(self::PARA_PLACEHOLDER, $descr);
        $total = count($paras);
        $current = 1;
        foreach($paras as $para)
        {
            // Append the lines from this paragraph
            array_push($lines, ...explode("__BREAK__", ConvertHelper::wordwrap(trim($para), 60, "__BREAK__")));

            if($current < $total) {
                // Add a blank line between paragraphs
                $lines[] = '';
            }

            $current++;
        }

        // Compile the PHPDoc lines
        return $this->replacePlaceholders(implode("\n * ", $lines));
    }

    private function getSubpackage() : string
    {
        if(!empty($this->subpackage)) {
            return $this->subpackage;
        }

        return ucwords(str_replace('_', ' ', ConvertHelper::camel2snake($this->typeName)));
    }

    /**
     * Returns the name of the constant that will hold the list of known factions.
     *
     * @return string
     */
    private function getListConstantName() : string
    {
        return strtoupper(ConvertHelper::camel2snake($this->typeName));
    }

    /**
     * @var array<int,array{id:string, constant:string, method:string}>
     */
    private array $items = array();

    public function addItem(string $id, string $label) : self
    {
        $prefix = $this->getListConstantName();
        if(str_ends_with($prefix, 'S')) {
            // Remove the trailing 'S' to avoid double pluralization
            $prefix = substr($prefix, 0, -1);
        }

        $this->items[] = array(
            'id' => $id,
            'constant' => DatabaseBuilder::resolveConstantName($label, $prefix),
            'method' => DatabaseBuilder::resolveMethodName($label),
        );

        return $this;
    }
}
