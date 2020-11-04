<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Source;

final class SourceTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['987123456', ['9','8','7','1','2','3','4','5','6']],
            ['věýéšč$aa', ['v','ě','ý','é','š','č','$','a','a']],
            ['⺴⻕⻨⺮', ['⺴','⻕','⻨','⺮']],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string $source
     * @param array $chars
     */
    public function testSimple(string $source, array $chars) : void
    {
        $source = new \Graphpinator\Source\StringSource($source);

        self::assertSame(\count($chars), $source->getNumberOfChars());

        $index = 0;

        foreach ($source as $key => $val) {
            self::assertSame($index, $key);
            self::assertSame($chars[$index], $val);

            ++$index;
        }
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string $source
     */
    public function testInitialization(string $source) : void
    {
        $source = new \Graphpinator\Source\StringSource($source);

        self::assertTrue($source->valid());
        self::assertSame(0, $source->key());
    }

    public function testInvalid() : void
    {
        $this->expectException(\Graphpinator\Exception\Tokenizer\SourceUnexpectedEnd::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Tokenizer\SourceUnexpectedEnd::MESSAGE);

        $source = new \Graphpinator\Source\StringSource('123');

        $source->next();
        $source->next();
        $source->next();
        $source->getChar();
    }

    public function testLocation() : void
    {
        $source = new \Graphpinator\Source\StringSource('abcd' . \PHP_EOL . 'abcde' . \PHP_EOL . \PHP_EOL . \PHP_EOL . 'abc');
        $lines = [1 => 5, 6, 1, 1, 3];

        for ($line = 1; $line <= 5; ++$line) {
            for ($column = 1; $column <= $lines[$line]; ++$column) {
                $location = $source->getLocation();

                self::assertSame($line, $location->getLine());
                self::assertSame($column, $location->getColumn());

                $source->next();
            }
        }
    }
}
