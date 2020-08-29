<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class MultipleOperationsTest extends \PHPUnit\Framework\TestCase
{
    public function requestDataProvider() : array
    {
        return [
            [
                '{"query":"query queryName { field0 { field1(arg2: null) { name } } }","operationName":"queryName"}',
                '{"field0":{"field1":{"name":"Test 123"}}}',
            ],
            [
                '{"query": "query queryName { field0 { field1 { name } } } query secondQueryName { aliasName: field0 { field1 { name } } }",' .
                '"operationName": "queryName"}',
                '{"field0":{"field1":{"name":"Test 123"}}}',
            ],
            [
                '{"query": "query queryName { field0 { field1 { name } } } query secondQueryName { aliasName: field0 { field1 { name } } }",' .
                '"operationName": "secondQueryName"}',
                '{"aliasName":{"field1":{"name":"Test 123"}}}',
            ],
        ];
    }

    /**
     * @dataProvider requestDataProvider
     * @param string $request
     * @param string $expected
     */
    public function testOperationName(string $request, string $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $result = $graphpinator->runQuery(\Infinityloop\Utils\Json::fromString($request));

        self::assertSame($expected, \Infinityloop\Utils\Json::fromArray($result->getData())->toString());
    }
}
