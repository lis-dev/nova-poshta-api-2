<?php

namespace LisDev\Services;

class CounterPartyService
{
    private NovaPoshtaApiClient $novaPoshtaApiClient;

    public function __construct()
    {
        $this->novaPoshtaApiClient = new NovaPoshtaApiClient();
    }
    /**
     * getCounterpartyContactPersons() function of model Counterparty.
     *
     * @param string $ref Counterparty ref
     *
     * @return mixed
     */
    public function getCounterpartyContactPersons($ref)
    {
        return $this->novaPoshtaApiClient->request('Counterparty', 'getCounterpartyContactPersons', array('Ref' => $ref));
    }

    /**
     * getCounterpartyAddresses() function of model Counterparty.
     *
     * @param string $ref  Counterparty ref
     * @param int    $page
     *
     * @return mixed
     */
    public function getCounterpartyAddresses($ref, $page = 0)
    {
        return $this->novaPoshtaApiClient->request('Counterparty', 'getCounterpartyAddresses', array('Ref' => $ref, 'Page' => $page));
    }

    /**
     * getCounterpartyOptions() function of model Counterparty.
     *
     * @param string $ref Counterparty ref
     *
     * @return mixed
     */
    public function getCounterpartyOptions($ref)
    {
        return $this->novaPoshtaApiClient->request('Counterparty', 'getCounterpartyOptions', array('Ref' => $ref));
    }

    /**
     * getCounterpartyByEDRPOU() function of model Counterparty.
     *
     * @param string $edrpou  EDRPOU code
     * @param string $cityRef City ID
     *
     * @return mixed
     */
    public function getCounterpartyByEDRPOU($edrpou, $cityRef)
    {
        return $this->novaPoshtaApiClient->request('Counterparty', 'getCounterpartyByEDRPOU', array('EDRPOU' => $edrpou, 'cityRef' => $cityRef));
    }
}