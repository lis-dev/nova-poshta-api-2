<?php

namespace LisDev\Request\ResponseHandler;

use LisDev\Exceptions\ApiException;
use LisDev\Services\Converter;

class XmlHandler extends AbstractResponseHandler
{

    /**
     * Convert response to array
     * @param array|string $response
     * @return string
     * @throws ApiException
     */
    public function format($response)
    {
        $this->handleError($response);

        return is_array($response) ?
            Converter::arrayToXml($response) :
            $response;
    }
}