<?php

declare(strict_types=1);

namespace X4Tests\Suites\Translations;

use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\Translations\Languages;
use Mistralys\X4\Database\Translations\TranslationDefs;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use X4Tests\Helpers\X4TestCase;

final class TranslationTests extends X4TestCase
{
    public function test_translate() : void
    {
        $testID = '{20101,20604}';

        $en = new TranslationDefs(Languages::LANGUAGE_ENGLISH);

        $this->assertSame('Manorina (Gas) Vanguard', $en->ts($testID));
    }

    public static function setUpBeforeClass(): void
    {
        $extractor = new TranslationExtractor(DatabaseBuilder::getDataFolders());
        $extractor->selectLanguage(Languages::LANGUAGE_ENGLISH);
        $extractor->extract();
    }
}
