<?php


namespace LisDev\Constants;

/**
 * Supported response formats
 */
class Format
{
    /**
     * (default) array
     * @var string
     */
    const ARR = 'array';
    /**
     * @var string
     */
    const JSON = 'json';
    /**
     * @var string
     */
    const XML = 'xml';

    /**
     * list of available format of returned data
     * @return bool[]
     */
    public static function getList()
    {
        return array(
            self::ARR => true,
            self::JSON => true,
            self::XML => true,
        );
    }
}