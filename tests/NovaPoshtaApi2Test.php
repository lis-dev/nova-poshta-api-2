<?php

namespace LisDev\Tests;

use LisDev\Delivery\NovaPoshtaApi2;

/**
 * phpUnit test class.
 *
 * @author lis-dev
 */
class NovaPoshtaApi2Test extends \PHPUnit_Framework_TestCase
{
    private static $createdDocumentsRefs = array();

    /**
     * Key for connection.
     *
     * @see https://my.novaposhta.ua/settings/index#apikeys
     */
    private static $key = '';

    /**
     * Test tracking number.
     * TODO: Track number doesn't persists for a long time, so tests can fail due this parameter.
     *
     * @var string
     */
    private $testTrackNumber = '20600009559994';

    /**
     * Instace of tested class.
     *
     * @var NovaPoshtaApi2
     */
    private static $np;

    protected function setUp()
    {
        self::$np = new NovaPoshtaApi2(self::$key);
    }

    public static function setUpBeforeClass()
    {
        !self::$key and self::$key = getenv('NOVA_POSHTA_API2_KEY');
    }

    public static function tearDownAfterClass()
    {
        // Remove internet documents created in this test
        self::$np->model('InternetDocument');
        foreach(self::$createdDocumentsRefs as $ref) {
            self::$np->delete(array('DocumentRefs' => $ref));
        }
    }

    /**
     * Test connection via file_get_contents().
     */
    public function testSetConnectionType()
    {
        $result = self::$np->setConnectionType('file_get_contents');
        $this->assertInstanceOf('LisDev\Delivery\NovaPoshtaApi2', $result);
    }

    /**
     * getConnectionType().
     */
    public function testGetConnectionType()
    {
        $result = self::$np->getConnectionType();
        $this->assertNotEmpty($result);
    }

    /**
     * getKey().
     */
    public function testGetKey()
    {
        $result = self::$np->getKey();
        $this->assertNotEmpty($result);
    }

    /**
     * getFormat().
     */
    public function testGetFormat()
    {
        $result = self::$np->getFormat();
        $this->assertNotEmpty($result);
    }

    /**
     * documentsTracking() result in array.
     */
    public function testDocumentsTrackingResultArray()
    {
        $result = self::$np->documentsTracking($this->testTrackNumber);

        $this->assertTrue($result['success']);
    }

    /**
     * Test request via file_get_content.
     */
    public function testRequestViaFileGetContent()
    {
        $result = self::$np->setConnectionType('file_get_content')->documentsTracking($this->testTrackNumber);
        $this->assertTrue($result['success']);
    }

    /**
     * documentsTracking() result in json.
     */
    public function testDocumentsTrackingResultJson()
    {
        $result = self::$np->setFormat('json')->documentsTracking($this->testTrackNumber);
        $result = json_decode($result, 1);
        $this->assertTrue($result['success']);
    }

    /**
     * documentsTracking() result in xml.
     */
    public function testDocumentsTrackingResultJsonXml()
    {
        $result = self::$np->setFormat('xml')->documentsTracking($this->testTrackNumber);
        $result = simplexml_load_string($result);
        $result = json_encode($result);
        $result = json_decode($result, 1);
        $this->assertEquals($result['success'], 'true');
    }

    /**
     * Get cities list by city name.
     *
     * @dataProvider getCitiesData
     */
    public function testGetCities($cityPage, $cityRef, $cityName)
    {
        $result = self::$np->getCities($cityPage, $cityRef, $cityName);
        $this->assertTrue($result['success']);
    }

    /**
     * Data provider for testGetCities.
     */
    public function getCitiesData()
    {
        return array(
            array(0, 'Киев', ''),
            array(1, '', ''),
            array(0, '', 'a9280688-94c0-11e3-b441-0050568002cf'),
        );
    }

    /**
     * Get warehouses list by city id.
     */
    public function testGetWarehouses()
    {
        $result = self::$np->getWarehouses('a9280688-94c0-11e3-b441-0050568002cf');

        $this->assertTrue($result['success']);
    }

    /**
     * findNearestWarehouse().
     */
    public function testFindNearestWarehouse()
    {
        $result = self::$np->findNearestWarehouse(array('Одесса', 'Донецкая область'));
        $this->assertTrue($result['success']);
    }

    /**
     * getStreet().
     */
    public function testGetStreet()
    {
        $result = self::$np->getStreet('a9280688-94c0-11e3-b441-0050568002cf');
        $this->assertTrue($result['success']);
    }

    /**
     * Get Areas.
     *
     * @dataProvider getAreaData
     */
    public function testGetArea($areaName, $areaRef)
    {
        $result = self::$np->getArea($areaName, $areaRef);
        $this->assertTrue($result['success']);
    }

