<?php

namespace LisDev\Delivery\Request;

use CurlHandle;
use LisDev\Delivery\Contracts\FormatInterface;
use LisDev\Delivery\Contracts\RequestInterface;
use LisDev\Delivery\Helpers\Array2Xml;
use LisDev\Delivery\Helpers\PrepareData;

class Request implements RequestInterface
{
    /**
     * @var int Connection type (self::CONNECTION_TYPE_CURL | self::CONNECTION_TYPE_FILE_GET_CONTENTS)
     */
    protected $connectionType = RequestInterface::CONNECTION_TYPE_CURL;

    /** @var int Connection timeout (in seconds) */
    protected $timeout = 0;

    /**
     * @var string Format of returned data - self::FORMAT_ARRAY, self::FORMAT_JSON, self::XML
     */
    protected $format = FormatInterface::FORMAT_ARRAY;

    public function __construct(
        protected string $key,
        protected string $apiUri,
        protected string $language,
        protected bool $throwErrors
    ) {
        //
    }

    /**
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int)$timeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Setter for format property.
     *
     * @param string $format Format of returned data by methods (json, xml, array)
     *
     * @return NovaPoshtaApi2
     */
    public function setFormat($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Getter for format property.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Setter for $connectionType property.
     *
     * @param int $connectionType Connection type (self::CONNECTION_TYPE_CURL | self::CONNECTION_TYPE_FILE_GET_CONTENTS)
     *
     * @return $this
     */
    public function setConnectionType($connectionType)
    {
        $this->connectionType = $connectionType;
        return $this;
    }

    /**
     * Getter for $connectionType property.
     *
     * @return string
     */
    public function getConnectionType()
    {
        return $this->connectionType;
    }

    /**
     * Make request to NovaPoshta API.
     *
     * @param string $model  Model name
     * @param string $method Method name
     * @param array  $params Required params
     */
    public function exec(string $model, string $method, ?array $params = null)
    {
        // Get required URL
        $url = FormatInterface::FORMAT_XML == $this->format
            ? $this->apiUri . '/xml/'
            : $this->apiUri . '/json/';

        $data = array(
            'apiKey' => $this->key,
            'modelName' => $model,
            'calledMethod' => $method,
            'language' => $this->language,
            'methodProperties' => $params,
        );
        $result = array();
        // Convert data to neccessary format
        $post = FormatInterface::FORMAT_XML == $this->format
            ? Array2Xml::convert($data)
            : json_encode($data);

        if (self::CONNECTION_TYPE_CURL == $this->getConnectionType()) {
            $ch = curl_init($url);
            if (is_resource($ch) || $ch instanceof CurlHandle) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: ' . (FormatInterface::FORMAT_XML == $this->format ? 'text/xml' : 'application/json')
                ));
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

                if ($this->timeout > 0) {
                    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
                }

                $result = curl_exec($ch);
                curl_close($ch);
            }
        } else {
            $httpOptions = array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded;\r\n",
                'content' => $post,
            );

            if ($this->timeout > 0) {
                $httpOptions['timeout'] = $this->timeout;
            }

            $result = file_get_contents($url, false, stream_context_create(array(
                'http' => $httpOptions,
            )));
        }

        return PrepareData::prepare($result, $this->format, $this->throwErrors);
    }

}
