<?php

declare(strict_types=1);

namespace LisDev\Service;

use LisDev\Model;

class CounterpartyService extends AbstractService
{
    /**
     * getCounterparties() function of model Counterparty.
     * @param string $counterpartyProperty
     * @param int|null $page
     * @param string|null $findByString
     * @param string|null $cityRef
     * @return mixed
     */
    public function getCounterparties(
        string $counterpartyProperty = 'Recipient',
        int $page = null,
        string $findByString = null,
        string $cityRef = null
    ) {
        $params['CounterpartyProperty'] = $counterpartyProperty;
        if ($page !== null) {
            $params['Page'] = $page;
        }
        if ($findByString !== null) {
            $params['FindByString'] = $findByString;
        }
        if ($cityRef !== null) {
            $params['City'] = $cityRef;
        }

        return $this->request(Model::Counterparty, 'getCounterparties', $params);
    }

    /**
     * cloneLoyaltyCounterpartySender() function of model Counterparty
     * The counterparty will be not created immediately, you can wait a long time
     * @param string $cityRef
     * @return mixed
     */
    public function cloneLoyaltyCounterpartySender(string $cityRef)
    {
        return $this->request(Model::Counterparty, 'cloneLoyaltyCounterpartySender', ['CityRef' => $cityRef]);
    }

    /**
     * getCounterpartyContactPersons() function of model Counterparty.
     * @param string $ref
     * @return mixed
     */
    public function getCounterpartyContactPersons(string $ref)
    {
        return $this->request(Model::Counterparty, 'getCounterpartyContactPersons', ['ref' => $ref]);
    }

    /**
     * getCounterpartyAddresses() function of model Counterparty.
     * @param string $ref
     * @param int $page
     * @return mixed
     */
    public function getCounterpartyAddresses(string $ref, int $page = 0)
    {
        return $this->request(Model::Counterparty, 'getCounterpartyContactPersons', ['Ref' => $ref, 'Page' => $page]);
    }

    /**
     * getCounterpartyOptions() function of model Counterparty.
     * @param $ref
     * @return mixed
     */
    public function getCounterpartyOptions($ref)
    {
        return $this->request(Model::Counterparty, 'getCounterpartyOptions', ['Ref' => $ref]);
    }

    /**
     * getCounterpartyByEDRPOU() function of model Counterparty.
     * @param string $edrpou
     * @param string $cityRef
     * @return mixed
     */
    public function getCounterpartyByEDRPOU(string $edrpou, string $cityRef)
    {
        return $this->request(
            Model::Counterparty,
            'getCounterpartyByEDRPOU',
            ['EDRPOU' => $edrpou, 'cityRef' => $cityRef]
        );
    }
}