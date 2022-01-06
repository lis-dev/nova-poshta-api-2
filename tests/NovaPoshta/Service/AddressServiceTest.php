<?php
declare(strict_types=1);

class AddressServiceTest extends \PHPUnit\Framework\TestCase
{
    private \LisDev\NovaPoshtaClient $client;

    protected function setUp()
    {
        $this->client = new \LisDev\NovaPoshtaClient(['apiKey' => 'YOUR_KEY']);
        parent::setUp();
    }

    public function testGetWarehouses()
    {
        $result = $this->client->address->getWarehouses('a9280688-94c0-11e3-b441-0050568002cf');
        $this->assertTrue($result['success']);
    }

    //todo: transfer all test
}