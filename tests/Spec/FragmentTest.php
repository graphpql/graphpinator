<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class FragmentTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { ... on Abc { name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => new \stdClass()]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { ... on Xyz { name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { ... on Query { field0 { field1 { ... on Xyz { name } } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { ... namedFragment } fragment namedFragment on Query { field0 { field1 { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { ... namedFragment } fragment namedFragment on Query { field0 { field1 { ... on Xyz { name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { 
                        ... namedFragment 
                    } 
                    fragment innerFragment on Xyz { 
                        name 
                    } 
                    fragment namedFragment on Query { 
                        field0 { 
                            field1 { 
                                ... innerFragment 
                            } 
                        } 
                    }',
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
        self::assertNull($result->getErrors());
    }

    public function fieldSelectionMergingDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { __typename ... on Abc { __typename name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['__typename' => 'Xyz']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { __typename ... on Xyz { __typename name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { ... on Xyz { __typename name } __typename } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { ... on TestInterface { __typename name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { ... on Xyz { __typename name } ... on TestInterface { __typename name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { ... on TestInterface { __typename name } ... on Xyz { __typename name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { __typename } ... on Abc { field1 { ... on Xyz { __typename name } } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { __typename ... on Xyz { __typename name } ... on Abc { __typename name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { name: __typename ... on Xyz { __typename } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Xyz', '__typename' => 'Xyz']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg1: 456) { name } ... on Abc { field1(arg1: 456) { __typename } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 456', '__typename' => 'Xyz']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg1: 456) { name } ... on Abc { field1(arg1: 456) { name } } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
            ],
        ];
    }

    /**
     * @dataProvider fieldSelectionMergingDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testFieldSelectionMerging(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { ...namedFragment }',
                ]),
                \Graphpinator\Exception\Normalizer\UnknownFragment::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { 
                        ...namedFragment 
                    } 
                    fragment namedFragment on Query { 
                        ...cycleFragment field0 
                    } 
                    fragment cycleFragment on Query { 
                        ...namedFragment 
                    }',
                ]),
                \Graphpinator\Exception\Normalizer\FragmentCycle::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { name: __typename ... on Xyz { name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldAlias::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { typename: __typename ... on Xyz { typename: name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldAlias::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg1: 456) { name } ... on Abc { field1(arg1: 123) { name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldArguments::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg1: 456) { name } ... on Abc { field1(arg1: [456]) { name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldArguments::class,
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
