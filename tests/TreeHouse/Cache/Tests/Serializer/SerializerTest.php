<?php

namespace TreeHouse\Cache\Tests\Serializer;

use TreeHouse\Cache\Serializer\SerializerInterface;

abstract class SerializerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    protected function setUp()
    {
        $this->serializer = $this->getSerializer();
    }

    /**
     * @dataProvider getTestData
     *
     * @param mixed      $value
     * @param string     $serialized
     * @param mixed|null $expected
     */
    public function testSerialize($value, $serialized, $expected = null)
    {
        $assert = (is_object($value)) ? 'assertEquals' : 'assertSame';

        if (null === $expected) {
            $expected = $value;

        }
        $this->$assert($serialized, $this->serializer->serialize($value));
        $this->$assert($expected, $this->serializer->deserialize($serialized));

    }

    /**
     * @return SerializerInterface
     */
    abstract protected function getSerializer();

    /**
     * @return array
     */
    abstract public function getTestData();
}
