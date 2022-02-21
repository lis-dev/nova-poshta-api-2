<?php

namespace LisDev\Delivery;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use LisDev\Delivery\Exception\ApiRequestException;
use LisDev\Delivery\Exception\BadRequestException;
use LisDev\Delivery\Util\Xml;
use Psr\Http\Message\ResponseInterface;

class ApiClient
{
    private const BASE_URI = 'https://api.novaposhta.ua/v2.0/';

    private HttpClient $httpClient;

    public function __construct(
        private string $apiKey,
        private bool $throwErrors = false,
        private string $format = 'array',
        int $timeout = 1,
    ) {
        $this->httpClient = new HttpClient([
            'base_uri' => self::BASE_URI,
            'timeout' => $timeout,
        ]);
    }

    public function request(string $model, string $method, array $params = null): array|string
    {
        try {
            $request = $this->createRequest($model, $method, $params);
            $response = $this->httpClient->send($request);
        } catch (GuzzleException $e) {
            throw new ApiRequestException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }

        return $this->prepareResponse($response);
    }

    private function createRequest(string $model, string $method, array $params = null): Request
    {
        $data = [
            'apiKey' => $this->apiKey,
            'modelName' => $model,
            'calledMethod' => $method,
            'methodProperties' => $params,
        ];

        return $this->format === NovaPoshta::FORMAT_XML
            ? $this->createXmlRequest($data)
            : $this->createJsonRequest($data);
    }

    private function createXmlRequest(array $data): Request
    {
        $headers = ['Content-Type' => 'application/xml'];
        $body = Utils::streamFor(Xml::arrayToXml($data));

        return new Request('POST', 'xml/', $headers, $body);
    }

    private function createJsonRequest(array $data): Request
    {
        $headers = ['Content-Type' => 'application/json'];
        $body = Utils::streamFor(json_encode($data));

        return new Request('POST', 'json/', $headers, $body);
    }

    private function prepareResponse(ResponseInterface $response): array|string
    {
        $body = $this->format === NovaPoshta::FORMAT_XML
            ? json_encode(simplexml_load_string($response->getBody()))
            : $response->getBody();

        $body = json_decode($body, true);
        if ($this->throwErrors) {
            $this->assertNoErrors($body);
        }

        return $this->format === NovaPoshta::FORMAT_ARRAY ? $body : (string)$response->getBody();
    }

    private function assertNoErrors(array $response): void
    {
        if (isset($response['success']) && !$response['success']) {
            $errors = array_merge($response['errors'], $response['warnings']);

            throw new BadRequestException('API request finished with errors: ' . json_encode($errors));
        }
    }
}
