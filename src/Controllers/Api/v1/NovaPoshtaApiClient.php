<?php

namespace LisDev\Services;

use LisDev\Common\Format;
use LisDev\Common\Language;
use LisDev\Interfaces\NovaPoshtaApiClientInterface;
use LisDev\Models\Model;

class NovaPoshtaApiClient implements NovaPoshtaApiClientInterface
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

    /** @var int Connection timeout (in seconds) */
    protected int $timeout = 0;

    protected OutputService $outputService;
    private ConnectionService $connectionService;
    private PreparationDataService $preparationDataService;
    private Language $languageService;
    private Format $formatService;

    public function __construct()
    {
        $this->outputService = new OutputService();
        $this->connectionService = new ConnectionService();
        $this->preparationDataService = new PreparationDataService();
        $this->languageService = new Language();
        $this->formatService = new Format();
    }

    /**
     * @throws \Exception
     */
    public function request(string $model, string $method, array $params = null)
    {
        // Get required URL
        $url = 'xml' == $this->formatService->getFormat()
            ? self::API_URI.'/xml/'
            : self::API_URI.'/json/';

        $data = array(
            'apiKey' => $this->key,
            'modelName' => $model,
            'calledMethod' => $method,
            'language' => $this->languageService->getLanguage(),
            'methodProperties' => $params,
        );
        $result = array();
        // Convert data to neccessary format
        $post = 'xml' == $this->formatService->getFormat()
            ? $this->outputService->array2xml($data)
            : json_encode($data);

        if ('curl' == $this->connectionService->getConnectionType()) {
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

        return $this->preparationDataService->prepare($result);
    }

    /**
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout(int $timeout): NovaPoshtaApiClient
    {
        $this->timeout = (int)$timeout;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }
}