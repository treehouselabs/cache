<?php

namespace TreeHouse\Cache\Serializer;

interface SerializerInterface
{
    /**
     * @param mixed $value
     *
     * @return string
     */
    public function serialize($value);

    /**
     * @param string $value
     *
     * @return mixed
     */
    public function deserialize($value);
}
