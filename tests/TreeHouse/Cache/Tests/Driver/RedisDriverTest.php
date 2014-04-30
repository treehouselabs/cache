<?php

namespace TreeHouse\Cache\Tests;

use TreeHouse\Cache\Driver\RedisDriver;

class RedisDriverTest extends DriverTest
{
    /**
     * @var \Redis
     */
    private $redis;

    protected function setUp()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('The ' . __CLASS__ . ' requires the redis extension');
        }

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
