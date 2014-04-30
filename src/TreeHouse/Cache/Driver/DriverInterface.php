<?php

namespace TreeHouse\Cache\Driver;

interface DriverInterface
{
    /**
     * Checks if the cache has a value for a key.
     *
     * @param string $key A unique key
     *
     * @return boolean Whether the cache has a value for this key
     */
    public function has($key);

    /**
     * Returns the value for a key.
     *
     * @param string $key A unique key
     *
     * @return string The value in the cache
     */
    public function get($key);

    /**
     * Sets a value in the cache.
     *
     * @param string  $key   A unique key
     * @param string  $value The value to cache
     * @param integer $ttl   Time-to-live
     *
     * @return boolean True if the value was set, false otherwise
     */
    public function set($key, $value, $ttl = 0);

    /**
     * Removes the value for a key.
     *
     * @param string $key A unique key
     *
     * @return boolean True if the key was removed, false otherwise
     */
    public function remove($key);

    /**
     * Adds an item to a list, creates the list if necessary.
     *
     * @param string $listName
     * @param string $value
     *
     * @return boolean True if the value was added, false otherwise
     */
    public function addToList($listName, $value);

    /**
     * Gets a list
     *
     * @param string $listName
     *
     * @return array
     */
    public function getList($listName);

    /**
     * Removes an item from a list
     *
     * @param string $listName
     * @param string $value
     *
     * @return boolean True if the value was removed, false otherwise
     */
    public function removeFromList($listName, $value);

    /**
     * Clears the cache
     *
     * @return boolean True if the cache was cleared, false otherwise
     */
    public function clear();
}
