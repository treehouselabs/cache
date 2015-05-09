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

    public function testSetWithTtl()
    {
        $ttl = 60;

        /** @var \PHPUnit_Framework_MockObject_MockObject|\Redis $redis */
        $redis = $this->getMock(\Redis::class, ['setex']);
        $redis
            ->expects($this->once())
            ->method('setex')
            ->with('foo', $ttl, 'bar')
        ;

        $driver = new RedisDriver($redis);
        $driver->set('foo', 'bar', $ttl);

    }

    public function testSetWithoutTtl()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|\Redis $redis */
        $redis = $this->getMock(\Redis::class, ['set', 'setex']);
        $redis
            ->expects($this->once())
            ->method('set')
        ;
        $redis
            ->expects($this->never())
            ->method('setex')
        ;

        $driver = new RedisDriver($redis);
        $driver->set('foo', 'bar');
    }

    protected function getCacheDriver()
    {
        return new RedisDriver($this->redis);
    }
}
