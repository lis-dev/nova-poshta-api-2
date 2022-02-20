<?php

namespace LisDev\Request\ResponseHandler;

interface ResponseHandlerInterface
{
    /**
     * Formats response
     * @param string|array $response
     * @return string|array
     */
    public function format($response);
}