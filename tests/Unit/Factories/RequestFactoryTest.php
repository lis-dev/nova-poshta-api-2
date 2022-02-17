<?php

namespace LisDev\Tests\Unit\Factories;

use LisDev\Constants\Connection;
use LisDev\Constants\Format;
use LisDev\Constants\Language;
use LisDev\Exceptions\ApplicationException;
use LisDev\Factories\RequestFactory;

class RequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instance of tested class.
     *
     * @var RequestFactory
     */
    private $factory;

    /**
     * Set up before each test.
     */
    public function setUp()
    {
        // Create new instance
        $this->factory = new RequestFactory();
    }

    /**
     * Create cURL request
     * @throws ApplicationException
     */
    public function testCreateCurlRequest()
    {
        $result = $this->factory->create(
            'token',
            Connection::CURL,
            Language::RU,
            0,
            Format::ARR
        );
        $this->assertInstanceOf('LisDev\Request\CurlRequest', $result);
    }

    /**
     * Create file_get_contents request
     * @throws ApplicationException
     */
    public function testCreateFileRequest()
    {
        $result = $this->factory->create(
            'token',
            Connection::FILE,
            Language::RU,
            0,
            Format::ARR
        );
        $this->assertInstanceOf('LisDev\Request\FileRequest', $result);
    }

    /**
     * Create unknown type of request
     * @throws ApplicationException
     */
    public function testUnknownRequest()
    {
        $type = 'blabla';
        $this->setExpectedException('LisDev\Exceptions\ApplicationException', "Unknown connection type '$type'");
        $result = $this->factory->create(
            'token',
            $type,
            Language::RU,
            0,
            Format::ARR
        );
    }

    /**
     * If this test fails, you need to add more tests for new request types (or remove deprecated)
     */
    public function testConnectionTypesCount()
    {
        $this->assertCount(2, Connection::getList());
    }
}