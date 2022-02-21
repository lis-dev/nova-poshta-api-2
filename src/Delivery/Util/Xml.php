<?php

namespace LisDev\Delivery\Util;

use SimpleXMLElement;

class Xml
{
    public static function arrayToXml(array $array, ?SimpleXMLElement $xml = null): string
    {
        if ($xml === null) {
            $xml = new SimpleXMLElement('<root/>');
        }

        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            if (is_array($value)) {
                self::arrayToXml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }
}
