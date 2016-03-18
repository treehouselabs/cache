<?php

namespace TreeHouse\Cache\Driver;

class ApcuDriver implements DriverInterface
{
    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return apcu_exists($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return apcu_fetch($key);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        return apcu_store($key, $value, $ttl);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        return apcu_delete($key) || !apcu_exists($key);
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        return apcu_clear_cache();
    }

    /**
     * @inheritdoc
     */
    public function getList($list)
    {
        return apcu_fetch($list) ?: [];
    }

    /**
     * @inheritdoc
     */
    public function addToList($listName, $value)
    {
        $list = apcu_fetch($listName) ?: [];

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
        $list = apcu_fetch($listName) ?: [];

        foreach ($list as $key => $listValue) {
            if ($listValue == $value) {
                unset($list[$key]);
            }
        }

        return $this->set($listName, $list);
    }
}
