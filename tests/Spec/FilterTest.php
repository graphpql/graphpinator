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
                        ['data' => ['name' => 'testValue4']],
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
                    ['data' => ['name' => 'testValue1', 'rating' => 98]], ['data' => ['name' => 'testValue3', 'rating' => 100]],
                    ['data' => ['name' => 'testValue4', 'rating' => null]],
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
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", lessThan: 99, orNull: true) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'rating' => 98]], ['data' => ['name' => 'testValue2', 'rating' => 99]],
                    ['data' => ['name' => 'testValue4', 'rating' => null]]
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", lessThan: 99, not: true) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue3', 'rating' => 100]], ['data' => ['name' => 'testValue4', 'rating' => null]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", greaterThan: 99, not: true) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'rating' => 98]], ['data' => ['name' => 'testValue4', 'rating' => null]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.rating", greaterThan: 99, not: true, orNull: true) { data { name rating } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'rating' => 98]],
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
                    ['data' => ['name' => 'testValue1', 'coefficient' => 0.99]], ['data' => ['name' => 'testValue3', 'coefficient' => 1.01]],
                    ['data' => ['name' => 'testValue4', 'coefficient' => null]],
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
                    ['data' => ['name' => 'testValue3', 'coefficient' => 1.01]], ['data' => ['name' => 'testValue4', 'coefficient' => null]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", greaterThan: 1.00, not: true) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'coefficient' => 0.99]], ['data' => ['name' => 'testValue4', 'coefficient' => null]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", greaterThan: 1.00, not: true, orNull: true) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'coefficient' => 0.99]],
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
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.coefficient", greaterThan: 1.00, orNull: true) { data { name coefficient } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue2', 'coefficient' => 1.00]], ['data' => ['name' => 'testValue3', 'coefficient' => 1.01]]
                    , ['data' => ['name' => 'testValue4', 'coefficient' => null]]
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
                    [['testValue31'], null],
                ]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListList @listWhere(minItems: 2, not: true, orNull: true) }',
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
                    [['testValue11', 'testValue12', 'testValue13'], null],
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
                    ['data' => ['listName' => ['testValue', '1']]], ['data' => ['listName' => null]],
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
                    ['data' => ['listName' => ['testValue', '3', 'test1', 'test2']]], ['data' => ['listName' => null]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @listWhere(field: "data.listName", maxItems: 3, not: true, orNull: true) { data { listName } } }',
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
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @booleanWhere(field: "data.isReady", equals: true, orNull: true) { data { name isReady } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue1', 'isReady' => true]], ['data' => ['name' => 'testValue4', 'isReady' => null]],
                ]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @booleanWhere(field: "data.isReady", equals: false, orNull: true) { data { name isReady } } }',
                ]),
                Json::fromNative((object) ['data' => ['fieldListFilter' => [
                    ['data' => ['name' => 'testValue2', 'isReady' => false]], ['data' => ['name' => 'testValue3', 'isReady' => false]],
                    ['data' => ['name' => 'testValue4', 'isReady' => null]],
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
                'Duplicated directive which is not repeatable.',
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @booleanWhere(field: "data.coefficient", equals: false) { data { name coefficient } } }',
                ]),
                \Graphpinator\Exception\Directive\InvalidValueType::class,
                'BooleanWhere directive expects filtered value to be Boolean, got Float.',
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @intWhere(field: "data.coefficient", equals: 1) { data { name coefficient } } }',
                ]),
                \Graphpinator\Exception\Directive\InvalidValueType::class,
                'IntWhere directive expects filtered value to be Int, got Float.',
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @floatWhere(field: "data.isReady", equals: 1.00) { data { name isReady } } }',
                ]),
                \Graphpinator\Exception\Directive\InvalidValueType::class,
                'FloatWhere directive expects filtered value to be Float, got Boolean.',
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @stringWhere(field: "data.rating", equals: "123") { data { name rating } } }',
                ]),
                \Graphpinator\Exception\Directive\InvalidValueType::class,
                'StringWhere directive expects filtered value to be String, got Int.',
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @listWhere(field: "data.rating", maxItems: 3) { data { name rating } } }',
                ]),
                \Graphpinator\Exception\Directive\InvalidValueType::class,
                'ListWhere directive expects filtered value to be List, got Int.',
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @listWhere(field: "data.1", maxItems: 3) { data { listName } } }',
                ]),
                \Graphpinator\Exception\Directive\ExpectedListValue::class,
                'The specified numeric index "1" is only usable for a list, got FilterInner.',
            ],
            [
                Json::fromNative((object) [
                'query' => '{ fieldListFilter @stringWhere(field: "data.listName.5", startsWith: "testValue") { data { name listName } } }',
                ]),
                \Graphpinator\Exception\Directive\InvalidListOffset::class,
                'The specified numeric index "5" is out of range.',
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @booleanWhere(field: "data.invalid", equals: false) { data { name isReady } } }',
                ]),
                \Graphpinator\Exception\Directive\InvalidFieldOffset::class,
                'The specified Field "invalid" doesnt exist in Type "FilterInner"',
            ],
            [
                Json::fromNative((object) [
                    'query' => '{ fieldListFilter @booleanWhere(field: "data.listName.isReady", equals: false) { data { name listName isReady } } }',
                ]),
                \Graphpinator\Exception\Directive\ExpectedTypeValue::class,
                'The specified Field "isReady" doesnt exist in Type "[String]".',
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param Json $request
     * @param string $exception
     * @param string $exceptionMessage
     */
    public function testInvalid(Json $request, string $exception, string $exceptionMessage) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage($exceptionMessage);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
