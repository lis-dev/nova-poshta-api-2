<?php
class Array2Xml
{
    /**
     * Converts array to xml.
     *
     * @param array $array
     * @param \SimpleXMLElement|bool $xml
     */
    public function array2xml(array $array, $xml = false)
    {
        if ($xml) {
            $xml = new \SimpleXMLElement('<root/>');
        }
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