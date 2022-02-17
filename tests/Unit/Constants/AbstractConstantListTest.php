<?php

namespace LisDev\Tests\Unit\Constants;

use LisDev\Constants\ConstantsListInterface;
use LisDev\Tests\AbstractTest;

abstract class AbstractConstantListTest extends AbstractTest
{
    const TESTED_CLASS = null;

    /**
     * Instance of tested class.
     *
     * @var ConstantsListInterface
     */
    protected $list;

    /**
     * Set up before each test.
     */
    public function setUp()
    {
        // Create new instance
        $class = static::TESTED_CLASS;
        $this->list = new $class();
    }

    /**
     * Compare count of constants with count returned in list method
     */
    public function testListSize()
    {
        $this->assertCount(count($this->getConstants(static::TESTED_CLASS)), $this->list->getList());
    }
}