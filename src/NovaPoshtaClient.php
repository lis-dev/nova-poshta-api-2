<?php

declare(strict_types=1);

namespace LisDev;

use LisDev\Service\AddressService;
use LisDev\Service\CommonService;
use LisDev\Service\CoreServiceFactory;
use LisDev\Service\CounterpartyService;
use LisDev\Service\InternetDocumentService;
use LisDev\Service\TrackingDocumentService;

/**
 * @property AddressService $address
 * @property CommonService $common
 * @property CounterpartyService $counterparty
 * @property InternetDocumentService $internetDocument
 * @property TrackingDocumentService $trackingDocument
 */
class NovaPoshtaClient extends BaseNovaPoshtaClient
{
    private $coreServiceFactory;

    public function __get($name)
    {
        if (null === $this->coreServiceFactory) {
            $this->coreServiceFactory = new CoreServiceFactory($this);
        }

        return $this->coreServiceFactory->__get($name);
    }
}