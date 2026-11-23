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
        $ids = $this->parseCode($code);

        if($ids !== null) {
            return $this->t($ids[0], $ids[1]);
        }

        return '';
    }

    /**
     * Whether an entry exists for the translation code, e.g.
     * <code>{1005,42}</code>. Unlike {@see self::ts()}, this does not
     * fall back to a placeholder: a malformed code, an unknown page
     * or an unknown text ID all return false.
     *
     * @param string $code
     * @return bool
     */
    public function hasCode(string $code) : bool
    {
        $ids = $this->parseCode($code);

        if($ids === null) {
            return false;
        }

        $this->load();

        return isset($this->texts[$ids[0]][$ids[1]]);
    }

    /**
     * Splits a translation code into its page and text IDs.
     *
     * @param string $code
     * @return array{0:int,1:int}|null NULL if the code is malformed.
     */
    private function parseCode(string $code) : ?array
    {
        $parts = ConvertHelper::explodeTrim(',', trim($code, '{}'));

        if(count($parts) === 2) {
            return array((int)$parts[0], (int)$parts[1]);
        }

        return null;
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
