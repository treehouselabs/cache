<?php

namespace TreeHouse\Cache\Tests\Driver;

use TreeHouse\Cache\Driver\ArrayDriver;

class ArrayDriverTest extends DriverTest
{
    /**
     * @inheritdoc
     */
    protected function getCacheDriver()
    {
        return new ArrayDriver();
    }
}
