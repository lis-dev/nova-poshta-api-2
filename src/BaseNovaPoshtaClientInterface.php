<?php

declare(strict_types=1);

namespace LisDev;

interface BaseNovaPoshtaClientInterface
{
    /**
     * Gets the API key used by the client to send requests.
     *
     * @return null|string the API key used by the client to send requests
     */
    public function getApiKey(): ?string;

    /**
     * Gets the API url used by the client to send requests.
     *
     * @return string
     */
    public function getApiUrl(): string;

    public function isThrowErrors(): bool;

    public function getFormat(): DataFormat;

}