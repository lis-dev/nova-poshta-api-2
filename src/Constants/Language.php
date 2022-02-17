<?php

namespace LisDev\Constants;

/**
 * Supported response languages
 */
class Language implements ConstantsListInterface
{
    /**
     * (default) Russian
     * @var string
     */
    const RU = 'ru';
    /**
     * Ukrainian
     * @var string
     */
    const UA = 'ua';
    /**
     * English
     * @var string
     */
    const EN = 'en';

    /**
     * list of available languages
     * @return bool[]
     */
    public static function getList()
    {
        return array(
            self::RU => true,
            self::UA => true,
            self::EN => true,
        );
    }
}