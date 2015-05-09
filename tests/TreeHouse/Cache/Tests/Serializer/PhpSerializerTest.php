<?php

namespace TreeHouse\Cache\Tests\Serializer;

use TreeHouse\Cache\Serializer\PhpSerializer;

class PhpSerializerTest extends SerializerTest
{
    protected function getSerializer()
    {
        return new PhpSerializer();
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            ['foo', 's:3:"foo";'],
            [['foo'], 'a:1:{i:0;s:3:"foo";}'],
            [false, 'b:0;'],
            [null, 'N;'],
            [new \stdClass(), 'O:8:"stdClass":0:{}'],
            [1234, 'i:1234;'],
            [1234.5678, 'd:1234.5678;'],
        ];
    }
}
