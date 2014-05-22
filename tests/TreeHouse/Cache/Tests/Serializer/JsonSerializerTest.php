<?php

namespace TreeHouse\Cache\Tests\Serializer;

use TreeHouse\Cache\Serializer\JsonSerializer;

class JsonSerializerTest extends SerializerTest
{
    protected function getSerializer()
    {
        return new JsonSerializer();
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            ['foo', '"foo"'],
            [['foo'], '["foo"]'],
            [false, 'false'],
            [null, 'null'],
            [new \ArrayObject(), '{}', []],
            [new \stdClass(), '{}', []],
            [1234, '1234'],
            [1234.5678, '1234.5678'],
        ];
    }
}
