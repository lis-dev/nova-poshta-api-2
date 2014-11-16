<?php
// Header information
header('Content-Type: text/html; charset=utf-8');
// Require class file
require_once './src/NovaPoshtaApi2.php';
// Set key
$key = '';
// Create instance
$np = new NovaPoshtaApi2($key);
// Get Track Info
// $result = $np->documentsTracking('59000082032106');
// Get cities by name
// $result = $np->getCities(0, 'Андреевка');
// Get region by name
// $result = $np->getArea('Чернігівська', '');
// Get city by name and region
// $result = $np->getCity('Андреевка', 'Харьков');
// Get method from Common Model of NovaPoshta
// $result = $np->getDocumentStatuses();
/*
$result = $np->counterparty()->save(array(
	'CounterpartyProperty' => 'Recipient',
	'CityRef' => 'f4890a83-8344-11df-884b-000c290fbeaa',
	'CounterpartyType' => 'PrivatePerson',
	'FirstName' => 'Иван',
	'MiddleName' => 'Иванович',
	'LastName' => 'Иванов',
	'Phone' => '380501112233',
));
*/
/*
$result = $np->counterparty()->update(array(
	'Ref' => '3f9c9486-6cd6-11e4-acce-0050568002cf',
	'CounterpartyProperty' => 'Recipient',
	'CityRef' => 'f4890a83-8344-11df-884b-000c290fbeaa',
	'CounterpartyType' => 'PrivatePerson',
	'FirstName' => 'Иван1',
	'MiddleName' => 'Иванович1',
	'LastName' => 'Иванов1',
	'Phone' => '380501112234',
));
*/
// $result = $np->counterparty()->delete(array('Ref' => '3f9c94b0-6cd6-11e4-acce-0050568002cf'));
/*
$result = $np->contactPerson()->save(array(
	'CounterpartyRef' => '3f9c94c1-6cd6-11e4-acce-0050568002cf',
	'FirstName' => 'Иван2-1',
	'MiddleName' => 'Иванович2-1',
	'LastName' => 'Иванов2-1',
	'Phone' => '0501112255',
));
*/
/*
$result = $np->contactPerson()->update(array(
	'Ref' => '29a5c4e8-6d43-11e4-acce-0050568002cf',
	'CounterpartyRef' => '3f9c94c1-6cd6-11e4-acce-0050568002cf',
	'FirstName' => 'Иван3',
	'MiddleName' => 'Иванович3',
	'LastName' => 'Иванов3',
	'Phone' => '0501112266',
	'Email' => 'some@mail.ru'
));
*/
// $result = $np->contactPerson()->delete(array('Ref' => '29a5c4e8-6d43-11e4-acce-0050568002cf'));
// Get result
var_export($result);
