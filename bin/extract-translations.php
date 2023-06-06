<?php

declare(strict_types=1);

namespace Mistralys\X4;

use AppUtils\FileHelper\FolderInfo;
use Mistralys\X4\Database\Translations\TranslationExtractor;

require_once __DIR__.'/prepend.php';

$extractor = new TranslationExtractor(FolderInfo::factory(X4_EXTRACTED_CAT_FILES_FOLDER.'/t'));
$extractor->selectLanguage(TranslationExtractor::LANGUAGE_ENGLISH);
$extractor->extract();
