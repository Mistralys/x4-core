<?php
/**
 * @package X4Tests
 * @subpackage Translations
 * @see \Mistralys\X4\Database\Translations\TranslationDefs
 */

declare(strict_types=1);

namespace X4Tests\Suites\Translations;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Translations\TranslationDefs;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use X4Tests\Helpers\X4TestCase;

/**
 * Detailed tests for TranslationDefs - the translation service class
 * that loads and provides translations for a specific language.
 *
 * @package X4Tests
 * @subpackage Translations
 */
final class TranslationDefsTests extends X4TestCase
{
    private static bool $allLanguagesExtracted = false;

    /**
     * Test that TranslationDefs can be instantiated for each language
     */
    public function test_instantiation(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $this->assertInstanceOf(TranslationDefs::class, $translator);
    }

    /**
     * Test exists() method returns true for extracted languages
     */
    public function test_exists_true(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $this->assertTrue($translator->exists(), 'English translation file should exist');
    }

    /**
     * Test exists() method returns false for non-extracted language
     */
    public function test_exists_false(): void
    {
        // Use a language ID that definitely does not exist
        $translator = new TranslationDefs(99999);
        
        $this->assertFalse($translator->exists(), 'Non-extracted language should return false');
    }

    /**
     * Test getStorageFile() returns valid JSONFile
     */
    public function test_getStorageFile(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $file = $translator->getStorageFile();

        $this->assertInstanceOf(\AppUtils\FileHelper\JSONFile::class, $file);
    }

    /**
     * Test getStorageFile() path format
     */
    public function test_getStorageFile_pathFormat(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $file = $translator->getStorageFile();
        $path = $file->getPath();

        $this->assertStringContainsString('lang-044-en_EN.json', $path);
    }

    /**
     * Test ts() with valid translation code
     */
    public function test_ts_validCode(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('{1001,1}');

        $this->assertNotEmpty($result);
        $this->assertNotSame('{1001,1}', $result);
    }

    /**
     * Test ts() with code without braces - actually works due to trim
     */
    public function test_ts_noBraces(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('1001,1');

        // The method trims braces, so '1001,1' becomes '1001,1' and parses successfully
        $this->assertNotEmpty($result);
        $this->assertNotSame('{1001,1}', $result);
    }

    /**
     * Test ts() with empty string
     */
    public function test_ts_emptyString(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('');

        $this->assertSame('', $result);
    }

    /**
     * Test ts() with malformed code (no comma)
     */
    public function test_ts_malformedCode_noComma(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('{1001}');

        $this->assertSame('', $result);
    }

    /**
     * Test ts() with malformed code (too many parts)
     */
    public function test_ts_malformedCode_tooManyParts(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('{1001,1,extra}');

        $this->assertSame('', $result);
    }

    /**
     * Test ts() with non-numeric values
     */
    public function test_ts_nonNumericValues(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('{abc,def}');

        // Should convert to 0,0 and return fallback
        $this->assertSame('{0,0}', $result);
    }

    /**
     * Test t() with valid page and text IDs
     */
    public function test_t_validIDs(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->t(1001, 1);

        $this->assertNotEmpty($result);
        $this->assertNotSame('{1001,1}', $result);
    }

    /**
     * Test t() returns fallback for missing translation
     */
    public function test_t_missingTranslation(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->t(999999, 999999);

        $this->assertSame('{999999,999999}', $result);
    }

    /**
     * Test t() with zero IDs
     */
    public function test_t_zeroIDs(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->t(0, 0);

        $this->assertSame('{0,0}', $result);
    }

    /**
     * Test t() with negative IDs
     */
    public function test_t_negativeIDs(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->t(-1, -1);

        $this->assertSame('{-1,-1}', $result);
    }

    /**
     * Test ts() handles spaces around comma
     */
    public function test_ts_spacesAroundComma(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('{1001 , 1}');

        $this->assertNotEmpty($result);
        $this->assertNotSame('{1001 , 1}', $result);
    }

    /**
     * Test ts() handles multiple spaces
     */
    public function test_ts_multipleSpaces(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('{  1001  ,  1  }');

        $this->assertNotEmpty($result);
    }

