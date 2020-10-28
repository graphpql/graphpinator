<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class MultipleOperationsTest extends \PHPUnit\Framework\TestCase
{
    public function requestDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg2: null) { name } } }',
                    'operationName' => 'queryName',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { 
                    fieldXyz { name } } } query secondQueryName { aliasName: fieldAbc { fieldXyz { name } 
                    } }',
                    'operationName' => 'queryName',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { 
                    fieldXyz { name } } } query secondQueryName { aliasName: fieldAbc { fieldXyz { name } 
                    } }',
                    'operationName' => 'secondQueryName',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['aliasName' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider requestDataProvider
     * @param string $request
     * @param string $expected
     */
    public function testOperationName(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $result = $graphpinator->run(\Graphpinator\Request::fromJson($request));

        self::assertSame($expected->toString(), $result->toString());
    }
}
