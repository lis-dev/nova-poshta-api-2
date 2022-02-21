<?php

namespace Tests;

use LisDev\Delivery\Addresses;
use LisDev\Delivery\ApiClient;

class AddressesTest extends TestCase
{
    private Addresses $addresses;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addresses = new Addresses(
            new ApiClient($this->apiKey, timeout: 5)
        );
    }

    public function testSearchSettlements()
    {
        $response = $this->addresses->searchSettlements('Киев');

        $this->assertTrue($response['success']);
    }

    public function testSearchSettlementStreets()
    {
        $response = $this->addresses->searchSettlementStreets(
            streetName:  'Незалежност',
            settlementRef: 'e715719e-4b33-11e4-ab6d-005056801329'
        );

        $this->assertTrue($response['success']);
    }

    public function testGetCities()
    {
        $response = $this->addresses->getCities();

        $this->assertTrue($response['success']);
    }
}
