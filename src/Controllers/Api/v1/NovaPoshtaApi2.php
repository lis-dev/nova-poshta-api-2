<?php

namespace LisDev\Controllers;

/**
 * Nova Poshta API Class.
 *
 * @author lis-dev
 *
 * @see https://my.novaposhta.ua/data/API2-200215-1622-28.pdf
 * @see https://github.com/lis-dev
 *
 * @license MIT
 */
class NovaPoshtaApi2
{
    const API_URI = 'https://api.novaposhta.ua/v2.0';

    /**
     * Key for API NovaPoshta.
     *
     * @var string
     *
     * @see https://my.novaposhta.ua/settings/index#apikeys
     */
    protected $key;

    /**
     * @var bool Throw exceptions when in response is error
     */
    protected $throwErrors = false;


    /**
     * @var string Areas (loaded from file, because there is no so function in NovaPoshta API 2.0)
     */
    protected $areas = '';
    /**
     * Default constructor.
     *
     * @param string $key            NovaPoshta API key
     * @param string $language       Default Language
     * @param bool   $throwErrors    Throw request errors as Exceptions
     * @param string   $connectionType Connection type (curl | file_get_contents)
     *
     * @return NovaPoshtaApi2
     */
    public function __construct($key, $language = 'ru', $throwErrors = false, $connectionType = 'curl')
    {
        $this->throwErrors = $throwErrors;
        $this
            ->setKey($key)
            ->setLanguage($language)
            ->setConnectionType($connectionType)
            ->model('Common');
    }

    /**
     * Setter for key property.
     *
     * @param string $key NovaPoshta API key
     *
     * @return NovaPoshtaApi2
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Getter for key property.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }


    /**
     * Set current model and empties method and params properties.
     *
     * @param string $model
     *
     * @return mixed
     */
    public function model($model = '')
    {
        if (!$model) {
            return $this->model;
        }

        $this->model = $model;
        $this->method = '';
        $this->params = array();
        return $this;
    }

    /**
     * Set method of current model property and empties params properties.
     *
     * @param string $method
     *
     * @return mixed
     */
    public function method($method = '')
    {
        if (!$method) {
            return $this->method;
        }

        $this->method = $method;
        $this->params = array();
        return $this;
    }

