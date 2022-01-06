<?php

declare(strict_types=1);

namespace LisDev\Service;

use LisDev\Model;

class TrackingDocumentService extends AbstractService
{
    /**
     * Get tracking information by track number.
     * @param string $track
     * @return mixed
     */
    public function documentsTracking(string $track)
    {
        return $this->request(
            Model::TrackingDocument,
            'getStatusDocuments',
            ['Documents' => [['DocumentNumber' => $track]]]
        );
    }
}