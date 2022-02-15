<?php

namespace LisDev\Models;

use LisDev\Constants\Api;
use LisDev\Exceptions\ParamsException;

class InternetDocument extends AbstractWritableModel
{
    /** @var string API model name */
    const API_NAME = 'InternetDocument';
    const API_NAME_TRACKING = 'TrackingDocument';

    // API methods
    const GET_DOCUMENT_PRICE = 'getDocumentPrice';
    const GET_DOCUMENT_DELIVERY_DATE = 'getDocumentDeliveryDate';
    const GET_DOCUMENT_LIST = 'getDocumentList';
    const GET_DOCUMENT = 'getDocument';
    const PRINT_DOCUMENT = 'printDocument';
    const PRINT_MARKING = 'printMarking';
    const GET_STATUS_DOCUMENTS = 'getStatusDocuments';
    const GENERATE_REPORT = 'generateReport';

    /**
     * Get price of delivery between two cities.
     *
     * @param string $citySender City ID
     * @param string $cityRecipient City ID
     * @param string $serviceType (DoorsDoors|DoorsWarehouse|WarehouseWarehouse|WarehouseDoors)
     * @param float $weight
     * @param float $cost
     *
     * @return array|string
     */
    public function getDocumentPrice($citySender, $cityRecipient, $serviceType, $weight, $cost)
    {
        return $this->request(self::API_NAME, self::GET_DOCUMENT_PRICE, array(
            Api::CITY_SENDER => $citySender,
            Api::CITY_RECIPIENT => $cityRecipient,
            Api::SERVICE_TYPE => $serviceType,
            Api::WEIGHT => $weight,
            API::COST => $cost,
        ));
    }

