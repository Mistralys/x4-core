<?php

declare(strict_types=1);

namespace Mistralys\X4;

use AppUtils\FileHelper\FolderInfo;
use Mistralys\X4\Database\Modules\ModuleExtractor;

require_once __DIR__.'/prepend.php';

$extractor = new ModuleExtractor(FolderInfo::factory(X4_EXTRACTED_CAT_FILES_FOLDER.'/assets/structures'));
$extractor->extract();
