<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Translations;

use AppUtils\Collections\BaseIntegerPrimaryCollection;

/**
 * @method Language getByID(int $id)
 * @method Language[] getAll()
 * @method Language getDefault()
 */
class Languages extends BaseIntegerPrimaryCollection
{
    public const LANGUAGE_SPANISH = 34;
    public const LANGUAGE_RUSSIAN = 7;
    public const LANGUAGE_ENGLISH = 44;
    public const LANGUAGE_ITALIAN = 39;
    public const LANGUAGE_COREAN = 82;
    public const LANGUAGE_FRENCH = 33;
    public const LANGUAGE_GERMAN = 49;

    public const LANGUAGES = array(
        self::LANGUAGE_RUSSIAN => 'ru_RU',
        self::LANGUAGE_FRENCH => 'fr_FR',
        self::LANGUAGE_SPANISH => 'es_ES',
        self::LANGUAGE_COREAN => 'ko_KR',
        self::LANGUAGE_GERMAN => 'de_DE',
        self::LANGUAGE_ENGLISH => 'en_EN',
        self::LANGUAGE_ITALIAN => 'it_IT'
    );

    public const DEFAULT_LANGUAGE = self::LANGUAGE_ENGLISH;

    private static ?Languages $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getDefaultID(): int
    {
        return self::DEFAULT_LANGUAGE;
    }

    protected function registerItems(): void
    {
        foreach(self::LANGUAGES as $id => $locale) {
            $this->registerItem(new Language($id, $locale));
        }
    }

    public function getEnglish() : Language
    {
        return $this->getByID(self::LANGUAGE_ENGLISH);
    }

    public function getRussian() : Language
    {
        return $this->getByID(self::LANGUAGE_RUSSIAN);
    }

    public function getGerman() : Language
    {
        return $this->getByID(self::LANGUAGE_GERMAN);
    }

    public function getFrench() : Language
    {
        return $this->getByID(self::LANGUAGE_FRENCH);
    }

    public function getSpanish() : Language
    {
        return $this->getByID(self::LANGUAGE_SPANISH);
    }

    public function getItalian() : Language
    {
        return $this->getByID(self::LANGUAGE_ITALIAN);
    }

    public function getKorean() : Language
    {
        return $this->getByID(self::LANGUAGE_COREAN);
    }
}
