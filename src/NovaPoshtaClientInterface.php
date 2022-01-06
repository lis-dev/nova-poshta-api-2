<?php

declare(strict_types=1);

namespace LisDev;

interface NovaPoshtaClientInterface extends BaseNovaPoshtaClientInterface
{
    /**
     * Sends a request to NovaPoshta's API.
     *
     * @param string $model the HTTP method
     * @param string $method the path of the request
     * @param array|null $params the parameters of the request
     */
    public function request(string $model, string $method, array $params = null);
}