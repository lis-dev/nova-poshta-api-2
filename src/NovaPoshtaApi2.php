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
	 * @see https://my.novaposhta.ua/settings/index#apikeys
	 */
	 
	private $key;
	
	/**
	 * Format of returned data - array, json, xml
	 */
	private $format = 'array';
	
	/**
	 * Default constructor
	 * 
	 * @param string $key NovaPoshta API key
	 * @return NovaPoshtaApi2 
	 */
	function __construct($key) {
		$this->setKey($key);
		return $this;
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
		if ($this->format == 'array')
			return json_decode($data, 1);
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
	function request($model, $method, $params = array()) {
		// Get required URL
		$url = $this->format == 'xml'
			? 'https://api.novaposhta.ua/v2.0/xml/'
			: 'https://api.novaposhta.ua/v2.0/json/';
		
		$data = array(
			'apiKey' => $this->key,
			'modelName' => $model,
			'calledMethod' => $method,
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
}