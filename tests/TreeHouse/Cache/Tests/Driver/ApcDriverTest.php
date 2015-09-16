<?php

namespace TreeHouse\Cache\Tests\Driver;

use TreeHouse\Cache\Driver\ApcDriver;

class ApcDriverTest extends DriverTest
{
    /**
     * @requires extension redis
     */
    protected function setUp()
    {
        if (!extension_loaded('apcu')) {
            $this->markTestSkipped('apcu extension not installed');
        }

        parent::setUp();
    }

    /**
     * @inheritdoc
     */
    protected function getCacheDriver()
    {
        return new ApcDriver();
    }
}
