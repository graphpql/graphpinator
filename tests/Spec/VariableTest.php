<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class VariableTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                'query queryName ($var1: Int) { field0 { field1(arg1: $var1) { name } } }',
                \Infinityloop\Utils\Json::fromArray(['var1' => 456]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
            ],
            [
                'query queryName ($var1: Int) { field0 { field1(arg1: $var1) { name } } }',
                \Infinityloop\Utils\Json::fromArray(['var1' => 123]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName ($var1: Int = 456) { field0 { field1(arg1: $var1) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
            ],
            [
                'query queryName ($var1: Int = 123) { field0 { field1(arg1: $var1) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple(string $request, \Infinityloop\Utils\Json $variables, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request, $variables)),
        );
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                'query queryName ($var1: Int = "123") { field0 { field1 { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
            ],
            [
                'query queryName ($var1: Int = 123) { field0 { field1 { name } } }',
                \Infinityloop\Utils\Json::fromArray(['var1' => '123']),
            ],
            [
                'query queryName ($var1: Int!) { field0 { field1 { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
            ],
            [
                'query queryName { field0 { field1(arg1: $varNonExistent) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(string $request, \Infinityloop\Utils\Json $variables) : void
    {
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request, $variables);
    }
}
