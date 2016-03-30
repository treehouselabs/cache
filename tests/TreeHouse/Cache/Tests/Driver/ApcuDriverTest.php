<?php

namespace TreeHouse\Cache\Tests\Driver;

use TreeHouse\Cache\Driver\ApcuDriver;

class ApcuDriverTest extends DriverTest
{
    /**
     * @requires extension apcu
     */
    protected function setUp()
    {
        if (!extension_loaded('apcu')) {
            $this->markTestSkipped('apcu extension not installed');
        }

        if ((bool) ini_get('apc.enable_cli') === false) {
            $this->markTestSkipped('apcu extension not enabled for CLI');
        }

        apcu_clear_cache();

        parent::setUp();
    }

    /**
     * @inheritdoc
     */
    protected function getCacheDriver()
    {
        return new ApcuDriver();
    }
}
