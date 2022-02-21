<?php

namespace Tests;

use LisDev\Delivery\Util\Xml;

class XmlTest extends TestCase
{
    public function testArrayToXml()
    {
        $data = [
            'string' => 'Some string',
            'number' => 123,
            'items' => [
                'first' => 'First item',
                'second' => 123,
            ],
        ];

        $xml = Xml::arrayToXml($data);
        $this->assertEquals("<?xml version=\"1.0\"?>\n<root><string>Some string</string><number>123</number><items><first>First item</first><second>123</second></items></root>\n", $xml);
    }
}
