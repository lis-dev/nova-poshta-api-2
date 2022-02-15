<?php

namespace LisDev\Models;

use LisDev\Request\RequestInterface;
use LisDev\Request\ResponseHandler\ResponseHandlerInterface;

abstract class AbstractModel
{
    /** @var string Sets API model name in children */
    const API_NAME = null;

    /** @var RequestInterface API Requests */
    protected $request;
    /** @var ResponseHandlerInterface Formats response and handle API error */
    protected $responseHandler;

    /**
     * API model
     * @param RequestInterface $request
     * @param ResponseHandlerInterface $responseHandler
     */
    public function __construct($request, $responseHandler)
    {
        $this->request = $request;
        $this->responseHandler = $responseHandler;
    }

    /**
     * Executes the request, checks the response for errors, formats and returns the response
     * @param string $model
     * @param string $method
     * @param array|null $params
     * @return array|string
     */
    protected function request($model, $method, $params = null)
    {
        $result = $this->request->execute($model, $method, $params);

        return $this->responseHandler->format($result);
    }
}