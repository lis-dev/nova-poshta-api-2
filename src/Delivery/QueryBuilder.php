<?php

namespace LisDev\Delivery;

use LisDev\Delivery\Exception\QueryBuilderException;

class QueryBuilder
{
    private ?string $model = null;

    private ?string $method = null;

    private ?array $params = null;

    public function __construct(
        private ApiClient $apiClient
    ) {
    }

    public function forModel(string $model): self
    {
        $this->model = $model;
        $this->method = null;
        $this->params = null;

        return $this;
    }

    public function method(string $method): self
    {
        $this->method = $method;
        $this->params = null;

        return $this;
    }

    public function withParams(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @throws QueryBuilderException
     */
    public function execute(): array|string
    {
        if (!$this->model) {
            throw new QueryBuilderException('Model is not set.');
        }

        if (!$this->method) {
            throw new QueryBuilderException('Method is not set');
        }

        return $this->apiClient->request($this->model, $this->method, $this->params);
    }
}
