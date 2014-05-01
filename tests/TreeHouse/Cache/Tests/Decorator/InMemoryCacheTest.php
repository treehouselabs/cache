<?php

namespace TreeHouse\Cache\Tests\Decorator;

use TreeHouse\Cache\CacheInterface;
use TreeHouse\Cache\Decorator\InMemoryCache;

class InMemoryCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var InMemoryCache
     */
    protected $cache;

    /**
     * @var CacheInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $innerCache;

    protected function setUp()
    {
        $this->innerCache = $this
            ->getMockBuilder(CacheInterface::class)
            ->getMockForAbstractClass()
        ;

        $this->cache = new InMemoryCache($this->innerCache);
    }

    public function testGet()
    {
        // inner cache will only be called once for a get
        $this->innerCache
            ->expects($this->once())
            ->method('get')
            ->will($this->returnValue('bar'))
        ;

        $this->assertEquals('bar', $this->cache->get('foo'));
        $this->assertEquals('bar', $this->cache->get('foo'));
    }

    public function testGetNonExisting()
    {
        // non existing the first time
        $this->innerCache
            ->expects($this->at(0))
            ->method('get')
            ->will($this->returnValue(false))
        ;

        // return null the second time (exists, but null)
        $this->innerCache
            ->expects($this->at(1))
            ->method('get')
            ->will($this->returnValue(null))
        ;

        // after the null, get should not be called anymore
        $this->innerCache
            ->expects($this->exactly(2))
            ->method('get')
        ;

        $this->assertSame(false, $this->cache->get('foo'));
        $this->assertNull($this->cache->get('foo'));
        $this->assertNull($this->cache->get('foo'));
    }

    public function testSet()
    {
        // set a value
        $this->innerCache
            ->expects($this->once())
            ->method('set')
            ->with('foo', 'bar', 3600)
            ->will($this->returnValue(true))
        ;

        // the set value should be cached, thus get is never called
        $this->innerCache
            ->expects($this->never())
            ->method('get')
        ;

        $this->assertTrue($this->cache->set('foo', 'bar', 3600));
        $this->assertEquals('bar', $this->cache->get('foo'));
    }

    public function testHas()
    {
        // the has call cannot be cached on itself, only when we've got the value
        $this->innerCache
            ->expects($this->exactly(2))
            ->method('has')
            ->will($this->returnValue(true))
        ;

        $this->assertTrue($this->cache->has('foo'));
        $this->assertTrue($this->cache->has('foo'));
    }

    public function testSetHas()
    {
        // set a value
        $this->innerCache
            ->expects($this->once())
            ->method('set')
            ->will($this->returnValue(true))
        ;

        // the set value should be cached, thus has is never called
        $this->innerCache
            ->expects($this->never())
            ->method('has')
        ;

        $this->cache->set('foo', 'bar');
        $this->assertTrue($this->cache->has('foo'));
    }

    public function testNotHas()
    {
        $this->innerCache
            ->expects($this->exactly(2))
            ->method('has')
            ->will($this->returnValue(false))
        ;

        $this->assertFalse($this->cache->has('foo'));
        $this->assertFalse($this->cache->has('foo'));
    }

    public function testNotSet()
    {
        // set the first time
        $this->innerCache
            ->expects($this->at(0))
            ->method('set')
            ->will($this->returnValue(true))
        ;

        // now set fails, clearing the existing value
        $this->innerCache
            ->expects($this->at(1))
            ->method('set')
            ->will($this->returnValue(false))
        ;

        // expecting a get now
        $this->innerCache
            ->expects($this->once())
            ->method('get')
        ;

        $this->cache->set('foo', 'bar');
        $this->cache->set('foo', 'bar');
        $this->cache->get('foo');
    }

    public function testRemove()
    {
        // memorize a value
        $this->innerCache
            ->expects($this->at(0))
            ->method('set')
            ->will($this->returnValue(true))
        ;

        // remove a value
        $this->innerCache
            ->expects($this->once())
            ->method('remove')
            ->will($this->returnValue(true))
        ;

        // the memorized value should be cleared, thus get is called
        $this->innerCache
            ->expects($this->once())
            ->method('get')
        ;

        $this->cache->set('foo', 'bar');
        $this->cache->remove('foo');
        $this->cache->get('foo');
    }

    public function testNotRemove()
    {
        // memorize a value
        $this->innerCache
            ->expects($this->at(0))
            ->method('set')
            ->will($this->returnValue(true))
        ;

        // remove a value fails
        $this->innerCache
            ->expects($this->once())
            ->method('remove')
            ->will($this->returnValue(false))
        ;

        // the memorized value should not be cleared, thus get is never called
        $this->innerCache
            ->expects($this->never())
            ->method('get')
        ;

        $this->cache->set('foo', 'bar');
        $this->cache->remove('foo');
        $this->cache->get('foo');
    }

    public function testClear()
    {
        // memorize a value
        $this->innerCache
            ->expects($this->at(0))
            ->method('set')
            ->will($this->returnValue(true))
        ;

        // clear the cache
        $this->innerCache
            ->expects($this->once())
            ->method('clear')
            ->will($this->returnValue(true))
        ;

        // the memorized value should be cleared, thus get is called
        $this->innerCache
            ->expects($this->once())
            ->method('get')
        ;

        $this->cache->set('foo', 'bar');
        $this->cache->clear();
        $this->cache->get('foo');
    }

    public function testNotClear()
    {
        // memorize a value
        $this->innerCache
            ->expects($this->at(0))
            ->method('set')
            ->will($this->returnValue(true))
        ;

        // clear fails
        $this->innerCache
            ->expects($this->once())
            ->method('clear')
            ->will($this->returnValue(false))
        ;

        // the memorized value should not be cleared, thus get is never called
        $this->innerCache
            ->expects($this->never())
            ->method('get')
        ;

        $this->cache->set('foo', 'bar');
        $this->cache->clear();
        $this->cache->get('foo');
    }

    public function testList()
    {
        // list calls are not cached
        $this->innerCache->expects($this->exactly(2))->method('addToList');
        $this->cache->addToList('foo', 'bar');
        $this->cache->addToList('foo', 'bar');

        $this->innerCache->expects($this->exactly(2))->method('getList');
        $this->cache->getList('foo');
        $this->cache->getList('foo');

        $this->innerCache->expects($this->exactly(2))->method('removeFromList');
        $this->cache->removeFromList('foo', 'bar');
        $this->cache->removeFromList('foo', 'bar');
    }
}
