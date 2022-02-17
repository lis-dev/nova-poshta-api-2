<?php

namespace LisDev\Constants;

/**
 * Supported connection types
 */
class Connection implements ConstantsListInterface
{
    /**
     * (default) cURL
     * @var string
     */
    const CURL = 'curl';
    /**
     * @var string
     */
    const FILE = 'file_get_contents';

    /**
     * list of available connection types
     * @return bool[]
     */
    public static function getList()
    {
        return array(
            self::CURL => true,
            self::FILE => true,
        );
    }
}