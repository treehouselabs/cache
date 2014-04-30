<?php

namespace TreeHouse\Cache;

use TreeHouse\Cache\Driver\DriverInterface;
use TreeHouse\Cache\Serializer\SerializerInterface;

class Cache implements CacheInterface
{
    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @param DriverInterface     $driver
     * @param SerializerInterface $serializer
     */
    public function __construct(DriverInterface $driver, SerializerInterface $serializer)
    {
        $this->driver     = $driver;
        $this->serializer = $serializer;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return $this->driver->has($key);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return $this->serializer->deserialize($this->driver->get($key));
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        $value = $this->serializer->serialize($value);

        return $this->driver->set($key, $value);
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        return $this->driver->remove($key);
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        return $this->driver->clear();
    }

    /**
     * @inheritdoc
     */
    public function addToList($list, $value)
    {
        return $this->driver->addToList($list, $value);
    }

    /**
     * @inheritdoc
     */
    public function getList($listName)
    {
        return $this->driver->getList($listName);
    }

    /**
     * @inheritdoc
     */
    public function removeFromList($list, $value)
    {
        return $this->driver->removeFromList($list, $value);
    }

    /**
     * Sets the serialization method to use.
     *
     * @throws \InvalidArgumentException When $strategy is not a defined constant
     *
     * @param integer $strategy One of the Redis::SERIALIZER_* constants
     */
    protected function setSerializeStrategy($strategy)
    {
        switch ($strategy) {
            case self::SERIALIZE_AUTO:
                $this->setAutoSerializeStrategy();
                break;
            case self::SERIALIZE_JSON:
                $this->setJsonSerializeStrategy();
                break;
            case self::SERIALIZE_IGBINARY:
                $this->setIgbinarySerializeStrategy();
                break;
            case self::SERIALIZE_PHP:
                $this->setPhpSerializeStrategy();
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Invalid serialize strategy: %s', json_encode($strategy)));
        }
    }

    /**
     * Sets serialize methods to use PHP's serialization methods
     */
    protected function setAutoSerializeStrategy()
    {
        if (function_exists('igbinary_serialize')) {
            $this->setIgbinarySerializeStrategy();

            return;
        }

        if (function_exists('json_encode')) {
            $this->setJsonSerializeStrategy();

            return;
        }

        $this->setPhpSerializeStrategy();
    }
}
