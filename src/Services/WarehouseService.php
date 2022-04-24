<?php

namespace LisDev\Services;

class WarehouseService
{
    private PreparationDataService $preparationDataService;

    public function __construct()
    {
        $this->preparationDataService = new PreparationDataService();
    }

    /**
     * Get one warehouse by city name and warehouse's description.
     *
     * @param string $cityRef ID of city
     * @param string $description Description like in getted by getWarehouses()
     *
     * @return mixed
     * @throws \Exception
     */
    public function getWarehouse($cityRef, $description = '')
    {
        $warehouses = $this->getWarehouses($cityRef);
        $error = array();
        $data = array();
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
        return $this->preparationDataService->prepare(
            array(
                'success' => empty($error),
                'data' => array($data),
                'errors' => (array) $error,
                'warnings' => array(),
                'info' => array(),
            )
        );
    }
}