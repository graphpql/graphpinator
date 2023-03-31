<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use \Infinityloop\Utils\Json;

final class InputTest extends \PHPUnit\Framework\TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg2: null) { name } } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'Test 123',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbc { fieldXyz(arg2: {name: "foo", innerList: [], innerNotNull: {name: "bar", number: []} }) { name } } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; innerList: []; innerNotNull: {name: bar; number: []; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbc { 
                            fieldXyz(arg2: {
                                name: "foo", innerList: [], innerNotNull: {
                                    name: "bar", number: [123, 456]
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; innerList: []; innerNotNull: {name: bar; number: []; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbc { 
                            fieldXyz(arg2: {
                                name: "foo", inner: null, innerList: [], innerNotNull: {
                                    name: "bar", number: []
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbc { 
                            fieldXyz(arg2: {
                                name: "foo", innerList: [], innerNotNull: {
                                    name: "bar", number: [], bool: null
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbc { 
                            fieldXyz(arg2: {
                                name: "foo", innerList: [], innerNotNull: {
                                    name: "bar", number: [], bool: true
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; innerList: []; innerNotNull: {name: bar; number: []; bool: 1; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { 
                        fieldAbc { 
                            fieldXyz(arg2: {
                                name: "foo", innerList: [{
                                    name: "bar", number: []
                                }, 
                                {
                                    name: "bar", number: []
                                }], 
                                innerNotNull: {
                                    name: "bar", number: []
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; innerList: []; innerNotNull: {name: bar; number: []; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: SimpleInput! = {
                        name: "bar", number: []
                    })
                    {
                        fieldAbc { 
                            fieldXyz(arg2: {
                                name: "foo", innerList: [$var1, $var1], innerNotNull: $var1 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; innerList: []; innerNotNull: {name: bar; number: []; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: SimpleInput!) { 
                        fieldAbc { 
                            fieldXyz(arg2: {
                                name: "foo", innerList: [$var1, $var1], innerNotNull: $var1 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                    'variables' => (object) ['var1' => (object) ['name' => 'bar', 'number' => []]],
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; innerList: []; innerNotNull: {name: bar; number: []; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: [SimpleInput!]! = [
                        {name: "bar", number: []}
                    ])
                    { 
                        fieldAbc { 
                            fieldXyz(arg2: {
                                name: "foo", innerList: $var1, innerNotNull: {
                                    name: "bar", number: []
                                } 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAbc' => [
                            'fieldXyz' => [
                                'name' => 'name: foo; innerList: []; innerNotNull: {name: bar; number: []; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public static function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                ]),
                \Graphpinator\Exception\Value\InvalidValue::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = 123) { fieldAbc { fieldXyz { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
                \Graphpinator\Request\Exception\VariablesNotObject::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int!) { fieldAbc { fieldXyz { name } } }',
                ]),
                \Graphpinator\Exception\Value\ValueCannotBeNull::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: $varNonExistent) { name } } }',
                ]),
                \Graphpinator\Normalizer\Exception\UnknownVariable::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param string $exception
     */
    public function testInvalid(Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
