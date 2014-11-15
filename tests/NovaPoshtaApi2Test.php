<?php
require_once dirname(__FILE__).'/../src/NovaPoshtaApi2.php';
/**
 * phpUnit test class
 * 
 * @author lis-dev
 * @version 0.01
 */
class NovaPoshtaApi2Test extends PHPUnit_Framework_TestCase
{
	/**
	 * Key for connection
	 * 
	 * @see https://my.novaposhta.ua/settings/index#apikeys
	 */
	private $key = '';
	/**
	 * Instace of tested class
	 */
	private $np;
	/**
	 * Set up before each test
	 */
	function setUp() {
		// Create new instance
		$this->np = new NovaPoshtaApi2($this->key);
	}
	/**
	 * getKey()
	 */
	function testGetKey() {
		$result = $this->np->getKey();
		$this->assertTrue($result != '');
	}
	
	/**
	 * getFormat()
	 */
	function testGetFormat() {
		$result = $this->np->getFormat();
		$this->assertTrue($result != '');
	}
	
	/**
	 * documentsTracking() result in array
	 */
	function testDocumentsTrackingResultArray() {
		$result = $this->np->documentsTracking('59000082032106');
		$this->assertTrue($result['success']);
	}
	
	/**
	 * documentsTracking() result in json
	 */
	function testDocumentsTrackingResultJson() {
		$result = $this->np->setFormat('json')->documentsTracking('59000082032106');
		$result = json_decode($result, 1);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * documentsTracking() result in xml
	 */
	function testDocumentsTrackingResultJsonXml() {
		$result = $this->np->setFormat('xml')->documentsTracking('59000082032106');
		$result = simplexml_load_string($result);
		$result = json_encode($result);
		$result = json_decode($result, 1);
		$this->assertEquals($result['success'], 'true');
	}
	
	/**
	 * Get cities list by city name
	 * @dataProvider testGetCitiesData
	 */
	function testGetCities($cityPage, $cityRef, $cityName) {
		$result = $this->np->getCities($cityPage, $cityRef, $cityName);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Data provider for testGetCities
	 */
	function testGetCitiesData() {
		return array(
			array(0, 'Киев', ''),
			array(1, '', ''),
			array(0, '', 'a9280688-94c0-11e3-b441-0050568002cf'),
		);
	}
	
	/**
	 * Get Areas
	 * 
	 * @dataProvider testGetAreaData
	 */
	function testGetArea($areaName, $areaRef) {
		$result = $this->np->getArea($areaName, $areaRef);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Data provider for testGetArea
	 */
	function testGetAreaData() {
		return array(
			array('Киев', ''),
			array('Чернігівська', ''),
			array('Днепропетровск', ''),
			array('Запорожская', ''),
			array('Одеська', ''),
			array('', '7150813e-9b87-11de-822f-000c2965ae0e'),
			array('', '7150813d-9b87-11de-822f-000c2965ae0e'),
			array('', '71508135-9b87-11de-822f-000c2965ae0e'),
			array('Одеська', '71508135-9b87-11de-822f-000c2965ae0e'),
		);
	}
	
	/**
	 * Get empty getArea
	 */
	function testGetAreaEmpty() {
		$result = $this->np->getArea('', '');
		$this->assertFalse($result['success']);
	}
	
	/**
	 * getCity()
	 * 
	 * @dataProvider testGetCityData
	 */
	function testGetCity($cityName, $regionName) {
		$result = $this->np->getCity($cityName, $regionName);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Data provider for testGetCity
	 */
	function testGetCityData() {
		return array(
			array('Андреевка', 'Запорожье'),
			array('Андреевка', 'Харьковская'),
			array('Мариуполь', 'Донецька'),
			array('Николаев', 'Николаев'),
		);
	}
	
	/**
	 * Getting language
	 */
	function testLanguageGet() {
		$language = $this->np->getLanguage();
		$this->assertNotEmpty($language);
	}
	
	/**
	 * Get list of Common model methods
	 *
	 * @dataProvider testGetCommonData
	 */
	function testGetCommon($method) {
		$result = $this->np->$method();
		$this->assertTrue($result['success']);
	}

	/**
	 * Data provider for testGetCommon, returns list of method
	 */
	function testGetCommonData() {
		return array(
			array('getTypesOfCounterparties'),
			array('getBackwardDeliveryCargoTypes'),
			array('getCargoDescriptionList'),
			array('getCargoTypes'),
			array('getDocumentStatuses'),
			array('getOwnershipFormsList'),
			array('getPalletsList'),
			array('getPaymentForms'),
			// Required to sign the agreement
			// array('getTimeIntervals'), 
			array('getServiceTypes'),
			array('getTiresWheelsList'),
			array('getTraysList'),
			array('getTypesOfAlternativePayers'),
			// Required to sign the agreement
			// array('getTypesOfPayers'),
			array('getTypesOfPayersForRedelivery'),
		);
	}
	
	/**
	 * Call __call with unregistered method 
	 */
	function testGetCommonError() {
		$result = $this->np->someUnregisteredMethod();
		$this->assertEmpty($result);
	}
}
