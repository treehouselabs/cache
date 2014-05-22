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
     * @inheritdoc
     *
     * @requires extension redis
     */
    protected function _getCacheDriver()
    {
        $this->redis = new \Redis();

        if (false === @$this->redis->connect('127.0.0.1')) {
            unset($this->redis);
            $this->markTestSkipped('Could not connect to Redis instance');
        }

        $driver = new RedisDriver($this->redis);
        $cache  = new Cache($driver, new PhpSerializer());

        return new DoctrineAdapter($cache);
    }
}
