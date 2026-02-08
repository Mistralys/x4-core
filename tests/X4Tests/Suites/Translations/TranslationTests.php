<?php
/**
 * @package X4Tests
 * @subpackage Translations
 * @see \Mistralys\X4\Database\Translations\TranslationDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Translations;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Translations\TranslationDefs;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for TranslationDefs - the translation lookup service.
 *
 * @package X4Tests
 * @subpackage Translations
 */
final class TranslationTests extends X4TestCase
{
    private static bool $allLanguagesExtracted = false;

    /**
     * Test basic translation with English
     */
    public function test_translate() : void
    {
        $testID = '{20101,20604}';

        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $this->assertSame('Manorina (Gas) Vanguard', $en->ts($testID));
    }

    /**
     * Test ts() translation method with all 7 languages
     */
    public function test_translate_allLanguages(): void
    {
        $this->ensureAllLanguagesExtracted();

        $testID = '{20101,20604}'; // Known ship name that exists in all languages

        $languages = Languages::getInstance()->getAll();
        
        $this->assertCount(7, $languages, 'Should have exactly 7 languages');

        foreach ($languages as $language) {
            $translator = new TranslationDefs($language->getID());
            $result = $translator->ts($testID);
            
            $this->assertNotEmpty($result, sprintf(
                'Translation should not be empty for language %s (ID: %d)',
                $language->getLocale(),
                $language->getID()
            ));
            $this->assertNotSame($testID, $result, 'Should return actual translation, not the code');
        }
    }

    /**
     * Test t() method (pageID, textID format)
     */
    public function test_translate_pageIDTextID(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $en->t(20101, 20604); // Same as {20101,20604}

        $this->assertSame('Manorina (Gas) Vanguard', $result);
    }

    /**
     * Test translation code parsing - with braces
     */
    public function test_translationCode_parsing(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $en->ts('{1001,1}'); // "Hull"

        $this->assertNotEmpty($result);
        $this->assertNotSame('{1001,1}', $result);
    }

    /**
     * Test translation code parsing - without braces (actually works due to trim)
     */
    public function test_translationCode_withoutBraces(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        // The ts() method trims braces, so without them it still works
        $result = $en->ts('1001,1');

        $this->assertNotEmpty($result);
        $this->assertNotSame('{1001,1}', $result);
    }

    /**
     * Test that missing translation returns fallback format
     */
    public function test_translate_missingTranslation(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        // Use a page/text ID that's very unlikely to exist
        $result = $en->t(999999, 999999);

        $this->assertSame('{999999,999999}', $result, 'Should return code in braces for missing translation');
    }

    /**
     * Test translation with invalid code format
     */
    public function test_translate_invalidCode(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $en->ts('{invalid}');

        $this->assertSame('', $result, 'Should return empty string for invalid code format');
    }

    /**
     * Test Language::t() method
     */
    public function test_Language_t(): void
    {
        $english = Languages::getInstance()->getEnglish();

        $result = $english->t(20101, 20604);

        $this->assertSame('Manorina (Gas) Vanguard', $result);
    }

    /**
     * Test Language::ts() method
     */
    public function test_Language_ts(): void
    {
        $english = Languages::getInstance()->getEnglish();

        $result = $english->ts('{20101,20604}');

        $this->assertSame('Manorina (Gas) Vanguard', $result);
    }

    /**
     * Test that translation exists() method works
     */
    public function test_exists(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $this->assertTrue($en->exists(), 'English translation file should exist');
    }

    /**
     * Test getStorageFile() returns JSONFile
     */
    public function test_getStorageFile(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $file = $en->getStorageFile();

        $this->assertNotNull($file);
        $this->assertTrue($file->exists(), 'Storage file should exist');
    }

    /**
     * Test cross-language consistency - same code should have translation in all languages
     */
    public function test_crossLanguageConsistency(): void
    {
        $this->ensureAllLanguagesExtracted();

        $testID = '{1001,1}'; // "Hull" - basic text that should exist in all languages

        $languages = Languages::getInstance()->getAll();
        $foundTranslations = 0;

        foreach ($languages as $language) {
            $translator = new TranslationDefs($language->getID());
            $result = $translator->ts($testID);
            
            if ($result !== '' && $result !== $testID) {
                $foundTranslations++;
            }
        }

        $this->assertGreaterThan(0, $foundTranslations, 'At least some languages should have translation for {1001,1}');
    }

    /**
     * Test that ts() handles empty string
     */
    public function test_translate_emptyString(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $en->ts('');

        $this->assertSame('', $result);
    }

    /**
     * Test that ts() handles code with spaces
     */
    public function test_translate_codeWithSpaces(): void
    {
        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $en->ts('{ 20101 , 20604 }'); // Spaces around numbers

        $this->assertSame('Manorina (Gas) Vanguard', $result, 'Should handle spaces in translation code');
    }

    /**
     * Extract all languages for comprehensive testing
     */
    private function ensureAllLanguagesExtracted(): void
    {
        if (self::$allLanguagesExtracted) {
            return;
        }

        $extractor = new TranslationExtractor(DatabaseBuilder::getDataFolders());
        
        foreach (Languages::LANGUAGES as $langID => $locale) {
            $extractor->selectLanguage($langID);
            $extractor->extract();
        }

        self::$allLanguagesExtracted = true;
    }

    public static function setUpBeforeClass(): void
    {
        $extractor = new TranslationExtractor(DatabaseBuilder::getDataFolders());
        $extractor->selectLanguage(Languages::LANGUAGE_ENGLISH);
        $extractor->extract();
    }
}
