<?php

declare(strict_types=1);

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
     */
    public function testSimple(\Graphpinator\Parser\Value\Value $value, string $name): void
    {
        $obj = new \Graphpinator\Parser\Value\NamedValue($value, $name);

        self::assertSame($name, $obj->getName());
        self::assertSame($value, $obj->getValue());
        self::assertSame($value->getRawValue(), $obj->getRawValue());
    }

    public function testApplyVariables() : void
    {
        $variables = new \Graphpinator\Request\VariableValueSet(
            new \Graphpinator\Request\Variable\VariableSet([
                new \Graphpinator\Request\Variable\Variable('var1', \Graphpinator\Type\Scalar\ScalarType::String())
            ]),
            \Infinityloop\Utils\Json::fromArray(['var1' => 'val1']),
        );

        $value = new \Graphpinator\Parser\Value\NamedValue(
            new \Graphpinator\Parser\Value\VariableRef('var1'),
            'argumentName'
        );

        $newValue = $value->applyVariables($variables);

        self::assertSame('val1', $newValue->getRawValue());
    }
}
