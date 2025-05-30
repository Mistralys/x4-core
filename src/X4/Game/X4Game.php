<?php

declare(strict_types=1);

namespace Mistralys\X4\Game;

use AppUtils\FileHelper\FileInfo;
use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper\PathInfoInterface;
use AppUtils\FileHelper_Exception;
use SplFileInfo;

class X4Game
{
    private FolderInfo $gameFolder;

    public function __construct(FolderInfo $gameFolder)
    {
        $this->gameFolder = $gameFolder;
    }

    /**
     * @param string|PathInfoInterface|SplFileInfo $gameFolder
     * @return self
     * @throws FileHelper_Exception
     */
    public static function create($gameFolder) : self
    {
        return new self(FolderInfo::factory($gameFolder)->requireExists());
    }

    private ?string $version = null;

    public function getVersion() : string
    {
        if ($this->version === null) {
            $this->version = $this->detectVersion();
        }

        return $this->version;
    }

    private function detectVersion() : string
    {
        $version = (int)FileInfo::factory($this->gameFolder.'/version.dat')
            ->getContents();

        // Versions are 760, for example, but we want to return 7.60
        return (string)($version / 100);
    }
}
