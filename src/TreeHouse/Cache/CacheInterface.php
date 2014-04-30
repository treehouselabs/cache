<?php

namespace TreeHouse\Cache;

interface CacheInterface
{
    /**
     * Serialize using the best determined method
     */
    const SERIALIZE_AUTO     = 0;

    /**
     * Serialize using igbinary
     */
    const SERIALIZE_IGBINARY = 1;

    /**
     * Serialize using PHP's internal serialize functions
     */
    const SERIALIZE_PHP      = 2;

    /**
     * Serialize using JSON encode/decode
     */
    const SERIALIZE_JSON     = 3;

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
     * @return mixed The value in the cache
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
     * @param mixed  $value
     *
     * @return array The list with the value added to it
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
     * @param mixed  $value
     *
     * @return array The list with the value removed from it
     */
    public function removeFromList($listName, $value);

    /**
     * Clears the cache
     *
     * @return boolean True if the cache was cleared, false otherwise
     */
    public function clear();
}
