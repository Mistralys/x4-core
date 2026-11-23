<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Translations;

use AppUtils\Interfaces\IntegerPrimaryRecordInterface;

class Language implements IntegerPrimaryRecordInterface
{
    private int $id;
    private string $locale;

    public function __construct(int $id, string $locale)
    {
        $this->id = $id;
        $this->locale = $locale;
    }

    public function getID(): int
    {
        return $this->id;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    /**
     * Translates a translation code, e.g. <code>{1005,42}</code>.
     *
     * @param string $code
     * @return string
     */
    public function ts(string $code) : string
    {
        return $this->getTranslator()->ts($code);
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
        return $this->getTranslator()->t($pageID, $textID);
    }

    /**
     * Whether the translation code exists in this language.
     *
     * @param string $code E.g. <code>{1005,42}</code>
     * @return bool
     */
    public function hasCode(string $code) : bool
    {
        return $this->getTranslator()->hasCode($code);
    }

    /**
     * Translates a translation code, falling back to another language
     * (English by default) when this language has no entry for it.
     * Never returns the raw <code>{page,id}</code> code: if no language
     * has an entry, or the code is malformed, an empty string is returned.
     *
     * @param string $code E.g. <code>{1005,42}</code>
     * @param Language|null $fallback Defaults to English.
     * @return string
     */
    public function tsWithFallback(string $code, ?Language $fallback = null) : string
    {
        if($this->hasCode($code)) {
            return $this->ts($code);
        }

        $fallback ??= Languages::getInstance()->getEnglish();

        if($fallback->getID() !== $this->getID() && $fallback->hasCode($code)) {
            return $fallback->ts($code);
        }

        return '';
    }

    private ?TranslationDefs $translator = null;

    public function getTranslator() : TranslationDefs
    {
        if(!isset($this->translator)) {
            $this->translator = new TranslationDefs($this->getID());
        }

        return $this->translator;
    }
}
