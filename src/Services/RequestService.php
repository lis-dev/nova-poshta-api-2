<?php

namespace LisDev\Services;

use LisDev\Models\Model;

class RequestService
{
    const API_URI = 'https://api.novaposhta.ua/v2.0';

    /**
     * Key for API NovaPoshta.
     *
     * @var string
     *
     * @see https://my.novaposhta.ua/settings/index#apikeys
     */
    protected string $key;

    /**
     * @var string Format of returned data - array, json, xml
     */
    protected string $format = 'array';

    /**
     * @var string Language of response
     */
    protected string $language = 'ru';
    protected OutputService $outputService;

    /**
     * Make request to NovaPoshta API.
     *
     * @param Model $model Model name
     * @param string $method Method name
     * @param array|null $params Required params
     * @return mixed
     */

    public function __construct()
    {
        $this->outputService = new OutputService();
    }

    private function request(Model $model, string $method, array $params = null)
    {
        // Get required URL
        $url = 'xml' == $this->format
            ? self::API_URI.'/xml/'
            : self::API_URI.'/json/';

        $data = array(
            'apiKey' => $this->key,
            'modelName' => $model,
            'calledMethod' => $method,
            'language' => $this->language,
            'methodProperties' => $params,
        );
        $result = array();
        // Convert data to neccessary format
        $post = 'xml' == $this->format
            ? $this->outputService->array2xml($data)
            : json_encode($data);

        if ('curl' == $this->getConnectionType()) {
            $ch = curl_init($url);
            if (is_resource($ch)) {
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: '.('xml' == $this->format ? 'text/xml' : 'application/json')));
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
            $httpOptions = array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded;\r\n",
                'content' => $post,
            );

            if ($this->timeout > 0) {
                $httpOptions['timeout'] = $this->timeout;
            }

            $result = file_get_contents($url, false, stream_context_create(array(
                'http' => $httpOptions,
            )));
        }

        return $this->prepare($result);
    }
}