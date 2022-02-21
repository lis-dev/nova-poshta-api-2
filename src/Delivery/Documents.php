<?php

namespace LisDev\Delivery;

class Documents
{
    public function __construct(
        private ApiClient $client
    ) {
    }

    public function documentsTracking(string $track): array|string
    {
        $params = ['Documents' => [['DocumentNumber' => $track]]];

        return $this->client->request('TrackingDocument', 'getStatusDocuments', $params);
    }
}
