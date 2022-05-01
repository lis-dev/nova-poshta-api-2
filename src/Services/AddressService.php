<?php

namespace LisDev\Services;

class AddressService
{
    private PreparationDataService $preparationDataService;
    private NovaPoshtaApiClient $novaPoshtaApiClient;

    public function __construct()
    {
        $this->preparationDataService = new PreparationDataService();
        $this->novaPoshtaApiClient = new NovaPoshtaApiClient();
    }

    /**
     * Get cities of company NovaPoshta.
     *
     * @param int    $page         Num of page
     * @param string $findByString Find city by russian or ukrainian word
     * @param string $ref          ID of city
     *
     * @return mixed
     */
    public function getCities($page = 0, $findByString = '', $ref = '')
    {
        return $this->novaPoshtaApiClient->request('Address', 'getCities', array(
            'Page' => $page,
            'FindByString' => $findByString,
            'Ref' => $ref,
        ));
    }

    /**
     * Get warehouses by city.
     *
     * @param string $cityRef ID of city
     * @param int    $page
     *
     * @return mixed
     */
    public function getWarehouses($cityRef, $page = 0)
    {
        return $this->novaPoshtaApiClient->request('Address', 'getWarehouses', array(
            'CityRef' => $cityRef,
            'Page' => $page,
        ));
    }

    /**
     * Get warehouse types.
     *
     * @return mixed
     */
    public function getWarehouseTypes()
    {
        return $this->novaPoshtaApiClient->request('Address', 'getWarehouseTypes');
    }

    /**
     * Get 5 nearest warehouses by array of strings.
     *
     * @param array $searchStringArray
     *
     * @return mixed
     */
    public function findNearestWarehouse(array $searchStringArray)
    {
        $searchStringArray = $searchStringArray;
        return $this->novaPoshtaApiClient->request('Address', 'findNearestWarehouse', array(
            'SearchStringArray' => $searchStringArray,
        ));
    }

    /**
     * Get streets list by city and/or search string.
     *
     * @param string $cityRef      ID of city
     * @param string $findByString
     * @param int    $page
     *
     * @return mixed
     */
    public function getStreet($cityRef, $findByString = '', $page = 0)
    {
        return $this->novaPoshtaApiClient->request('Address', 'getStreet', array(
            'FindByString' => $findByString,
            'CityRef' => $cityRef,
            'Page' => $page,
        ));
    }

    /**
     * Find current area in list of areas.
     *
     * @param array  $areas        List of arias, getted from file
     * @param string $findByString Area name
     * @param string $ref          Area Ref ID
     *
     * @return array
     */
    protected function findArea(array $areas, $findByString = '', $ref = '')
    {
        $data = array();
        if (!$findByString and !$ref) {
            return $data;
        }
        // Try to find current region
        foreach ($areas as $key => $area) {
            $found = $findByString
                ? ((false !== mb_stripos($area['Description'], $findByString))
                    or (false !== mb_stripos($area['DescriptionRu'], $findByString))
                    or (false !== mb_stripos($area['Area'], $findByString))
                    or (false !== mb_stripos($area['AreaRu'], $findByString)))
                : ($key == $ref);
            if ($found) {
                $area['Ref'] = $key;
                $data[] = $area;
                break;
            }
        }
        return $data;
    }

    /**
     * Get area by name or by ID.
     *
     * @param string $findByString Find area by russian or ukrainian word
     * @param string $ref          Get area by ID
     *
     * @return array
     */
    public function getArea($findByString = '', $ref = '')
    {
        // Load areas list from file
        empty($this->areas) and $this->areas = (include dirname(__FILE__) . '/NovaPoshtaApi2Areas.php');
        $data = $this->findArea($this->areas, $findByString, $ref);
        // Error
        $error = array();
        empty($data) and $error = array('Area was not found');
        // Return data in same format like NovaPoshta API
        return $this->preparationDataService->prepare(
            array(
                'success' => empty($error),
                'data' => $data,
                'errors' => $error,
                'warnings' => array(),
                'info' => array(),
            )
        );
    }

    /**
     * Get areas list by city and/or search string.
     *
     * @param string $ref  ID of area
     * @param int    $page
     *
     * @return mixed
     */
    public function getAreas($ref = '', $page = 0)
    {
        return $this->novaPoshtaApiClient->request('Address', 'getAreas', array(
            'Ref' => $ref,
            'Page' => $page,
        ));
    }

    /**
     * Find city from list by name of region.
     *
     * @param array  $cities   Array from query getCities to NovaPoshta
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
        $area['success'] and $areaRef = $area['data'][0]['Ref'];
        if ($areaRef and is_array($cities['data'])) {
            foreach ($cities['data'] as $city) {
                if ($city['Area'] == $areaRef) {
                    $data[] = $city;
                }
            }
        }
        return $data;
    }

    /**
     * Get city by name and region (if it needs).
     *
     * @param string $cityName City's name
     * @param string $areaName Region's name
     * @param string $warehouseDescription Warehouse description to identiry needed city (if it more than 1 in the area)
     *
     * @return array Cities's data Can be returned more than 1 city with the same name
     */
    public function getCity($cityName, $areaName = '', $warehouseDescription = '')
    {
        // Get cities by name
        $cities = $this->getCities(0, $cityName);
        $data = array();
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
                $warehouseDescriptions = array(
                    $warehouseData['data'][0]['Description'],
                    $warehouseData['data'][0]['DescriptionRu']
                );
                if (in_array($warehouseDescription, $warehouseDescriptions)) {
                    $data = array($cityData);
                    break;
                }
            }
        }
        // Error
        $error = array();
        (!$data) and $error = array('City was not found');
        // Return data in same format like NovaPoshta API
        return $this->preparationDataService->prepare(
            array(
                'success' => empty($error),
                'data' => $data,
                'errors' => $error,
                'warnings' => array(),
                'info' => array(),
            )
        );
    }
}