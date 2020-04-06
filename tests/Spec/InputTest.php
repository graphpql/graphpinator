<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class InputTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                'query queryName { field0 { field1(arg2: null) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { field0 { field1(arg2: {name: "foo", innerList: [], innerNotNull: {name: "bar", number: []} }) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; bool: ; ']]]]),
            ],
            [
                'query queryName { field0 { field1(arg2: {name: "foo", innerList: [], innerNotNull: {name: "bar", number: [123, 456]} }) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; 0: 123; 1: 456; bool: ; ']]]]),
            ],
            [
                'query queryName { field0 { field1(arg2: {name: "foo", inner: null, innerList: [], innerNotNull: {name: "bar", number: []} }) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; bool: ; ']]]]),
            ],
            [
                'query queryName { field0 { field1(arg2: {name: "foo", innerList: [], innerNotNull: {name: "bar", number: [], bool: null} }) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; bool: ; ']]]]),
            ],
            [
                'query queryName { field0 { field1(arg2: {name: "foo", innerList: [], innerNotNull: {name: "bar", number: [], bool: true} }) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; bool: 1; ']]]]),
            ],
            [
                'query queryName { field0 { field1(arg2: {name: "foo", innerList: [{name: "bar", number: []}, {name: "bar", number: []}], innerNotNull: {name: "bar", number: []} }) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; bool: ; name: bar; bool: ; name: bar; bool: ; ']]]]),
            ],
            [
                'query queryName ($var1: TestInnerInput = {name: "bar", number: []}){ field0 { field1(arg2: {name: "foo", innerList: [$var1, $var1], innerNotNull: $var1 }) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; bool: ; name: bar; bool: ; name: bar; bool: ; ']]]]),
            ],
            [
                'query queryName ($var1: TestInnerInput){ field0 { field1(arg2: {name: "foo", innerList: [$var1, $var1], innerNotNull: $var1 }) { name } } }',
                \Infinityloop\Utils\Json::fromArray(['var1' => ['name' => 'bar', 'number' => []]]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; bool: ; name: bar; bool: ; name: bar; bool: ; ']]]]),
            ],
            [
                'query queryName ($var1: [TestInnerInput] = [{name: "bar", number: []}]){ field0 { field1(arg2: {name: "foo", innerList: $var1, innerNotNull: {name: "bar", number: []} }) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test input: name: foo; inner: ; name: bar; bool: ; name: bar; bool: ; ']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple(string $request, \Infinityloop\Utils\Json $variables, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getTypeResolver());

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
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName ($var1: Int = 123) { field0 { field1 { name } } }',
                \Infinityloop\Utils\Json::fromArray(['var1' => '123']),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName ($var1: Int!) { field0 { field1 { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { field0 { field1(arg1: $varNonExistent) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(string $request, \Infinityloop\Utils\Json $variables) : void
    {
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getTypeResolver());
        $graphpinator->runQuery($request, $variables);
    }
}
