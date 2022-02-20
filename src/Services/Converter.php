<?php

namespace LisDev\Services;

use LisDev\Constants\Api;
use SimpleXMLElement;

class Converter
{
    /**
     * Converts array to xml
     * @param array $array
     * @param SimpleXMLElement|bool $xml
     * @return bool|string
     */
    public static function arrayToXml(array $array, $xml = false)
    {
        (false === $xml) and $xml = new SimpleXMLElement('<root/>');
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = Api::XML_ITEM;
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