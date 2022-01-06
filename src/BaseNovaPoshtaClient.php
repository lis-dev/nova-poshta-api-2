<?php

declare(strict_types=1);

namespace LisDev;

use LisDev\Exception\InvalidArgumentException;

class BaseNovaPoshtaClient implements NovaPoshtaClientInterface
{
    protected const DEFAULT_API_URL = 'https://api.novaposhta.ua/v2.0';

    protected const DEFAULT_MODEL = Model::Common;

    /**
     * @param array $config todo: make config as object of config class
     */
    public function __construct(private array $config = [])
    {
        $config = array_merge($this->getDefaultConfig(), $config);
        $this->validateConfig($config);
        $this->config = $config;
    }

    public function getApiKey(): ?string
    {
        return $this->config['apiKey'];
    }

    public function getApiUrl(): string
    {
        return $this->config['apiUrl'];
    }

    public function isThrowErrors(): bool
    {
        return $this->config['throwErrors'];
    }

    public function getFormat(): DataFormat
    {
        return $this->config['format'];
    }

    public function getLanguage(): Language
    {
        return $this->config['language'];
    }

    public function getConnectionType(): ConnectionType
    {
        return $this->config['connectionType'];
    }

    public function getTimeout(): int
    {
        return $this->config['timeout'];
    }

    public function getModel(): Model
    {
        return $this->config['model'];
    }

    public function request(string $model, string $method, array $params = null)
    {
        $requestor = new ApiRequestor($this->getApiKey(), $this->getApiUrl(), $this->config);
        $result = $requestor->request($model, $method, $params);
        $preparator = new ApiDefaultDataPreparator();
        $result = $preparator->prepare($result, $this->getFormat(), $this->isThrowErrors());

        return $result;
    }

    protected function getDefaultConfig(): array
    {
        return [
            'apiUrl' => self::DEFAULT_API_URL,
            'apiKey' => '',
            'throwErrors' => false,
            'format' => DataFormat::Array,
            'language' => Language::Ru,
            'connectionType' => ConnectionType::Curl,
            'timeout' => 0,
            'model' => self::DEFAULT_MODEL,
        ];
    }

    private function validateConfig(array $config): void
    {
        if (null !== ($config['apiKey'])) {
            if (!is_string($config['apiKey'])) {
                throw new InvalidArgumentException('apiKey must be null or a string');
            }
            if ('' === $config['apiKey']) {
                throw new InvalidArgumentException('apiKey cannot be the empty string');
            }
            if (preg_match('/\s/', $config['apiKey'])) {
                throw new InvalidArgumentException('apiKey cannot contain whitespace');
            }
        }
        if (!is_string($config['apiUrl'])) {
            throw new InvalidArgumentException('apiUrl must be a string');
        }
    }
}