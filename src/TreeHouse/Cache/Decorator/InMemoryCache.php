<?php

namespace TreeHouse\Cache\Decorator;

use TreeHouse\Cache\CacheInterface;

class InMemoryCache implements CacheInterface
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @var array
     */
    protected $memory;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache  = $cache;
        $this->memory = [];
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return array_key_exists($key, $this->memory) || $this->cache->has($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->memory)) {
            $value = $this->cache->get($key);

            // don't cache false values (meaning non-existing)
            if (false === $value) {
                return $value;
            }

            $this->memory[$key] = $value;
        }

        return $this->memory[$key];
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        $res = $this->cache->set($key, $value, $ttl);

        if ($res === true) {
            $this->memory[$key] = $value;
        } else {
            unset($this->memory[$key]);
        }

        return $res;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $res = $this->cache->remove($key);

        if ($res === true) {
            unset($this->memory[$key]);
        }

        return $res;
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        $res = $this->cache->clear();

        if ($res === true) {
            $this->memory = [];
        }

        return $res;
    }

    /**
     * @inheritdoc
     */
    public function addToList($list, $value)
    {
        // no memory cache for lists
        return $this->cache->addToList($list, $value);
    }

    /**
     * @inheritdoc
     */
    public function getList($listName)
    {
        // no memory cache for lists
        return $this->cache->getList($listName);
    }

    /**
     * @inheritdoc
     */
    public function removeFromList($list, $value)
    {
        // no memory cache for lists
        return $this->cache->removeFromList($list, $value);
    }
}
