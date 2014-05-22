<?php

namespace TreeHouse\Cache\Tests\Driver;

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

    public function testListOperations()
    {
        $list = 'testlist';

        // add items to list
        $this->driver->addToList($list, 'foo');
        $this->driver->addToList($list, 'bar');

        $items = $this->driver->getList($list);

        // items should be in list
        $this->assertContains('foo', $items);
        $this->assertContains('bar', $items);

        // remove item from list
        $this->driver->removeFromList($list, 'foo');

        $items = $this->driver->getList($list);

        // foo should be removed, but bar not
        $this->assertNotContains('foo', $items);
        $this->assertContains('bar', $items);
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
        $this->assertSame(false, $this->driver->get('nonexistent_key'));
    }

    /**
     * @return CacheInterface
     */
    abstract protected function getCacheDriver();
}
