<?php

namespace Tests;

use LisDev\Delivery\ApiClient;
use LisDev\Delivery\Exception\QueryBuilderException;
use LisDev\Delivery\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    private QueryBuilder $query;

    protected function setUp(): void
    {
        parent::setUp();

        $this->query = new QueryBuilder(
            new ApiClient($this->apiKey, timeout: 5)
        );
    }

    public function testExecute()
    {
        $result = $this->query
            ->forModel('Address')
            ->method('getCities')
            ->withParams(['Page' => 1])
            ->execute();

        $this->assertTrue($result['success']);
    }

    public function testExecuteWhenModelNotSet()
    {
        $this->expectException(QueryBuilderException::class);

        $result = $this->query->execute();

        $this->assertTrue($result['success']);
    }

    public function testExecuteWhenMethodNotSet()
    {
        $this->expectException(QueryBuilderException::class);

        $result = $this->query->forModel('Address')->execute();

        $this->assertTrue($result['success']);
    }
}
