<?php

namespace Tests;

use LisDev\Delivery\ApiClient;
use LisDev\Delivery\InternetDocuments;

class InternetDocumentTest extends TestCase
{
    private InternetDocuments $internetDocuments;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->internetDocuments = new InternetDocuments(
            new ApiClient($this->apiKey, timeout: 5)
        );
    }
}
