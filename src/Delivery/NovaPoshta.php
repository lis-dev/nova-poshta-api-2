<?php

namespace LisDev\Delivery;

class NovaPoshta
{
    public const FORMAT_ARRAY = 'array';
    public const FORMAT_JSON = 'json';
    public const FORMAT_XML = 'xml';

    private ApiClient $apiClient;

    public function __construct(
        string $apiKey,
        bool $throwErrors = false,
        string $format = 'array',
        int $timeout = 1,
    ) {
        $this->apiClient = new ApiClient($apiKey, $throwErrors, $format, $timeout);
    }

    public function addresses(): Addresses
    {
        return new Addresses($this->apiClient);
    }

    public function documents(): Documents
    {
        return new Documents($this->apiClient);
    }

    public function counterparties(): Counterparties
    {
        return new Counterparties($this->apiClient);
    }

    public function internetDocuments(): InternetDocuments
    {
        return new InternetDocuments($this->apiClient);
    }

    public function newQuery(): QueryBuilder
    {
        return new QueryBuilder($this->apiClient);
    }
}
