<?php

namespace LisDev\Models;

use LisDev\Constants\Api;

class Address extends AbstractWritableModel
{
    /** @var string API model name */
    const API_NAME = 'Address';

    // API methods
    const GET_CITIES = 'getCities';
    const GET_STREET = 'getStreet';
    const GET_WAREHOUSES = 'getWarehouses';
    const GET_AREAS = 'getAreas';
    const FIND_NEAREST_WAREHOUSE = 'findNearestWarehouse';
    const GET_WAREHOUSE_TYPES = 'getWarehouseTypes';

    /**
     * Get cities of company NovaPoshta.
     *
     * @param int $page Num of page
     * @param string $findByString Find city by russian or ukrainian word
     * @param string $ref ID of city
     *
     * @return array|string
     */
    public function getCities($page = 0, $findByString = '', $ref = '')
    {
        return $this->request(self::API_NAME, self::GET_CITIES, array(
            Api::PAGE => $page,
            Api::FIND_BY_STRING => $findByString,
            Api::REF => $ref,
        ));
    }

    /**
     * Get streets list by city and/or search string.
     *
     * @param string $cityRef ID of city
     * @param string $findByString
     * @param int $page
     *
     * @return array|string
     */
    public function getStreet($cityRef, $findByString = '', $page = 0)
    {
        return $this->request(self::API_NAME, self::GET_STREET, array(
            Api::FIND_BY_STRING => $findByString,
            Api::CITY_REF => $cityRef,
            Api::PAGE => $page,
        ));
    }

    /**
     * Get warehouses by city.
     *
     * @param string $cityRef ID of city
     * @param int $page
     *
     * @return array|string
     */
    public function getWarehouses($cityRef, $page = 0)
    {
        return $this->request(self::API_NAME, self::GET_WAREHOUSES, array(
            Api::CITY_REF => $cityRef,
            Api::PAGE => $page,
        ));
    }

    /**
     * Get areas list by city and/or search string.
     *
     * @param string $ref ID of area
     * @param int $page
     *
     * @return array|string
     */
    public function getAreas($ref = '', $page = 0)
    {
        return $this->request(self::API_NAME, self::GET_AREAS, array(
            Api::REF => $ref,
            Api::PAGE => $page,
        ));
    }

    /**
     * Get 5 nearest warehouses by array of strings.
     *
     * @param array $searchStringArray
     *
     * @return array|string
     */
    public function findNearestWarehouse($searchStringArray)
    {
        $searchStringArray = (array)$searchStringArray;
        return $this->request(self::API_NAME, self::FIND_NEAREST_WAREHOUSE, array(
            Api::SEARCH_STRING_ARRAY => $searchStringArray,
        ));
    }

    /**
     * Get warehouse types.
     *
     * @return array|string
     */
    public function getWarehouseTypes()
    {
        return $this->request(self::API_NAME, self::GET_WAREHOUSE_TYPES);
    }

    /**
     * Get one warehouse by city name and warehouse's description.
     *
     * @param string $cityRef ID of city
     * @param string $description Description like in got by getWarehouses()
     *
     * @return array|string
     */
    public function getWarehouse($cityRef, $description = '')
    {
        $warehouses = $this->getWarehouses($cityRef);
        $error = array();
        $data = array();
        if (is_array($warehouses[Api::RESPONSE_DATA])) {
            $data = $warehouses[Api::RESPONSE_DATA][0];
            if ($description && count($warehouses[Api::RESPONSE_DATA]) > 1) {
                foreach ($warehouses[Api::RESPONSE_DATA] as $warehouse) {
                    if (false !== mb_stripos($warehouse[Api::PROPERTY_DESCRIPTION], $description) ||
                        false !== mb_stripos($warehouse[Api::PROPERTY_DESCRIPTION_RU], $description)
                    ) {
                        $data = $warehouse;
                        break;
                    }
                }
            }
        }
        // Error
        (!$data) and $error = 'Warehouse was not found';
        // Return data in same format like NovaPoshta API
        return $this->responseHandler->format(
            array(
                Api::RESPONSE_SUCCESS => empty($error),
                Api::RESPONSE_DATA => array($data),
                Api::RESPONSE_ERRORS => (array)$error,
                Api::RESPONSE_WARNINGS => array(),
                Api::RESPONSE_INFO => array(),
            )
        );
    }

