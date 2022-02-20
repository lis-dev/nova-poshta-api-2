<?php

namespace LisDev\Tests\Unit\Factories;

use LisDev\Constants\Format;
use LisDev\Exceptions\ApplicationException;
use LisDev\Factories\ResponseHandlerFactory;

class ResponseHandlerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create array handler
     * @throws ApplicationException
     */
    public function testCreateArrayHandler()
    {
        $result = ResponseHandlerFactory::create(
            Format::ARR,
            true
        );
        $this->assertInstanceOf('LisDev\Request\ResponseHandler\ArrayHandler', $result);
    }

    /**
     * Create json handler
     * @throws ApplicationException
     */
    public function testCreateJsonHandler()
    {
        $result = ResponseHandlerFactory::create(
            Format::JSON,
            true
        );
        $this->assertInstanceOf('LisDev\Request\ResponseHandler\JsonHandler', $result);
    }

    /**
     * Create xml handler
     * @throws ApplicationException
     */
    public function testCreateXmlHandler()
    {
        $result = ResponseHandlerFactory::create(
            Format::XML,
            true
        );
        $this->assertInstanceOf('LisDev\Request\ResponseHandler\XmlHandler', $result);
    }

    /**
     * Create unknown type of request
     * @throws ApplicationException
     */
    public function testUnknownRequest()
    {
        $format = 'blabla';
        $this->setExpectedException('LisDev\Exceptions\ApplicationException', "Unknown format '$format'");
        ResponseHandlerFactory::create(
            $format,
            true
        );
    }

    /**
     * If this test fails, you need to add more tests for new formats types (or remove deprecated)
     */
    public function testConnectionTypesCount()
    {
        $this->assertCount(3, Format::getList());
    }
}