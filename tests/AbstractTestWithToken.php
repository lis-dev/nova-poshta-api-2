<?php

namespace LisDev\Tests;

use LisDev\Exceptions\ApplicationException;
use LisDev\NovaPoshta;

abstract class AbstractTestWithToken extends AbstractTest
{
    /**
     * Token for connection.
     *
     * @see https://my.novaposhta.ua/settings/index#apikeys
     */
    protected static $token = '';

    /**
     * Instance of tested class.
     *
     * @var NovaPoshta
     */
    protected $np;

    /**
     * Set up before class.
     */
    public static function setUpBeforeClass()
    {
        // Disable notices
        error_reporting(E_ALL ^ E_NOTICE);
        !self::$token and self::$token = getenv('NOVA_POSHTA_API2_KEY');
    }

    /**
     * Set up before each test.
     * @throws ApplicationException
     */
    public function setUp()
    {
        // Create new instance
        $this->np = new NovaPoshta(self::$token);
    }
}