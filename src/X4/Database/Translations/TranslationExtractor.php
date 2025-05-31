<?php


declare(strict_types=1);

namespace Mistralys\X4\Database\Translations;

use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper\JSONFile;
use DOMDocument;
use DOMElement;

class TranslationExtractor
{
    public const LANGUAGE_RUSSIAN = 7;
    public const LANGUAGE_FRENCH = 33;
    public const LANGUAGE_SPANISH = 34;
    public const LANGUAGE_COREAN = 82;
    public const LANGUAGE_GERMAN = 49;
    public const LANGUAGE_ENGLISH = 44;
    public const LANGUAGE_ITALIAN = 39;

    public const LANGUAGES = array(
        self::LANGUAGE_RUSSIAN => 'ru_RU',
        self::LANGUAGE_FRENCH => 'fr_FR',
        self::LANGUAGE_SPANISH => 'es_ES',
        self::LANGUAGE_COREAN => 'ko_KR',
        self::LANGUAGE_GERMAN => 'de_DE',
        self::LANGUAGE_ENGLISH => 'en_EN',
        self::LANGUAGE_ITALIAN => 'it_IT'
    );

    public const ERROR_TRANSLATION_FILE_NOT_FOUND = 138501;

    private FolderInfo $folder;

    /**
     * @var int[]
     */
    private array $languageIDs = array();
    private array $texts = array();

    public function __construct(FolderInfo $translationsFolder)
    {
        $this->folder = $translationsFolder;
    }

    public function selectLanguage(int $langID) : self
    {
        if(!in_array($langID, $this->languageIDs, true)) {
            $this->languageIDs[] = $langID;
        }

        return $this;
    }

    public function langExists(int $lang) : bool
    {
        return self::getLanguageFile($lang)->exists();
    }

    public function extract() : void
    {
        echo 'Extracting languages.'.PHP_EOL;

        foreach($this->languageIDs as $languageID) {
            $this->extractLanguage($languageID);
        }
    }

    private function extractLanguage(int $languageID) : void
    {
        echo '- Processing language #'.$languageID.' ('.self::LANGUAGES[$languageID].')...'.PHP_EOL;

        $file = FileInfo::factory(sprintf(
            '%s/0001-l%03d.xml',
            $this->folder,
            $languageID
        ));

        if(!$file->exists()) {
            throw new TranslationException(
                'Translation file not found.',
                sprintf(
                    'Language ID: [%s] '.PHP_EOL.
                    'Target file: [%s]',
                    $languageID,
                    $file
                ),
                self::ERROR_TRANSLATION_FILE_NOT_FOUND
            );
        }

        $dom = new DOMDocument();
        $dom->loadXML($file->getContents());

        $pages = $dom->getElementsByTagName('page');
        $this->texts = array();

        foreach($pages as $page)
        {
            if($page instanceof DOMElement) {
                $this->parsePage($page);
            }
        }

        $this->parseTexts();

        self::getLanguageFile($languageID)->putData($this->texts, true);

        echo 'OK'.PHP_EOL;
    }

    private function parseTexts() : void
    {
        foreach($this->texts as $pageID => $texts)
        {
            foreach($texts as $textID => $text)
            {
                $this->parseText($pageID, $textID, $text);
            }
        }
    }

    private function parseText(int $pageID, int $textID, string $text) : void
    {
        $this->texts[$pageID][$textID] = $this->replaceNestedTranslations($text);
    }

    private function replaceNestedTranslations(string $text) : string
    {
        // Text contains comments for translators
        if(str_contains($text, '('))
        {
            $text = $this->removeComments($text);
        }

        // Detect nested translation strings that must be replaced with translations
        preg_match_all('/{([0-9]+),([0-9]+)}/x', $text, $matches);

        if(empty($matches[0]))
        {
            return $text;
        }

        foreach ($matches[0] as $idx => $matchedText)
        {
            $subPage = (int)$matches[1][$idx];
            $subText = (int)$matches[2][$idx];

            if(isset($this->texts[$subPage][$subText]))
            {
                $text = str_replace(
                    $matchedText,
                    $this->texts[$subPage][$subText],
                    $text
                );
            }
            else
            {
                echo '- Reference not found: '.$matchedText.PHP_EOL;
                $text = str_replace($matchedText, '', $text);
            }
        }

        return $this->replaceNestedTranslations($text);
    }

    private function removeComments(string $text) : string
    {
        $orig = $text;

        $text = str_replace('\(', '__OPQUOT__', $text);
        $text = str_replace('\)', '__CLQUOT__', $text);

        preg_match_all('/\([^)]+\)/x', $text, $matches);

        foreach($matches[0] as $matchedText) {
            $text = str_replace($matchedText, '', $text);
        }

        $text = str_replace('__OPQUOT__', '(', $text);
        $text = str_replace('__CLQUOT__', ')', $text);

        print_r(array($orig, $text));

        return $text;
    }

    public static function getLanguageFile(int $languageID) : JSONFile
    {
        return JSONFile::factory(sprintf(
            __DIR__.'/../../../../data/lang-%03d-%s.json',
            $languageID,
            self::LANGUAGES[$languageID]
        ));
    }

    private function parsePage(DOMElement $page) : void
    {
        $id = (int)$page->getAttribute('id');

        foreach($page->childNodes as $node)
        {
            if($node instanceof DOMElement && $node->nodeName === 't')
            {
                $this->registerText($id, (int)$node->getAttribute('id'), $node->textContent);
            }
        }
    }

    private function registerText(int $pageID, int $textID, string $text) : void
    {
        if(!isset($this->texts[$pageID]))
        {
            $this->texts[$pageID] = array();
        }

        $this->texts[$pageID][$textID] = $text;
    }
}
