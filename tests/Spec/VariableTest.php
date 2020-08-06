<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class VariableTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int) { field0 { field1(arg1: $var1) { name } } }',
                    'variables' => ['var1' => 456],
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int) { field0 { field1(arg1: $var1) { name } } }',
                    'variables' => ['var1' => 123],
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int = 456) { field0 { field1(arg1: $var1) { name } } }',
                    'variables' => [],
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int = 123) { field0 { field1(arg1: $var1) { name } } }',
                    'variables' => [],
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int = "123") { field0 { field1 { name } } }',
                    'variables' => [],
                ]),
                \Graphpinator\Exception\Type\InvalidResolvedValue::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int = "123") { field0 { field1 { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
                \Graphpinator\Exception\Type\InvalidResolvedValue::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($var1: Int!) { field0 { field1 { name } } }',
                    'variables' => [],
                ]),
                \Graphpinator\Exception\Type\ExpectedNotNullValue::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg1: $varNonExistent) { name } } }',
                    'variables' => [],
                ]),
                \Graphpinator\Exception\Parser\UnknownVariable::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param string $exception
     */
    public function testInvalid(\Infinityloop\Utils\Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request);
    }
}
