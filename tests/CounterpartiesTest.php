<?php

namespace Tests;

use LisDev\Delivery\ApiClient;
use LisDev\Delivery\Counterparties;

class CounterpartiesTest extends TestCase
{
    private Counterparties $counterparties;

    protected function setUp(): void
    {
        parent::setUp();

        $this->counterparties = new Counterparties(
            new ApiClient($this->apiKey, timeout: 5)
        );
    }
}
