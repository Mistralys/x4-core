<?php

declare(strict_types=1);

namespace X4Tests\Suites\Translations;

use AppUtils\FileHelper\FolderInfo;
use Mistralys\X4\Database\Translations\TranslationDefs;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use X4Tests\Helpers\X4TestCase;
use const Mistralys\X4\X4_EXTRACTED_CAT_FILES_FOLDER;

final class TranslationTests extends X4TestCase
{
    public function test_extract() : void
    {
        $extractor = new TranslationExtractor(FolderInfo::factory(X4_EXTRACTED_CAT_FILES_FOLDER . '/t'));

        if(!$extractor->langExists(TranslationExtractor::LANGUAGE_ENGLISH)) {
            $extractor->selectLanguage(TranslationExtractor::LANGUAGE_ENGLISH);
            $extractor->extract();
        }

        $this->addToAssertionCount(1);
    }

    public function test_translate() : void
    {
        $testID = '{20101,20604}';

        $en = new TranslationDefs(TranslationExtractor::LANGUAGE_ENGLISH);

        $this->assertSame('Manorina (Gas) Vanguard', $en->ts($testID));
    }
}
