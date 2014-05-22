<?php

namespace TreeHouse\Cache\Tests\Driver;

use TreeHouse\Cache\Driver\RedisDriver;

class RedisDriverTest extends DriverTest
{
    /**
     * @var \Redis
     */
    private $redis;

    /**
     * @requires extension redis
     */
    protected function setUp()
    {
        $this->redis = new \Redis();

        if (false === @$this->redis->connect('127.0.0.1')) {
            unset($this->redis);
            $this->markTestSkipped('Could not connect to Redis instance');
        }

        parent::setUp();
    }

    protected function getCacheDriver()
    {
        return new RedisDriver($this->redis);
    }
}
