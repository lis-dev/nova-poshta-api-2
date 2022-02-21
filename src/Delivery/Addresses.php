<?php

namespace LisDev\Delivery;

class Addresses
{
    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    public function searchSettlements(string $cityName, int $limit = 15): array|string
    {
        return $this->apiClient->request('Address', 'searchSettlements', [
            'CityName' => $cityName,
            'Limit' => $limit,
        ]);
    }

    public function searchSettlementStreets(string $streetName, string $settlementRef, int $limit = 15): array|string
    {
        return $this->apiClient->request('Address', 'searchSettlementStreets', [
            'StreetName' => $streetName,
            'SettlementRef' => $settlementRef,
            'Limit' => $limit,
        ]);
    }

    public function getCities(string $ref = '', string $findByString = '', int $page = 0): array|string
    {
        return $this->apiClient->request('Address', 'getCities', [
            'Ref' => $ref,
            'FindByString' => $findByString,
            'Page' => $page,
        ]);
    }
}
