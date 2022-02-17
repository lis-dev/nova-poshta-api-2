<?php

use LisDev\Services\Converter;

require __DIR__ . '/vendor/autoload.php';

$np = new \LisDev\Delivery\NovaPoshtaApi2('3ef3805f5661ec3d99e8e958c46d5a0e', 'ru', false);

//$np->setFormat('xml');
$np->setConnectionType('file_get_contents');

//var_dump($np->getCity('Киев', 'Киевская'));
//var_dump($np->getDocumentList());

var_dump($np->generateReport(array('Type' => 'xls', 'DocumentRefs' => array('1fb8943e-14e4-11e5-ad08-005056801333'), 'DateTime' => date('d.m.Y'))));






$config = new \LisDev\Config();
$config
    ->setFormat(\LisDev\Constants\Format::ARR)
    ->setThrowError(false)
;

$np2 = new \LisDev\NovaPoshta('3ef3805f5661ec3d99e8e958c46d5a0e', $config);


//var_dump($np2->Common()->getMessageCodeText());

