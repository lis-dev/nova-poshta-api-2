<?php

namespace LisDev;

use LisDev\Constants\Connection;
use LisDev\Constants\Format;
use LisDev\Constants\Language;
use LisDev\Exceptions\ConfigException;

class Config
{
    /** @var string Language of response (ru | ua) */
    protected $language;
    /** @var string Connection type (curl | file_get_contents) */
    protected $connectionType;
    /** @var int Connection timeout (in seconds) */
    protected $timeout;
    /** @var string Format of returned data (array, json, xml) */
    protected $format;
    /** @var bool Throw exceptions when there is an error in the response */
    protected $throwErrors;

    /**
     * NovaPoshta configuration
     * @param string $language Language of response (ru | ua | en)
     * @param string $connectionType Connection type (curl | file_get_contents)
     * @param int $timeout Request timeout (greater than zero)
     * @param string $format Format of returned data (array, json, xml)
     * @param bool $throwErrors Throw exceptions when there is an error in the response
     * @throws ConfigException
     */
    public function __construct(
        $language = Language::RU,
        $connectionType = Connection::CURL,
        $timeout = 0,
        $format = Format::ARR,
        $throwErrors = false
    )
    {
        $this
            ->setLanguage($language)
            ->setConnectionType($connectionType)
            ->setTimeout($timeout)
            ->setFormat($format)
            ->setThrowError($throwErrors);
    }

    /**
     * Setter for request's language (ru | ua | en)
     * @param string $language available variants in \LisDev\Constants\Language
     * @return $this
     * @throws ConfigException
     */
    public function setLanguage($language)
    {
        $language = (string)$language;
        if (!array_key_exists($language, Language::getList())) {
            throw new ConfigException("Unknown language '$language'. Check \LisDev\Constants\Language");
        }
        $this->language = $language;
        return $this;
    }

    /**
     * Getter for request's language
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Setter for request's connection type (curl | file_get_contents)
     * @param string $connectionType available variants in \LisDev\Constants\Connection
     * @return $this
     * @throws ConfigException
     */
    public function setConnectionType($connectionType)
    {
        $connectionType = (string)$connectionType;
        if (!array_key_exists($connectionType, Connection::getList())) {
            throw new ConfigException("Unknown connection type '$connectionType'" .
                ". Check \LisDev\Constants\Connection");
        }
        $this->connectionType = $connectionType;
        return $this;
    }

    /**
     * Getter for request's connection type
     * @return string
     */
    public function getConnectionType()
    {
        return $this->connectionType;
    }

    /**
     * Setter for request timeout
     * @param int $timeout greater than zero
     * @return $this
     * @throws ConfigException
     */
    public function setTimeout($timeout)
    {
        $timeout = (int)$timeout;
        if ($timeout < 0) {
            throw new ConfigException('Timeout must be greater than zero');
        }
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * Getter for request timeout
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Setter for format of returned data (array, json, xml)
     * @param string $format available variants in \LisDev\Constants\Format
     * @return $this
     * @throws ConfigException
     */
    public function setFormat($format)
    {
        $format = (string)$format;
        if (!array_key_exists($format, Format::getList())) {
            throw new ConfigException("Unknown format '$format'. Check \LisDev\Constants\Format");
        }
        $this->format = $format;

        return $this;
    }

    /**
     * Getter for format of returned data
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Setter for property 'Throw exceptions when there is an error in the response'
     * @param bool $throwErrors
     * @return $this
     */
    public function setThrowError($throwErrors)
    {
        $this->throwErrors = (bool)$throwErrors;
        return $this;
    }

    /**
     * Getter for property 'Throw exceptions when there is an error in the response'
     * @return bool
     */
    public function getThrowError()
    {
        return $this->throwErrors;
    }
}