    /**
     * Get area by name or by ID.
     *
     * @param string $findByString Find area by russian or ukrainian word
     * @param string $ref Get area by ID
     *
     * @return array|string
     */
    public function getArea($findByString = '', $ref = '')
    {
        // Load areas list from file
        empty($this->areas) and $this->areas = (include __DIR__ . '/../Constants/Areas.php');
        $data = $this->findArea($this->areas, $findByString, $ref);
        // Error
        $error = array();
        empty($data) and $error = array('Area was not found');
        // Return data in same format like NovaPoshta API
        return $this->responseHandler->format(
            array(
                Api::RESPONSE_SUCCESS => empty($error),
                Api::RESPONSE_DATA => $data,
                Api::RESPONSE_ERRORS => $error,
                Api::RESPONSE_WARNINGS => array(),
                Api::RESPONSE_INFO => array(),
            )
        );
    }

    /**
     * Find current area in list of areas.
     *
     * @param array $areas List of arias, got from file
     * @param string $findByString Area name
     * @param string $ref Area Ref ID
     *
     * @return array
     */
    protected function findArea(array $areas, $findByString = '', $ref = '')
    {
        $data = array();
        if (!$findByString && !$ref) {
            return $data;
        }
        // Try to find current region
        foreach ($areas as $key => $area) {
            // Is current area found by string or by key
            $found = $findByString
                ? ((false !== mb_stripos($area[Api::PROPERTY_DESCRIPTION], $findByString))
                    or (false !== mb_stripos($area[Api::PROPERTY_DESCRIPTION_RU], $findByString))
                    or (false !== mb_stripos($area[Api::PROPERTY_AREA], $findByString))
                    or (false !== mb_stripos($area[Api::PROPERTY_AREA_RU], $findByString)))
                : ($key === $ref);
            if ($found) {
                $area[Api::REF] = $key;
                $data[] = $area;
                break;
            }
        }
        return $data;
    }

    /**
     * Get city by name and region (if it needs).
     *
     * @param string $cityName City's name
     * @param string $areaName Region's name
     * @param string $warehouseDescription Warehouse description to identify needed city (if it is more than 1 in the area)
     *
     * @return array|string Cities data Can be returned more than 1 city with the same name
     */
    public function getCity($cityName, $areaName = '', $warehouseDescription = '')
    {
        // Get cities by name
        $cities = $this->getCities(0, $cityName);
        $data = array();
        if (is_array($cities) && is_array($cities[Api::RESPONSE_DATA])) {
            // If cities more than one, calculate current by area name
            $data = (count($cities[Api::RESPONSE_DATA]) > 1)
                ? $this->findCityByRegion($cities, $areaName)
                : array($cities[Api::RESPONSE_DATA][0]);
        }
        // Try to identify city by one of warehouses descriptions
        if ($warehouseDescription && count($data) > 1) {
            foreach ($data as $cityData) {
                $warehouseData = $this->getWarehouse($cityData[Api::REF], $warehouseDescription);
                $warehouseDescriptions = array(
                    $warehouseData[Api::RESPONSE_DATA][0][Api::PROPERTY_DESCRIPTION],
                    $warehouseData[Api::RESPONSE_DATA][0][Api::PROPERTY_DESCRIPTION_RU]
                );
                if (in_array($warehouseDescription, $warehouseDescriptions, true)) {
                    $data = array($cityData);
                    break;
                }
            }
        }
        // Error
        $error = array();
        (!$data) and $error = array('City was not found');
        // Return data in same format like NovaPoshta API
        return $this->responseHandler->format(
            array(
                Api::RESPONSE_SUCCESS => empty($error),
                Api::RESPONSE_DATA => $data,
                Api::RESPONSE_ERRORS => $error,
                Api::RESPONSE_WARNINGS => array(),
                Api::RESPONSE_INFO => array(),
            )
        );
    }

    /**
     * Find city from list by name of region.
     *
     * @param array $cities Array from query getCities to NovaPoshta
     * @param string $areaName
     *
     * @return array
     */
    protected function findCityByRegion($cities, $areaName)
    {
        $data = array();
        $areaRef = '';
        // Get region id
        $area = $this->getArea($areaName);
        $area[Api::RESPONSE_SUCCESS] and $areaRef = $area[Api::RESPONSE_DATA][0][Api::REF];
        if ($areaRef && is_array($cities[Api::RESPONSE_DATA])) {
            foreach ($cities[Api::RESPONSE_DATA] as $city) {
                if ($city[Api::PROPERTY_AREA] === $areaRef) {
                    $data[] = $city;
                }
            }
        }
        return $data;
    }
}