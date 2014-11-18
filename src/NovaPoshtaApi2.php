<?php
/**
 * Nova Poshta API Class
 * 
 * @author lis-dev
 * @see https://my.novaposhta.ua/data/API2-071114-1736-56.pdf
 * @see https://github.com/lis-dev
 * @license MIT
 */
class NovaPoshtaApi2 {
	/**
	 * Key for API NovaPoshta
	 * 
	 * @var string $key
	 * @see https://my.novaposhta.ua/settings/index#apikeys
	 */
	protected $key;
	
	/**
	 * @var string $format Format of returned data - array, json, xml
	 */
	protected $format = 'array';
	
	/**
	 * @var string $language Language of response
	 */
	protected $language = 'ru';
	
	/**
	 * @var string $areas Areas (loaded from file, because there is no so function in NovaPoshta API 2.0)
	 */
	protected $areas;
	
	/**
	 * @var string $model Set current model for methods save(), update(), delete()
	 */
	protected $model = 'Common';

	/**
	 * Default constructor
	 * 
	 * @param string $key NovaPoshta API key
	 * @param string $language Default Language
	 * @return NovaPoshtaApi2 
	 */
	function __construct($key, $language = 'ru') {
		return $this	
			->setKey($key)
			->setLanguage($language)
			->common();
	}
	
	/**
	 * Setter for key property
	 * 
	 * @param string $key NovaPoshta API key
	 * @return NovaPoshtaApi2
	 */
	function setKey($key) {
		$this->key = $key;
		return $this;
	}
	
	/**
	 * Getter for key property
	 * 
	 * @return string
	 */
	function getKey() {
		return $this->key;
	}
	
	/**
	 * Setter for language property
	 * 
	 * @param string $language
	 * @return NovaPoshtaApi2
	 */
	function setLanguage($language) {
		$this->language = $language;
		return $this;
	}
	
	/**
	 * Getter for language property
	 * 
	 * @return string
	 */
	function getLanguage() {
		return $this->language;
	}
	
	/**
	 * Setter for format property
	 * 
	 * @param string $format Format of returned data by methods (json, xml, array)
	 * @return NovaPoshtaApi2 
	 */
	function setFormat($format) {
		$this->format = $format;
		return $this;
	}
	
	/**
	 * Getter for format property
	 * 
	 * @return string
	 */
	function getFormat() {
		return $this->format;
	}
	
	/**
	 * Prepare data before return it
	 * 
	 * @param json $data
	 * @return mixed
	 */
	private function prepare($data) {
		//Returns array
		if ($this->format == 'array') {
			return is_array($data)
				? $data
				: json_decode($data, 1);
		}
		// Returns json or xml document
		return $data;
	}
	
	/**
	 * Converts array to xml
	 * 
	 * @param array 
	 */
	private function array2xml(array $array, $xml = false){
		($xml === false) AND $xml = new SimpleXMLElement('<root/>');
		foreach($array as $key => $value){
			if (is_array($value)){
				$this->array2xml($value, $xml->addChild($key));
			} else {
				$xml->addChild($key, $value);
			}
		}
		return $xml->asXML();
	}
	
	/**
	 * Make request to NovaPoshta API
	 * 
	 * @param string $model Model name
	 * @param string $method Method name
	 * @param array $params Required params
	 */
	function request($model, $method, $params = NULL) {
		// Get required URL
		$url = $this->format == 'xml'
			? 'https://api.novaposhta.ua/v2.0/xml/'
			: 'https://api.novaposhta.ua/v2.0/json/';
		
		$data = array(
			'apiKey' => $this->key,
			'modelName' => $model,
			'calledMethod' => $method,
			'language' => $this->language,
			'methodProperties' => $params
		);
		// Convert data to neccessary format
		$post = $this->format == 'xml'
			? $this->array2xml($data)
			: $post = json_encode($data);
			
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: '.($this->format == 'xml' ? 'text/xml' : 'application/json')));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$result = curl_exec($ch);
		curl_close($ch);
		return $this->prepare($result);
	}
	
	/**
	 * Get tracking information by track number
	 * 
	 * @param string $track Track number
	 * @return mixed
	 */
	function documentsTracking($track) {
		return $this->request('InternetDocument', 'documentsTracking', array('Documents' => array('item' => $track)));
	}
	
