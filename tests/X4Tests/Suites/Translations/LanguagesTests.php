<?php
/**
 * @package X4Tests
 * @subpackage Translations
 * @see \Mistralys\X4\Database\Translations\Languages
 */

declare(strict_types=1);

namespace X4Tests\Suites\Translations;

use AppUtils\Collections\RecordNotExistsException;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use X4Tests\Helpers\X4TestCase;

/**
 * Tests for the Languages collection which manages all available
 * game languages (English, German, French, Spanish, Italian, Russian, Korean).
 *
 * @package X4Tests
 * @subpackage Translations
 */
final class LanguagesTests extends X4TestCase
{
    private function getLanguages(): Languages
    {
        return Languages::getInstance();
    }

    /**
     * Test that getInstance returns a singleton instance
     */
    public function test_getInstance(): void
    {
        $instance1 = Languages::getInstance();
        $instance2 = Languages::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    /**
     * Test that getAll returns all 7 languages
     */
    public function test_getAll(): void
    {
        $languages = $this->getLanguages()->getAll();

        $this->assertCount(7, $languages, 'Should have exactly 7 languages');

        // Verify all items are Language instances
        foreach ($languages as $language) {
            $this->assertInstanceOf(Language::class, $language);
        }
    }

    /**
     * Test that getByID retrieves a specific language
     */
    public function test_getByID(): void
    {
        $language = $this->getLanguages()->getByID(Languages::LANGUAGE_ENGLISH);

        $this->assertInstanceOf(Language::class, $language);
        $this->assertSame(Languages::LANGUAGE_ENGLISH, $language->getID());
        $this->assertSame('en_EN', $language->getLocale());
    }

    /**
     * Test that getByID throws exception for invalid ID
     */
    public function test_getByID_invalid(): void
    {
        $this->expectException(RecordNotExistsException::class);
        $this->getLanguages()->getByID(999);
    }

    /**
     * Test getEnglish() factory method
     */
    public function test_getEnglish(): void
    {
        $english = $this->getLanguages()->getEnglish();

        $this->assertInstanceOf(Language::class, $english);
        $this->assertSame(Languages::LANGUAGE_ENGLISH, $english->getID());
        $this->assertSame('en_EN', $english->getLocale());
    }

    /**
     * Test getGerman() factory method
     */
    public function test_getGerman(): void
    {
        $german = $this->getLanguages()->getGerman();

        $this->assertInstanceOf(Language::class, $german);
        $this->assertSame(Languages::LANGUAGE_GERMAN, $german->getID());
        $this->assertSame('de_DE', $german->getLocale());
    }

    /**
     * Test getFrench() factory method
     */
    public function test_getFrench(): void
    {
        $french = $this->getLanguages()->getFrench();

        $this->assertInstanceOf(Language::class, $french);
        $this->assertSame(Languages::LANGUAGE_FRENCH, $french->getID());
        $this->assertSame('fr_FR', $french->getLocale());
    }

    /**
     * Test getSpanish() factory method
     */
    public function test_getSpanish(): void
    {
        $spanish = $this->getLanguages()->getSpanish();

        $this->assertInstanceOf(Language::class, $spanish);
        $this->assertSame(Languages::LANGUAGE_SPANISH, $spanish->getID());
        $this->assertSame('es_ES', $spanish->getLocale());
    }

    /**
     * Test getItalian() factory method
     */
    public function test_getItalian(): void
    {
        $italian = $this->getLanguages()->getItalian();

        $this->assertInstanceOf(Language::class, $italian);
        $this->assertSame(Languages::LANGUAGE_ITALIAN, $italian->getID());
        $this->assertSame('it_IT', $italian->getLocale());
    }

    /**
     * Test getRussian() factory method
     */
    public function test_getRussian(): void
    {
        $russian = $this->getLanguages()->getRussian();

        $this->assertInstanceOf(Language::class, $russian);
        $this->assertSame(Languages::LANGUAGE_RUSSIAN, $russian->getID());
        $this->assertSame('ru_RU', $russian->getLocale());
    }

    /**
     * Test getKorean() factory method
     */
    public function test_getKorean(): void
    {
        $korean = $this->getLanguages()->getKorean();

        $this->assertInstanceOf(Language::class, $korean);
        $this->assertSame(Languages::LANGUAGE_COREAN, $korean->getID());
        $this->assertSame('ko_KR', $korean->getLocale());
    }

    /**
     * Test all language constants exist
     */
    public function test_allLanguageConstants(): void
    {
        $this->assertSame(44, Languages::LANGUAGE_ENGLISH);
        $this->assertSame(49, Languages::LANGUAGE_GERMAN);
        $this->assertSame(33, Languages::LANGUAGE_FRENCH);
        $this->assertSame(34, Languages::LANGUAGE_SPANISH);
        $this->assertSame(39, Languages::LANGUAGE_ITALIAN);
        $this->assertSame(7, Languages::LANGUAGE_RUSSIAN);
        $this->assertSame(82, Languages::LANGUAGE_COREAN);
    }

    /**
     * Test LANGUAGES constant contains all language mappings
     */
    public function test_LANGUAGES_constant(): void
    {
        $languages = Languages::LANGUAGES;

        $this->assertCount(7, $languages);
        $this->assertArrayHasKey(Languages::LANGUAGE_ENGLISH, $languages);
        $this->assertArrayHasKey(Languages::LANGUAGE_GERMAN, $languages);
        $this->assertArrayHasKey(Languages::LANGUAGE_FRENCH, $languages);
        $this->assertArrayHasKey(Languages::LANGUAGE_SPANISH, $languages);
        $this->assertArrayHasKey(Languages::LANGUAGE_ITALIAN, $languages);
        $this->assertArrayHasKey(Languages::LANGUAGE_RUSSIAN, $languages);
        $this->assertArrayHasKey(Languages::LANGUAGE_COREAN, $languages);

        $this->assertSame('en_EN', $languages[Languages::LANGUAGE_ENGLISH]);
        $this->assertSame('de_DE', $languages[Languages::LANGUAGE_GERMAN]);
        $this->assertSame('fr_FR', $languages[Languages::LANGUAGE_FRENCH]);
        $this->assertSame('es_ES', $languages[Languages::LANGUAGE_SPANISH]);
        $this->assertSame('it_IT', $languages[Languages::LANGUAGE_ITALIAN]);
        $this->assertSame('ru_RU', $languages[Languages::LANGUAGE_RUSSIAN]);
        $this->assertSame('ko_KR', $languages[Languages::LANGUAGE_COREAN]);
    }

    /**
     * Test DEFAULT_LANGUAGE constant is English
     */
    public function test_DEFAULT_LANGUAGE_constant(): void
    {
        $this->assertSame(Languages::LANGUAGE_ENGLISH, Languages::DEFAULT_LANGUAGE);
    }

    /**
     * Test getDefault() returns English
     */
    public function test_getDefault(): void
    {
        $default = $this->getLanguages()->getDefault();

        $this->assertInstanceOf(Language::class, $default);
        $this->assertSame(Languages::LANGUAGE_ENGLISH, $default->getID());
    }

    /**
     * Test getDefaultID() returns English ID
     */
    public function test_getDefaultID(): void
    {
        $defaultID = $this->getLanguages()->getDefaultID();

        $this->assertSame(Languages::LANGUAGE_ENGLISH, $defaultID);
    }

    /**
     * Test that all languages have valid IDs and locales
     */
    public function test_allLanguagesHaveValidData(): void
    {
        $languages = $this->getLanguages()->getAll();

        foreach ($languages as $language) {
            $this->assertGreaterThan(0, $language->getID(), 'Language ID should be positive');
            $this->assertNotEmpty($language->getLocale(), 'Language locale should not be empty');
            $this->assertMatchesRegularExpression(
                '/^[a-z]{2}_[A-Z]{2}$/',
                $language->getLocale(),
                'Locale should match format: xx_XX'
            );
        }
    }

    /**
     * Test that all getter methods return the correct language
     */
    public function test_allGettersReturnCorrectLanguage(): void
    {
        $languages = $this->getLanguages();

        $getters = [
            'getEnglish' => [Languages::LANGUAGE_ENGLISH, 'en_EN'],
            'getGerman' => [Languages::LANGUAGE_GERMAN, 'de_DE'],
            'getFrench' => [Languages::LANGUAGE_FRENCH, 'fr_FR'],
            'getSpanish' => [Languages::LANGUAGE_SPANISH, 'es_ES'],
            'getItalian' => [Languages::LANGUAGE_ITALIAN, 'it_IT'],
            'getRussian' => [Languages::LANGUAGE_RUSSIAN, 'ru_RU'],
            'getKorean' => [Languages::LANGUAGE_COREAN, 'ko_KR'],
        ];

        foreach ($getters as $method => [$expectedID, $expectedLocale]) {
            $language = $languages->$method();
            
            $this->assertSame(
                $expectedID,
                $language->getID(),
                sprintf('%s() should return language with ID %d', $method, $expectedID)
            );
            $this->assertSame(
                $expectedLocale,
                $language->getLocale(),
                sprintf('%s() should return language with locale %s', $method, $expectedLocale)
            );
        }
    }

    /**
     * Test Language::getID() method
     */
    public function test_Language_getID(): void
    {
        $english = $this->getLanguages()->getEnglish();

        $id = $english->getID();

        $this->assertIsInt($id);
        $this->assertSame(Languages::LANGUAGE_ENGLISH, $id);
    }

    /**
     * Test Language::getLocale() method
     */
    public function test_Language_getLocale(): void
    {
        $english = $this->getLanguages()->getEnglish();

        $locale = $english->getLocale();

        $this->assertIsString($locale);
        $this->assertSame('en_EN', $locale);
    }

    /**
     * Test Language::getTranslator() method returns TranslationDefs
     */
    public function test_Language_getTranslator(): void
    {
        $english = $this->getLanguages()->getEnglish();

        $translator = $english->getTranslator();

        $this->assertInstanceOf(\Mistralys\X4\Database\Translations\TranslationDefs::class, $translator);
    }

    /**
     * Test that each Language instance has unique ID
     */
    public function test_allLanguagesHaveUniqueIDs(): void
    {
        $languages = $this->getLanguages()->getAll();
        $ids = [];

        foreach ($languages as $language) {
            $id = $language->getID();
            $this->assertNotContains($id, $ids, sprintf('Language ID %d should be unique', $id));
            $ids[] = $id;
        }

        $this->assertCount(7, $ids, 'Should have 7 unique language IDs');
    }

    /**
     * Test that each Language instance has unique locale
     */
    public function test_allLanguagesHaveUniqueLocales(): void
    {
        $languages = $this->getLanguages()->getAll();
        $locales = [];

        foreach ($languages as $language) {
            $locale = $language->getLocale();
            $this->assertNotContains($locale, $locales, sprintf('Language locale %s should be unique', $locale));
            $locales[] = $locale;
        }

        $this->assertCount(7, $locales, 'Should have 7 unique language locales');
    }
}
