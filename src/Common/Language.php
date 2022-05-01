<?php

namespace LisDev\Common;

class Language
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
     * @return Language
     */
    public function setLanguage(string $language): Language
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