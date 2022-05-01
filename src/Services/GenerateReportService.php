<?php

namespace LisDev\Services;

class GenerateReportService
{
    private NovaPoshtaApiClient $novaPoshtaApiClient;

    public function __construct()
    {
        $this->novaPoshtaApiClient = new NovaPoshtaApiClient();
    }

    /**
     * Generetes report by Document refs.
     *
     * @param array $params Params like getDocumentList with requiered keys
     *                      'Type' => [xls, csv], 'DocumentRefs' => []
     *
     * @return mixed
     * @throws \Exception
     */
    public function generateReport($params)
    {
        return $this->novaPoshtaApiClient->request('InternetDocument', 'generateReport', $params);
    }
}