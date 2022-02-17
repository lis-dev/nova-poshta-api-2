<?php

namespace LisDev\Tests\Unit;

use LisDev\Config;
use LisDev\Constants\Connection;
use LisDev\Constants\Language;
use LisDev\Exceptions\ApplicationException;
use LisDev\Exceptions\ConfigException;
use LisDev\NovaPoshta;
use LisDev\Tests\AbstractTestWithToken;
use ReflectionException;

class NovaPoshtaTest extends AbstractTestWithToken
{
    /**
     * Тут я решил попробовать сделать тесты приватных методов, но потом счёл, что оные лучше покрыть Future-тестами,
     *  а Unit-тестами лучше покрыть непосредственно фабрики
     * @throws ReflectionException
     * @throws ApplicationException|ConfigException
     */
    public function testRequestFile()
    {
        $requestMethod = $this->getPrivateMethod('LisDev\NovaPoshta', 'createRequest');
        $config = new Config();
        $config->setConnectionType(Connection::FILE);
        $np = new NovaPoshta(self::$token, $config);
        $request = $requestMethod->invokeArgs($np, array(self::$token, $config));
        $this->assertInstanceOf('LisDev\Request\FileRequest', $request);
        $token = $request->getToken();
        $this->assertEquals(self::$token, $token);
    }

    /**
     * CustomModel creator
     */
    public function testModel()
    {
        $result = $this->np->model('Model');
        $this->assertInstanceOf('LisDev\Models\CustomModel', $result);
    }

    /**
     * InternetDocument creator
     */
    public function testInternetDocument()
    {
        $result = $this->np->InternetDocument();
        $this->assertInstanceOf('LisDev\Models\InternetDocument', $result);
    }

    /**
     * Counterparty creator
     */
    public function testCounterparty()
    {
        $result = $this->np->Counterparty();
        $this->assertInstanceOf('LisDev\Models\Counterparty', $result);
    }

    /**
     * ContactPerson creator
     */
    public function testContactPerson()
    {
        $result = $this->np->ContactPerson();
        $this->assertInstanceOf('LisDev\Models\ContactPerson', $result);
    }

    /**
     * Address creator
     */
    public function testAddress()
    {
        $result = $this->np->Address();
        $this->assertInstanceOf('LisDev\Models\Address', $result);
    }

    /**
     * Common creator
     */
    public function testCommon()
    {
        $result = $this->np->Common();
        $this->assertInstanceOf('LisDev\Models\Common', $result);
    }
}
