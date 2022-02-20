<?php

namespace LisDev\Tests\Unit\Constants;

use LisDev\Tests\AbstractTest;

abstract class AbstractConstantListTest extends AbstractTest
{
    const TESTED_CLASS = null;

    /**
     * Compare count of constants with count returned in list method
     */
    public function testListSize()
    {
        $class = static::TESTED_CLASS;
        $this->assertCount(count($this->getConstants(static::TESTED_CLASS)), $class::getList());
    }
}