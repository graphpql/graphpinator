<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class InputTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid { field1(arg2: null) { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'Test 123',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldValid { field1(arg2: {name: "foo", innerList: [], innerNotNull: {name: "bar", number: []} }) { name } } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldValid { 
                            field1(arg2: {
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
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldValid { 
                            field1(arg2: {
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
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldValid { 
                            field1(arg2: {
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
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldValid { 
                            field1(arg2: {
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
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: 1; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { 
                        fieldValid { 
                            field1(arg2: {
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
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: SimpleInput = {
                        name: "bar", number: []
                    })
                    {
                        fieldValid { 
                            field1(arg2: {
                                name: "foo", innerList: [$var1, $var1], innerNotNull: $var1 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: SimpleInput) { 
                        fieldValid { 
                            field1(arg2: {
                                name: "foo", innerList: [$var1, $var1], innerNotNull: $var1 
                            }) 
                            { 
                                name 
                            } 
                        } 
                    }',
                    'variables' => (object) ['var1' => (object) ['name' => 'bar', 'number' => []]],
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: [SimpleInput] = [
                        {name: "bar", number: []}
                    ])
                    { 
                        fieldValid { 
                            field1(arg2: {
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
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldValid' => [
                            'field1' => [
                                'name' => 'name: foo; inner: null; innerList: []; innerNotNull: {name: bar; number: []; bool: null; }; ',
                            ],
                        ],
                    ],
                ]),
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
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldValid { field1 { name } } }',
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = 123) { fieldValid { field1 { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int!) { fieldValid { field1 { name } } }',
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid { field1(arg1: $varNonExistent) { name } } }',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Graphpinator\Json $request
     */
    public function testInvalid(\Graphpinator\Json $request) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request);
    }
}
