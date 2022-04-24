<?php

namespace LisDev\Services;

class TrackingDocumentService
{
    private NovaPoshtaApiClient $novaPoshtaApiClient;

    public function __construct()
    {
        $this->novaPoshtaApiClient = new NovaPoshtaApiClient();
    }

    /**`
     * Get tracking information by track number.
     *
     * @param string $track Track number
     *
     * @return mixed
     */
    public function documentsTracking($track)
    {
        $params = array('Documents' => array(array('DocumentNumber' => $track)));

        return $this->novaPoshtaApiClient->request('TrackingDocument', 'getStatusDocuments', $params);
    }
}