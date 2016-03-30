<?php

namespace TreeHouse\Cache\Tests\Driver;

use TreeHouse\Cache\Driver\ApcDriver;

class ApcDriverTest extends DriverTest
{
    /**
     * @requires extension apc
     */
    protected function setUp()
    {
        if (!extension_loaded('apc')) {
            $this->markTestSkipped('apc extension not installed');
        }

        if ((bool) ini_get('apc.enable_cli') === false) {
            $this->markTestSkipped('apc extension not enabled for CLI');
        }

        apc_clear_cache('user');

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
