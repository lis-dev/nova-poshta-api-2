<?php

namespace LisDev\Request\ResponseHandler;

use LisDev\Constants\Api;
use LisDev\Exceptions\ApiException;

abstract class AbstractResponseHandler implements ResponseHandlerInterface
{
    /** @var bool Throw exceptions when there is an error in the response */
    protected $throwErrors;

    /**
     * Response data formatter
     * @param bool $throwErrors
     */
    public function __construct($throwErrors)
    {
        $this->throwErrors = $throwErrors;
    }

    /**
     * If error exists, throws ApiException
     * @param $result
     * @throws ApiException
     */
    protected function handleError($result)
    {
        if (!$this->throwErrors) {
            return;
        }

        $errorMsg = $this->extractError($result);
        if ($errorMsg) {
            throw new ApiException($errorMsg);
        }
    }

    /**
     * Extracts the error message from the response. Returns false if no error is found
     * @param $result
     * @return mixed
     */
    protected function extractError($result)
    {
        if (is_string($result)) {
            //try to extract from xml
            $xmlErrors = libxml_use_internal_errors(true);
            $xml = simplexml_load_string($result);
            libxml_use_internal_errors($xmlErrors);
            if ($xml) {
                if (property_exists($xml, Api::RESPONSE_ERRORS) &&
                    property_exists($xml->errors, Api::XML_ITEM) &&
                    count($xml->errors->item) !== 0
                ) {
                    $errors = '';
                    foreach ($xml->errors->item as $error) {
                        $errors .= $errors === '' ? $error : PHP_EOL . $error;
                    }
                    return $errors;
                }
                return false;
            }

            //try to extract from json
            $json = json_decode($result, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $result = $json;
            } else {
                return false;
            }
        }

        //try to extract from array
        if (is_array($result) && array_key_exists(Api::RESPONSE_ERRORS, $result) && $result[Api::RESPONSE_ERRORS]) {
            return is_array($result[Api::RESPONSE_ERRORS]) ?
                implode("\n", $result[Api::RESPONSE_ERRORS]) :
                $result[Api::RESPONSE_ERRORS];
        }

        return false;
    }
}