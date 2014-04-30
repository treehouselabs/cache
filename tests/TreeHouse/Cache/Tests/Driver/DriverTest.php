<?php

namespace TreeHouse\Cache\Tests;

use TreeHouse\Cache\CacheInterface;
use TreeHouse\Cache\Driver\DriverInterface;

abstract class DriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    protected function setUp()
    {
        $this->driver = $this->getCacheDriver();
    }

    protected function tearDown()
    {
        if ($this->driver instanceof DriverInterface) {
            $this->driver->clear();
        }
    }

    public function testBasicCrudOperations()
    {
        // Test saving a value, checking if it exists, and fetching it back
        $this->assertTrue($this->driver->set('key', 'foo'));
        $this->assertTrue($this->driver->has('key'));
        $this->assertEquals('foo', $this->driver->get('key'));

        // Test updating the value of a cache entry
        $this->assertTrue($this->driver->set('key', 'value-changed'));
        $this->assertTrue($this->driver->has('key'));
        $this->assertEquals('value-changed', $this->driver->get('key'));

        // Test removing a value
        $this->assertTrue($this->driver->remove('key'));
        $this->assertFalse($this->driver->has('key'));
    }

    public function testClear()
    {
        $this->assertTrue($this->driver->set('key1', 1));
        $this->assertTrue($this->driver->set('key2', 2));
        $this->assertTrue($this->driver->clear());
        $this->assertFalse($this->driver->has('key1'));
        $this->assertFalse($this->driver->has('key2'));
    }

    public function testFetchMissShouldReturnFalse()
    {
        $result = $this->driver->get('nonexistent_key');

        $this->assertFalse($result);
        $this->assertNotNull($result);
    }

    /**
     * @return CacheInterface
     */
    abstract protected function getCacheDriver();
}
