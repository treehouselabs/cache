<?php

namespace TreeHouse\Cache\Driver;

use Memcached;

class MemcachedDriver implements DriverInterface
{
    /**
     * @var Memcached
     */
    protected $memcached;

    /**
     * @param Memcached $memcached
     */
    public function __construct(Memcached $memcached)
    {
        $this->memcached = $memcached;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return false !== $this->memcached->get($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->memcached->get($key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        /*
         * If the ttl is more than 30 days, Memcache assumes it's a Unix time instead of
         * an offset from current time. We add the current time to ensure the right time is used.
         *
         * @see http://nl3.php.net/manual/en/memcached.expiration.php
         */
        if ($ttl > 60 * 60 * 24 * 30) {
            $ttl = time() + $ttl;
        }

        return $this->memcached->set($key, $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        return $this->memcached->delete($key);
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        return $this->memcached->flush();
    }

    /**
     * @inheritdoc
     */
    public function addToList($listName, $value)
    {
        $list = $this->memcached->get($listName) ?: array();

        if (!in_array($value, $list)) {
            $list[] = $value;
        }

        return $this->memcached->set($listName, $list);
    }

    /**
     * @inheritdoc
     */
    public function removeFromList($listName, $value)
    {
        $list = $this->memcached->get($listName) ?: array();

        foreach ($list as $key => $listValue) {
            if ($listValue == $value) {
                unset($list[$key]);
            }
        }

        return $this->memcached->set($listName, $list);
    }

    /**
     * @inheritdoc
     */
    public function getList($listName)
    {
        return $this->memcached->get($listName);
    }
}