    /**
     * Set params of current method/property property.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function params($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Execute request to NovaPoshta API.
     *
     * @return mixed
     */
    public function execute()
    {
        return $this->request($this->model, $this->method, $this->params);
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
        return $this->request('Address', 'getCities', array(
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
        return $this->request('Address', 'getWarehouses', array(
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
        return $this->request('Address', 'getWarehouseTypes');
    }    

    /**
     * Get 5 nearest warehouses by array of strings.
     *
     * @param array $searchStringArray
     *
     * @return mixed
     */
    public function findNearestWarehouse($searchStringArray)
    {
        $searchStringArray = (array) $searchStringArray;
        return $this->request('Address', 'findNearestWarehouse', array(
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
        return $this->request('Address', 'getStreet', array(
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
            // Is current area found by string or by key
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
        return $this->prepare(
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
        return $this->request('Address', 'getAreas', array(
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
        return $this->prepare(
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
     * Magic method of calling functions (uses for calling Common Model of NovaPoshta API).
     *
     * @param string $method    Called method of Common Model
     * @param array  $arguments Array of params
     */
    public function __call($method, $arguments)
    {
        $common_model_method = array(
            'getTypesOfCounterparties',
            'getBackwardDeliveryCargoTypes',
            'getCargoDescriptionList',
            'getCargoTypes',
            'getDocumentStatuses',
            'getOwnershipFormsList',
            'getPalletsList',
            'getPaymentForms',
            'getTimeIntervals',
            'getServiceTypes',
            'getTiresWheelsList',
            'getTraysList',
            'getTypesOfAlternativePayers',
            'getTypesOfPayers',
            'getTypesOfPayersForRedelivery',
        );
        // Call method of Common model
        if (in_array($method, $common_model_method)) {
            return $this
                ->model('Common')
                ->method($method)
                ->params(null)
                ->execute();
        }
    }

    /**
     * getCounterparties() function of model Counterparty.
     *
     * @param string $counterpartyProperty Type of Counterparty (Sender|Recipient)
     * @param int    $page                 Page number
     * @param string $findByString         String to search
     * @param string $cityRef              City ID
     *
     * @return mixed
     */
    public function getCounterparties($counterpartyProperty = 'Recipient', $page = null, $findByString = null, $cityRef = null)
    {
        // Any param can be skipped
        $params = array();
        $params['CounterpartyProperty'] = $counterpartyProperty ? $counterpartyProperty : 'Recipient';
        $page and $params['Page'] = $page;
        $findByString and $params['FindByString'] = $findByString;
        $cityRef and $params['City'] = $cityRef;
        return $this->request('Counterparty', 'getCounterparties', $params);
    }

    /**
     * cloneLoyaltyCounterpartySender() function of model Counterparty
     * The counterparty will be not created immediately, you can wait a long time.
     *
     * @param string $cityRef City ID
     *
     * @return mixed
     */
    public function cloneLoyaltyCounterpartySender($cityRef)
    {
        return $this->request('Counterparty', 'cloneLoyaltyCounterpartySender', array('CityRef' => $cityRef));
    }

    /**
     * getCounterpartyContactPersons() function of model Counterparty.
     *
     * @param string $ref Counterparty ref
     *
     * @return mixed
     */
    public function getCounterpartyContactPersons($ref)
    {
        return $this->request('Counterparty', 'getCounterpartyContactPersons', array('Ref' => $ref));
    }

    /**
     * getCounterpartyAddresses() function of model Counterparty.
     *
     * @param string $ref  Counterparty ref
     * @param int    $page
     *
     * @return mixed
     */
    public function getCounterpartyAddresses($ref, $page = 0)
    {
        return $this->request('Counterparty', 'getCounterpartyAddresses', array('Ref' => $ref, 'Page' => $page));
    }

    /**
     * getCounterpartyOptions() function of model Counterparty.
     *
     * @param string $ref Counterparty ref
     *
     * @return mixed
     */
    public function getCounterpartyOptions($ref)
    {
        return $this->request('Counterparty', 'getCounterpartyOptions', array('Ref' => $ref));
    }

    /**
     * getCounterpartyByEDRPOU() function of model Counterparty.
     *
     * @param string $edrpou  EDRPOU code
     * @param string $cityRef City ID
     *
     * @return mixed
     */
    public function getCounterpartyByEDRPOU($edrpou, $cityRef)
    {
        return $this->request('Counterparty', 'getCounterpartyByEDRPOU', array('EDRPOU' => $edrpou, 'cityRef' => $cityRef));
    }

    /**
     * Check required fields for new InternetDocument and set defaults.
     *
     * @param array &$counterparty Recipient info array
     */
    protected function checkInternetDocumentRecipient(array &$counterparty)
    {
        // Check required fields
        if (!$counterparty['FirstName']) {
            throw new \Exception('FirstName is required filed for recipient');
        }
        // MiddleName realy is not required field, but manual says otherwise
        // if ( ! $counterparty['MiddleName'])
        // throw new \Exception('MiddleName is required filed for sender and recipient');
        if (!$counterparty['LastName']) {
            throw new \Exception('LastName is required filed for recipient');
        }
        if (!$counterparty['Phone']) {
            throw new \Exception('Phone is required filed for recipient');
        }
        if (!($counterparty['City'] or $counterparty['CityRef'])) {
            throw new \Exception('City is required filed for recipient');
        }
        if (!($counterparty['Region'] or $counterparty['CityRef'])) {
            throw new \Exception('Region is required filed for recipient');
        }

        // Set defaults
        if (empty($counterparty['CounterpartyType'])) {
            $counterparty['CounterpartyType'] = 'PrivatePerson';
        }
    }

    /**
     * Check required params for new InternetDocument and set defaults.
     *
     * @param array &$params
     */
    protected function checkInternetDocumentParams(array &$params)
    {
        if (!$params['Description']) {
            throw new \Exception('Description is required filed for new Internet document');
        }
        if (!$params['Weight']) {
            throw new \Exception('Weight is required filed for new Internet document');
        }
        if (!$params['Cost']) {
            throw new \Exception('Cost is required filed for new Internet document');
        }
        empty($params['DateTime']) and $params['DateTime'] = date('d.m.Y');
        empty($params['ServiceType']) and $params['ServiceType'] = 'WarehouseWarehouse';
        empty($params['PaymentMethod']) and $params['PaymentMethod'] = 'Cash';
        empty($params['PayerType']) and $params['PayerType'] = 'Recipient';
        empty($params['SeatsAmount']) and $params['SeatsAmount'] = '1';
        empty($params['CargoType']) and $params['CargoType'] = 'Cargo';
        if($params['CargoType'] != 'Documents') {
            empty($params['VolumeGeneral']) and $params['VolumeGeneral'] = '0.0004';
            empty($params['VolumeWeight']) and $params['VolumeWeight'] = $params['Weight'];
        }
    }

    /**
     * Create Internet Document by.
     *
     * @param array $sender    Sender info.
     *                         Required:
     *                         For existing sender:
     *                         'Description' => String (Full name i.e.), 'City' => String (City name)
     *                         For creating:
     *                         'FirstName' => String, 'MiddleName' => String,
     *                         'LastName' => String, 'Phone' => '000xxxxxxx', 'City' => String (City name), 'Region' => String (Region name),
     *                         'Warehouse' => String (Description from getWarehouses))
     * @param array $recipient Recipient info, same like $sender param
     * @param array $params    Additional params of Internet Document
     *                         Required:
     *                         'Description' => String, 'Weight' => Float, 'Cost' => Float
     *                         Recommended:
     *                         'VolumeGeneral' => Float (default = 0.004), 'SeatsAmount' => Int (default = 1),
     *                         'PayerType' => (Sender|Recipient - default), 'PaymentMethod' => (NonCash|Cash - default)
     *                         'ServiceType' => (DoorsDoors|DoorsWarehouse|WarehouseDoors|WarehouseWarehouse - default)
     *                         'CargoType' => String
     * @return mixed
     */
    public function newInternetDocument($sender, $recipient, $params)
    {
        // Check for required params and set defaults
        $this->checkInternetDocumentRecipient($recipient);
        $this->checkInternetDocumentParams($params);
        if (empty($sender['CitySender'])) {
            $senderCity = $this->getCity($sender['City'], $sender['Region'], $sender['Warehouse']);
            $sender['CitySender'] = $senderCity['data'][0]['Ref'];
        }
        $sender['CityRef'] = $sender['CitySender'];
        if (empty($sender['SenderAddress']) and $sender['CitySender'] and $sender['Warehouse']) {
            $senderWarehouse = $this->getWarehouse($sender['CitySender'], $sender['Warehouse']);
            $sender['SenderAddress'] = $senderWarehouse['data'][0]['Ref'];
        }
        if (empty($sender['Sender'])) {
            $sender['CounterpartyProperty'] = 'Sender';
            $fullName = trim($sender['LastName'].' '.$sender['FirstName'].' '.$sender['MiddleName']);
            // Set full name to Description if is not set
            if (empty($sender['Description'])) {
                $sender['Description'] = $fullName;
            }
            // Check for existing sender
            $senderCounterpartyExisting = $this->getCounterparties('Sender', 1, $fullName, $sender['CityRef']);
            // Copy user to the selected city if user doesn't exists there
            if (isset($senderCounterpartyExisting['data'][0]['Ref'])) {
                // Counterparty exists
                $sender['Sender'] = $senderCounterpartyExisting['data'][0]['Ref'];
                $contactSender = $this->getCounterpartyContactPersons($sender['Sender']);
                $sender['ContactSender'] = $contactSender['data'][0]['Ref'];
                $sender['SendersPhone'] = isset($sender['Phone']) ? $sender['Phone'] : $contactSender['data'][0]['Phones'];
            }
        }

        // Prepare recipient data
        $recipient['CounterpartyProperty'] = 'Recipient';
        $recipient['RecipientsPhone'] = $recipient['Phone'];
        if (empty($recipient['CityRecipient'])) {
            $recipientCity = $this->getCity($recipient['City'], $recipient['Region'], $recipient['Warehouse']);
            $recipient['CityRecipient'] = $recipientCity['data'][0]['Ref'];
        }
        $recipient['CityRef'] = $recipient['CityRecipient'];
        if (empty($recipient['RecipientAddress'])) {
            $recipientWarehouse = $this->getWarehouse($recipient['CityRecipient'], $recipient['Warehouse']);
            $recipient['RecipientAddress'] = $recipientWarehouse['data'][0]['Ref'];
        }
        if (empty($recipient['Recipient'])) {
            $recipientCounterparty = $this->model('Counterparty')->save($recipient);
            $recipient['Recipient'] = $recipientCounterparty['data'][0]['Ref'];
            $recipient['ContactRecipient'] = $recipientCounterparty['data'][0]['ContactPerson']['data'][0]['Ref'];
        }
        // Full params is merge of arrays $sender, $recipient, $params
        $paramsInternetDocument = array_merge($sender, $recipient, $params);
        // Creating new Internet Document
        return $this->model('InternetDocument')->save($paramsInternetDocument);
    }
}
