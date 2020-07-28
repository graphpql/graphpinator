<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class DirectiveTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @skip(if: true) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @skip(if: false) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @include(if: true) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @include(if: false) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @include(if: false) @skip(if: false) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @include(if: true) @skip(if: true) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @include(if: false) @skip(if: true) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @include(if: true) @skip(if: false) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { ... @include(if: true) { field1 { name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { ... @include(if: false) { field1 { name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { ... namedFragment @include(if: true) } } fragment namedFragment on Abc { field1 { name } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { ... namedFragment @include(if: false) } } fragment namedFragment on Abc { field1 { name } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => []]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @testDirective() { name } } }',
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

    public function testRepeatable() : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        TestSchema::getSchema()->getContainer()->getDirective('testDirective')::$count = 0;

        self::assertSame(
            \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]])->toString(),
            \json_encode($graphpinator->runQuery(
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @testDirective @testDirective @testDirective { name } } }',
                ]),
            ), \JSON_THROW_ON_ERROR, 512),
        );
        self::assertSame(3, TestSchema::getSchema()->getContainer()->getDirective('testDirective')::$count);
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @skip(if: false) @skip(if: false) { name } } }',
                ]),
                \Graphpinator\Exception\Normalizer\DuplicatedDirective::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @include(if: false) @include(if: false) { name } } }',
                ]),
                \Graphpinator\Exception\Normalizer\DuplicatedDirective::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @include(if: false) @testDirective @include(if: false) { name } } }',
                ]),
                \Graphpinator\Exception\Normalizer\DuplicatedDirective::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { ... on Abc @testDirective { field1 { name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\MisplacedDirective::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 @invalidDirective() { field1 { name } } }',
                ]),
                \Graphpinator\Exception\Resolver\InvalidDirectiveResult::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 @testDirective(if: true) { name } } }',
                ]),
                \Graphpinator\Exception\Resolver\UnknownArgument::class,
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
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        $graphpinator->runQuery($request);
    }
}
