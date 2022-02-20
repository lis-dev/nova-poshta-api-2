<?php

namespace LisDev\Tests\Unit;

use LisDev\Config;
use LisDev\Constants\Connection;
use LisDev\Constants\Format;
use LisDev\Constants\Language;
use LisDev\Exceptions\ConfigException;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Instance of tested class.
     *
     * @var Config
     */
    private $config;

    /**
     * Set up before each test.
     */
    public function setUp()
    {
        // Create new instance
        $this->config = new Config();
    }

    /**
     * Language setter with correct parameter
     * @throws ConfigException
     */
    public function testSetLanguageRight()
    {
        $result = $this->config->setLanguage(Language::UA);
        $this->assertInstanceOf('LisDev\Config', $result);
    }

    /**
     * Language setter with incorrect parameter
     * @throws ConfigException
     */
    public function testSetLanguageWrong()
    {
        $language = 'blabla';
        $this->setExpectedException('LisDev\Exceptions\ConfigException', "Unknown language '$language'. " .
            "Check \LisDev\Constants\Language");
        $this->config->setLanguage($language);
    }

    /**
     * Language getter
     */
    public function testGetLanguage()
    {
        $result = $this->config->getLanguage();
        $this->assertEquals(Language::RU, $result);
    }

    /**
     * Connect type setter with correct parameter
     * @throws ConfigException
     */
    public function testSetConnectTypeRight()
    {
        $result = $this->config->setConnectionType(Connection::FILE);
        $this->assertInstanceOf('LisDev\Config', $result);
    }

    /**
     * Connect type setter with incorrect parameter
     * @throws ConfigException
     */
    public function testSetConnectTypeWrong()
    {
        $connection = 'blabla';
        $this->setExpectedException('LisDev\Exceptions\ConfigException', "Unknown connection type '$connection'" .
            ". Check \LisDev\Constants\Connection");
        $this->config->setConnectionType($connection);
    }

    /**
     * Connect type getter
     */
    public function testGetConnectType()
    {
        $result = $this->config->getConnectionType();
        $this->assertEquals(Connection::CURL, $result);
    }

    /**
     * Timeout setter with correct parameter
     * @throws ConfigException
     */
    public function testSetTimeoutRightInt()
    {
        $result = $this->config->setTimeout(10);
        $this->assertInstanceOf('LisDev\Config', $result);
    }

    /**
     * Timeout setter with correct parameter
     * @throws ConfigException
     */
    public function testSetTimeoutRightString()
    {
        $result = $this->config->setTimeout('10');
        $this->assertInstanceOf('LisDev\Config', $result);
    }

    /**
     * Timeout setter with incorrect parameter
     * @throws ConfigException
     */
    public function testSetTimeoutWrongInt()
    {
        $this->setExpectedException('LisDev\Exceptions\ConfigException', 'Timeout must be less than or equal to 0');
        $this->config->setTimeout(-10);
    }

    /**
     * Timeout setter with incorrect parameter
     * @throws ConfigException
     */
    public function testSetTimeoutWrongString()
    {
        $this->setExpectedException('LisDev\Exceptions\ConfigException', 'Timeout must be less than or equal to 0');
        $this->config->setTimeout('-10');
    }

    /**
     * Timeout getter
     */
    public function testGetTimeout()
    {
        $result = $this->config->getTimeout();
        $this->assertEquals(0, $result);
    }

    /**
     * Format setter with correct parameter
     * @throws ConfigException
     */
    public function testSetFormatRight()
    {
        $result = $this->config->setFormat(Format::XML);
        $this->assertInstanceOf('LisDev\Config', $result);
    }

    /**
     * Format setter with incorrect parameter
     * @throws ConfigException
     */
    public function testSetFormatWrong()
    {
        $format = 'blabla';
        $this->setExpectedException('LisDev\Exceptions\ConfigException', "Unknown format '$format'. " .
            "Check \LisDev\Constants\Format");
        $this->config->setFormat($format);
    }

    /**
     * Format getter
     */
    public function testGetFormat()
    {
        $result = $this->config->getFormat();
        $this->assertEquals(Format::ARR, $result);
    }

    /**
     * Throw Error setter with correct parameter
     */
    public function testSetThrowErrorBool()
    {
        $result = $this->config->setThrowError(true);
        $this->assertInstanceOf('LisDev\Config', $result);
    }

    /**
     * Throw Error setter with not bool param
     */
    public function testSetThrowErrorNotBoolTrue()
    {
        $result = $this->config->setThrowError('blabla');
        $this->assertInstanceOf('LisDev\Config', $result);
        $result = $this->config->getThrowError();
        $this->assertEquals(true, $result);
    }

    /**
     * Throw Error setter with bool param
     */
    public function testSetThrowErrorNotBoolFalse()
    {
        $result = $this->config->setThrowError('0');
        $this->assertInstanceOf('LisDev\Config', $result);
        $result = $this->config->getThrowError();
        $this->assertEquals(false, $result);
    }

    /**
     * Throw Error getter
     */
    public function testGetThrowError()
    {
        $result = $this->config->getThrowError();
        $this->assertEquals(false, $result);
    }
}
