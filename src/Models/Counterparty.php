<?php

namespace LisDev\Models;

use LisDev\Constants\Api;

class Counterparty extends AbstractWritableModel
{
    /** @var string API model name */
    const API_NAME = 'Counterparty';

    // API methods
    const CLONE_LOYALTY_SENDER = 'cloneLoyaltyCounterpartySender';
    const GET_COUNTERPARTIES = 'getCounterparties';
    const GET_COUNTERPARTY_ADDRESSES = 'getCounterpartyAddresses';
    const GET_COUNTERPARTY_CONTACT = 'getCounterpartyContactPersons';
    const GET_COUNTERPARTY_BY_EDRPOU = 'getCounterpartyByEDRPOU';
    const GET_COUNTERPARTY_OPTIONS = 'getCounterpartyOptions';

    /**
     * cloneLoyaltyCounterpartySender() function of model Counterparty
     * The counterparty will be not created immediately, you can wait a long time.
     *
     * @param string $cityRef City ID
     *
     * @return array|string
     */
    public function cloneLoyaltyCounterpartySender($cityRef)
    {
        return $this->request(self::API_NAME, self::CLONE_LOYALTY_SENDER, array(Api::CITY_REF => $cityRef));
    }

    /**
     * getCounterparties() function of model Counterparty.
     *
     * @param string $counterpartyProperty Type of Counterparty (Sender|Recipient)
     * @param int $page Page number
     * @param string $findByString String to search
     * @param string $cityRef City ID
     *
     * @return array|string
     */
    public function getCounterparties($counterpartyProperty = Api::RECIPIENT, $page = null, $findByString = null, $cityRef = null)
    {
        // Any param can be skipped
        $params = array();
        $params[Api::COUNTERPARTY_PROPERTY] = $counterpartyProperty ?: Api::RECIPIENT;
        $page and $params[Api::PAGE] = $page;
        $findByString and $params[Api::FIND_BY_STRING] = $findByString;
        $cityRef and $params[Api::CITY] = $cityRef;
        return $this->request(self::API_NAME, self::GET_COUNTERPARTIES, $params);
    }

    /**
     * getCounterpartyAddresses() function of model Counterparty.
     *
     * @param string $ref Counterparty ref
     * @param int $page
     *
     * @return array|string
     */
    public function getCounterpartyAddresses($ref, $page = 0)
    {
        $params = array(Api::REF => $ref, Api::PAGE => $page);
        return $this->request(self::API_NAME, self::GET_COUNTERPARTY_ADDRESSES, $params);
    }

    /**
     * getCounterpartyContactPersons() function of model Counterparty.
     *
     * @param string $ref Counterparty ref
     *
     * @return array|string
     */
    public function getCounterpartyContactPersons($ref)
    {
        return $this->request(self::API_NAME, self::GET_COUNTERPARTY_CONTACT, array(Api::REF => $ref));
    }

    /**
     * getCounterpartyByEDRPOU() function of model Counterparty.
     *
     * @param string $edrpou EDRPOU code
     * @param string $cityRef City ID
     *
     * @return array|string
     */
    public function getCounterpartyByEDRPOU($edrpou, $cityRef)
    {
        $params = array(Api::EDRPOU => $edrpou, Api::CITY_REF => $cityRef);
        return $this->request(self::API_NAME, self::GET_COUNTERPARTY_BY_EDRPOU, $params);
    }

    /**
     * getCounterpartyOptions() function of model Counterparty.
     *
     * @param string $ref Counterparty ref
     *
     * @return array|string
     */
    public function getCounterpartyOptions($ref)
    {
        return $this->request(self::API_NAME, self::GET_COUNTERPARTY_OPTIONS, array(Api::REF => $ref));
    }
}