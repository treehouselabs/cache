<?php

namespace TreeHouse\Cache\Driver;

use Predis\Client;

class PredisDriver implements DriverInterface
{
    /**
     * @var Client
     */
    protected $predis;

    /**
     * @param Client $predis
     */
    public function __construct(Client $predis)
    {
        $this->predis = $predis;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return $this->predis->exists($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return (null !== $value = $this->predis->get($key)) ? $value : false;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        if ($ttl > 0) {
            return $this->predis->setex($key, $ttl, $value);
        }

        return $this->predis->set($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        return $this->predis->del($key) > 0;
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        return $this->predis->flushdb();
    }

    /**
     * @inheritdoc
     */
    public function addToList($list, $value)
    {
        return $this->predis->sadd($list, $value) > 0;
    }

    /**
     * @inheritdoc
     */
    public function getList($listName)
    {
        return $this->predis->smembers($listName);
    }

    /**
     * @inheritdoc
     */
    public function removeFromList($list, $value)
    {
        return $this->predis->srem($list, $value) > 0;
    }
}
