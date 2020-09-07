<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit;

final class JsonTest extends \PHPUnit\Framework\TestCase
{
    public function testFromString() : void
    {
        $instance = \Graphpinator\Json::fromString('{"name":"Rosta"}');

        self::assertSame('{"name":"Rosta"}', $instance->toString());
        self::assertEquals((object) ['name' => 'Rosta'], $instance->toObject());
    }

    public function testFromArray() : void
    {
        $instance = \Graphpinator\Json::fromObject((object) ['name' => 'Rosta']);

        self::assertSame('{"name":"Rosta"}', $instance->toString());
        self::assertEquals((object) ['name' => 'Rosta'], $instance->toObject());
    }

    public function testLoadArrayInvalidInput() : void
    {
        $instance = \Graphpinator\Json::fromString('{name: Rosta}');

        self::assertFalse($instance->isValid());
    }

    public function testLoadStringInvalidInput() : void
    {
        $instance = \Graphpinator\Json::fromObject((object) ['name' => " \xB1\x31"]);

        self::assertFalse($instance->isValid());
    }

    public function testIsValid() : void
    {
        self::assertTrue(\Graphpinator\Json::fromString('{"name":"Rosta"}')->isValid());
        self::assertTrue(\Graphpinator\Json::fromObject((object) ['name' => 'Rosta'])->isValid());
    }

    public function testCount() : void
    {
        $instance = \Graphpinator\Json::fromString('{"name":"Rosta"}');

        self::assertSame(1, $instance->count());
        self::assertCount($instance->count(), (array) $instance->toObject());
    }

    public function testOffsetExists() : void
    {
        self::assertTrue(\Graphpinator\Json::fromString('{"name":"Rosta"}')->offsetExists('name'));
    }

    public function testOffsetGet() : void
    {
        self::assertSame('Rosta', \Graphpinator\Json::fromString('{"name":"Rosta"}')->offsetGet('name'));
    }

    public function testOffsetSet() : void
    {
        $instance = \Graphpinator\Json::fromString('{"name":"Rosta"}');
        $instance->offsetSet('car', 'Tesla');

        self::assertSame('{"name":"Rosta","car":"Tesla"}', $instance->toString());
        self::assertTrue($instance->offsetExists('car'));
    }

    public function testOffsetUnset() : void
    {
        $instance = \Graphpinator\Json::fromString('{"name":"Rosta"}');
        $instance->offsetUnset('name');

        self::assertFalse($instance->offsetExists('name'));
    }

    public function testSerialize() : void
    {
        self::assertSame('{"name":"Rosta"}', \Graphpinator\Json::fromString('{"name":"Rosta"}')->serialize());
    }

    public function testGetIterator() : void
    {
        $instance = \Graphpinator\Json::fromString('{"name":"Rosta"}');
        $itr = $instance->getIterator();

        while ($itr->valid()) {
            self::assertSame('name', $itr->key());
            self::assertSame('Rosta', $itr->current());

            $itr->next();
        }
    }

    public function testUnserialize() : void
    {
        $instance = \Graphpinator\Json::fromString('{"name":"Rosta"}');
        $instance->unserialize('{"name":"Rosta"}');

        self::assertSame('{"name":"Rosta"}', $instance->toString());
    }

    public function testMagicToString() : void
    {
        self::assertSame('{"name":"Rosta"}', \Graphpinator\Json::fromObject((object) ['name' => 'Rosta'])->__toString());
    }

    public function testMagicIsset() : void
    {
        self::assertTrue(\Graphpinator\Json::fromObject((object) ['name' => 'Rosta'])->__isset('name'));
    }

    public function testMagicGet() : void
    {
        self::assertSame('Rosta', \Graphpinator\Json::fromObject((object) ['name' => 'Rosta'])->__get('name'));
    }

    public function testMagicSet() : void
    {
        $instance = \Graphpinator\Json::fromString('{"name":"Rosta"}');
        $instance->__set('car', 'Tesla');

        self::assertTrue($instance->offsetExists('car'));
        self::assertTrue($instance->offsetExists('name'));
    }

    public function testMagicUnset() : void
    {
        $instance = \Graphpinator\Json::fromObject((object) ['name' => 'Rosta']);
        $instance->__unset('name');

        self::assertFalse($instance->offsetExists('name'));
    }
}