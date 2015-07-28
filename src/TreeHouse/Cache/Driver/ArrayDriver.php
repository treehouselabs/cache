<?php

namespace TreeHouse\Cache\Driver;

class ArrayDriver implements DriverInterface
{
    /**
     * @var array
     */
    protected $storage = [];

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return isset($this->storage[$key]) || array_key_exists($key, $this->storage);

    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->has($key) ? $this->storage[$key] : false;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        $this->storage[$key] = $value;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        unset($this->storage[$key]);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        $this->storage = [];

        return true;
    }

    /**
     * @inheritdoc
     */
    public function addToList($list, $value)
    {
        $this->storage[$list][] = $value;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function getList($listName)
    {
        if (!array_key_exists($listName, $this->storage)) {
            return [];
        }

        return $this->storage[$listName];
    }

    /**
     * @inheritdoc
     */
    public function removeFromList($list, $value)
    {
        if (false !== $key = array_search($value, $this->storage[$list], true)) {
            unset($this->storage[$list][$key]);
        }

        return true;
    }
}
