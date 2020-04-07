<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Source;

final class SourceTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider(): array
    {
        return [
            ['987123456', ['9','8','7','1','2','3','4','5','6']],
            ['věýéšč$aa', ['v','ě','ý','é','š','č','$','a','a']],
            ['⺴⻕⻨⺮', ['⺴','⻕','⻨','⺮']],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple(string $source, array $chars) : void
    {
        $index = 0;

        foreach ((new \Graphpinator\Source\StringSource($source)) as $key => $val) {
            self::assertSame($index, $key);
            self::assertSame($chars[$index], $val);

            ++$index;
        }
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testInitialization(string $source) : void
    {
        $source = new \Graphpinator\Source\StringSource($source);

        self::assertTrue($source->valid());
        self::assertSame(0, $source->key());
    }
}
