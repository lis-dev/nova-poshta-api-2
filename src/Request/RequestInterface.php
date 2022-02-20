<?php

namespace LisDev\Request;

interface RequestInterface
{
    /**
     * Send request to API
     * @param string $model
     * @param string $method
     * @param array|null $params
     * @return mixed
     */
    public function execute($model, $method, $params = null);

    /**
     * Returns API key
     * @return string
     */
    public function getToken();
}