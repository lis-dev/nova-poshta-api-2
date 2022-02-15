<?php

namespace LisDev\Models;

abstract class AbstractWritableModel extends AbstractModel
{
    // API methods
    const SAVE = 'save';
    const UPDATE = 'update';
    const DELETE = 'delete';

    /**
     * Save method of current model
     * Required params:
     * For ContactPerson model (only for Organization API key, for PrivatePerson error will be returned):
     *     CounterpartyRef, FirstName (ukr), MiddleName, LastName, Phone (format 0xxxxxxxxx)
     * For Counterparty model:
     *     CounterpartyProperty (Recipient|Sender), CityRef, CounterpartyType (Organization, PrivatePerson),
     *     FirstName (or name of organization), MiddleName, LastName, Phone (0xxxxxxxxx), OwnershipForm (if Organization).
     *
     * @param array $params
     *
     * @return array|string
     */
    public function save($params)
    {
        return $this->request(static::API_NAME, self::SAVE, $params);
    }

    /**
     * Update method of current model
     * Required params:
     * For ContactPerson model: Ref, CounterpartyRef, FirstName (ukr), MiddleName, LastName, Phone (format 0xxxxxxxxx)
     * For Counterparty model: Ref, CounterpartyProperty (Recipient|Sender), CityRef, CounterpartyType (Organization, PrivatePerson),
     * FirstName (or name of organization), MiddleName, LastName, Phone (0xxxxxxxxx), OwnershipForm (if Organization).
     *
     * @param array $params
     *
     * @return array|string
     */
    public function update($params)
    {
        return $this->request(static::API_NAME, self::UPDATE, $params);
    }

    /**
     * Delete method of current model.
     *
     * @param array $params
     *
     * @return array|string
     */
    public function delete($params)
    {
        return $this->request(static::API_NAME, self::DELETE, $params);
    }
}