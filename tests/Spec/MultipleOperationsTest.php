<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use \Infinityloop\Utils\Json;

final class MultipleOperationsTest extends \PHPUnit\Framework\TestCase
{
    public function requestDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg2: null) { name } } }',
                    'operationName' => 'queryName',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { 
                    fieldXyz { name } } } query secondQueryName { aliasName: fieldAbc { fieldXyz { name } 
                    } }',
                    'operationName' => 'queryName',
                ]),
                Json::fromNative((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAbc { 
                    fieldXyz { name } } } query secondQueryName { aliasName: fieldAbc { fieldXyz { name } 
                    } }',
                    'operationName' => 'secondQueryName',
                ]),
                Json::fromNative((object) ['data' => ['aliasName' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider requestDataProvider
     * @param string $request
     * @param string $expected
     */
    public function testOperationName(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }
}
