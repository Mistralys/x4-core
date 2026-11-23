<?php
/**
 * @package X4Tests
 * @subpackage Translations
 * @see \Mistralys\X4\Database\Translations\Language::tsWithFallback()
 */

declare(strict_types=1);

namespace X4Tests\Suites\Translations;

use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;
use X4Tests\Helpers\X4TestCase;

final class LanguageFallbackTests extends X4TestCase
{
    private const CODE_EXISTING = '{1001,1}';
    private const CODE_UNKNOWN = '{999999,999999}';

    public function test_hasCode(): void
    {
        $english = Languages::getInstance()->getEnglish();

        $this->assertTrue($english->hasCode(self::CODE_EXISTING));
        $this->assertFalse($english->hasCode(self::CODE_UNKNOWN));
        $this->assertFalse($english->hasCode('{1001}'));
    }

    public function test_ownTextIsPreferred(): void
    {
        $english = Languages::getInstance()->getEnglish();

        $this->assertSame(
            $english->ts(self::CODE_EXISTING),
            $english->tsWithFallback(self::CODE_EXISTING)
        );
    }

    public function test_missingEntryFallsBackToEnglish(): void
    {
        $english = Languages::getInstance()->getEnglish();

        $this->assertSame(
            $english->ts(self::CODE_EXISTING),
            $this->createLanguageWithoutEntries()->tsWithFallback(self::CODE_EXISTING)
        );
    }

    public function test_explicitFallback(): void
    {
        // A double avoids loading another full language file into memory.
        $fallback = new class(Languages::LANGUAGE_GERMAN, 'de-DE') extends Language {
            public function hasCode(string $code): bool
            {
                return true;
            }

            public function ts(string $code): string
            {
                return 'fallback text';
            }
        };

        $this->assertSame(
            'fallback text',
            $this->createLanguageWithoutEntries()->tsWithFallback(self::CODE_EXISTING, $fallback)
        );
    }

    public function test_noEntryAnywhereReturnsEmptyString(): void
    {
        $english = Languages::getInstance()->getEnglish();

        $this->assertSame('', $english->tsWithFallback(self::CODE_UNKNOWN), 'English is its own fallback: no loop');
        $this->assertSame('', $this->createLanguageWithoutEntries()->tsWithFallback(self::CODE_UNKNOWN));
    }

    public function test_malformedCodeReturnsEmptyString(): void
    {
        $this->assertSame('', Languages::getInstance()->getEnglish()->tsWithFallback('{1001}'));
        $this->assertSame('', $this->createLanguageWithoutEntries()->tsWithFallback('{1001}'));
    }

    /**
     * All shipped languages are complete, so a test double stands in
     * for an incomplete one.
     */
    private function createLanguageWithoutEntries(): Language
    {
        return new class(Languages::LANGUAGE_FRENCH, 'fr-FR') extends Language {
            public function hasCode(string $code): bool
            {
                return false;
            }
        };
    }
}
