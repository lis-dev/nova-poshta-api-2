<?php

namespace LisDev\Tests;

use ReflectionClass;
use ReflectionException;

abstract class AbstractTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws ReflectionException
     */
    protected function getPrivateMethod($class, $methodName) {
        $class = new ReflectionClass($class);
        $method = $class->getMethod($methodName);

        $method->setAccessible(true);

        return $method;
    }

    protected function getConstants($class)
    {
        $class = new ReflectionClass($class);

        return $class->getConstants();
    }
}