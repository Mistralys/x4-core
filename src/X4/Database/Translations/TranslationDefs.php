<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Translations;

use AppUtils\ConvertHelper;
use AppUtils\FileHelper\JSONFile;

class TranslationDefs
{
    private bool $loaded = false;
    private array $texts = array();
    private JSONFile $storageFile;

    public function __construct(int $languageID)
    {
        $this->storageFile = TranslationExtractor::getLanguageFile($languageID);
    }

    public function exists() : bool
    {
        return $this->storageFile->exists();
    }
    public function getStorageFile() : JSONFile
    {
        return $this->storageFile;
    }

    /**
     * Translates a translation code, e.g. <code>{1005,42}</code>.
     *
     * @param string $code
     * @return string
     */
    public function ts(string $code) : string
    {
        $parsed = trim($code, '{}');
        $parts = ConvertHelper::explodeTrim(',', $parsed);

        if(count($parts) === 2) {
            return $this->t((int)$parts[0], (int)$parts[1]);
        }

        return '';
    }

    /**
     * Translates a text ID, e.g. <code>t(1005,42)</code>.
     *
     * @param int $pageID
     * @param int $textID
     * @return string
     */
    public function t(int $pageID, int $textID) : string
    {
        $this->load();

        return $this->texts[$pageID][$textID] ?? '{'.$pageID.','.$textID.'}';
    }

    private function load() : void
    {
        if($this->loaded === true) {
            return;
        }

        $this->loaded = true;

        $this->texts = $this->getStorageFile()->parse();
    }
}
