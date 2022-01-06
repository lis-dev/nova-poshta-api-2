<?php

declare(strict_types=1);

namespace LisDev\Service;

use LisDev\Model;
use LisDev\NovaPoshtaClientInterface;

abstract class AbstractService
{
    public function __construct(protected NovaPoshtaClientInterface $client)
    {
    }

    public function getClient(): NovaPoshtaClientInterface
    {
        return $this->client;
    }

    protected function request(Model $model, string $method, array $params = null)
    {
        return $this->getClient()->request($model->value, $method, static::formatParams($params));
    }

    private static function formatParams(array $params)
    {
        if (null === $params) {
            return null;
        }
        array_walk_recursive($params, function (&$value, $key) {
            if (null === $value) {
                $value = '';
            }
        });

        return $params;
    }
}