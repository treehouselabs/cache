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

    protected function getCacheDriver()
    {
        return new PredisDriver($this->predis);
    }
}
