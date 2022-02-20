<?php

namespace LisDev\Tests\Unit\Request;

use LisDev\Constants\Format;
use LisDev\Constants\Language;
use LisDev\Request\AbstractRequest;
use LisDev\Tests\AbstractTest;
use ReflectionException;

class AbstractRequestTest extends AbstractTest
{
    /**
     * Instance of tested class.
     *
     * @var AbstractRequest
     */
    private $class;

    private $token = 'blabla';

    /**
     * Set up before each test.
     */
    public function setUp()
    {
        $params = array(
            'token' => $this->token,
            'language' => Language::RU,
            'timeout' => 0,
            'format' => Format::ARR,
        );
        $this->class = $this->getMockForAbstractClass('LisDev\Request\AbstractRequest', $params);
    }

    /**
     * Test token getter
     */
    public function testGetToken()
    {
        $this->assertEquals($this->token, $this->class->getToken());
    }

    /**
     * Test url maker (xml)
     * @throws ReflectionException
     */
    public function testUrlXml()
    {
        $makeUrl = $this->getPrivateMethod('LisDev\Request\AbstractRequest', 'makeUrl');
        $url = $makeUrl->invokeArgs($this->class, array(Format::XML));
        $this->assertEquals(AbstractRequest::API_URI . AbstractRequest::API_XML, $url);
    }

    /**
     * Test url maker (any not xml)
     * @throws ReflectionException
     */
    public function testUrlNotXml()
    {
        $makeUrl = $this->getPrivateMethod('LisDev\Request\AbstractRequest', 'makeUrl');
        $url = $makeUrl->invokeArgs($this->class, array('blabla'));
        $this->assertEquals(AbstractRequest::API_URI . AbstractRequest::API_JSON, $url);
    }
}