	/**
	 * Get cities of company NovaPoshta
	 * 
	 * @param int $page Num of page
	 * @param string $findByString Find city by russian or ukrainian word
	 * @param string $ref ID of city
	 * @return mixed
	 */
	function getCities($page = 0, $findByString = '', $ref = '') {
		return $this->request('Address', 'getCities', array(
			'Page' => $page,
			'FindByString' => $findByString,
			'Ref' => $ref,
		));
	}
	
	/**
	 * Find current area in list of areas
	 * 
	 * @param array $areas List of arias, getted from file
	 * @param string $findByString Area name
	 * @param string $ref Area Ref ID
	 * @return array
	 */
	protected function findArea(array $areas, $findByString = '', $ref = '') {
		$data = array();
		if ( ! $findByString AND ! $ref)
			return $data;
		// Try to find current region
		foreach ($areas as $key => $area) {
			// Is current area found by string or by key
			$found = $findByString 
				? ((mb_stripos($area['Description'], $findByString) !== FALSE) 
					OR (mb_stripos($area['DescriptionRu'], $findByString) !== FALSE)
					OR (mb_stripos($area['Area'], $findByString) !== FALSE)
					OR (mb_stripos($area['AreaRu'], $findByString) !== FALSE))
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
	 * Get area by name or by ID
	 * 
	 * @param string $findByString Find area by russian or ukrainian word
	 * @param string $ref Get area by ID
	 * @return array
	 */
	function getArea($findByString = '', $ref = '') {
		// Load areas list from file
		empty($this->areas) AND $this->areas = include dirname(__FILE__).'/NovaPoshtaApi2Areas.php';
		$error = '';
		$data = $this->findArea($this->areas, $findByString, $ref);
		// Error
		empty($data) AND $error = 'Area was not found';
		// Return data in same format like NovaPoshta API
		return $this->prepare(
			array(
				'success' => empty($error),
				'data' => $data,
				'errors' => (array) $error,
				'warnings' => array(),
				'info' => array(),
		));
	}
	
	/**
	 * Find city from list by name of region
	 * 
	 * @param array $cities Array from query getCities to NovaPoshta 
	 * @param string $areaName
	 * @return array 
	 */
	protected function findCityByRegion($cities, $areaName) {
		$data = array();
		$areaRef = '';
		// Get region id
		$area = $this->getArea($areaName);
		$area['success'] AND $areaRef = $area['data'][0]['Ref'];
		if ($areaRef AND is_array($cities['data'])) {
			foreach($cities['data'] as $city) {
				if ($city['Area'] == $areaRef) {
					$data[] = $city;
				}
			}
		}
		return $data;
	}
	
	/**
	 * Get city by name and region (if it needs)
	 * 
	 * @param string $cityName City's name
	 * @param string $areaName Region's name
	 * @return array City's data
	 */
	function getCity($cityName, $areaName = '') {
		$error = '';
		// Get cities by name
		$cities = $this->getCities(0, $cityName);
		if (is_array($cities['data'])) {
			// If cities more then one, calculate current by area name
			$data = (count($cities['data']) > 1) 
				? $this->findCityByRegion($cities, $areaName)
				: $cities['data'][0];
		}
		// Error
		( ! $data) AND $error = 'City was not found';
		// Return data in same format like NovaPoshta API
		return $this->prepare(
			array(
				'success' => empty($error),
				'data' => $data,
				'errors' => (array) $error,
				'warnings' => array(),
				'info' => array(),
		));
	}
	
	/**
	 * Magic method of calling functions (uses for calling Common Model of NovaPoshta API)
	 * 
	 * @param string $method Called method of Common Model
	 * @param array $arguments Array of params
	 */
	function __call($method, $arguments) {
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
			return $this->request('Common', $method);
		}
	}
	
	/**
	 * Set current model to Common
	 * 
	 * @return this
	 */
	function common() {
		$this->model = 'Common';
		return $this;
	}
	
	/**
	 * Set current model to ContactPerson
	 * 
	 * @return this
	 */
	function contactPerson() {
		$this->model = 'ContactPerson';
		return $this;
	}
	
	/**
	 * Set current model to Counterparty
	 * 
	 * @return this
	 */
	function counterparty() {
		$this->model = 'Counterparty';
		return $this;
	}
	
	/**
	 * Delete method of current model
	 * 
	 * @param array $params
	 * @return mixed
	 */
	function delete($params) {
		return $this->request($this->model, 'delete', $params);
	}
	
	/**
	 * Update method of current model
	 * Required params: 
	 * For ContactPerson model: Ref, CounterpartyRef, FirstName (ukr), MiddleName, LastName, Phone (format 0xxxxxxxxx)
	 * For Counterparty model: Ref, CounterpartyProperty (Recipient|Sender), CityRef, CounterpartyType (Organization, PrivatePerson), 
	 * FirstName (or name of organization), MiddleName, LastName, Phone (0xxxxxxxxx), OwnershipForm (if Organization)
	 * 
	 * @param array $params
	 * @return mixed
	 */
	function update($params) {
		return $this->request($this->model, 'update', $params);
	}
	
	/**
	 * Save method of current model
	 * Required params: 
	 * For ContactPerson model: CounterpartyRef, FirstName (ukr), MiddleName, LastName, Phone (format 0xxxxxxxxx)
	 * For Counterparty model: CounterpartyProperty (Recipient|Sender), CityRef, CounterpartyType (Organization, PrivatePerson), 
	 * FirstName (or name of organization), MiddleName, LastName, Phone (0xxxxxxxxx), OwnershipForm (if Organization)
	 * 
	 * @param array $params
	 * @return mixed
	 */
	function save($params) {
		return $this->request($this->model, 'save', $params);
	}
	
	/**
	 * getCounterparties() function of model Counterparty
	 * 
	 * @param string $counterpartyProperty Type of Counterparty (Sender|Recipient)
	 * @param int $page Page number
	 * @param string $findByString String to search
	 * @param string $cityRef City ID
	 * @return mixed
	 */
	function getCounterparties($counterpartyProperty = 'Recipient', $page = NULL, $findByString = NULL, $cityRef = NULL) {
		// Any param can be skipped
		$params = array();
		$params['CounterpartyProperty'] = $counterpartyProperty ? $counterpartyProperty : 'Recipient';
		$page AND $params['Page'] = $page;
		$findByString AND $params['FindByString'] = $findByString;
		$cityRef AND $params['CityRef'] = $cityRef;
		return $this->request('Counterparty', 'getCounterparties', $params);
	}
	
	/**
	 * cloneLoyaltyCounterpartySender() function of model Counterparty
	 * 
	 * @param string $cityRef City ID
	 * @return mixed
	 */
	function cloneLoyaltyCounterpartySender($cityRef) {
		return $this->request('Counterparty', 'cloneLoyaltyCounterpartySender', array('CityRef' => $cityRef));
	}
	
	/**
	 * getCounterpartyContactPersons() function of model Counterparty
	 * 
	 * @param string $ref Counterparty ref
	 * @return mixed
	 */
	function getCounterpartyContactPersons($ref) {
		return $this->request('Counterparty', 'getCounterpartyContactPersons', array('Ref' => $ref));
	}
	
	/**
	 * getCounterpartyAddresses() function of model Counterparty
	 * 
	 * @param string $ref Counterparty ref
	 * @param int $page
	 * @return mixed
	 */
	function getCounterpartyAddresses($ref, $page = 0) {
		return $this->request('Counterparty', 'getCounterpartyAddresses', array('Ref' => $ref, 'Page' => $page));
	}
	
	/**
	 * getCounterpartyOptions() function of model Counterparty
	 * 
	 * @param string $ref Counterparty ref
	 * @return mixed
	 */
	function getCounterpartyOptions($ref) {
		return $this->request('Counterparty', 'getCounterpartyOptions', array('Ref' => $ref));
	}
	
	/**
	 * getCounterpartyByEDRPOU() function of model Counterparty
	 * 
	 * @param string $edrpou EDRPOU code
	 * @param string $cityRef City ID
	 * @return mixed
	 */
	function getCounterpartyByEDRPOU($edrpou, $cityRef) {
		return $this->request('Counterparty', 'getCounterpartyByEDRPOU', array('EDRPOU' => $edrpou, 'cityRef' => $cityRef));
	}
}