    /**
     * Test that translation works for all 7 languages
     */
    public function test_allLanguagesCanTranslate(): void
    {
        $this->ensureAllLanguagesExtracted();

        // Test a common translation that should exist in all languages
        $testCode = '{1001,1}'; // "Hull"

        foreach (Languages::LANGUAGES as $langID => $locale) {
            $translator = new TranslationDefs($langID);
            $result = $translator->ts($testCode);

            $this->assertNotEmpty(
                $result,
                sprintf('Language %d (%s) should have translation for %s', $langID, $locale, $testCode)
            );
        }
    }

    /**
     * Test that multiple TranslationDefs instances can coexist
     */
    public function test_multipleInstances(): void
    {
        $this->ensureAllLanguagesExtracted();

        $english = new TranslationDefs(Languages::LANGUAGE_ENGLISH);
        $german = new TranslationDefs(Languages::LANGUAGE_GERMAN);

        $testCode = '{1001,1}';

        $enResult = $english->ts($testCode);
        $deResult = $german->ts($testCode);

        $this->assertNotEmpty($enResult);
        $this->assertNotEmpty($deResult);
        // Results should be different (different languages)
        // Note: We can't assert they're different because we don't know for certain
        // that the translation differs, but we can assert both work
    }

    /**
     * Test that lazy loading works - translations loaded on first access
     */
    public function test_lazyLoading(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        // Create instance but don't access translations yet
        $this->assertInstanceOf(TranslationDefs::class, $translator);

        // Now access translation - this should trigger loading
        $result = $translator->t(1001, 1);

        $this->assertNotEmpty($result);
    }

    /**
     * Test that subsequent calls don't reload data
     */
    public function test_translationsCached(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result1 = $translator->t(1001, 1);
        $result2 = $translator->t(1001, 1);

        $this->assertSame($result1, $result2, 'Should return same result from cache');
    }

    /**
     * Test translation with large page/text IDs
     */
    public function test_t_largeIDs(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        // Use known ship name with large IDs
        $result = $translator->t(20101, 20604);

        $this->assertSame('Manorina (Gas) Vanguard', $result);
    }

    /**
     * Test different valid translation codes
     */
    public function test_ts_variousValidCodes(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $codes = [
            '{1001,1}',
            '{1001,2}',
            '{1001,3}',
            '{20101,20604}',
        ];

        foreach ($codes as $code) {
            $result = $translator->ts($code);
            $this->assertNotEmpty($result, sprintf('Translation for %s should not be empty', $code));
            $this->assertNotSame($code, $result, sprintf('Should translate %s to actual text', $code));
        }
    }

    /**
     * Test that invalid language ID still creates valid instance
     */
    public function test_invalidLanguageID(): void
    {
        $translator = new TranslationDefs(999);

        $this->assertInstanceOf(TranslationDefs::class, $translator);
        $this->assertFalse($translator->exists(), 'Invalid language should not exist');
    }

    /**
     * Test ts() with only opening brace - actually works due to trim
     */
    public function test_ts_onlyOpeningBrace(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('{1001,1');

        // The method trims '{}' characters, so '{1001,1' becomes '1001,1' and parses
        $this->assertNotEmpty($result);
        $this->assertNotSame('{1001,1}', $result);
    }

    /**
     * Test ts() with only closing brace - actually works due to trim
     */
    public function test_ts_onlyClosingBrace(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('1001,1}');

        // The method trims '{}' characters, so '1001,1}' becomes '1001,1' and parses
        $this->assertNotEmpty($result);
        $this->assertNotSame('{1001,1}', $result);
    }

    /**
     * Test ts() with nested braces - outer braces trimmed, inner ones parsed
     */
    public function test_ts_nestedBraces(): void
    {
        $translator = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $result = $translator->ts('{{1001,1}}');

        // After trim, becomes '{1001,1}' which then gets trimmed again to '1001,1' and parses
        $this->assertNotEmpty($result);
        $this->assertNotSame('{{1001,1}}', $result);
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
