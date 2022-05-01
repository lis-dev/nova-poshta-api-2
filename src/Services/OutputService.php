<?php

namespace LisDev\Services;

class OutputService
{
    /**
     * Converts array to xml.
     *
     * @param array $array
     * @param \SimpleXMLElement|bool $xml
     */
    public function array2xml(array $array, \SimpleXMLElement|bool $xml = false): bool|string
    {
        (false === $xml) and $xml = new \SimpleXMLElement('<root/>');
        foreach ($array as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item';
            }
            if (is_array($value)) {
                $this->array2xml($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, $value);
            }
        }
        return $xml->asXML();
    }


}