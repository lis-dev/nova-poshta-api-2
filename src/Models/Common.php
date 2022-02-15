<?php

namespace LisDev\Models;

use LisDev\Exceptions\ParamsException;

/**
 * @method array|string getTypesOfCounterparties()
 * @method array|string getBackwardDeliveryCargoTypes()
 * @method array|string getCargoDescriptionList($params = null)
 * @method array|string getCargoTypes()
 * @method array|string getDocumentStatuses($params = null)
 * @method array|string getOwnershipFormsList()
 * @method array|string getPalletsList()
 * @method array|string getPaymentForms()
 * @method array|string getTimeIntervals($params)
 * @method array|string getServiceTypes()
 * @method array|string getTiresWheelsList()
 * @method array|string getTraysList($params = null)
 * @method array|string getTypesOfPayers()
 * @method array|string getTypesOfPayersForRedelivery()
 * @method array|string getPackList()
 * @method array|string getMessageCodeText()
 */
class Common extends AbstractModel
{
    /** @var string API model name */
    const API_NAME = 'Common';
    const API_NAME_GENERAL = 'CommonGeneral';

    /**
     * Magic method of calling functions (uses for calling Common Model of NovaPoshta API).
     *
     * @param string $method Called method of Common Model
     * @param array $arguments Array of params
     * @throws ParamsException
     */
    public function __call($method, $arguments)
    {
        $commonMethods = array(
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
            'getTypesOfPayers',
            'getTypesOfPayersForRedelivery',
            'getPackList',
        );
        $commonGeneralMethods = array(
            'getMessageCodeText'
        );

        $model = null;
        if (in_array($method, $commonMethods)) {
            $model = self::API_NAME;
        } elseif (in_array($method, $commonGeneralMethods)) {
            $model = self::API_NAME_GENERAL;
        }

        if ($model !== null) {
            $params = (array_key_exists(0, $arguments) && is_array($arguments[0])) ? $arguments[0] : null;
            return $this->request($model, $method, $params);
        }
        throw new ParamsException("Unknown method '$method' for model '$model'");
    }
}