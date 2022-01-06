<?php

declare(strict_types=1);

namespace LisDev\Service;

use LisDev\Model;

/**
 * @method getTypesOfCounterparties
 * @method getBackwardDeliveryCargoTypes
 * @method getCargoDescriptionList
 * @method getCargoTypes
 * @method getDocumentStatuses
 * @method getOwnershipFormsList
 * @method getPalletsList
 * @method getPaymentForms
 * @method getTimeIntervals
 * @method getServiceTypes
 * @method getTiresWheelsList
 * @method getTraysList
 * @method getTypesOfAlternativePayers
 * @method getTypesOfPayers
 * @method getTypesOfPayersForRedelivery
 */
class CommonService extends AbstractService
{
    const COMMON_MODEL_METHOD = [
        'getTypesOfCounterparties',
        'getBackwardDeliveryCargoTypes',
        'getCargoDescriptionList',
        'getCargoTypes',
        'getDocumentStatuses',
        'getOwnershipFormsList',
        'getPalletsList',
        'getPaymentForms',
        'getTimeIntervals',
        'getServiceTypes',
        'getTiresWheelsList',
        'getTraysList',
        'getTypesOfAlternativePayers',
        'getTypesOfPayers',
        'getTypesOfPayersForRedelivery',
    ];

    /**
     * Magic method of calling functions (uses for calling Common Model of NovaPoshta API).
     *
     * @param string $method Called method of Common Model
     * @param array $arguments Array of params
     */
    public function __call($method, $arguments)
    {
        // Call method of Common model
        if (in_array($method, static::COMMON_MODEL_METHOD)) {
            return $this->request(Model::Common, $method, null);
        }
    }
}