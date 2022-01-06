<?php

declare(strict_types=1);

namespace LisDev\Service;

use LisDev\ApiDefaultDataPreparator;
use LisDev\AreasList;
use LisDev\Model;

class AddressService extends AbstractService
{
    /**
     * Get cities of company NovaPoshta.
     *
     * @param string $ref
     * @param int $page
     * @param string $findByString
     * @return mixed
     */
    public function getCities(string $ref = '', int $page = 0, string $findByString = '')
    {
        return $this->request(Model::Address, 'getCities', [
            'Page' => $page,
            'FindByString' => $findByString,
            'Ref' => $ref,
        ]);
    }

    /**
     * Get warehouses by city.
     * @param string $cityRef
     * @param int $page
     * @return mixed
     */
    public function getWarehouses(string $cityRef, int $page = 0)
    {
        return $this->request(Model::Address, 'getWarehouses', [
            'CityRef' => $cityRef,
            'Page' => $page,
        ]);
    }

    /**
     * Get warehouse types.
     * @return mixed
     */
    public function getWarehouseTypes()
    {
        return $this->request(Model::Address, 'getWarehouseTypes');
    }

    /**
     * Get 5 nearest warehouses by array of strings.
     * @param array $searchStringArray
     * @return mixed
     */
    public function findNearestWarehouse(array $searchStringArray)
    {
        return $this->request(Model::Address, 'findNearestWarehouse', [
            'SearchStringArray' => $searchStringArray,
        ]);
    }

    /**
     * Get streets list by city and/or search string.
     * @param string $cityRef
     * @param int $page
     * @param string $findByString
     * @return mixed
     */
    public function getStreet(string $cityRef, int $page = 0, string $findByString = '')
    {
        return $this->request(Model::Address, 'getStreet', [
            'FindByString' => $findByString,
            'CityRef' => $cityRef,
            'Page' => $page,
        ]);
    }

    /**
     * Get areas list by city and/or search string.
     * @param string $ref
     * @param int $page
     * @return mixed
     */
    public function getAreas(string $ref = '', int $page = 0)
    {
        return $this->request(Model::Address, 'getAreas', [
            'Ref' => $ref,
            'Page' => $page,
        ]);
    }

    /**
     * Get area by name or by ID.
     * @param string $findByString Find area by russian or ukrainian word
     * @param string $ref Get area by ID
     * @return array
     * @throws \Exception
     */
    public function getArea(string $findByString = '', string $ref = ''): array
    {
        $areas = AreasList::AREAS;
        $data = $this->findArea($areas, $findByString, $ref);
        // Error
        $error = [];
        empty($data) and $error = ['Area was not found'];

        // Return data in same format like NovaPoshta API

        return (new ApiDefaultDataPreparator())->prepare([
            'success' => empty($error),
            'data' => [$data],
            'errors' => (array)$error,
            'warnings' => [],
            'info' => [],
        ], $this->client->getFormat(), $this->client->isThrowErrors());
    }

    /**
     * Get one warehouse by city name and warehouse's description.
     * @param string $cityRef
     * @param string $description
     * @return array
     * @throws \Exception
     */
    public function getWarehouse(string $cityRef, string $description = '')
    {
        $warehouses = $this->getWarehouses($cityRef);
        $error = [];
        $data = [];
        if (is_array($warehouses['data'])) {
            $data = $warehouses['data'][0];
            if (count($warehouses['data']) > 1 && $description) {
                foreach ($warehouses['data'] as $warehouse) {
                    if (false !== mb_stripos($warehouse['Description'], $description)
                        or false !== mb_stripos($warehouse['DescriptionRu'], $description)) {
                        $data = $warehouse;
                        break;
                    }
                }
            }
        }
        // Error
        (!$data) and $error = 'Warehouse was not found';

        // Return data in same format like NovaPoshta API
        return (new ApiDefaultDataPreparator())->prepare([
            'success' => empty($error),
            'data' => [$data],
            'errors' => (array)$error,
            'warnings' => [],
            'info' => [],
        ], $this->client->getFormat(), $this->client->isThrowErrors());
    }

    /**
     * Get city by name and region (if it needs).
     * @param string $cityName
     * @param string $areaName
     * @param string $warehouseDescription
     * @return mixed
     * @throws \Exception
     */
    public function getCity(string $cityName, string $areaName = '', string $warehouseDescription = '')
    {
        // Get cities by name
        $cities = $this->getCities($cityName);
        $data = [];
        if (is_array($cities) && is_array($cities['data'])) {
            // If cities more then one, calculate current by area name
            $data = (count($cities['data']) > 1)
                ? $this->findCityByRegion($cities, $areaName)
                : array($cities['data'][0]);
        }
        // Try to identify city by one of warehouses descriptions
        if (count($data) > 1 && $warehouseDescription) {
            foreach ($data as $cityData) {
                $warehouseData = $this->getWarehouse($cityData['Ref'], $warehouseDescription);
                $warehouseDescriptions = [
                    $warehouseData['data'][0]['Description'],
                    $warehouseData['data'][0]['DescriptionRu'],
                ];
                if (in_array($warehouseDescription, $warehouseDescriptions, true)) {
                    $data = [$cityData];
                    break;
                }
            }
        }
        // Error
        $error = [];
        (!$data) and $error = ['City was not found'];

        // Return data in same format like NovaPoshta API
        return (new ApiDefaultDataPreparator())->prepare([
            'success' => empty($error),
            'data' => [$data],
            'errors' => (array)$error,
            'warnings' => [],
            'info' => [],
        ], $this->client->getFormat(), $this->client->isThrowErrors());
    }

    /**
     * Find city from list by name of region.
     * @param array $cities
     * @param string $areaName
     * @return array
     * @throws \Exception
     */
    protected function findCityByRegion(array $cities, string $areaName)
    {
        $data = [];
        $areaRef = '';
        // Get region id
        $area = $this->getArea($areaName);
        $area['success'] and $areaRef = $area['data'][0]['Ref'];
        if ($areaRef and is_array($cities['data'])) {
            foreach ($cities['data'] as $city) {
                if ($city['Area'] === $areaRef) {
                    $data[] = $city;
                }
            }
        }

        return $data;
    }

    /**
     * Find current area in list of areas.
     * @param array $areas
     * @param string $findByString
     * @param string $ref
     * @return array
     */
    protected function findArea(array $areas, string $findByString = '', string $ref = ''): array
    {
        $data = [];
        if (!$findByString and !$ref) {
            return $data;
        }
        // Try to find current region
        foreach ($areas as $key => $area) {
            // Is current area found by string or by key
            $found = $findByString
                ? ((false !== mb_stripos($area['Description'], $findByString))
                    or (false !== mb_stripos($area['DescriptionRu'], $findByString))
                    or (false !== mb_stripos($area['Area'], $findByString))
                    or (false !== mb_stripos($area['AreaRu'], $findByString)))
                : ($key === $ref);
            if ($found) {
                $area['Ref'] = $key;
                $data[] = $area;
                break;
            }
        }

        return $data;
    }


}