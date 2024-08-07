<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Graphpinator\Graphpinator;
use Graphpinator\Normalizer\Exception\ConflictingFieldAlias;
use Graphpinator\Normalizer\Exception\ConflictingFieldArguments;
use Graphpinator\Normalizer\Exception\FragmentCycle;
use Graphpinator\Normalizer\Exception\InvalidFragmentType;
use Graphpinator\Normalizer\Exception\UnknownFragment;
use Graphpinator\Request\JsonRequestFactory;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class FragmentTest extends TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... on Xyz { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... @skip(if: true) { __typename } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... @skip(if: false) { __typename } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['__typename' => 'Abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... @include(if: false) { __typename } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... @include(if: true) { __typename } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['__typename' => 'Abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ...namedFragment @skip(if: true) } } fragment namedFragment on Abc { __typename }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ...namedFragment @skip(if: false) } } fragment namedFragment on Abc { __typename }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['__typename' => 'Abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ...namedFragment @include(if: false) } } fragment namedFragment on Abc { __typename }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => new \stdClass()]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ...namedFragment @include(if: true) } } fragment namedFragment on Abc { __typename }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['__typename' => 'Abc']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { fieldXyz { ... on Xyz { name } } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { ... on Query { fieldUnion { ... on Abc { fieldXyz { ... on Xyz { name } } } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { ... namedFragment } 
                    fragment namedFragment on Query { fieldUnion { ... on Abc { fieldXyz { name } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { ... namedFragment } 
                    fragment namedFragment on Query { fieldUnion { ... on Abc { fieldXyz { ... on Xyz { name } } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
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
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldFragment {
                            ... interfaceAbcFragment
                        }
                    }
                    fragment interfaceAbcFragment on InterfaceAbc { 
                        name 
                        ... on InterfaceEfg { name number }
                        ... on FragmentTypeB { name number bool }
                    }',
                ]),
                Json::fromNative((object) ['data' => ['fieldFragment' => ['name' => 'defaultA']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldFragment {
                            ... interfaceAbcFragment
                        }
                    }
                    fragment interfaceAbcFragment on InterfaceAbc { 
                        name 
                        ... on InterfaceEfg { number }
                        ... on FragmentTypeB { bool }
                    }',
                ]),
                Json::fromNative((object) ['data' => ['fieldFragment' => ['name' => 'defaultA']]]),
            ],
            [
                Json::fromNative((object) [
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
                Json::fromNative((object) ['data' => ['fieldFragment' => ['name' => 'defaultA']]]),
            ],
            [
                Json::fromNative((object) [
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
                Json::fromNative((object) ['data' => ['fieldFragment' => ['name' => 'defaultA']]]),
            ],
        ];
    }

    public static function fieldSelectionMergingDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query { fieldUnion { ... on Abc { fieldXyz { __typename ... on Xyz { __typename } } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { fieldUnion { ... on Abc { fieldXyz { __typename ... on Xyz { __typename name } } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { fieldUnion { ... on Abc { fieldXyz { ... on Xyz { __typename name } __typename } } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['__typename' => 'Xyz', 'name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { fieldUnion { ... on Abc { 
                        fieldXyz { name: __typename ... on Xyz { __typename } } 
                    }}}',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Xyz', '__typename' => 'Xyz']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { fieldUnion { 
                        ... on Abc { fieldXyz(arg1: 456) { name } }
                        ... on Abc { fieldXyz(arg1: 456) { name } } 
                    }}',
                ]),
                Json::fromNative((object) ['data' => ['fieldUnion' => ['fieldXyz' => ['name' => 'Test 456']]]]),
            ],
        ];
    }

    public static function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query { ...namedFragment }',
                ]),
                UnknownFragment::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { ...namedFragment } fragment namedFragment on Query { ...secondFragment }',
                ]),
                UnknownFragment::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { ... on TestInterface { __typename } }',
                ]),
                InvalidFragmentType::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { ... namedFragment } fragment namedFragment on TestInterface { __typename }',
                ]),
                InvalidFragmentType::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query { ...namedFragment } 
                    fragment namedFragment on Query { 
                        ...cycleFragment 
                        fieldUnion
                    } 
                    fragment cycleFragment on Query { 
                        ...namedFragment 
                    }',
                ]),
                FragmentCycle::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz { name: __typename ... on Xyz { name } } 
                    } } }',
                ]),
                ConflictingFieldAlias::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz { typename: __typename ... on Xyz { typename: name } } 
                    } } }',
                ]),
                ConflictingFieldAlias::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz(arg1: 456) { name } ... on Abc { fieldXyz(arg1: 123) { name } } 
                    } } }',
                ]),
                ConflictingFieldArguments::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldUnion { ... on Abc { 
                    fieldXyz(arg1: 456) { name } ... on Abc { fieldXyz { name } } 
                    } } }',
                ]),
                ConflictingFieldArguments::class,
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    /**
     * @dataProvider fieldSelectionMergingDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testFieldSelectionMerging(Json $request, Json $expected) : void
    {
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param Json $request
     * @param string $exception
     */
    public function testInvalid(Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new JsonRequestFactory($request));
    }
}
