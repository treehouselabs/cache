<?php

namespace TreeHouse\Cache\Tests\Adapter;

use Doctrine\Tests\Common\Cache\CacheTest;
use TreeHouse\Cache\Adapter\DoctrineAdapter;
use TreeHouse\Cache\Cache;
use TreeHouse\Cache\Driver\RedisDriver;
use TreeHouse\Cache\Serializer\PhpSerializer;

class DoctrineAdapterTest extends CacheTest
{
    /**
     * @var RedisDriver
     */
    private $driver;

    /**
     * @requires extension redis
     */
    protected function setUp()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('redis extension not installed');
        }

        $redis = new \Redis();

        if (false === @$redis->connect('127.0.0.1')) {
            $this->markTestSkipped('Could not connect to Redis instance');
        }

        $redis->flushAll();

        $this->driver = new RedisDriver($redis);
        $this->driver->clear();

        parent::setUp();
    }

    /**
     * @inheritdoc
     */
    protected function _getCacheDriver()
    {
        $cache = new Cache($this->driver, new PhpSerializer());

        return new DoctrineAdapter($cache);
    }
}
