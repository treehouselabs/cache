<?php

namespace TreeHouse\Cache\Tests;

use TreeHouse\Cache\Cache;
use TreeHouse\Cache\Driver\DriverInterface;
use TreeHouse\Cache\Serializer\SerializerInterface;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|DriverInterface
     */
    protected $driver;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|SerializerInterface
     */
    protected $serializer;

    protected function setUp()
    {
        /** @var DriverInterface $driver */
        $this->driver = $this
            ->getMockBuilder(DriverInterface::class)
            ->getMockForAbstractClass()
        ;

        /** @var SerializerInterface $serializer */
        $this->serializer = $this
            ->getMockBuilder(SerializerInterface::class)
            ->getMockForAbstractClass()
        ;

        $this->serializer
            ->expects($this->any())
            ->method('serialize')
            ->will($this->returnArgument(0))
        ;

        $this->serializer
            ->expects($this->any())
            ->method('deserialize')
            ->will($this->returnArgument(0))
        ;

        $this->cache = new Cache($this->driver, $this->serializer);
    }

    public function testHas()
    {
        $this->driver
            ->expects($this->once())
            ->method('has')
            ->with('foo')
            ->will($this->returnValue(true));
        ;

        $this->assertTrue($this->cache->has('foo'));
    }

    public function testNotHas()
    {
        $this->driver
            ->expects($this->once())
            ->method('has')
            ->with('foo')
            ->will($this->returnValue(false));
        ;

        $this->assertFalse($this->cache->has('foo'));
    }

    public function testGet()
    {
        $this->driver
            ->expects($this->once())
            ->method('get')
            ->with('foo')
            ->will($this->returnValue('bar'))
        ;

        $this->assertEquals('bar', $this->cache->get('foo'));
    }

    public function testSet()
    {
        $this->driver
            ->expects($this->once())
            ->method('set')
            ->with('foo', 'bar', 123)
        ;

        $this->cache->set('foo', 'bar', 123);
    }

    public function testRemove()
    {
        $this->driver
            ->expects($this->once())
            ->method('remove')
            ->with('foo')
        ;

        $this->cache->remove('foo');
    }

    public function testList()
    {
        $this->driver
            ->expects($this->once())
            ->method('addToList')
            ->with('foo', 'bar')
        ;

        $this->driver
            ->expects($this->once())
            ->method('getList')
            ->with('foo')
        ;

        $this->driver
            ->expects($this->once())
            ->method('removeFromList')
            ->with('foo', 'bar')
        ;

        $this->cache->addToList('foo', 'bar');
        $this->cache->getList('foo');
        $this->cache->removeFromList('foo', 'bar');
    }

    public function testClearCache()
    {
        $this->driver
            ->expects($this->once())
            ->method('clear')
            ->will($this->returnValue(true))
        ;

        $this->assertTrue($this->cache->clear());
    }
}
