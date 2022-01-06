<?php
declare(strict_types=1);

class CounterpartyServiceTest extends \PHPUnit\Framework\TestCase
{
    private \LisDev\NovaPoshtaClient $client;

    protected function setUp()
    {
        $this->client = new \LisDev\NovaPoshtaClient(['apiKey' => 'YOUR_KEY']);
        parent::setUp();
    }

    //todo: write tests

}