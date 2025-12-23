<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MultipleOperationsTest extends TestCase
{
    public static function requestDataProvider() : array
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

    #[DataProvider('requestDataProvider')]
    public function testOperationName(Json $request, Json $expected) : void
    {
        $graphpinator = new Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }
}
