<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class MultipleOperationsTest extends \PHPUnit\Framework\TestCase
{
    public function testOperationName() : void
    {
        $request = '{"query":"query queryName { field0 { field1(arg2: null) { name } } }","operationName":"queryName"}';

        $graphpinator = new \Graphpinator\Graphpinator(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $result = $graphpinator->runQuery(\Infinityloop\Utils\Json::fromString($request));

        $expected = '{"field0":{"field1":{"name":"Test 123"}}}';

        self::assertSame($expected, \Infinityloop\Utils\Json::fromArray($result->getData())->toString());
    }

    public function testMultipleOperationsWithOperationName() : void
    {
        $request = '{"query": "query queryName { field0 { field1 { name } } } query secondQueryName { aliasName: field0 { field1 { name } } }"' .
            ',"operationName": "queryName"}';

        $graphpinator = new \Graphpinator\Graphpinator(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $result = $graphpinator->runQuery(\Infinityloop\Utils\Json::fromString($request));

        $expected = '{"field0":{"field1":{"name":"Test 123"}}}';

        self::assertSame($expected, \Infinityloop\Utils\Json::fromArray($result->getData())->toString());
    }

    public function testMultipleOperationsWithOperationName2() : void
    {
        $request = '{"query": "query queryName { field0 { field1 { name } } } query secondQueryName { aliasName: field0 { field1 { name } } }",' .
            '"operationName": "secondQueryName"}';

        $graphpinator = new \Graphpinator\Graphpinator(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $result = $graphpinator->runQuery(\Infinityloop\Utils\Json::fromString($request));

        $expected = '{"aliasName":{"field1":{"name":"Test 123"}}}';

        self::assertSame($expected, \Infinityloop\Utils\Json::fromArray($result->getData())->toString());
    }
}
