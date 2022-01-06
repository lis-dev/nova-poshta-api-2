<?php
declare(strict_types=1);

class BaseNovaPoshtaClientTest extends \PHPUnit\Framework\TestCase
{
    public function testCtorDoesNotThrowWhenNoParams()
    {
        $client = new \LisDev\BaseNovaPoshtaClient();
        $this->assertNotNull($client);
        $this->assertNull($client->getApiKey());
    }

   //todo: test for exceptions
}