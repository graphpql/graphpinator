<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Graphpinator\Graphpinator;
use Graphpinator\Normalizer\Exception\UnknownVariable;
use Graphpinator\Request\Exception\VariablesNotObject;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Exception\ValueCannotBeNull;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class InputTest extends TestCase
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

    public static function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                ]),
                InvalidValue::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int = 123) { fieldAbc { fieldXyz { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
                VariablesNotObject::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName ($var1: Int!) { fieldAbc { fieldXyz { name } } }',
                ]),
                ValueCannotBeNull::class,
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: $varNonExistent) { name } } }',
                ]),
                UnknownVariable::class,
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
