<?php

namespace LisDev\Constants;

/**
 * Supported request types
 */
class Connection
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
}