<?php

namespace TreeHouse\Cache\Serializer;

class IgbinarySerializer implements SerializerInterface
{
    /**
     * @inheritdoc
     */
    public function serialize($value)
    {
        return igbinary_serialize($value);
    }

    /**
     * @inheritdoc
     */
    public function deserialize($value)
    {
        return igbinary_unserialize($value);
    }
}
