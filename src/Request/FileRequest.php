<?php

namespace LisDev\Request;

use LisDev\Constants\Headers;
use LisDev\Exceptions\RequestException;

class FileRequest extends AbstractRequest
{
    const METHOD = 'POST';

    const PARAM_METHOD = 'method';
    const PARAM_HEADER = 'header';
    const PARAM_CONTENT = 'content';
    const PARAM_TIMEOUT = 'timeout';
    const PARAM_HTTP = 'http';

    /**
     * @throws RequestException
     */
    public function execute($model, $method, $params = null)
    {
        $httpOptions = array(
            self::PARAM_METHOD => self::METHOD,
            self::PARAM_HEADER => Headers::CONTENT_TYPE_FORM_URLENCODED . ";\r\n",
            self::PARAM_CONTENT => $this->makePayload($model, $method, $params),
        );

        if ($this->timeout > 0) {
            $httpOptions[self::PARAM_TIMEOUT] = $this->timeout;
        }

        $response = file_get_contents($this->uri, false, stream_context_create(array(
            self::PARAM_HTTP => $httpOptions,
        )));

        if ($response === false) {
            $error = error_get_last();
            throw new RequestException("file_get_contents request failed. Error was: " . $error['message']);
        }

        return $response;
    }
}