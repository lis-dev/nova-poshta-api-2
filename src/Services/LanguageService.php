<?php

namespace LisDev\Services;

use LisDev\Controllers\NovaPoshtaApi2;

class LanguageService
{
    /**
     * @var string Language of response
     */
    protected string $language = 'ru';

    /**
     * Setter for language property.
     *
     * @param string $language
     *
     * @return LanguageService
     */
    public function setLanguage(string $language): LanguageService
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Getter for language property.
     *
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }
}