    /**
     * Data provider for testGetArea.
     */
    public function getAreaData()
    {
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
     * Get empty getArea.
     */
    public function testGetAreaEmpty()
    {
        $result = self::$np->getArea('', '');
        $this->assertFalse($result['success']);
    }

    /**
     * getAreas().
     */
    public function testGetAreas()
    {
        $result = self::$np->getAreas();
        $this->assertTrue($result['success']);
    }

    /**
     * getCity().
     *
     * @dataProvider getCityData
     */
    public function testGetCity($cityName, $regionName)
    {
        $result = self::$np->getCity($cityName, $regionName);
        $this->assertTrue($result['success']);
    }

    /**
     * Data provider for testGetCity.
     */
    public function getCityData()
    {
        return array(
            array('Андреевка', 'Запорожье'),
            array('Андреевка', 'Харьковская'),
            array('Мариуполь', 'Донецька'),
            array('Николаев', 'Николаев'),
        );
    }

    /**
     * Getting language.
     */
    public function testLanguageGet()
    {
        $language = self::$np->getLanguage();
        $this->assertNotEmpty($language);
    }

    /**
     * Get list of Common model methods.
     *
     * @dataProvider getCommonData
     */
    public function testGetCommon($method)
    {
        $result = self::$np->$method();
        $this->assertTrue($result['success']);
    }

    /**
     * Data provider for testGetCommon, returns list of method.
     */
    public function getCommonData()
    {
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
     * Call __call with unregistered method.
     */
    public function testGetCommonError()
    {
        $result = self::$np->someUnregisteredMethod();
        $this->assertEmpty($result);
    }

    /**
     * Save method of Counterparty model.
     */
    public function testCounterpartySave()
    {
        $result = self::$np->model('Counterparty')->save(array(
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
     * Save method of Counterparty model for Organization.
     *
     * TODO Test always is failed with error "Organization does not exists or incorrect EDRPOU'"
     * Uncomment this and all depends when this will be fixed
     */
    public function testCounterpartyOrganizationSave()
    {
        $result = self::$np->model('Counterparty')->save(array(
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
     * Update for Counterparty model.
     *
     * @depends testCounterpartySave
     */
    public function testCounterpartyUpdate($ref)
    {
        $result = self::$np->model('Counterparty')->update(array(
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
     * Now PrivatePerson can  create ContactPerson.
     *
     * @depends testCounterpartySave
     */
    public function testContactPersonSave($ref)
    {
        $result = self::$np->model('ContactPerson')->save(array(
            'CounterpartyRef' => $ref,
            'FirstName' => 'Сидоров',
            'MiddleName' => 'Иванович',
            'LastName' => 'Петров',
            'Phone' => '0501112255',
        ));

        $this->assertTrue($result['success']);

        return $result['data'][0]['Ref'];
    }

    /**
     * Update for ContactPerson model.
     *
     * @depends testCounterpartySave
     */
    public function testContactPersonUpdate($counterpartyRef)
    {
        $existedContactPerson = self::$np->getCounterpartyContactPersons($counterpartyRef);
        $result = self::$np->model('ContactPerson')->update(array(
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
     * ContactPerson of natural counterparty can be removed.
     *
     * @depends testContactPersonSave
     */
    public function testContactPersonDelete($ref)
    {
        $result = self::$np->model('ContactPerson')->delete(array('Ref' => $ref));
        // ContactPerson of natural counterparty cannot be removed, so there test assertFalse
        $this->assertTrue($result['success']);
    }

    /**
     * getCounterparties() of Counterparty model.
     *
     * @dataProvider getCounterpartiesData
     */
    public function testGetCounterparties($counterpartyProperty, $page, $findByString, $cityRef)
    {
        $result = self::$np->getCounterparties($counterpartyProperty, $page, $findByString, $cityRef);
        $this->assertTrue($result['success']);
    }

    /**
     * Data for testGetCounterparties().
     */
    public function getCounterpartiesData()
    {
        return array(
            array('Sender', '', '', ''),
            array('', 1, '', ''),
            array('', '', 'Иван', ''),
            array('', '', '', 'f4890a83-8344-11df-884b-000c290fbeaa'),
        );
    }

    /**
     * testGetCounterpartyContactPersons() of Counterparty model.
     *
     * @depends testCounterpartySave
     */
    public function testGetCounterpartyContactPersons($ref)
    {
        $result = self::$np->getCounterpartyContactPersons($ref);
        $this->assertTrue($result['success']);
    }

    /**
     * getCounterpartyOptions() of Counterparty model.
     *
     * @depends testCounterpartySave
     */
    public function testGetCounterpartyOptions($ref)
    {
        $result = self::$np->getCounterpartyOptions($ref);
        $this->assertTrue($result['success']);
    }

    /**
     * getCounterpartyAddresses() of Counterparty model.
     *
     * @depends testCounterpartySave
     */
    public function testGetCounterpartyAddresses($ref)
    {
        $result = self::$np->getCounterpartyAddresses($ref);
        $this->assertTrue($result['success']);
    }

    /**
     * getCounterpartyByEDRPOU() of Counterparty model.
     *
     * TODO Alter test when testCounterpartyOrganizationSave will works correctly
     * -- depends testCounterpartyOrganizationSave
     */
    /*
    public function testGetCounterpartyByEDRPOU()
    {
        $result = self::$np->getCounterpartyByEDRPOU('12345678', 'f4890a83-8344-11df-884b-000c290fbeaa');
         $this->assertEmpty($result['success']);
    }
    */

    /**
     * Delete organization for Counterparty model.
     *
     * @depends testCounterpartyOrganizationSave
    function testCounterpartyOrganizationDelete($params) {
        $result = self::$np->model('Counterparty')->delete(array('Ref' => $params['Ref']));
        $this->assertTrue($result['success']);
    }
     */

    /**
     * Delete for Counterparty model.
     * Counterparty PrivatePerson can't be deleted, so success should be false.
     *
     *
     * @depends testCounterpartySave
     */
    public function testCounterpartyDelete($ref)
    {
        $result = self::$np->model('Counterparty')->delete(array('Ref' => $ref));

        $this->assertFalse($result['success']);
    }

    /**
     * cloneLoyaltyCounterpartySender() of Counterparty model.
     */
    public function testCloneLoyaltyCounterpartySender()
    {
        $result = self::$np->cloneLoyaltyCounterpartySender('f4890a83-8344-11df-884b-000c290fbeaa');
        $this->assertTrue($result['success']);
        return $result;
    }

    /**
     * Get the warehouse by city id and description.
     */
    public function testGetWarehouseManyInCity()
    {
        $result = self::$np->getWarehouse('db5c88d1-391c-11dd-90d9-001a92567626', 'Відділення №1: вул. Маяковського, 59а');

        $this->assertTrue($result['success']);
    }

    /**
     * Get the warehouse by city id and description.
     */
    public function testGetWarehouseOneInCity()
    {
        $result = self::$np->getWarehouse('db5c88d1-391c-11dd-90d9-001a92567626');

        $this->assertTrue($result['success']);
    }

    /**
     * getDocumentPrice().
     */
    public function testGetDocumentPrice()
    {
        $result = self::$np->getDocumentPrice(
            'db5c88d1-391c-11dd-90d9-001a92567626',
            '8d5a980d-391c-11dd-90d9-001a92567626',
            'WarehouseWarehouse',
            50,
            0.5
        );
        $this->assertTrue($result['success']);
    }

    /**
     * getDocumentDeliveryDate().
     */
    public function testGetDocumentDeliveryDate()
    {
        $result = self::$np->getDocumentDeliveryDate(
            'db5c88d1-391c-11dd-90d9-001a92567626',
            '8d5a980d-391c-11dd-90d9-001a92567626',
            'WarehouseWarehouse',
            date('d.m.Y', time() + 60 * 60 * 4)
        );
        $this->assertTrue($result['success']);
    }

    /**
     * generateReport().
     */
    public function testGenerateReport()
    {
        // Must return xls with headers
        $result = self::$np->generateReport(array('Type' => 'xls', 'DocumentRefs' => array('1fb8943e-14e4-11e5-ad08-005056801333'), 'DateTime' => date('d.m.Y')));
        $this->assertEmpty($result);
    }

    /**
     * Get first existing sender.
     */
    public function testNewInternetDocumentGetSender()
    {
        $existingSender = self::$np->getCounterparties('Sender', 1, '', '');
        $this->assertNotEmpty($existingSender['data'][0]);

        return array_merge($existingSender['data'][0], array(
            'City' => 'Киев',
            'Region' => 'Киевская',
            'Warehouse' => 'Отделение №1: ул. Пироговский путь, 135',
        ));
    }

    /**
     * newInternetDocument().
     *
     * This test must be called much before deleting test to spend
     *  much time to process document on server side of NovaPoshtaAPI
     *
     * @param array $sender Required sender info
     * @depends testNewInternetDocumentGetSender
     */
    public function testNewInternetDocument($sender)
    {
        $result = self::$np->newInternetDocument(
            array(
                'LastName' => $sender['LastName'],
                'FirstName' => $sender['FirstName'],
                'MiddleName' => $sender['MiddleName'],
                'City' => $sender['City'],
                'Region' => $sender['Region'],
                'Warehouse' => $sender['Warehouse'],
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
                'DateTime' => date('d.m.Y', time() + 4 * 84600),
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
        
        self::$createdDocumentsRefs[] = $result['data'][0]['Ref'];

        return $result['data'][0]['Ref'];
    }
    
    /**
     * @depends testNewInternetDocument
     */
    public function testGetDocumentList()
    {
        $result = self::$np->getDocumentList();
        $this->assertTrue($result['success']);
    }

    /**
     * getDocument().
     *
     * @depends testNewInternetDocument
     */
    public function testGetDocument($ref)
    {
        $result = self::$np->getDocument($ref);
        $this->assertTrue($result['success']);
    }

    /**
     * printDocument().
     *
     * @depends testNewInternetDocument
     */
    public function testPrintDocument($ref)
    {
        /*
        There is unexsisted DocumentRef, because if will real id there will not
        any chance delete this tested document
        */
        $result = self::$np->printDocument('123');

        // Code of 'Document not found'
        $this->assertEquals('20000300415', $result['errorCodes'][0]);
    }

    /**
     * printDocument().
     *
     * @depends testNewInternetDocument
     */
    public function testPrintDocumentGetLink($ref)
    {
        /*
         There is unexsisted DocumentRef, because if will real id there will not
         any chance delete this tested document
         */
        $result = self::$np->printDocument('123', 'html_link');
        $this->assertTrue($result['success']);
    }

    public function testPrintMarkings()
    {
        /*
        There is unexsisted DocumentRef, because if will real id there will not
        any chance delete this tested document
        */
        $result = self::$np->printMarkings('123');

        // Code of 'Document does not exist'
        $this->assertEquals('20000202552', $result['errorCodes'][0]);
    }

    public function testPrintMarkingsGetLink()
    {
        /*
         There is unexsisted DocumentRef, because if will real id there will not
         any chance delete this tested document
         */
        $result = self::$np->printMarkings('123', 'html_link');
        $this->assertTrue($result['success']);
    }

    // TODO Address can be changed by NovaPoshta
    public function testGetCityKamyanske()
    {
        $result = self::$np->getCity("Кам'янське", 'Дніпропетровська', 'Відділення №12 (до 30 кг): бульв. Будівельників, 27а (маг.VARUS, ТЦ"Терра")');

        $this->assertEquals('db5c88ed-391c-11dd-90d9-001a92567626', $result['data'][0]['Ref']);
    }

    // TODO Address can be changed by NovaPoshta
    public function testGetCityKamyanskeByFullName()
    {
        $result = self::$np->getCity("Кам'янське(Дніпропетровська обл)");
        
        $this->assertCount(1, $result['data']);
        $this->assertEquals('db5c88ed-391c-11dd-90d9-001a92567626', $result['data'][0]['Ref']);
    }

    // TODO Warehouse can be closed in the settlement
    public function testGetCityKamyanskeNikopolskyDistrict()
    {
        $result = self::$np->getCity("Кам'янське", 'Дніпропетровська');
        
        $this->assertEquals('8715313e-9e6b-11e9-898c-005056b24375', $result['data'][0]['Ref']);
    }

    public function testNewInternetDocumentKamyanske()
    {
        $sender = $this->testNewInternetDocumentGetSender();
        $sender = array_merge($sender, array(
            'City' => "Кам'янське",
            'Region' => 'Дніпропетровська',
            'Warehouse' => 'Відділення №12 (до 30 кг): бульв. Будівельників, 27а (маг.VARUS, ТЦ"Терра")',
        ));
        $ref = $this->testNewInternetDocument($sender);
        $document = self::$np->getDocument($ref);
        self::$createdDocumentsRefs[] = $ref;

        $this->assertEquals('db5c88ed-391c-11dd-90d9-001a92567626', $document['data'][0]['CitySenderRef']);
    }

    
    public function testNewInternetDocumentKamyanskeDnipropetrovskArea()
    {
        $sender = $this->testNewInternetDocumentGetSender();
        $sender = array_merge($sender, array(
            'City' => "Кам'янське(Дніпропетровська обл)",
            'Region' => 'Дніпропетровська',
            'Warehouse' => 'Відділення №12',
        ));
        $ref = $this->testNewInternetDocument($sender);
        $document = self::$np->getDocument($ref);
        self::$createdDocumentsRefs[] = $ref;

        $this->assertEquals('db5c88ed-391c-11dd-90d9-001a92567626', $document['data'][0]['CitySenderRef']);
    }

    public function testNewInternetDocumentKamyanskeNikopolskyDistrict()
    {
        $sender = $this->testNewInternetDocumentGetSender();
        $sender = array_merge($sender, array(
            'City' => "Кам'янське",
            'Region' => 'Дніпропетровська',
            'Warehouse' => '',
        ));
        $ref = $this->testNewInternetDocument($sender);
        $document = self::$np->getDocument($ref);
        self::$createdDocumentsRefs[] = $ref;

        $this->assertEquals('8715313e-9e6b-11e9-898c-005056b24375', $document['data'][0]['CitySenderRef']);
    }
}
