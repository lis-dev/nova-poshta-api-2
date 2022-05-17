<?php

namespace LisDev\Delivery\Helpers;

class Array2Xml
{
    public static function convert(array $array, $xml = false)
    {
        (false === $xml) and $xml = new \SimpleXMLElement('<root/>');
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            if (is_array($value)) {
                self::convert($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }
}
