<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Parser\Value;

final class NamedValueTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [new \Graphpinator\Parser\Value\Literal(123), 'name'],
            [new \Graphpinator\Parser\Value\Literal(123.123), 'name'],
            [new \Graphpinator\Parser\Value\Literal('123'), 'name'],
            [new \Graphpinator\Parser\Value\Literal(true), 'name'],
            [new \Graphpinator\Parser\Value\ListVal([]), 'name'],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Graphpinator\Parser\Value\Value $value
     * @param string $name
     */
    public function testSimple(\Graphpinator\Parser\Value\Value $value, string $name) : void
    {
        $obj = new \Graphpinator\Parser\Value\NamedValue($value, $name);

        self::assertSame($name, $obj->getName());
        self::assertSame($value, $obj->getValue());
        self::assertSame($value->getRawValue(), $obj->getRawValue());
    }

    public function testApplyVariables() : void
    {
        $variables = new \Graphpinator\Resolver\VariableValueSet(
            new \Graphpinator\Normalizer\Variable\VariableSet([
                new \Graphpinator\Normalizer\Variable\Variable('var1', \Graphpinator\Type\Container\Container::String()),
            ]),
            \Infinityloop\Utils\Json::fromArray(['var1' => 'val1']),
        );

        $value = new \Graphpinator\Parser\Value\NamedValue(
            new \Graphpinator\Parser\Value\VariableRef('var1'),
            'argumentName',
        );

        $newValue = $value->applyVariables($variables);

        self::assertSame('val1', $newValue->getRawValue());
    }
}
