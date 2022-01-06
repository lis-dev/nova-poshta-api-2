<?php

declare(strict_types=1);

namespace LisDev;

class ApiRequestor
{
    public function __construct(private string $apiKey, private string $apiUrl, private array $config)
    {
    }

    public function request(string $model, string $method, array $params = null): bool|array|string
    {
        $url = $this->prepareUrl();
        $data = $this->prepareData($model, $method, $params);
        $result = [];
        if ($this->config['format'] === DataFormat::Xml) {
            $post = $this->arrayToXml($data);
        } else {
            $post = json_encode($data);
        }

        if (ConnectionType::Curl === $this->config['connectionType']) {
            $ch = curl_init($url);
            if (is_resource($ch)) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt(
                    $ch,
                    CURLOPT_HTTPHEADER,
                    ['Content-Type: '.(DataFormat::Xml === $this->config['format'] ? 'text/xml' : 'application/json')]
                );
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

                if ($this->timeout > 0) {
                    curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
                }

                $result = curl_exec($ch);
                curl_close($ch);
            }
        } else {
            $httpOptions = [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded;\r\n",
                'content' => $post,
            ];

            if ($this->config['timeout'] > 0) {
                $httpOptions['timeout'] = $this->config['timeout'];
            }

            $result = file_get_contents(
                $url,
                false,
                stream_context_create(array(
                    'http' => $httpOptions,
                ))
            );
        }

        return $result;
    }

    private function prepareUrl(): string
    {
        return ($this->config['format'] === DataFormat::Xml) ? $this->apiUrl.'/xml/' : $this->apiUrl.'/json/';
    }

    private function prepareData(string $model, string $method, ?array $params): array
    {
        return [
            'apiKey' => $this->apiKey,
            'modelName' => $model,
            'calledMethod' => $method,
            'language' => $this->config['language']->value,
            'methodProperties' => $params,
        ];
    }

    /**
     * @param array $array
     * @param \SimpleXMLElement|null $xml
     * @return bool|string
     */
    private function arrayToXml(array $array, \SimpleXMLElement $xml = null): bool|string
    {
        (null === $xml) and $xml = new \SimpleXMLElement('<root/>');
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            if (is_array($value)) {
                $this->arrayToXml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml->asXML();
    }
}