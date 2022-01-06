<?php

declare(strict_types=1);

namespace LisDev\Service;

class CoreServiceFactory extends AbstractServiceFactory
{
    private static array $classMap = [
        'Common',
        'TrackingDocument',
        'Address',
        'Counterparty',
        'InternetDocument',
    ];

    protected function getServiceClass(string $name): ?string
    {
        return self::$classMap[$name] ?? null;
    }
}