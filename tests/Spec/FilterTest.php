<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Infinityloop\Utils\Json;

final class FilterTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(equals: "testValue1") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue1']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(not: true, equals: "testValue1") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue2', 'testValue3']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(contains: "1") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue1']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(not: true, contains: "1") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue2', 'testValue3']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(startsWith: "test") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue1', 'testValue2', 'testValue3']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(not: true, startsWith: "testValue5") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue1', 'testValue2', 'testValue3']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(endsWith: "3") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue3']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(not: true, endsWith: "3") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue1', 'testValue2']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldList @stringWhere(not: true, equals: "testValue1") @stringWhere(not: true, endsWith: "3") }',
                ]),
                Json::fromNative((object) ['data' => ['fieldList' => ['testValue2']]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @stringWhere(field: "data.name", equals: "testValue1") { data { name } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [['data' => ['name' => 'testValue1']]]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @stringWhere(field: "data.name", not: true, equals: "testValue1") { data { name } } }',
                ]),
                Json::fromNative((object) ['data' => [
                    'fieldListFilter' => [
                        ['data' => ['name' => 'testValue2']],
                        ['data' => ['name' => 'testValue3']],
                    ]
                ]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @stringWhere(field: "data.listName.0", startsWith: "testValue") { data { name listName } } }',
                ]),
                Json::fromNative((object) ['data' => [
                    'fieldListFilter' => [
                        ['data' => ['name' => 'testValue1', 'listName' => ['testValue', '1']]],
                        ['data' => ['name' => 'testValue2', 'listName' => ['testValue', '2', 'test1']]],
                        ['data' => ['name' => 'testValue3', 'listName' => ['testValue', '3', 'test1', 'test2']]],
                    ]
                ]]),
            ],
        ];
    }

    public function simpleIntDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListInt @intWhere(equals: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListInt' => [2]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListInt @intWhere(not: true, equals: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListInt' => [1, 3]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListInt @intWhere(greaterThan: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListInt' => [2, 3]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListInt @intWhere(not:true, greaterThan: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListInt' => [1]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListInt @intWhere(lessThan: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListInt' => [1, 2]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListInt @intWhere(not:true, lessThan: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListInt' => [3]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", equals: 99) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [['data' => ['name' => 'testValue2', 'rating' => 99]]]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", equals: 99, not: true) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'rating' => 98]], ['data' => ['name' => 'testValue3', 'rating' => 100]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", lessThan: 99) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'rating' => 98]], ['data' => ['name' => 'testValue2', 'rating' => 99]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", lessThan: 99, not: true) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue3', 'rating' => 100]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", greaterThan: 99, not: true) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'rating' => 98]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", greaterThan: 99) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue2', 'rating' => 99]], ['data' => ['name' => 'testValue3', 'rating' => 100]]
                ]]]),
            ],
        ];
    }

    public function simpleFloatDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFloat @floatWhere(equals: 1.01) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFloat' => [1.01]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFloat @floatWhere(not: true, equals: 1.01) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFloat' => [1.00, 1.02]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFloat @floatWhere(greaterThan: 1.01) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFloat' => [1.01, 1.02]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFloat @floatWhere(not:true, greaterThan: 1.01) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFloat' => [1.00]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFloat @floatWhere(lessThan: 1.00) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFloat' => [1.00]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFloat @floatWhere(not:true, lessThan: 1.00) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFloat' => [1.01, 1.02]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", equals: 1.00) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [['data' => ['name' => 'testValue2', 'coefficient' => 1.00]]]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", equals: 1.00, not: true) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'coefficient' => 0.99]], ['data' => ['name' => 'testValue3', 'coefficient' => 1.01]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", lessThan: 1.00) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'coefficient' => 0.99]], ['data' => ['name' => 'testValue2', 'coefficient' => 1.00]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", lessThan: 1.00, not: true) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue3', 'coefficient' => 1.01]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", greaterThan: 1.00, not: true) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'coefficient' => 0.99]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", greaterThan: 1.00) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue2', 'coefficient' => 1.00]], ['data' => ['name' => 'testValue3', 'coefficient' => 1.01]]
                ]]]),
            ],
        ];
    }

    public function simpleListDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListList @listWhere(minItems: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListList' =>
                    [['testValue11', 'testValue12', 'testValue13'], ['testValue21', 'testValue22']],
                ]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListList @listWhere(minItems: 2, not: true) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListList' =>
                    [['testValue31']],
                ]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListList @listWhere(maxItems: 2) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListList' =>
                    [['testValue21', 'testValue22'], ['testValue31']],
                ]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListList @listWhere(maxItems: 2, not: true) }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListList' =>
                    [['testValue11', 'testValue12', 'testValue13']],
                ]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @listWhere(field: "data.listName", minItems: 3) { data { listName } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['listName' => ['testValue', '2', 'test1']]], ['data' => ['listName' => ['testValue', '3', 'test1', 'test2']]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @listWhere(field: "data.listName", minItems: 3, not: true) { data { listName } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['listName' => ['testValue', '1']]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @listWhere(field: "data.listName", maxItems: 3) { data { listName } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['listName' => ['testValue', '1']]], ['data' => ['listName' => ['testValue', '2', 'test1']]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @listWhere(field: "data.listName", maxItems: 3, not: true) { data { listName } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['listName' => ['testValue', '3', 'test1', 'test2']]],
                ]]]),
            ],
        ];
    }

    public function simpleBoolDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @booleanWhere(field: "data.isReady", equals: true) { data { name isReady } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'isReady' => true]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @booleanWhere(field: "data.isReady", equals: false) { data { name isReady } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue2', 'isReady' => false]], ['data' => ['name' => 'testValue3', 'isReady' => false]],
                ]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @dataProvider simpleIntDataProvider
     * @dataProvider simpleFloatDataProvider
     * @dataProvider simpleListDataProvider
     * @dataProvider simpleBoolDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz @skip(if: false) @skip(if: false) { name } } }',
                ]),
                \Graphpinator\Exception\Normalizer\DuplicatedDirective::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param Json $request
     * @param string $exception
     */
    public function testInvalid(Json $request, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
