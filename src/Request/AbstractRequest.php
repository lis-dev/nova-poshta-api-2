<?php

namespace LisDev\Request;

use LisDev\Constants\Format;
use LisDev\Services\Converter;

abstract class AbstractRequest implements RequestInterface
{
    /** @var string url of API */
    const API_URI = 'https://api.novaposhta.ua/v2.0';
    /** @var string Slug of XML API endpoint */
    const API_XML = '/xml/';
    /** @var string Slug of JSON API endpoint */
    const API_JSON = '/json/';

    /** @var string API key */
    protected $token;
    /** @var string Language of response */
    protected $language;
    /** @var int Connection timeout (in seconds) */
    protected $timeout;
    /** @var string Format of returned data */
    protected $format;

    /** @var string Resulting API uri */
    protected $uri;

    /**
     * Sends an HTTP request to the Nova Poshta API and returns a response
     * @param string $token
     * @param string $language
     * @param int $timeout
     * @param string $format
     */
    public function __construct($token, $language, $timeout, $format)
    {
        $this->token = $token;
        $this->language = $language;
        $this->timeout = $timeout;
        $this->format = $format;

        $this->uri = $this->makeUrl($this->format);
    }

    /**
     * @inheritDoc
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Makes the resulting endpoint URI from the response format property
     * @param string $format
     * @return string
     */
    private function makeUrl($format)
    {
        return Format::XML === $format
            ? self::API_URI . self::API_XML
            : self::API_URI . self::API_JSON;
    }

    /**
     * Makes the payload for the request and converts to the required format
     * @param string $model
     * @param string $method
     * @param array|null $params
     */
    protected function makePayload($model, $method, $params)
    {
        $payload = array(
            'apiKey' => $this->token,
            'modelName' => $model,
            'calledMethod' => $method,
            'language' => $this->language,
            'methodProperties' => $params,
        );

        return Format::XML === $this->format ?
            Converter::arrayToXml($payload) :
            json_encode($payload);
    }
}