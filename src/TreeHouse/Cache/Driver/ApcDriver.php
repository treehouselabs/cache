<?php

namespace TreeHouse\Cache\Driver;

class ApcDriver implements DriverInterface
{
    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return apc_exists($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return apc_fetch($key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        $result = apc_store($key, $value, $ttl);

        if (is_array($result)) {
            $result = false;
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        return apc_delete($key);
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        return apc_clear_cache('user');
    }

    /**
     * @inheritdoc
     */
    public function getList($list)
    {
        return apc_fetch($list) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function addToList($listName, $value)
    {
        $list = apc_fetch($listName) ?: [];

        if (!in_array($value, $list)) {
            $list[] = $value;
        }

        return $this->set($listName, $list);
    }

    /**
     * @inheritdoc
     */
    public function removeFromList($listName, $value)
    {
        $list = apc_fetch($listName) ?: [];

        foreach ($list as $key => $listValue) {
            if ($listValue == $value) {
                unset($list[$key]);
            }
        }

        return $this->set($listName, $list);
    }
}
