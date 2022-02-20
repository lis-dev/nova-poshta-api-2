<?php

namespace LisDev\Factories;

use LisDev\Constants\Connection;
use LisDev\Exceptions\ApplicationException;
use LisDev\Request\CurlRequest;
use LisDev\Request\FileRequest;
use LisDev\Request\RequestInterface;

class RequestFactory
{
    /**
     * Create request object
     * @param string $token
     * @param string $connectionType
     * @param string $language
     * @param int $timeout
     * @param string $format
     * @return RequestInterface
     * @throws ApplicationException
     */
    public static function create($token, $connectionType, $language, $timeout, $format)
    {
        switch ($connectionType) {
            case Connection::CURL:
                return new CurlRequest($token, $language, $timeout, $format);
            case Connection::FILE:
                return new FileRequest($token, $language, $timeout, $format);
            default:
                throw new ApplicationException("Unknown connection type '$connectionType'");
        }
    }
}