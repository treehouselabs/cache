<?php

namespace TreeHouse\Cache\Tests\Driver;

use Predis\Client;
use TreeHouse\Cache\Driver\PredisDriver;

class PredisDriverTest extends DriverTest
{
    /**
     * @var Client
     */
    private $predis;

    protected function setUp()
    {
        $this->predis = new Client();

        if (true !== $this->predis->ping()) {
            unset($this->predis);
            $this->markTestSkipped('Could not ping Redis instance');
        }

        parent::setUp();
    }

    public function testSetWithTtl()
    {
        $ttl = 60;

        /** @var \PHPUnit_Framework_MockObject_MockObject|Client $predis */
        $predis = $this->getMock(Client::class, ['setex']);
        $predis
            ->expects($this->once())
            ->method('setex')
            ->with('foo', $ttl, 'bar')
        ;

        $driver = new PredisDriver($predis);
        $driver->set('foo', 'bar', $ttl);
    }

    public function testSetWithoutTtl()
    {
        /** @var \PHPUnit_Framework_MockObject_MockObject|Client $predis */
        $predis = $this->getMock(Client::class, ['set', 'setex']);
        $predis
            ->expects($this->once())
            ->method('set')
        ;
        $predis
            ->expects($this->never())
            ->method('setex')
        ;

        $driver = new PredisDriver($predis);
        $driver->set('foo', 'bar');
    }

    protected function getCacheDriver()
    {
        return new PredisDriver($this->predis);
    }
}
