<?php

namespace LisDev\Interfaces;

use LisDev\Models\Model;

interface NovaPoshtaApiClientInterface
{
    public function request(string $model, string $method, array $params = null);
}