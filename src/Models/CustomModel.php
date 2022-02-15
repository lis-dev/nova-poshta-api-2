<?php

namespace LisDev\Models;

use LisDev\Request\RequestInterface;
use LisDev\Request\ResponseHandler\ResponseHandlerInterface;

class CustomModel extends AbstractModel
{
    /** @var string Name of custom model */
    private $model;

    /**
     * @param RequestInterface $request
     * @param ResponseHandlerInterface $responseHandler
     * @param string $model
     */
    public function __construct($request, $responseHandler, $model)
    {
        parent::__construct($request, $responseHandler);

        $this->model = $model;
    }


    /**
     * Get custom method from custom model
     * @param string $method
     * @param mixed $params
     * @return array|string
     */
    public function method($method, $params = null)
    {
        return $this->request($this->model, $method, $params);
    }
}