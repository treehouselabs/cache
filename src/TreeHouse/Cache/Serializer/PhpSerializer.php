<?php

namespace TreeHouse\Cache\Serializer;

class PhpSerializer implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize($value)
    {
        return serialize($value);
    }

    /**
     * @inheritdoc
     */
    public function deserialize($value)
    {
        return unserialize($value);
    }
}
