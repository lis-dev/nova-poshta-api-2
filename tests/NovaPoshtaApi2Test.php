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
	 * @dataProvider getCitiesData
	 */
	function testGetCities($cityPage, $cityRef, $cityName) {
		$result = $this->np->getCities($cityPage, $cityRef, $cityName);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Data provider for testGetCities
	 */
	function getCitiesData() {
		return array(
			array(0, 'Киев', ''),
			array(1, '', ''),
			array(0, '', 'a9280688-94c0-11e3-b441-0050568002cf'),
		);
	}
	
	/**
	 * Get Areas
	 * 
	 * @dataProvider getAreaData
	 */
	function testGetArea($areaName, $areaRef) {
		$result = $this->np->getArea($areaName, $areaRef);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Data provider for testGetArea
	 */
	function getAreaData() {
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
	 * @dataProvider getCityData
	 */
	function testGetCity($cityName, $regionName) {
		$result = $this->np->getCity($cityName, $regionName);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Data provider for testGetCity
	 */
	function getCityData() {
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
	 * @dataProvider getCommonData
	 */
	function testGetCommon($method) {
		$result = $this->np->$method();
		$this->assertTrue($result['success']);
	}

	/**
	 * Data provider for testGetCommon, returns list of method
	 */
	function getCommonData() {
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
	
	/**
	 * Save method of Counterparty model
	 */
	function testCounterpartySave() {
		$result = $this->np->counterparty()->save(array(
			'CounterpartyProperty' => 'Recipient',
			'CityRef' => 'f4890a83-8344-11df-884b-000c290fbeaa',
			'CounterpartyType' => 'PrivatePerson',
			'FirstName' => 'Иван',
			'MiddleName' => 'Иванович',
			'LastName' => 'Иванов',
			'Phone' => '380501112233',
		));
		$this->assertTrue($result['success']);
		return $result['data'][0]['Ref'];
	}
	
	/**
	 * Save method of Counterparty model for Organization
	 * 
	 * TODO Test always is failed with error "Organization does not exists or incorrect EDRPOU'"
	 * Uncomment this and all depends when this will be fixed
	 */
	function testCounterpartyOrganizationSave() {
		$result = $this->np->counterparty()->save(array(
			'CounterpartyProperty' => 'Recipient',
			'CityRef' => 'f4890a83-8344-11df-884b-000c290fbeaa',
			'CounterpartyType' => 'Organization',
			'FirstName' => 'ООО Рога и Копыта',
			'MiddleName' => '',
			'LastName' => '',
			'Phone' => '80501112233',
			'OwnershipForm' => '7f0f351d-2519-11df-be9a-000c291af1b3',
			'EDRPOU' => '12345678',
		));
		// $this->assertTrue($result['success']);
		/*
		return array(
			'Ref' => $result['data'][0]['Ref'],
			'EDRPOU' => $result['data'][0]['EDRPOU'],
			'CityRef' => $result['data'][0]['CityRef'],
		);
		*/
	}
	
	/**
	 * Update for Counterparty model
	 * 
	 * @depends testCounterpartySave
	 */
	function testCounterpartyUpdate($ref) {
		$result = $this->np->counterparty()->update(array(
			'Ref' => $ref,
			'CounterpartyProperty' => 'Recipient',
			'CityRef' => 'a9280688-94c0-11e3-b441-0050568002cf',
			'CounterpartyType' => 'PrivatePerson',
			'FirstName' => 'Иван1',
			'MiddleName' => 'Иванович1',
			'LastName' => 'Иванов1',
			'Phone' => '380501112234',
		));
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Save for ContactPerson model
	 * 
	 * @depends testCounterpartySave
	 */
	function testContactPersonSave($ref) {
		$result = $this->np->contactPerson()->save(array(
			'CounterpartyRef' => $ref,
			'FirstName' => 'Иван2',
			'MiddleName' => 'Иванович2',
			'LastName' => 'Иванов2',
			'Phone' => '0501112255',
		));
		$this->assertTrue($result['success']);
		return $result['data'][0]['Ref'];
	}
	
	/**
	 * Update for ContactPerson model
	 * 
	 * @depends testContactPersonSave
	 * @depends testCounterpartySave
	 */
	function testContactPersonUpdate($ref, $counterpartyRef) {
		$result = $this->np->contactPerson()->update(array(
			'Ref' => $ref,
			'CounterpartyRef' => $counterpartyRef,
			'FirstName' => 'Иван3',
			'MiddleName' => 'Иванович3',
			'LastName' => 'Иванов3',
			'Phone' => '0501112266',
		));
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Delete for ContactPerson model
	 * ContactPerson of natural counterparty cannot be removed
	 * 
	 * @depends testContactPersonSave
	 */
	function testContactPersonDelete($ref) {
		$result = $this->np->contactPerson()->delete(array('Ref' => $ref));
		// ContactPerson of natural counterparty cannot be removed, so there test assertFalse
		$this->assertFalse($result['success']);
	}
	
	/**
	 * getCounterparties() of Counterparty model
	 * 
	 * @dataProvider getCounterpartiesData
	 */
	function testGetCounterparties($counterpartyProperty, $page, $findByString, $cityRef) {
		$result = $this->np->getCounterparties($counterpartyProperty, $page, $findByString, $cityRef);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Data for testGetCounterparties()
	 */
	function getCounterpartiesData() {
		return array(
			array('Sender', '', '', ''),
			array('', 1, '', ''),
			array('', '', 'Иван', ''),
			array('', '', '', 'f4890a83-8344-11df-884b-000c290fbeaa'),
		);
	}

	/**
	 * testGetCounterpartyContactPersons() of Counterparty model
	 * 
	 * @depends testCounterpartySave
	 */
	function testGetCounterpartyContactPersons($ref) {
		$result = $this->np->getCounterpartyContactPersons($ref);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * getCounterpartyOptions() of Counterparty model
	 * 
	 * @depends testCounterpartySave
	 */
	function testGetCounterpartyOptions($ref) {
		$result = $this->np->getCounterpartyOptions($ref);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * getCounterpartyAddresses() of Counterparty model
	 * 
	 * @depends testCounterpartySave
	 */
	function testGetCounterpartyAddresses($ref) {
		$result = $this->np->getCounterpartyAddresses($ref);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * getCounterpartyByEDRPOU() of Counterparty model
	 * 
	 * TODO Alter test when testCounterpartyOrganizationSave will works correctly
	 * -- depends testCounterpartyOrganizationSave
	 */
	function testGetCounterpartyByEDRPOU() {
		$result = $this->np->getCounterpartyByEDRPOU('12345678', 'f4890a83-8344-11df-884b-000c290fbeaa');
		// $this->assertEmpty($result['success']);
	}

	/**
	 * Delete organization for Counterparty model
	 * 
	 * @depends testCounterpartyOrganizationSave
	function testCounterpartyOrganizationDelete($params) {
		$result = $this->np->counterparty()->delete(array('Ref' => $params['Ref']));
		$this->assertTrue($result['success']);
	}
	 */
	
	/**
	 * Delete for Counterparty model
	 * 
	 * @depends testCounterpartySave
	 */
	function testCounterpartyDelete($ref) {
		$result = $this->np->counterparty()->delete(array('Ref' => $ref));
		$this->assertTrue($result['success']);
	}
	
	/**
	 * cloneLoyaltyCounterpartySender() of Counterparty model
	 */
	function testCloneLoyaltyCounterpartySender() {
		$result = $this->np->cloneLoyaltyCounterpartySender('f4890a83-8344-11df-884b-000c290fbeaa');
		$this->assertTrue($result['success']);
	}
}
