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
     * @var \Redis
     */
    private $redis;

    /**
     * @requires extension redis
     */
    protected function setUp()
    {
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('redis extension not installed');
        }

        $this->redis = new \Redis();

        if (false === @$this->redis->connect('127.0.0.1')) {
            unset($this->redis);
            $this->markTestSkipped('Could not connect to Redis instance');
        }

        parent::setUp();
    }

    /**
     * @inheritdoc
     *
     * @requires extension redis
     */
    protected function _getCacheDriver()
    {
        $driver = new RedisDriver($this->redis);
        $cache  = new Cache($driver, new PhpSerializer());

        return new DoctrineAdapter($cache);
    }
}
