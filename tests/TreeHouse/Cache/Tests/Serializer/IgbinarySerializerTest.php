<?php

namespace TreeHouse\Cache\Tests\Serializer;

use TreeHouse\Cache\Serializer\IgbinarySerializer;

class IgbinarySerializerTest extends SerializerTest
{
    protected function getSerializer()
    {
        return new IgbinarySerializer();
    }

    /**
     * @dataProvider getTestData
     */
    public function testSerialize($value, $serialized, $expected = null)
    {
        if (!extension_loaded('igbinary')) {
            $this->markTestSkipped('The igbinary extension must be loaded');
        }

        $serialized = igbinary_serialize($value);

        parent::testSerialize($value, $serialized, $expected);
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            ['foo', null],
            [['foo'], null],
            [false, null],
            [null, null],
            [new \ArrayObject(), null],
            [1234, null],
            [1234.5678, null],
        ];
    }
}
