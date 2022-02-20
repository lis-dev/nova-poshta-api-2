<?php

namespace LisDev\Request;

use LisDev\Constants\Format;
use LisDev\Constants\Headers;
use LisDev\Exceptions\ApplicationException;
use LisDev\Exceptions\RequestException;

class CurlRequest extends AbstractRequest
{
    /**
     * @param string $model
     * @param string $method
     * @param null $params
     * @throws ApplicationException
     * @throws RequestException
     * @return string
     */
    public function execute($model, $method, $params = null)
    {
        $ch = curl_init($this->uri);
        if ($ch === false) {
            throw new ApplicationException("Can't create cURL handle");
        }
        $contentType =  array(Format::XML === $this->format ? Headers::CONTENT_TYPE_XML : Headers::CONTENT_TYPE_JSON);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $contentType);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->makePayload($model, $method, $params));

        if ($this->timeout > 0) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        }

        $result = curl_exec($ch);
        curl_close($ch);

        if (false === $result) {
            throw new RequestException('cURL request failed');
        }

        return (string)$result;
    }
}