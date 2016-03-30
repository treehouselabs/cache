<?php

namespace TreeHouse\Cache\Driver;

use Redis;

class RedisDriver implements DriverInterface
{
    /**
     * @var Redis
     */
    protected $redis;

    /**
     * @param \Redis $redis Redis instance
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return $this->redis->exists($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        if ($ttl > 0) {
            return $this->redis->setex($key, $ttl, $value);
        }

        return $this->redis->set($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        return $this->redis->del($key) >= 0;
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        return $this->redis->flushdb();
    }

    /**
     * @inheritdoc
     */
    public function addToList($list, $value)
    {
        return $this->redis->sadd($list, $value) >= 0;
    }

    /**
     * @inheritdoc
     */
    public function getList($listName)
    {
        return $this->redis->smembers($listName);
    }

    /**
     * @inheritdoc
     */
    public function removeFromList($list, $value)
    {
        return $this->redis->srem($list, $value) >= 0;
    }
}
