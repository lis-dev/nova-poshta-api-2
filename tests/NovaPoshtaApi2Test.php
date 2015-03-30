<?php
namespace LisDev\Tests;
use LisDev\Delivery\NovaPoshtaApi2;
/**
 * phpUnit test class
 * 
 * @author lis-dev
 */
class NovaPoshtaApi2Test extends \PHPUnit_Framework_TestCase
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
	 * Set up before class
	 */
	static function setUpBeforeClass() {
		// Disable notices
		error_reporting(E_ALL ^ E_NOTICE);
	}
	/**
	 * Set up before each test
	 */
	function setUp() {
		// Create new instance
		$this->np = new NovaPoshtaApi2($this->key);
	}
	
	/**
	 * Test connectin via file_get_contents()
	 */
	function testSetConnectionType() {
		$result = $this->np->setConnectionType('file_get_contents');
		$this->assertInstanceOf('LisDev\Delivery\NovaPoshtaApi2', $result);
	}

	/**
	 * getConnectionType()
	 */
	function testGetConnectionType() {
		$result = $this->np->getConnectionType();
		$this->assertNotEmpty($result);
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
	 * Test request via file_get_content
	 */
	function testRequestViaFileGetContent() {
		$result = $this->np->setConnectionType('file_get_content')->documentsTracking('59000082032106');
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
	 * Get warehouses list by city id
	 */
	function testGetWarehouses() {
		$result = $this->np->getWarehouses('a9280688-94c0-11e3-b441-0050568002cf');
		$this->assertTrue($result['success']);
	}
		
	/**
	 * findNearestWarehouse()
	 */
	function testFindNearestWarehouse() {
		$result = $this->np->findNearestWarehouse(array('Одесса', 'Донецкая область'));
		$this->assertTrue($result['success']);
	}
	
	/**
	 * getStreet() 
	 */
	function testGetStreet() {
		$result = $this->np->getStreet('a9280688-94c0-11e3-b441-0050568002cf');
		$this->assertTrue($result['success']);
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
	 * getAreas()
	 */
	function testGetAreas() {
	    $result = $this->np->getAreas();
	    $this->assertTrue($result['success']);
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
		$result = $this->np->model('Counterparty')->save(array(
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
		$result = $this->np->model('Counterparty')->save(array(
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
		$result = $this->np->model('Counterparty')->update(array(
			'Ref' => $ref,
			'CounterpartyProperty' => 'Recipient',
			// City code of 'Андреевка (Харьков)'
			'CityRef' => 'a9280688-94c0-11e3-b441-0050568002cf',
			'CounterpartyType' => 'PrivatePerson',
			'FirstName' => 'Петр',
			'MiddleName' => 'Сидорович',
			'LastName' => 'Иванович',
			'Phone' => '380501112234',
		));
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Save for ContactPerson model 
	 * Must be failed with error "PrivatePerson can not create ContactPerson"
	 * 
	 * @depends testCounterpartySave
	 */
	function testContactPersonSave($ref) {
		$result = $this->np->model('ContactPerson')->save(array(
			'CounterpartyRef' => $ref,
			'FirstName' => 'Сидор',
			'MiddleName' => 'Иванович',
			'LastName' => 'Петров',
			'Phone' => '0501112255',
		));
		$this->assertFalse($result['success']);
		return $result['data'][0]['Ref'];
	}
	
	/**
	 * Update for ContactPerson model
	 * 
	 * @depends testCounterpartySave
	 */
	function testContactPersonUpdate($counterpartyRef) {
		$existedContactPerson = $this->np->getCounterpartyContactPersons($counterpartyRef);
		$result = $this->np->model('ContactPerson')->update(array(
			'Ref' => $existedContactPerson['data'][0]['Ref'],
			'CounterpartyRef' => $counterpartyRef,
			'FirstName' => 'Петр',
			'MiddleName' => 'Сидорович',
			'LastName' => 'Иванов',
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
		$result = $this->np->model('ContactPerson')->delete(array('Ref' => $ref));
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
		$result = $this->np->model('Counterparty')->delete(array('Ref' => $params['Ref']));
		$this->assertTrue($result['success']);
	}
	 */
	
	/**
	 * Delete for Counterparty model
	 * 
	 * @depends testCounterpartySave
	 */
	function testCounterpartyDelete($ref) {
		$result = $this->np->model('Counterparty')->delete(array('Ref' => $ref));
		$this->assertTrue($result['success']);
	}
	
	/**
	 * cloneLoyaltyCounterpartySender() of Counterparty model
	 */
	function testCloneLoyaltyCounterpartySender() {
		$result = $this->np->cloneLoyaltyCounterpartySender('f4890a83-8344-11df-884b-000c290fbeaa');
		$this->assertTrue($result['success']);
		return $result;
	}
	
	/**
	 * Get the warehouse by city id and description
	 */
	function testGetWarehouseManyInCity() {
		$result = $this->np->getWarehouse('db5c88d1-391c-11dd-90d9-001a92567626', 'Відділення №2 (до 30 кг): вул. Дзержинського, 54');
		$this->assertTrue($result['success']);
	}
	
	/**
	 * Get the warehouse by city id and description
	 */
	function testGetWarehouseOneInCity() {
		$result = $this->np->getWarehouse('69da41b4-3f5d-11de-b509-001d92f78698');
		$this->assertTrue($result['success']);
	}
	
	/**
	 * getDocumentPrice()
	 */
	function testGetDocumentPrice() {
		$result = $this->np->getDocumentPrice(
			'db5c88d1-391c-11dd-90d9-001a92567626',
			'8d5a980d-391c-11dd-90d9-001a92567626',
			'WarehouseWarehouse',
			50,
			0.5
		);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * getDocumentDeliveryDate()
	 */
	function testGetDocumentDeliveryDate() {
		$result = $this->np->getDocumentDeliveryDate(
			'db5c88d1-391c-11dd-90d9-001a92567626',
			'8d5a980d-391c-11dd-90d9-001a92567626',
			'WarehouseWarehouse',
			date('d.m.Y')
		);
		$this->assertTrue($result['success']);
	}
	
	/**
	 * getDocumentList()
	 */
	function testGetDocumentList($params = NULL) {
	    $result = $this->np->getDocumentList();
	    $this->assertTrue($result['success']);
	    return $result['data'][0]['Ref'];
	}
	
	/**
	 * generateReport()
	 */
	function testGenerateReport() {
	    // Must return xls with headers
	    $result = $this->np->generateReport(array('Type' => 'xls', 'DocumentRefs' => '1234', 'DateTime' => date('d.m.Y')));
	    $this->assertEmpty($result);
	}
	
	/**
	 * Get first existing sender
	 */
	function testNewInternetDocumentGetSender() {
		$existingSender = $this->np->getCounterparties('Sender', 1, '', '');
		$this->assertNotEmpty($existingSender['data'][0]);
		return $existingSender['data'][0];
	}
	
	/**
	 * newInternetDocument()
	 * 
	 * This test must be called much before deleting test to spend 
	 *  much time to process document on server side of NovaPoshtaAPI
	 *   
	 * @param array $sender Required sender info
	 * @depends testNewInternetDocumentGetSender
	 */
	function testNewInternetDocument($sender) {
		$result = $this->np->newInternetDocument(
			array(
				'LastName' => $sender['LastName'],
				'FirstName' => $sender['FirstName'],
				'MiddleName' => $sender['MiddleName'],
				'City' => 'Киев',
				'Region' => 'Киевская',
				'Warehouse' => 'Отделение №82 (до 30 кг): ул. Горького, 157',
			),
			array(
				'FirstName' => 'Сидор',
				'MiddleName' => 'Сидорович',
				'LastName' => 'Сиродов',
				'Phone' => '0509998877',
				'City' => 'Киев',
				'Region' => 'Киевская',
				'Warehouse' => 'Отделение №3: ул. Калачевская, 13 (Старая Дарница)',
			),
			array(
				'DateTime' => date('d.m.Y', time() + 84600),
				'ServiceType' => 'WarehouseWarehouse',
				'PaymentMethod' => 'Cash',
				'PayerType' => 'Recipient',
				'Cost' => '500',
				'SeatsAmount' => '1',
				'Description' => 'Спутник',
				'CargoType' => 'Cargo',
				'Weight' => '10',
				'VolumeGeneral' => '0.5',
			)
		);
		$this->assertTrue($result['success']);
		return $result['data'][0]['Ref'];
	}
	
	/**
	 * getDocument()
	 *
	 * @depends testNewInternetDocument
	 */
	function testGetDocument($ref) {
	    $result = $this->np->getDocument($ref);
	    $this->assertTrue($result['success']);
	}
	
	/**
	 * printDocument()
	 * 
	 * @depends testNewInternetDocument
	 */
	function testPrintDocument($ref) {
		/*
		There is unexsisted DocumentRef, because if will real id there will not
		any chance delete this tested document 
		*/
		$result = $this->np->printDocument('123');
		$this->assertTrue($result['success']);
	}

	/**
	 * printDocument()
	 * 
	 * @depends testNewInternetDocument
	 */
	function testPrintDocumentGetLink($ref) {
		/*
		 There is unexsisted DocumentRef, because if will real id there will not
		 any chance delete this tested document
		 */
		$result = $this->np->printDocument('123', 'html_link');
		$this->assertTrue($result['success']);
	}
	
	/**
	 * printMarkings()
	 * 
	 * @depends testNewInternetDocument
	 */
	function testPrintMarkings($ref) {
		/*
		There is unexsisted DocumentRef, because if will real id there will not
		any chance delete this tested document 
		*/
		$result = $this->np->printMarkings('123');
		$this->assertTrue($result['success']);
	}
	
	/**
	 * printMarkings()
	 * 
	 * @depends testNewInternetDocument
	 */
	function testPrintMarkingsGetLink($ref) {
		/*
		 There is unexsisted DocumentRef, because if will real id there will not
		 any chance delete this tested document
		 */
		$result = $this->np->printMarkings('123', 'html_link');
		$this->assertTrue($result['success']);
	}
}