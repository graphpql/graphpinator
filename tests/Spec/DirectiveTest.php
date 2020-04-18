<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class DirectiveTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                'query queryName { field0 { field1 @skip(if: true) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                'query queryName { field0 { field1 @skip(if: false) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { field0 { field1 @include(if: true) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { field0 { field1 @include(if: false) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                'query queryName { field0 { field1 @include(if: false) @skip(if: false) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                'query queryName { field0 { field1 @include(if: true) @skip(if: true) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                'query queryName { field0 { field1 @include(if: false) @skip(if: true) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                'query queryName { field0 { field1 @include(if: true) @skip(if: false) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { field0 { ... @include(if: true) { field1 { name } } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { field0 { ... @include(if: false) { field1 { name } } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                'query queryName { field0 { ... namedFragment @include(if: true) } } fragment namedFragment on Abc { field1 { name } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { field0 { ... namedFragment @include(if: false) } } fragment namedFragment on Abc { field1 { name } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                'query queryName { field0 { field1 @testDirective() { name } } }',
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
            ['query queryName { field0 { field1 @include(if: false) @include(if: false) { name } } }'],
            ['query queryName @include(if: false) { field0 { field1 { name } } }'],
            ['query queryName @testDirective(if: false) { field0 { field1 { name } } }'],
            ['query queryName { field0 @invalidDirective() { field1 { name } } }'],
            ['query queryName { field0 { field1 @testDirective(if: true) { name } } }']
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(string $query) : void
    {
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        $graphpinator->runQuery($query, \Infinityloop\Utils\Json::fromArray([]),);
    }
}
