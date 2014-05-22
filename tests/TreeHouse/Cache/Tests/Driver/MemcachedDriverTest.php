<?php

namespace TreeHouse\Cache\Tests\Driver;

use TreeHouse\Cache\Driver\MemcachedDriver;

class MemcachedDriverTest extends DriverTest
{
    /**
     * @var \Memcached
     */
    private $memcached;

    /**
     * @requires extension memcached
     */
    protected function setUp()
    {
        $this->memcached = new \Memcached();
        $this->memcached->addServer('127.0.0.1', 11211);

        if (@fsockopen('127.0.0.1', 11211) === false) {
            unset($this->memcache);
            $this->markTestSkipped('Could not connect to Memcached instance');
        }

        parent::setUp();
    }

    public function testNoExpire()
    {
        $this->driver->set('noexpire', 'value', 0);
        sleep(1);
        $this->assertTrue($this->driver->has('noexpire'), 'Memcached provider should support no-expire');
    }

    public function testLongLifetime()
    {
        $this->driver->set('key', 'value', 30 * 24 * 3600 + 1);
        $this->assertTrue($this->driver->has('key'), 'Memcached provider should support TTL > 30 days');
    }

    protected function getCacheDriver()
    {
        return new MemcachedDriver($this->memcached);
    }
}
