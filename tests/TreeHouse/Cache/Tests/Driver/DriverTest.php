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
        $this->assertFalse($this->driver->has('key'));

        // Test saving a value, checking if it exists, and fetching it back
        $this->assertTrue($this->driver->set('key', 'foo'), '->set() should return true on success');
        $this->assertTrue($this->driver->has('key'));
        $this->assertEquals('foo', $this->driver->get('key'));

        // Test updating the value of a cache entry
        $this->assertTrue($this->driver->set('key', 'value-changed'), '->set() should return true on success');
        $this->assertTrue($this->driver->has('key'));
        $this->assertEquals('value-changed', $this->driver->get('key'));

        // Test removing a value
        $this->assertTrue($this->driver->remove('key'), '->remove() should return true on success');
        $this->assertFalse($this->driver->has('key'));
    }

    public function testListOperations()
    {
        $list = 'testlist';

        // add items to list
        $this->assertTrue($this->driver->addToList($list, 'foo'), '->addToList() should return true on success');
        $this->assertTrue($this->driver->addToList($list, 'bar'), '->addToList() should return true on success');

        $items = $this->driver->getList($list);

        // items should be in list
        $this->assertContains('foo', $items);
        $this->assertContains('bar', $items);

        // remove item from list
        $this->assertTrue($this->driver->removeFromList($list, 'foo'), '->removeFromList() should return true on success');

        $items = $this->driver->getList($list);

        // foo should be removed, but bar not
        $this->assertNotContains('foo', $items);
        $this->assertContains('bar', $items);
    }

    public function testClear()
    {
        $this->assertTrue($this->driver->set('key1', 1));
        $this->assertTrue($this->driver->set('key2', 2));
        $this->assertTrue($this->driver->clear(), '->clear() should return true on success');
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
