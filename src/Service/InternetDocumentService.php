<?php

declare(strict_types=1);

namespace LisDev\Service;

use LisDev\ApiDefaultDataPreparator;
use LisDev\InternetDocumentType;
use LisDev\Model;
use LisDev\PrintMarkingType;

class InternetDocumentService extends AbstractService
{
    /**
     * Get price of delivery between two cities
     * @param string $citySender
     * @param string $cityRecipient
     * @param string $serviceType
     * @param float $weight
     * @param float $cost
     * @return mixed
     */
    public function getDocumentPrice(
        string $citySender,
        string $cityRecipient,
        string $serviceType,
        float $weight,
        float $cost
    ) {
        return $this->request(Model::InternetDocument, 'getDocumentPrice', [
            'CitySender' => $citySender,
            'CityRecipient' => $cityRecipient,
            'ServiceType' => $serviceType,
            'Weight' => $weight,
            'Cost' => $cost,
        ]);
    }

    /**
     * Get approximately date of delivery between two cities.
     * @param $citySender
     * @param $cityRecipient
     * @param $serviceType
     * @param $dateTime
     * @return mixed
     */
    public function getDocumentDeliveryDate($citySender, $cityRecipient, $serviceType, $dateTime)
    {
        return $this->request(Model::InternetDocument, 'getDocumentDeliveryDate', array(
            'CitySender' => $citySender,
            'CityRecipient' => $cityRecipient,
            'ServiceType' => $serviceType,
            'DateTime' => $dateTime,
        ));
    }

    /**
     * Get documents list.
     * @param array|null $params
     * @return mixed
     */
    public function getDocumentList(array $params = null)
    {
        return $this->request(Model::InternetDocument, 'getDocumentList', $params);
    }

    /**
     * Get document info by ID.
     * @param $ref
     * @return mixed
     */
    public function getDocument($ref)
    {
        return $this->request(Model::InternetDocument, 'getDocument', array(
            'Ref' => $ref,
        ));
    }

    /**
     * Generetes report by Document refs.
     * @param array $params
     * @return mixed
     */
    public function generateReport(array $params)
    {
        return $this->request(Model::InternetDocument, 'generateReport', $params);
    }

    /**
     * printDocument method of InternetDocument model.
     * @param array $documentRefs
     * @param InternetDocumentType $type
     * @return array
     */
    public function printDocument(array $documentRefs, InternetDocumentType $type = InternetDocumentType::Html)
    {
        // If needs link
        if (InternetDocumentType::HtmlLink === $type || InternetDocumentType::PdfLink === $type) {
            return $this->printGetLink('printDocument', $documentRefs, $type->value);
        }

        // If needs data
        return $this->request(
            Model::InternetDocument,
            'printDocument',
            array('DocumentRefs' => $documentRefs, 'Type' => $type)
        );
    }

    /**
     * printMarkings method of InternetDocument model.
     * @param array $documentRefs
     * @param PrintMarkingType $type
     * @param string $size
     * @return array
     */
    public function printMarkings(
        array $documentRefs,
        PrintMarkingType $type = PrintMarkingType::NewHtml,
        string $size = '85x85'
    ) {
        $documentSize = $size === '85x85' ? '85x85' : '100x100';
        $method = 'printMarking'.$documentSize;
        // If needs link
        if (PrintMarkingType::HtmlLink === $type || PrintMarkingType::PdfLink === $type) {
            return $this->printGetLink($method, $documentRefs, $type->value);
        }

        // If needs data
        return $this->request(Model::InternetDocument, $method, array('DocumentRefs' => $documentRefs, 'Type' => $type)
        );
    }

    /**
     * Get only link on internet document for printing
     * @param string $method
     * @param array $documentRefs
     * @param string $type
     * @return array
     */
    protected function printGetLink(string $method, array $documentRefs, string $type)
    {
        $data = 'https://my.novaposhta.ua/orders/'.$method.'/orders[]/'.implode(',', $documentRefs)
            .'/type/'.str_replace('_link', '', $type)
            .'/apiKey/'.$this->client->getApiKey();

        // Return data in same format like NovaPoshta API
        return (new ApiDefaultDataPreparator())->prepare([
            'success' => true,
            'data' => [$data],
            'errors' => [],
            'warnings' => [],
            'info' => [],

        ], $this->client->getFormat(), $this->client->isThrowErrors());
    }
}