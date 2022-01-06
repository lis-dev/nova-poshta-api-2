<?php
declare(strict_types=1);


final class NovaPoshtaClientTest extends \PHPUnit\Framework\TestCase
{
    public function testExposesPropertiesForServices()
    {
        $client = new \LisDev\NovaPoshtaClient(['apiKey' => 'YOUR_KEY']);
        $this->assertInstanceOf(\LisDev\Service\AddressService::class, $client->address);
        $this->assertInstanceOf(\LisDev\Service\CommonService::class, $client->common);
        $this->assertInstanceOf(\LisDev\Service\CounterpartyService::class, $client->counterparty);
        $this->assertInstanceOf(\LisDev\Service\InternetDocumentService::class, $client->internetDocument);
        $this->assertInstanceOf(\LisDev\Service\TrackingDocumentService::class, $client->trackingDocument);
    }
}