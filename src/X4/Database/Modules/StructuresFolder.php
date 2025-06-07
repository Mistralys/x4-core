<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\FileHelper\FolderInfo;
use Mistralys\X4\ExtractedData\DataFolder;

class StructuresFolder
{
    private DataFolder $dataFolder;
    private FolderInfo $folder;

    public function __construct(DataFolder $dataFolder, FolderInfo $folder)
    {
        $this->dataFolder = $dataFolder;
        $this->folder = $folder;
    }

    public function getDataFolder() : DataFolder
    {
        return $this->dataFolder;
    }

    public function getFolder() : FolderInfo
    {
        return $this->folder;
    }
}
