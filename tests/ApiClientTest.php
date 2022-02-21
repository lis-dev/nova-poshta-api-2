<?php

namespace Tests;

use LisDev\Delivery\ApiClient;
use LisDev\Delivery\Exception\BadRequestException;
use LisDev\Delivery\NovaPoshta;

class ApiClientTest extends TestCase
{
    public function testRequest()
    {
        $client = new ApiClient($this->apiKey);

        $response = $client->request('Address', 'getAreas');

        $this->assertIsArray($response);
        $this->assertTrue($response['success']);
    }

    public function testBadRequest()
    {
        $client = new ApiClient($this->apiKey);

        $response = $client->request('BabModel', 'badMethod');

        $this->assertFalse($response['success']);
    }

    public function testRequestWithThrowErrors()
    {
        $this->expectException(BadRequestException::class);

        $client = new ApiClient($this->apiKey, throwErrors: true);

        $client->request('BabModel', 'badMethod');
    }

    public function testRequestWithoutThrowErrors()
    {
        $client = new ApiClient($this->apiKey, throwErrors: false);

        $response = $client->request('BabModel', 'badMethod');

        $this->assertFalse($response['success']);
    }

    public function testJsonResponse()
    {
        $client = new ApiClient(apiKey: $this->apiKey, format: NovaPoshta::FORMAT_JSON);

        $response = $client->request('Address', 'getAreas');

        $this->assertIsString($response);

        $response = json_decode($response, true);

        $this->assertTrue($response['success']);
    }

    public function testXmlResponse()
    {
        $client = new ApiClient(apiKey: $this->apiKey, format: NovaPoshta::FORMAT_XML);

        $response = $client->request('Address', 'getAreas');

        $this->assertIsString($response);

        $response = simplexml_load_string($response);
        $response = json_encode($response);
        $response = json_decode($response, true);

        $this->assertEquals('true', $response['success']);
    }
}
