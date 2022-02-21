<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected string $apiKey;

    protected function setUp(): void
    {
        $this->apiKey = getenv('NOVA_POSHTA_API_KEY');
    }
}
