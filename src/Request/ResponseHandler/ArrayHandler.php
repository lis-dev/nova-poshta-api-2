<?php

namespace LisDev\Request\ResponseHandler;

use LisDev\Exceptions\ApiException;

class ArrayHandler extends AbstractResponseHandler
{

    /**
     * Convert response to array
     * @param array|string $response
     * @return array
     * @throws ApiException
     */
    public function format($response)
    {
        $this->handleError($response);

        return is_array($response) ?
            $response :
            json_decode($response, true);
    }
}