<?php

declare(strict_types=1);

namespace Mistralys\X4;

use AppUtils\BaseException;
use AppUtils\FileHelper\FolderInfo;
use Mistralys\X4\Database\Translations\TranslationExtractor;
use Mistralys\X4\UI\UserInterface;

require_once __DIR__.'/prepend.php';

header('Content-Type: text/plain');

try
{
    $extractor = new TranslationExtractor(FolderInfo::factory(X4_EXTRACTED_CAT_FILES_FOLDER . '/t'));
    $extractor->selectLanguage(TranslationExtractor::LANGUAGE_ENGLISH);
    $extractor->extract();
}
catch (BaseException $e)
{
    UserInterface::displayException($e);
}
