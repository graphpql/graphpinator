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
        ];
    }

    /**
     * @dataProvider simpleDataProvider
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
