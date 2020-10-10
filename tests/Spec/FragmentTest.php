<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class FragmentTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1 { ... on Abc { name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => new \stdClass()]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1 { ... on Xyz { name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { ... on Query { fieldUnion { field1 { ... on Xyz { name } } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { ... namedFragment } 
                    fragment namedFragment on Query { fieldUnion { field1 { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { ... namedFragment } 
                    fragment namedFragment on Query { fieldUnion { field1 { ... on Xyz { name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        ... namedFragment 
                    } 
                    fragment innerFragment on Xyz { 
                        name 
                    } 
                    fragment namedFragment on Query { 
                        fieldUnion { 
                            field1 { 
                                ... innerFragment 
                            } 
                        } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testSimple(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(\Graphpinator\Request::fromJson($request));

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertNull($result->getErrors());
    }

    public function fieldSelectionMergingDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1 { __typename ... on Abc { __typename name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['__typename' => 'Xyz']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1 { __typename ... on Xyz { __typename name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1 { ... on Xyz { __typename name } __typename } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1 { ... on TestInterface { __typename name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { 
                    field1 { ... on Xyz { __typename name } ... on TestInterface { __typename name } } 
                    } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { 
                    field1 { ... on TestInterface { __typename name } ... on Xyz { __typename name } } 
                    } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion {
                     field1 { __typename } ... on Abc { field1 { ... on Xyz { __typename name } } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { 
                    field1 { __typename ... on Xyz { __typename name } ... on Abc { __typename name } } 
                    } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { 
                    field1 { name: __typename ... on Xyz { __typename } } 
                    } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['name' => 'Xyz', '__typename' => 'Xyz']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { 
                    field1(arg1: 456) { name } ... on Abc { field1(arg1: 456) { __typename } } 
                    } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['name' => 'Test 456', '__typename' => 'Xyz']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { 
                    field1(arg1: 456) { name } ... on Abc { field1(arg1: 456) { name } } 
                    } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['field1' => ['name' => 'Test 456']]]]),
            ],
        ];
    }

    /**
     * @dataProvider fieldSelectionMergingDataProvider
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testFieldSelectionMerging(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(\Graphpinator\Request::fromJson($request));

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { ...namedFragment }',
                ]),
                \Graphpinator\Exception\Normalizer\UnknownFragment::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        ...namedFragment 
                    } 
                    fragment namedFragment on Query { 
                        ...cycleFragment fieldUnion 
                    } 
                    fragment cycleFragment on Query { 
                        ...namedFragment 
                    }',
                ]),
                \Graphpinator\Exception\Normalizer\FragmentCycle::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1 { name: __typename ... on Xyz { name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldAlias::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1 { typename: __typename ... on Xyz { typename: name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldAlias::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1(arg1: 456) { name } ... on Abc { field1(arg1: 123) { name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldArguments::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { field1(arg1: 456) { name } ... on Abc { field1(arg1: [456]) { name } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldArguments::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Graphpinator\Json $request
     * @param string $exception
     */
    public function testInvalid(\Graphpinator\Json $request, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(\Graphpinator\Request::fromJson($request));
    }
}
