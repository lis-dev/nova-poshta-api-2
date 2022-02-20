<?php

namespace LisDev\Factories;

use LisDev\Constants\Format;
use LisDev\Exceptions\ApplicationException;
use LisDev\Request\ResponseHandler\ArrayHandler;
use LisDev\Request\ResponseHandler\JsonHandler;
use LisDev\Request\ResponseHandler\ResponseHandlerInterface;
use LisDev\Request\ResponseHandler\XmlHandler;

class ResponseHandlerFactory
{
    /**
     * Create response formatter object
     * @param string $format
     * @param bool $throwError
     * @return ResponseHandlerInterface
     * @throws ApplicationException
     */
    public static function create($format, $throwError)
    {
        switch ($format) {
            case Format::ARR:
                return new ArrayHandler($throwError);
            case Format::JSON:
                return new JsonHandler($throwError);
            case Format::XML:
                return new XmlHandler($throwError);
            default:
                throw new ApplicationException("Unknown format '$format'");
        }
    }
}