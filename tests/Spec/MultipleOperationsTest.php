<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class MultipleOperationsTest extends \PHPUnit\Framework\TestCase
{
    public function requestDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg2: null) { name } } }',
                    'operationName' => 'queryName',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { name } } } query secondQueryName { aliasName: field0 { field1 { name } } }',
                    'operationName' => 'queryName',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { name } } } query secondQueryName { aliasName: field0 { field1 { name } } }',
                    'operationName' => 'secondQueryName',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['aliasName' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider requestDataProvider
     * @param string $request
     * @param string $expected
     */
    public function testOperationName(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
    }
}
