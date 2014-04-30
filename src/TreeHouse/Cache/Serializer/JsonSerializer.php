<?php

namespace TreeHouse\Cache\Serializer;

class JsonSerializer implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize($value)
    {
        return json_encode($value);
    }

    /**
     * @inheritdoc
     */
    public function deserialize($value)
    {
        return json_decode($value, true);
    }
}
