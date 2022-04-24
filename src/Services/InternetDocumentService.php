<?php

namespace LisDev\Services;

class InternetDocumentService
{
    private NovaPoshtaApiClient $novaPoshtaApiClient;

    public function __construct()
    {
        $this->novaPoshtaApiClient = new NovaPoshtaApiClient();
    }

    /**
     * Get price of delivery between two cities.
     *
     * @param string $citySender City ID
     * @param string $cityRecipient City ID
     * @param string $serviceType (DoorsDoors|DoorsWarehouse|WarehouseWarehouse|WarehouseDoors)
     * @param float $weight
     * @param float $cost
     *
     * @return mixed
     * @throws \Exception
     */
    public function getDocumentPrice($citySender, $cityRecipient, $serviceType, $weight, $cost)
    {
        return $this->novaPoshtaApiClient->request('InternetDocument', 'getDocumentPrice', array(
            'CitySender' => $citySender,
            'CityRecipient' => $cityRecipient,
            'ServiceType' => $serviceType,
            'Weight' => $weight,
            'Cost' => $cost,
        ));
    }

    /**
     * Get approximately date of delivery between two cities.
     *
     * @param string $citySender    City ID
     * @param string $cityRecipient City ID
     * @param string $serviceType   (DoorsDoors|DoorsWarehouse|WarehouseWarehouse|WarehouseDoors)
     * @param string $dateTime      Date of shipping
     *
     * @return mixed
     */
    public function getDocumentDeliveryDate($citySender, $cityRecipient, $serviceType, $dateTime)
    {
        return $this->novaPoshtaApiClient->request('InternetDocument', 'getDocumentDeliveryDate', array(
            'CitySender' => $citySender,
            'CityRecipient' => $cityRecipient,
            'ServiceType' => $serviceType,
            'DateTime' => $dateTime,
        ));
    }

    /**
     * Get documents list.
     *
     * @param array $params List of params
     *                      Not required keys:
     *                      'Ref', 'IntDocNumber', 'InfoRegClientBarcodes', 'DeliveryDateTime', 'RecipientDateTime',
     *                      'CreateTime', 'SenderRef', 'RecipientRef', 'WeightFrom', 'WeightTo',
     *                      'CostFrom', 'CostTo', 'SeatsAmountFrom', 'SeatsAmountTo', 'CostOnSiteFrom',
     *                      'CostOnSiteTo', 'StateIds', 'ScanSheetRef', 'DateTime', 'DateTimeFrom',
     *                      'RecipientDateTime', 'isAfterpayment', 'Page', 'OrderField =>
     *                      [
     *                      IntDocNumber, DateTime, Weight, Cost, SeatsAmount, CostOnSite,
     *                      CreateTime, EstimatedDeliveryDate, StateId, InfoRegClientBarcodes, RecipientDateTime
     *                      ],
     *                      'OrderDirection' => [DESC, ASC], 'ScanSheetRef'
     *
     * @return mixed
     */
    public function getDocumentList($params = null)
    {
        return $this->novaPoshtaApiClient->request('InternetDocument', 'getDocumentList', $params ? $params : null);
    }

    /**
     * Get document info by ID.
     *
     * @param string $ref Document ID
     *
     * @return mixed
     */
    public function getDocument($ref)
    {
        return $this->novaPoshtaApiClient->request('InternetDocument', 'getDocument', array(
            'Ref' => $ref,
        ));
    }
}