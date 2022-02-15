<?php

use LisDev\Services\Converter;

require __DIR__ . '/vendor/autoload.php';

$np = new \LisDev\Delivery\NovaPoshtaApi2('3ef3805f5661ec3d99e8e958c46d5a0e', 'ru', false);

//$np->setFormat('xml');
$np->setConnectionType('file_get_contents');

//var_dump($np->getCity('Киев', 'Киевская'));
//var_dump($np->getDocumentList());







$config = new \LisDev\Config();
$config
    ->setFormat(\LisDev\Constants\Format::ARR)
    ->setThrowError(false)
    ->setLanguage('aaaa')
;

$np2 = new \LisDev\NovaPoshta('3ef3805f5661ec3d99e8e958c46d5a0e', $config);

//var_dump($result = $np->documentsTracking('LV336687519CN'));

//var_dump($np->getCities());
//var_dump($np->getCity('Киев', 'Киевская'));

//var_dump($np->getTypesOfCounterparties());


//var_dump($np->getDocumentList());

//
//var_dump($np
//    ->model('Common')
//    ->method('getTypesOfCounterparties')
//    ->params(null)
//    ->execute());

//var_dump($np->getDocumentList());

//var_dump($np2->InternetDocument()->getDocumentList());
var_dump($np2->Common()->getMessageCodeText());

