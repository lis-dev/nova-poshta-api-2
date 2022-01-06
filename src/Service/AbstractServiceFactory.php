<?php

declare(strict_types=1);

namespace LisDev\Service;

use LisDev\NovaPoshtaClientInterface;

abstract class AbstractServiceFactory
{
    private array $services;

    /**
     * @param $client
     */
    public function __construct(private NovaPoshtaClientInterface $client)
    {
        $this->services = [];
    }

    abstract protected function getServiceClass(string $name);

    public function __get(string $name)
    {
        $serviceClass = $this->getServiceClass($name);
        if (null !== $serviceClass) {
            if (!\array_key_exists($name, $this->services)) {
                $this->services[$name] = new $serviceClass($this->client);
            }

            return $this->services[$name];
        }

        trigger_error('Undefined property: '.static::class.'::$'.$name);

        return null;
    }
}