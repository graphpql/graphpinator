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
                    'query' => 'query queryName { fieldUnion { ... on Xyz { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => new \stdClass()]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz { ... on Xyz { name } } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { ... on Query { fieldUnion { ... on Abc { fieldXyz { ... on Xyz { name } } } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { ... namedFragment } 
                    fragment namedFragment on Query { fieldUnion { ... on Abc { fieldXyz { name } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { ... namedFragment } 
                    fragment namedFragment on Query { fieldUnion { ... on Abc { fieldXyz { ... on Xyz { name } } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
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
                            ... on Abc { 
                                fieldXyz { 
                                    ... innerFragment 
                                }
                            } 
                        } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldFragment {
                            ... interfaceAbcFragment
                        }
                    }
                    fragment interfaceAbcFragment on InterfaceAbc { 
                        name 
                        ... on InterfaceEfg { name number }
                        ... on FragmentTypeB { name number bool }
                        ... on Xyz { name }
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldFragment' => ['name' => 'defaultA']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldFragment {
                            ... interfaceAbcFragment
                        }
                    }
                    fragment interfaceAbcFragment on InterfaceAbc { 
                        name 
                        ... on InterfaceEfg { number }
                        ... on FragmentTypeB { bool }
                        ... on Xyz { name }
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldFragment' => ['name' => 'defaultA']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldFragment {
                            ... interfaceAbcFragment
                        }
                    }
                    fragment interfaceAbcFragment on InterfaceAbc { 
                        name 
                        ... @include(if: true) {
                            ... on InterfaceEfg { name number }
                            ... on FragmentTypeB { name number bool }
                        }
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldFragment' => ['name' => 'defaultA']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldFragment {
                            ... interfaceAbcFragment
                        }
                    }
                    fragment interfaceAbcFragment on InterfaceAbc { 
                        name 
                        ... @include(if: true) {
                            ... on InterfaceEfg { number }
                            ... on FragmentTypeB { bool }
                        }
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldFragment' => ['name' => 'defaultA']]]),
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
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function fieldSelectionMergingDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz { __typename ... on Abc { __typename } } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz { __typename ... on Xyz { __typename name } } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz { ... on Xyz { __typename name } __typename } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz { ... on TestInterface { __typename name } } } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz { ... on Xyz { __typename name } ... on TestInterface { __typename name } } 
                    } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz { ... on TestInterface { __typename name } ... on Xyz { __typename name } } 
                    } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                     fieldXyz { __typename } ... on Abc { fieldXyz { ... on Xyz { __typename name } } } 
                     } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz { __typename ... on Xyz { __typename name } ... on Abc { __typename } } 
                    } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz { name: __typename ... on Xyz { __typename } } 
                    } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Xyz', '__typename' => 'Xyz']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz(arg1: 456) { name } ... on Abc { fieldXyz(arg1: 456) { __typename } } 
                    } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 456', '__typename' => 'Xyz']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz(arg1: 456) { name } ... on Abc { fieldXyz(arg1: 456) { name } } 
                    } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 456']]]]),
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
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
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
                        ...cycleFragment 
                        fieldUnion
                    } 
                    fragment cycleFragment on Query { 
                        ...namedFragment 
                    }',
                ]),
                \Graphpinator\Exception\Normalizer\FragmentCycle::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz { name: __typename ... on Xyz { name } } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldAlias::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz { typename: __typename ... on Xyz { typename: name } } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldAlias::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz(arg1: 456) { name } ... on Abc { fieldXyz(arg1: 123) { name } } } } }',
                ]),
                \Graphpinator\Exception\Normalizer\ConflictingFieldArguments::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz(arg1: 456) { name } ... on Abc { fieldXyz(arg1: [456]) { name } } } } }',
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
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