    /**
     * Get approximately date of delivery between two cities.
     *
     * @param string $citySender City ID
     * @param string $cityRecipient City ID
     * @param string $serviceType (DoorsDoors|DoorsWarehouse|WarehouseWarehouse|WarehouseDoors)
     * @param string $dateTime Date of shipping
     *
     * @return array|string
     */
    public function getDocumentDeliveryDate($citySender, $cityRecipient, $serviceType, $dateTime)
    {
        return $this->request(self::API_NAME, static::GET_DOCUMENT_DELIVERY_DATE, array(
            Api::CITY_SENDER => $citySender,
            Api::CITY_RECIPIENT => $cityRecipient,
            Api::SERVICE_TYPE => $serviceType,
            API::DATETIME => $dateTime,
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
     * @return array|string
     */
    public function getDocumentList($params = null)
    {
        return $this->request(self::API_NAME, self::GET_DOCUMENT_LIST, $params);
    }

    /**
     * Get document info by ID.
     *
     * @param string $ref Document ID
     *
     * @return array|string
     */
    public function getDocument($ref)
    {
        return $this->request(self::API_NAME, self::GET_DOCUMENT, array(
            Api::REF => $ref,
        ));
    }

    /**
     * printDocument method of InternetDocument model.
     *
     * @param array|string $documentRefs Array of Documents IDs
     * @param string $type (pdf|html|html_link|pdf_link)
     *
     * @return array|string
     */
    public function printDocument($documentRefs, $type = API::DOCTYPE_HTML)
    {
        $documentRefs = (array)$documentRefs;
        // If link is needed
        if (Api::DOCTYPE_HTML_LINK === $type || API::DOCTYPE_PDF_LINK === $type) {
            return $this->printGetLink(static::PRINT_DOCUMENT, $documentRefs, $type);
        }
        // If data is needed
        return $this->request(self::API_NAME, static::PRINT_DOCUMENT, array(
            Api::DOCUMENT_REFS => $documentRefs,
            Api::TYPE => $type
        ));
    }

    /**
     * Get only link on internet document for printing.
     *
     * @param string $method Called method of NovaPoshta API
     * @param array $documentRefs Array of Documents IDs
     * @param string $type (html_link|pdf_link)
     *
     * @return array|string
     */
    protected function printGetLink($method, $documentRefs, $type)
    {
        $data = 'https://my.novaposhta.ua/orders/' . $method . '/orders[]/' . implode(',', $documentRefs)
            . '/type/' . str_replace('_link', '', $type)
            . '/apiKey/' . $this->request->getToken();

        // Return data in same format like NovaPoshta API
        return $this->responseHandler->format(
            array(
                Api::RESPONSE_SUCCESS => true,
                Api::RESPONSE_DATA => array($data),
                Api::RESPONSE_ERRORS => array(),
                Api::RESPONSE_WARNINGS => array(),
                Api::RESPONSE_INFO => array(),
            )
        );
    }

    /**
     * printMarkings method of InternetDocument model.
     *
     * @param array|string $documentRefs Array of Documents IDs
     * @param string $type (pdf|new_pdf|new_html|old_html|html_link|pdf_link)
     *
     * @return array|string
     */
    public function printMarkings($documentRefs, $type = Api::DOCTYPE_NEW_HTML, $size = Api::DOCSIZE_85)
    {
        $documentRefs = (array)$documentRefs;
        $documentSize = $size === Api::DOCSIZE_85 ? Api::DOCSIZE_85 : Api::DOCSIZE_100;
        $method = self::PRINT_MARKING . $documentSize;
        // If link is needed
        if (Api::DOCTYPE_HTML_LINK === $type || API::DOCTYPE_PDF_LINK === $type) {
            return $this->printGetLink($method, $documentRefs, $type);
        }
        // If data is needed
        return $this->request(self::API_NAME, $method, array(
            Api::DOCUMENT_REFS => $documentRefs,
            Api::TYPE => $type
        ));
    }

    /**
     * Get tracking information by track number.
     *
     * @param string $track Track number
     *
     * @return array|string
     */
    public function documentsTracking($track)
    {
        $params = array(Api::DOCUMENTS => array(array(Api::DOCUMENT_NUMBER => $track)));
        return $this->request(self::API_NAME_TRACKING, self::GET_STATUS_DOCUMENTS, $params);
    }

    /**
     * Create Internet Document by.
     *
     * @param array $sender Sender info.
     *                         Required:
     *                         For existing sender:
     *                         'Description' => String (Full name i.e.), 'City' => String (City name)
     *                         For creating:
     *                         'FirstName' => String, 'MiddleName' => String,
     *                         'LastName' => String, 'Phone' => '000xxxxxxx', 'City' => String (City name), 'Region' => String (Region name),
     *                         'Warehouse' => String (Description from getWarehouses)
     * @param array $recipient Recipient info, same like $sender param
     * @param array $params Additional params of Internet Document
     *                         Required:
     *                         'Description' => String, 'Weight' => Float, 'Cost' => Float
     *                         Recommended:
     *                         'VolumeGeneral' => Float (default = 0.004), 'SeatsAmount' => Int (default = 1),
     *                         'PayerType' => (Sender|Recipient - default), 'PaymentMethod' => (NonCash|Cash - default)
     *                         'ServiceType' => (DoorsDoors|DoorsWarehouse|WarehouseDoors|WarehouseWarehouse - default)
     *                         'CargoType' => String
     * @return array|string
     * @throws ParamsException
     */
    public function newInternetDocument($sender, $recipient, $params)
    {
        // Check for required params and set defaults
        $this->checkInternetDocumentRecipient($recipient);
        $this->checkInternetDocumentParams($params);

        $address = new Address($this->request, $this->responseHandler);
        $counterparty = new Counterparty($this->request, $this->responseHandler);
        if (empty($sender[Api::CITY_SENDER])) {
            $senderCity = $address->getCity($sender[API::CITY], $sender[Api::REGION], $sender[Api::WAREHOUSE]);
            $sender[Api::CITY_SENDER] = $senderCity[Api::RESPONSE_DATA][0][Api::REF];
        }
        $sender[Api::CITY_REF] = $sender[Api::CITY_SENDER];
        if (empty($sender[Api::SENDER_ADDRESS]) && $sender[Api::CITY_SENDER] && $sender[Api::WAREHOUSE]) {
            $senderWarehouse = $address->getWarehouse($sender[Api::CITY_SENDER], $sender[Api::WAREHOUSE]);
            $sender[Api::SENDER_ADDRESS] = $senderWarehouse[Api::RESPONSE_DATA][0][Api::REF];
        }
        if (empty($sender[Api::SENDER])) {
            $sender[Api::COUNTERPARTY_PROPERTY] = Api::SENDER;
            $fullName = trim($sender[Api::LASTNAME] . ' ' . $sender[Api::FIRSTNAME] . ' ' . $sender[Api::MIDDLENAME]);
            // Set full name to Description if is not set
            if (empty($sender[Api::DESCRIPTION])) {
                $sender[Api::DESCRIPTION] = $fullName;
            }
            // Check for existing sender
            $senderCounterpartyExisting = $counterparty->getCounterparties(
                Api::SENDER,
                1,
                $fullName,
                $sender[Api::CITY_REF]
            );
            // Copy user to the selected city if user doesn't exist there
            if (isset($senderCounterpartyExisting[Api::RESPONSE_DATA][0][Api::REF])) {
                // Counterparty exists
                $sender[Api::SENDER] = $senderCounterpartyExisting[Api::RESPONSE_DATA][0][Api::REF];
                $contactSender = $counterparty->getCounterpartyContactPersons($sender[Api::SENDER]);
                $sender[Api::CONTACT_SENDER] = $contactSender[Api::RESPONSE_DATA][0][Api::REF];
                $sender[Api::SENDERS_PHONE] = isset($sender[Api::PHONE]) ?
                    $sender[Api::PHONE] :
                    $contactSender[Api::RESPONSE_DATA][0][Api::PHONES];
            }
        }

        // Prepare recipient data
        $recipient[Api::COUNTERPARTY_PROPERTY] = Api::RECIPIENT;
        $recipient[Api::RECIPIENTS_PHONE] = $recipient[Api::PHONE];
        if (empty($recipient[Api::CITY_RECIPIENT])) {
            $recipientCity = $address->getCity($recipient[API::CITY], $recipient[Api::REGION], $recipient[Api::WAREHOUSE]);
            $recipient[Api::CITY_RECIPIENT] = $recipientCity[Api::RESPONSE_DATA][0][Api::REF];
        }
        $recipient[Api::CITY_REF] = $recipient[Api::CITY_RECIPIENT];
        if (empty($recipient[Api::RECIPIENT_ADDRESS])) {
            $recipientWarehouse = $address->getWarehouse($recipient[Api::CITY_RECIPIENT], $recipient[Api::WAREHOUSE]);
            $recipient[Api::RECIPIENT_ADDRESS] = $recipientWarehouse[Api::RESPONSE_DATA][0][Api::REF];
        }
        if (empty($recipient[Api::RECIPIENT])) {
            $recipientCounterparty = $counterparty->save($recipient);
            $recipient[Api::RECIPIENT] = $recipientCounterparty[Api::RESPONSE_DATA][0][Api::REF];
            $recipient[Api::CONTACT_RECIPIENT] = $recipientCounterparty[Api::RESPONSE_DATA][0][Api::CONTACT_PERSON][Api::RESPONSE_DATA][0][Api::REF];
        }
        // Full params is merge of arrays $sender, $recipient, $params
        $paramsInternetDocument = array_merge($sender, $recipient, $params);
        // Creating new Internet Document
        return $this->save($paramsInternetDocument);
    }

    /**
     * Generates report by Document refs.
     *
     * @param array $params Params like getDocumentList with required keys
     *                      'Type' => [xls, csv], 'DocumentRefs' => []
     *
     * @return array|string
     */
    public function generateReport($params)
    {
        return $this->request(self::API_NAME, self::GENERATE_REPORT, $params);
    }

    /**
     * Check required fields for new InternetDocument and set defaults.
     *
     * @param array &$counterparty Recipient info array
     * @throws ParamsException
     */
    protected function checkInternetDocumentRecipient(array &$counterparty)
    {
        $requiredFieldMsg = ' is required filed for recipient';
        // Check required fields
        if (!$counterparty[Api::FIRSTNAME]) {
            throw new ParamsException(Api::FIRSTNAME . $requiredFieldMsg);
        }
        // MiddleName really is not required field, but manual says otherwise
        // if ( ! $counterparty[Api::MIDDLENAME])
        // throw new \Exception(Api::MIDDLENAME . ' is required field for sender and recipient');
        if (!$counterparty[Api::LASTNAME]) {
            throw new ParamsException(Api::LASTNAME . $requiredFieldMsg);
        }
        if (!$counterparty[Api::PHONE]) {
            throw new ParamsException(Api::PHONE . $requiredFieldMsg);
        }
        if (!($counterparty[API::CITY] || $counterparty[Api::CITY_REF])) {
            throw new ParamsException(API::CITY . $requiredFieldMsg);
        }
        if (!($counterparty[Api::REGION] || $counterparty[Api::CITY_REF])) {
            throw new ParamsException(Api::REGION . $requiredFieldMsg);
        }

        // Set defaults
        if (empty($counterparty[Api::COUNTERPARTY_TYPE])) {
            $counterparty[Api::COUNTERPARTY_TYPE] = Api::DEFAULT_COUNTERPARTY_TYPE;
        }
    }

    /**
     * Check required params for new InternetDocument and set defaults.
     *
     * @param array &$params
     * @throws ParamsException
     */
    protected function checkInternetDocumentParams(array &$params)
    {
        if (!$params[Api::DESCRIPTION]) {
            throw new ParamsException('Description is required filed for new Internet document');
        }
        if (!$params[Api::WEIGHT]) {
            throw new ParamsException('Weight is required filed for new Internet document');
        }
        if (!$params[API::COST]) {
            throw new ParamsException('Cost is required filed for new Internet document');
        }
        empty($params[API::DATETIME]) and $params[API::DATETIME] = date(Api::DEFAULT_DATE_FORMAT);
        empty($params[Api::SERVICE_TYPE]) and $params[Api::SERVICE_TYPE] = Api::DEFAULT_SERVICE_TYPE;
        empty($params[Api::PAYMENT_METHOD]) and $params[Api::PAYMENT_METHOD] = Api::DEFAULT_PAYMENT_METHOD;
        empty($params[Api::PAYER_TYPE]) and $params[Api::PAYER_TYPE] = Api::DEFAULT_PAYER_TYPE;
        empty($params[Api::SEATS_AMOUNT]) and $params[Api::SEATS_AMOUNT] = Api::DEFAULT_SEATS_AMOUNT;
        empty($params[Api::CARGO_TYPE]) and $params[Api::CARGO_TYPE] = Api::DEFAULT_CARGO_TYPE;
        if ($params[Api::CARGO_TYPE] !== Api::DOCUMENTS) {
            empty($params[Api::VOLUME_GENERAL]) and $params[Api::VOLUME_GENERAL] = Api::DEFAULT_VOLUME_GENERAL;
            empty($params[Api::VOLUME_WEIGHT]) and $params[Api::VOLUME_WEIGHT] = $params[Api::DEFAULT_VOLUME_WEIGHT];
        }
    }
}