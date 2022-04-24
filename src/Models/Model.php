<?php

namespace LisDev\Models;

use LisDev\Interfaces\ModelInterface;

class Model implements ModelInterface
{
    protected Model $model;

    /**
     * Save method of current model
     * Required params:
     * For ContactPerson model (only for Organization API key, for PrivatePerson error will be returned):
     *	 CounterpartyRef, FirstName (ukr), MiddleName, LastName, Phone (format 0xxxxxxxxx)
     * For Counterparty model:
     *	 CounterpartyProperty (Recipient|Sender), CityRef, CounterpartyType (Organization, PrivatePerson),
     *	 FirstName (or name of organization), MiddleName, LastName, Phone (0xxxxxxxxx), OwnershipForm (if Organization).
     *
     * @param array $params
     *
     * @return mixed
     */
    public function save(array $params): Model
    {
        return $this->request($this->model, 'save', $params);
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
     * @return mixed
     */
    public function update(array $params): Model
    {
        return $this->request($this->model, 'update', $params);
    }

    /**
     * Delete method of current model.
     *
     * @param array $params
     *
     * @return mixed
     */
    public function delete(array $params): string
    {
        return $this->request($this->model, 'delete', $params);
    }
}