<?php

namespace Tests;

use LisDev\Delivery\ApiClient;
use LisDev\Delivery\Documents;

class DocumentsTest extends TestCase
{
    private Documents $documents;

    protected function setUp(): void
    {
        parent::setUp();

        $this->documents = new Documents(
            new ApiClient($this->apiKey, timeout: 5)
        );
    }

    public function testDocumentsTracking()
    {
        $response = $this->documents->documentsTracking('20600009559994');

        $this->assertIsArray($response);
        $this->assertTrue($response['success']);
    }
}
